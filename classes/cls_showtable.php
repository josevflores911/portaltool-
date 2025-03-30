<!DOCTYPE html>
<html lang="en">
<head>
   <title>Portal fornecedores</title>
   <meta charset="UTF-8">
   <meta name="version" content="0.0.2"/>
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="Pragma" content="no-cache, no-store">
 
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

   <!-- JQUERY -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   <style>
        body {
            display: flex;
            flex-direction: row;
            position: relative;
            top: 16%;
            color: #fff;
        }
   </style> 
</head>
<body>
<?php
    include_once "cls_connect.php";
    include_once "cls_struct_nfse.php";
    class cls_showTable extends cls_connect {
        static $oConn = NULL;
        static $oTable = NULL;
        static $oObj = NULL;
        static $curr_table = NULL;
        static $curr_page = NULL;
        static $curr_filter = NULL;
        static $curr_sort = NULL;
        static $curr_cursor = NULL;
        static $struct = NULL;
        static $nrecords = 0;
        static $key = NULL;
        static $system = array (
            "nfse" => "cls_notas",
            "consumo" => "cls_consumo",
            "transporte" => "cls_ncte"
        );

        function __construct($id_user, $system='nfse') {

            parent::__construct(); # conectado ao banco
            self::$conn = parent::$conn;
            if (self::$conn) {
               self::$key = $system;
               $object_class = self::$system[self::$key];
               $program = $object_class . '.php';
               include_once $program;
               self::$oObj = new $object_class($id_user);
               $class_struct = "cls_struct_" . self::$key . '.php';
               include_once $class_struct;
               self::$struct = new cls_struct();
               
            }
        }
        public function show_cursor() {
        ?>
            <div class="table d-flex justify-content-center">
                <div class="table-header"> <?php
                echo "<tr>";

                foreach (self::$struct as $field_name => $vline) {
                    $field_desc = $vline['desc'];
                    $field_type = $vline['Tipo'];
                    $field_length = $vline['Length'];
                    $field_visible = $vline['Visible'];
                    if ($field_type == 'int' or $field_type=='decimal' or $field_type == 'float' or $field_type=='bigint') {
                        $talign = "right";
                    } elseif ($field_type == 'date' or $field_type=='datetime') {
                        $talign = "center";
                    } else {
                        $talign = "left";
                    }
                    
                }
        ?>
        <?php
        }
    }
?>
</body>
</html>