<?php
    require_once "cls_connect.php";
    
    class cls_status extends cls_connect {
        protected static $message = "";
        protected static $cd_status = "";
        protected static $te_status = "";
        protected static $dict_row = array();
        static $conn=NULL;
        protected static $connected=FALSE;
        function __construct() {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = TRUE;
                $cmd = "SELECT * FROM status order by cast(cd_status as integer);";
                $results = json_decode($this->dbquery($cmd));
                if ($results->nrecords > 0) {
                    foreach ($results->records as $cursor) {
                        $cursor = get_object_vars($cursor); 
                        $cd_status = $cursor['cd_status'];
                        $te_status = $cursor['te_status'];
                        $color = $cursor['te_color'];
                        self::$dict_row[$cd_status] = array($te_status, $color);
                    }
               }
            }
        }
        public function getHistory($id_nota) {
            $vet = array();
            if (self::$connected) {
                $cmd = "SELECT * FROM tbnotasxstatus WHERE id_nota=? ORDER BY dt_registro DESC";
                $result = json_decode($this->dbquery($cmd, $id_nota));
                if ($result->nrecords > 0) {
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push($vet, $row);
                    }
                } else {
                    $vet["Error"] = "404";
                }
            } else {
                $vet["Error"] = "504";
            }
            return $vet;
        }

        public function getStatus($id_nota=NULL, $cd_status = NULL) {
            if (self::$connected) {
                if ($cd_status === NULL and $id_nota === NULL) {
                    return self::$dict_row;
                } else {
                    if ($id_nota == NULL and $cd_status !== NULL) {
                        return self::$dict_row[$cd_status];
                    } else {
                        $cmd = "SELECT cd_status, te_status, te_color FROM vi_last_status WHERE id_nota=?";
                        $result = json_decode($this->dbquery($cmd, $id_nota));
                        if ($result->nrecords > 0) {
                            $cursor = $result->records[0];
                            $line=array("cd_status" => $cursor->cd_status, "te_status" => $cursor->te_status, "te_color"=>$cursor->te_color);
                        } else {
                            $line=array("cd_status" => '0', "te_status" => "não informado", "te_color"=>"badge bg-dark text-white");
                        }
                        return $line;
                    }
                }
            } else {
                return NULL;
            }
        }
    }
?>