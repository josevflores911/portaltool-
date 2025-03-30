<?php
    error_reporting(0);
    error_reporting(E_ALL);
    ini_set('display_errors', 1); // Asegura que los errores se muestren en el navegador
    $id_user= $_POST['id_user'];

    if (empty($id_user)) {
        echo json_encode(array('error' => 'ID de usuario no proporcionado'));
        exit();
    }

    require_once ("../classes/cls_tabelausuario.php");

    $ouser = new cls_tabelausuario();
    $result = $ouser->getUserById($id_user);

    if (!$result) {
        echo json_encode(array('error' => 'No se encontró el usuario'));
        exit();
    }

    // var_dump($result);

    echo json_encode( $result);
?>

