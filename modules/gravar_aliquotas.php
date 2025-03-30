<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_notastributos.php";
    $id_nota     = $_GET['id_nota'];
    $id_user     = $_GET['id_user'];
    $bitributacao = $_GET['bitributacao'];
    $cd_aliquota = $_GET['cd_aliquota'];
    $val_base    = $_GET['vl_base'];
    $val_perc    = $_GET['vl_perc'];
    $val_aliquota= $_GET['vl_aliquota'];
    $cs_liminar  = $_GET['cs_liminar'];
    $te_liminar  = $_GET['te_liminar'];
    $cs_original = $_GET['cs_original'];

    $onotas_aliquotas = new cls_notastributos($id_nota);
    $result = $onotas_aliquotas->write_NewAliquota($cd_aliquota,$val_base,$val_perc, $val_aliquota, $cs_liminar, $te_liminar);
    if ($result["Error"] == '0') {
        if ($bitributacao  and $cd_aliquota == "ISS") {
            $cd_aliquota = "ISSBI";
            $result = $onotas_aliquotas->write_NewAliquota($cd_aliquota,$val_base,$val_perc, $val_aliquota, $cs_liminar, $te_liminar);
        }
    }
    if ($result['Error'] == '0') {
        if ($bitributacao and $cd_aliquota == "ISS") {
            $vetor = array("ISS", "ISSBI");
            $result = $onotas_aliquotas->getAliquota($vetor,$cs_original);
        } else {
            $result = $onotas_aliquotas->getAliquota($cd_aliquota,$cs_original);
        }
        
    }
    echo json_encode($result);
?>