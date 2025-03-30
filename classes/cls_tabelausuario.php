<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once ("cls_connect.php");

   class cls_tabelausuario extends cls_connect {
        static $cursor = NULL;
        static $connected = FALSE;
        static $conn = NULL;
        static $Error = '0';
        static $message ="";

        function __construct()
        {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) 
            {
                self::$connected=TRUE;
                self::$conn = parent::$conn;
                
                $cmd = "SELECT 
                            u.id_user AS id_usuario,
                            u.nm_user AS nome_usuario,
                            u.cd_acesso AS codigo_usuario,
                             u.cs_ativo AS status_ativo,
                            u.cd_currposition AS tipo_usuario,
                            m.nm_estado AS estado_usuario,
                            m.nm_muni AS municipio_usuario,
                            a.nm_agencia AS agencia_usuario
                        FROM 
                            tbusers u
                        JOIN 
                            tbusersxmunicipios um ON u.id_user = um.id_user
                        JOIN 
                            tbmunicipios m ON um.id_userxmunicipio = m.id_muni
                        JOIN 
                            tbagencias a ON um.id_agenciaxmunicipio = a.id_agencia
                        ORDER BY u.id_user asc";

                $result = json_decode($this->dbquery($cmd));
            
                if ($result->nrecords > 0) {
                    self::$Error = '0';
                    self::$message = 'Consulta realizada com sucesso';
                    self::$cursor = $result->records;
                } else {
                    self::$Error = '404';
                    self::$message = "Consulta não retornou registros";
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
            }
        }

        public function getCursor () {
            if (self::$connected) {
                return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
            } else {
                return array("Error" => "504");
            }
        }
   

}
?>