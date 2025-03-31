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
                                        t.senha,     
                                        t.codigo_usuario,
                                        t.status_ativo,
                                        t.tipo_usuario,
                                        t.estado_usuario,
                                        t.municipio_usuario,
                                        t.id_municipio,
                                        t.id_agenciaxmunicipio,
                                        t.id_agencia,
                                        t.agencia_usuario
                                       
                                    FROM (
                                        SELECT 
                                            ROW_NUMBER() OVER (ORDER BY u.id_user ASC) AS indice,
                                            u.id_user AS id_usuario,
                                            u.nm_user AS nome_usuario,
                                            u.te_pwd AS senha,
                                            u.cd_acesso AS codigo_usuario,
                                            u.cs_ativo AS status_ativo,
                                            u.cd_currposition AS tipo_usuario,
                                            m.nm_estado AS estado_usuario,
                                            m.nm_muni AS municipio_usuario,
											m.id_municipio as id_municipio,
                                           -- a.id_agenciaxmunicipio as id_agenciaxmunicipio,
                                            um.id_userxmunicipio as id_agenciaxmunicipio,
                                            a.id_agencia As id_agencia,
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

//
        public function saveUser($nome, $codigo_acesso, $senha, $tipo_usuario, $agencia_id) {
            if (self::$conn) {
                self::$connected = TRUE;
                self::$conn = parent::$conn;

                $email='a@a.com';//3
                $idarea=6;//4  de 1 ate 8
                $cd_matricula=12;//5
                $fecha_hardcodeadainiyfim = '2025-03-30';
                $hora_inicio_jornada  =  '08:00:00';
                $cd_token = '201sa';//just 6

                self::$conn->begin_transaction();

                try {
                    // Primer INSERT: Insertar en tbusers
                    $query1 = "INSERT INTO `tbusers` (
                                `cd_acesso`, 
                                `nm_user`,
                                `te_email`, 
                                `id_area`, 
                                `cd_matricula`, 
                                `hr_inicio_jornada`, 
                                `hr_fim_jornada`, 
                                `cd_token`, 
                                `te_pwd`, 
                                `cd_currposition`
                                
                            ) VALUES (
                                ?, ?, ?, ?, ?,?,?,?,?,?
                            )";
                    $stmt1 = self::$conn->prepare($query1);
                    $stmt1->bind_param("sssissssss", $codigo_acesso, $nome,$email,$idarea,$cd_matricula,$hora_inicio_jornada,$hora_inicio_jornada,$cd_token, $senha, $tipo_usuario);
                    $stmt1->execute();
                    $last_user_id = self::$conn->insert_id; // Obtener el ID del usuario insertado

                    // Segundo INSERT: Insertar en tbagenciasxmunicipios
                    $query2 = "INSERT INTO `tbagenciasxmunicipios` (
                                `id_agencia`
                            ) VALUES (?)";
                    $stmt2 = self::$conn->prepare($query2);
                    $stmt2->bind_param("i", $agencia_id);
                    $stmt2->execute();
                    $last_agencia_id = self::$conn->insert_id; // Obtener el ID generado para la agencia

                    // Tercer INSERT: Insertar en tbusersxmunicipios
                    $query3 = "INSERT INTO `tbusersxmunicipios` (
                                `id_user`, 
                                `id_agenciaxmunicipio`, 
                                `cd_currposition`
                            ) VALUES (
                                ?, ?,  ?
                            )";
                    $stmt3 = self::$conn->prepare($query3);
                    $stmt3->bind_param("iis", $last_user_id, $last_agencia_id, $tipo_usuario);
                    $stmt3->execute();


                    // self::$conn->commit();

                    return array("Success" => "User saved successfully");

                } catch (Exception $e) {

                    self::$conn->rollback();

                    self::$Error = '500';
                    self::$message = "Error al guardar el usuario: " . $e->getMessage();
                    return array("Error" => "500", "Message" => self::$message);
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Error" => "504");
            }
        }

        // $user_id,$agenciaxmuni_id
        public function updateUser($nome, $codigo_acesso, $senha, $tipo_usuario, $user_id, $agencia_id,$agenciaxmuni_id) {
            if (self::$conn) {
                self::$connected = TRUE;
                self::$conn = parent::$conn;
        
                // Iniciar transacción
                self::$conn->begin_transaction();
        
                try {
                    // Primer UPDATE: Actualizar en tbusers
                    $query1 = "UPDATE `tbusers` SET
                                `cd_acesso` = ?, 
                                `nm_user` = ?, 
                                `te_pwd` = ?, 
                                `id_area` = ?, 
                                `cd_currposition` = ?, 
                                
                              WHERE `id_user` = ?";  // Ahora se usa `id_user` para identificar al usuario
        
                    $stmt1 = self::$conn->prepare($query1);
                    $stmt1->bind_param("sssssis", $codigo_acesso, $nome, $senha, $tipo_usuario, $user_id); // Usamos `id_user` en WHERE
                    $stmt1->execute();
        
                    // Segundo UPDATE: Actualizar en tbagenciasxmunicipios
                    $query2 = "UPDATE `tbagenciasxmunicipios` SET 
                                `id_agencia` = ?
                              WHERE `id_agenciaxmunicipio` = ?"; // Se usa `id_agencia` para identificar la agencia existente
        
                    $stmt2 = self::$conn->prepare($query2);
                    $stmt2->bind_param("ii", $agencia_id, $agenciaxmuni_id); // Usamos `id_agencia` en WHERE
                    $stmt2->execute();
        
                    // Obtener el ID de la agencia insertado o actualizado
                    $last_agencia_id = self::$conn->insert_id;
        
                    // Tercer UPDATE: Actualizar en tbusersxmunicipios
                    $query3 = "UPDATE `tbusersxmunicipios` SET
                                `id_agenciaxmunicipio` = ?
                              WHERE `id_user` = ?"; // Usamos `id_user` para filtrar por el usuario
        
                    $stmt3 = self::$conn->prepare($query3);
                    $stmt3->bind_param("ii", $agenciaxmuni_id, $user_id); // Usamos `last_agencia_id` para el `id_agenciaxmunicipio`
                    $stmt3->execute();
        
                    // Confirmar la transacción
                    self::$conn->commit();
        
                    return array("Success" => "User updated successfully");
        
                } catch (Exception $e) {
                    // En caso de error, hacer rollback de la transacción
                    self::$conn->rollback();
        
                    self::$Error = '500';
                    self::$message = "Error al actualizar el usuario: " . $e->getMessage();
                    return array("Error" => "500", "Message" => self::$message);
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Error" => "504");
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
