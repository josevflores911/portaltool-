<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../../classes/cls_tabelausuario.php";

    $id_muni = $_POST["id_muni"];
    

    $object = new cls_tabelausuario();
    $result = $object->getAllAgenciesByCounty( $id_muni);
    echo json_encode($result);
?>