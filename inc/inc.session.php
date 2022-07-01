<?php
	
	session_start();
	
	if(!isset($_SESSION['login']) and empty($_SESSION['login'])){
		
		header('Location:login.php');
		
	}

	$daouri = new URI();
	$uri 	= $daouri->NomeUrl(); 
		
	$listaSubAutoriza = array(
		'admin.php',
		'atualizar-usuario.php',
		'ajax-submenu.php',
		'cadastro-usuario.php',
		'response.php',
		'atualizar-usuario.php',
		'importa_agregar_xml.php',
		'importa_agregar_txt.php',
		'notasmanuais.php',
		'visualiza-empresas.php',
		'competenciaenviadas.php',
		'detalhe-notificacao.php',
		'competencias.php',
		'lista-notificacao.php',
		'competenciaenviadas_teste.php',
		'pageteste.php'		
		);
	$key 	= in_array($uri, $listaSubAutoriza);
	//echo substr($uri, 0, 7);
	$daoSub = new SubmenuDAO();
	$vetSub = $daoSub->SubMenuPermissao($_SESSION['coduser'],$uri,$_SESSION['idsys']);
	$numSub = count($vetSub);
	//echo substr($uri, 0, 9);
	if($numSub == 0){
		if($key != 1 and substr($uri, 0, 9) != 'relatorio' and trim(substr($uri, 0, 9)) != 'atualizar' and trim(substr($uri, 0, 8)) != 'cadastro' and trim(substr($uri, 0, 7)) != 'alterar'){
			header('Location:../php/response.php');				
		}
	}

?>