<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_aliquotas.php";
    $id_user   = $_GET['id_user'];
    if (! isset($_GET['cfilter'])) {
        $filter_fields = NULL;
    } else {
        $filter_fields = $_GET["cfilter"];
        if (strpos($filter_fields, "{") !== FALSE)
            $filter_fields = json_decode(stripslashes($filter_fields), true);
    }
    $oAliquotas = new cls_ntaliquotas($id_user);
    $total = $oAliquotas->getTotalRecords($filter_fields);
    echo json_encode($total);
?>