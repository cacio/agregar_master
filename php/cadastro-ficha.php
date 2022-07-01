<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/_master.htm');

	$tpl->assignInclude('conteudo','../tpl/cadastro-ficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$daof = new FichaDAO();
		$vetf = $daof->proximoid();
		$prox = $vetf[0];
		$proximoid = $prox->getProxid();
		
		$tpl->assign('proximoid',$proximoid);
		
		$dao = new CategoriaDAO();
		$vet = $dao->ListaCategoria();
		$num = count($vet);
		
		 
		for($i = 0; $i < $num; $i++){
		
				$cat = $vet[$i];
					
					
				$codigo = $cat->getCodigo();
				$nome   = $cat->getNome();				
				
				$tpl->newBlock('categoria');

				$tpl->assign('codigo',$codigo);
				$tpl->assign('nome',$nome);

				
		}
		
		$daoatv = new AtividadesDAO();
		$vetatv = $daoatv->ListaAtividade();
		$numatv = count($vetatv);
		
		 
		for($x = 0; $x < $numatv; $x++){
		
				$atv = $vetatv[$x];
					
					
				$codigoatv = $atv->getCodigo();
				$nomeatv   = $atv->getNome();				
				
				$tpl->newBlock('atividade');

				$tpl->assign('codigoatv',$codigoatv);
				$tpl->assign('nomeatv',$nomeatv);

				
		}
		
		
		$daoe = new EspeDAO();
		$vete = $daoe->ListaEspe();
		$nume = count($vete);
		
		 
		for($y = 0; $y < $nume; $y++){
		
				$esp = $vete[$y];
					
					
				$codigoe = $esp->getCodigo();
				$nomee   = $esp->getNome();				
				
				$tpl->newBlock('espe');

				$tpl->assign('codigoe',$codigoe);
				$tpl->assign('nomee',$nomee);

				
		}	
	/**************************************************************/

	$tpl->printToScreen();



?>