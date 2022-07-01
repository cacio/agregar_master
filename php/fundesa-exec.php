<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':

			$func   = new FuncoesDAO();
			$res    = array();

			if(empty($_REQUEST['compensa'])){				
				$compensa = "";					
			}else{
				$compensa = $_REQUEST['compensa'];
			}
			
			if(empty($_REQUEST['numero'])){				
				$numero = "";					
			}else{
				$numero = $_REQUEST['numero'];
			}
			
			if(empty($_REQUEST['valorpago'])){				
				$valorpago = "0.00";					
			}else{
				$valorpago = str_replace(',', '.', str_replace('.', '', $_REQUEST['valorpago']));
			}
			
			if(empty($_REQUEST['datapago'])){				
				$datapago = date('Y-m-d');					
			}else{
				$datapago = implode("-", array_reverse(explode("/", "".$_REQUEST['datapago']."")));
			}						
			
			$validdata    = $func->ValidaData(date('d').'/'.$compensa);

			if($validdata == true){

				$dao = new FundesaDAO();
				$vet = $dao->ValidaFundesa($_SESSION["cnpj"],$compensa);
				$num = count($vet);	

				if($num == 0){
					$fun = new Fundesa();
					
					$fun->setCompetenc($compensa);
					$fun->setNumero($numero);
					$fun->setValorPago($valorpago);
					$fun->setDataPago($datapago);
					$fun->setCnpjEmp($_SESSION["cnpj"]);
													
					$dao->inserir($fun);

					array_push($res,array(
						'titulo'=>"Mensagem da FUNDESA",
						'mensagem'=>"Gravado com sucesso!",
						'url'=>'listar-fundesa.php'	,
						'tipo'=>'1'
					));
				}else{
					//existe comp
					array_push($res,array(
						'titulo'=>"Mensagem da FUNDESA",
						'mensagem'=>"Competência já existe, tente outra!",
						'url'=>'cadastro-fundesa.php',
						'tipo'=>'2'
					));

				}
			}else{
				//compentencia invalida
				array_push($res,array(
					'titulo'=>"Mensagem da FUNDESA",
					'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
					'url'=>'cadastro-fundesa.php'	,
					'tipo'=>'2'
				));	
			}

			$re      = json_encode($res);
			$reponse = urlencode($re);

			$destino = "response.php?mg={$reponse}";
			header("Location:{$destino}");
						
		break;
		case 'alterar':

			
			$func   = new FuncoesDAO();
			$res    = array();
			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			
			if(empty($_REQUEST['compensa'])){				
				$compensa = "";					
			}else{
				$compensa = $_REQUEST['compensa'];
			}
			
			if(empty($_REQUEST['numero'])){				
				$numero = "";					
			}else{
				$numero = $_REQUEST['numero'];
			}
			
			if(empty($_REQUEST['valorpago'])){				
				$valorpago = "0.00";					
			}else{
				$valorpago = str_replace(',', '.', str_replace('.', '', $_REQUEST['valorpago']));
			}
			
			if(empty($_REQUEST['datapago'])){				
				$datapago = date('Y-m-d');					
			}else{
				$datapago = implode("-", array_reverse(explode("/", "".$_REQUEST['datapago']."")));
			}						
			
			$validdata    = $func->ValidaData(date('d').'/'.$compensa);

			if($validdata == true){
				$fun = new Fundesa();
				
				$fun->setCodigo($id);
				$fun->setCompetenc($compensa);
				$fun->setNumero($numero);
				$fun->setValorPago($valorpago);
				$fun->setDataPago($datapago);
				$fun->setCnpjEmp($_SESSION["cnpj"]);
				
				$dao = new FundesaDAO();
				
				$dao->update($fun);
				array_push($res,array(
					'titulo'=>"Mensagem da FUNDESA",
					'mensagem'=>"Alterado com sucesso!",
					'url'=>'listar-fundesa.php'	,
					'tipo'=>'1'
				));
			}else{
				array_push($res,array(
					'titulo'=>"Mensagem da FUNDESA",
					'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
					'url'=>'atualizar-fundesa.php?id='.$id.'',
					'tipo'=>'2'
				));
			}
			
			$re      = json_encode($res);
			$reponse = urlencode($re);

			$destino = "response.php?mg={$reponse}";
			header("Location:{$destino}");

		break;
		case 'delete':
			
			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			
			$fun = new Fundesa();
			
			$fun->setCodigo($id);
			
			$dao = new FundesaDAO();
			
			$dao->deletar($fun);
			
		break;
		}
	}

?>