<?php
    error_reporting(0);
    error_reporting(E_ALL);
    $tm = 6000;
    set_time_limit ($tm);
	ini_set('post_max_size','20M');
	ini_set('upload_max_filesize','2M');
	ini_set('mysql.connect_timeout',"$tm");   
    ini_set('max_execution_time', "$tm"); 

    $id_nota = $_POST['id_nota'];
    $checksum = $_POST["n_auxi"];
    $fileout = $_POST["file_out"];
    $arquivo = $_FILES['file']['tmp_name'];
    try {
        $nome = $_FILES['file']['name'];
        $vaux = explode("/", $nome);
        if (count($vaux) > 0) {
            $filename = end($vaux);
        } else {
            $filename = NULL;
        }
        if ($filename and $arquivo) {
            $data = file_get_contents($arquivo);
            $ret = file_put_contents($fileout, $data);
            if ($ret) {
                echo json_encode(array("Error" => "0", "file" => $fileout));
            } else {
                echo json_encode(array("Error" => "305", "Message" => "Não foi possível copiar o arquivo"));
            }
        }

    } catch (Exception $err) {
        echo json_encode(array("Error" =>$err->getCode(),"Message" =>$err->getMessage()));
    }
?>
