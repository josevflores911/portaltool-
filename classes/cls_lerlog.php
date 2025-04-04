<?php
   error_reporting(0);
   error_reporting(E_ALL);
   include_once('cls_connect.php');

   class cls_lerlog extends cls_connect {
        static $conn = null;
        static $connected = FALSE;
        static $cursor = NULL;
        static $noffset = 0;
        static $nrows = 20;
        static $cmd="";
        static $id_nota=NULL;
        static $id_user=NULL;
        static $total_records=NULL;
        static $id_tomador = NULL;
        static $id_prestador = NULL;
        static $cd_tipo=NULL;
        static $parms = "";
        function __construct($id_nota = NULL, $id_user=NULL, $curr_page=NULL, $nrows=NULL, $dbname=NULL) {
            parent::__construct($dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected=TRUE;

                if (! is_null($nrows)) {
                    self::$nrows = $nrows;
                }

            
                if (!is_null($curr_page)) {
                    self::$noffset = ($curr_page -1) * self::$nrows;
                } 

                if (!is_null($id_user)) {
                    self::$id_user=$id_user;
                }
                if (!is_null($id_nota)) {
                    self::$id_nota=$id_nota;
                }
                
                self::getTpUser($this);

                if (! is_null($id_nota) and ! is_null($id_user)) {
                    $cmd ="SELECT count(*) as total_records FROM vi_log WHERE id_user=? AND id_nota=?";
                    
                    $result = json_decode($this->dbquery($cmd, self::$id_user, self::$id_nota));
                } elseif (! is_null($id_nota) and is_null($id_user)) {
                    $cmd ="SELECT count(*) as total_records FROM vi_log WHERE id_nota=?";
                    $result = json_decode($this->dbquery($cmd, self::$id_nota));
                } elseif (is_null($id_nota) and ! is_null($id_user)) {
                    $cmd ="SELECT count(*) as total_records FROM vi_log WHERE id_user=?";
                    $result = json_decode($this->dbquery($cmd, self::$id_user));
                } elseif (is_null($id_nota) and is_null($id_user)) {
                    $cmd ="SELECT count(*) as total_records FROM vi_log";
                    $result = json_decode($this->dbquery($cmd));
                }
                
                self::$total_records = ($result->records[0])->total_records;
                
            }
        }

        static function getTpUser ($objConn) {
            if (self::$connected) {
                $cmd = "SELECT cd_tipo, id_prestador, id_tomador FROM vi_users WHERE id_user=?";
                $result = json_decode($objConn->dbquery($cmd, self::$id_user));
                
                if ($result->nrecords > 0) {
                    self::$cd_tipo = ($result->records[0])->cd_tipo;
                    self::$id_prestador = ($result->records[0])->id_prestador;
                    self::$id_tomador = ($result->records[0])->id_tomador;
                }
            }
        }
        static function getOffset($npage) {
            if (is_null($npage)) {
                return 0;
            }
            return ($npage -1) * self::$nrows;
        }

        public function getRows($nrows=20, $npage=0) {
            $nrows = intval($nrows);
            self::$nrows = ( !is_null($nrows) ) ? $nrows : self::$nrows;
       
            self::$noffset = self::getOffset($npage);

            if (self::$connected) {
                if (! is_null(self::$id_nota) and ! is_null(self::$id_user)) {
                    $cmd ="SELECT * FROM vi_log WHERE id_user=? AND id_nota=?";
                    $cmd .= " ORDER BY dt_log DESC LIMIT " . self::$nrows . " OFFSET " . self::$noffset;

                    $result = json_decode($this->dbquery($cmd, self::$id_user, self::$id_nota));
                } elseif (! is_null(self::$id_nota) and is_null(self::$id_user)) {
                    $cmd ="SELECT * FROM vi_log WHERE id_nota=?";
                    $cmd .= " ORDER BY dt_log DESC LIMIT " . self::$nrows . " OFFSET " . self::$noffset;
                    $result = json_decode($this->dbquery($cmd, self::$id_nota));
                } elseif (is_null(self::$id_nota) and ! is_null(self::$id_user)) {
                    $cmd ="SELECT * FROM vi_log WHERE id_user=?";
                    $cmd .= " ORDER BY dt_log DESC LIMIT " . self::$nrows . " OFFSET " . self::$noffset;
                    $result = json_decode($this->dbquery($cmd, self::$id_user));
                } elseif (is_null(self::$id_nota) and is_null(self::$id_user)) {
                    $cmd ="SELECT *  FROM vi_log";
                    $cmd .= " ORDER BY dt_log DESC LIMIT " . self::$nrows . " OFFSET " . self::$noffset;
                    $result = json_decode($this->dbquery($cmd));
                }

                if ($result->nrecords > 0) {
                    self::$cursor = array();
                    self::$cursor['Error'] = "0";
                    self::$cursor["total_records"] = self::$total_records;
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push(self::$cursor, $row);
                    }
                } else {
                    self::$cursor = array("Error" => "404");
                }
                return self::$cursor;

            } else {
                return array("Error" => "504");
            }
        }
    }
?>