<?php
error_reporting(E_ALL);
include_once('cls_connect.php');

class cls_statusmunicipio extends cls_connect {
    public static $conn = null;
    public static $cursor = null;
    public static $connected = false;
    public static $Error = '0';
    public static $message = '';

    function __construct() {
        parent::__construct();
        self::$conn = parent::$conn;
        
        if (self::$conn) {
            self::$connected = true;
            try {
                $cmd = "SELECT * FROM tbstatus_municipios";
                $result = json_decode($this->dbquery($cmd));
                
                if (isset($result->nrecords) && $result->nrecords > 0) {
                    self::$cursor = $result->records;
                } else {
                    self::$Error = '404';
                    self::$message = 'Nenhum registro encontrado';
                    self::$cursor = array();
                }
            } catch (PDOException $e) {
                self::$Error = '500';
                self::$message = 'Erro na consulta ao banco de dados: ' . $e->getMessage();
                self::$cursor = array();
            }
        } else {
            self::$Error = '504';
            self::$message = 'Erro na conexão ao banco de dados';
            self::$cursor = array();
        }
    }

    public function getCursor() {
        if (self::$connected) {
            return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
        } else {
            return array('Error' => self::$Error, 'Message' => self::$message);
        }
    }
}
?>
