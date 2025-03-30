<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once('cls_connect.php');
    class cls_dashcte extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $message = "";
        static $cursor=NULL;
        static $connected=FALSE;
        static $oConn=NULL;
        static $dbname = "Mbpf202202cte1";

        function __construct() {
            parent::__construct(self::$dbname);
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = True;
            }
        }
        function getDates($list_date) {
            if (gettype($list_date) == 'array') {
                if (count($list_date) >= 1) {
                    $data_1 = $list_date[0];
                    if (count($list_date) == 1) {
                        $data_2 = date("Y-m-d ");
                    } else {
                        $data_2 = $list_date[1];
                    }
                    return array($data_1, $data_2);
                } else {
                    return null;
                }
            } else {
                return null;
            }
            
        }

        function getValues($list_values) {
            if (gettype($list_values) == 'array') {
                if (count($list_values) >= 1) {
                    $value_1 = $list_values[0];
                    if (count($list_values) == 1) {
                        $value_2 = 9999999999999999999999.99;
                    } else {
                        $value_2 = $list_values[1];
                    }
                    return array($value_1, $value_2);
                } else {
                    return null;
                }
            }else {
                return null;
            }
        }

        static function hasFilterValues($list_values) {
            if (! is_null($list_values)) {
                if (! empty($list_values)) {
                    return true;
                }
            }
            return false;
        }
        static function hasFilterDates($list_dates) {
            if (! is_null($list_dates)) {
                if (! empty($list_dates)) {
                    return true;
                }
            }
            return false;
        }

        public function getPanel1($list_dates, $list_values) {
             $vresult = array();
            if (self::$connected) {
                $bfilter = self::hasFilterValues($list_values) == true and self::hasFilterDates($list_dates) == true;

                $sum_qtd = 0;
                $sum_values=0;
                if ($bfilter) {
                    $cmd = "SELECT DATE(a.dt_emissao) AS dt_emissao,count(*) as qtd_notas,sum(a.vl_areceber) as total_notas FROM tbnotas_cte a ";
                    $cmdgroup = " GROUP BY DATE(a.dt_emissao)";
                    if (count($list_dates) > 0 AND count($list_values) == 0) {
                        $vdata = $this->getDates($list_dates);
                        $data_1 = $vdata[0];
                        $data_2 = $vdata[1];
                        $cmd .= " WHERE DATE(a.dt_emissao) BETWEEN '$data_1' AND '$data_2'";
                    } elseif (count($list_dates) == 0 AND count($list_values) > 0) {
                        $vdata = $this->getValues($list_dates);
                        $value_1 = $vdata[0];
                        $value_2 = $vdata[1];
                        $cmd .= " WHERE a.vl_areceber BETWEEN $value_1 AND $value_2";
                    } else {
                        $vdata = $this->getDates($list_dates);
                        $data_1 = $vdata[0];
                        $data_2 = $vdata[1];
                        $vdata = $this->getValues($list_dates);
                        $value_1 = $vdata[0];
                        $value_2 = $vdata[1];
                        $cmd .= " WHERE (DATE(a.dt_emissao) BETWEEN '$data_1' AND '$data_2') AND (a.vl_areceber BETWEEN $value_1 AND $value_2)";
                    }
                    $cmd .= $cmdgroup;
                    $result = json_decode($this->dbquery($cmd));
          
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
                    $cmd="SELECT COUNT(a.nu_nota) as qtd_notas, SUM(a.vl_areceber) as total_notas FROM tbnotas_cte a";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        $cursor = $result->records[0];
                        $sum_qtd = intval($cursor->qtd_notas);
                        $sum_values = floatval($cursor->total_notas);
                        self::$Error = '0';
                        self::$message = "consulta ok";
                        
                    } else {
                        self::$Error = '404';
                        self::$message = "consulta não trouxe registros";
                    }
                    $vresult = array("qtd_notas"=>$sum_qtd, "total_notas" => $sum_values);

                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }
    }
?>