<?php
	//Incluindo a conexão com banco de dados
	
	include_once("../classes/cls_login.php");
	session_start();

	if (isset($_SESSION["url"])) {
		$url = $_SESSION['url'];
	} else {
		$url = (isset($_SERVER['HTTPS']) ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$_SESSION['url'] = $url;
	}

	//O campo usuário e senha preenchido entra no if para validar
	
	if((isset($_POST['email'])) && (isset($_POST['senha']))){
		$usuario = $_POST['email'];
		$senha = $_POST['senha'];
		$convert="";
		$vlist = explode(";", $senha);
		foreach ($vlist as $letter) {
			if (strlen($letter) == 0) break;
			$c = mb_ord($letter) - 32;
			$c = mb_chr($c);
			$convert .= $c;
		}
		$senha = $convert;
		try {
			$oLogin = new cls_login($usuario, $senha);
			$erro =  $oLogin->getErroLogin();
			$message = $oLogin->getMessage();
			$ret = array("erro"=> $erro, "message"=> $message);
			if ($erro == "0") {
				$connected = $oLogin->conectado();
				if ($connected) {
					$vetor = $oLogin->getDetails();
					$ret['id_user'] = $vetor['id_user'];
					$ret['nm_user'] = $vetor['nm_user'];
				}
			}
		} catch (\Exception $e) {
			die ($e->getMessage());
		}
	}  else {
		$erro = "550";
		$message = "Usuário e senha inválidos";
		$ret = array("erro"=> $erro, "message"=> $message);
	}
	
	echo json_encode($ret);
?>
