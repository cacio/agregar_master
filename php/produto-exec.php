<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'busca':
				
				$s = $_REQUEST['term'];
				
				$dao = new ProdutosAgregarDAO();
				$vet = $dao->BuscaProdutoAgregar($s);
				$num = count($vet);
				$result = array();
						
				for($i =0; $i < $num; $i++){
					
					$prod = $vet[$i];	
						
						
					$cod  = $prod->getCodProd();
					$desc = $prod->getDescProd();	
						
					
					array_push($result, array(
							'label'=>''.$cod.' - '.$desc.'',
							'value' => ''.$desc.'',
							'nome' => ''.$desc.'',
							'cod' => ''.$cod.'',							
					));	
					
					
				}		
						
				echo (json_encode($result));		
						
		break;
		case 'buscaproduto':
				
				$s    = $_REQUEST['term'];
				$cnpj = $_SESSION['cnpj'];

				$dao = new ProdutosTxtDAO();
				$vet = $dao->BuscaProdutoTxt($s,$cnpj);

				$num = count($vet);
				$result = array();
						
				for($i =0; $i < $num; $i++){
					
					$prod = $vet[$i];	
						
						
					$cod  = $prod->getCodProd();
					$desc = $prod->getDescProd();	
						
					
					array_push($result, array(
							'label'=>''.$cod.' - '.$desc.'',
							'value' => ''.$desc.'',
							'nome' => ''.$desc.'',
							'cod' => ''.$cod.'',							
					));	
					
					
				}		
						
				echo (json_encode($result));

		break;
		case'lista':

			$dao = new ProdutosAgregarDAO();
			$vet = $dao->ListaProdutoAgregar();
			$num = count($vet);
			$result = array();
					
			for($i =0; $i < $num; $i++){
				
				$prod = $vet[$i];	
					
					
				$cod  = $prod->getCodProd();
				$desc = $prod->getDescProd();	
					
				
				$result[] = array(
						'text'=>''.$cod.' - '.$desc.'',						
						'id' => ''.$cod.'|'.$desc.'',							
				);
				
				
			}		
					
			echo (json_encode($result));

		break;
		case 'listaprodfrig':

			$cnpj = $_SESSION['cnpj'];

			$dao = new ProdutosTxtDAO();
			$vet = $dao->ListaProdutoTxt($cnpj);

			$num = count($vet);
			$result = array();
					
			for($i =0; $i < $num; $i++){
				
				$prod = $vet[$i];	
					
					
				$cod  = $prod->getCodProd();
				$desc = $prod->getDescProd();	
					
				
				$result[] = array(
					'text'=>''.$cod.' - '.$desc.'',						
					'id' => ''.$cod.'|'.$desc.'',							
				);
								
			}		
					
			echo (json_encode($result));

		break;
		
		}
	}

?>