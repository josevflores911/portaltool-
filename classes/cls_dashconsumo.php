<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once('cls_connect.php');

    class cls_dashconsumo extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $message = "";
        static $cursor=NULL;
        static $connected=FALSE;
        static $oConn=NULL;
        static $dbname = "Mbcp20211802wb1";
        static $bfilter = FALSE;
        function __construct($bfilter=NULL) {
            parent::__construct(self::$dbname);
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = True;
                self::$bfilter = $bfilter;
            }
        }
        function getDates($list_date) {
            if (count($list_date) >= 1) {
                $data_1 = $list_date[0];
                if (count($list_date) == 1) {
                    $data_2 = date("Y-m-d ");
                } else {
                    $data_2 = $list_date[1];
                }
            } else {
                $data_1 = '1949-01-01';
                $data_2 = $data_2 = date("Y-m-d ");
            }
            return array($data_1, $data_2);
        }

        function getValues($list_values) {
            if (count($list_values) >= 1) {
                $value_1 = $list_values[0];
                if (count($list_values) == 1) {
                    $value_2 = 9999999999999999999999.99;
                } else {
                    $value_2 = $list_values[1];
                }
            } else {
                $value_1 = 0.00;
                $value_2 = 9999999999999999999999.99;
            }
            return array($value_1, $value_2);
        }
        
        function getPanel1($list_dates, $list_values) {
            $vresult = array();
            if (self::$connected) {
                $bfilter = FALSE;
                if (!is_null($list_dates)) {
                    if (count($list_dates) > 0) {
                        $bfilter=TRUE;
                    }
                }

                if (! is_null($list_values)) {
                    if (count($list_values) > 0) {
                        $bfilter=TRUE;
                    }
                }

                if ($bfilter) {
                    if (count($list_values) > 0 AND count($list_dates) == 0) {
                        $values = $this->getValues($list_values);
                        $value_1=$values[0];
                        $value_2=$values[1];
                        $cmd="SELECT COUNT(*) AS qtd_notas, SUM(a.vl_nota) as total_notas FROM notas_consumo a WHERE a.vl_nota >= $value_1 and a.vl_nota < $value_2";
                    } elseif (count($list_values) == 0 AND count($list_dates) > 0) {
                        $values = $this->getDates($list_dates);
                        $data_1=$values[0];
                        $data_2=$values[1];

                        $cmd="SELECT COUNT(*) AS qtd_notas, SUM(a.vl_nota) as total_notas FROM notas_consumo a WHERE a.dt_emissao BETWEEN '$data_1' and '$data_2'";
                    } else {
                        $values = $this->getValues($list_values);
                        $value_1=$values[0];
                        $value_2=$values[1];
                        $values = $this->getDates($list_dates);
                        $data_1=$values[0];
                        $data_2=$values[1];
                        $cmd="SELECT COUNT(*) AS qtd_notas, SUM(a.vl_nota) as total_notas FROM notas_consumo a WHERE (a.dt_emissao BETWEEN '$data_1' and '$data_2') AND ";
                        $cmd .= " (a.vl_nota >= $value_1 and a.vl_nota < $value_2)";
                    }
                    $result = json_decode($this->dbquery($cmd));
                    $sum_qtd = 0;
                    $sum_values=0;

                    if ($result->nrecords > 0) {
                        self::$Error = '0';
                        self::$message = "ok";

                        $cursor = $result->records;
                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $qtd_notas    = $row['qtd_notas'];
                            $total_notas  = $row['total_notas'];
                            $qtd_notas = floatval($qtd_notas);
                            $total_notas = floatval($total_notas);
                            $sum_qtd += $qtd_notas;
                            $sum_values += $total_notas;
                        }
                    } else {
                        self::$Error = '404';
                        self::$message = "Consulta não retornou registros";
                    }
                    $vresult = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    
                } else {
                    $cmd = "SELECT COUNT(*) as qtd_notas, SUM(a.vl_nota) as total_notas FROM notas_consumo a";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        $cursor = $result->records[0];
                        $qtd_notas = $cursor->qtd_notas;
                        $total_notas = $cursor->total_notas;
                        $vresult = array("qtd_notas"=>$qtd_notas, "total_notas" => $total_notas);
                        self::$Error = '0';
                        self::$message = "consulta ok";
                    } else {
                        self::$Error = '404';
                        self::$message = "consulta não trouxe registros";
                    }
                }

              
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }

        function getCursor($parms =NULL) {
            $vresult = array();
            $interval_data = NULL;
            $interval_value = NULL;
            if (self::$bfilter) {
                if (! is_null($parms)) {
                    $list_data = (array_key_exists('list_data', $parms)) ? $parms['list_data'] : NULL;
                    $list_values = (array_key_exists('list_values', $parms)) ? $parms['list_values'] : NULL;
                    if (! is_null($list_data) AND count($list_data) == 2) {
                        $vaux = explode(",", $list_data);
                        $interval_data = "'$vaux[0]'" . " AND '$vaux[1]'";
                    } else {
                        $now = new \DateTime();
                        $vaux = explode(",", $list_data);
                        $last_date = $now->format('%Y-%m-%d');
                        $interval_data = "'$vaux[0]' AND '$last_date'";
                    }

                    if (! is_null($list_values) AND count($list_values) == 2) {
                        $vaux = explode(",", $list_values);
                        $interval_value= strval($vaux[0]) . " AND " . strval($vaux[1]);
                    } else {
                        $vaux = explode(",", $list_values);
                        $interval_value = strval($vaux[0]) . " AND " . strval(999999999999999999.99);
                    }
                    if (is_null($interval_data) and is_null($interval_value)) {
                        self::$bfilter = FALSE;
                    } else {
                        self::$bfilter = TRUE;
                    }
                }
            }
            if (self::$connected) {
                if (self::$bfilter == FALSE) {
                    $cmd ="SELECT b.te_tipo, COUNT(a.id_notaconsumo) as total_notas, SUM(a.vl_nota) as vl_nota FROM notas_consumo a, tipos_notas b WHERE (a.cd_tipo = b.cd_tipo)";
                    $result = json_decode($this->dbquery($cmd));
                    $curs = $result->records[0];
                    $total_geral = intval($curs->total_notas);

                    $cmd = "SELECT * FROM vi_dashconsumo";
                    $result = json_decode($this->dbquery($cmd));
                } else {
                    $cmd = "SELECT b.te_tipo, COUNT(a.id_notaconsumo) as total_notas, SUM(a.vl_nota) as vl_nota
                            FROM notas_consumo a, tipos_notas b WHERE (a.cd_tipo = b.cd_tipo) AND ";
                    if (! is_null($interval_data)) {
                        $cmd .= "(DATE(a.dt_emissao) BETWEEN $interval_data) AND ";
                    }
                    if (! is_null($interval_value)) {
                        $cmd .= "(a.vl_nota BETWEEN $interval_value) AND ";
                    }
                    $cmd = substr($cmd, 0, strripos($cmd, "AND")-1);
                    $result = json_decode($this->dbquery($cmd));
                    $total_geral = ($result->records[0])->total_notas;
                    
                    $cmd .= " GROUP BY b.te_tipo ORDER BY SUM(a.vl_nota) DESC";
                    $result= json_decode($this->dbquery($cmd));
                }
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        $te_tipo = $row["te_tipo"];
                        $vl_nota = floatval($row["vl_nota"]);
                        $qtd_notas = $row["total_notas"];
                        $perc = round($qtd_notas*100/$total_geral);
                       
                        $aux = array("descricao" => $te_tipo, "qtd_notas"=>$qtd_notas, "vl_total" => $vl_nota, "perc" => $perc);
                        array_push($vresult, $aux);
                    }
                    self::$Error = '0';
                    self::$message = "consulta ok";
                } else {
                    self::$Error = '404';
                    self::$message = "consulta não trouxe registros";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }
    }

    
?>