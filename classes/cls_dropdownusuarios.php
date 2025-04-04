<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once ("cls_connect.php");

   class cls_dropdownusuarios extends cls_connect {
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
//---remove from here to cls_dropdownuser

        public function getAllUserType(){
            if (self::$conn) 
                {
                    self::$connected=TRUE;
                    self::$conn = parent::$conn;
                    
                    $cmd = " SELECT ROW_NUMBER() OVER (ORDER BY cd_currposition) AS indice, cd_currposition 
                                FROM (SELECT DISTINCT cd_currposition FROM tbusers) AS distinct_positions";

                    $result = json_decode($this->dbquery($cmd));
                
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

        public function getAllStates(){
            if (self::$conn) 
                {
                    self::$connected=TRUE;
                    self::$conn = parent::$conn;
                    
                    $cmd = " SELECT ROW_NUMBER() OVER (ORDER BY nm_estado) AS indice, nm_estado 
                            FROM (SELECT DISTINCT nm_estado FROM tbmunicipios) AS distinct_states";

                    $result = json_decode($this->dbquery($cmd));
                
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

        //municipios
        public function getAllCountyByState($nome_estado){
            if (self::$conn) 
                        {
                            self::$connected=TRUE;
                            self::$conn = parent::$conn;
                            
                            $cmd = "SELECT DISTINCT id_muni, nm_muni, nm_estado
                                        FROM tbmunicipios
                                        WHERE nm_estado = ?";

                            $result = json_decode($this->dbquery($cmd,$nome_estado));
                        
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

        public function getAllAgenciesByCounty($municipio_id){
            if (self::$conn) 
                        {
                            self::$connected=TRUE;
                            self::$conn = parent::$conn;
                            
                            $cmd = " SELECT DISTINCT id_agencia, id_muni, nm_agencia
                                        FROM tbagencias
                                        WHERE id_muni = ?";

                            $result = json_decode($this->dbquery($cmd,$municipio_id));
                        
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
