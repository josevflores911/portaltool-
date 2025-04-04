<?php
error_reporting(0);
error_reporting(E_ALL);
require_once("cls_connect.php");

class cls_notas extends cls_connect {
    static $cursor = NULL;
    static $Error = '0';
    static $message = '';
    static $curr_page = 0;
    static $conn = NULL;
    static $total = 0;
    static $init = 1;
    static $limit = 8;
    static $cmd = NULL;
    static $id_agencia = NULL;
    static $dt_compet = NULL;
    static $total_pages = 0;
    static $change_limit = FALSE;
    static $connected = FALSE;

    function __construct($id_agencia, $dt_compet) {
        parent::__construct();
        self::$conn = parent::$conn;
        if (self::$conn) {
            self::$connected = TRUE;
            self::$id_agencia = $id_agencia;
            self::$dt_compet = $dt_compet;
            if (self::$id_agencia && self::$dt_compet) {
                
                $cmd = "SELECT FC_getTotalNotas(?, ?) as total_records";
                $result = json_decode($this->dbquery($cmd,$id_agencia, $dt_compet));
                self::$total=($result->records[0])->total_records;
                self::$message = 'Dados retornados.';
                self::$Error = '0';
                if (self::$total == 0) {
                    self::$Error = '404';
                    self::$message = 'Nenhum dado encontrado.';
                }
                $this->calcTotalPages();

            } else {
                self::$Error = '401';
                self::$message = 'Parâmetros inválidos.';
            }
        } else {
            self::$connected = FALSE;
            self::$Error = '504';
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
    }

    public function getCursor() {
        if (self::$connected) {
            return array('Error' => self::$Error, 'Message' => self::$message, 'Data' => self::$cursor);
        }
    }

    public function setPage($npage){
        if ($npage > 0 and $npage <= self::$total_pages) {
            if (self::$curr_page != $npage) {
                self::$curr_page = $npage;
                return TRUE;
            } else {
                if (self::$change_limit) {
                    self::$curr_page = $npage;
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
    }

    public function getError() {
        return self::$Error;
    }
    public function getMessage() {
        return self::$message;
    }

    public function getPage() {
        return self::$curr_page;
    }

    public function getTotalPages() {
        return self::$total_pages;
    }

    public function calcTotalPages() {
        $totalpages = intval(self::$total / self::$limit);
        if ($totalpages == 0) {
            $totalpages = 1;
        } elseif ((self::$total % self::$limit)!= 0) {
            $totalpages +=1;
        }
        self::$total_pages = $totalpages;
        return $totalpages;
    }

    public function setLimitPage($nrows=8) {
        if ($nrows >= 8 and $nrows <= 50) {
            self::$limit = $nrows;
            self::$curr_page=1;
            self::$change_limit=True;
        }
    }

    public function getLimitPage() {
        return self::$limit;
    }

    public function getTotalRecords() {
        if (self::$connected) {
            return self::$total;
        }
    }

    public function getRows($page=1) {
        $cursor = array();

        if (self::$connected) {
            self::$curr_page = $page;
            $cmd = "CALL PR_vinotas(?,?,?,?);";
            $result = json_decode($this->dbquery($cmd,self::$id_agencia,self::$dt_compet,$page, self::$limit));
            if ($result->nrecords > 0) {
                foreach ($result->records as $row) {
                    $row = get_object_vars($row);
                    array_push($cursor, $row);
                }
                self::$Error = 0;
                self::$message = 'Registros capturados com sucesso.';
            } else {
                self::$Error = 404;
                self::$message = 'Não há mais registros pra ler.';
            }
        } else {
            self::$Error = 504;
            self::$message = 'Não foi possível conectar ao banco de dados.';
        }
        return array("Error" => self::$Error, "Message" => self::$message, "Data" => $cursor);
    }
}
?>