<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'busca':

				$dao = new FichaDAO();
				$vet = $dao->BuscaFicha($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$ficha = $vet[$i];
					
					$cod = $ficha->getCodigo();
					$nom = $ficha->getNome();

					array_push($results, array(
									'label' => ''.$cod.'-'.$nom.'',
									'value' => ''.$cod.'',
								   	'cod'=>''.$cod.'',
									'nom'=>''.$nom.'',
								));		
					
				}
				
				echo (json_encode($results));
				
			break;
			case 'busca2':

				$dao = new FichaDAO();
				$vet = $dao->BuscaFicha($_REQUEST['term']);
				$num = count($vet);
				$results = array();

				for($i = 0; $i < $num; $i++){
					
					$ficha = $vet[$i];
					
					$cod = $ficha->getCodigo();
					$nom = $ficha->getNome();

					array_push($results, array(
									'label' => ''.$cod.'-'.$nom.'',
									'value' => ''.$nom.'',
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