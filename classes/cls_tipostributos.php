<?php
error_reporting(0);
error_reporting(E_ALL);
require_once('cls_connect.php');

class cls_tipostributos extends cls_connect {
    static $cursor = NULL;
    static $Error = '0';
    static $message = '';
    static $curr_page = 0;
    static $conn = NULL;
    static $total = 0;
    static $init = 1;
    static $limit = 8;
    static $cmd = NULL;
    static $cd_tptributo = NULL;
    static $cd_contacontabila=NULL;
    static $connected = FALSE;

    function __construct($cd_tptributo) {
        parent::__construct();
        self::$conn = parent::$conn;
        if (self::$conn) {
            self::$connected = TRUE;
            self::$cd_tptributo = $cd_tptributo;
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
    }

    public function getCursor() {
        $vetDados = array();
        if (self::$connected) {
            $sql = "SELECT cd_contacontabil FROM tb_tipostributos WHERE tp_tributo=?";
            $result =json_decode($this->dbquery($sql,self::$cd_tptributo));
            if ($result->nrecords > 0) {
                foreach ($result->records as $row) {
                    $row = get_object_vars($row);
                    array_push($vetDados,$row);
                }
                self::$Error = '0';
                self::$message = 'Consulta realizada com sucesso.';
            } else {
                self::$Error = '404';
                self::$message = 'Divergência não encontrada.';
            }

        } else {
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
        return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => $vetDados);
    }
}