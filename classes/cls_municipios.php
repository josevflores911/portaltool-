<?php
    error_reporting(0);
    error_reporting(E_ALL);
    require_once "cls_connect.php";


    class cls_municipios extends cls_connect {
        static $cursor = NULL;
        static $connected = FALSE;
        static $conn = NULL;
        static $Error = '0';
        static $message ="";
        static $cd_uf=NULL;
        static $dash=NULL;
        function __construct($id_user=NULL, $tp_user=NULL)
        {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) 
            {
                self::$connected=TRUE;
                self::$conn = parent::$conn;
                if (!is_null($id_user) and !is_null($tp_user)) {
                    $varray = array('Sistema', 'Administrador', 'Gestor');
                    if (in_array($tp_user, $varray)) {
                        $cmd = "SELECT id_muni, nm_muni FROM vi_usersxmunicipios GROUP BY nm_muni";
                        $result = json_decode($this->dbquery($cmd));
                    } else {
                        $cmd = "SELECT id_muni, nm_muni FROM vi_usersxmunicipios WHERE id_user=? GROUP BY nm_muni";
                        $result = json_decode($this->dbquery($cmd, $id_user));
                    }
                    if ($result->nrecords > 0) {
                        self::$Error = '0';
                        self::$message = 'Consulta realizada com sucesso';
                        self::$cursor = $result->records;
                    } else {
                        self::$Error = '404';
                        self::$message = "Consulta não retornou registros";
                    }
                }

            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
        }
        /*
        public function lerLink($id_nota) {
            $vet_result = [];
            if (self::$connected) {
                $cmd = "SELECT id_muni FROM vi_notas WHERE id_nota=?";
                
                $cmd = "SELECT Nm_Link FROM municipios WHERE id_muni=?";
                $result = json_decode($this->dbquery($cmd, $id_muni));
                if ($result->nrecords > 0) {
                    $nm_link = ($result->records[0])->Nm_Link;
                    $cmd = "SELECT IMP,NNFS,CV FROM notas WHERE id_nota=?";
                    $result = json_decode($this->dbquery($cmd, $id_nota));
                    if ($result->nrecords > 0) {
                        $IMP = ($result->records[0])->IMP;
                        $NNFS= ($result->records[0])->NNFS;
                        $CV = ($result->records[0])->CV;
                        self::$Error = '0';
                        self::$message = 'link_montado';
                        $vet_result = eval($nm_link);
                    } else {
                        self::$Error = '404';
                        self::$message = "Consulta não retornou registros";
                    }
                }  else {
                    self::$Error = '404';
                    self::$message = "Consulta não retornou registros";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "nm_link" => $vet_result);
        }
            */
        public function getDashMuni() {
            $vet_result = array();
            if (self::$connected) {
                $cmd = "SELECT * FROM vi_dash_muni WHERE cd_uf=? ORDER BY nm_muni";
                $result = json_decode($this->dbquery($cmd, self::$cd_uf));
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        $cd_uf = $row["cd_uf"];
                        $nm_muni = $row["nm_muni"];
                        $cmd = "SELECT id_muni FROM municipios WHERE Cd_UF=? AND Nm_Muni=?";
                        $result = json_decode($this->dbquery($cmd, $cd_uf, $nm_muni));
                        if ($result->records > 0) {
                            $id_muni = ($result->records[0])->id_muni;
                        } else {
                            continue;
                        }
                        $qtd_notas = $row["total_notas"];
                        array_push($vet_result, ["id_muni" => $id_muni, "nm_muni" => $nm_muni, "qtd_notas" => $qtd_notas]);
                    }
                    self::$Error = '0';
                    self::$message = "Encontrados " . $result->nrecords . " registros";
                } else {
                    self::$Error = '404';
                    self::$message = "Consulta não retornou registros";
                }

            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vet_result);
        }

        public function getIdMuniByKeys($cd_uf, $nm_muni) {
            $id_muni=NULL;
            if (self::$connected) {
                if (! is_null($cd_uf) and ! is_null($nm_muni)) {
                    $cd_uf = strtolower($cd_uf);
                    $nm_muni = strtolower($nm_muni);
                    $cmd = "SELECT id_muni FROM municipios WHERE LOWER(cd_uf)=? AND LOWER(nm_muni)=?";
                    $result = json_decode($this->dbquery($cmd, $cd_uf, $nm_muni));
                    if ($result->nrecords > 0) {
                        $id_muni = ($result->records[0])->id_muni;
                    }
                }
            } 
            return $id_muni;
        }

        public function getIdMuni($pesq, $key=FALSE) {
            $id_result=NULL;
            if (self::$connected) {
                if ($key == FALSE) {
                    if (is_numeric($pesq)) {
                        $field = "cd_ibge";
                    } else {
                        $field ="Nm_Muni";
                    }
                    foreach (self::$cursor as $id_muni => $vetor) {
                        if ($id_muni !== "Error") {
                            $result = $vetor[$field];
                            if ($pesq == $result) { 
                                $id_result = $id_muni;
                                break;
                            }
                        }
                    }
                } else {
                    $vpesq = array_keys(self::$cursor);
                    if (in_array($pesq, $vpesq)) {
                        $id_result = $pesq;
                    }
                }
            }
            return $id_result;
        }
        public function getCursor () {
            if (self::$connected) {
                return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
            } else {
                return array("Error" => "504");
            }
        }

        public function getMuniByEstado($cd_estado) {
            $vet_result = array();
            if (is_null($cd_estado)) {
                // pega o primeiro estado registrado
                $cmd = "SELECT id_muni, nm_muni FROM vi_tbagenciasxmunicipios
                        GROUP by cd_estado, nm_muni
                        order by cd_estado, nm_muni";
                $result = json_decode($this->dbquery($cmd));
            } else {
                $cmd = "SELECT id_muni, nm_muni FROM vi_tbagenciasxmunicipios
                        WHERE cd_estado=?
                        GROUP by cd_estado, nm_muni
                        order by cd_estado, nm_muni";
                $result = json_decode($this->dbquery($cmd, $cd_estado));
            }
            if ($result->nrecords > 0) {
                $cursor = $result->records;
                foreach ($cursor as $row) {
                    $row = get_object_vars($row);
                    $id_muni = $row["id_muni"];
                    $nm_muni = $row["nm_muni"];
                    array_push($vet_result, ["id_muni" => $id_muni, "nm_muni" => $nm_muni]);
                }
            } 
            return $vet_result;
        }
        
    }
?>