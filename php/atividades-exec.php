<?php

	require_once('../inc/inc.autoload.php');

	

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

	

		

		$act = $_REQUEST['act'];

		

		switch($act){

			

		case 'inserir':

			

			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
	
			$atv = new Atividades();
			
			$atv->setNome($nome);
			
			$dao = new AtividadesDAO();
			
			$dao->inserir($atv);	
			
			header('Location:Listar-atividades.php');					

		break;

		case 'alterar':

		

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;

	
			$atv = new Atividades();
			
			$atv->setCodigo($id);
			$atv->setNome($nome);
			
			$dao = new AtividadesDAO();
			
			$dao->alterar($atv);	
			
			header('Location:Listar-atividades.php');	
		

		break;

		

		case 'delete':

	

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;

	
			
			$atv = new Atividades();
			
			$atv->setCodigo($id);
			
			$dao = new AtividadesDAO();
			
			$dao->deletar($atv);	
	
		

		break;

		

		}

	

	

	}

	

?>