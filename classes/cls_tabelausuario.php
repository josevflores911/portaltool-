<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once ("cls_connect.php");
   include_once ('../classes/cls_aesencdec.php');

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
           
        }

        //GET_ALL
        public function getAllActiveUsers(){
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
                        WHERE
                            u.cs_ativo = 'S'
                        ORDER BY u.id_user DESC
                        LIMIT 1000";
                        

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

        //GET_BY_ID
        public function getUserById($iduser){
                if (self::$conn) 
                        {
                            self::$connected=TRUE;
                            self::$conn = parent::$conn;
                            
                            $cmd = " SELECT 
                                        t.indice,
                                        t.id_usuario,
                                        t.nome_usuario,
                                        t.email_usuario,
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
                                            u.te_email AS email_usuario,
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


                            $te_pwd= base64_decode($result->records[0]->senha);
                            $oDecrypt = new AES_EncryptDecrypt(DECRYPT);
                            $te_newpwd = $oDecrypt->decrypt_password($te_pwd);
                            $result->records[0]->senha=$te_newpwd;
                            
                        
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

         //POST
         public function saveUser($nome, $codigo_acesso, $tipo_usuario, $agencia_id) {
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

                    $cd_acesso = $codigo_acesso;
                    $oDecrypt = new AES_EncryptDecrypt(1);
                    $te_pwd = $oDecrypt->encrypt_password(substr($cd_acesso,0,5) . '12345');
                    $te_pwd = base64_encode($te_pwd);                    

                    $stmt1 = self::$conn->prepare($query1);
                    $stmt1->bind_param("sssissssss", $codigo_acesso, $nome,$email,$idarea,$cd_matricula,$hora_inicio_jornada,$hora_inicio_jornada,$cd_token, $te_pwd, $tipo_usuario);
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

                    return array("Success" => "User salvo com sucesso");

                } catch (Exception $e) {

                    self::$conn->rollback();

                    self::$Error = '500';
                    self::$message = "Erro ao guardar o usuario: " . $e->getMessage();
                    return array("Error" => "500", "Message" => self::$message);
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Error" => "504");
            }
        }

        // $user_id,$agenciaxmuni_id
        //PUT
        public function updateUser($nome, $codigo_acesso, $senha, $tipo_usuario, $user_id, $agencia_id,$agenciaxmuni_id) {
            if (self::$conn) {
                self::$connected = TRUE;
                self::$conn = parent::$conn;
        
                self::$conn->begin_transaction();
        
                try {
                    // Primer UPDATE: Actualizar en tbusers
                    $query1 = "UPDATE `tbusers` SET
                                `cd_acesso` = ?, 
                                `nm_user` = ?, 
                                `te_pwd` = ?, 
                                `id_area` = ?, 
                                `cd_currposition` = ?, 
                                
                              WHERE `id_user` = ?";  
        
                    $stmt1 = self::$conn->prepare($query1);
                    $stmt1->bind_param("sssssis", $codigo_acesso, $nome, $senha, $tipo_usuario, $user_id); 
                    $stmt1->execute();
        
                    // Segundo UPDATE: Actualizar en tbagenciasxmunicipios
                    $query2 = "UPDATE `tbagenciasxmunicipios` SET 
                                `id_agencia` = ?
                              WHERE `id_agenciaxmunicipio` = ?"; 
        
                    $stmt2 = self::$conn->prepare($query2);
                    $stmt2->bind_param("ii", $agencia_id, $agenciaxmuni_id); 
                    $stmt2->execute();
        
                    // Obtener el ID de la agencia insertado o actualizado
                    // $last_agencia_id = self::$conn->insert_id;
        
                    // Tercer UPDATE: Actualizar en tbusersxmunicipios
                    $query3 = "UPDATE `tbusersxmunicipios` SET
                                `id_agenciaxmunicipio` = ?
                              WHERE `id_user` = ?";
        
                    $stmt3 = self::$conn->prepare($query3);
                    $stmt3->bind_param("ii", $agenciaxmuni_id, $user_id); // Usamos `last_agencia_id` para el `id_agenciaxmunicipio`
                    $stmt3->execute();
        
                    
                    //self::$conn->commit();
        
                    return array("Success" => "User updated successfully");
        
                } catch (Exception $e) {
                    
                    self::$conn->rollback();
        
                    self::$Error = '500';
                    self::$message = "Erro ao atualizar o usuario: " . $e->getMessage();
                    return array("Error" => "500", "Message" => self::$message);
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Error" => "504");
            }
        }

        //delete
        public function deleteUser($iduser){
            if (self::$conn) {
                self::$connected = TRUE;
                self::$conn = parent::$conn;

                self::$conn->begin_transaction();

                $user_status='N';

                try {
                    // Primer INSERT: Insertar en tbusers
                    $query1 = "UPDATE `tbusers`
                      SET 
                        `cs_ativo` = ?
                      WHERE `id_user` = ?";
                    $stmt1 = self::$conn->prepare($query1);
                    $stmt1->bind_param("si",$user_status,$iduser);
                    $stmt1->execute();
                 

                    // self::$conn->commit();

                    return array("Success" => "Status usuario inativo atualizado");

                } catch (Exception $e) {

                    self::$conn->rollback();

                    self::$Error = '500';
                    self::$message = "Erro ao guardar usuario: " . $e->getMessage();
                    return array("Erro" => "500", "Message" => self::$message);
                }
            } else {
                self::$Error = '504';
                self::$message = "Banco de dados desconectado";
                return array("Erro" => "504");
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
