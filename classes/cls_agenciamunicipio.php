<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once ("cls_connect.php");
   include_once ('../classes/cls_aesencdec.php');

   class cls_agenciamunicipio extends cls_connect {
        static $cursor = NULL;
        static $connected = FALSE;
        static $conn = NULL;
        static $Error = '0';
        static $message ="";

        function __construct()
        {
            parent::__construct();
            self::$conn = parent::$conn;
           
        }

        //GET_ALL
        public function getAgenciaXSistema($agencia,$sistema){
            if (self::$conn) 
            {
                self::$connected=TRUE;
                self::$conn = parent::$conn;
                
                $cmd = "   select * from tbagenciasxmunicipios where id_agencia=? and id_sistema=?;";
                        
                $result = json_decode($this->dbquery($cmd,$agencia,$sistema));
            
                if ($result->nrecords > 0) {
                    self::$Error = '0';
                    self::$message = 'Consulta realizada com sucesso';
                    self::$cursor = $result->records;
                    return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
                } else {
                    self::$Error = '404';
                    self::$message = "Consulta não retornou registros";
                    return array("Error" => "404");
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Error" => "504");
            }
        }
        

        public function getCursor () {
            if (self::$connected) {
                return array('Erro' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
            } else {
                return array("Erro" => "504");
            }
        }
   

}
?>
