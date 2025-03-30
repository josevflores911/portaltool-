<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once('cls_connect.php');

    class cls_dash10mais extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $oConn=NULL;
        static $message="";
        static $bfilter=FALSE;
        static $tipo=1; // 1- prestadores, 2 - tomadores, 3-servicos;
        function __construct($tipo=1,$bfilter=FALSE) {
            parent::__construct();
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = True;
                self::$bfilter = $bfilter;
                self::$tipo=$tipo;
            }
        }
        
        function getCursor($parms = NULL ) {
            $vresult = array();
            $id_prestador = NULL;
            $id_tomador = NULL;
            $id_servico = NULL;
            $cd_uf = NULL;
            $interval_data = NULL;
            $interval_value = NULL;
            if (self::$bfilter) {
                if (! is_null($parms)) {
                    $id_prestador = (array_key_exists('id_prestador', $parms)) ? $parms['id_prestador'] : NULL;
                    $id_tomador = (array_key_exists('id_tomador', $parms)) ? $parms['id_tomador'] : NULL;
                    $id_servico = (array_key_exists('id_servico', $parms)) ? $parms['id_servico'] : NULL;
                    $id_muni = (array_key_exists('id_muni', $parms)) ? $parms['id_muni'] : NULL;
                    $cd_uf = (array_key_exists('cd_uf', $parms)) ? $parms['cd_uf'] : NULL;
                    $list_data = (array_key_exists('list_data', $parms)) ? $parms['list_data'] : NULL;
                    $list_values = (array_key_exists('list_values', $parms)) ? $parms['list_values'] : NULL;
                    if (! is_null($list_data)) {
                        $vaux = explode(",", $list_data);
                        $interval_data = "'$vaux[0]'" . " AND '$vaux[1]'";
                    } 
                    if (! is_null($list_values)) {
                        $vaux = explode(",", $list_values);
                        $interval_value= $vaux[0] . " AND " . $vaux[1];
                    } 
            
                    if (is_null($id_prestador) and is_null($id_tomador) and is_null($id_servico) and
                        is_null($id_muni) and is_null($cd_uf) and is_null($interval_data) and is_null($interval_value)) {
                        $parms = NULL;
                        self::$bfilter = FALSE;
                    }
                }
            }
            if (self::$connected) {
                if (self::$tipo == 1) {
            

                    if (self::$bfilter==FALSE) {
                        $cmd = "SELECT FC_GetVlTotalServicos() as tot_geral";
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->tot_geral;
                        $vl_total = floatval($vl_total);

                        $cmd = "SELECT LEFT(a.CNPJP,8) as raiz_cnpj, a.RSP, COUNT(a.NNFS) as qtd_notas, SUM(a.VS) as total_servicos FROM vi_notas a GROUP BY LEFT(a.CNPJP,8), a.RSP ORDER BY SUM(a.VS) DESC limiT 10";
                        $result = json_decode($this->dbquery($cmd));
                    } else {
                        $cmd = "SELECT LEFT(a.CNPJP,8) as raiz_cnpj,a.RSP,";
                        $cmdgroup = " GROUP BY LEFT(a.CNPJP,8) ";
                        $cmdwhere = "WHERE ";
                        if ( !is_null($id_prestador)) {
                            $cmdwhere .= "(a.id_prestador = $id_prestador) AND ";
                        } 
                        if ( !is_null($id_tomador)) {
                            $cmdwhere .= "(a.id_tomador = $id_tomador) AND ";
                        }

                        if (!is_null($id_muni)) {
                            $cmdwhere .= "(a.id_muniprestador = $id_muni) AND ";
                        }

                        if (!is_null($id_servico)) {
                            $cmdwhere .= "(a.id_nota IN (SELECT b.id_nota FROM tbnotasxaliquotas b, vi_notas c, vi_depara d WHERE
                            b.id_notaxdepara = c.id_notaxdepara AND
                            b.id_nota = c.id_nota AND
                            b.id_depara = d.id_depara AND c.id_depara = d.id_depara AND 
                            c.DH BETWEEN d.dt_inivigencia_muni AND d.dt_fimvigencia_muni AND d.id_servico = $id_servico) AND";
                        }

                        if (! is_null($cd_uf)) {
                            $cmdwhere .= "(a.UFP = '$cd_uf') AND ";
                        }
                        if (! is_null($interval_data)) {
                            $cmdwhere .= "(DATE(a.DH) BETWEEN $interval_data) AND ";
                        }
                        if (! is_null($interval_value)) {
                            $cmdwhere .= "(a.VS BETWEEN $interval_value) AND ";
                        }
                        
                        $cmd .= "COUNT(a.id_nota) AS qtd_notas, SUM(a.VS) AS total_servicos FROM vi_notas a ";
                        $cmdwhere = substr($cmdwhere, 0, strripos($cmdwhere, "AND")-1);
                        $cmd .= $cmdwhere;
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->total_servicos;
                        $vl_total = floatval($vl_total);
                        $cmd .=  " " . $cmdgroup . " ORDER BY SUM(a.VS) DESC limit 10";
                        $result = json_decode($this->dbquery($cmd));
                        
                    }
                    
                    if ($result->nrecords > 0) {
                        $cursor = $result->records;
                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $raiz_cnpj = $row["raiz_cnpj"];
                            $nm_empresa = $row["RSP"];
                            $qtd_notas = $row["qtd_notas"];
                            $qtd_notas = number_format(floatval($qtd_notas),0,',','.');
                            $vl_servicos = $row["total_servicos"];
                            $val_servicos = number_format(floatval($vl_servicos),2,',','.');
                            $perc = round($vl_servicos*100/$vl_total,2);
                            $perc = number_format(floatval($perc),2,',','.');
                            $aux = array("codigo"=> $raiz_cnpj, "descricao" => $nm_empresa, "qtd_notas"=>$qtd_notas, "vl_total" => $val_servicos, "perc" => $perc);
                            array_push($vresult, $aux);
                        }
                        self::$Error = '0';
                        self::$message = "consulta ok";
                    } else {
                        self::$Error = '404';
                        self::$message = "consulta não trouxe registros";
                    }
                } elseif (self::$tipo == 2) { 
                    
                    if (self::$bfilter == FALSE) {
                        $cmd = "SELECT FC_GetVlTotalServicos() as tot_geral";
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->tot_geral;
                        $vl_total = floatval($vl_total);

                        $cmd = "SELECT a.id_tomador, LEFT(a.CNPJP,9) AS raiz_cnpj, a.RSP, COUNT(a.id_nota) as qtd_notas, SUM(a.vs) as total_servicos
                        FROM vi_notas a GROUP by a.id_tomador, LEFT(a.CNPJP,8) ORDER BY SUM(a.VS) DESC LIMIT 10";
                        $result = json_decode($this->dbquery($cmd));
                    } else {
                        $cmd = "SELECT a.id_tomador,LEFT(a.CNPJP,8) as raiz_cnpj,a.RSP,";
                        $cmdgroup = " GROUP BY LEFT(a.CNPJP,8) ";
                        $cmdwhere = "WHERE ";
                        if ( !is_null($id_prestador)) {
                            $cmdwhere .= "(a.id_prestador = $id_prestador) AND ";
                        } 
                        if ( !is_null($id_tomador)) {
                            $cmdwhere .= "(a.id_tomador = $id_tomador) AND ";
                        }

                        if (!is_null($id_muni)) {
                            $cmdwhere .= "(a.id_muniprestador = $id_muni) AND ";
                        }

                        if (!is_null($id_servico)) {
                            $cmdwhere .= "(a.id_nota IN (SELECT b.id_nota FROM tbnotasxaliquotas b, vi_notas c, vi_depara d WHERE
                            b.id_notaxdepara = c.id_notaxdepara AND
                            b.id_nota = c.id_nota AND
                            b.id_depara = d.id_depara AND c.id_depara = d.id_depara AND 
                            c.DH BETWEEN d.dt_inivigencia_muni AND d.dt_fimvigencia_muni AND d.id_servico = $id_servico) AND";
                        }
                        
                        if (! is_null($cd_uf)) {
                            $cmdwhere .= "(a.UFP = '$cd_uf') AND ";
                        }
                        if (! is_null($interval_data)) {
                            $cmdwhere .= "(DATE(a.DH) BETWEEN $interval_data) AND ";
                        }
                        if (! is_null($interval_value)) {
                            $cmdwhere .= "(a.VS BETWEEN $interval_value) AND ";
                        }
                        $cmd .= "COUNT(a.id_nota) AS qtd_notas, SUM(a.VS) AS total_servicos FROM vi_notas a ";
                        $cmdwhere = substr($cmdwhere, 0, strripos($cmdwhere, "AND")-1);
                        $cmd .= $cmdwhere;
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->total_servicos;
                        $vl_total = floatval($vl_total);

                        $cmd .= " " . $cmdgroup . " ORDER BY SUM(a.VS) DESC limit 10";
                        $result = json_decode($this->dbquery($cmd));
                    }
                    if ($result->nrecords > 0) {
                        $cursor = $result->records;
                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $raiz_cnpj = $row["raiz_cnpj"];
                            $nm_empresa = $row["RST"];
                            $qtd_notas = $row["total_notas"];
                            $qtd_notas = number_format(floatval($qtd_notas),0,',','.');
                            $vl_servicos = $row["total_servicos"];
                            $vl_servicos = number_format(floatval($vl_servicos),2,',','.');
                            $perc = $row["perc_valor"];
                            $perc = number_format(floatval($perc),2,',','.');
                            $aux = array("codigo"=> $raiz_cnpj, "descricao" => $nm_empresa, "qtd_notas"=>$qtd_notas, "vl_total" => $vl_servicos, "perc" => $perc);
                            array_push($vresult, $aux);
                        }
                        self::$Error = '0';
                        self::$message = "consulta ok";
                    } else {
                        self::$Error = '404';
                        self::$message = "consulta não trouxe registros";
                    }
                } elseif (self::$tipo == 3) {
                    if (self::$bfilter == FALSE) {
                        $cmd = "SELECT FC_GetVlTotalServicos() as tot_geral";
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->tot_geral;
                        $vl_total = floatval($vl_total);

                        $cmd="SELECT * from vi_dash_servfederal ORDER BY total_servicos DESC";
                        $result = json_decode($this->dbquery($cmd));
                    } else {
                        $cmd = "SELECT CONCAT(b.cd_servico_federal,'-', b.cd_subgrupo_federal) AS cd_servico, b.te_servico_federal, COUNT(a.id_nota) as qtd_notas,
                                SUM(a.VS) as total_servicos FROM vi_notas a INNER JOIN vi_depara b ON a.id_depara = b.id_depara AND a.DH BETWEEN b.dt_inivigencia_muni AND b.dt_fimvigencia_muni
                                WHERE ";
                        $cmdwhere = "";
                        if ( !is_null($id_prestador)) {
                            $cmdwhere .= "(a.id_prestador = $id_prestador) AND ";
                        } 
                        if ( !is_null($id_tomador)) {
                            $cmdwhere .= "(a.id_tomador = $id_tomador) AND ";
                        }

                        if (!is_null($id_muni)) {
                            $cmdwhere .= "(a.id_muniprestador = $id_muni) AND ";
                        }
                        
                        if (! is_null($cd_uf)) {
                            $cmdwhere .= "(a.UFP = '$cd_uf') AND ";
                        }
                        if (! is_null($interval_data)) {
                            $cmdwhere .= "(DATE(a.DH) BETWEEN $interval_data) AND ";
                        }
                        if (! is_null($interval_value)) {
                            $cmdwhere .= "(a.VS BETWEEN $interval_value) AND ";
                        }
                        $cmdwhere = substr($cmdwhere, 0, strripos($cmdwhere, "AND")-1);
                        $cmd .= $cmdwhere;
                        $result = json_decode($this->dbquery($cmd));
                        $vl_total = ($result->records[0])->total_servicos;
                        $vl_total = floatval($vl_total);

                        $cmd .= " GROUP BY CONCAT(b.cd_servico_federal,'-', b.cd_subgrupo_federal) ORDER BY SUM(a.VS)";
                        $result = json_decode($this->dbquery($cmd));
                    }
                    if ($result->nrecords > 0) {
                        $cursor = $result->records;
                        $nrow = 0;
                        $vl_outros = 0.0;
                        $qtd_outros = 0;

                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $codigo = $row["cd_servico"];
                            $te_servico = $row["te_servico_federal"];
                            $qtd_notas = $row["qtd_notas"];
                            $vl_servicos = $row["total_servicos"];
                            if ($nrow < 10) {
                                $perc = round($vl_servicos * 100/ $vl_total, 2);
                                $qtd_notas = number_format(floatval($qtd_notas),0,',','.');
                                $vl_servicos = number_format(floatval($vl_servicos),2,',','.');
                                $perc = number_format(floatval($perc),2,',','.');
                                $aux = array("codigo"=> $codigo, "descricao" => $te_servico, "qtd_notas"=>$qtd_notas, "vl_total" => $vl_servicos, "perc" => $perc);
                                array_push($vresult, $aux);
                            } else {
                                $qtd_outros += intval($qtd_notas);
                                $vl_outros += floatval($vl_servicos);
                            }
                            $nrow+=1;
                        }
                        if ($vl_outros > 0) {
                            $perc = round($vl_outros * 100 / $vl_total, 2);
                            $vl_outros = number_format($vl_outros,2,',', '.');
                            $perc = number_format($perc,2,',','.');
                            $aux = array("codigo"=> "Outros", "descricao" => "", "qtd_notas"=>$qtd_outros, "vl_total" => $vl_outros, "perc" => $perc);
                            array_push($vresult, $aux);
                        }
                        self::$Error = '0';
                        self::$message = "consulta ok";
                    } else {
                        self::$Error = '404';
                        self::$message = "consulta não trouxe registros";
                    }

                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados inoperante";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }

    }

    class cls_dashstatus extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $oConn=NULL;
        static $message="";
        function __construct() {
            parent::__construct();
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = True;
            }
        }  

        function getCursor() {
            $vresult = array();
            if (self::$connected) {
                
                $cmd = "SELECT * FROM vi_dash_status_1";
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    $total = 0;
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        $vl_total = $row["vl_total"];
                        $vl_total = floatval($vl_total);
                        $total += $vl_total;
                    }
                    
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        $cd_status = $row["cd_status"];
                        $te_status = $row["te_status"];
                        $te_color = $row["te_color"];
                        $num_notas = $row["total_notas"];
                        $num_notas = number_format(floatval($num_notas),0,',','.');
                        $vl_total = $row["vl_total"];
                      
                        $perc = round(($vl_total / $total) * 100,2);
                        $vl_total = number_format(floatval($vl_total),2,',','.');
                        $perc = number_format($perc,2,',', '.');

                        $aux = array("cd_status" => $cd_status, "te_status" => $te_status, "te_color" => $te_color, "num_notas" =>$num_notas, "vl_total"=> $vl_total, "percent" => $perc);
                        array_push ($vresult, $aux);
                    }
                    self::$Error = '0';
                    self::$message="Registros lidos:" . $result->nrecords;
                } else {
                    self::$Error = '404';
                    self::$message = "Sem registros para contabilizar";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados inoperante";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }
    }

    class cls_dashservicos extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $message="";
        static $dbname = NULL;
        static $bfilter = FALSE;
        function __construct($bfilter) {
            parent::__construct(self::$dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$bfilter = $bfilter;
                self::$connected = True;

            } 
        }  
        function getCursor($parms = NULL) {
            $vresult = array();
            $id_prestador = NULL;
            $id_tomador = NULL;
            $id_servico = NULL;
            $id_muni = NULL;
            $cd_uf = NULL;
            $interval_data = NULL;
            $interval_value = NULL;
            if (self::$bfilter) {
                if (! is_null($parms)) {
                    $id_prestador = (array_key_exists('id_prestador', $parms)) ? $parms['id_prestador'] : NULL;
                    $id_tomador = (array_key_exists('id_tomador', $parms)) ? $parms['id_tomador'] : NULL;
                    $id_servico = (array_key_exists('id_servico', $parms)) ? $parms['id_servico'] : NULL;
                    $id_muni = (array_key_exists('id_muni', $parms)) ? $parms['id_muni'] : NULL;
                    $cd_uf = (array_key_exists('cd_uf', $parms)) ? $parms['cd_uf'] : NULL;
                    $list_data = (array_key_exists('list_data', $parms)) ? $parms['list_data'] : NULL;
                    $list_values = (array_key_exists('list_values', $parms)) ? $parms['list_values'] : NULL;
                    if (! is_null($list_data)) {
                        $vaux = explode(",", $list_data);
                        $interval_data = "'$vaux[0]'" . " AND '$vaux[1]'";
                    } 

                    if (! is_null($list_values)) {
                        $vaux = explode(",", $list_values);
                        $interval_value= $vaux[0] . " AND " . $vaux[1];
                    } 

                    if (is_null($id_prestador) and is_null($id_tomador) and is_null($id_servico) and
                        is_null($id_muni) and is_null($cd_uf) and is_null($interval_value) and is_null($interval_data)) {
                        $parms = NULL;
                        self::$bfilter = FALSE;
                    }
                   
                }
            }
            if (self::$connected) {
                if (self::$bfilter == FALSE) {
                    $cmd = "SELECT  COUNT(a.id_nota) as qtd_notas FROM vi_notas a ";
                    $result = json_decode($this->dbquery($cmd));
                    $qtd_total = ($result->records[0])->qtd_notas;
                    $qtd_total = intval($qtd_total);
                    
                    $cmd = "SELECT CONCAT(b.cd_servico_federal,'-',IFNULL(b.cd_subgrupo_federal,'00')) as `cd_servico`,
                            b.te_servico_federal, COUNT(a.id_nota) as qtd_notas, SUM(a.VS) as total_notas FROM vi_notas a INNER JOIN vi_depara b 
                            ON a.id_depara = b.id_depara AND a.DH BETWEEN b.dt_inivigencia_muni AND b.dt_fimvigencia_muni GROUP BY `cd_servico` ORDER BY SUM(a.VS) DESC";
                    $result = json_decode($this->dbquery($cmd));
                } else {
                    $cmd = "SELECT CONCAT(b.cd_servico_federal,'-',IFNULL(b.cd_subgrupo_federal,'00')) as `cd_servico`,
                            b.te_servico_federal, COUNT(a.id_nota) as qtd_notas, SUM(a.VS) as total_notas FROM vi_notas a INNER JOIN vi_depara b 
                            ON a.id_depara = b.id_depara AND a.DH BETWEEN b.dt_inivigencia_muni AND b.dt_fimvigencia_muni ";

                    $cmdgroup = "GROUP BY `cd_servico` ";
                    $cmdwhere = "WHERE ";
                    if (! is_null($cd_uf)) {
                        $cmdwhere .= "(a.UFP ='$cd_uf') AND";
                    }
                    if (! is_null(($id_muni))) {
                        $cmdwhere .= "(b.id_muni =$id_muni) AND ";
                    }
                    if (! is_null($id_prestador)) {
                        $cmdwhere .= "(a.id_prestador =$id_prestador) AND ";
                    }

                    if (! is_null($id_servico)) {
                        $cmdwhere .= "(b.id_servico =$id_servico) AND ";
                    }
                    if (! is_null($interval_data)) {
                        $cmdwhere .= "(DATE(a.DH) BETWEEN $interval_data) AND ";
                    }
                    
                    if (! is_null($interval_value)) {
                        $cmdwhere .= "(DATE(a.VS) BETWEEN $interval_value) AND ";
                    }
                    $cmdwhere = substr($cmdwhere, 0, strripos($cmdwhere,"AND")-1);
                    $cmd .= $cmdwhere;
                    $result = json_decode($this->dbquery($cmd));
                    $qtd_total = ($result->records[0])->qtd_notas;

                    $cmd .= $cmdwhere . $cmdgroup . " ORDER BY SUM(a.VS) DESC";
                }
                if ($result->nrecords > 0) {
                    self::$Error ='0';
                    $sum_rest = 0;
                    $qtd_rest = 0;
                    $perc_total = 0;
                    foreach($result->records as $nrow => $row) {
                        $row = get_object_vars($row);
                        if ($nrow < 10) {
                            $cd_servico = $row["cd_servico"];
                            $qtd_notas = intval($row['qtd_notas']);
                            $val_notas = floatval($row['total_notas']);
                            $perc = round($qtd_notas*100/$qtd_total, 2);
                            $perc_total += $perc;
                            $aux = array("cd_servfederal" => $cd_servico,  "total_notas" => $qtd_notas, "vl_total" =>$val_notas, "perc_total" => $perc);
                            array_push($vresult, $aux);
                        } else {
                            $qtd_rest += intval($row['qtd_notas']);
                            $sum_rest += floatval($row['total_notas']);
                        }
                    }
                    if ($qtd_rest > 0) {
                        $perc = 100 - $perc_total;
                        $aux = array("cd_servfederal" => "Outros",  "total_notas" => $qtd_rest, "vl_total" =>$sum_rest, "perc_total" => $perc);
                        array_push($vresult, $aux);
                    }

                } else {
                    self::$Error = '404';
                    self::$message = "Sem registros para contabilizar";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados inoperante";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vresult);
        }
    }
    class cls_dashnotasxservicos extends cls_connect {
        
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $id_nota=NULL;
        static $data=NULL;
        static $filename=NULL;
        static $message="";
        static $dbname = NULL;
        function __construct($id_nota=NULL) {
            self::$id_nota = $id_nota;
            parent::__construct(self::$dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = True;

            } 
        }  

        function getCursor() {
            if (self::$connected) {
                $cmd="SELECT * FROM `vi_notasxservicos_dash` ORDER by periodo DESC LIMIT 10";
                $result = json_decode ($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    self::$Error = '0';
                    $ntotal_records = $result->nrecords;
                    self::$message = "Total de registros " . $ntotal_records;
                    $vet = array();
                    $ntotal = 0;
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        $vl_notas = $row["total"];
                        $vl_notas = floatval($vl_notas);
                        $ntotal += $vl_notas;
                    }
                    
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        $cperiodo = $row['periodo'];
                        $vl_notas = $row["total"];
                        if ($cperiodo == "Total geral") {
                            $vl_total = $vl_notas;
                            $percent = "100";
                            continue;
                        }
                        $percent = round(($vl_notas / $ntotal) * 100,2);
                        $aux = array("Periodo" => $cperiodo, "Total" => $vl_notas, "Percent" => $percent);
                        array_push($vet, $aux);
                    }
                    $aux = array();
                    $i = count($vet) -1;
                    while ($i > 0) {
                        array_push($aux, $vet[$i]);
                        $i--;
                    }
                    $vet = $aux;
                    array_push ($vet, array("Período" => "", "Total" => $ntotal, "Percent" => "100"));
                } else {
                    self::$Error = '404';
                    self::$message = "Sem registros para contabilizar";
                    $vet = array();
                }
                return array("Error" => self::$Error, "Message" => self::$message, "Data_charts" => $vet);
            } else {
                return array("Error" => self::$Error, "Message" => self::$message, "Data_charts" => array());
            }
        }
    }  
     
    class cls_dashservpanel1 extends cls_connect {
           
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $id_servico=NULL;
        static $data=NULL;
        static $filename=NULL;
        static $message="";
        static $dbname = NULL;
        function __construct($id_servico=NULL) {
            self::$id_servico = $id_servico;
            parent::__construct(self::$dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = True;

            } 
        }  

        function getDashPanel1() {
            $vresult = array();
            if (self::$connected) {
                $cmd = "SELECT id_servico, desc_servico, SUM(qtd_notas) as qtd_notas, SUM(total_notas) as total_notas FROM vi_dash_servico GROUP BY id_servico";
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $cursor = $result->records;
                    foreach ($cursor as $row) {
                        $row = get_object_vars($row);
                        $id_servico = intval($row["id_servico"]);
                        $cd_servico = $row["desc_servico"];
                        $qtd_notas = $row["qtd_notas"];
                        $total_notas = $row["total_notas"];
                        array_push($vresult, array("id_servico" => $id_servico,"cd_servico" => $cd_servico,"qtd_notas" => $qtd_notas,"total_notas" =>$total_notas));
                    }
                    self::$Error = "0";
                    self::$message = "Encontrados " . $result->nrecords . " registros";
                } else {    
                    self::$Error = '404';
                    self::$message = "Sem registros para contabilizar";
                }
            }else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data"=> $vresult);
        }
    }

    class cls_dashtodoperiodo extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $oConn=NULL;
        static $message="";
        function __construct() {
            parent::__construct();
            self::$oConn = parent::$conn;
            if (self::$oConn) {
                self::$connected = True;
            }
        }

        function getDatas() {
            $data = array();

            if (self::$connected) {
                $cmd = "SELECT MIN(DATE(a.DH)) as dt_menordata, MAX(DATE(a.DH)) as dt_maiordata FROM vi_notas a";
                $result = json_decode($this->dbquery($cmd));
                if ($result->nrecords > 0) {
                    $cursor = ($result->records[0]);
                    $cursor = get_object_vars($cursor);
                    $dt_periodo_1 = $cursor['dt_menordata'];
                    $dt_periodo_2 = $cursor['dt_maiordata'];
                    self::$Error = '0';
                    self::$message = "Encontrados 1 registro";
                    $data = array('dt_periodo1' => $dt_periodo_1, "dt_periodo2" =>$dt_periodo_2);
                } else {
                    self::$Error = '404';
                    self::$message = "Encontrados 0 registro";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $data);
        }
        
    }
?>