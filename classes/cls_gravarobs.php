<?php
   error_reporting(0);
   error_reporting(E_ALL);
   include_once('cls_gravarlog.php');
   class cls_gravarobs extends cls_connect {
         static $conn=NULL;
         static $cursor=NULL;
         static $connected=FALSE;
         static $id_nota=NULL;
         static $id_user=NULL;
         static $texto=NULL;
         static $gravacao=false;
         static $erro="504";
         static $nnfs=NULL;
         function __construct($id_nota, $id_user, $texto) {
             parent::__construct();
             self::$conn = parent::$conn;
             if (self::$conn) {
                 self::$connected = TRUE;
                 self::$id_nota=$id_nota;
                 self::$id_user=$id_user;
                 self::$texto = $texto;
                 $cmd = "SELECT * FROM notas WHERE ID=?";
                 $result = json_decode($this->dbquery($cmd, self::$id_nota));
                 if ($result->nrecords > 0) {
                    self::$cursor = $result->records[0];
                    self::$cursor = get_object_vars($cursor);
                    self::$nnfs = self::$cursor["NNFS"];
                    self::$gravacao = true;
                 } else {
                    self::$erro = '404';
                 }
             }
         }

         function gravarObservacao() {
            if (self::$connected) {
                $te_obs = self::$cursor["DCINF"];
                $te_obs .= "\n" . self::$texto;
                $cmd = "UPDATE notas SET DCINF =? WHERE ID=?";
                $result = json_decode($this->dbquery($cmd, $te_obs, self::$id_nota));
                if ($result->error == '0') {
                    // gravar log
                    $id_oper = 9;
                    $nm_table = "notas";
                    $te_descricao = "Alterado de campos da nota (" . self::$nnfs . ") Campo de Observacao (" . self::$texto . ")\n";
                    $olog = new cls_gravarlog(self::$id_user, $id_oper, self::$id_nota, $nm_table, $te_descricao);
                    $resp = $olog->gravar_log();
                    if ($resp) {
                        $olog->setNmTable("tbnotasxobservacoes");
                        $olog->setIdOper(9);
                        $te_descricao = "Inclusão de nova observação da nota (" . self::$nnfs . ') conteúdo (' . self::$texto . ")\n";
                        $olog->setTeDescricao($te_descricao);
                        $resp = $olog->gravar_log();
                        if ($resp) {
                            self::$erro = '0';
                        } else {
                            self::$erro="307";
                        }
                    } else {
                        self::$erro ='307';
                    }
                    self::$erro = $result->error;
                } else {
                    self::$erro = "308";
                }
            }
            return array("Error" => self::$erro);
         }
   }
?>