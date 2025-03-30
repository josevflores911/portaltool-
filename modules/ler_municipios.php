<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $id_user= $_POST['id_user'];
    $tp_user= $_POST['tp_user'];
    require_once ("../classes/cls_municipios.php");

    $omuni = new cls_municipios($id_user,$tp_user);
    $result = $omuni->getCursor();

    echo json_encode($result);
?>