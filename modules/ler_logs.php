<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_lerlog.php";

    if (isset($_GET['id_nota'])) {
        $id_nota = intval($_GET["id_nota"]);
    } else {
        $id_nota = NULL;
    }
    if (isset($_GET["id_user"])) {
        $id_user = intval($_GET["id_user"]);
    } else {
        $id_user = NULL;
    }
    
    if (isset($_GET["curr_page"])) {
        $nu_page = intval($_GET["curr_page"]);
    } else {
        $nu_page = 0;
    }
    
    if (isset($_GET["nu_rows"])) {
        $nu_rows = intval($_GET["nu_rows"]);
    } else {
        $nu_rows = 20;
    }
    $olog = new cls_lerlog( $id_nota, $id_user);
    $result = $olog->getRows($nu_rows, $nu_page);
    echo json_encode($result);
?>