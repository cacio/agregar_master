<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':

			$func   = new FuncoesDAO();
			$res    = array();
			$tipo   = !empty($_REQUEST['opt']) ? $_REQUEST['opt'] :0;
			
			if(empty($_REQUEST['compensa'])){				
				$compensa = "";					
			}else{
				$compensa = $_REQUEST['compensa'];
			}

			$validdata    = $func->ValidaData(date('d').'/'.$compensa);
			
			if($validdata == true){

				$dao = new GuiaicmsDAO();

				$validicms = $dao->ValidGuia($_SESSION['cnpj'],$compensa);
				$numimcs   = count($validicms);

				if($numimcs == 0){
				
				
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
				
				$guia = new Guiaicms();
				
				$guia->setTipo($tipo);
				$guia->setCompetenc($compensa);			
				$guia->setCodigoGuia($tipo);
				$guia->setValorPago($valorpago);			
				$guia->setCnpjEmp($_SESSION["cnpj"]);
								
				
				$dao->inserir($guia);

				array_push($res,array(
					'titulo'=>"Mensagem da GUIA ICMS",
					'mensagem'=>"Gravado com sucesso!",
					'url'=>'listar-guiacms.php'	,
					'tipo'=>'1'
				));	

				}else{

					array_push($res,array(
						'titulo'=>"Mensagem da GUIA ICMS",
						'mensagem'=>"Competência já existe, tente outra!",
						'url'=>'cadastro-guiaicms.php'	,
						'tipo'=>'2'
					));

				}

			}else{
				
				array_push($res,array(
					'titulo'=>"Mensagem da GUIA ICMS",
					'mensagem'=>"Mês e ano invalido digite uma data da competência valida!",
					'url'=>'cadastro-guiaicms.php'	,
					'tipo'=>'2'
				));

			}

			$re      = json_encode($res);
			$reponse = urlencode($re);

			$destino = "response.php?mg={$reponse}";
			header("Location:{$destino}");
			
						
		break;
		case 'alterar':
		
			$func = new FuncoesDAO();

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
			$res    = array();
			$tipo   = !empty($_REQUEST['opt']) ? $_REQUEST['opt'] :0;
			
			if(empty($_REQUEST['compensa'])){				
				$compensa = "";					
			}else{
				$compensa = $_REQUEST['compensa'];
			}
			
			$validdata    = $func->ValidaData(date('d').'/'.$compensa);
			
			if($validdata == true){

								
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
				
				$guia = new Guiaicms();
				
				$guia->setCodigo($id);
				$guia->setTipo($tipo);
				$guia->setCompetenc($compensa);			
				$guia->setCodigoGuia($tipo);
				$guia->setValorPago($valorpago);			
				$guia->setCnpjEmp($_SESSION["cnpj"]);
				//$guia->setCnpjEmp($_SESSION["cnpj"]);
				
				$dao = new GuiaicmsDAO();
				
				$dao->update($guia);

				array_push($res,array(
					'titulo'=>"Mensagem da GUIA ICMS",
					'mensagem'=>"Alterado com sucesso!",
					'url'=>'listar-guiacms.php'	,
					'tipo'=>'1'
				));	

		}else{

			array_push($res,array(
				'titulo'=>"Mensagem da GUIA ICMS",
				'mensagem'=>"Mês e ano invalido digite uma data da competencia valida!",
				'url'=>'atualizar-guiaicms.php?id='.$id.''	,
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
			
			$guia = new Guiaicms();
			
			$guia->setCodigo($id);
			
			$dao = new GuiaicmsDAO();
			
			$dao->deletar($guia);
			
		break;
		case 'inseriricmsnormal':

			$competencia   = $_SESSION['apura']['mesano'];
			$codicmsnormal = $_REQUEST['codicmsnormal'];	
			$vlicmsnormal  = str_replace(',', '.', str_replace('.', '', $_REQUEST['vlicmsnormal']));

			$dao = new GuiaicmsDAO();
			$vet = $dao->ValidGuiaicmsNormal($_SESSION["cnpj"],$_SESSION['apura']['mesano']);
			$num = count($vet);
			$result = array();

			if($num == 0){
				$guia = new Guiaicms();

				$guia->setCompetenc($competencia);		
				$guia->setCodigoGuia($codicmsnormal);
				$guia->setValorPago($vlicmsnormal);
				$guia->setTipo(1);				
				$guia->setCnpjEmp($_SESSION["cnpj"]);	

				
				$dao->inserirguia($guia);

				array_push($result, array('msg' =>'inserido' ));	

			}else{
					
				$guias = $vet[0];

				$id = $guias->getId();

				$guia = new Guiaicms();
				
				$guia->setId($id);
				$guia->setCodigoGuia($codicmsnormal);
				$guia->setValorPago($vlicmsnormal);
				
				$dao->updateguia($guia);	

				array_push($result, array('msg' =>'alterado' ));		
			}


			echo json_encode($result);
		break;
		case 'inseriricmsst':

			$competencia   = $_SESSION['apura']['mesano'];
			$codicmsst	   = $_REQUEST['codicmsst'];	
			$vlicmsst	   = str_replace(',', '.', str_replace('.', '', $_REQUEST['vlicmsst']));

			$dao = new GuiaicmsDAO();
			$vet = $dao->ValidGuiaicmsSt($_SESSION["cnpj"],$_SESSION['apura']['mesano']);
			$num = count($vet);
			$result = array();

			if($num == 0){
				$guia = new Guiaicms();

				$guia->setCompetenc($competencia);		
				$guia->setCodigoGuia($codicmsst);
				$guia->setValorPago($vlicmsst);
				$guia->setTipo(2);				
				$guia->setCnpjEmp($_SESSION["cnpj"]);	

				
				$dao->inserirguia($guia);

				array_push($result, array('msg' =>'inserido' ));	

			}else{
					
				$guias = $vet[0];

				$id = $guias->getId();

				$guia = new Guiaicms();
				
				$guia->setId($id);
				$guia->setCodigoGuia($codicmsst);
				$guia->setValorPago($vlicmsst);
				
				$dao->updateguia($guia);	

				array_push($result, array('msg' =>'alterado' ));		
			}


			echo json_encode($result);
		break;

		}
	}

?>