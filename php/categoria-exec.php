<?php

	require_once('../inc/inc.autoload.php');

	

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

	

		

		$act = $_REQUEST['act'];

		

		switch($act){

			

		case 'inserir':

			

			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
	
			$cat = new Categoria();
			
			$cat->setNome($nome);
			
			$dao = new CategoriaDAO();
			
			$dao->inserir($cat);	
			
			header('Location:Listar-categoria.php');					

		break;

		case 'alterar':

		

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;

	
			$cat = new Categoria();
			
			$cat->setCodigo($id);
			$cat->setNome($nome);
			
			$dao = new CategoriaDAO();
			
			$dao->alterar($cat);	
			
			header('Location:Listar-categoria.php');	
		

		break;

		

		case 'delete':

	

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;

	
			
			$cat = new Categoria();
			
			$cat->setCodigo($id);
			
			$dao = new CategoriaDAO();
			
			$dao->deletar($cat);	
	
			echo "Deletado com sucesso";		

		break;

		

		}

	

	

	}

	

?>