<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'inserir':

				$func   = new FuncoesDAO();
				$res    = array();
				
				if(empty($_REQUEST['numfunc'])){				
					$numfunc = "";					
				}else{
					$numfunc = $_REQUEST['numfunc'];
				}

				if(empty($_REQUEST['valorfolha'])){				
					$valorfolha = "0.00";					
				}else{
					$valorfolha = str_replace(',', '.', str_replace('.', '', $_REQUEST['valorfolha']));
				}

				if(empty($_REQUEST['dtpago'])){				
					$datapago = date('Y-m-d');					
				}else{
					$datapago = implode("-", array_reverse(explode("/", "".'28/'.$_REQUEST['dtpago']."")));
				}						

				$validdata    = $func->ValidaData(date('d').'/'.$_REQUEST['dtpago']);

				if($validdata == true){

					$dao = new FolhaTxtDAO();
					$vet = $dao->ValidaFolhaMes($_SESSION["cnpj"],$_REQUEST['dtpago']);
					$num = count($vet);

					if($num == 0){

						$folha = new FolhaTxt();

						$folha->setDataPag($datapago);
						$folha->setNumFuncionario($numfunc);
						$folha->setValorFolha($valorfolha);
						$folha->setCnpjEmp($_SESSION["cnpj"]);

						$dao->inserir($folha);
						
						array_push($res,array(
							'titulo'=>"Mensagem da FOLHA",
							'mensagem'=>"Gravado com sucesso!",
							'url'=>'listar-folha.php'	,
							'tipo'=>'1'
						));

					}else{
						//compentencia ja existe
						array_push($res,array(
							'titulo'=>"Mensagem da FOLHA",
							'mensagem'=>"Competência já existe, tente outra!",
							'url'=>'cadastro-folha.php',
							'tipo'=>'2'
						));

					}
				}else{
					//data invalida
					array_push($res,array(
						'titulo'=>"Mensagem da FOLHA",
						'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
						'url'=>'cadastro-folha.php'	,
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

				($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
				$res    = array();

				if(empty($_REQUEST['numfunc'])){				
					$numfunc = "";					
				}else{
					$numfunc = $_REQUEST['numfunc'];
				}

				if(empty($_REQUEST['valorfolha'])){				
					$valorfolha = "0.00";					
				}else{
					$valorfolha = str_replace(',', '.', str_replace('.', '', $_REQUEST['valorfolha']));
				}

				if(empty($_REQUEST['dtpago'])){				
					$datapago = date('Y-m-d');					
				}else{
					$datapago = implode("-", array_reverse(explode("/", "".'28/'.$_REQUEST['dtpago']."")));
				}						
				
				$validdata    = $func->ValidaData(date('d').'/'.$_REQUEST['dtpago']);

				if($validdata == true){

					$folha = new FolhaTxt();

					$folha->setCodigo($id);
					$folha->setDataPag($datapago);
					$folha->setNumFuncionario($numfunc);
					$folha->setValorFolha($valorfolha);
					$folha->setCnpjEmp($_SESSION["cnpj"]);

					$dao = new FolhaTxtDAO();
					$dao->update($folha);

					array_push($res,array(
						'titulo'=>"Mensagem da FOLHA",
						'mensagem'=>"Alterado com sucesso!",
						'url'=>'listar-folha.php'	,
						'tipo'=>'1'
					));

				}else{
					
					array_push($res,array(
						'titulo'=>"Mensagem da FOLHA",
						'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
						'url'=>'atualizar-folha.php?id='.$id.'',
						'tipo'=>'2'
					));	

				}

				$re      = json_encode($res);
				$reponse = urlencode($re);

				$destino = "response.php?mg={$reponse}";
				header("Location:{$destino}");

			break;
			case 'delete':

				($_REQUEST['id'])     ? $id    = $_REQUEST['id']               :false;

				$folha = new FolhaTxt();

				$folha->setCodigo($id);

				$dao = new FolhaTxtDAO();

				$dao->deletar($folha);

			break;
			case 'inserirnumfun':

				$dao 	= new FolhaTxtDAO();		
				$vet 	= $dao->BuscaCodigoFolhaDia($_SESSION["cnpj"],$_SESSION["cnpj"],$_SESSION['apura']['mesano']);	
				$num 	= count($vet);	
				$result = array();

				if($num == 0){	

					if(empty($_REQUEST['nfuncionario'])){				
						$numfunc = "";					
					}else{
						$numfunc = $_REQUEST['nfuncionario'];
					}

					$datapago = implode("-", array_reverse(explode("/", "".'28/'.$_SESSION['apura']['mesano']."")));

					$folha = new FolhaTxt();

					$folha->setDataPag($datapago);
					$folha->setNumFuncionario($numfunc);			
					$folha->setCnpjEmp($_SESSION["cnpj"]);


					$dao->inserirnumfun($folha);


					array_push($result,array(					
						'msg'=>'inserido'
					));	

				}else{

					$folhas = $vet[0];

					$cod    = $folhas->getCodigo();

					if(empty($_REQUEST['nfuncionario'])){				
						$numfunc = "";					
					}else{
						$numfunc = $_REQUEST['nfuncionario'];
					}

					$folha = new FolhaTxt();

					$folha->setCodigo($cod);
					$folha->setNumFuncionario($numfunc);

					$dao->updatenumfun($folha);

					array_push($result,array(					
						'msg'=>'Alterado'
					));
				}

				echo json_encode($result);	

			break;
			case 'inserirvalorfolha':
				
				$dao 	= new FolhaTxtDAO();		
				$vet 	= $dao->BuscaCodigoFolhaDia($_SESSION["cnpj"],$_SESSION["cnpj"],$_SESSION['apura']['mesano']);	
				$num 	= count($vet);	
				$result = array();

				if($num == 0){
				
					$vlpagto  = !empty($_REQUEST['vlpagto']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['vlpagto'])) : 0;		
					$datapago = implode("-", array_reverse(explode("/", "".'28/'.$_SESSION['apura']['mesano']."")));

					$folha = new FolhaTxt();

					$folha->setDataPag($datapago);
					$folha->setValorFolha($vlpagto);			
					$folha->setCnpjEmp($_SESSION["cnpj"]);
					
					$dao->inserirvlpagto($folha);

					array_push($result,array(					
						'msg'=>'inserido'
					));	

				}else{

					$folhas = $vet[0];

					$cod    	= $folhas->getCodigo();
					$vlpagto    = !empty($_REQUEST['vlpagto']) ? str_replace(',', '.', str_replace('.', '', $_REQUEST['vlpagto'])) : 0;	

					$folha = new FolhaTxt();
						
					$folha->setCodigo($cod);	
					$folha->setValorFolha($vlpagto);
						
					$dao->updatevlpagto($folha);	

					array_push($result,array(					
						'msg'=>'Alterado'
					));
				}
				
				echo json_encode($result);

			break;	
			case 'verificacompfolha':
				
				$dtcomp = $_REQUEST['dtpago'];
				
				$dao = new FolhaTxtDAO();
				$vet = $dao->ValidaFolhaMes($_SESSION["cnpj"],$dtcomp); 
				$num = count($vet);
				$res = array();

				if($num > 0){
					array_push($res,array(
						'tipo'=>'1',
						'msg'=>'Essa competência ja existe!'
					));	

				}else{
					array_push($res,array(
						'tipo'=>'2',
						'msg'=>'ok'
					));	
				}

				echo json_encode($res);

			break;	
				
		}
	}

?>