<?php
     error_reporting(0);
     error_reporting(E_ALL);
     include_once ("../classes/cls_charts.php");
     $data = json_decode(stripslashes($_GET["data"]),true);
     $type = "line";
     if (isset($_GET["labels"])) {
         $labels =json_decode(stripslashes( $_GET["labels"]),true);
     } else {
         $labels = NULL;
     }
     if (isset($_GET["colors"])) {
         $colors = json_decode(stripslashes($_GET["colors"]),true);
     } else {
         $colors = NULL;
     }
 
     if (isset($_GET["title"])) {
         $title = $_GET["title"];
     } else {
         $title = NULL;
     }
 
     if (isset($_GET["Dimensions"])) {
         $Dimensions = json_decode(stripslashes($_GET["Dimensions"]),true);
     } else {
         $Dimensions = NULL;
     }
     $oChart = new cls_graphics($data, $type,NULL, $colors, $title,$Dimensions);
     $oChart->setTransparency('0.27');
     $oChart->setLabel_X($labels);
     
     $data = $oChart->draw_line();
     if (strlen($data) > 0) {
         $Error = '0';
         $message = "Imagem ok";
     } else {
         $Error ='404';
         $message = "Não foi possivel gerar a imagem";
     }
     $ret = array( "Error" => $Error, "Message" => $message, "Data" => $data);
     echo json_encode($ret);
?>