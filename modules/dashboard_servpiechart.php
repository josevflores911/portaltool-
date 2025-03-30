<?php
   error_reporting(0);
   error_reporting(E_ALL);
   include_once ("../classes/cls_dashservices.php");
   $bfilter=FALSE;
   $parms = array();
   if (isset($_GET["tipo"])) {
       $tipo = $_GET["tipo"];
   } else {
       $tipo = 1;
   }
   if (isset($_GET['cd_uf'])) {
       $cd_uf = $_GET['cd_uf'];
       if ( ! empty($cd_uf)) {
           $bfilter =TRUE;
           $parms['cd_uf'] = $cd_uf;
       }
   }

   if (isset($_GET['id_muni'])) {
       $id_muni = $_GET['id_muni'];
       if (!empty($id_muni)) {
           $bfilter=true;
           $id_muni = intval($id_muni);
           $parms["id_muni"] = $id_muni;
       }
   }

   if (isset($_GET['id_prestador'])) {
       $id_prestador = $_GET['id_prestador'];
       if (!empty($id_prestador)) {
           $id_prestador = intval($id_prestador);
           $bfilter = TRUE;
           $parms["id_prestador"] = $id_prestador;
       }
   } 
   
   if (isset($_GET['id_tomador'])) {
       $id_tomador = $_GET['id_tomador'];
       if (!empty($id_tomador)) {
           $id_tomador = intval($id_tomador);
           $bfilter=true;
           $parms["id_tomador"] = $id_tomador;
       }
   } 
   if (isset($_GET['id_servico'])) {
       $id_servico = $_GET['id_servico'];
       if (!empty($id_servico)) {
           $parms["id_servico"] = intval($id_servico);
           $bfilter=true;
       }
   }

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
   $oDash = new cls_dashservicos($bfilter);
   $result = $oDash->getCursor($parms);
   echo json_encode($result); 
?>