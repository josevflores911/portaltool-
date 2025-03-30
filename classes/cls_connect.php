<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    define ("DESENV", '1');
    define ("PRODUCAO", '2');
    /*
    try {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $parent = $_SERVER['HTTP_REFERER'];
        } else {
            $parent = NULL;
        }
    } catch (\Exception $e) {
        header('Location: ../index.php');
        die();
    }
    if (is_null($parent)) {
        header('Location: ../index.php');
        die();
    } 
    */
    class cls_connect extends mysqli {  
        static $tpAmbient = PRODUCAO;
        static $conn=NULL;
        static $nvez=3;
        static $private_access=False;
        static $inserted_id=-1;
        static $eh_insert=False;
        static $server = "177.153.58.200:14006";
        static $user = NULL;
        static $pwd = NULL;
        static $database=NULL;
        static $curr_now = NULL;
        static $vobjects=[];
        static $retCursor=[];
        static $struct_cmd=[];
        static $dbname=NULL;
        function __construct($dbname="dbptool24usbwf01pre", $server= "177.153.58.200:14006", $tpAmbient=PRODUCAO) {
            self::setAmbient($tpAmbient);
            if (self::$tpAmbient === PRODUCAO) {
                if (is_null($server)) {
                    self::$server = "177.153.58.200:14006";
                } else {
                    self::$server = $server;
                }
            } else {
                self::$server = "localhost";
            }
            if (is_null($dbname)) {
                self::$database = "dbptool24usbwf01pre";
                $dbname = "dbptool24usbwf01pre";
            } else {
                self::$database = $dbname;
            }
            
            self::$user = "admin";
            self::$pwd = "a6wggfgjv9ui";
            if (! self::$conn or self::$conn === NULL) {
                self::$conn= self::dbConnect();
            } else {
                if (! self::is_ping(self::$conn)) {
                    self::$conn = self::dbConnect();
                }
            }
            
            $this->selectDatabase($dbname);
        }

        public function getListDatabases() {
            $list_db = array();
            if (self::$conn) {
                $result = json_decode($this->dbquery("SHOW DATABASES"));
                if ($result->nrecords > 0) {
                    $rows = $result->records;
                    foreach ($rows as $row) {
                        $row = get_object_vars($row);
                        $dbname = $row['Database'];
                        array_push($list_db, $dbname);
                    }
                }
            }
            return $list_db;
        }

        public function selectDatabase($dbname=NULL) {
            self::$database = NULL;
            $bselect = False;
            if (self::$conn) {
                if (is_null($dbname)) 
                {
                    $dbname= self::$dbname;
                } else {
                    self::$dbname = $dbname;
                }
                $bselect=False;
                $list_db = $this->getListDatabases();
                if (in_array(self::$dbname, $list_db)) {
                    $bselect=True;
                }
                if ($bselect) {
                    self::$conn->select_db(self::$dbname);
                    $result = self::$conn->query("SELECT DATABASE()");
                    $row = $result->fetch_row();
                    foreach($row as $database) {
                        if ($database == $dbname) 
                        {
                            self::$database = $dbname;
                            break;
                        }
                    }
                }
            }
            return self::$database;
        }
        
        public function getAmbiente () {
            return self::$tpAmbient;
        }
        public function setAmbient($tpAmbient) {
            if ($tpAmbient == DESENV or $tpAmbient == PRODUCAO) {
                self::$tpAmbient = $tpAmbient;
            }
        }

        static function dbConnect() {
            try {
                $conn = new mysqli (self::$server,  self::$user, self::$pwd, self::$database);
	            if(!$conn){
                    self::$nvez -=1;
                    if (self::$nvez == 0) {
                        if (self::$private_access === False) {
                            self::$private_access = True;
                            self::$server = (self::$tpAmbient == PRODUCAO) ? "177.153.58.200:14006" : "127.0.1.1:3306";
                            self::$nvez =3;
                            return self::dbConnect();
                        } else {
                            self::$server = NULL;
                            return NULL;
                        }

                    } else {
                        return self::dbConnect();
                    }
                }
                $conn->set_charset("utf8");
                date_default_timezone_set('Brazil/East');
                set_time_limit(0);
                ini_set('max_execution_time', 9600);
                return $conn;
            } catch (mysqli_sql_exception  $e) {
                try {
                        self::$server = (self::$tpAmbient === PRODUCAO) ? "177.153.58.200:14006" : "127.0.1.1:3306";// servidor IP da aplicação
                        return self::dbConnect();
                    } catch (mysqli_sql_exception $e) {
                        echo "Error: " . $e->getMessage();
                        self::$server = NULL;
                        return NULL;
        
                    }
            } 
        }
        public function logout() {
            self::$conn->close();
        }
        static function is_ping() {
            $value=False;
            try {
                self::$conn->query("SELECT 1");
                $value=True;
            } catch (mysqli_sql_exception $e) {
                $value=False;
            }
            return $value;
        }

        static function ConvertToStr($num) {
            if ($num === 'undefined' or $num === NULL) {
                return '';
            } else {
                try {
                    if (gettype($num)  == 'float' or gettype($num) == 'double') {
                        $value = strval($num);
                    } else if (gettype($num) == 'date') {
                        $dt_aux = new DateTime($num);
                        $value = $dt_aux->format('Y-m-d');
                    } else if (gettype($num) == "datetime") {
                        $dt_aux = new DateTime($num);
                        $value = $dt_aux->format('Y-m-d H:i:s');
                    }
                    return $value;
                } catch (mysqli_sql_exception $e) {
                    die($e->getMessage());
                    return '';
                }
            }
        }

        static function stmt_prepare(string $sql) {
            
            try {
                if (self::is_ping()) {
                    $stmt = self::$conn->init();

                    $stmt = self::$conn->prepare($sql);
                    self::$nvez=0;
                    return $stmt;
                } else {
                    if (++self::$nvez > 3) {
                        die('Erro na tentativa de reconexão');
                    }
                    self::dbConnect();
                    return self::stmt_prepare($sql);
                }
            
            } catch (mysqli_sql_exception $e) {
                return array("comando:" . $sql . "erro" . $e->getCode() . ": " . $e->getMessage(), "nrecords" => -1);
            }
        }
        static function getTypeParameter($parameter) {
            $ctype = "s";
            if (gettype($parameter) == 'string') {
                $ctype ="s";
            } elseif (gettype($parameter) == 'int') {
                $ctype ="i";
            } elseif (gettype($parameter) == 'integer') {
                $ctype ="i";
            } elseif (gettype($parameter) == 'float') {
                $ctype ="d";
            } elseif (gettype($parameter) == 'double') {
                $ctype ="d";
            } elseif (gettype($parameter) == 'decimal') {
                $ctype ="d";
            } elseif (gettype($parameter) == 'date') {
                $ctype ="s";
            } elseif (gettype($parameter) == 'time') {
                $ctype ="s";
            } elseif (gettype($parameter) == 'datetime') {
                $ctype ="s";
            }
            return $ctype;
        }
 
        static function set_bindparms(...$parms) {
            $bind_param="";
            if (! empty($parms)) {
                foreach($parms as $param) {
                    if (gettype($param) == "array") {
                        foreach ($param as $lparam) {
                            $bind_param .= self::getTypeParameter($lparam);
                        }
                    } else {
                        $bind_param .= self::getTypeParameter($param);
                    }
                }   
            }
            return $bind_param;
        }

        static function eh_dml($cmd) {
            try {
                $vetor_cmd = array("INSERT", "DELETE", "UPDATE", "TRUNCATE", "DROP", "CREATE");
                $cmd_upper = strtoupper($cmd);
                $achei=False;
                self::$eh_insert = False;
                foreach ($vetor_cmd as $nix=>$command) {
                    if (strpos($cmd_upper, $command) !== False) {
                        if ($nix == 0) {
                            self::$eh_insert = true;
                        }
                        $achei=True;
                        break;
                    }
                }
                return $achei;

            } catch (\Exception $e) {
                echo $e->getCode() . $e->getMessage();
                die();
            }
        }
        public function getColumnsTable(string $tableName) {
            $vstruct = $this->tableStruct($tableName);
            $vcolumns = array();
            foreach ($vstruct as $nix => $row) {
                $field = $row[0];
                array_push($vcolumns, $field);
            }
            return $vcolumns;
        }

        public function tableStruct(string $tableName) {
            if (empty($tableName)) {
                return NULL;
            }
            $table_struct=[];
            try {
                $cmd = "DESCRIBE " . $tableName;
                if (self::is_ping()) {
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        $cursor = json_decode(json_encode($result->records), true);
                        foreach ($cursor as $nix=>$row) {
                            $type = strtolower($row['Type']);
                            if (strpos($type, "(")) {
                                $nlen = substr($type, strpos($type, "("));
                                $nlen = preg_replace("/\D+/","", $nlen);
                                $type = substr($type, 0, strpos($type, "("));
                            } elseif ($type == 'date') {
                                $nlen = 10;
                            } elseif ($type == "datetime") {
                                $nlen = 19;
                            } elseif ($type == 'time') {
                                $nlen = 8;
                            } elseif (strpos($type, "text")) {
                                $nlen = '255';
                            } else {
                                $nlen = -1;
                            }
                            $table_struct[$nix] = [$row['Field'], $type, $nlen];
                        }
                    
                    }
                }
            } catch (\mysqli_sql_exception $e) {
                $table_struct = NULL;
                if ($tableName) {
                    echo "Error: " . $e->getMessage() . " command: " . $tableName;
                } else {
                    echo "Error: " . $e->getMessage() . " command: " . $cmd;
                }
                die();
            }
            return $table_struct;
        }
        
        static function getStruct($cursor) {
            $struct_cmd=[];
            
            if ($cursor) {
                if (gettype($cursor) == 'string') {
                    return NULL;    
                } 
                foreach ($cursor as $ix =>$row) {
                    if ($ix==0) {
                        $nix=0;
                        
                        foreach($row as $pcol => $value) {
                            $struct_cmd[$nix] = $pcol;
                            $nix++;
                        }
                        break;
                    }
                }
            }
            return $struct_cmd;
        }
        /*----------------------------------------------------------------
            primeiro parametro da função é a string de parametros
        */

        public function dbqueryblob($vcmd,...$parms){
            self::$retCursor = NULL;
            $cursor = NULL;
            $nrecords = 0;
            $error = '0';
            try {
                $cmd = $vcmd[0];
                $str_bind = $vcmd[1];
                $blob_data = $vcmd[2];
                if (gettype($cmd) == 'string' and strlen($cmd) > 0) {
                    if (! self::is_ping()) {
                        self::$conn= self::dbConnect();
                    }
                    $stmt = self::stmt_prepare($cmd);
                    if (gettype($stmt) !== 'array') {
                        $blob_pos = 0;
                        $vstr = str_split($str_bind);
                        foreach ($vstr as $i => $v) {
                            if ($v == 'b') {
                                $blob_pos = $i;
                                break;
                            }
                        }
                        $stmt->bind_param($str_bind, ...$parms);
                        $stmt->send_long_data($blob_pos, $blob_data);
                        $result = $stmt->execute();

                        if ($result) {
                            if (self::eh_dml($cmd) == True) {
                                $nrecords = $stmt->affected_rows;
                                if ($nrecords > 0) {
                                    self::$conn->commit();
                                    if (self::$eh_insert) {
                                        self::$inserted_id = $stmt->insert_id;
                                    }
                                  
                                    $result = array('error'=>'0', 'nrecords' => $nrecords, 'records' => []);        
                                } else {
                                    if ($stmt->errno != 0) {
                                        $result = array("error"=> strval($stmt->$stmt->errno) . "-" . $stmt->error . "-" .$cmd, 'nrecords' => '-1', 'records' => $cursor);        
                                    } else {
                                        $result = array('error'=>'0', 'nrecords' => $nrecords, 'records' => []);        
                                    }
                                }
                            } else {
                                $rows = $stmt->get_result();
                                if ($rows) {
                                    $nrecords = $rows->num_rows;
                                    $cursor = $rows->fetch_all(MYSQLI_ASSOC);
                                    self::$retCursor = $cursor;
                                    $result = array('error' => "0", 'nrecords' => $nrecords, 'records' => $cursor, "sqlcommand" => $cmd);
                                } else {
                                    $result = array('error' => intval($stmt->errorno) . "-" . $stmt->error , 'nrecords' => $nrecords, 'records' => $cursor, "sqlcommand" => $cmd);
                                }
                            }
                        } else {
                            $result = array('error' =>  intval($stmt->errorno) . "-" . $stmt->error, 'nrecords' => $nrecords, 'records' => $cursor, "sqlcommand" => $cmd);
                        }
                        
                    } else {
                        $result = $stmt;
                    }
                    $stmt->close();
                } else {
                    $error='130-Comando inválido';
                    $result = array('error' => $error, 'nrecords' => $nrecords, 'records' => $cursor);
                }
            } catch (mysqli_sql_exception $e) {
                $result = array ('error'=> $e->getCode() . "-" . $e->getMessage(), 'nrecords' => '-1', 'records' => NULL);
            }
            return json_encode($result);
        }

                 
        public function dbquery($cmd, ...$parms) {
            self::$retCursor = NULL;
            $cursor = NULL;
            $nrecords = 0;
            $error = '0';
            
            try {
            
                if (gettype($cmd) == 'string' and strlen($cmd) > 0) {
                    if (! self::is_ping()) {
                        self::$conn= self::dbConnect();
                    }
                    
                    $stmt = self::stmt_prepare($cmd);
                    if (gettype($stmt) !== 'array') {
                        $binds = self::set_bindparms(...$parms);
                        if (empty($binds) == false) {
                            $stmt->bind_param($binds, ...$parms);
                        } 
                        $result = $stmt->execute();
                        
                        if ($result) {
                            if (self::eh_dml($cmd) == True) {
                                $nrecords = $stmt->affected_rows;
                          
                                if ($nrecords > 0) {
                                    self::$conn->commit();
                                    if (self::$eh_insert) {
                                        self::$inserted_id = $stmt->insert_id;
                                    }
                                  
                                    $result = array('error'=>'0', 'nrecords' => $nrecords, 'records' => []);        
                                } else {
                                    if ($stmt->errno != 0) {
                                        $result = array("error"=>  intval($stmt->errorno) . "-" . $stmt->error, 'nrecords' => '-1', 'records' => $cursor);        
                                    } else {
                                        $result = array('error'=>'0', 'nrecords' => $nrecords, 'records' => []);        
                                    }
                                    
                                }
                            } else {
                                $rows = $stmt->get_result();
                                
                                if ($rows) {
                                    $nrecords = $rows->num_rows;
                                    $cursor = $rows->fetch_all(MYSQLI_ASSOC);
                                    self::$retCursor = $cursor;
                                    $result = array('error' => "0", 'nrecords' => $nrecords, 'records' => $cursor);
                                    
                                } else {
                                    
                                    $result = array('error' =>  intval($stmt->errorno) . "-" . $stmt->error, 'nrecords' => $nrecords, 'records' => $cursor);
                                }
                            }
                            
                        } else {
                            $result = array('error' =>  intval($stmt->errorno) . "-" . $stmt->error, 'nrecords' => $nrecords, 'records' => $cursor);
                        }
                        $stmt->close();
                    } else {
                        $result = $stmt;
                    }
                } else {
                    $error='130';
                    $result = array('error' => $error, 'nrecords' => $nrecords, 'records' => $cursor);
                }
                
         
            } catch (mysqli_sql_exception $e) {
                $result = array ('error'=> $e->getCode() . "-" . $e->getMessage(), 'nrecords' => '-1', 'records' => NULL, "comando" => $cmd, "parms" => $parms);
                echo $cmd . " <br /> " . $e->getCode() . "-" . $e->getMessage();
            } 
            
            return json_encode($result); 
            
        }
        private function ConvertCursor() {
            if (self::$retCursor) {
                $cursor = self::$retCursor;
                $cursor = json_decode(json_encode($cursor), true);
                return $cursor;
            } else {
                return NULL;
            }
        }
        public function getField(string $field_name) {
            if (empty($field_name)) {
                return NULL;
            }
            $cursor = $this->ConvertCursor();
            if ($cursor) {
                $struct = self::getstruct($cursor);
                $bret = in_array($field_name,$struct);
                if ($bret == False) {
                    return NULL;
                } else {
                    $result = array_column($cursor, $field_name);
                    return $result;
                }
            } else {
                return NULL;
            }
        }

        public function getFieldType(string $field_name, string $tablename) {
            if (empty($field_name)) {
                return NULL;
            }
            $struct = $this->tableStruct($tablename);
            $field_name = strtolower($field_name);
            $bachei = FALSE;
            for ($i=0; $i < count($struct); $i++) {
                $line = $struct[$i];
                $field = strtolower($line[0]);
                $type = strtolower($line[1]);
                if ($field === $field_name) {
                    $bachei=true;
                    break;
                }
            }
            if ($bachei) {
                return $type;
            } else {
                return NULL;
            }
        }

        public function getConn() {
            return self::$conn;
        }

        public function getDbname() {
            if (self::$conn) {
                return self::$database;
            } else {
                return NULL;
            }
        }

        public function getServerConnected() {
            return self::$server;
        }

        public function getInsertedId() : int {
            return self::$inserted_id;
        }
        public function getCurrentStruct() {
            return self::$struct_cmd;
        }

        public function getCurrentCursor() {
            return self::$retCursor;
        }
    }
?>