<?php
	
	require_once('../inc/inc.autoload.php');
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/admin.htm');
	$tpl->prepare();
	
	/**************************************************************/	

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		require_once('../inc/inc.pushmensagem.php');
		
		$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
		$configJson    = file_get_contents($pathFile);
		$installConfig = json_decode($configJson);

		$encoding = 'UTF-8'; // ou ISO-8859-1...		

		$tpl->assign('log',mb_convert_case($_SESSION['login'], MB_CASE_UPPER, $encoding));
		$tpl->assign('email',$_SESSION['email']);

		
		if($_SESSION['idsys'] == 2){

			$tpl->newBlock('empresa');

			$daou = new Usuario2DAO();
			$vetu = $daou->VerificaSenhaFraca($_SESSION['id_emp']);
			
			if($vetu[0]['valid'] == 'true'){
				$tpl->newBlock('senhafranca');				
			}


			$dao = new ResumoDAO();
			$vet = $dao->ListaCompetencias($_SESSION['cnpj']);	
			$num = count($vet);		
						
				$ii = 0;
				for($i = 0; $i < $num; $i++){

					$resu 		= $vet[$i];			
					$codigo		= $resu->getCodigo();	   
					$competenc  = $resu->getCompetenc();
					$nomstatus	= $resu->getNomeStatus();
					$protocolo  = $resu->getProtocolo();
					$tipoarq    = $resu->getTipoArq();	

					$data       = implode("-", array_reverse(explode("/", "".'01/'.$competenc."")));						
					$nomesmes   = date('M',strtotime($data));

					$mes_extenso = array(
						'Jan' => 'Jan',
						'Feb' => 'Fev',
						'Mar' => 'Mar',
						'Apr' => 'Abr',
						'May' => 'Mai',
						'Jun' => 'Jun',
						'Jul' => 'Jul',
						'Aug' => 'Ago',
						'Nov' => 'Nov',
						'Sep' => 'Set',
						'Oct' => 'Out',
						'Dec' => 'Dez'
					);

					if($tipoarq == '1'){
						//xml
						$tipoarq	  = "xml";	
					}else if($tipoarq == '2'){
						$tipoarq     = "txt";	
					}

					if(/*$nomstatus == 'Entregue' or*/ $nomstatus == 'Arquivo Enviado'){
						$tpl->newBlock('listar');	

						$tpl->assign('codigo',$codigo);
						$tpl->assign('competenc',$competenc);
						$tpl->assign('nomesmes',$mes_extenso["$nomesmes"]);
						$tpl->assign('cnpj',$_SESSION['cnpj']);
						$tpl->assign('protocolo',$protocolo);
						$tpl->assign('tipoarq',$tipoarq);
						
						$ii++;
						
					}
				}
					
				$tpl->newBlock('numtotal');
				$tpl->assign('total',$ii);

			$daos =  new ProtocoloDAO();
			$vets =  $daos->ListaCompetenciasEmProgresso($_SESSION['cnpj']);
			$nums = count($vets);			
			
			for($x = 0; $x < $nums; $x++){
				
				$prot		 = $vets[$x];	
				$cod 		 = $prot->getCodigo();
				$competencia = $prot->getCompetencia();
				$nome        = $prot->getStatus();
				$tipo_arq    = $prot->getTipoArq();
				$data2       = implode("-", array_reverse(explode("/", $competencia)));
				$nomesme     = date('M',strtotime($data2));
				$url	     = "";	
				$mes_extens = array(
			        'Jan' => 'Jan',
			        'Feb' => 'Fev',
			        'Mar' => 'Mar',
			        'Apr' => 'Abr',
			        'May' => 'Mai',
			        'Jun' => 'Jun',
			        'Jul' => 'Jul',
			        'Aug' => 'Ago',
			        'Nov' => 'Nov',
			        'Sep' => 'Set',
			        'Oct' => 'Out',
			        'Dec' => 'Dez'
				);
				
				if($tipo_arq == '1'){
					//xml
					$url	     = "importa_agregar_xml.php";	
				}else if($tipo_arq == '2'){
					$url	     = "importa_agregar_txt.php";	
				}
				
				/*if($nome == 'Entregue'){
					$nome = 'Entregue - (Não enviado!)';
				}*/
				
				$tpl->newBlock('listar2');
				$tpl->assign('nomesme',$mes_extens["$nomesme"]);
				$tpl->assign('competencia',$competencia);
				$tpl->assign('nome',$nome);
				$tpl->assign('cnpj2',$_SESSION['cnpj']);
				$tpl->assign('url',$url);
			}
			
			$tpl->newBlock('numtotal2');
			$tpl->assign('total2',$nums);

			if(!empty($installConfig->abtxt)){
				if($installConfig->abtxt == 'on'){
					$tpl->newBlock('ativaabtxt');
				}
			}
			if(!empty($installConfig->abmanual)){
				if($installConfig->abmanual == 'on'){
					$tpl->newBlock('ativaabmanual');	
				}
			}

		}else{

			$tpl->newBlock('adm');

			$dao = new MensagemEmpresaDAO();
			$vet = $dao->CountMesagemNaoLidas();
			$num = count($vet);

			if($num > 0){

				$msg      = $vet[0];				
				$naolidas = $msg->getNumNaoLidas();
			}else{
				$naolidas = 0;
			}

			$daoprot = new ProtocoloDAO();
			$vetprot = $daoprot->NumerosDeCompetenciaEnviada();
			$numprot = count($vetprot);

			if($numprot > 0){
				
				$prot = $vetprot[0];

				$numcomp = $prot->getNumeroComp();

			}else{
				$numcomp = 0;
			}

			$daoarq = new DocDigitalizadoEmpresaDAO();
			$vetarq = $daoarq->NumerosDeArquivosEnvMes();
			$numarq = count($vetarq);

			if($numarq > 0){
				$docdig  = $vetarq[0];
				$num_arq = $docdig->getNumeroArquivo();
			}else{
				$num_arq = 0;
			}

			
			$tpl->assign('num_arq',$num_arq);
			$tpl->assign('numcomp',$numcomp);
			$tpl->assign('naolidas',$naolidas);

		}



	/**************************************************************/

	$tpl->printToScreen();

?>