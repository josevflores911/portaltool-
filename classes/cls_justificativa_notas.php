<?php
error_reporting(0);
error_reporting(E_ALL);
require_once('cls_connect.php');

class cls_justificativa_notas extends cls_connect {
    static $cursor = NULL;
    static $Error = 0;
    static $message = '';
    static $conn = NULL;
    static $cmd = NULL;
    static $connected = FALSE;

    function __construct($te_justificativa) {
        parent::__construct();
        self::$conn = parent::$conn;
        if (self::$conn) {
            self::$connected = TRUE;
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
    }

    public function getCursor() {
        if (self::$connected) {
            $cmd = "SELECT * FROM dbptool24usbwf01pre.tbjustificativa_notas";
            $result = json_decode(self::dbquery($cmd), true);

            self::$Error = '0';
            self::$message = "Total de registros ".count($result);
        } else {
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
        return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => $result);
    }
}
?>