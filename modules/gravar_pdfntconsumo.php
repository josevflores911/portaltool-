<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once "../classes/cls_imgnotasconsumo.php";

    $arquivo = $_FILES['file']['tmp_name'];
    $checksum = $_POST["checksum"];
    $id_user = $_POST["id_user"];
    $id_nota = $_POST["id_nota"];
    try {
        //ler todo o arquivo para um array
        $nome = $_FILES['file']['name'];
        $filename = removeExtesion($nome);
        $vaux = explode(".", $nome);
        $ext = end($vaux);
        if ($arquivo and strlen($filename) > 0 and $ext == 'pdf') {
            $filename = "../files/" . $filename . "";
            $filename = preg_replace("/\s/", "", $filename) ."[" . strval($id_nota) . "-" . strval($id_user) . "]" . "." . $ext;
            move_uploaded_file($arquivo, $filename);
            $ocls = new cls_imgnotasconsumo($id_nota);
            $result = $ocls->gravarFilename($filename);
        } else {
            $result = array("Error" => '405');
        }
    } catch(Exception $err) {
        $result = array ("Error" =>$err->getCode(),"Message" =>$err->getMessage());
    }
    echo json_encode($result);

    // função pra remover a extesão do arquivo
    // Autor Carlos e Will Bala
    function removeExtesion($pfile) {
        $aux = explode(".",$pfile);
        array_pop($aux);
        $var = implode(",",$aux);
        return $var;
    }
?>