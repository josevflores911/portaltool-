<?php
    include_once "../classes/cls_dashservices.php";
    $oEmpresa = new cls_dashservpanel1();
    $result = $oEmpresa->getDashPanel1();
    echo json_encode($result);
?>