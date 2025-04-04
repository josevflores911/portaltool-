<?php
    error_reporting(0);
    error_reporting(E_ALL);
    ini_set('default_charset', '');
    mb_http_output('pass');
    mb_detect_order(["UTF-8"]);

    class cls_loadfiles {
        static $file = "../files/nota_issretido.txt";
        static $result = NULL;
        function __construct() {
            if (file_exists(self::$file)) {
                self::$result = file_get_contents(self::$file);
            }
        }

        function get_notaTecnica() {
            if (! is_null(self::$result)) {
                $erro = "0";
            } else {
                $erro = "404";
            }
            return array("Error" => $erro, "Data" => self::$result);
        }
    }
    
?>