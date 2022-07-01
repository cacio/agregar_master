<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){
			
			case 'inserir':

			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
	
			$grup = new Grupo();
			
			$grup->setNome($nome);
			
			$dao = new GrupoDAO();
			
			$dao->inserir($grup);	
			
			header('Location:lista-grupo.php');					

		break;

		case 'alterar':		

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
	
			$grup = new Grupo();
			
			$grup->setCodigo($id);
			$grup->setNome($nome);
			
			$dao = new GrupoDAO();
			
			$dao->alterar($grup);	
			
			header('Location:lista-grupo.php');
				
		break;

		case 'delete':

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;
			
			$grup = new Grupo();
			
			$grup->setCodigo($id);
			
			$dao = new GrupoDAO();
			
			$dao->deletar($grup);	
	
			echo "Deletado com sucesso";		

		break;	
			case 'busca':
								
				$dao = new GrupoDAO();
				$vet = $dao->BuscaGrupo($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$grupo = $vet[$i];
					
					$cod = $grupo->getCodigo();
					$nom = $grupo->getNome();

					array_push($results, array(
									'label' => ''.$cod.'-'.$nom.'',
									'value' => ''.$cod.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.$nom.'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;

			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>