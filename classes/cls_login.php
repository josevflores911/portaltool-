<?php
   error_reporting(0);
   error_reporting(E_ALL);
    require_once "cls_connect.php";
	include_once("cls_aesencdec.php");

    class cls_login extends cls_connect{
        protected static $logado = False;
        protected static $erro_login=NULL;
        protected static $username = NULL;
        protected static $password = NULL;
        protected static $message = "";
        protected static $administrador = 'N';
        protected static $id_area = NULL;
        protected static $id_muni = NULL;
        protected static $Id_User = NULL;
        protected static $cd_ipddress =NULL;
        protected static $cs_sistema = "N";
        protected static $id_prestador=NULL;
        protected static $id_tomador=NULL;
        protected static $cs_ativo = "S";
        protected static $cs_conferencia='N';
        protected static $cs_fornecedor='N';
        static $conn=NULL;
        protected static $tipo_user=NULL;
        protected static $client_ip=NULL;
        protected static $nm_usuario=NULL;

        function __construct($username = NULL, $password=NULL) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                if (self::$username == NULL and self::$password == NULL) {
                    self::$username = $username;
                    self::$password = $password;
                    if (! is_null($username)) {
                        $cmd = "SELECT * FROM tbusers WHERE cd_acesso=? LIMIT 1";
                    
                        $result = json_decode($this->dbquery($cmd, $username));
                        if ($result->nrecords == 1) {
                            $nrecords = $result->nrecords;
                            if ($nrecords == 0) {
                                self::$message = "Usuário não encontrado no " . self::$database;
                                self::$erro_login="100";
                            } else {
                                self::$logado=False;;
                                $cursor = get_object_vars($result->records[0]);
                                $senha_encrypted = base64_decode($cursor['te_pwd']);
                                /*
                                    usa o mesmo hash do portaltools para 
                                    resolver a questão da senha
                                */
                                $cls_encrypt = new AES_EncryptDecrypt(2);
                                $pwd_lida = $cls_encrypt->decrypt_password($senha_encrypted);
                                if ($pwd_lida !== $password) {
                                    self::$erro_login="200";
                                    self::$message = "Senha não confere";
                                } else {
                                        /* verifica se está logado */
                                        
                                    self::$Id_User = $cursor["id_user"];
                                    self::$id_area = $cursor["id_area"];
                                    self::$nm_usuario = $cursor["nm_user"];
                                    self::$tipo_user = $cursor['cd_currposition'];
                                    self::$cd_ipddress = $cursor['cd_ipaddress'];
                                    self::$cs_ativo = $cursor['cs_ativo'];
                                    self::$logado=True;
                                    self::$erro_login ="0";
                                }

                                if (self::$erro_login =="0") {
                                    self::$message = "conectado com sucesso";
                                }
                            }    
                        } else {
                            self::$message = "Usuário não encontrado no " . self::$database;
                            self::$erro_login ="300";
                        }
                    }
                } else {
                    self::$erro_login = '0';
                    self::$message = "Inicialização de classe sem dados";
                }
            } else {
                self::$message = "Erro na conexão com banco de dados";
                self::$erro_login = "440";
            }
        }

        public function getErroLogin () {
            return self::$erro_login;
        }

        public function getIdUser() {
            if ($this->conectado()) {
                return self::$Id_User;
            } else {
                return NULL;
            }
        }

        public function getUsername() {
            if ($this->conectado()) {
                return self::$nm_usuario;
            } else {
                return "";
            }
        }

        public function getMessage() {
            return self::$message;
        }

        
        public function eh_administrador() {
            if ($this->conectado()) {
                if (self::$administrador === "S") {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        }

        public function eh_fornecedor() {
            if ($this->conectado()) {
                if (self::$cs_fornecedor === "S") {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        }

        public function eh_sistema () {
            if ($this->conectado()) {
                if (self::$cs_sistema === "S") {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        }

        public function conectado() {
            return self::$logado ;
        }

        public function eh_ativo() {
            if ($this->conectado()) {
                if (self::$cs_ativo === "S") {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        }

        public function eh_conferente() {
            if ($this->conectado()) {
                if (self::$cs_conferencia=== "S") {
                    return True;
                } else {
                    return False;
                }
            } else {
                return False;
            }
        }

        static function get_client_ip() {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            return $ip;
        }

        public function getUserIP() {
            if ($this->conectado()) {
                return self::$client_ip;
            } else {
                return NULL;
            }
        }

        public function getAllOnline() {
            $cmd = "SELECT id_user, user_ipaddress FROM visitas WHERE cs_offline='N' AND DATE(data_inicio)=?";
            $data_pesq = date("Y-m-d");
            $result = json_decode($this->dbquery($cmd,$data_pesq));
            $list_users = array();
            if ($result->nrecords > 0) {
                foreach ($result->records as $ix =>$cursor) {
                    $cursor = get_object_vars($cursor);
                    $list_users[$ix] = array($cursor['id_user'], $cursor['user_ipaddress']);
                }
            }
            return $list_users;
        }

        public function getDetails() {
            $vetor_user=array();
            if ($this->conectado()) {
                $vetor_user = array (
                    "id_user" => self::$Id_User,
                    "nm_user" => self::$nm_usuario,
                    "cd_ipaddress" => self::$cd_ipddress,
                    "cs_admin" =>  (in_array(self::$tipo_user, array('Administrador', 'Gestor', 'Sistema'))) ? "S" : "N",
                    "tp_user" =>self::$tipo_user,
                    "id_area" => self::$id_area,
                    "cs_ativo" => self::$cs_ativo,
                );
            }
            return $vetor_user;
        }
    }
?>