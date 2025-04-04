<?php
   error_reporting(0);
   error_reporting(E_ALL);
   include_once ("../classes/cls_dashconsumo.php");
   $bfilter=FALSE;
   $parms = array();
  
   if (isset($_GET['list_values'])) {
       $list_values = json_decode(stripslashes($_GET['list_values']), true);
       if ((!is_null($list_values)) or (! empty($list_values))) {
           if (count($list_values) == 2) {
               $parms["list_values"] = $list_values;
               $bfilter=true;
           }
       }
   }

   if (isset($_GET['list_data'])) {
       $list_dates = json_decode(stripslashes($_GET['list_data']), true);
       if ( (! is_null($list_dates)) or (! empty($list_dates))) {
           if (count($list_dates) == 2) {
               $parms["list_dates"] = $list_dates;
               $bfilter=true;
           }
       }
   }
   if (empty($parms) or count($parms) == 0) $parms = NULL;
   $oDash = new cls_dashconsumo($bfilter);
   $result = $oDash->getCursor($parms);
   echo json_encode($result); 
?>