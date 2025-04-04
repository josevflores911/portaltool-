<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ('../classes/cls_lerlogusuarios.php');
    $id_user= $_GET['id_user'];
    $nu_rows = $_GET['nu_rows'];
    $curr_page = $_GET['curr_page'];
    $ologusu = new cls_lerlogusuarios($id_user);
    $result = $ologusu->getRows($nu_rows, $curr_page);
    echo json_encode($result);
?>