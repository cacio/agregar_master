<?php

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){
			
			case 'inserir':
						
				$id 	  = !empty($_REQUEST['id']) 			? $_REQUEST['id'] 		  :0;					
				$idprod   = !empty($_REQUEST['idproduto']) 		? $_REQUEST['idproduto']  :0;
				$qtd	  = !empty($_REQUEST['quantidade']) 	? $_REQUEST['quantidade'] :0;
				$valor    = !empty($_REQUEST['valor']) 			? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor'])):0.00;
				$subtotal = $valor * $qtd;
				
				$postld   = new PostoLivroDetalhe();
				
				$postld->setNumero($id);
				$postld->setQuantidade($qtd);
				$postld->setPreco($valor);
				$postld->setSubTotal($subtotal);
				$postld->setCodProduto($idprod);
				
				$dao = new PostoLivroDetalheDAO();
				
				$vet  	=  $dao->proximoid();
				$prox 	=  $vet[0];
				$codigo =  $prox->getProximoId();
				
				$dao->inserir($postld);
				
				$result = array();
				
				array_push($result,array(
					'proxcod'=>''.$codigo.'',
					'numero'=>''.$id.'',
					'produto'=>''.$idprod.' - '.$_REQUEST['produto'].'',
					'quantidade'=>''.$qtd.'',
					'valor'=>''.number_format($valor,2,',','.').'',
					'total'=>''.number_format($subtotal,2,',','.').'',
					'msg'=>'Inserido com sucesso!',
				));
				
				echo(json_encode($result));
				
			break;

			case 'alterar':		

						

			break;

			case 'delete':



			break;	
			

			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>