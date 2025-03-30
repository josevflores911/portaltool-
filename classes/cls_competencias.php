<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once('cls_connect.php');

    class cls_competencias extends cls_connect {
        static $conn=NULL;
        static $cursor=NULL;
        static $connected = FALSE;
        static $Error = '0';
        static $message='';
        static $ultimo = '';
        function __construct($id_user, $tp_user) {
            parent::__construct();
            self::$conn = parent::$conn;
            if (self::$conn) {
                self::$connected = TRUE;
                $v_todos =array('Sistema', 'Gestor', 'Administrador');
                if (in_array($tp_user, $v_todos)) {
                    $cmd ="SELECT distinctrow b.dt_compet FROM vi_usersxagenciasmuni a LEFT JOIN tbrecolhimentos b ON b.id_agencia = a.id_agencia ORDER BY b.dt_compet";
                    $result = json_decode($this->dbquery($cmd));
                } else {
                    $cmd ="SELECT distinctrow b.dt_compet FROM vi_usersxagenciasmuni a LEFT JOIN tbrecolhimentos b ON b.id_agencia = a.id_agencia 
                    WHERE a.id_user = ? ORDER BY b.dt_compet";
                    $result = json_decode($this->dbquery($cmd, $id_user));
                }
                self::$cursor = array();

                if ($result->nrecords > 0) {
                    foreach ($result->records as $row) {
                        $row = get_object_vars($row);
                        $dt_compet = $row['dt_compet'];
                        $aux = explode("-", $dt_compet);
                        $dt_compet = $aux[1] . '/'. $aux[0];
                        array_push(self::$cursor, ['dt_compet'=> $dt_compet]);
                    }
                    self::$ultimo = end(self::$cursor);
                    array_unshift(self::$cursor, self::$ultimo);
                    array_pop(self::$cursor);
                } else {
                    self::$Error = '404';
                    self::$message = 'Nenhum registro encontrado';
                }
            } else {
                self::$Error = '504';
                self::$message = 'Erro na conexão ao banco de dados';
            }
        }

        public function getCursor() {
            if (self::$connected) {
                return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
            } else {
                return array('Error' => self::$Error, 'Message' => self::$message);
            }
        }
        public function getUltimo() {
            if (self::$connected) {
                return self::$ultimo;
            } else {
                return NULL;
            }
        }
    }
?>