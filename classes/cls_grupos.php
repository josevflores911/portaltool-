<?php
    error_reporting(0);
        error_reporting(E_ALL);
    include_once ("cls_connect.php");
    class cls_grupos extends cls_connect {
        static $conn=NULL;
        static $cursor = NULL;
        static $connected=FALSE;
        static $erro = -1;
        static $message=NULL;
        static $id_nota = NULL;
        static $id_grupo = NULL;
        static $extra = array();
        static $sql = NULL;
        function __construct($id_nota=NULL, $id_grupo=NULL) {
            parent::__construct();
            self::$conn = parent::$conn;
           
            if (self::$conn) {
                self::$connected=TRUE;
                self::$id_nota=$id_nota;
                self::$id_grupo = $id_grupo;
                if (!is_null($id_grupo)){
                    self::$sql = "SELECT * FROM vi_grupos WHERE id_grupo=?;";
                } else {
                    self::$sql = 'SELECT id_grupo,Cd_Grupo, Te_Grupo FROM vi_grupos';
                }
                self::$sql .= " ORDER BY Cd_Grupo";
            } 
        }
        
        function getCursor() {
            if (self::$connected == FALSE) {
                self::$error ='504';
                self::$message="Banco de dados desconectado";
            } else {
                if (strpos(self::$sql, "WHERE") > 0) {
                    $result = json_decode($this->dbquery(self::$sql, self::$id_grupo));
                } else {
                    $result = json_decode($this->dbquery(self::$sql));
                }    
                if ($result->nrecords > 0) {
                    self::$cursor = array();
                    self::$error = '0';
                    foreach($result->records as $row) {
                        $row = get_object_vars($row);
                        array_push(self::$cursor, $row);
                    }
                    $id_gruponota = null;
                    $dt_inivigencia_federal = NULL;
                    $dt_fimvigencia_federal = NULL;
                    $dt_inivigencia_muni    = NULL;
                    $dt_fimvigencia_muni = NULL;
                    $id_servico = NULL;
                    $id_servmuni = NULL;
                        
                    if (!is_null(self::$id_nota)) {
                        $cmd = "SELECT * FROM vi_notasxservicos WHERE id_nota=?";
                        $result = json_decode($this->dbquery($cmd, self::$id_nota));
                        if ($result->nrecords > 0) {
                            $id_gruponota = ($result->records[0])->id_grupo;
                            $id_servico = ($result->records[0])->id_servico;
                            $id_servmuni = ($result->records[0])->id_servmuni;
                            $dt_inivigencia_federal = ($result->records[0])->dt_inivigencia_federal;
                            $dt_fimvigencia_federal = ($result->records[0])->dt_fimvigencia_federal;
                            $dt_inivigencia_muni    = ($result->records[0])->dt_inivigencia_muni;
                            $dt_fimvigencia_muni  = ($result->records[0])->dt_fimvigencia_muni;
                        } 
                    } 
                    if (! is_null($id_gruponota)) {
                       self::$extra = array("id_grupo", $id_gruponota,
                                            "id_servico" => $id_servico,
                                            "id_servmuni" => $id_servmuni,
                                            "dt_inivigencia_federal" => $dt_inivigencia_federal,
                                            "dt_fimvigencia_federal" => $dt_fimvigencia_federal,
                                            "dt_inivigencia_muni" => $dt_inivigencia_muni,
                                            "dt_fimvigencia_muni" => $dt_fimvigencia_muni);
                    }
                } else {
                    self::$error ='404';
                    self::$message="Registro de grupo não encontrado";
                }
            }
            return array("Error" => self::$error, "Message" => self::$message, "Data" => self::$cursor, "Extra" => self::$extra);
        }
    }
?>