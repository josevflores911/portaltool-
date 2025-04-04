<?php
    error_reporting(0);
    error_reporting(E_ALL);
    require_once "cls_connect.php";
    require_once "cls_dashconsumo.php";
    require_once "cls_dashcte.php";
    require_once "cls_dashservices.php";
    
    class cls_dashsmultpanel extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $message="";
        static $dbname = NULL;
        static $cd_uf=NULL;
        static $id_muni=NULL; 
        static $id_prestador=NULL;
        static $id_tomador=NULL;
        static $id_servico=NULL;
        static $list_values = NULL;
        static $list_date = NULL;
        static $bfilter = FALSE;
        public function __construct($cd_uf=NULL, $id_muni=NULL, $id_prestador=NULL, $id_tomador=NULL,$id_servico=NULL,$list_values = NULL, $list_date = NULL) {
            parent::__construct(self::$dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = True;
                $this->setCdUF($cd_uf);
                $this->setIdMuni($id_muni); 
                $this->setIdPrestador($id_prestador);
                $this->setIdTomador($id_tomador);
                $this->setvalues($list_values);
                $this->setDates($list_date);
                $this->setIdServico($id_servico);
                $vfilter = array(self::hasFilterUF(), self::hasFilterMuni(), self::hasFilterPrestador(), self::hasFilterTomador(), self::hasFilterValues(), self::hasFilterDates());
                self::$bfilter = count(array_filter($vfilter)) > 0;
            
            }
        }

        static function hasFilterUF() {
            return (! is_null(self::$cd_uf) and ! empty(self::$cd_uf));
        }
        
        static function hasFilterMuni() {
            return (! is_null(self::$id_muni) and ! empty(self::$id_muni));
        }

        static function hasFilterPrestador() {
            return (! is_null(self::$id_prestador) and ! empty(self::$id_prestador));
        }

        static function hasFilterTomador() {
            return (! is_null(self::$id_tomador) and ! empty(self::$id_tomador));
        }

        static function hasFilterServico() {
            return (! is_null(self::$id_servico) and ! empty(self::$id_servico));
        }

        static function hasFilterValues() {
            if (gettype(self::$list_values) == 'array') {
                if (! empty(self::$list_values)) {
                    return (count(self::$list_values) > 0);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        static function hasFilterDates() {
            if (gettype(self::$list_date) == 'array') {
                if (! empty(self::$list_date)) {
                    return (count(self::$list_date) > 0);
                } else {
                    return false;
                }
            } else {
                return false;
            }
            
        }

        function getIdPrestador() {
            return self::$id_prestador;
        }
        function getIdTomador() {
            return self::$id_tomador;
        }
        function getIdMuni() {
            return self::$id_muni;
        }
        function getCdUF() {
            return self::$cd_uf;
        }
        
        function getIdServico() {
            return self::$id_servico;
        }

        function setIdServico($id_servico) {
            self::$id_servico = $id_servico;
        }

        function getDates() {
            if (gettype(self::$list_date) == 'array') {
                if (count(self::$list_date) >= 1) {
                    $data_1 = self::$list_date[0];
                    if (count(self::$list_date) == 1) {
                        $data_2 = date("Y-m-d ");
                    } else {
                        $data_2 = self::$list_date[1];
                    }
                    return array($data_1, $data_2);
                } else {
                    return NULL;
                }
            } else {
                return NULL;
            }
            
        }

        function getValues() {
            if (gettype(self::$list_values) == 'array') {
                if (count(self::$list_values) >= 1) {
                    $value_1 = self::$list_values[0];
                    if (count(self::$list_values) == 1) {
                        $value_2 = 9999999999999999999999.99;
                    } else {
                        $value_2 = self::$list_values[1];
                    }
                    return array($value_1, $value_2);
                } else {
                    return NULL;
                }
              
            } else {
                return NULL;
            }
            
        }

        function setIdPrestador($id_prestador) {
            self::$id_prestador = $id_prestador;
        }

        function setIdTomador($id_tomador) {
            self::$id_tomador = $id_tomador;
        }

        function setIdMuni($id_muni) {
            self::$id_muni = $id_muni;
        }

        function setCdUF($value) {
            self::$cd_uf = $value;
        }

        function setDates($values) {
            if (gettype($values) == 'array') {
                self::$list_date = $values;
            } elseif (gettype($values) == 'string') {
                self::$list_date = explode(",", $values);
            }

        }

        function setValues($values) {
            if (gettype($values) == 'array') {
                self::$list_values = $values;
            } elseif (gettype($values) == 'string') {
                self::$list_values = explode(",", $values);
            }
        }

        public function getPanel3() {
            $vet_result = array();
            if (self::$connected) {
                $sum_qtd = 0;
                $sum_values=0;
                if (self::$bfilter) {
                    if (self::hasFilterValues()) {
                        $values = $this->getValues();
                        $value_1 = $values[0];
                        $value_2 = $values[1];
                        $cmd="SELECT a.UFP as cd_uf,a.id_muniprestador as id_muni,a.id_prestador,a.id_tomador,b.id_servico, CONCAT(RIGHT(concat('0',DAY(a.dt_emissao)),2),'/', FC_GetNomeMes(a.dt_emissao)) AS dt_emissao,COUNT(a.id_nota) AS qtd_notas, SUM(a.VS) AS total_notas 
                        FROM vi_notas a INNER JOIN vi_depara b ON a.id_depara = b.id_depara AND a.DH BETWEEN b.dt_inivigencia_muni AND b.dt_fimvigencia_muni
                        WHERE a.VS BETWEEN $value_1 AND $value_2 GROUP BY cd_uf,id_muni,a.id_prestador, a.id_tomador, b.id_servico, DATE(a.DH) ORDER BY a.DH DESC LIMIT 30";
                        $result = json_decode($this->dbquery($cmd));
                        if ($result->nrecords > 0) {
                            self::$Error = '0';
                            self::$message = "Encontrados " . $result->nrecords . " registros";
                            $cursor = $result->records;
                            $dt_atu = null;
                            $nvez = 0;
                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $id_prestador = intval($row['id_prestador']);
                                $id_prestador = intval($row['id_prestador']);
                                $id_tomador   = intval($row["id_tomador"]);
                                $id_muni      = intval($row['id_muni']);
                                $id_servico   = intval($row['id_servico']);
                                $cd_uf        = $row['cd_uf']; 
                                $dt_emissao   = $row['dt_emissao'];
                                $qtd_notas    = intval($row['qtd_notas']);
                                $total_notas  = floatval($row['total_notas']);
                                if (self::hasFilterServico() and $id_servico != $this->getIdServico()) continue;
                                if (self::hasFilterPrestador() and $id_prestador != $this->getIdPrestador()) continue;
                                if (self::hasFilterTomador() and $id_tomador != $this->getIdTomador()) continue;
                                if (self::hasFilterUF() and $cd_uf != $this->getCdUF()) continue;
                                if (self::hasFilterMuni() and $id_muni != $this->getIdMuni()) continue;
                                if (self::hasFilterDates()) {
                                    $vdates = $this->getDates();
                                    $data_1 = $vdates[0];
                                    $data_2 = $vdates[1];
                                    $data_1 = date_create($data_1);
                                    $data_2 = date_create($data_2);
                                    $dt_emissao = date_create($dt_emissao);
                                    if ( $dt_emissao < $data_1 or $dt_emissao >$data_2) continue;
                                } else {
                                    $dt_emissao = date_create($dt_emissao);
                                }
                                if (is_null($dt_atu)) {
                                    $dt_atu = $dt_emissao;
                                    $sum_qtd = 0;
                                    $sum_values = 0;    
                                    $nvez = 1;
                                } else {
                                    if ($dt_atu != $dt_emissao) {
                                        if ($nvez > 0) {
                                            $dt_atu = date_format($dt_atu, "d/m/Y");
                                            array_push($vet_result, array("dt_emissao" => $dt_atu, "qtd_notas" => $sum_qtd, "total_notas" => $sum_values));
                                            $sum_qtd = 0;
                                            $sum_values = 0;    
                                        }
                                        $dt_atu = $dt_emissao;
                                    }
                                }
                                $sum_qtd    += $qtd_notas;
                                $sum_values += $total_notas;
                            }
                            if ($sum_qtd > 0) {
                                if (is_null($dt_atu))
                                    $dt_atu = $dt_emissao;
                                $dt_atu = date_format($dt_atu, "d/m/Y");
                                array_push($vet_result, array("dt_emissao" => $dt_atu, "qtd_notas" => $sum_qtd, "total_notas" => $sum_values));
                            }
                        }

                    } else {
                        $cmd="SELECT ";
                        $cmdwhere = " WHERE ";
                        $cmdgroup = " GROUP BY ";

                        if (self::hasFilterUF()) {
                            $cmd .= "a.UFP AS cd_uf,";
                            $cmdwhere .= "a.UFP='" . $this->getCdUF() . "' AND ";
                            $cmdgroup .= "a.UFP,";
                        }

                        if (self::hasFilterMuni()) {
                            $cmd .=  "a.id_muniprestador AS id_muni,";
                            $cmdwhere .= "a.id_muniprestador=" . $this->getIdMuni() . " AND ";
                            $cmdgroup .= "a.id_muniprestador,";
                        }
                        if (self::hasFilterPrestador()) {
                            $cmd .=  "a.id_prestador,";
                            $cmdwhere .= "a.id_prestador=" . $this->getIdPrestador() . " AND ";
                            $cmdgroup .= "a.id_prestador,";
                        }
                        if (self::hasFilterTomador()) {
                            $cmd .=  "a.id_tomador,";
                            $cmdwhere .= "a.id_tomador=" . $this->getIdTomador() . " AND ";
                            $cmdgroup .= "a.id_tomador,";
                        }
                        if (self::hasFilterServico()) {
                            $cmd .=  "a.id_servico,";
                            $cmdwhere .= "a.id_servico=" . $this->getIdServico() . " AND ";
                            $cmdgroup .= "a.id_servico,";
                        }

                        if (self::hasFilterDates()) {
                            $vdates = $this->getDates();
                            $data_1 = $vdates[0];
                            $data_2 = $vdates[1];
                            
                            $cmd .=  "CONCAT(RIGHT(concat('0',DAY(a.dt_emissao)),2),'/', FC_GetNomeMes(a.dt_emissao)) AS dt_emissao,";
                            $cmdwhere .= "(DATE(a.DH) BETWEEN '$data_1' AND '$data_2') AND ";
                            $cmdgroup .= "DATE(a.DH),";
                        }

                        $cont = "COUNT(a.id_nota) AS qtd_notas, SUM(a.VS) AS total_notas FROM vi_notas a INNER JOIN vi_depara b ON a.id_depara = b.id_depara AND ";
                        $cont .= " a.DH BETWEEN b.dt_inivigencia_muni AND b.dt_fimvigencia_muni ";
                        $cmd .= $cont;
                        $ix = strripos($cmdwhere, "AND");
                        $cmdwhere = substr($cmdwhere, $ix-1);
                        $cmd .= $cmdwhere;
                        $ix = strripos($cmdgroup, ",");
                        $cmdgroup = substr($cmdgroup, $ix-1);
                        $cmd .= $cmdwhere . $cmdgroup;
                        $cmd .= " ORDER BY a.DH DESC LIMIT 30";
                        $result = json_decode($this->dbquery($cmd));

                        if ($result->nrecords > 0) {
                            self::$Error = '0';
                            self::$message = "Encontrados " . $result->nrecords . " registros";
                            $cursor = $result->records;
                            $dt_atu = null;
                            $nvez = 0;
                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $dt_emissao   = date_create($row['dt_emissao']);
                                $qtd_notas    = intval($row['qtd_notas']);
                                $total_notas  = floatval($row['total_notas']);
                                $dt_emissao = date_format($dt_emissao, "d/m/Y");
                                if (is_null($dt_atu)) {
                                    $dt_atu = $dt_emissao;
                                    $sum_qtd = 0;
                                    $sum_values = 0;    
                                    $nvez = 1;
                                } else {
                                    if ($dt_atu != $dt_emissao) {
                                        if ($nvez > 0) {
                                            $dt_atu = date_format($dt_atu, "d/m/Y");
                                            array_push($vet_result, array("dt_emissao" => $dt_atu, "qtd_notas" => $sum_qtd, "total_notas" => $sum_values));
                                            $sum_qtd = 0;
                                            $sum_values = 0;    
                                        }
                                        $dt_atu = $dt_emissao;
                                    }
                                }
                                $sum_qtd    += $qtd_notas;
                                $sum_values += $total_notas;
                            }
                            if ($sum_qtd > 0) {
                                if (is_null($dt_atu))
                                   $dt_atu = $dt_emissao;
                                $dt_atu = date_format($dt_atu, "d/m/Y");
                                array_push($vet_result, array("dt_emissao" => $dt_atu, "qtd_notas" => $sum_qtd, "total_notas" => $sum_values));

                            }
                        }

                    }
                } else {
                    $cmd = "SELECT CONCAT(RIGHT(concat('0',DAY(a.dt_emissao)),2),'/', FC_GetNomeMes(a.dt_emissao)) as dt_emissao,
                    a.qtd_notas, a.total_notas FROM vi_dash_last_30 a ORDER BY dt_emissao";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        self::$Error = '0';
                        self::$message = "Encontrados " . $result->nrecords . " registros";
                        $cursor = $result->records;
                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $dt_emissao   = $row['dt_emissao'];
                            $qtd_notas    = intval($row['qtd_notas']);
                            $total_notas  = floatval($row['total_notas']);
                            array_push($vet_result, array("dt_emissao" => $dt_emissao, "qtd_notas" => $qtd_notas, "total_notas" => $total_notas));
                        }
                    }
                }
            } else {
                self::$Error = '504';
                self::$message="Banco de Dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vet_result);
        }

        public function getPanel2() {
            $vet_result = array();
            if (self::$connected) {
                // dash services panel1
                $sum_qtd = 0;
                $sum_values=0;

                if (self::$bfilter) {
                    if (self::hasFilterValues()) {
                        $values = $this->getValues();
                        $value_1 = $values[0];
                        $value_2 = $values[1];
                        $cmd= "SELECT IF(a.ICNPJP = '2', 'PJ','PF') AS cd_tipo, a.PorteP, a.UFP as cd_uf, a.id_muniprestador as id_muni,
                        a.id_prestador, a.id_tomador, b.id_servico, DATE(a.DH) as dt_emissao, COUNT(a.id_nota) as qtd_notas, SUM(a.VS) as total_notas
                        FROM vi_notas a, vi_depara b WHERE a.id_depara = b.id_depara AND a.VS BETWEEN ? AND ? AND a.ICNPJP='2' GROUP BY
                        a.ICNPJP, a.PorteP, a.UFP, a.id_muniprestador, a.id_tomador, b.id_servico, DATE(a.DH)";
                        $result = json_decode($this->dbquery($cmd, $value_1, $value_2));
                        $vet_tipo = array();
                        $vet_porte = array();
                        $auxfisica = array();
                        
                        if ($result->nrecords > 0) {
                            $cursor = $result->records;
                            $tipo_emp  = "";
                            self::$Error = '0';
                            self::$message = "Encontrados " . $result->nrecords . " registros";
                            
                            $porte_emp = "";
                            $qtd_tipo_emp=0;
                            $qtd_porte_emp=0;
                            $sum_tipo_emp = 0;
                            $sum_porte_emp = 0;
                            
                            $nvez_emp = 0;
                            $nvez_porte = 0;
                       

                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $cd_tipo      = $row["cd_tipo"];
                                $porte        = $row["PorteP"];
                                $id_prestador = intval($row['id_prestador']);
                                $id_prestador = intval($row['id_prestador']);
                                $id_tomador   = intval($row["id_tomador"]);
                                $id_muni      = intval($row['id_muni']);
                                $id_servico   = intval($row['id_servico']);
                                $cd_uf        = $row['cd_uf']; 
                                $dt_emissao   = $row['dt_emissao'];
                                $qtd_notas    = floatval($row['qtd_notas']);
                                $total_notas  = floatval($row['total_notas']);

                                if (self::hasFilterServico() and $id_servico != $this->getIdServico()) continue;
                                if (self::hasFilterPrestador() and $id_prestador != $this->getIdPrestador()) continue;
                                if (self::hasFilterTomador() and $id_tomador != $this->getIdTomador()) continue;
                                if (self::hasFilterUF() and $cd_uf != $this->getCdUF()) continue;
                                if (self::hasFilterMuni() and $id_muni != $this->getIdMuni()) continue;
                                if (self::hasFilterDates()) {
                                    $vdates = $this->getDates();
                                    $data_1 = $vdates[0];
                                    $data_2 = $vdates[1];
                                    $data_1 = new \DateTime($data_1);
                                    $data_2 = new \DateTime($data_2);
                                    $data_emissao = new \DateTime($dt_emissao);
                                    if ( $data_emissao < $data_1 or $data_emissao >$data_2) continue;
                                }

                                if ($tipo_emp !== $cd_tipo) {
                                    if ($nvez_emp > 0) {
                                        array_push($vet_tipo, array("tipo_emp" => $tipo_emp, "qtd_notas" => $qtd_tipo_emp, "total_notas" => $sum_tipo_emp));
                                        $sum_tipo_emp = 0;
                                        $qtd_tipo_emp=0;
                                    } else {
                                        $nvez_emp =1;
                                        $sum_tipo_emp = 0;
                                        $qtd_tipo_emp=0;
                                    }
                                    $tipo_emp = $cd_tipo;
                                }

                                if ($tipo_emp !== $porte) {
                                    if ($nvez_porte > 0) {
                                        array_push($vet_porte, array("porte_emp" => $porte_emp, "qtd_notas"=> $qtd_porte_emp, "total_notas" => $sum_porte_emp ));
                                        $sum_porte_emp = 0;
                                        $qtd_porte_emp=0;
                                    } else {
                                        $nvez_porte = 1;
                                        $sum_porte_emp = 0;
                                        $qtd_porte_emp=0;
                                    }
                                    $porte_emp = $porte;
                                }
                                $sum_porte_emp += $total_notas;
                                $qtd_porte_emp += $qtd_notas;
                                $sum_tipo_emp += $total_notas;
                                $qtd_tipo_emp += $qtd_notas;
                                $sum_qtd    += $qtd_notas;
                                $sum_values += $total_notas;
                            }

                            if ($qtd_porte_emp > 0) {
                                array_push($vet_porte, array("porte_emp" => $porte_emp, "qtd_notas"=> $qtd_porte_emp, "total_notas" => $sum_porte_emp ));   
                            }
                            if ($qtd_tipo_emp > 0) {
                                array_push($vet_tipo, array("tipo_emp" => $tipo_emp, "qtd_notas" => $qtd_tipo_emp, "total_notas" => $sum_tipo_emp));
                            }
                            array_push ($vaux, array("porte" => $vet_porte));
                            array_push ($vaux, array("tipo_emp" => $vet_tipo));

                            $cmd= "SELECT IF(a.ICNPJP = '2', 'PJ','PF') AS cd_tipo, a.PorteP, a.UFP as cd_uf, a.id_muniprestador as id_muni,
                            a.id_prestador, a.id_tomador, b.id_servico, DATE(a.DH) as dt_emissao, COUNT(a.id_nota) as qtd_notas, SUM(a.VS) as total_notas
                            FROM vi_notas a, vi_depara b WHERE a.id_depara = b.id_depara AND a.VS BETWEEN ? AND ? AND a.ICNPJP='1' GROUP BY
                            a.ICNPJP, a.PorteP, a.UFP, a.id_muniprestador, a.id_tomador, b.id_servico, DATE(a.DH)";
                            $result = json_decode($this->dbquery($cmd, $value_1, $value_2));
                            if ($result->nrecords > 0) {
                                $cursor = $result->records;
                                self::$Error = '0';
                                self::$message = "Encontrados " . $result->nrecords . " registros";
                                $qtd_fisica=0;
                                $total_fisica=0;

                                foreach ($cursor as $row) {
                                    $row = get_object_vars($row);
                                    $cd_tipo      = $row["cd_tipo"];
                                    $porte        = $row["PorteP"];
                                    $id_prestador = $row['id_prestador'];
                                    $id_prestador = $row['id_prestador'];
                                    $id_tomador   = $row["id_tomador"];
                                    $id_muni      = $row['id_muni'];
                                    $id_servico   = $row['id_servico'];
                                    $cd_uf        = $row['cd_uf']; 
                                    $dt_emissao   = $row['dt_emissao'];
                                    $qtd_notas    = floatval($row['qtd_notas']);
                                    $total_notas  = floatval($row['total_notas']);
    
                                    if (self::hasFilterServico() and $id_servico != $this->getIdServico()) continue;
                                    if (self::hasFilterPrestador() and $id_prestador != $this->getIdPrestador()) continue;
                                    if (self::hasFilterTomador() and $id_tomador != $this->getIdTomador()) continue;
                                    if (self::hasFilterUF() and $cd_uf != $this->getCdUF()) continue;
                                    if (self::hasFilterMuni() and $id_muni != $this->getIdMuni()) continue;
                                    if (self::hasFilterDates()) {
                                        $vdates = $this->getDates();
                                        $data_1 = $vdates[0];
                                        $data_2 = $vdates[1];
                                        $data_1 = new \DateTime($data_1);
                                        $data_2 = new \DateTime($data_2);
                                        $data_emissao = new \DateTime($dt_emissao);
                                        if ( $data_emissao < $data_1 or $data_emissao >$data_2) continue;
                                    }
                                    $sum_qtd    += $qtd_notas;
                                    $sum_values += $total_notas;
                                    $qtd_fisica += $qtd_notas;
                                    $total_fisica += $total_notas;
                                }

                            }
                            $auxfisica = array("Pessoa Física" => array("qtd_notas" => $qtd_fisica, "total_notas" => $total_fisica));
                        }
                        $vet_result = array("total_notas" => $sum_values, "qtd_notas" => $qtd_notas, "porte_emp" => $vet_porte, "tipo_emp" => $vet_tipo, $auxfisica);
                    } else {
                        $cmd = "SELECT 'PJ' AS cd_tipo, a.PorteP,";
                        $cmdWhere = " WHERE a.id_depara = b.id_depara AND  a.ICNPJP = '2' AND ";
                        $group = " GROUP BY a.ICNPJP, a.PorteP, ";

                        if (self::hasFilterPrestador()) {
                            $cmd .= "a.id_prestador,";
                            $cmdWhere .= "(a.id_prestador=" . $this->getIdPrestador() . ") AND ";
                            $group .= "a.id_prestador,";
                        }
                        if (self::hasFilterTomador()) {
                            $cmd .= "a.id_tomador,";
                            $cmdWhere .= "(a.id_tomador=" . $this->getIdTomador() . ") AND ";
                            $group .= "a.id_tomador,";
                        }
                        if (self::hasFilterServico()) {
                            $cmd .= "b.id_servico,";
                            $cmdWhere .= "b.id_servico=" . $this->getIdServico() . ") AND ";
                            $group .= "b.id_servico,";
                        }
                        if (self::hasFilterUF()) {
                            $cmd .= "a.cd_uf,";
                            $cmdWhere .= "(a.UFP='" . $this->getCdUF() . "') AND ";
                            $group .= "a.UFP,";
                        }
    
                        if (self::hasFilterMuni()) {
                            $cmd .= "a.id_muniprestador,";
                            $cmdWhere .= "(a.id_muniprestador=" . $this->getIdMuni() . ") AND ";
                            $group .= "a.id_muniprestador,";
                        }
                        $cmd .= "DATE(a.DH) as dt_emissao,";
                        if (self::hasFilterDates()) {
                            $vdata = $this->getDates();
                            $data_1 = $vdata[0];
                            $data_2 = $vdata[2];
                            $cmdWhere.= " DATE(a.DH) BETWEEN '" . $data_1 . "' AND '" . $data_2 . "' AND ";
                        }
                        $group .= "DATE(a.DH),";
                        
                        $ix = strripos($cmd,',');
                        $cmd = substr($cmd,0,$ix);
                        $cmd .= " FROM vi_notas a, vi_depara b ";

                        $ix = strripos($cmdWhere, "AND");
                        $cmdWhere = substr($cmdWhere,0,$ix);
                        $ix = strripos($group, ",");
                        $group = substr($group,0,$ix);
                        $cmd .= $cmdWhere;
                        $cmd .= $group;
                        $vet_tipo = array();
                        $vet_porte = array();
                        $auxfisica = array();

                        $result = json_decode($this->dbquery($cmd));
                        if ($result->nrecords > 0) {
                            self::$Error = '0';
                            self::$message = "Encontrados " . $result->nrecords . " registros";


                            $cursor = $result->records;
                        
                            $tipo_emp  = "";
                            $porte_emp = "";                            
                            $qtd_tipo_emp=0;

                            $qtd_porte_emp=0;
                            $sum_tipo_emp = 0;
                            $sum_porte_emp = 0;
                            
                            $nvez_emp = 0;
                            $nvez_porte = 0;
                       
                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $cd_tipo      = $row["cd_tipo"];
                                $porte        = $row["PorteP"];
                                $qtd_notas    = $row['qtd_notas'];
                                $total_notas  = $row['total_notas'];
                                $qtd_notas = floatval($qtd_notas);
                                $total_notas = floatval($total_notas);
                                  if ($tipo_emp !== $cd_tipo) {
                                    if ($nvez_emp > 0) {
                                        array_push($vet_tipo, array("tipo_emp" => $tipo_emp, "qtd_notas" => $qtd_tipo_emp, "total_notas" => $sum_tipo_emp));
                                        $sum_tipo_emp = 0;
                                        $qtd_tipo_emp=0;
                                    } else {
                                        $nvez_emp =1;
                                        $sum_tipo_emp = 0;
                                        $qtd_tipo_emp=0;
                                    }
                                    $tipo_emp = $cd_tipo;
                                }

                                if ($tipo_emp !== $porte) {
                                    if ($nvez_porte > 0) {
                                        array_push($vet_porte, array("porte_emp" => $porte_emp, "qtd_notas"=> $qtd_porte_emp, "total_notas" => $sum_porte_emp ));
                                        $sum_porte_emp = 0;
                                        $qtd_porte_emp=0;
                                    } else {
                                        $nvez_porte = 1;
                                        $sum_porte_emp = 0;
                                        $qtd_porte_emp=0;
                                    }
                                    $porte_emp = $porte;
                                }
                                $sum_porte_emp += $total_notas;
                                $qtd_porte_emp += $qtd_notas;
                                $sum_tipo_emp += $total_notas;
                                $qtd_tipo_emp += $qtd_notas;
                                $sum_qtd    += $qtd_notas;
                                $sum_values += $total_notas;
                            }
                            
                            $aux = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                            $vaux = array ("total_geral" => $aux);
                            if ($qtd_porte_emp > 0) {
                                array_push($vet_porte, array("porte_emp" => $porte_emp, "qtd_notas"=> $qtd_porte_emp, "total_notas" => $sum_porte_emp ));   
                            }
                            if ($qtd_tipo_emp > 0) {
                                array_push($vet_tipo, array("tipo_emp" => $tipo_emp, "qtd_notas" => $qtd_tipo_emp, "total_notas" => $sum_tipo_emp));
                            }
                            array_push ($vaux, array("porte" => $vet_porte));
                            array_push ($vaux, array("tipo_emp" => $vet_tipo));

                            $cmd = "SELECT IF(a.ICNPJP = '2', 'PJ','PF') AS cd_tipo, a.PorteP,";
                            $cmdWhere = " WHERE a.id_depara = b.id_depara AND  a.ICNPJP = '1' AND ";
                            $group = " GROUP BY a.ICNPJP, a.PorteP, ";
    
                            if (self::hasFilterPrestador()) {
                                $cmd .= "a.id_prestador,";
                                $cmdWhere .= "(a.id_prestador=" . $this->getIdPrestador() . ") AND ";
                                $group .= "a.id_prestador,";
                            }
                            if (self::hasFilterTomador()) {
                                $cmd .= "a.id_tomador,";
                                $cmdWhere .= "(a.id_tomador=" . $this->getIdTomador() . ") AND ";
                                $group .= "a.id_tomador,";
                            }
                            if (self::hasFilterServico()) {
                                $cmd .= "b.id_servico,";
                                $cmdWhere .= "b.id_servico=" . $this->getIdServico() . ") AND ";
                                $group .= "b.id_servico,";
                            }
                            if (self::hasFilterUF()) {
                                $cmd .= "a.cd_uf,";
                                $cmdWhere .= "(a.UFP='" . $this->getCdUF() . "') AND ";
                                $group .= "a.UFP,";
                            }
        
                            if (self::hasFilterMuni()) {
                                $cmd .= "a.id_muniprestador,";
                                $cmdWhere .= "(a.id_muniprestador=" . $this->getIdMuni() . ") AND ";
                                $group .= "a.id_muniprestador,";
                            }
                            $cmd .= "DATE(a.DH) as dt_emissao,";
                            if (self::hasFilterDates()) {
                                $vdata = $this->getDates();
                                $data_1 = $vdata[0];
                                $data_2 = $vdata[2];
                                $cmdWhere.= " DATE(a.DH) BETWEEN '" . $data_1 . "' AND '" . $data_2 . "' AND ";
                            }
                            $group .= "DATE(a.DH),";
                            
                            $ix = strripos($cmd,',');
                            $cmd = substr($cmd,0,$ix);
                            $cmd .= " FROM vi_notas a, vi_depara b ";
    
                            $ix = strripos($cmdWhere, "AND");
                            $cmdWhere = substr($cmdWhere,0,$ix);
                            $ix = strripos($group, ",");
                            $group = substr($group,0,$ix);
                            $cmd .= $cmdWhere;
                            $cmd .= $group;
    
                            $result = json_decode($this->dbquery($cmd));
                            if ($result->nrecords > 0) {
                                self::$Error = '0';
                                self::$message = "Encontrados " . $result->nrecords . " registros";
                                $cursor = $result->records;
                                $qtd_fisica = 0;
                                $total_fisica = 0;
    
                                foreach ($cursor as $row) {
                                    $row = get_object_vars($row);
                                    $cd_tipo      = $row["cd_tipo"];
                                    $porte        = $row["PorteP"];
                                    $qtd_notas    = $row['qtd_notas'];
                                    $total_notas  = $row['total_notas'];
                                    $qtd_notas = floatval($qtd_notas);
                                    $total_notas = floatval($total_notas);
                                   
                                    $sum_qtd    += $qtd_notas;
                                    $sum_values += $total_notas;
                                    $qtd_fisica += $qtd_notas;
                                    $total_fisica += $total_notas;
                                }

                            }    
                            $auxfisica = array("Pessoa Física" => array("qtd_notas" => $qtd_fisica, "total_notas" => $total_fisica));
                        }
                        $vet_result = array("total_notas" => $sum_values, "qtd_notas" => $qtd_notas, "porte_emp" => $vet_porte, "tipo_emp" => $vet_tipo, $auxfisica);
                    }
                } else {
                    $cmd="SELECT a.PorteP, SUM(a.qtd_notas) as qtd_notas, sum(a.total_notas) as total_notas FROM vi_dash_pj a GROUP BY a.PorteP";
                    $result = json_decode($this->dbquery($cmd));
                    $qtd_notas =0;
                    $total_notas = 0;
                    $porte = "";
                    $nvez = 0;
                    $vet_porte = array();
                    if ($result->nrecords > 0) {
                        self::$Error = '0';
                        self::$message = "Encontrados " . $result->nrecords . " registros de Pessoa jurídica";
                        $cursor = $result->records;
                        foreach ($cursor as $row) {
                            $row = get_object_vars($row);
                            $porte_lido = ($row["PorteP"]) ? $row["PorteP"] : "indefinido";
                            $qtd_notas_lido = floatval($row["qtd_notas"]);
                            $total_notas_lido = floatval($row["total_notas"]);
                            if ($porte_lido !== $porte) {
                                if ($nvez > 0) {
                                    array_push($vet_porte, array("porte" => $porte, "qtd_notas" => $qtd_notas, "total_notas" => $total_notas));
                                }
                                $nvez = 1;
                                $porte = $porte_lido;
                                $qtd_notas = 0;
                                $total_notas = 0;
                            }
                            $qtd_notas += $qtd_notas_lido;
                            $total_notas += $total_notas_lido;
                        }
                        if ($qtd_notas_lido > 0) {
                            array_push($vet_porte, array("porte" => $porte_lido, "qtd_notas" => $qtd_notas, "total_notas" => $total_notas));
                        }
                    } else {
                        array_push($vet_porte, array("porte" => "", "qtd_notas" => $qtd_notas, "total_notas" => $total_notas));
                    }
                    $cmd = "SELECT 'PF' AS cd_tipo, IFNULL(SUM(a.qtd_notas),0) as qtd_notas, IFNULL(SUM(a.total_notas),0) as total_notas FROM vi_dash_pf a";
                    $vet_fisica = array();
                    $qtd_notas = 0;
                    $total_notas = 0;
                    $tipo = 'PJ';
                    $result = json_decode($this->dbquery($cmd));

                    if ($result->nrecords > 0) {
                        self::$Error = '0';
                        self::$message = "Encontrados " . $result->nrecords . " registros de Pessoa fisicas";
                        $qtd_notas = ($result->records[0])->qtd_notas;
                        $total_notas = ($result->records[0])->total_notas;
                        $vet_fisica = array("tipo" => $tipo, "qtd_notas" => $qtd_notas, "total_notas" => $total_notas);
                    } else {
                        $vet_fisica = array("tipo" => $tipo, "qtd_notas" => $qtd_notas, "total_notas" => $total_notas);
                    }
                    $vet_result = array("PJ" => $vet_porte, "PF" => $vet_fisica);
                }
            } else {
                self::$Error = '504';
                self::$message="Banco de Dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vet_result);
        }

        public function getPanel1() {   
            $vet_result = array();
            if (self::$connected) {
                // dash services panel1
                $sum_qtd = 0;
                $sum_values=0;
                if (self::$bfilter) {
                    if (self::hasFilterValues()) {
                        $values = $this->getValues();
                        $value_1 = $values[0];
                        $value_2 = $values[1];
                        $cmd = "CALL PR_DASH_VALUES(?,?);";
                        $result = json_decode($this->dbquery($cmd,$value_1,$value_2));
                        
                        if ($result->nrecords > 0) {
                            $cursor = $result->records;
                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $id_prestador = $row['id_prestador'];
                                $id_tomador   = $row["id_tomador"];
                                $id_muni      = $row['id_muni'];
                                $id_servico   = $row['id_servico'];
                                $cd_uf        = $row['cd_uf']; 
                                $dt_emissao   = $row['dt_emissao'];
                                $qtd_notas    = $row['qtd_notas'];
                                $total_notas  = $row['total_notas'];
                                if (self::hasFilterServico() and $id_servico != $this->getIdServico()) continue;
                                if (self::hasFilterPrestador() and $id_prestador != $this->getIdPrestador()) continue;
                                if (self::hasFilterTomador() and $id_tomador != $this->getIdTomador()) continue;
                                if (self::hasFilterUF() and $cd_uf != $this->getCdUF()) continue;
                                if (self::hasFilterMuni() and $id_muni != $this->getIdMuni()) continue;
                                if (self::hasFilterDates()) {
                                    $vdates = $this->getDates();
                                    $data_1 = $vdates[0];
                                    $data_2 = $vdates[1];
                                    $data_1 = new \DateTime($data_1);
                                    $data_2 = new \DateTime($data_2);
                                    $data_emissao = new \DateTime($dt_emissao);
                                    if ( $data_emissao < $data_1 or $data_emissao >$data_2) continue;
                                }

                                $qtd_notas = floatval($qtd_notas);
                                $total_notas = floatval($total_notas);
                                $sum_qtd += $qtd_notas;
                                $sum_values += $total_notas;
                            }
                        }
                        $vet_nfse = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);

                    } else {
                        $cmd = "SELECT ";
                        $cmdWhere = " WHERE (NOT a.TDR = '2') AND (NOT a.id_muniprestador IS NULL) AND ";
                        $group = " GROUP BY ";

                        if (self::hasFilterPrestador()) {
                            $cmd .= "a.id_prestador,";
                            $cmdWhere .= "(a.id_prestador=" . $this->getIdPrestador() . ") AND ";
                            $group .= "a.id_prestador,";
                        }
                        if (self::hasFilterTomador()) {
                            $cmd .= "a.id_tomador,";
                            $cmdWhere .= "(a.id_tomador=" . $this->getIdTomador() . ") AND ";
                            $group .= "a.id_tomador,";
                        }
                        if (self::hasFilterServico()) {
                            $cmd .= "a.id_servico,";
                            $cmdWhere .= "a.id_servico=" . $this->getIdServico() . ") AND ";
                            $group .= "a.id_servico,";
                        }
                        if (self::hasFilterUF()) {
                            $cmd .= "a.a.UFP,";
                            $cmdWhere .= "(a.UFP='" . $this->getCdUF() . "') AND ";
                            $group .= "a.UFP,";
                        }
    
                        if (self::hasFilterMuni()) {
                            $cmd .= "a.id_muni,";
                            $cmdWhere .= "(a.id_muni=" . $this->getIdMuni() . ") AND ";
                            $group .= "a.id_muni,";
                        }
                        $cmd .= "DATE(a.DH) as dt_emissao,";
                        if (self::hasFilterDates()) {
                            $vdata = $this->getDates();
                            $data_1 = $vdata[0];
                            $data_2 = $vdata[2];
                            $cmdWhere.= " dt_emissao BETWEEN '" . $data_1 . "' AND '" . $data_2 . "' AND ";
                        }
                        $group .= "DATE(a.DH),";
                        
                        $ix = strripos($cmd,',');
                        $cmd = substr($cmd,0,$ix);
                        $cmd .= " FROM vi_notasxserv a";

                        $ix = strripos($cmdWhere, "AND");
                        $cmdWhere = substr($cmdWhere,0,$ix);
                        $ix = strripos($group, ",");
                        $group = substr($group,0,$ix);
                        $cmd .= $cmdWhere;
                        $cmd .= $group;
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
                        }
                        $vet_nfse = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    }
                } else {
                    $cmd = "SELECT SUM(qtd_notas) as qtd_notas,SUM(total_notas) as total_notas FROM vi_dash_nfse";
                    $result = json_decode($this->dbquery($cmd));
                    $vet_nfse = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    if ($result->nrecords > 0) {
                        $cursor = $result->records[0];
                        $sum_qtd = intval($cursor->qtd_notas);
                        $sum_values = floatval($cursor->total_notas);
                        $vet_nfse = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    } 
                }

                // pega dados do consumo
                $sum_qtd = 0;
                $sum_values=0;

                $list_dates = $this->getDates();
                $list_values = $this->getValues();
                $oConsumo = new cls_dashconsumo();
                $result = $oConsumo->getPanel1($list_dates, $list_values);
                $vet_consumo = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                if ($result["Error"] == '0') {
                    $vet_consumo = $result['Data'];
                }

                // pega dados do CTE
                $sum_qtd = 0;
                $sum_values=0;
                $oCte = new cls_dashcte();
                $result = $oCte->getPanel1($list_dates, $list_values);
                $vet_cte =array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                if ($result["Error"] == '0') {
                    $vet_cte = $result['Data'];
                }
                // danfe
                // seleciona o database servicos
                $this->selectDatabase("Mbpf201215wb2");

                $sum_qtd = 0;
                $sum_values=0;

                if (self::$bfilter) {
                    if (self::hasFilterValues()) {
                        $values = $this->getValues();
                        $value_1 = $values[0];
                        $value_2 = $values[1];
                        $cmd = "call PR_DASH_DANFEVALUES(?,?)";
                        $result = json_decode($this->dbquery($cmd,$value_1,$value_2));

                        if ($result->nrecords > 0) {
                            $cursor = $result->records;
                            foreach ($cursor as $row) {
                                $row = get_object_vars($row);
                                $id_prestador = $row['id_prestador'];
                                $id_tomador   = $row["id_tomador"];
                                $id_muni      = $row['id_muni'];
                                $id_servico   = $row['id_servico'];
                                $cd_uf        = $row['cd_uf']; 
                                $dt_emissao   = $row['dt_emissao'];
                                $qtd_notas    = $row['qtd_notas'];
                                $total_notas  = $row['total_notas'];
                                if (self::hasFilterServico() and $id_servico != $this->getIdServico()) continue;
                                if (self::hasFilterPrestador() and $id_prestador != $this->getIdPrestador()) continue;
                                if (self::hasFilterTomador() and $id_tomador != $this->getIdTomador()) continue;
                                if (self::hasFilterUF() and $cd_uf != $this->getCdUF()) continue;
                                if (self::hasFilterMuni() and $id_muni != $this->getIdMuni()) continue;
                                if (self::hasFilterDates()) {
                                    $vdates = $this->getDates();
                                    $data_1 = $vdates[0];
                                    $data_2 = $vdates[1];
                                    $data_1 = new \DateTime($data_1);
                                    $data_2 = new \DateTime($data_2);
                                    $data_emissao = new \DateTime($dt_emissao);
                                    if ( $data_emissao < $data_1 or $data_emissao >$data_2) continue;
                                }
                                $qtd_notas = floatval($qtd_notas);
                                $total_notas = floatval($total_notas);
                                $sum_qtd += $qtd_notas;
                                $sum_values += $total_notas;
                            }
                        }
                        $vet_danfe = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);

                    } else {
                        $cmd = "SELECT ";
                        $cmdWhere = " WHERE (a.TDR = '7') AND (NOT a.id_muniprestador IS NULL) AND ";
                        $group = " GROUP BY ";

                        if (self::hasFilterPrestador()) {
                            $cmd .= "a.id_prestador,";
                            $cmdWhere .= "(a.id_prestador=" . $this->getIdPrestador() . ") AND ";
                            $group .= "a.id_prestador,";
                        }
                        if (self::hasFilterTomador()) {
                            $cmd .= "a.id_tomador,";
                            $cmdWhere .= "(a.id_tomador=" . $this->getIdTomador() . ") AND ";
                            $group .= "a.id_tomador,";
                        }
                        if (self::hasFilterUF()) {
                            $cmd .= "a.cd_uf,";
                            $cmdWhere .= "(a.UFP='" . $this->getCdUF() . "') AND ";
                            $group .= "a.UFP,";
                        }
    
                        if (self::hasFilterMuni()) {
                            $cmd .= "a.id_muni,";
                            $cmdWhere .= "(a.id_muni=" . $this->getIdMuni() . ") AND ";
                            $group .= "a.id_muni,";
                        }

                        if (self::hasFilterServico()) {
                            $cmd .= "a.id_servico,";
                            $cmdWhere .= "(a.id_servico=" . $this->getIdServico() . ") AND ";
                            $group .= "a.id_servico,";

                        }
                        $cmd .= "DATE(a.DH) as dt_emissao,";
                        if (self::hasFilterDates()) {
                            $vdata = $this->getDates();
                            $data_1 = $vdata[0];
                            $data_2 = $vdata[2];
                            $cmdWhere.= " dt_emissao BETWEEN '" . $data_1 . "' AND '" . $data_2 . "' AND ";
                        }
                        $group .= "DATE(a.DH),";
                        
                        $ix = strripos($cmd,',');
                        $cmd = substr($cmd,0,$ix);
                        $cmd .= " FROM vi_notas a";

                        $ix = strripos($cmdWhere, "AND");
                        $cmdWhere = substr($cmdWhere,0,$ix);
                        $ix = strripos($group, ",");
                        $group = substr($group,0,$ix);
                        $cmd .= $cmdWhere;
                        $cmd .= $group;
                        
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
                        }
                        $vet_danfe = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    }
                } else {
                    $vet_danfe = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    $cmd = "SELECT SUM(qtd_notas) as qtd_notas,SUM(total_notas) as total_notas FROM vi_dash_danfe";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        $cursor = $result->records[0];
                        $sum_qtd = $cursor->qtd_notas;
                        $sum_values = $cursor->total_notas;
                        $vet_danfe = array("qtd_notas" => $sum_qtd, "total_notas" => $sum_values);
                    } 
                }
                $qtd_total = $vet_nfse['qtd_notas']+$vet_danfe['qtd_notas']+$vet_consumo['qtd_notas'] + $vet_cte['qtd_notas'];
                $vl_total =$vet_nfse['total_notas']+$vet_danfe['total_notas']+$vet_consumo['total_notas'] + $vet_cte['total_notas'];

                $vet_nfse['perc_total'] = round($vet_nfse['qtd_notas']*100/$qtd_total,2);
                $vet_nfse['perc_valor'] = round($vet_nfse['total_notas']*100/$vl_total,2);

                $vet_danfe['perc_total'] = round($vet_danfe['qtd_notas']*100/$qtd_total,2);
                $vet_danfe['perc_valor'] = round($vet_danfe['total_notas']*100/$vl_total,2);

                $vet_consumo['perc_total'] = round($vet_consumo['qtd_notas']*100/$qtd_total,2);
                $vet_consumo['perc_valor'] = round($vet_consumo['total_notas']*100/$vl_total,2);

                $vet_cte['perc_total'] = round($vet_cte['qtd_notas']*100/$qtd_total,2);
                $vet_cte['perc_valor'] = round($vet_cte['total_notas']*100/$vl_total,2);

                $vet_result = array("nfse" => $vet_nfse, "danfe" => $vet_danfe, "consumo" => $vet_consumo, "cte" => $vet_cte);
                self::$Error = '0';
                self::$message="Dados coletados com sucesso";
            } else {
                self::$Error = '504';
                self::$message="Banco de Dados não conectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $vet_result);
        }
    }
?>
