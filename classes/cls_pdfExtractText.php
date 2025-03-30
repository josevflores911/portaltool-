<?php
    error_reporting(E_ALL);
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    define ("DESENV", '1');
    define ("PRODUCAO", '2');
    require_once "../vendor/autoload.php";

    define('PHP_FILE', 'pdf_file');
    define('MEMORY', 'pdf_context');

    class cls_pdfExtractText {
        protected $file = NULL;
        protected $type = NULL;
        protected $stream = NULL;
        protected $parser = NULL;

        function __construct($contextPDF, $type=NULL) {
            if ($type == PDF_FILE) {
                if (is_file($contextPDF)) {
                    self::$file = $contextPDF;
                    self::$type = PDF_FILE;
                }
            } elseif ($type == MEMORY) {
                if (! is_null($contextPDF)) {
                    self::$stream = base64_decode($contextPDF);
                    self::$type = MEMORY;
                }
            } 

            if (! is_null(self::$type)) {
                
            }
        }

        function getText() {
            if (! is_null(self::$type)) {
                if (self::$type == PDF_FILE) {
                    
                }
            }
        }
   }
?>