<?php
error_reporting(E_ALL);

include "cls_connect.php";

class cls_viewusers extends cls_connect{
    protected static $id_user;
    protected static $type_users;
    protected static $cs_admin;
    protected static $records = array();
    function __construct($id_user = NULL) {
        parent::__construct();
        if ($id_user !== NULL) {
            self::$id_user = $id_user;
            $niveis = $this->getTypeUser($id_user);
            self::$cs_admin = $this->getCs_Admin($id_user);
            self::$type_users = $niveis;
           if ($niveis == "BR") {
                $cmd = "SELECT id_user, id_prestador, id_tomador, nome, id_area, nm_area,email, niveis, CNPJP, RSP, cs_admin,cs_conferente1,cs_deletado FROM vi_users ORDER BY id_user";
                $result = json_decode($this->dbquery($cmd));
            } else {
                if (self::$cs_admin == "S") {
                    /*  
                        pegar os usuários gerentes e funcionários
                    */
                    $cmd = "SELECT SUBSTR(CNPJP, 1,8) AS raiz FROM vi_users WHERE id_user = $id_user GROUP BY raiz";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        $cursor = $result->records;
                        $vraiz = array();
                        foreach ($cursor as $row) {
                            array_push($vraiz, "'" . $row->raiz . "'");
                        }
                        $is_empty = array_filter($vraiz, 'strlen') == [];
                        if (! $is_empty) {
                            $cnpj_list = implode(',', $vraiz);
                            $cmd = "SELECT id_user, id_prestador, id_tomador, nome, id_area, nm_area,email, niveis, CNPJP, RSP, cs_admin,cs_conferente1,cs_deletado FROM vi_users 
                            WHERE SUBSTRING(CNPJP,1,8) IN (" . $cnpj_list . ") AND niveis = '" . self::$type_users ."' ORDER BY id_user, id_prestador";
                            $result = json_decode($this->dbquery($cmd));
                        }
                    } 
                } else {
                    $cmd = "SELECT id_user, id_prestador, id_tomador, nome, id_area, nm_area,email, niveis, CNPJP, RSP, cs_admin,cs_conferente1,cs_deletado FROM vi_users WHERE id_user = $id_user ORDER BY RSP";
                    $result = json_decode($this->dbquery($cmd));
                }
            }
           
            self::$records = $result->records;
        } 
    }

    function getUserPhtoto($id_user, $mime = true) {
        $cmd = "SELECT photo_user FROM users WHERE id = $id_user;";
        $conn = $this->getConn();
        $stmt = $conn->prepare($cmd);
        $result = $stmt->execute();
        $img = NULL;
        if ($result) {
            $rows =  $stmt->get_result();
            $cursor = $rows->fetch_array();
            if (count($cursor) > 0) {
                $img = base64_decode(stripslashes($cursor[0]));
                if ($img) {
                    if ($mime) {
                        $fp = fopen("php://memory", "w+b");
                        fwrite($fp, $img);
                        $mime = mime_content_type($fp);
                        fclose($fp);
                        $img = "data:" . $mime . ";base64," . $cursor[0];
                        
                    }
                }
            }
        }
        $stmt->close();
        return $img;
    }

    function get_progress($id_user) {
        $cmd = "SELECT COUNT(*) AS total FROM cnpjxusers WHERE id_user=$id_user";
        $result =json_decode($this->dbquery($cmd));
        $nrecords = 0;
        if ($result->error == '0') {
            $cursor = $result->records;
            $nrecords = $cursor[0]->total;
        }
        return $nrecords;
    }
    
    /*
        rotina para gravar os cnpj na tabela cnpjxusers
        
     */

    function write_cnpj($id_user, $lst_tomadores, $lst_prestadores) {
        $path = dirname(__FILE__);
        $path = str_replace('classes', "", $path);
        # verifica se existe usuário, se existir deletar un
        $cmd = "SELECT count(*) as total FROM cnpjxusers WHERE id_user=$id_user";
        $result = json_decode($this->dbquery($cmd));
        $nrecords = 0;
        $bgravar=false;
        if ($result->error == '0') {
            $cursor = $result->records;
            $total = $cursor[0]->total;
            $nivel = $this->getTypeUser($id_user);
            $cs_admin = $this->getCs_Admin($id_user);
            $lst_tomadores = explode(',', $lst_tomadores);
            $lst_prestadores = explode(',', $lst_prestadores);
            
            if ($nivel == 'BR') {
                
                if ($cs_admin == 'S') {
                    if ($total > 0) {
                        $cmd = "DELETE FROM cnpjxusers WHERE id_user=$id_user";
                        $result = json_decode($this->dbquery($cmd));
                    } 
                    if ($result->error == '0') {
                        $cmd = "INSERT INTO cnpjxusers (id_user) VALUES ($id_user);";
                        $result = json_decode($this->dbquery($cmd));
                        $nrecords =$result->nrecords;
                        
                        return $nrecords;
                    }
                } else {
                    
                    if (count($lst_prestadores) == 0 and count($lst_tomadores) == 0) {
                        return $nrecords;
                    } else {
                        $bgravar = true;
                    }
                }
            } elseif ($nivel == 'BRP1') {
                if (count($lst_prestadores) == 0 and count($lst_tomadores) == 0) {
                    if ($total > 0) { 
                        $nrecords = $total;
                        $bgravar = false;
                    } else {
                        $bgravar = true;
                    }
                } else {
                    $bgravar = true;
                }
            } elseif ($nivel == 'FN') {
                if (count($lst_prestadores) == 0 and count($lst_tomadores) == 0) {
                    if ($total > 0) {
                        $nrecords = $total;
                        $bgravar = false;
                    } else {
                        $bgravar =true;
                    }
                } else {
                    $bgravar = true;
                }
            }
            
        }
        if ($bgravar) {
            if ($total > 0) {
                $cmd = "DELETE FROM cnpjxusers WHERE id_user=$id_user";
                $result = json_decode($this->dbquery($cmd));
            } 
            set_time_limit(0);

            foreach ($lst_tomadores as $id_tomador) {
                if (intval($id_tomador) == 0) continue;
                foreach ($lst_prestadores as $id_prestador) {
                    if (intval($id_prestador) == 0) continue;
                    
                    $cmd = "INSERT INTO cnpjxusers (id_user, id_prestador, id_tomador) VALUES (?,?,?)";
                    $result = json_decode($this->dbquery($cmd, $id_user,intval($id_prestador), intval($id_tomador)));
                    if ($result->error != '0') {
                        break;
                    }
                    $nrecords +=1;
                    file_put_contents($path . "id_user_" . $id_user . ".txt", strval($nrecords),FILE_USE_INCLUDE_PATH);
                }
                if ($result->error != '0') {
                    break;
                }
            }
            set_time_limit(1200);
        }
        return $nrecords;
    }

    function update_user($id_user,$data_img, $nome, $email, $senha, $id_area, $nivel, $cs_admin, $cs_conferente) {
        $path = dirname(__FILE__);
        $path = str_replace('classes', "", $path);
        if ($data_img === NULL) {
            $cmd = "UPDATE users SET nome=?, email=?, senha=?,id_area=?,niveis=?,cs_admin=?, cs_conferente1=? WHERE id=?";
            $result = json_decode($this->dbquery($cmd, $nome, $email, $senha,$id_area, $nivel, $cs_admin, $cs_conferente,$id_user));
        } else {
            $cmd = "UPDATE users SET nome=?, email=?, senha=?,id_area=?,niveis=?,photo_user=?,cs_admin=?, cs_conferente1=? WHERE id=?";
            $vcmd = array($cmd, "sssisbssi", $data_img);
            $aux=NULL;
            $result = json_decode($this->dbqueryblob($vcmd,$nome,$email,$senha, $id_area,$nivel,$aux,$cs_admin,$cs_conferente,$id_user));
        }
        if ($result->error == '0') {
            $nrecs = $id_user;
            file_put_contents($path . "id_user_" . $id_user . ".txt", "0",FILE_USE_INCLUDE_PATH);
        } else {
            $nrecs = -1;
        }
        return $nrecs;
    }

    function insert_user($data_img, $nome, $email, $senha, $id_area, $nivel, $cs_admin, $cs_conferente) {
        $path = dirname(__FILE__);
        $path = str_replace('classes', "", $path);

        if ($data_img === NULL) {
            $cmd="INSERT INTO users (nome, niveis, email, senha, id_area, cs_admin, cs_conferente1) VALUES (?,?,?,?,?,?,?);";
            $result = json_decode($this->dbquery($cmd, $nome, $nivel,$email, $senha,$id_area, $cs_admin, $cs_conferente));
            if ($result->error == '0') {
                $id_user = $this->getInsertedId();       
                $nrecs = $id_user;
                file_put_contents($path ."id_user_" . $id_user . ".txt", "0",FILE_USE_INCLUDE_PATH);
            } else {
                $nrecs = -1;
            }
        } else {
            $cmd="INSERT INTO users (nome, niveis, email, senha, id_area, photo_user, cs_admin, cs_conferente1) VALUES (?,?,?,?,?,?,?,?);";
            $vcmd = array($cmd, "ssssibss", $data_img);
            $aux=NULL;
            $result = json_decode($this->dbqueryblob($vcmd,$nome,$nivel,$email,$senha, $id_area,$aux,$cs_admin,$cs_conferente));
            if ($result->error == '0') {
                $id_user = $this->getInsertedId();
                file_put_contents($path ."id_user_" . $id_user . ".txt", "0".FILE_USE_INCLUDE_PATH);
                $nrecs = $id_user;
            } else {
                $nrecs = -1;
            }
        }
        return $nrecs;
    }

    function write_admin($id_user) {
        $cmd="SELECT * FROM cnpjxusers WHERE id_user=?";
        $result = json_decode($this->dbquery($cmd, $id_user));
        $id_lista = -1;
        if ($result->nrecords == 0) {
            $cmd = "INSERT INTO cnpjxusers (id_user) VALUES (?)";
            $result = json_decode($this->dbquery($cmd, $id_user));
            if ($result->error=="0") {
                $id_lista = $this->getInsertedId();
            }
        } 
        return $id_lista;
    }
    
    function getCs_Admin($id_user = NULL) {
        if ($id_user == NULL) {
            return self::$cs_admin;
        } else {
            $cmd = "SELECT cs_admin FROM users WHERE id = $id_user";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error=='0') {
                $cursor = $result->records;
                if (count($cursor) > 0) {
                    $cs_admin = $cursor[0]->cs_admin;
                } else {
                    $cs_admin = NULL;
                }
                
            } else {
                $cs_admin = NULL;
            }
            return $cs_admin;
        }
    }

    function setTypeUser($nivel) {
        self::$type_users = $nivel;
    }

    function getTypeUser($id_user = NULL) {
        if ($id_user == NULL) {
            return self::$type_users;
        } else {
            $cmd = "SELECT niveis FROM users WHERE id= $id_user";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error=='0') {
                $cursor = $result->records;
                if (count($cursor) > 0) {
                    $niveis = $cursor[0]->niveis;
                } else {
                    $niveis = NULL;
                }
                
            } else {
                $niveis = NULL;
            }
            return $niveis;
        }
    }
    function getAreas () {
        $cmd = "SELECT * FROM areas ORDER BY nm_area;";
        $result = json_decode($this->dbquery($cmd));
        if ($result->error == '0') {
            $cursor = $result->records;
        } else {
            $cursor = array();
        }
        return $cursor;
    }

    function getRow($index = NULL) {
        if ($index == NULL) {
            return self::$records;
        } else {
            if ($index >=0 and $index < count(self::$records)) {
                return self::$records[$index];
            } else {
                return array();
            }
        }
    }

    function isAdmin($id_user) {
        if ($id_user == NULL) {
            return False;
        } else {
           $cs_admin = $this->getCs_Admin($id_user);
           if ($cs_admin ==="S") {
               return true;
           } else {
               if ($cs_admin == "T" or $cs_admin == "F") {
                   return true;
               } else {
                   return false;
               }
           }
        }
    }

    function getNumPrestadoes() {
        $cmd = "SELECT count(*) as total FROM prestadores;";
        $result =json_decode($this->dbquery($cmd));
        if ($result->error == '0') {
            $cursor = $result->records;
            $total = $cursor[0]->total;
            return $total; 
        } else {
            return 0;
        }
    }
    function getListCNPJ($id_user, $nivel) {
        $list_cnpj=array();
        if ($nivel != "BR") {
          
            $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores WHERE id_prestador IN (SELECT id_prestador FROM cnpjxusers WHERE id_user = $id_user) ORDER BY RSP;";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $id_prestador = $row->id_prestador;
                    $cnpjp = $row->CNPJP;
                    $rsp = $row->RSP;
                    array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);  
                } 
            }
        } else {
            $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores ORDER BY RSP;";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $id_prestador = $row->id_prestador;
                    $cnpjp = $row->CNPJP;
                    $rsp = $row->RSP;
                    array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);  
                }
            }
        }
        return $list_cnpj;
    }
    function getCNPJP($id, $tipo=NULL, $nivel=NULL,$list_tomadores = NULL) {
        $list_cnpj=array();
        if ($nivel === NULL) {
            $nivel = $this->getTypeUser($id);
        }
        
        if ($tipo =="0") {
            $tipo = NULL;
        }
        if ($nivel == 'BR') {
            $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores ORDER BY RSP;";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $id_prestador = $row->id_prestador;
                    $cnpjp = $row->CNPJP;
                    $rsp = $row->RSP;
                    if ($tipo == NULL) {
                        array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                    } elseif ($tipo == '1') {
                        if (substr($cnpjp, 8, 4) == "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    } else {
                        if (substr($cnpjp, 8, 4) != "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    }
                }
            }
        } elseif ($nivel == "FN") {
            $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores WHERE id_prestador IN (SELECT id_prestador FROM vi_users WHERE id_user = $id) ORDER BY RSP;";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $id_prestador = $row->id_prestador;
                    $cnpjp = $row->CNPJP;
                    $rsp = $row->RSP;
                    if ($tipo == NULL) {
                        array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                    } elseif ($tipo == '1') {
                        if (substr($cnpjp, 8, 4) == "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    } else {
                        if (substr($cnpjp, 8, 4) != "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    }
                }
            }
        } elseif ($nivel == 'BRP1') {
            if ($list_tomadores === NULL) {
                $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores WHERE id_prestador IN (SELECT id_prestador FROM `notas_empresas` group by id_prestador) ORDER BY RSP";
            } else {
                $cmd = "SELECT id_prestador, CNPJP, RSP from prestadores WHERE id_prestador IN (SELECT id_prestador FROM `notas_empresas` WHERE id_tomador IN ($list_tomadores) group by id_prestador) ORDER BY RSP";
            }
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $id_prestador = $row->id_prestador;
                    $cnpjp = $row->CNPJP;
                    $rsp = $row->RSP;
                    if ($tipo == NULL) {
                        array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                    } elseif ($tipo == '1') {
                        if (substr($cnpjp, 8, 4) == "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    } else {
                        if (substr($cnpjp, 8, 4) != "0001") {
                            array_push($list_cnpj, intval($id_prestador) . "-" . $cnpjp . "-" . $rsp);
                        }
                    }
                }
            }
        }
      
        return $list_cnpj;
    }

    function getTomador($id_user) {
        $cmd = "SELECT RST FROM vi_tomadores WHERE id_tomador in (SELECT id_tomador FROM vi_users WHERE id_user=$id_user);";
        $result = json_decode($this->dbquery($cmd));
        if ($result->error == '0') {
            $cursor = $result->records;
            if (count($cursor) > 0) {
                $tomador = $cursor[0]->RST;
            } else {
                $tomador = "";
            }
        } else {
            $tomador = "";
        }
        return $tomador;
    }
    function getTomadores ($id_user = NULL) {
        $list_tomadores = array();
        if ($id_user ===NULL) {
            $cmd = "SELECT id_tomador, CNPJT, RST FROM vi_tomadores;";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    array_push($list_tomadores, intval($row->id_tomador) . "-" . $row->CNPJT . "-" . $row->RST);
                }
            }
        } else {
            $cmd = "SELECT id_tomador, CNPJT, RST FROM vi_tomadores WHERE id_tomador in (SELECT id_tomador FROM vi_users WHERE id_user=$id_user);";
            $result = json_decode($this->dbquery($cmd));
            if ($result->error == '0') {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    array_push($list_tomadores, intval($row->id_tomador) . "-" . $row->CNPJT . "-" . $row->RST);
                }
            }
        }
        return $list_tomadores;
    }
    function getNumUsers($id_user = NULL) {
        $ult_id=0;
        $nrecords = 0;
        foreach(self::$records as $record) {
            if ($id_user == NULL) {
                if ($ult_id !== $record->id_user) {
                    $ult_id=$record->id_user;
                    $nrecords++;
                }
            } else {
                if ($id_user == $record->id_user) {
                    $nrecords++;
                }
            }
        }
        return $nrecords;
    }

    function getCurrentUser($id_user) {
        $cmd = "SELECT id, nome, senha, id_area, email, niveis, cs_admin,cs_conferente1 FROM users WHERE id= $id_user;";
        $result = json_decode($this->dbquery($cmd));
        $array = NULL;
        if ($result->error == '0') {
            if ($result->nrecords > 0) {
                $cursor = $result->records;
                $array = json_decode(json_encode($cursor[0]),true);
            }
        }
        return $array;
    }

    function getUsers($id_user = NULL) {
      return self::$records;
    }

    function checkUser($email) {
        $cmd = "SELECT * FROM users WHERE email=?";
        $result = json_decode($this->dbquery($cmd, $email));
        if ($result->error == '0') {
            if ($result->nrecords == 0) {
                $name = NULL;
            } else {
                $cursor = $result->records;
                $name = $cursor[0]->nome;
            }
        } else {
            $name= NULL;
        }
        return $name;
    }
}

?>