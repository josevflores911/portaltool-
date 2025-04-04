<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_updatestatus.php";
    $id_nota = $_GET["id_nota"];
    $id_user = $_GET["id_user"];
    $cd_status = $_GET["cd_status"];
    $te_status = $_GET["te_status"];
    $oUpdate = new cls_updatestatus($id_nota, $id_user, $cd_status, $te_status);
    $result = $oUpdate->updateStatus();
    echo json_encode($result);
?>