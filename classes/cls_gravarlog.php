<?php
   error_reporting(0);
   error_reporting(E_ALL);
   require_once('cls_connect.php');
   class cls_gravarlog extends cls_connect {
         static $conn=NULL;
         static $cursor=NULL;
         static $connected=FALSE;
         static $id_user=NULL;
         static $id_oper=NULL;
         static $id_nota=NULL;
         static $nm_table=NULL;
         static $te_descricao=NULL;
         static $gravar_log = FALSE;
         static $dbname = NULL;
         function __construct($id_user, $id_oper, $id_nota = NULL, $nm_table="", $te_descricao="",$dbname=NULL) {
             parent::__construct($dbname);
             self::$conn = parent::$conn;
             if (self::$conn) {
                 self::$connected = TRUE;
                 self::$dbname = $dbname;
                 if (self::checkUser($id_user,$this)) {
                    if (! is_null($id_nota)) {
                        self::$id_nota = $id_nota;
                    } 
                    self::$id_user = $id_user;
                    if (self::checkOper($id_oper, $this)) {
                        self::$id_oper = $id_oper;
                        self::$nm_table = $nm_table;
                        self::$te_descricao = explode('\n', $te_descricao);
                        if (gettype(self::$te_descricao) == 'array') {
                            self::$gravar_log=TRUE;
                        } 
                    }
                 }
             }
         }
         public function getIdnota() {
            if (self::$connected) return self::$id_nota;
         }
         public function setIdnota($id_nota = NULL) {
            $bret = FALSE;
            if (self::$connected) {
                self::$id_nota = $id_nota;
                $bret=TRUE;
            }
            return $bret;
         }

         public function getIduser () {
            if (self::$connected) return self::$id_user;
         }

         public function setIduser($id_user = NULL) {
            $bret = FALSE;
            if (self::$connected) {
                self::$id_user = $id_user;
                $bret=TRUE;
            }
            return $bret;
         }

         public function getNmTable(){
            if (self::$connected) return self::$nm_table;
         }

         public function setNmTable($nm_table = NULL) {
            $bret = FALSE;
            if (self::$connected) {
                self::$nm_table = $nm_table;
                $bret=TRUE;
            }
            return $bret;
         }

         public function getTeDescricao() {
            if (self::$connected) return self::$te_descricao;
         }

         public function setTeDescricao($te_descricao = NULL) {
            $bret = FALSE;
            if (self::$connected) {
               if (is_null($te_descricao)) {
                  self::$gravar_log=FALSE;
               } else {
                  if (gettype($te_descricao) == "string") {
                     if (strlen($te_descricao) == 0) {
                        self::$gravar_log=FALSE;
                     } else {
                        self::$te_descricao = explode('\n', $te_descricao);
                        self::$gravar_log=TRUE;
                     }
                   } 
                   if (gettype($te_descricao) == "array") {
                     self::$te_descricao = $te_descricao;
                     self::$gravar_log=TRUE;
                   }
   
               }

                $bret=TRUE;
            }
            return $bret;
         }

         public function getIdOper() {
            if (self::$connected) return self::$id_oper;
         }

         public function setIdOper(int $id_oper) {
            $bret = FALSE;
            if (self::$connected) {
                self::$id_oper = $id_oper;
                $bret=TRUE;
            }
            return $bret;
         }


         public function gravar_log() {
            $gravou = TRUE;
            if (self::$gravar_log) {
                  foreach (self::$te_descricao as $row) {
                     $row = preg_replace("/[\r\n\t]+/","",$row);
                     $cmd = "INSERT INTO log(id_user, id_oper, id_nota, Nm_Tabela, Te_Descricao) VALUES (?,?,?,?,?);";
                     $result = json_decode($this->dbquery($cmd, self::$id_user, self::$id_oper, self::$id_nota, self::$nm_table, $row));
                     if ($result->error !== '0') {
                           $gravou=FALSE;
                           break;
                     } 
                  }
            }
            return $gravou;
         }

         static function checkOper($id_oper, $oConn) {
            $tboper = "operacoes_log";
            if (! is_null(self::$dbname)) {
               $tboper = "`Mbpf201215wb2`.`" . $tboper . "`";
            }
            $cmd = "SELECT * FROM $tboper WHERE id_operacao=?";
            $result = json_decode($oConn->dbquery($cmd, $id_oper));
            return ($result->nrecords > 0);
         }

         static function checkUser($id_user, $oConn) {
            $tbuser = "users";

            if (! is_null(self::$dbname)) {
               $tbuser = "`Mbpf201215wb2`.`" . $tbuser ."`";
            }
            $cmd = "SELECT * FROM $tbuser WHERE id=?";
            $result = json_decode($oConn->dbquery($cmd, $id_user));
            return ($result->nrecords > 0);
         }
   }
?>