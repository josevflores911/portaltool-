<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_email.php";
    $id_user = $_POST['id_user'];
    $id_nota = $_POST['id_nota'];
    $to = $_POST["to"];
    $name = $_POST["name"];
    $cc = $_POST["cc"];
    $subject = $_POST["subject"];
    $body = $_POST["body"];
    $anexos = json_decode(stripslashes($_POST["anexos"]));
    $o_email = new cls_email($id_user, $id_nota, $to, $name, $subject,$body,$cc,$anexos);
    $ret = $o_email->sendEmail();
    echo json_encode ($ret);
?>