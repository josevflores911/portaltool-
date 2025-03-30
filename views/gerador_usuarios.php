<?php
    include_once("../classes/cls_aesencdec.php");
    include_once("../classes/cls_viewusers.php");

    $oencryt = new AES_EncryptDecrypt(ENCRYPT);
    $ouser = new cls_viewusers();

    $nome= $_GET['usuario'];
    $email=$_GET['email'];
    $senha=$_GET['senha'];
    $oencryt->encrypt_password($senha);
    var_dump($oencryt);

    $oencryt->setEncryptMethod(DECRYPT);
    $new_senha = $oencryt->getEncryptedPassword();
    echo $new_senha;
    echo "<br>";
    $data_img=NULL;
    $id_area= 10;
    $nivel="BR";
    $cs_admin="S";
    $cs_conferente="N";
    $id_user = $ouser->insert_user($data_img, $nome, $email, $new_senha, $id_area, $nivel, $cs_admin, $cs_conferente);
    if ($id_user > 0) {
        $id_lista = $ouser->write_admin($id_user);
        echo json_encode(array('Errror'=> '0', 'message'=>'gravado com sucesso'));
    } else {
        echo json_encode(array('Errror'=> '404', 'message'=>'erro gravado com sucesso'));
    }
    
?>