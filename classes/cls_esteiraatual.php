<?php
error_reporting(0);
error_reporting(E_ALL);
require_once('cls_connect.php');

class cls_esteiraatual extends cls_connect {
    static $cursor = NULL;
    static $Error = '0';
    static $message = '';
    static $curr_page = 0;
    static $conn = NULL;
    static $total = 0;
    static $init = 1;
    static $limit = 8;
    static $cmd = NULL;
    static $cd_status = NULL;
    static $connected = FALSE;

    function __construct($cd_status=NULL) {
        parent::__construct();
        self::$conn = parent::$conn;
        if (self::$conn) {
            self::$connected = TRUE;
            self::$cd_status = $cd_status;
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
    }
    public function getRows($cd_status=NULL) {
        $vdados = array();
        if (self::$conn) {
            if (is_null($cd_status)) {
                $cd_status = self::$cd_status;
            }
            if (is_null($cd_status)) {
                $sql = "SELECT * FROM tbstatus";
                $result = json_decode($this->dbquery($sql));
            } else {
                $sql = "SELECT * FROM tbstatus WHERE cd_status =?";
                $result = json_decode($this->dbquery($sql, $cd_status));
            }
            if ($result->nrecords > 0) {
                foreach($result->records as $row) {
                    $row = get_object_vars($row);
                    array_push($vdados, $row);
                }
                self::$Error = '0';
            } else {
                self::$Error = '404';
            }
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
        return json_encode(array('Error' => self::$Error, 'Message'=> self::$message, 'Dados' => $vdados));
    }
}