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
                
                $cmd = "    SELECT 
                            ROW_NUMBER() OVER (ORDER BY u.id_user ASC) AS indice,
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
                        ORDER BY u.id_user ASC";

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

        public function getUserById($iduser){
                if (self::$conn) 
                        {
                            self::$connected=TRUE;
                            self::$conn = parent::$conn;
                            
                            $cmd = " SELECT 
                                        t.indice,
                                        t.id_usuario,
                                        t.nome_usuario,
                                      
                                        t.codigo_usuario,
                                        t.status_ativo,
                                        t.tipo_usuario,
                                        t.estado_usuario,
                                        t.municipio_usuario,
                                        t.agencia_usuario
                                    FROM (
                                        SELECT 
                                            ROW_NUMBER() OVER (ORDER BY u.id_user ASC) AS indice,
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
                                    ) t
                                    WHERE 
                                        t.indice = ?";

                            $result = json_decode($this->dbquery($cmd,$iduser));
                        
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
        public function getAllCountyByState($nome_estado="Alagoas"){
            if (self::$conn) 
                        {
                            self::$connected=TRUE;
                            self::$conn = parent::$conn;
                            
                            $cmd = "SELECT id_muni, nm_muni, nm_estado
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
                            
                            $cmd = " SELECT id_agencia, id_muni, nm_agencia
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


        public function saveUser ($nome, $codigo_acesso, $senha, $tipo_usuario_id,$status_ativo, $municipio_id, $agencia_id) {

            //to this
            // if (self::$conn) 
            // {
            //     self::$connected=TRUE;
            //     self::$conn = parent::$conn;
            
            
            // } else {
            //     self::$Error = '504';
            //     self::$message = "Banco de dados desconectado";
            //     return array("Error" => "504");
            // }

// adaptar
            //this
            // try {
            //     // Inserção na tabela de usuários
            //     $sql_usuario = "INSERT INTO usuarios (nome, codigo_acesso, senha, tipo_usuario_id) 
            //                     VALUES ('$nome', '$codigo_acesso', '$senha', $tipo_usuario_id)";
            //     if ($conexao->query($sql_usuario) === TRUE) {
                  
            //         $usuario_id = $conexao->insert_id;
        
            //         // Inserção na tabela de períodos de vigência
            //         $sql_periodo = "INSERT INTO periodos_vigencia (usuario_id, data_inicio, data_fim) 
            //                         VALUES ($usuario_id, '$data_inicio', '$data_fim')";
                    
            //         if ($conexao->query($sql_periodo) === TRUE) {
            //             // Inserção na tabela de relacionamentos Usuário-Município-Agência
            //             $sql_relacionamento = "INSERT INTO usuario_municipio_agencia (usuario_id, municipio_id, agencia_id) 
            //                                    VALUES ($usuario_id, $municipio_id, $agencia_id)";
        
            //             if ($conexao->query($sql_relacionamento) === TRUE) {
            //                 $conexao->commit();
            //                 $_SESSION['mensagem'] = 'Usuário inserido com sucesso!';
            //                 header('Location: ../index.php');
            //                 exit;
            //             } else {
                          
            //                 $conexao->rollback();
            //                 $_SESSION['mensagem'] = 'Erro ao inserir relacionamento: ' . $conexao->error;
            //                 // echo "Erro xx: " . $conexao->error . "<br>";
            //                  header('Location: ../index.php');
            //                 exit;
            //             }
            //         } else {
                       
            //             // echo "Erro xxx: " . $conexao->error . "<br>";
            //             $conexao->rollback();
            //             $_SESSION['mensagem'] = 'Erro ao inserir período de vigência: ' . $conexao->error;
            //              header('Location: ../index.php');
            //             exit;
            //         }
            //     } else {
            //         // echo "Erro x: " . $conexao->error . "<br>";
                  
            //         $conexao->rollback();
            //         $_SESSION['mensagem'] = 'Erro ao inserir usuário: ' . $conexao->error;
            //          header('Location: ../index.php');
            //         exit;
            //     }
            // } catch (Exception $e) {
              
            //     echo "Erro xxxx: " . $conexao->error . "<br>";
            //     $conexao->rollback();
            //     $_SESSION['mensagem'] = 'Erro ao processar a transação: ' . $e->getMessage();
            //      header('Location: ../index.php');
            //     exit;
            // }

        }

        public function updateUser ($nome, $codigo_acesso, $senha, $tipo_usuario_id,$status_ativo, $municipio_id, $agencia_id) {}
        

        public function getCursor () {
            if (self::$connected) {
                return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
            } else {
                return array("Error" => "504");
            }
        }
   

}
?>

