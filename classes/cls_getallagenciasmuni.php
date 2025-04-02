<?php
    error_reporting(0);
    error_reporting(E_ALL);
    require_once "cls_connect.php";

    class cls_agenciasMuni extends cls_connect {
        static $conn=NULL;
        static $Error = '0';
        static $message = '';
        static $cursor=NULL;
        static $connected=FALSE;
        static $id_muni=NULL;
        static $cd_estado=NULL;
        static $data=NULL;
        static $limit=10;
        static $total=0;
        static $totalpages=0;
        static $curr_page=1;
        static $change_limit=FALSE;
        
        public function __construct($id_muni=NULL, $cd_estado=NULL) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = TRUE;
                self::$id_muni = $id_muni;
                self::$cd_estado = $cd_estado;
                $cmd = "SELECT * FROM vi_tbagenciasxmunicipios ";
         
                if (is_null($cd_estado) and ! is_null($id_muni)) {
                    $cmd .= "WHERE id_muni=? GROUP BY cd_estado, id_muni,id_agencia ";
                    $result = json_decode($this->dbquery($cmd, $id_muni));
    
                } elseif ( ! is_null($cd_estado) and is_null($id_muni)) {
                    $cmd .= "WHERE cd_estado=? GROUP BY cd_estado, id_muni, id_agencia ";
                    $result = json_decode($this->dbquery($cmd, $cd_estado));
                } elseif ( ! is_null($cd_estado) and ! is_null($id_muni)) {
                    $cmd .= "WHERE cd_estado=? AND id_muni=? GROUP BY cd_estado, id_muni, id_agencia ";
                    $result = json_decode($this->dbquery($cmd, $cd_estado, $id_muni));
                } else {
                    $cmd .= " GROUP BY cd_estado, id_muni, id_agencia";
                    $result = json_decode($this->dbquery($cmd));
                }
                self::$total = $result->nrecords;
            }
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

        static function calcOffset($page) {
            $init = intval(($page -1) * self::$limit);
            
            return $init;
        }

        public function setLimitPage($nrows=10) {
            if ($nrows >= 5 and $nrows <= 50) {
                self::$limit = $nrows;
                self::$totalpages = self::calcTotalPages();
                self::$curr_page=1;
                self::$change_limit=True;
            }
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

        public function getTotalRecords() {
            return self::$total;
        }
        
        public function getRows($page=1, $filter = NULL) {
            $ler_cmd = False;
            $filter = False;
            $cursor = array();
            if($page == 1) {
                $init= 0;
            } else {
                $init = ($page -1) * self::$limit;
            }
   
            $cmd = "SELECT * FROM vi_tbagenciasxmunicipios ";
            $bfilter = FALSE;
            if (! is_null($filter)) {
                if (is_numeric($filter)) {
                    $bfilter = TRUE;
                }
            }
            if (is_null(self::$cd_estado) and ! is_null(self::$id_muni)) {
                $cmd .= "WHERE id_muni=? ";
                if ($bfilter) {
                    $cmd .= "AND (nu_cnpj LIKE CONCAT('%', ?,'%') OR cd_codagencia LIKE CONCAT('%',?,'%')) GROUP BY cd_estado, id_muni,id_agencia ";
                    $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$id_muni, $filter, $filter));
                } else {
                    $cmd .= "GROUP BY cd_estado, id_muni, id_agencia ";
                    $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$id_muni));
                }
            } elseif ( ! is_null(self::$cd_estado) and is_null(self::$id_muni)) {
                if ($bfilter) {
                    $cmd .= "WHERE cd_estado=? AND (nu_cnpj LIKE CONCAT('%', ?,'%') OR cd_codagencia LIKE CONCAT('%',?,'%')) GROUP BY cd_estado, id_muni,id_agencia ";
                    $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$cd_estado,$filter, $filter));
                } else {
                    $cmd .= "WHERE cd_estado=? GROUP BY cd_estado, id_muni,id_agencia ";
                    $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$cd_estado));
                }
            } elseif ( ! is_null(self::$cd_estado) and ! is_null(self::$id_muni)) {
                if ($bfilter) {
                    $cmd .= "WHERE cd_estado=? AND id_muni=? AND (nu_cnpj LIKE CONCAT('%', ?,'%') OR cd_codagencia LIKE CONCAT('%',?,'%')) GROUP BY cd_estado, id_muni, id_agencia ";
                    $cmd .= " LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$cd_estado, self::$id_muni,$filter, $filter));
                } else {
                    $cmd .= "WHERE cd_estado=? AND id_muni=? GROUP BY cd_estado, id_muni, id_agencia ";
                    $cmd .= "LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd, self::$cd_estado, self::$id_muni));
                }
            } else {
                if ($bfilter) {
                    $cmd .= "WHERE nu_cnpj LIKE CONCAT('%', ?,'%') OR cd_codagencia LIKE CONCAT('%',?,'%') GROUP BY cd_estado, id_muni, id_agencia ";
                    $result = json_decode($this->dbquery($cmd,$filter, $filter));
                } else {
                    $cmd .= " GROUP BY cd_estado, id_muni, id_agencia LIMIT " . self::$limit . " OFFSET " . $init;
                    $result = json_decode($this->dbquery($cmd));
                }
            }

            if ($result->nrecords > 0) {
                $cursor = array();
                foreach ($result->records as $key => $row) {
                    $cursor[$key] = get_object_vars($row);
                }
                self::$Error='0';
                self::$cursor = $cursor;

            } else {
                self::$cursor = [];
            }
            return array('data'=>$cursor);
        }
    }
?>