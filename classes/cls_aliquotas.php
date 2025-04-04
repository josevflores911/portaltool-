<?php
   error_reporting(0);
   error_reporting(E_ALL);
   date_default_timezone_set ('America/Sao_Paulo');

    include_once "cls_gravarlog.php";
    class cls_ntaliquotas extends cls_connect {
        static $cursor = NULL;
        static $curr_filter =NULL;
        static $curr_page = 0;
        static $curr_sort = array();
        static $conn = NULL;
        static $total = 0;
        static $total_filtered = 0;
        static $init = 0;
        static $limit = 20;
        static $cmd=NULL;
        static $id_user = NULL;
        static $totalpages=0;
        static $change_limit=False;
        static $connected = FALSE;

        function __construct($id_user) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = self::getAuth($id_user,$this);
                if (self::$connected) {
                    self::$id_user = $id_user;
                    self::$cmd = "SELECT c.id_servicoxgrupo, c.id_grupo, c.id_servmuni, c.id_servico, d.id_muni, d.Cd_UF, d.Nm_Muni, 
                                         e.Cd_Grupo, e.Te_Grupo, c.dt_ini_vigencia, c.dt_fim_vigencia, 
                                         concat(date_format(c.dt_ini_vigencia, '%d/%m/%Y'),'-',date_format(c.dt_fim_vigencia, '%d/%m/%Y')) as te_vigencia, 
                                         b.cd_servico as cd_servicofederal, b.cd_subgrupo as cd_subgrupofederal, 
                                         right(concat('0',b.cd_servico, ifnull(b.cd_subgrupo,'00')),6) as cod_servfederal, b.te_servico as te_servicofederal, 
                                         a.cd_servico as cd_servicomuni, a.cd_subgrupo as cd_subgrupomuni, 
                                         concat(a.cd_servico, ifnull(a.cd_subgrupo,'00')) as cod_servmuni, 
                                         a.te_servico as te_servicomuni, a.te_resptributaria, c.vl_percIR, c.vl_percPCC, c.vl_percINSS, 
                                         c.vl_percISS, c.vl_percISSBI, a.cs_depara FROM servicosmuni a, servicos b, tbgruposxservicos c, municipios d, tbgrupos e 
                                         WHERE c.id_servmuni = a.id_servmuni AND c.id_servico = b.id_servico AND a.id_servico = b.id_servico 
                                         AND c.id_grupo = e.id_grupo AND a.id_muni = d.id_muni AND e.Cs_Ativo = 'S' AND c.cs_ativo = 'S' AND 
                                         c.dt_ini_vigencia BETWEEN b.dt_inivigencia AND b.dt_fimvigencia AND 
                                         c.dt_fim_vigencia BETWEEN b.dt_inivigencia AND b.dt_fimvigencia";
                    self::$total = $this->getTotalRows();
                    self::$totalpages = self::calcTotalPages();
                }
            }
        }

        /*
            retorna o valor de uma aliquota
        */
        public function rescueValor($id_servicoxgrupo,$tp_aliquota) {
            $field = "vl_perc" . $tp_aliquota;
            $cmd = self::$cmd . " WHERE id_servicoxgrupo=?";
            $result = json_decode($this->dbquery($cmd, $id_servicoxgrupo));
            if ($result->nrecords > 0) {
                $cursor = get_object_vars($result->records[0]);
                $vl_aliquota = $cursor[$field];
                return array("Error"=> "0", "vl_aliquota"=>$vl_aliquota, "Message"=>"Alíquota recuperada com sucesso");
            } else {
                return array("Error" => "404", "vl_aliquota" => Null,"Message"=>"Alíquota não encontrada");
            }
        }
        static function getAuth($id_user, $objConn) {
            $cmd="SELECT cd_tipo,cs_admin FROM vi_usersxareas WHERE id_user=?";

            $result = json_decode($objConn->dbquery($cmd,$id_user));
            if ($result->nrecords > 0) {
                $cursor = $result->records[0];
                $cd_tipo = $cursor->cd_tipo;
                $cs_admin = $cursor->cs_admin;
                if ($cd_tipo == '1' and $cs_admin == 'S') return TRUE;
            }
            return FALSE;
        }

        
        public function getTotalRows() {
           $total=0; 
           if (self::$connected) {
                $cmd = "SELECT COUNT(*) AS total FROM servicosmuni a, servicos b, tbgruposxservicos c, municipios d, tbgrupos e 
                WHERE c.id_servmuni = a.id_servmuni AND c.id_servico = b.id_servico AND a.id_servico = b.id_servico 
                AND c.id_grupo = e.id_grupo AND a.id_muni = d.id_muni AND e.Cs_Ativo = 'S' AND c.cs_ativo = 'S' AND 
                c.dt_ini_vigencia BETWEEN b.dt_inivigencia AND b.dt_fimvigencia AND 
                c.dt_fim_vigencia BETWEEN b.dt_inivigencia AND b.dt_fimvigencia";
                $result = json_decode($this->dbquery($cmd));
                $total = $result->records[0]->total;
            }
            return $total;
        }

        public function setPage($npage) {
            if ($npage > 0 and $npage <= self::$totalpages) {
                if (self::$curr_page != $npage) {
                    self::$curr_page = $npage;
                    return True;
                } else {
                    if (self::$change_limit) {
                        self::$curr_page = $npage;
                        return True;
                    } else {
                        return False;
                    }
                }
            } else {
                return False;
            }
        }

        public function getPage() {
            return self::$curr_page;
        }

        public function getTotalPages() {
            return self::$totalpages;
        }

        static function calcTotalPages() {
            $totalpages = intval(self::$total/self::$limit);
            if ($totalpages == 0) {
                $totalpages = 1;
            } elseif ((self::$total % self::$limit) != 0) {
                $totalpages +=1;
            }
            return $totalpages;
        }
        public function setLimitPage($nrows=20) {
            if ($nrows >= 8 and $nrows <= 50) {
                self::$limit = $nrows;
                self::$totalpages = self::calcTotalPages();
                self::$curr_page=1;
                self::$change_limit=True;
            }
        }
        public function getLimitPage() {
            if (self::$connected) return self::$limit; else return 0;
        }
        
        static function mountFilter($filter_fields) {
            $cmd = self::$cmd;
            if ( !is_null($filter_fields)) {
                if (gettype($filter_fields) == "string") {
                    $cmd .= " AND ((d.Cd_UF LIKE CONCAT('%','$filter_fields','%')) OR 
                    (d.Nm_Muni LIKE CONCAT('%','$filter_fields','%')) OR 
                    (e.Cd_Grupo LIKE CONCAT('%','$filter_fields','%')) OR 
                    (e.Te_Grupo LIKE CONCAT('%','$filter_fields','%')) OR 
                    (c.te_vigencia LIKE CONCAT('%','$filter_fields','%')) OR 
                    (b.cd_servico LIKE CONCAT('%','$filter_fields','%')) OR 
                    (b.te_servico LIKE CONCAT('%','$filter_fields','%')) OR 
                    (a.cd_servico LIKE CONCAT('%','$filter_fields','%')) OR 
                    (a.te_servico LIKE CONCAT('%','$filter_fields','%')) OR 
                    (CAST(c.vl_percIR AS CHAR) LIKE CONCAT('%','$filter_fields','%')) OR 
                    (CAST(c.vl_percPCC AS CHAR) LIKE CONCAT('%','$filter_fields','%')) OR 
                    (CAST(c.vl_percINSS AS CHAR) LIKE CONCAT('%','$filter_fields','%')) OR 
                    (CAST(c.vl_percISS  AS CHAR) LIKE CONCAT('%','$filter_fields','%')) OR 
                    (CAST(c.vl_percISSBI  AS CHAR) LIKE CONCAT('%','$filter_fields','%')))";
                } else {
                    foreach ($filter_fields as $field => $content) {
                        $content = "'" . $content . "'";
                        if ($field == "vl_percIR") {
                            $cmd .= "(CAST($field AS CHAR) LIKE CONCAT('%',$content','%')) OR ";
                        } elseif ($field == "vl_percPCC") {
                            $cmd .= "(CAST($field AS CHAR) LIKE CONCAT('%',$content','%')) OR ";
                        } elseif ($field == "vl_percINSS") {
                            $cmd .= "(CAST($field AS CHAR) LIKE CONCAT('%',$content','%')) OR ";
                        } elseif ($field == "vl_percISS") {
                            $cmd .= "(CAST($field AS CHAR) LIKE CONCAT('%',$content','%')) OR ";
                        } elseif ($field == "vl_percISSBI") {
                            $cmd .= "(CAST($field AS CHAR) LIKE CONCAT('%',$content','%')) OR ";
                        } else {
                            $cmd .= "(a.field LIKE CONCAT('%',$content,'%')) OR ";
                        }
                    }
                }
                $cmd = substr($cmd, 0, strlen($cmd)-3);
            }
            return $cmd;
        }


        public function getTotalRecords($filter_fields=NULL) {
            if (self::$connected) {
                $cmd = self::mountFilter($filter_fields);
                $ipos = stripos($cmd, "FROM");
                $aux = substr($cmd, $ipos);
                $cmd = "SELECT COUNT(1) as total " . $aux;
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $total = ($result->records[0])->total;
                } else {
                    $total = 0;
                } 
            } else {
                $total = 0;
            }
            return array("total"=>$total);
        }

        static function calcOffset($page) {
            $init = intval(($page -1) * self::$limit);
            
            return $init;
        }

      
        /*
            ler primeira página
        */
        public function getFirst($filter_fields=NULL, $vetor_sort=NULL) {
            $cursor = array();
            if (self::$connected) {
                if (self::$curr_page == 1) {
                    $cursor=self::$cursor;
                } else {
                    $cursor = $this->getRows(1,$filter_fields, $vetor_sort);
                }
            }
            return $cursor;
        }
          /*
            ler última página
        */
        public function getLast($filter_fields=NULL, $vetor_sort=NULL) {
            if (self::$connected) {
                if ($filter_fields == NULL) {
                    $npage = self::$totalpages;
                } else {
                    $ntotal = self::$total_filtered;
                    $npage = intval($ntotal / self::$limit);
                    if ($npage = 0) {
                        $npage=1;
                    } elseif (intval($ntotal % self::$limit) != 0) {
                        $npage +=1;
                    }
                }

                return $this->getRows($npage,$filter_fields, $vetor_sort);
            }
        }

        public function getCursor($id_servicoxgrupo=NULL) {
            if (self::$connected) {
                if ($id_servicoxgrupo === NULL) {
                    return self::$cursor;
                } else {
                    $result = NULL;
                    foreach (self::$cursor as $row) {
                        if ($row['id_servicoxgrupo'] === $id_servicoxgrupo) {
                            $result = $row;
                            break;
                        }
                    }
                    return $result;
                }
            } else {
                return NULL;
            }
        }
    
           /*
            parametros: numero da pagina, string com filtro, vetor de sort
            o vetor de sort tem o nome do campo e a ordem de classificação
            onde ASC é ascendente =1 e DESC = descendente = 2
            A rotina não trata se o campo não existir na instrução sql 
            se não existir vai dar erro mesmo, então precisa passar os campos certos
            para que o filtro funcione.
            a orderm é primeiro filtrar e depois ordernar se for o caso
        */
        public function getRows($page=1, $filter_fields=null, $vetor_sort=null) {
            $cmd = self::$cmd;
            $ler_cmd = False;
            $filter = False;
            $cursor = array();
            if (self::setPage($page)) {
                self::$init = self::calcOffset($page);
                $ler_cmd=True;
                if ($filter_fields) {
                    if (gettype($filter_fields) == 'array') {
                        if (count($filter_fields) > 0) {
                            $filter = True;
                        }
                        
                    } else {
                        if (gettype($filter_fields) != 'string') {
                            $filter_fields = strval($filter_fields);
                        }

                        if (strlen($filter_fields) > 0) {
                            $filter=True;
                            $filter_fields = "'" . $filter_fields . "'";
                            self::$curr_filter = $filter_fields;
                        }       
                    }
                }
            } 
            if ($ler_cmd) {
                if ($filter == True) {
                    /* filtra os prestadores */
                    $cmd = self::mountFilter($filter_fields);
                }

                if (! is_null($vetor_sort)) {
                    if (count($vetor_sort) > 0) {
                        $cmd .= " ORDER BY cs_depara DESC,";
                        $order = "";
                        foreach ($vetor_sort as $field_name => $field_value) {
                            if ($field_name == 'dt_vigencia') {
                                $order .= "c.dt_ini_vigencia " . $field_value . ", c.dt_fim_vigencia " . $field_value . ",";
                            } elseif ($field_name == 'cd_servicofederal') {
                                $order .= "cd_servicofederal " . $field_value . ",";
                            } elseif ($field_name == "cd_servicomuni") {
                                $order .= "cd_servicomuni " . $field_value . ",";
                            } else {
                                $order = $field_name . " " . $field_value . ",";
                            }
                        }
                        if (strpos($order, "d.id_muni") === false)  {
                            $cmd .= "d.id_muni,";
                        }
                        $cmd = $cmd . substr($order, 0, strlen($order)-1);
                    } else {
                        $cmd .= " ORDER BY a.cs_depara DESC,d.id_muni, c.dt_ini_vigencia DESC, c.dt_fim_vigencia desc, c.id_grupo, cd_servicofederal";
                    }
                } else {
                    $cmd .= " ORDER BY a.cs_depara DESC,d.id_muni, c.dt_ini_vigencia DESC, c.dt_fim_vigencia desc, c.id_grupo, cd_servicofederal";
                }

                $cmd .= " LIMIT " . self::$limit . " OFFSET " . self::$init;
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $cursor = array();
                    foreach ($result->records as $key => $row) {
                        $cursor[$key] = get_object_vars($row);
                    }

                    self::$cursor = $cursor;

                } else {
                    self::$cursor = NULL;
                    $cursor = NULL;
                }
            } else {
                $cursor = self::$cursor;
            }
            return $cursor;
        }     
    }
    /**
     * Summary of cls_aliquotas
     */
    class cls_aliquotas extends cls_connect {
        static $conn = NULL;
        static $aliquotas_muni = array();
        static $aliquotas_federais = array();
        static $grupos = array();
        static $aliquotas_geral = array();
        static $id_muni = NULL;
        static $connected = FALSE;
        function __construct($id_muni=NULL) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected=TRUE;
                if (! is_null($id_muni)) {
                    self::$id_muni = $id_muni;
                }
            }
        }

        static function hasMuni($id_muni) {
            $retValue=False;
            if (count(self::$aliquotas_muni) > 0) {
                $vmuni = array_keys(self::$aliquotas_muni);
                if (in_array($id_muni,$vmuni)) $retValue = true;
            }
            return $retValue;
        }

        /**
         * Summary of lerAliquotas
         * @param mixed $id_muni
         * @return void
         */
        public function lerAliquotas($id_grupo,$id_servmuni, $dt_ini, $dt_fim) {
            if (self::$connected) {
                $cmd = "SELECT a.vl_percIR, a.vl_percPCC,a.vl_percINSS, a.vl_percISS,a.vl_percISSBI, b.te_resptributaria FROM 
                vi_gruposxservicos a INNER JOIN servicosmuni b ON a.id_servmuni = b.id_servmuni 
                WHERE id_grupo= ? AND b.id_servmuni=? AND dt_ini_vigencia= ? AND dt_fim_vigencia = ?"; 
                $result = json_decode($this->dbquery($cmd, $id_grupo, $id_servmuni, $dt_ini,$dt_fim));
                if ($result->nrecords > 0) {
                    $retcursor = $result->records[0];
                    $retcursor = get_object_vars($retcursor);
                    $cursor = array("Error"=>"0", "vet_aliquotas" => $retcursor);
                } else {
                    $cursor = array("Error"=>"404");
                }
            } else {
                $error = '504';
                $cursor = array("Error" => $error);
            }
            return $cursor;
        }
        /*
            altera o valor corrente do depara
            do município
            verificando se existe primeiro tomador para o município selecionado
            caso não haja, não realiza a alteração.
        */

        public function alterDepara() {
            if (self::$connected) {

            }
        }
      
        public function getLastUpdate($id_muni = NULL) {
            if (self::$connected) {
                if (is_null($id_muni)) {
                    $id_muni = self::$id_muni;
                }
                
                $cmd = "SELECT MAX(dt_ini_vigencia) as dt_ini, MAX(dt_fim_vigencia) as dt_fim FROM vi_gruposxservicos WHERE id_muni=? AND dt_fim_vigencia < NOW()";
                $result = json_decode($this->dbquery($cmd, $id_muni));
                if ($result->nrecords > 0) {
                    $cursor = $result->records[0];
                    $cursor = get_object_vars($cursor);
                    $dt_ini = $cursor['dt_ini'];
                    $dt_fim = $cursor['dt_fim'];

                    $dt_ini = new Datetime($dt_ini);
                    $dt_ini = $dt_ini->format('Y-m-d');

                    $dt_fim = new Datetime($dt_fim);
                    $dt_fim = $dt_fim->format('Y-m-d');
                    return array("Error" => "0", "dt_ini" => strval($dt_ini), "dt_fim" => strval($dt_fim));
                } else {
                    return array("Error" => "404");
                }
            }
            return array("Error" => "504");
        }
        static function getServico($vetor, $cd_servico, $cd_subgrupo, $check_subgrupo) {
            $bachei = false;
            foreach ($vetor as $key => $valiquotas) {
                $cd_servicolido = $valiquotas["cd_servico"];
                $cd_subgrupolido = $valiquotas["cd_subgrupo"];
                if ($check_subgrupo) {
                    if ($cd_servicolido === $cd_servico and $cd_subgrupolido === $cd_subgrupo) {
                        $bachei=true;
                        break;
                    }
                } else {
                    if ($cd_servicolido === $cd_servico) {
                        $bachei=true;
                        break;
                    }
                }
            }
            if ($bachei) {
                return $vetor[$key];
            } else {
                return NULL;
            }
        }

        public function getServicoMuni ($id_muni, $cd_servico, $cd_subgrupo, $check_subgrupo = true) {
            if (count(self::$aliquotas_muni) > 0) {
                $vMuni = array_keys(self::$aliquotas_muni);
                if (in_array($id_muni, $vMuni)) {
                    $aliquotas_muni = self::$aliquotas_muni[$id_muni];
                    return self::getServico($aliquotas_muni, $cd_servico, $cd_subgrupo, $check_subgrupo);
                }
            }
            return NULL;
        }

        public function getServicoFederal ($cd_servico, $cd_subgrupo, $check_subgrupo = true) {
            if (count(self::$aliquotas_federais) > 0) {
               return self::getServico(self::$aliquotas_federais, $cd_servico, $cd_subgrupo, $check_subgrupo);
            }
            return NULL;
        }

        public function gravarServicoFederal($cd_servico, $cd_subgrupo, $te_servico) {
            if (strlen($cd_subgrupo) > 0) {
                $cmd = "SELECT * FROM servicos WHERE cd_servico =? AND cd_subgrupo=?";
                $result = json_decode($this->dbquery($cmd, $cd_servico, $cd_subgrupo));
            } else {
                $cmd = "SELECT * FROM servicos WHERE cd_servico =?";
                $result = json_decode($this->dbquery($cmd, $cd_servico));
            }
            if ($result->nrecords == 0) {
                $cmd = "INSERT INTO servicos (cd_servico, cd_subgrupo, te_servico) VALUES (?,?,?);";
                $result = json_decode($this->dbquery($cmd, $cd_servico, $cd_subgrupo,$te_servico));
                if ($result->error == "0") {
                    $id_servico = $this->getInsertedId();
                } else {
                    $id_servico = NULL;
                }
            } else {
                $cursor = $result->records[0];
                $id_servico = $cursor->id_servico;
                $cmd = "UPDATE servicos SET cd_servico = ?, cd_subgrupo=?, te_servico =? WHERE id_servico=?;";
                $result = json_decode($this->dbquery($cmd, $cd_servico, $cd_subgrupo,$te_servico, $id_servico));
                if ($result->error != "0") {
                    $id_servico = NULL;
                }
            }
            return $id_servico;
        }

        public function gravarServicoMuni($id_muni, $cd_servico, $cd_subgrupo, $te_servico) {
            if (strlen($cd_subgrupo) > 0) {
                $cmd = "SELECT * FROM servicosmunicipais WHERE id_muni=? AND cd_servico =? AND cd_subgrupo=?";
                $result = json_decode($this->dbquery($cmd,$id_muni, $cd_servico, $cd_subgrupo));
            } else {
                $cmd = "SELECT * FROM servicos WHERE id_muni=? AND cd_servico =?";
                $result = json_decode($this->dbquery($cmd, $id_muni, $cd_servico));
            }
            if ($result->nrecords == 0) {
                $cmd = "INSERT INTO servicosmunicipais (id_muni,cd_servico, cd_subgrupo, te_servico) VALUES (?,?,?,?);";
                $result = json_decode($this->dbquery($cmd, $id_muni, $cd_servico, $cd_subgrupo,$te_servico));
                if ($result->error == "0") {
                    $id_servico = $this->getInsertedId();
                } else {
                    $id_servico = NULL;
                }
            } else {
                $cursor = $result->records[0];
                $id_servico = $cursor->id_servmuni;
                $cmd = "UPDATE servicosmunicipais SET id_muni=?,cd_servico = ?, cd_subgrupo=?, te_servico =? WHERE id_servmuni=?;";
                $result = json_decode($this->dbquery($cmd, $id_muni, $cd_servico, $cd_subgrupo,$te_servico, $id_servico));
                if ($result->error != "0") {
                    $id_servico = NULL;
                }
            }
            return $id_servico;
        }

        public function getIdGroupByDescr ($description) {
            $cmd = "SELECT * FROM tbgrupos WHERE Te_Grupo Like %$description%";
            $result = json_decode($this->dbquery($cmd));
            if ($result->nrecords > 0) {
                $cursor = $result->records[0];
                return $cursor->id_grupo;
            } else {
                return NULL;
            }
        }

        public function getIdGroupByCode($code) {
            $cmd = "SELECT * FROM tbgrupos WHERE Cd_Grupo=?";
            $result = json_decode($this->dbquery($cmd,$code));
            if ($result->nrecords > 0) {
                $cursor = $result->records[0];
                return $cursor->id_grupo;
            } else {
                return NULL;
            }
        }

        public function listGroup() {
            if (self::$connected) {
                $cmd = "SELECT * FROM tbgrupos ORDER BY Cd_Grupo;";
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    $ret = array();
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        array_push ($ret, array("id_grupo" => $row["id_grupo"], 
                                                "cd_grupo" => $row["Cd_Grupo"],
                                                "te_grupo" => $row["Te_Grupo"]));
                    }
                    return $ret;
                } else {
                    return array("Error" => "404");
                }
            } else {
                return array("Error" => "504");
            }
        }
        public function getListServicosFederais($id_grupo, $dt_last_ini, $dt_last_fim) {
            $cursor_federal = array();

            if (self::$connected) {
                $cmd = "SELECT id_servico,cd_servico, cd_subgrupo, te_servico FROM vi_gruposxservfederal WHERE";
                $cmd .= " id_grupo= ? AND dt_ini_vigencia=? AND dt_fim_vigencia = ?";
                $cmd .= " ORDER BY id_grupo, concat (right(concat('0',cd_servico),4), ifnull(cd_subgrupo,'00'))";
                $result = json_decode($this->dbquery($cmd, $id_grupo, $dt_last_ini, $dt_last_fim));
                if ($result->nrecords > 0) {
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push($cursor_federal, $row);
                    }
                    $error = "0";
                } else {
                    $error = "404";
                }
            } else {
                $error = "504";
            }
            return array("Error" => $error,
                    "serv_federal" => $cursor_federal);
        } 

        public function getListServicosmuni($id_grupo, $id_muni, $dt_last_ini, $dt_last_fim) {
            $cursor_muni= array();
            if (self::$connected) {
                $cmd = "SELECT a.id_servmuni,a.cd_servicomuni, a.cd_subgrupomuni, a.te_servicomuni,a.vl_percIR, 
                a.vl_percPCC,a.vl_percINSS, a.vl_percISS,a.vl_percISSBI, b.te_resptributaria FROM vi_gruposxservicos a INNER JOIN servicosmuni b 
                ON a.id_servmuni = b.id_servmuni WHERE id_grupo= ? AND b.id_muni=? AND dt_ini_vigencia= ? AND dt_fim_vigencia = ? 
                ORDER BY id_grupo, concat (right(concat('0',cd_servicofederal),4), ifnull(cd_subgrupofederal,'00'))";
                $result = json_decode($this->dbquery($cmd, $id_grupo, $id_muni,$dt_last_ini, $dt_last_fim));
                if ($result->nrecords > 0) {
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push($cursor_muni, $row);
                    }
                    $error = "0";
                } else {
                    $error = "304";
                }
            } else {
                $error = "504";
            }
            return array("Error" => $error,
                    "serv_muni" => $cursor_muni);
        } 

    
        public function update_impostofederal($id_user, $tp_aliquota, $vl_aliquota,$dt_ini_vigencia, 
                                                        $dt_fim_vigencia,$id_grupo,$id_servico) {
            if (self::$connected) {
                if ($tp_aliquota == "IR" or $tp_aliquota == "PCC" or $tp_aliquota == "INSS") {
                    // verifica se existe o registro
                    $cmd = "SELECT b.id_servicoxgrupo,b.id_muni,b.id_grupo,a.id_servico,b.dt_ini_vigencia,b.dt_fim_vigencia,a.cd_servico,
                    a.cd_subgrupo,a.te_servico,b.vl_percIR,b.vl_percISS,b.vl_percINSS,b.cs_ativo
                    FROM servicos a, tbgruposxservicos b WHERE a.id_servico = b.id_servico AND
                    a.dt_inivigencia = b.dt_ini_vigencia AND
                    a.dt_fimvigencia = b.dt_fim_vigencia AND
                    b.cs_ativo='S' AND
                    b.id_grupo=? AND
                    a.id_servico=? AND
                    b.dt_ini_vigencia=? AND
                    b.dt_fim_vigencia=?
                    group by b.id_grupo, a.id_servico, b.dt_ini_vigencia, b.dt_fim_vigencia";
                    $result = json_decode($this->dbquery($cmd, $id_grupo, $id_servico, $dt_ini_vigencia, $dt_fim_vigencia));
                    if ($result->nrecords > 0) {
                        $field = "vl_perc" .strtoupper($tp_aliquota);
                        $cursor = get_object_vars($result->records[0]);
                        $id_servicoxgrupo = $cursor["id_servicoxgrupo"];
                        $id_muni = $cursor["id_muni"];
                        $old_value=$cursor[$field];
                        
                        $cmd = "UPDATE tbgruposxservicos SET $field=? WHERE id_servicoxgrupo=?";
                        $result = json_decode($this->dbquery($cmd, $vl_aliquota, $id_servicoxgrupo));
                        if ($result->error == '0') {

                            // gravar log
                            $id_oper='4';
                            $nm_table = "tbgruposxservicos";
                            $te_descricao = "Atualização da alíquota ($tp_aliquota) do município $id_muni do valor ($old_value) para ($vl_aliquota)";
                            $olog = new cls_gravarlog($id_user, $id_oper, NULL,$nm_table, $te_descricao);
                            $result = $olog->gravar_log();
                            if ($result) {
                                $ret=array("Error" => '0');
                            } else {
                                $ret = array("Error" => '404');
                            }
                        } else {
                            $ret = array("Error" => $result->error);
                        }
                    } else {
                        $ret = array("Error" => "404");
                    }
                } else {
                    $ret = array("Error" => "404");
                }
            } else {
                $ret = array("Error" => "504");
            }
            return $ret;
        }
        public function update_impostomunicipal($id_user, $tp_aliquota, $vl_aliquota,$dt_ini_vigencia, 
                                                $dt_fim_vigencia,$id_grupo,$id_servico, $id_servmuni) {
            if (self::$connected) {
                if ($tp_aliquota == "ISS" or $tp_aliquota == "ISSBI" ) {
                    // verifica se existe o registro
                    $cmd = "SELECT b.id_servicoxgrupo,b.id_muni,b.id_grupo,a.id_servico,b.dt_ini_vigencia,b.dt_fim_vigencia,a.cd_servico,
                    a.cd_subgrupo,a.te_servico,b.vl_percIR,b.vl_percISS,b.vl_percINSS,b.cs_ativo
                    FROM servicosmuni a, tbgruposxservicos b WHERE a.id_servmuni = b.id_servmuni AND
                    a.dt_inivigencia = b.dt_ini_vigencia AND
                    a.dt_fimvigencia = b.dt_fim_vigencia AND
                    b.cs_ativo='S' AND
                    b.id_grupo=? AND
                    b.id_servico=? AND
                    b.id_servmuni = ? AND 
                    b.dt_ini_vigencia=? AND
                    b.dt_fim_vigencia=?
                    group by b.id_grupo, b.id_servico, b.id_servmuni b.dt_ini_vigencia, b.dt_fim_vigencia";
                    $result = json_decode($this->dbquery($cmd, $id_grupo, $id_servico, $id_servmuni, $dt_ini_vigencia, $dt_fim_vigencia));
                    if ($result->nrecords > 0) {
                        $field = "vl_perc" .strtoupper($tp_aliquota);
                        $cursor = get_object_vars($result->records[0]);
                        $id_servicoxgrupo = $cursor["id_servicoxgrupo"];
                        $id_muni = $cursor["id_muni"];
                        $old_value=$cursor[$field];
                        $cmd = "UPDATE tbgruposxservicos SET $field=? WHERE id_servicoxgrupo=?";
                        $result = json_decode($this->dbquery($cmd, $vl_aliquota, $id_servicoxgrupo));
                        if ($result->error == '0') {
                            // gravar log
                            $id_oper='4';
                            $nm_table = "tbgruposxservicos";
                            $te_descricao = "Atualização da alíquota ($tp_aliquota) do município $id_muni do valor ($old_value) para ($vl_aliquota)";
                            $olog = new cls_gravarlog($id_user, $id_oper, NULL,$nm_table, $te_descricao);
                            $result = $olog->gravar_log();
                            if ($result) {
                                $ret=array("Error" => '0');
                            } else {
                                $ret = array("Error" => '404');
                            }
                        } else {
                            $ret = array("Error" => $result->error);
                        }
                    } else {
                        $ret = array("Error" => "404");
                    }
                } else {
                    $ret = array("Error" => "404");
                }
            } else {
                $ret = array("Error" => "504");
            }
            return $ret;
        }

        public function update_aliquotas($id_user, $id_servicoxgrupo, $tp_aliquota, $vl_aliquota) {
            if (self::$connected) {
                $field = "vl_perc" . strtoupper($tp_aliquota);
                $cmd = "SELECT Nm_Muni, $field  FROM vi_gruposxservicos WHERE id_servicoxgrupo=?";
                $result = json_decode($this->dbquery($cmd, $id_servicoxgrupo));
                if ($result->nrecords > 0) {
                    $cursor = $result->records[0];
                    $cursor = get_object_vars($cursor);
                    $Nm_Muni = $cursor['Nm_Muni'];
                    $old_value = $cursor[$field];
                    
                    $id_oper='4';
                    $nm_table = "tbgruposxservicos";
                    $te_descricao = "Atualização da alíquota ($tp_aliquota) do município $Nm_Muni do valor ($old_value) para ($vl_aliquota)";
                    $cmd = "UPDATE tbgruposxservicos SET $field=? WHERE id_servicoxgrupo= ?";
                    $result = json_decode($this->dbquery($cmd, $vl_aliquota, $id_servicoxgrupo));
                    if ($result->error == '0') {
                        $mensagem = "Alíquota ($tp_aliquota) atualizada com sucesso";
                        $Error = '0';
                        // gravar log
                        $olog = new cls_gravarlog($id_user, $id_oper, NULL,$nm_table, $te_descricao);
                        $resp = $olog->gravar_log();
                    } else {
                        $verro = explode("-", $result->error);
                        $Error = $verro[0];
                        $mensagem = $verro[1];
                    }
                } else {
                    $Error = "404";
                    $mensagem = "Registro não encontrado!";
                }
            } else {
                $Error = "504";
                $mensagem = "Servidor não conectado";
            }
            return array("Error" => $Error, "mensagem" => $mensagem);
        }
        function update_descricao($id_user, $id_servicoxgrupo, $tp_descricao, $te_descricao) {
            if (self::$connected) {
                if ($tp_descricao == 1) {
                    $cmd = "SELECT b.id_servico, a.te_servico FROM servicos a, tbgruposxservicos b WHERE a.id_servico = b.id_servico AND id_servicoxgrupo =  ?";
                } else {
                    $cmd = "SELECT b.id_servmuni, a.te_servico FROM servicosmuni a, tbgruposxservicos b WHERE a.id_servmuni = b.id_servmuni AND id_servicoxgrupo =  ?";
                }
                $result = json_decode($this->dbquery($cmd, $id_servicoxgrupo));
                if ($result->nrecords > 0) {
                    if ($tp_descricao == 1) {
                        $id_servico = ($result->records[0])->id_servico;
                        $nm_table="servicos";
                        $field="id_servico";
                    } else {
                        $id_servico = ($result->records[0])->id_servmuni;
                        $nm_table="servicosmuni";
                        $field="id_servmuni";
                    }
                    $cmd="UPDATE `" . $nm_table . "` SET `te_servico`=? WHERE `" . $field . "`=?";
                    $result = json_decode($this->dbquery($cmd, $te_descricao, $id_servico));
                    if ($result->error == '0') {
                        $mensagem = "Descrição do " . ($tp_descricao == 1) ? "Serviço federal" : "Serviço municipal" . " atualizada com sucesso";
                        $Error = '0';
                        $id_oper='4';
                        // gravar log
                        $olog = new cls_gravarlog($id_user, $id_oper, NULL,$nm_table, $te_descricao);
                        $resp = $olog->gravar_log();
                    } else {
                        $Error="305";
                        $mensagem = "Não atualizaou registro";
                    }
                } else {
                    $Error="404";
                    $mensagem = "Registro não encontrado";

                }
            } else {
                $Error = "504";
                $mensagem = "Servidor não conectado";
            }
            return array("Error" => $Error, "mensagem" => $mensagem);
        }
    }
?>