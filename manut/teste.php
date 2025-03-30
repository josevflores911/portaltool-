<?php
include_once ('../classes/cls_connect.php');
include_once ('../classes/cls_aesencdec.php');

$conn = new cls_connect();
$cmd = "SELECT * FROM tbusers;";
$vresult = json_decode($conn->dbquery($cmd));

foreach($vresult->records as $row) {
    $row = get_object_vars($row);
    $id_user = $row['id_user'];
    $cd_acesso = $row['cd_acesso'];
    $oDecrypt = new AES_EncryptDecrypt(1);
    $te_pwd = $oDecrypt->encrypt_password(substr($cd_acesso,0,5) . '12345');
    $te_pwd = base64_encode($te_pwd);

    $cmd = "UPDATE tbusers SET te_pwd =? WHERE id_user=?";
    $response = json_decode($conn->dbquery($cmd, $te_pwd, $id_user));
    echo $te_pwd . '<br>';
    $te_pwd = base64_decode($te_pwd);
    echo $te_pwd . '<br>';
    $oDecrypt = new AES_EncryptDecrypt(DECRYPT);
    $te_newpwd = $oDecrypt->decrypt_password($te_pwd);

    echo "user $cd_acesso senha $te_newpwd<br>";

   
    /*
    $cmd = "UPDATE tbusers SET te_pwd =? WHERE id_user=?";
    $response = json_decode($conn->dbquery($cmd, $te_newpwd, $id_user));
    if ($response->error == '0') {
        echo "User $id_user's password has been updated successfully.<br>";
    }
        */
}
?>
