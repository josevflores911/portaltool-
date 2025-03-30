<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once ("cls_connect.php");

   class cls_usuarios extends cls_connect {
        static $cursor = NULL;
        static $curr_filter =NULL;
        static $Error = '0';
        static $message='';
        static $curr_page = 0;
        static $curr_sort = array();
        static $conn = NULL;
        static $total = 0;
        static $total_filtered = 0;
        static $init = 1;
        static $limit = 20;
        static $cmd=NULL;
        static $id_user = NULL;
        static $cs_sistema = False;
        static $totalpages=0;
        static $cid_notas=NULL;
        static $change_limit=False;
        static $connected = FALSE;

         function __construct($id_user,$tp_user=NULL) {
             parent::__construct();
             self::$conn = parent::$conn;
             if (self::$conn) {
                self::$connected = TRUE;
                self::$id_user = $id_user;
                if (! is_null($tp_user)) {
                    self::$cs_sistema = in_array($tp_user, array('Sistema', 'Administrador', 'Gestor'));
                } else {
                    self::$cs_sistema = $this->isAdmin($id_user);
                }
                if (self::$cs_sistema) {
                    $cmd = "SELECT * FROM tbusers";
                    $result = json_decode($this->dbquery($cmd));
                } else {
                    $cmd = "SELECT * FROM tbusers WHERE id_user=?";
                    $result = json_decode($this->dbquery($cmd, $id_user));
                }
                self::$cursor = array();
                if ($result->nrecords > 0) {
                    self::$cmd = $cmd;
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push(self::$cursor, $row);
                    }
                 } else {
                    self::$Error='404';
                    self::$message = 'Nenhum registro encontrado.';
                 }
             } else {
                self::$Error='500';
                self::$message = 'Não foi possível conectar ao banco de dados.';
             }
        }
        public function isAdmin($id_user) {
            $cmd = "SELECT cd_currposition FROM tbusers WHERE id_user=?";
            $result = json_decode($this->dbquery($cmd, $id_user));
            if ($result->nrecords > 0) {
                $cs_admin = ($result->records[0])->cd_currposition;
                
                return in_array($cs_admin, array('Administrador', 'Gestor', 'Sistema'));
            } else {
                return FALSE;
            }
        }
        static function getUserRows($obj) {
            $ntotal = 0;
            if (self::$connected) {
                if (self::$cs_sistema) {
                    $cmd = "SELECT COUNT(*) AS ntotal FROM vi_usuarios_lst";
                    $result = json_decode($obj->dbquery($cmd));
                    $ntotal = ($result->records[0])->ntotal;
                } else {
                    $cmd = "SELECT cs_admin, te_tipo FROM vi_usuarios_lst WHERE id_usuario=?";
                    $result = json_decode($obj->dbquery($cmd, self::$id_user));
                    if ($result->nrecords > 0) {
                        $cs_admin = ($result->records[0])->cs_admin;
                        if ($cs_admin == 'S') {
                            $cmd = "SELECT id_tomador, id_prestador FROM vi_userxcnpj WHERE id_user=?";
                            $result = json_decode($obj->dbquery($cmd, self::$id_user));
                            if ($result->nrecords > 0) {
                                $vet_result = array();
                                foreach ($result->records as $row) {
                                    $row = get_object_vars($row);
                                    $id_prestador = $row['id_prestador'];
                                    $id_tomador = $row['id_tomador'];
                                    array_push($vet_result, array('id_prestador' => $id_prestador, "id_tomador" => $id_tomador));
                                }
                                $ntotal = 0;
                                foreach ($vet_result as $row) {
                                    $id_prestador = $row['id_prestador'];
                                    $id_tomador = $row['id_tomador'];
                                    $cmd = "SELECT * FROM vi_userxcnpj WHERE id_prestador=? AND id_tomador=?";
                                    $result = json_decode($obj->dbquery($cmd, $id_prestador, $id_tomador));
                                    if ($result->nrecords > 0) {
                                        $ntotal += $result->nrecords;
                                    }
                                }
                            }
                        } else {
                            $ntotal = 1;
                        }
                    }
                }
            }
            return $ntotal;
        }
        public function delCursor() {
            if (self::$connected) {
                $nome = self::$cursor['nome'];
                $nm_table = "users";
                $cmd = "DELETE FROM users WHERE id=?";
                $result = json_decode($this->dbquery($cmd, self::$id_user));
                if ($result->error == '0') {
                    // gravar log
                    $oper = 5; // exclusão
                    $descricao = "Exclusão do usuário (". $nome. ")\n";
                    $nm_table = "users";

                    $cmd = "INSERT INTO log (id_user, id_oper,Nm_Tabela,Te_Descricao) VALUES (?,?,?,?);";
                    $result = json_decode($this->dbquery($cmd,self::$id_user, $oper, $nm_table, $descricao));
                    if ($result->error == '0') {
                        return array("Error" => "0");
                    } else {
                        return array("Error" => "405");
                    }
                } else {
                    return array("Error" => "404");
                }
            } else {
                return array("Error" => "504");
            }
        }
 
        public function getTipoUser($id_user) {
            $result = NULL;
            if (self::$connected) {
                $cmd = "SELECT cd_currposition FROM tbusers WHERE id_user=?";
                $resp = json_decode($this->dbquery($cmd, $id_user));
                if ($resp->nrecords > 0) {
                    $cd_tipo = ($resp->records[0])->cd_currposition;
                    self::$Error='0';
                    self::$message = "Lido tipo usuário";
                    $result = array("Error" => self::$Error, "Message" => self::$message, "tp_user" => $cd_tipo);
                    
                } else {
                    self::$Error = '404';
                    self::$message = 'Nenhum registro encontrado.';
                    $result = array("Error" => self::$Error, "Message" => self::$message, "tp_user" => NULL);
                }
            } else {
                self::$Error = '504';
                self::$message = 'Não foi possível conectar ao banco de dados.';
                $result = array("Error" => self::$Error, "Message" => self::$message, "tp_user" => NULL);
            }
            return $result;
        }

        public function getCursor($id_user = NULL) {
            $result = array();
            $bachei = False;
                    
            if (self::$connected) {
                if (is_null($id_user)) {
                    $result =self::$cursor;
                    $bachei = count($result) > 0;
                } else {
                    
                    if (count(self::$cursor) > 0) {
                        $result = array();
                        foreach (self::$cursor as $row) {
                            if ($row['id_user'] == $id_user) {
                                array_push($result, $row);
                                $bachei = True;
                            }
                        }
                    } 
                }
                if ($bachei) {
                    self::$Error='0';
                    self::$message = "Lido cursor de usuarios";
                } else {
                    self::$Error = '404';
                    self::$message = 'Nenhum registro encontrado.';
                }
            } else {
                self::$Error = '504';
                self::$message = 'Não foi possível conectar ao banco de dados.';
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $result);
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
            if (self::$connected) {
                return self::$total;
            }
        }

        public function getNivel($id_user = NULL) {
            $cs_sistema = NULL;
            $cs_admin = NULL;
            $id_area = NULL;
            $cs_conferente1 = NULL;
            if (self::$connected) {
                $result = $this->getCursor($id_user);
                if ($result['Error'] == '0') {
                    $data = $result['Data'];
                    $cs_sistema = $data['cs_sistema'];
                    $cs_admin = $data['cs_admin'];
                    $cs_conferente1 = $data['cs_conferente1'];
                    $id_area = $data['id_area'];
                }
                
            }
            return array("sistema" => $cs_sistema, "cs_admin" => $cs_admin, "id_area" => $id_area, 'conferente'=> $cs_conferente1);
        }
        
        static function getTotalRows($obj) {
            $cmd = self::$cmd;
            $cmd = str_replace("*", " count(*) as total ", $cmd);
            $result = json_decode($obj->dbquery($cmd));
            if ($result->nrecords == 1) {
                $cursor = $result->records[0];
                return $cursor->total;
            }
            return 0;
        }

        public function getTotalFiltered($cmd=NULL) {
            if (self::$connected) {
                if ($cmd == NULL) {
                    return self::$total_filtered;
                } else {
                    $ix = stripos($cmd, "LIMIT");
                    $cmd = substr($cmd, 0, $ix);
                    $cmd = str_replace("*", " count(*) as total ", $cmd);
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords == 1) {
                        $cursor = $result->records[0];
                        return $cursor->total;
                    } else {
                        return 0;
                    }
                }
            }
        }

        static function calcOffset($page) {
            $init = intval(($page -1) * self::$limit);
            return $init;
        }

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
            $cmd = self::$cmd;
            $ler_cmd = False;
            $filter = False;
            $cursor = array();
            if (self::setPage($page)) {
                self::$init = self::calcOffset($page);
                $ler_cmd=True;
                if ($filter_fields) {
                    if (gettype($filter_fields) == 'array') {
                        $filter = True;
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
                    
                    if (gettype($filter_fields) != "array") {
                        $cmd = "SELECT * FROM vi_usersxareas WHERE";
                        $cmd .= " nome LIKE CONCAT('%', '$filter_fields', '%') OR";
                        $cmd .= " nm_area LIKE CONCAT('%', '$filter_fields', '%') OR";
                        $cmd .= " nu_telefone LIKE CONCAT('%', '$filter_fields', '%') OR";
                        $cmd .= " nu_celular LIKE CONCAT('%', '$filter_fields', '%') OR";
                        $cmd .= " email LIKE CONCAT('%', '$filter_fields', '%')";
                    } else {
                        $cmd = "SELECT * FROM vi_usersxareas WHERE ";
                        $content="";
                        foreach ($filter_fields as $field => $value) {
                            $content .= " $field LIKE CONCAT('%', '$value', '%')) OR";
                        }
                        $cmd = substr($cmd, 0, strlen($cmd)-3);
                    }
                }

                if ($vetor_sort) {
                    if (count($vetor_sort) > 0) {
                        if (stripos($cmd,"ORDER BY") > 0) {
                            $cmd = str_replace("ORDER BY", "", $cmd);
                        }
                        $cmd .= " ORDER BY ";
                        foreach ($vetor_sort as $field_name => $field_value) {
                            $cmd .= $field_name . " " . $field_value . ",";
                        }
                        $cmd = substr($cmd, 0, strlen($cmd)-1);
                    }
                }

                $cmd .= " LIMIT " . self::$limit . " OFFSET " . self::$init;
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords >0) {
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
                    $cursor = NULL;
                }
            } else {
                $cursor = self::$cursor;
            }
            return $cursor;
        }    

   }
?>