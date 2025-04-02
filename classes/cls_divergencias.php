<?php
error_reporting(0);
error_reporting(E_ALL);
require_once('cls_connect.php');

class cls_divergencias extends cls_connect {
    static $cursor = NULL;
    static $Error = '0';
    static $message = '';
    static $curr_page = 0;
    static $conn = NULL;
    static $total = 0;
    static $init = 1;
    static $limit = 8;
    static $cmd = NULL;
    static $id_divergencia = NULL;
    static $id_recolhimento=NULL;
    static $connected = FALSE;

    function __construct($id_recolhimento=NULL, $id_divergencia=NULL) {
        parent::__construct();
        self::$conn = parent::$conn;
        if (self::$conn) {
            self::$connected = TRUE;
            self::$id_recolhimento = $id_recolhimento;
            self::$id_divergencia = $id_divergencia;
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
    }

    public function getCursor() {
        $vetDados = array();
        if (self::$connected) {
            if (is_null(self::$id_divergencia) and ! is_null(self::$id_recolhimento)) {
                $sql = "SELECT * FROM tbrecolhimentosxdivergencias WHERE id_recolhidmento = ?";
                $result = json_decode(self::dbquery($sql, self::$id_recolhimento));
            } elseif (! is_null(self::$id_recolhimento) and !is_null(self::$id_divergencia)) {
                $sql = "SELECT * FROM tbrecolhimentosxdivergencias WHERE id_recolhidmento = ? AND id_divergencia = ?";
                $result = json_decode(self::dbquery($sql, self::$id_recolhimento, self::$id_divergencia));
            } else {
                $sql = "SELECT * FROM tbdivergencias";
                $result = json_decode(self::dbquery($sql));
            }
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