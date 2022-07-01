<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-Empresas.htm');

	$tpl->prepare();

	

	/**************************************************************/

		

		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		

		$dao = new EmpresasDAO();
		$vet = $dao->ListaEmpresa();
		$num = count($vet);
		
		for($i = 0; $i < $num; $i++){
			
		
			$emp = $vet[$i];
			
			
			
			$cod 		    = $emp->getCodigo();
			$cnpj 		    = $emp->getCnpj();
			$razao_social   = $emp->getRazaoSocial();
			$fantasia	    = $emp->getFantasia();
			$marca		    = $emp->getMarca();
			$insc_estadual  = $emp->getInscricaoEstadual();
			$endereco	    = $emp->getEndereco();
			$numero		    = $emp->getNumero();
			$complemento    = $emp->getComplemento();
			$cep		    = $emp->getCep();
			$cidade		    = $emp->getCidade();
			$Estado		    = $emp->getEstado();
			$bairro		    = $emp->getBairro();
			$inspecao	    = $emp->getInspecao();
			$fone1		    = $emp->getFone1();
			$fone2		    = $emp->getFone2();
			$email		    = $emp->getEmail();
			$responsavel    = $emp->getResponsavel();
			$idmodalidade   = $emp->getIdModalidade();
			$capacidade_bov = $emp->getCapacidadeBovino();
			$capacidade_ovi = $emp->getCapacidadeOvino();	
			
			$tpl->newBlock('listaempresa');
			
			
			$tpl->assign('cod',$cod);
			$tpl->assign('cnpj',$cnpj);
			$tpl->assign('razao_social',$razao_social);
			$tpl->assign('fantasia',$fantasia);
			$tpl->assign('marca',$marca);
			$tpl->assign('insc_estadual',$insc_estadual);
			$tpl->assign('endereco',$endereco);
			$tpl->assign('numero',$numero);
			$tpl->assign('complemento',$complemento);
			$tpl->assign('cep',$cep);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('Estado',$Estado);
			$tpl->assign('bairro',$bairro);
			$tpl->assign('inspecao',$inspecao);
			$tpl->assign('fone1',$fone1);
			$tpl->assign('fone2',$fone2);
			$tpl->assign('email',$email);
			$tpl->assign('responsavel',$responsavel);
			$tpl->assign('idmodalidade',$idmodalidade);
			$tpl->assign('capacidade_bov',$capacidade_bov);
			$tpl->assign('capacidade_ovi',$capacidade_ovi);
			
								
	
		}

		

	/**************************************************************/

	$tpl->printToScreen();



?>