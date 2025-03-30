<?php
     error_reporting(0);
     error_reporting(E_ALL);

     include_once '../classes/cls_competencias.php';
     $id_user = $_POST['id_user'];
     $tp_user = $_POST['tp_user'];
     $oCompet = new cls_competencias($id_user, $tp_user);
     $result = $oCompet->getCursor();
     echo json_encode($result);
?>