<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/atualizar-ficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);


		$id    = !empty($_REQUEST['id'])    		    ? $_REQUEST['id']    		 	: '';
		
		$dao =  new FichaDAO();
		$vet = $dao->ListaFichaUm($id);
		$num = count($vet);
		
			
		
			$ficha = $vet[0];
			
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
			$codesde		= $ficha->getCodEsde();		
			$cpf			= $ficha->getCpf();
			$email			= $ficha->getEmail();
			$profissao		= $ficha->getProfissao();
			$cep 			= $ficha->getCep();			
			
			if(strlen($celular) == 13){
				$celular1 = explode('(51)',$celular);
				$celular  = '9'.$celular1[1];
				$celular  = $celular1[0].$celular;
			}
			
			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);
			$tpl->assign('endereco',$endereco);
			$tpl->assign('numero',$numero);
			$tpl->assign('bairro',$bairro);
			$tpl->assign('cidade',$cidade);
			$tpl->assign('telefone',$telefone);
			$tpl->assign('celular',$celular);
			$tpl->assign('recado',$recado);
			$tpl->assign('dataadmissao',implode("-", array_reverse(explode("-", "".$dataadmissao.""))));
			$tpl->assign('rg',$rg);
			$tpl->assign('vlmensalidade',number_format($vlmensalidade,2,',','.'));
			$tpl->assign('obs',$obs);
			$tpl->assign('datanascimento',implode("-", array_reverse(explode("-", "".$datanascimento.""))));
			$tpl->assign('dataexclusao',implode("-", array_reverse(explode("-", "".$dataexclusao.""))));
			$tpl->assign('cpf',$cpf);
			$tpl->assign('email',$email);
			$tpl->assign('profissao',$profissao);
			$tpl->assign('cep',$cep);
			
			$daoc = new CategoriaDAO();
			$vetc = $daoc->ListaCategoriaSelecionado($codcategoria);
			$numc = count($vetc);
			
			for($x = 0; $x < $numc; $x++){
			
					$cat = $vetc[$x];
							
					$catcodigo 	 = $cat->getCodigo();
					$catnome   	 = $cat->getNome();				
					$selecionado = $cat->getSelecionado();
					
					$tpl->newBlock('categoria');
	
					$tpl->assign('catcodigo',$catcodigo);
					$tpl->assign('catnome',$catnome);
					$tpl->assign('selecionado',$selecionado);
	
			}
			
			
			$daoatv = new AtividadesDAO();
			$vetatv = $daoatv->ListaAtividade();
			$numatv = count($vetatv);
			
			 
			for($p = 0; $p < $numatv; $p++){
			
					$atv = $vetatv[$p];
						
						
					$codigoatv = $atv->getCodigo();
					$nomeatv   = $atv->getNome();				
					
					$tpl->newBlock('atividade');
	
					$tpl->assign('codigoatv',$codigoatv);
					$tpl->assign('nomeatv',$nomeatv);
	
					
			}
			
			$vetat = $daoatv->ListaAtividadeFicha($codigo);
			$numat = count($vetat);

			for($e = 0; $e < $numat; $e++){
				
				$at = $vetat[$e];
						
						
				$codigoat = $at->getCodigo();
				$nomeat   = $at->getNome();				
				$idat	  = $at->getIdDetalhe();
				$ano	  = $at->getAno();
				
				$tpl->newBlock('atividadeficha');

				$tpl->assign('codigoat',$codigoat);
				$tpl->assign('nomeat',$nomeat);
				$tpl->assign('idat',$idat);
				$tpl->assign('ano',$ano);
				
			}
			
			
			$daoe = new EspeDAO();
			$vete = $daoe->ListaEspSelecionado($codesde);
			$nume = count($vete);
			
			 
			for($y = 0; $y < $nume; $y++){
			
					$esp = $vete[$y];
						
						
					$codigoe 	  = $esp->getCodigo();
					$nomee   	  = $esp->getNome();				
					$selecionadoe = $esp->getSelecionado();
					$tpl->newBlock('espe');
	
					$tpl->assign('codigoe',$codigoe);
					$tpl->assign('nomee',$nomee);
					$tpl->assign('selecionadoe',$selecionadoe);
	
					
			}
			
	
		
		
		
	/**************************************************************/

	$tpl->printToScreen();



?>