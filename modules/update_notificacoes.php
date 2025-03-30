<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once("../classes/cls_updatenotificacoes.php");
    $id_nota = $_GET["id_nota"];
    $te_nota = $_GET["te_nota"];
    $onota = new cls_updatenotificacoes($id_nota, $te_nota);
    $result = $onota->writeNotificacoes();
    echo json_encode($result);
 ?>