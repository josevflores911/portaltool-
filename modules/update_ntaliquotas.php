<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ("../classes/cls_updatentaliquotas.php");
    $id_nota =$_POST["id_nota"];
    $id_user =$_POST["id_user"];
    $vl_base =$_POST["vl_base"];
    $vl_percIR=$_POST["vl_percIR"];
    $vl_percPCC=$_POST["vl_percPCC"];
    $vl_percINSS=$_POST["vl_percINSS"];
    $vl_percISS=$_POST["vl_percISS"];
    $vl_percISSBI=$_POST["vl_percISSBI"];
    $vl_newir=$_POST["vl_newir"];
    $vl_newpcc = $_POST["vl_newpcc"];
    $vl_newinss=$_POST["vl_newinss"];
    $vl_newiss=$_POST["vl_newiss"];
    $vl_newissbi=$_POST["vl_newissbi"];
    $vl_oldir=$_POST["vl_oldir"];
    $vl_oldpcc=$_POST["vl_oldpcc"];
    $vl_oldinss=$_POST["vl_oldinss"];
    $vl_oldiss=$_POST["vl_oldiss"];
    $vl_oldissbi=$_POST["vl_oldissbi"];
    
    $oNtAliquota = new cls_updatentaliquotas($id_user, $id_nota);
    $result = $oNtAliquota->updateNotasxaliquotas($vl_base, $vl_percIR, $vl_percPCC,$vl_percINSS, $vl_percISS, $vl_percISSBI,
    $vl_newir, $vl_newpcc, $vl_newinss, $vl_newiss, $vl_newissbi, $vl_oldir, $vl_oldpcc, $vl_oldinss, $vl_oldiss, $vl_oldissbi);
    echo json_encode($result);
?>