<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/Listar-ficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');
		require_once('../inc/inc.permissao.php');
		
		$tpl->assign('log',$_SESSION['login']);

		
		$dao =  new FichaDAO();
		$vet = $dao->ListaFicha();
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
			
			$daocat = new CategoriaDAO();
			$vecatt = $daocat->ListaCategoriaUm($codcategoria);
			$numcat = count($vecatt);			
			if($numcat > 0){
				$cat = $vecatt[0];
			
				$cat_nome   = $cat->getNome();				
			}else{
				$cat_nome = "";			
			}
			
			$tpl->newBlock('ficha');
			
			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);
			$tpl->assign('endereco',$endereco.', '.$numero);
			$tpl->assign('numero',$numero);
			$tpl->assign('bairro',$bairro);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('telefone',$telefone);
			$tpl->assign('celular',$celular);
			$tpl->assign('recado',$recado);
			$tpl->assign('dataadmissao',$dataadmissao);
			$tpl->assign('rg',$rg);
			$tpl->assign('vlmensalidade',number_format($vlmensalidade,2,',','.'));
			$tpl->assign('obs',$obs);
			$tpl->assign('obs',$obs);
			$tpl->assign('cat_nome',$cat_nome);
			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('per');
				$tpl->assign('codigo',$codigo);
			}
		}			
		
	
		$vets = $dao->ListaFichaExcluidos();
		$nums = count($vets);
		
		for($x = 0; $x < $nums; $x++){
		
			$fichas = $vets[$x];
			
			$codigos		 = $fichas->getCodigo();
			$nomes      	 = $fichas->getNome();
			$enderecos  	 = $fichas->getEndereco();
			$numeros    	 = $fichas->getNumero();
			$bairros    	 = $fichas->getBairro();
			$cidades	   	 = $fichas->getCidade();
			$telefones  	 = $fichas->getTelefone();
			$celulars        = $fichas->getCelular();
			$recados	   	 = $fichas->getRecados();
			$dataadmissaos   = $fichas->getDataAdmissao();
			$rgs		   	 = $fichas->getRg();	
			$vlmensalidades  = $fichas->getValorMensalidade();
			$obss		     = $fichas->getObs();
			$datanascimentos = $fichas->getDataNascimento();
			$dataexclusaos   = $fichas->getDataExclusao();
			$codcategorias   = $fichas->getCodCategoria();
			
			$daocat = new CategoriaDAO();
			$vecatt = $daocat->ListaCategoriaUm($codcategorias);
			$numcat = count($vecatt);			
			if($numcat > 0){
				$cat = $vecatt[0];
			
				$cat_nomes   = $cat->getNome();				
			}else{
				$cat_nomes = "";			
			}
			
			$tpl->newBlock('fichas');
			
			$tpl->assign('codigos',$codigos);
			$tpl->assign('nomes',$nomes);
			$tpl->assign('enderecos',$enderecos.', '.$numeros);
			$tpl->assign('numeros',$numeros);
			$tpl->assign('bairros',$bairros);
			$tpl->assign('cidades',$cidades);
			$tpl->assign('telefones',$telefones);
			$tpl->assign('celulars',$celulars);
			$tpl->assign('recados',$recados);
			$tpl->assign('dataadmissaos',$dataadmissaos);
			$tpl->assign('rg',$rgs);
			$tpl->assign('vlmensalidades',number_format($vlmensalidades,2,',','.'));
			$tpl->assign('obss',$obss);		
			$tpl->assign('cat_nomes',$cat_nomes);
			if($_SESSION['idsys'] == 1){
				$tpl->newBlock('pers');
				$tpl->assign('codigos',$codigos);
			}
		}
		
		
	/**************************************************************/

	$tpl->printToScreen();



?>