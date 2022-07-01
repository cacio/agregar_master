<?php

	require_once('../inc/inc.autoload.php');

	

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

	

		

		$act = $_REQUEST['act'];

		

		switch($act){

			

		case 'inserir':

			

			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
	
			$esp = new Espe();
			
			$esp->setNome($nome);
			
			$dao = new EspeDAO();
			
			$dao->inserir($esp);	
			
			header('Location:Listar-espe.php');					

		break;

		case 'alterar':

		

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;

	
			$esp = new Espe();
			
			$esp->setCodigo($id);
			$esp->setNome($nome);
			
			$dao = new EspeDAO();
			
			$dao->alterar($esp);	
			
			header('Location:Listar-espe.php');	
		

		break;

		

		case 'delete':

	

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;

	
			
			$esp = new Espe();
			
			$esp->setCodigo($id);
			
			$dao = new EspeDAO();
			
			$dao->deletar($esp);	
	
		

		break;

		

		}

	

	

	}

	

?>