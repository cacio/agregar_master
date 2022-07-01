<?php
	require_once('../inc/inc.autoload.php');
	
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
	
		
		$act = $_REQUEST['act'];
		
		switch($act){
			
		case 'inserir':
			
			echo '<pre>';
			print_r($_REQUEST);
			
			
			if(empty($_REQUEST['idmenu'])){
				$idmenu = '';
			}else{
				$idmenu = $_REQUEST['idmenu'];			
			}
			
			if(empty($_REQUEST['icone'])){
				$icone = '';
			}else{
				$icone = $_REQUEST['icone'];			
			}
    		if(empty($_REQUEST['nome'])){
				$nome = '';
			}else{
 			    $nome = $_REQUEST['nome'];
			}
		    
			if(empty($_REQUEST['link'])){
				$link = '';
			}else{
				$link = $_REQUEST['link'];
			}
			
			$sub = new Submenu();
			
			$sub->setIdmenu($idmenu);
			$sub->setNome($nome);
			$sub->setLink($link);
			$sub->setIcone($icone);	
			
			$dao = new SubmenuDAO();
			$dao->inserir($sub);
			
			//header('Location: lista-menu.php');
		    echo 'Adicionado com sucesso !';
							
		break;
		case 'alterar':
			
			($_REQUEST['id']) ? $id  = $_REQUEST['id'] :false;
			
			if(empty($_REQUEST['icone'])){
				$icone = '';
			}else{
				$icone = $_REQUEST['icone'];			
			}
    		if(empty($_REQUEST['nome'])){
				$nome = '';
			}else{
 			    $nome = $_REQUEST['nome'];
			}
		    
			if(empty($_REQUEST['link'])){
				$link = '';
			}else{
				$link = $_REQUEST['link'];
			}
			
			$men = new Menu();
			
			$men->setCodigo($id);
			$men->setNome($nome);
			$men->setLink($link);
			$men->setIcone($icone);	
			
			$dao = new MenuDAO();
			$dao->update($men);
		header('Location: ../php/lista-menu.php');
		//echo 'Alterado com sucesso !';	
		
		break;
		
		case 'delete':
	
		($_REQUEST['id'])  ? $id  = $_REQUEST['id'] :false;
		
		$men = new Menu();	
		$men->setCodigo($id);
		$dao = new MenuDAO();
		$dao->delete($men);
		echo 'Removido com sucesso !';
		
		break;
		
		case 'listasubmenu':
		
		
		
		
		break;
		}
	
	
	}
	
?>