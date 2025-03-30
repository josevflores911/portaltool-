<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ("../classes/cls_dashservices.php");
    $ostatus = new cls_dashstatus();
    $result =$ostatus->getCursor();
    echo json_encode($result);
?>