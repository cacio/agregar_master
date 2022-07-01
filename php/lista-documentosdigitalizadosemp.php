<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-documentosdigitalizadosemp.htm');

	$tpl->prepare();

	

	/**************************************************************/

				

		require_once('../inc/inc.session.php');
		/*require_once('../inc/inc.permissao.php');*/
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('email',$_SESSION['email']);
		//email
		$dao = new DocDigitalizadoEmpresaDAO();
		$vet = $dao->ListaDocumentoDiditalizadoEmp($_SESSION['id_emp']);
		$num = count($vet);
		$tpl->assign('num',$num);
		if($num > 0){
			$contados = 1;
			$idemp    = "";
			
			for($i = 0; $i < $num; $i++){
				
				$docdig = $vet[$i];	
					
				$cod 	  = $docdig->getCodigo();
				$id_tpdoc = $docdig->getIdTpDoc();
				$nome	  = $docdig->getModalidadeEnvio();
				$dt_emiss = $docdig->getDtEmissao();
				$xcaminho = $docdig->getXcaminho();
				$document = $docdig->getDocumento();
				$xmotivo  = $docdig->getXmotivo();	
				$status   = $docdig->getStatus();
				$id_empre = $docdig->getIdEmpresa();
				$razao_so = $docdig->getRazaoSocial();	
				
				if($id_empre != $idemp){
					
					$idemp = $id_empre;	
					
					if($contados > 0){
						
						//$tpl->newBlock('listaempresanome');	
						$tpl->assign('razao_so',$razao_so);
						$tpl->assign('id_empre',$id_empre);
					}
					
				}
					
				
				$tpl->newBlock('listaempresadocumentos');			
				
				if($status == 1){
					$status = 'Enviado';	
				}else if($status == 2){
					$status = 'aceito';
				}else if($status == 3){
					$status = 'Rejeitado';
				}else if($status == 4){
					$status = 'Reenviado';
				}
				
				$tpl->assign('cod',$cod);
				$tpl->assign('id_tpdoc',$id_tpdoc);
				$tpl->assign('nome',$nome);
				$tpl->assign('dt_emiss',implode("/", array_reverse(explode("-", "".$dt_emiss.""))));
				$tpl->assign('xcaminho',$xcaminho);
				$tpl->assign('document',addslashes($document));
				$tpl->assign('xmotivo',$xmotivo);
				$tpl->assign('status',$status);
				
				/*$daodet = new DetalheDocDigitalizadoEmpresaDAO();
				$vetdet = $daodet->ListaDetalheDocumentoDiditalizado($cod);
				$numdet = count($vetdet);
						
				for($x = 0; $x < $numdet; $x++){
						
					$det = $vetdet[$x];		
					
					$cod  	  = $det->getCodigo();
					$docs	  = $det->getDocumento();
					$iddocdig = $det->getIdocdig();				
					$ext 	  = $dao->getExtension($docs);
					$nomedocs = explode('.', str_replace('_', ' ', $docs));
					$tpl->newBlock('listadearquivos');
					
					$tpl->assign('docs',$docs);
					$tpl->assign('nomedocs',$nomedocs[0]);
					$tpl->assign('ext',$ext);
					$tpl->assign('xcaminho',$xcaminho);				
				}	*/	
						
			}
		}else{
			$tpl->newBlock('nonedados');
			$tpl->assign('info','Não existe arquivos Enviados!');

		}
	/**************************************************************/

	$tpl->printToScreen();



?>