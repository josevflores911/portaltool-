<?php
    error_reporting(0);
    error_reporting(E_ALL);
    require_once "cls_connect.php";
    class cls_tbagencias extends cls_connect {
        protected static $oConn = NULL;
        protected static $Error = '0';
        protected static $Message = '';
        static $cmd = "";
        static $id_user;
        static $tp_user;
        static $id_muni;
        static $id_agencia;
        static $cd_compet;
        
        protected static $init=0;
        protected static $total=0;
        protected static $limit=20;
        protected static $totalpages=0;
        protected static $total_filtered=0;
        protected static $curr_page = 1;
        protected static $change_limit = FALSE;
        protected static $connected = FALSE;
        protected static $cursor=NULL;
        protected static $curr_filter = NULL;

        function __construct($id_agencia,$id_user =NULL, $tp_user=NULL) {
            parent::__construct();
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = TRUE;
                self::$id_user = $id_user;
                self::$tp_user = $tp_user;
                self::$id_agencia = $id_agencia;
            }
        }

        public function getAnalista($cd_tipo, $cs_responsavel='N') {
            $vDados = array();
            if (self::$connected) {
                $sql = "SELECT distinct 
                        a.id_agencia, 
                        a.id_agenciaxmunicipio, 
                        b.id_user, 
                        c.nm_user, 
                        c.cd_currposition,
                        b.cs_responsavel
                        FROM vi_tbagenciasxmunicipios a JOIN tbusersxmunicipios b ON a.id_agenciaxmunicipio = b.id_agenciaxmunicipio
                        inner join tbusers c on c.id_user = b.id_user
                        where a.id_agencia=? AND c.cd_currposition=? AND b.cs_responsavel=?";
                $result = json_decode($this->dbquery($sql,self::$id_agencia, $cd_tipo, $cs_responsavel));
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        array_push($vDados, $row);
                    }
                    self::$Error = '0';
                    self::$Message = 'Analistas encontrados';                    
                } else {
                    self::$Error = '404';
                    self::$Message = 'Analistas não encontrados';
                }
            } else {
                self::$Error = '504';
                self::$Message = 'Base não conectada no banco de dados';
            }
            return array("Error" => self::$Error, "Message" => self::$Message, 'Data' => $vDados );
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
            return self::$limit;
        }
        
        public function getTotalRecords() {
            if (in_array(self::$tp_user, array('Gestor', 'Administradr', 'Sistema'))) {
                $cmd = "SELECT COUNT(*) AS total_records FROM tbacessos_completos";
            } else {
                $cmd = "SELECT COUNT(*) AS total_records FROM tbacessos_completos WHERE id_user=" . self::$id_user . " AND ";
                $cmd .= "tp_user = '" . self::$tp_user . "'";
            }
            $result = json_decode($this->dbquery($cmd));
            if ($result->nrecords == 1) {
                $cursor = $result->records[0];
                self::$total = $cursor->total_records;
                self::$totalpages = self::calcTotalPages();
            } else {
                self::$total = 0;
                self::$totalpages = 0;
            }
            return self::$total;
        }
        static function CountArray($array) {
            $count = 0;
            try {
                if (is_array($array) || $array instanceof Countable) {
                    $count = count($array);
                } elseif (is_array($array)) {
                    $count = count(array_keys($array));
                }
            } catch (\Exception $e) {
                try {
                    $aux = json_decode($array, true);
                    if (is_array($aux) || $aux instanceof Countable) {
                        $count = count($aux);
                    } elseif (is_array($aux)) {
                        $count = count(array_keys($aux));
                    }
                } catch (\Exception $e) {
                    $count = 0;
                }
            }
            return $count;
        }

        public function getTotalFiltered($filter_fields) {
            if (in_array(self::$tp_user, array('Gestor', 'Administradr', 'Sistema'))) {
                $cmd = "SELECT COUNT(*) AS total_records FROM tbacessos_completos WHERE ";
            } else {
                $cmd = "SELECT COUNT(*) AS total_records FROM tbacessos_completos WHERE ";
            }

            if (self::CountArray($filter_fields)> 0) {
                foreach ($filter_fields as $field => $content) {
                    if (gettype($content) === 'string') {
                        $cmd .= "$field = '$content' AND ";
                    } elseif (gettype($content) === 'integer') {
                        $cmd .= "$field = $content AND ";
                    }
                }
                $cmd = substr($cmd, 0, strlen($cmd) - 5);
                $check = substr($cmd, stripos($cmd, "WHERE")+6);
                if (strlen(trim($check)) > 0) {
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords == 1) {
                        $cursor = $result->records[0];
                        self::$total_filtered = $cursor->total_records;
                        self::$totalpages = self::calcTotalPages();
                    } else {
                        self::$total_filtered = 0;
                        self::$totalpages = 0;
                    }
                } else {
                    self::$total_filtered = 0;
                    self::$totalpages = 0;
                }
            }
            return self::$total_filtered;
        }
        
        static function getTotalRows($obj) {
            $cmd = self::$cmd;
            $cmd = str_replace("*", " count(*) as total ", $cmd);
            $result = json_decode($obj->dbquery($cmd));
            if ($result->nrecords == 1) {
                $cursor = $result->records[0];
                return $cursor->total;
            }
        }

        public function getCursor($id=NULL) {
            if (self::$connected) {
                if ($id === NULL) {
                    return self::$cursor;
                } else {
                    $result = NULL;
                    foreach (self::$cursor as $row) {
                        if ($row['id_muni'] === $id) {
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
        static function calcOffset($page) {
            $init = intval(($page -1) * self::$limit);
            
            return $init;
        }

        
        static function DateCompareAsc($elem1, $elem2) {
            $datetime1 = strtotime($elem1['DH']);
            $datetime2 = strtotime($elem2['DH']);
            return $datetime1 - $datetime2;
        }

        static function DateCompareDesc($elem1, $elem2) {
            $datetime1 = strtotime($elem1['DH']);
            $datetime2 = strtotime($elem2['DH']);
            return $datetime2 - $datetime1;
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

        public function getRows($page=1, $filter_fields=null, $vetor_sort=null) {
            $ler_cmd = False;
            $filter = False;
            $cursor = array();
            if($page == 1) {
                $init= 0;
            } else {
                $init = ($page -1) * self::$limit;
            }
            if (self::CountArray($filter_fields) > 0) {
                $filter = True;
            } 
            if (in_array(self::$tp_user, array('Gestor', 'Administradr', 'Sistema'))) {
                $cmd = "SELECT * FROM tbacessos_completos WHERE ";
            } else {
                $cmd = "SELECT * FROM tbacessos_completos WHERE id_user=" . self::$id_user . " AND ";
                $cmd .= "tp_user = '" . self::$tp_user . "' AND ";
            }
            if ($filter == True) {
                foreach ($filter_fields as $field => $content) {
                    if (gettype($content) === 'string') {
                        $cmd .= " $field = '$content' AND";
                    } elseif (gettype($content) === 'integer') {
                        $cmd .= " $field = $content AND ";
                    }
                }
                $cmd = substr($cmd, 0, strlen($cmd) - 4);
            } else {
                $cmd= substr($cmd, 0, strlen($cmd) - 5);
            }

            if (gettype($vetor_sort) == "array") {
                $ncount = self::CountArray($vetor_sort);

                if ($ncount > 0) {
                    if (stripos($cmd,"ORDER BY") > 0) {
                        $cmd = str_replace("ORDER BY", "", $cmd);
                    }
                    $cmd .= " ORDER BY ";
                
                    foreach ($vetor_sort as $field_name => $field_value) {
                            $cmd .= $field_name . " " . $field_value . ",";
                    }
                    $check = substr($cmd, stripos("ORDER BY", $cmd)+8);
                    $check = rtrim($check);
                    if (strlen($check) == 0) {
                        $cmd = substr($cmd, 0, stripos("ORDER BY", $cmd)-1);
                    } else {
                        $cmd = substr($cmd, 0, strlen($cmd)-1);
                    }
                } 
            }
            /*
                acerta o limite
            */
            $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;

            $result = json_decode($this->dbquery($cmd));
            if ($result->nrecords > 0) {
                $cursor = array();
                foreach ($result->records as $key => $row) {
                    $cursor[$key] = get_object_vars($row);
                }

                if ($filter) {
                    self::$total_filtered = $this->getTotalFiltered($cmd);
                } else {
                    self::$total_filtered =0;
                }
                self::$cursor = $cursor;

            } else {
                self::$cursor = NULL;
            }
            return $cursor;
        }
    }
?>