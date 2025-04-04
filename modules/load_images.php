<?php
    include_once "../classes/cls_loadimg.php";
    $id = $_POST["id_img"];
    $oimg = new cls_loadimg();
    $result = $oimg->getImageUrl($id);
    echo json_encode($result);
?>