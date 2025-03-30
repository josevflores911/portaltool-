<?php
 	error_reporting(E_ALL);
	$erro=false;
	$id_user = NULL;
	if (isset($_POST['id_user'])) {
		$id_user = intval($_POST['id_user']);
	} 

	include_once "../classes/cls_visitas.php";
	$visitas = new cls_visitas($id_user);
	$result = $visitas->setLogout();
	echo $result;

?>