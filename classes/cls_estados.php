<?php
   error_reporting(0);
   error_reporting(E_ALL);
    require_once "cls_connect.php";
     
    class cls_estados extends cls_connect {
         static $conn=NULL;
         static $estados=NULL;
         static $Error = '0';
         static $message='';
         static $connected = False;
         static $sel_dash = NULL;
         function __construct($sel_dash=NULL) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) 
            {
                self::$connected=True;
                if (is_null($sel_dash)) {
                    $cmd="SELECT cd_estado, nm_estado FROM vi_tbagenciasxmunicipios GROUP BY cd_estado ORDER BY cd_estado";
                    $result = json_decode($this->dbquery($cmd));
                    if ($result->nrecords > 0) {
                        self::$estados = [];
                        foreach ($result->records as $cursor) {
                            $cursor = get_object_vars($cursor);
                            array_push(self::$estados, $cursor);
                        }
                    }
                } else {
                    self::$sel_dash = $sel_dash;
                }
            }
         }
         public function conectado() {
            return self::$connected;
         }
         static function setEstados($estados) {
             self::$estados = $estados;
         }
         public function getData() {
            if (self::$connected)
                return self::$estados;
            else
                return NULL;
         }

         public function getDashEstados() {
            $data=array();
            if (self::$connected) {
                $cmd = "SELECT * FROM `dash_estados`";
                $result = json_decode($this->dbquery($cmd));
                foreach ($result->records as $row) {
                    $row = get_object_vars($row);
                    $cd_uf = $row["sigla"];
                    $nm_uf = $row["nome"];
                    $qtd_notas = $row["total_notas"];
                    $vl_notas = $row["valor_notas"];
                    $aux = array("cd_uf" => $cd_uf, 'nm_uf' => $nm_uf, "qtd_notas" => $qtd_notas, "vl_notas" => $vl_notas);
                    array_push ($data, $aux);
                }
                self::$Error = '0';
                self::$message = "Encontrados " . $result->nrecords . " registros";
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
            return array("Error" => self::$Error, "Message" => self::$message, "Data" => $data);
         }

         public function getStructure() {
            if (self::$connected) {
                return $this->tableStruct("estados");
            } else {
                return NULL;
            }
              
         }
     }
?>