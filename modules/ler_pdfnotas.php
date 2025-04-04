<?php
       include_once "../classes/cls_municipios.php";
       $id_nota = $_GET["id_nota"];
       $oMuni = new cls_municipios();
       $vet_result = $oMuni->lerLink($id_nota);
       echo json_encode($vet_return);
?>