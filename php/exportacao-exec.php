<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':

			$func   = new FuncoesDAO();
			$res    = array();

			$competenc    = !empty($_REQUEST['competenc']) ? $_REQUEST['competenc'] :'';
			$pais	      = !empty($_REQUEST['pais']) ? $_REQUEST['pais'] : 0;
			$kg_glos      = !empty($_REQUEST['kg_glos']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['kg_glos'])) : 0;
			$valor_glos   = !empty($_REQUEST['valor_glos']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor_glos'])) : 0;
			$kg_venda     = !empty($_REQUEST['kg_venda']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['kg_venda'])) : 0;
			$valor_venda  = !empty($_REQUEST['valor_venda']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor_venda'])) : 0;

           // print_r($_REQUEST);
			
			$validdata    = $func->ValidaData(date('d').'/'.$competenc);

			if($validdata == true){

				$dao = new ExportacaoDAO();
				$vet = $dao->VerificaCompPais($_SESSION['cnpj'],$competenc,$pais);
				$num = count($vet);

				if($num == 0){
					//insere


					$expo = new Exportacao();
            				
					$expo->setCompetencia($competenc);
					$expo->setPais($pais);
					$expo->setKgGlosado($kg_glos);
					$expo->setValorGlosado($valor_glos);
					$expo->setKgVend($kg_venda);
					$expo->setValorVend($valor_venda);
					$expo->setCnpjEmp($_SESSION['cnpj']);

					$dao->Inserir($expo);

					array_push($res,array(
						'titulo'=>"Mensagem Informação de Exportação",
						'mensagem'=>"Gravado com sucesso!",
						'url'=>'informacaoexportacao.php'	,
						'tipo'=>'1'
					));
				}else{
					array_push($res,array(
						'titulo'=>"Mensagem Informação de Exportação",
						'mensagem'=>"Competência já existe, para esse pais tente outra!",
						'url'=>'cadastro-exportacao.php',
						'tipo'=>'2'
					));

				}
				

			}else{
				//exite competencia
				array_push($res,array(
					'titulo'=>"Mensagem Informação de Exportação",
					'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
					'url'=>'cadastro-exportacao.php'	,
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
			
			$competenc    = !empty($_REQUEST['competenc']) ? $_REQUEST['competenc'] :'';
			$pais	      = !empty($_REQUEST['pais']) ? $_REQUEST['pais'] : 0;
			$kg_glos      = !empty($_REQUEST['kg_glos']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['kg_glos'])) : 0;
			$valor_glos   = !empty($_REQUEST['valor_glos']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor_glos'])) : 0;
			$kg_venda     = !empty($_REQUEST['kg_venda']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['kg_venda'])) : 0;
			$valor_venda  = !empty($_REQUEST['valor_venda']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['valor_venda'])) : 0;					
			
			$validdata    = $func->ValidaData(date('d').'/'.$competenc);

			if($validdata == true){

				$expo = new Exportacao();
				
				$expo->setCodigo($id);
				$expo->setCompetencia($competenc);
				$expo->setPais($pais);
				$expo->setKgGlosado($kg_glos);
				$expo->setValorGlosado($valor_glos);
				$expo->setKgVend($kg_venda);
				$expo->setValorVend($valor_venda);
				$expo->setCnpjEmp($_SESSION['cnpj']);
				
				$dao = new ExportacaoDAO();
				
				$dao->Update($expo);
				
				array_push($res,array(
					'titulo'=>"Mensagem Informação de Exportação",
					'mensagem'=>"Gravado com sucesso!",
					'url'=>'informacaoexportacao.php'	,
					'tipo'=>'1'
				));

			}else{
				array_push($res,array(
					'titulo'=>"Mensagem Informação de Exportação",
					'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
					'url'=>'atualizar-exportacao.php?id='.$id.''	,
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
			
			$expo = new Exportacao();
				
			$expo->setCodigo($id);
			$expo->setCnpjEmp($_SESSION['cnpj']);

			$dao = new ExportacaoDAO();
			$dao->Delete($expo);

			echo "Deletado com sucesso!";
			
		break;
		}
	}

?>