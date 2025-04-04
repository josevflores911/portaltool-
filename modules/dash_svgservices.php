<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_charts.php";
    $ocharts = new cls_charts();
    $result = $ocharts->dash_svg();
    $vet = $ocharts->draw_svg($result);
    $result["Data_charts"] = $vet;
    echo json_encode($result);
?>