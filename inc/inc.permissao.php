<?php

$path = $_SERVER['SCRIPT_FILENAME'];
$path_parts = pathinfo($path);

$perDao = new PermissoesDAO();

$perVet = $perDao->verificapermissaourl($_SESSION['coduser'],$path_parts['basename']);
$pernum = count($perVet);

if($pernum == 0){
	
	header('Location:../php/404.php');
	
}


$tpl->gotoBlock('_ROOT');
?>