<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once('cls_connect.php');
    include_once "cls_traitimg.php";


    class cls_anexos extends cls_connect {
        
        static $conn=NULL;
        static $Error = '0';
        static $cursor=NULL;
        static $connected=FALSE;
        static $id_nota=NULL;
        static $data=NULL;
        static $filename=NULL;
        static $te_assunto=NULL;
        static $has_anexos = FALSE;
        static $extension = NULL;
        static $checksum='1';
        static $mimetype= "";
        static $dbname = NULL;
        static $tbanexo = "notas_anexos";
        use getExtesionFiles;

        function __construct($id_nota, $filename=NULL, $te_assunto=NULL, $arquivo=NULL, $checksum=NULL, $modulo=NULL) {
            self::$dbname = self::getDatabaseName($modulo);
            parent::__construct(self::$dbname);
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = TRUE;
                self::$checksum = $checksum;
                self::$id_nota = $id_nota;
                self::$filename= $filename;
                $varray = $this->getExtension($filename);
                self::$extension = $varray[0];
                self::$mimetype = $varray[1];
                if (strlen(self::$extension) > 0)
                    self::$filename = str_replace("." . self::$extension, '', self::$filename);
                self::$te_assunto= $te_assunto;
                self::$data = self::readBlobData($arquivo);
                if (is_null($modulo )) {
                    self::$tbanexo = 'notas_anexos';
                } elseif ($modulo == "ntconsumo") {
                    self::$tbanexo = 'notas_anexos';
                } elseif ($modulo == "ntsefaz") {
                    self::$tbanexo = 'notas_anexos';
                } elseif ($modulo == "nttransporte") {
                    self::$tbanexo = "tbcte_anexos";
                }

                $cmd = "SELECT * FROM " . self::$tbanexo . " WHERE id_nota=?";
                if (is_null($filename)) {
                    $result= json_decode($this->dbquery($cmd, self::$id_nota));
                } else {
                    $cmd = "SELECT * FROM " . self::$tbanexo . " WHERE id_nota=? AND nm_filename=?";
                    $result= json_decode($this->dbquery($cmd, self::$id_nota, self::$filename));
                }
                if ($result->nrecords > 0) {
                    self::$has_anexos = TRUE;
                    self::$cursor = $result->records;
                }
            }
        }

         static function getDatabaseName($modulo) {
            $dbname = NULL;
            if (! is_null($modulo)) {
                $vet_exmodulo = array("ntconsumo" =>'Mbcp20211802wb1' , "ntsefaz" => 'Mbpf201215wb2', 'nttransporte' => 'Mbpf202202cte1');
                foreach ($vet_exmodulo as $key => $database) {
                    if ($key == $modulo) {
                        $dbname = $database;
                        break;
                    }
                }
                if (! is_null($dbname)) {
                    if ($modulo == "nttransporte") {
                        self::$tbanexo = "tbntcte_anexos";
                    }
                }
            }
            return $dbname;
        }
        
        function deleteAnexo($id_anexo) {
            $return = array("Error" => "400");
            if (self::$connected) {
                if (self::$cursor) {
                    $cmd = "DELETE FROM " . self::$tbanexo . " WHERE id_nota=? AND id_notaanexo=?";
                    $result = json_decode($this->dbquery($cmd, self::$id_nota, $id_anexo));
                    $return["Error"] = $result->error;
                }
            }
            return $return;
        }

        static function readBlobData($arquivo) {
            if (self::$connected) {
                try {
                    if (! is_null($arquivo)) {
                        $data = file_get_contents($arquivo);
                        $blob_nota = addslashes(base64_encode($data));
                    } else {
                        $blob_nota = NULL;
                    }
                    return $blob_nota;
                } catch(Exception $err) {
                    return NULL;
                }
            } else {
                return NULL;
            }
        }

        function getCursor() {
            $vlines = array();
            if (self::$connected) {
                self::$Error = '404';
                if (self::$has_anexos) {
                    $vlines = self::readAnexos();
                    if (count($vlines)  > 0) { 
                        self::$Error = '0';
                    }
                }
                $result = array("Error" => self::$Error, "Data" => $vlines);
            } else {
                $result = array('Error' => '504', "Data" => $vlines);
            }
            return $result;
        }

        static function readAnexos() {
            $vlines = array();
            if (self::$has_anexos) {
                foreach (self::$cursor as $row) {
                    $row = get_object_vars($row);
                    if (! is_null(self::$filename)) {
                        if (self::$filename == $row['nm_filename']) {
                            array_push($vlines, $row);
                            break;
                        } 
                    } else {
                        array_push($vlines, $row);
                    }
                }
            } 
            return $vlines;
        }

        function writeAnexo() {
            $id_notaanexo=NULL;
            if (self::$connected) {
                if (self::$has_anexos) {
                    $result = self::readAnexos();

                    if (count($result) === 0) {
                        if (! is_null(self::$data)) {
                            $cmd="INSERT INTO " . self::$tbanexo . " (id_nota, nm_filename, nm_filetype,te_mimetype,te_assunto,nu_endrecord,blob_anexo) VALUES (?,?,?,?,?,?,?);";
                            $str_parms = "isssssb";
                            $vet = array($cmd, $str_parms, self::$data);
                            $nulo = NULL;
                            $result = json_decode($this->dbqueryblob($vet, self::$id_nota,self::$filename,self::$extension,self::$mimetype, self::$te_assunto,self::$checksum,$nulo));
                        } else {
                            $cmd="INSERT INTO " . self::$tbanexo . " (id_nota, nm_filename, nm_filetype,te_mimetype,te_assunto,nu_endrecord) VALUES (?,?,?,?,?,?);";
                            $result = json_decode($this->dbquery($cmd, self::$id_nota, self::$filename, self::$extension,self::$mimetype, self::$te_assunto,self::$checksum));
                            
                        }
                        if ($result->error == '0') {
                            $id_notaanexo = $this->getInsertedId();
                        }
    
                    } else {
                        $id_notaanexo = $result["id_notaanexo"];
                        if (is_null(self::$data)) {
                            $cmd = "UPDATE " . self::$tbanexo . " SET id_nota=?, nm_filename=?, nm_filetype=?, te_mimetype= ?,te_assunto=?, nu_endrecord=? WHERE id_notaanexo=?";
                            $result = json_decode($this->dbquery($cmd, self::$id_nota, self::$filename, self::$extension, self::$mimetype, self::$te_assunto,self::$checksum, $id_notaanexo));
                        } else {
                            $nulo = NULL;
                            $cmd = "UPDATE " . self::$tbanexo . " SET id_nota=?, nm_filename=?, nm_filetype=?, te_mimetype=?, te_assunto=?, nu_endrecord=?, blob_anexo=? WHERE id_notaanexo=?";
                            $str_parms = "isssssbi";
                            $vet = array($cmd, $str_parms, self::$data);
                            $result = json_decode($this->dbqueryblob($vet, self::$id_nota, self::$filename, self::$extension, self::$mimetype, self::$te_assunto,self::$checksum,$nulo, $id_notaanexo));
                        }
                    }
                } else {
                    if (! is_null(self::$data)) {
                        $cmd="INSERT INTO " . self::$tbanexo . " (id_nota, nm_filename, nm_filetype,te_mimetype,te_assunto,nu_endrecord,blob_anexo) VALUES (?,?,?,?,?,?,?);";
                        $str_parms = "isssssb";
                        $nulo = NULL;
                        $vet = array($cmd, $str_parms, self::$data);
                        $result = json_decode($this->dbqueryblob($vet, self::$id_nota,self::$filename,self::$extension, self::$mimetype,self::$te_assunto,self::$checksum,$nulo));
                    } else {
                        $cmd="INSERT INTO " . self::$tbanexo . "(id_nota, nm_filename, nm_filetype,te_mimetype, te_assunto,nu_endrecord) VALUES (?,?,?,?,?,?);";
                        $result = json_decode($this->dbquery($cmd, self::$id_nota, self::$filename, self::$extension, self::$mimetype, self::$te_assunto,self::$checksum));
                    }
                    if ($result->error == '0') {
                        $id_notaanexo = $this->getInsertedId();
                    }
                }
                self::$Error = $result->error;
            }
            return array("Error" => self::$Error, "id_notaanexo" => $id_notaanexo, "filename" => self::$filename,);
        }
        
    }
?>