<?php
    error_reporting(0);
    error_reporting(E_ALL);
    ini_set('display_errors', 1); // Asegura que los errores se muestren en el navegador
    

    $nome = $_POST['nome'];
    $codigo_acesso= $_POST['codigo'];
    $senha= $_POST['senha'];
    $tipo_usuario= $_POST['sel_tiposusuario'];

    $status_ativo= 'S';
    $agencia_id= $_POST['sel_agencias'];

    if (empty($nome) || empty($codigo_acesso) || empty($senha) || empty($tipo_usuario) || empty($agencia_id) || empty($status_ativo)) {
        echo json_encode(array('error' => 'erro em atributo de usuario faltante'));
        exit();
    }

    require_once ("../classes/cls_tabelausuario.php");

    $ouser = new cls_tabelausuario();
    $result = $ouser->saveUser($nome, $codigo_acesso, $senha, $tipo_usuario, $status_ativo, $agencia_id);

    if (!$result) {
        echo json_encode(array('error' => 'No se encontró el usuario'));
        exit();
    }

    // var_dump($result);

    echo json_encode( $result);
?>