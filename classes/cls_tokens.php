<?php
   error_reporting(0);
   error_reporting(E_ALL);
   class token_generate {
         static $token=FALSE;
         static $chamador=NULL;
         static $list_token = array ("listar_notas" => "d5bccefab1ece23d134b307160ab1df6");
         function __construct($token) {
            foreach(self::$list_token as $key=> $value) {
                if ($token == $value) {
                    self::$token = TRUE;
                    self::$chamador=$key;
                    break;
                }
            }
         }
         function getRefer () {
            return self::$chamador;
         }
         function getToken() {
            return self::$token;
         }
   }
?>