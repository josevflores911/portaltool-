<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_gravarlog.php";
    $id_user = $_GET['id_user'];
    $id_oper = $_GET['id_oper'];
    $tb_name = $_GET['tb_name'];
    $te_descricao = $_GET['te_descricao'];
    $vte_descricao = json_decode(stripslashes($te_descricao));
    $te_descricao = "";
    foreach ($vte_descricao as $line) {
        $field = $line->field_name;
        $old_value = $line->old_value;
        $new_value = $line->new_value;
        $te_descricao .= "$field|$old_value|$new_value\n";
    }
    $olog = new cls_gravarlog($id_user, $id_oper, $tb_name, $te_descricao);
    $result = $olog->gravar_log();
    
    if ($result) {
        $vresult = array ("Error" => '0');    
    } else {
        $vresult = array ("Error" => '300');    
    }
    echo json_encode ($vresult);
?>