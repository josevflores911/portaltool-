<?php
    error_reporting(0);
    error_reporting(E_ALL);
    include_once ("../classes/cls_anexos.php");
    $tm = 6000;
    set_time_limit ($tm);
	ini_set('post_max_size','20M');
	ini_set('upload_max_filesize','2M');
	ini_set('mysql.connect_timeout',"$tm");   
    ini_set('max_execution_time', "$tm"); 

    $arquivo = $_FILES['file']['tmp_name'];
    $id_nota = $_POST["id_nota"];
    $te_assunto = $_POST["te_assunto"];
    $checksum = $_POST["n_auxi"];
    try {
        $nome = $_FILES['file']['name'];
        $vaux = explode("/", $nome);
        if (count($vaux) > 0) {
            $filename = end($vaux);
        } else {
            $filename = NULL;
        }
        if ($filename and $arquivo) {
            $oanexo = new cls_anexos($id_nota, $filename, $te_assunto,$arquivo, $checksum);
            $ret = $oanexo->writeAnexo();
            echo json_encode($ret);
        } else {
            echo json_encode(array("status" =>"ok","file" => $arquivo));
        }
    }catch(Exception $err) {
        echo json_encode(array("status" =>$err->getCode(),"msg" =>$err->getMessage()));
    }
?>


