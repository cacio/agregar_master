<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/relatorioficha.htm');

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

			$condicao[]   = " f.codcategoria = '".$categoria."' ";
					
		}	
				
		if(isset($_REQUEST['dtadmissaoini']) and !empty($_REQUEST['dtadmissaoini'])){

			$dtadmissaoini    =  $_REQUEST['dtadmissaoini'];	

			$condicao[]  = " f.dataadmissao between '".implode("-", array_reverse(explode("-", "".$dtadmissaoini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtadmissaofin']) and !empty($_REQUEST['dtadmissaofin'])){

			$dtadmissaofin     =  $_REQUEST['dtadmissaofin'];	

			$condicao[]  = " '".implode("-", array_reverse(explode("-", "".$dtadmissaofin."")))."' ";		
		}						
		
		
		if(isset($_REQUEST['dtaexclusaoini']) and !empty($_REQUEST['dtaexclusaoini'])){

			$dtaexclusaoini    =  $_REQUEST['dtaexclusaoini'];	

			$condicao[]  = " f.dataexclusao between '".implode("-", array_reverse(explode("-", "".$dtaexclusaoini."")))."' ";		
		}
				
		if(isset($_REQUEST['dtaexclusaofin']) and !empty($_REQUEST['dtaexclusaofin'])){

			$dtaexclusaofin     =  $_REQUEST['dtaexclusaofin'];	

			$condicao[]  = " '".implode("-", array_reverse(explode("-", "".$dtaexclusaofin."")))."' ";		
		}				
		
		if(isset($_REQUEST['mesaniver']) and !empty($_REQUEST['mesaniver'])){

			$mesaniver     =  $_REQUEST['mesaniver'];	

			$condicao[]  = " Month(f.datanascimanto) = '".$mesaniver."' ";		
		}
			
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
			
			//´Pegando  a categoria
			$daocat = new CategoriaDAO();
			$vecatt = $daocat->ListaCategoriaUm($codcategoria);
			$numcat = count($vecatt);			
			if($numcat > 0){
				$cat = $vecatt[0];
			
				$cat_nome   = $cat->getNome();				
			}else{
				$cat_nome = "";			
			}
	
			//Pegando os esde		
			$daoesd = new EspeDAO();
			$vetesd = $daoesd->ListaEspUm($codesde);
			$numesd = count($vetesd);
			
			if($numesd > 0){
			$esp = $vetesd[0];
	
			$esd_nome   = $esp->getNome();				
			
			}else{
				$esd_nome   = "";				
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
			$tpl->assign('cat_nome',$cat_nome);
			$tpl->assign('esd_nome',$esd_nome);
			$tpl->assign('email',$email);
			
			//pegar as atividades	
			 $daoat = new AtividadesDAO();
			 $vetat = $daoat->ListaAtividadeFicha($codigo);
			 $numat = count($vetat);
			
			 
			 for($y = 0; $y < $numat; $y++){
			
					$atv = $vetat[$y];
						
						
					$at_codigo = $atv->getCodigo();
					$at_nome   = $atv->getNome();				
					
					$tpl->newBlock('atividade');
	
					$tpl->assign('at_codigo',$at_codigo);
					$tpl->assign('at_nome',$at_nome);
	
					
			}		
		}

	/**************************************************************/

	$tpl->printToScreen();



?>