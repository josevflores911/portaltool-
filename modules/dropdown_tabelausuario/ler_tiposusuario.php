<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../../classes/cls_dropdownusuarios.php";

    $object = new cls_dropdownusuarios();
    $result = $object->getAllUserType();
    echo json_encode($result);
?>