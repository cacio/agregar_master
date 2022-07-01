<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/reenv-dados.htm');

	$tpl->prepare();

	

	/**************************************************************/
		require_once('../inc/inc.session.php');		
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$daoe = new EmpresasDAO();
		$vete = $daoe->ListaEmpresaUm($_SESSION['id_emp']);
		$nume = count($vete);
				
		$emp  = $vete[0];		
		$cnpj = $emp->getCnpj();
		$tpl->assign('cnpj',$cnpj);
		$tpl->assign('idemp',$_SESSION['id_emp']);
		
		if(empty($_REQUEST['idtpdoc'])){
			header('Location:lista-documentosdigitalizadosemp.php');
		 }
		
		$daodig = new DocDigitalizadoEmpresaDAO();
		$vetdig = $daodig->ListaAlteracaoDocumentoDiditalizado($_REQUEST['idtpdoc']);		
		$dig 	= $vetdig[0]; 
		
		$coddig   = $dig->getCodigo();
		$nomedig  = $dig->getModalidadeEnvio();		
		$xcaminho = $dig->getXcaminho();
		$document = $dig->getDocumento();
		$xmotivo  = $dig->getXmotivo();	
		$id_tpdoc = $dig->getIdTpDoc();
		
		$tpl->assign('coddig',$coddig);	
		$tpl->assign('nomedig',$nomedig);	
		$tpl->assign('xcaminho',$xcaminho);	
		$tpl->assign('xmotivo',$xmotivo);	
		$tpl->assign('document',$document);	
		
		$dao = new ModalidadeEnvDAO();
		$vet = $dao->ListaModalidadeEnvioPorModalidadeEmpresaUm($_SESSION['id_emp'],$id_tpdoc);	
		$num = count($vet);	
		
		//for($i = 0; $i < $num; $i++){
			
			$modenv   = $vet[0];
			
			$idmodenv   = $modenv->getCodigo();
			$nome  		= $modenv->getNome();
		
				
			//$tpl->newBlock('listamodalidadeenvio');
			
			
			$tpl->assign('id',$idmodenv);
			$tpl->assign('nome',$nome);	
			$tpl->assign('sel','selected');	
				
				
				
		//} 		
		
		
		$daodoc = new DocumentosDAO();
		$vetdoc = $daodoc->ListaDocumentosPorModalidadeEnvio($idmodenv);
		$numdoc = count($vetdoc);
		$results = array();
		
		for($x = 0; $x < $numdoc; $x++){
		
			$doc = $vetdoc[$x];
			
			$cod 	 = $doc->getCodigo();
			$nomedoc = $doc->getNome();			
			
			$actual_name = "".$idmodenv.'_'.str_replace(' ', '_', $nomedoc)."";

			$daode = new DetalheDocDigitalizadoEmpresaDAO();
			$vetde = $daode->VerificaNomeDoArquivo($_REQUEST['idtpdoc'],$actual_name);
			$numde = count($vetde);

			if($numde > 0){
				$det  = $vetde[0];
				$docs = $det->getDocumento();
				//$cam  = "../arquivos/{$cnpj}//{}";
				$chec  = "<i class='icon-check' style='color: green;'></i>";
				$boder = ' style="border: 1px green dotted;"';
			}else{
				$docs  = "";
				$chec  = "";
				$boder = "";
			}

			
			$tpl->newBlock('listadocument');
			
			$tpl->assign('cod',$cod);
			$tpl->assign('nomedoc',$nomedoc);	
			$tpl->assign('codv',$x);
			$tpl->assign('docs',$docs);
			$tpl->assign('chec',$chec);
			$tpl->assign('boder',$boder);
		}
				

	

	/**************************************************************/

	$tpl->printToScreen();



?>