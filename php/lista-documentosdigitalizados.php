<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/lista-documentosdigitalizados.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);
		$tpl->assign('idsys',$_SESSION['idsys']);
		
		$condicoes = array();

		if(isset($_REQUEST['filtro']) and !empty($_REQUEST['filtro'])){
			if(isset($_REQUEST['dataini']) and !empty($_REQUEST['dataini'])){

				$dataini     =  implode("-", array_reverse(explode("/", $_REQUEST['dataini'])));	

				$condicao[]  = " d.dt_emissao between '".$dataini."' ";		
			}
					
			if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

				$datafin     =  implode("-", array_reverse(explode("/", $_REQUEST['datafin'])));	

				$condicao[]  = " '".$datafin."' ";		
			}


			if(isset($_REQUEST['empresa']) and !empty($_REQUEST['empresa'])){

				$empresa    =  $_REQUEST['empresa'];	

				$condicao[]  = " d.id_empresa = '".$empresa."' ";		
			}
		}else{
			$condicao[]  = " DATE_FORMAT(d.dt_emissao, '%Y%m') = DATE_FORMAT(CURRENT_DATE, '%Y%m') ";	
		}

		$where = '';
		if(count($condicao) > 0){	
			$where = ' where'.implode('AND',$condicao);				
		}


		$dao = new DocDigitalizadoEmpresaDAO();
		$vet = $dao->ListaDocumentoDiditalizado($where);
		$num = count($vet);
		$contados = 1;
		$idemp    = "";
		if($num > 0){
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
					
					//if($contados > 0){
						
						$tpl->newBlock('listaempresanome');	
						$tpl->assign('razao_so',$razao_so);
						$tpl->assign('id_empre',$id_empre);
					//}
					
				}
					
				
				$tpl->newBlock('listaempresadocumentos');			
				
				$daos = new StatusDAO();			
				$vets = $daos->ListaStatusUm($status);
				$nums = count($vets);
				
				$stat = $vets[0];	
				
				$codigo   = $stat->getCodigo();
				$status   = $stat->getNome();
													
				
				$tpl->assign('cod',$cod);
				$tpl->assign('id_tpdoc',$id_tpdoc);
				$tpl->assign('nome',$nome);
				$tpl->assign('dt_emiss',implode("/", array_reverse(explode("-", "".$dt_emiss.""))));
				$tpl->assign('xcaminho',$xcaminho);
				$tpl->assign('document',strip_tags($document));
				$tpl->assign('xmotivo',$xmotivo);
				$tpl->assign('status',$status);
				//$contados++;

				/*$daodet = new DetalheDocDigitalizadoEmpresaDAO();
				$vetdet = $daodet->ListaDetalheDocumentoDiditalizado($cod);
				$numdet = count($vetdet);
						
				for($x = 0; $x < $numdet; $x++){
						
					$det = $vetdet[$x];		
					
					$cod  	  = $det->getCodigo();
					$docs	  = $det->getDocumento();
					$iddocdig = $det->getIdocdig();				
					$ext 	  = $dao->getExtension($docs);
					
					$tpl->newBlock('listadearquivos');
					
					$tpl->assign('docs',$docs);
					$tpl->assign('nomedocs',explode('.', str_replace('_', ' ', $docs))[0]);
					$tpl->assign('ext',$ext);
					$tpl->assign('xcaminho',$xcaminho);				
				}		*/
				
				
			}		
		}else{
			$tpl->newBlock('nonedados');
			$tpl->assign('info','Não existe arquivos Enviados!');
		}		
		
		$daomod = new ModalidadeDAO();
		$vetmode = $daomod->ListaModalidade();
		$nummode = count($vetmode);
		
		for($y = 0; $y < $nummode; $y++){
			
			$mod = $vetmode[$y];	
			
			$cod_mod  = $mod->getCodigo();
			$nome_mod = $mod->getNome();
			
			$tpl->newBlock('listamod');
			
			
			$tpl->assign('cod_mod',$cod_mod);
			$tpl->assign('nome_mod',$nome_mod);
			
		}
		
		
		$daoe = new EmpresasDAO();
		$vete = $daoe->ListaEmpresa();
		$nume = count($vete);
		for($i = 0; $i < $nume; $i++){					
			$emp = $vete[$i];
			
			$cod 		    = $emp->getCodigo();			
			$razao_social   = $emp->getRazaoSocial();
			$fantasia	    = $emp->getFantasia();
			
			$tpl->newBlock('listaempresa');
			
			
			$tpl->assign('cod',$cod);
			$tpl->assign('razao_social',$razao_social);
			$tpl->assign('fantasia',$fantasia);
			
			
		}



		
	/**************************************************************/

	$tpl->printToScreen();



?>