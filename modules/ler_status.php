<?php
     error_reporting(0);
     error_reporting(E_ALL);
     include_once '../classes/cls_statusmunicipio.php';
     $oStatus = new cls_statusmunicipio();
     $result = $oStatus->getCursor();
     echo json_encode($result);
?>