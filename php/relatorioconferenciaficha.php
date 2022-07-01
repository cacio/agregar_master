<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatorioconferenciaficha.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$condicao  = array();
		$condicao2 = array();		
			
			
		if(isset($_REQUEST['categoria']) and !empty($_REQUEST['categoria'])){

			$categoria       =  $_REQUEST['categoria'];	
			
			if($categoria == 1){
				$condicao[]   = " f.cpf = '' ";
			}else if($categoria == 2){
				$condicao[]   = " f.rg = '' ";
			}else if($categoria == 3){
				$condicao[]   = " (f.cpf = '' or f.rg = '') ";
			}
		}	
				
		$condicao[]   = " f.codcategoria = '1' ";
		
		$where = '';

		if(count($condicao) > 0){
		
			$where = ' where'.implode('AND',$condicao);
				
		}	
			
		
		$dao = new FichaDAO();
		$vet = $dao->Relatorioficha($where);
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
		
			$ficha = $vet[$i];
			
			
			$codigo			= $ficha->getCodigo();	
			$nome      	    = $ficha->getNome();
			$endereco  	    = $ficha->getEndereco();
			$numero    	    = $ficha->getNumero();
			$bairro    	    = $ficha->getBairro();
			$cidade	   	    = $ficha->getCidade();
			$telefone  	    = $ficha->getTelefone();
			$celular        = $ficha->getCelular();
			$recado	   	    = $ficha->getRecados();
			$dataadmissao   = $ficha->getDataAdmissao();
			$rg		   	    = $ficha->getRg();	
			$vlmensalidade  = $ficha->getValorMensalidade();
			$obs		    = $ficha->getObs();
			$datanascimento = $ficha->getDataNascimento();
			$dataexclusao   = $ficha->getDataExclusao();
			$codcategoria   = $ficha->getCodCategoria();
			$ativo			= $ficha->getAtivo();
			$codesde		= $ficha->getCodEsde();
			$email			= $ficha->getEmail();
			$cpf			= $ficha->getCpf();
			
			if($cpf == ""){
				$cpf = "FALTA CPF";
			}
			
			if($rg == ""){
				$rg = "FALTA RG";
			}
			
			$tpl->newBlock('ficha');
			
			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);
			$tpl->assign('endereco',$endereco);
			$tpl->assign('numero',$numero);
			$tpl->assign('bairro',$bairro);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('telefone',$telefone);
			$tpl->assign('celular',$celular);
			$tpl->assign('recado',$recado);
			$tpl->assign('dataadmissao',implode("/", array_reverse(explode("-", "".$dataadmissao.""))));
			$tpl->assign('dataexclusao',implode("/", array_reverse(explode("-", "".$dataexclusao.""))));
			$tpl->assign('rg',$rg);
			$tpl->assign('vlmensalidade',number_format($vlmensalidade,2,',','.'));
			$tpl->assign('obs',$obs);
			$tpl->assign('datanascimento',implode("/", array_reverse(explode("-", "".$datanascimento.""))));		
			$tpl->assign('email',$email);
			$tpl->assign('cpf',$cpf);
			
		}

	/**************************************************************/

	$tpl->printToScreen();



?>