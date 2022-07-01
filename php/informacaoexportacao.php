<?php
    require_once('../inc/inc.autoload.php');
    
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/informacaoexportacao.htm');
	$tpl->prepare();

	

	/**************************************************************/

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.permissao.php');
		require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);


        $dao = new ExportacaoDAO();
        $vet = $dao->ListaExportacaoCompetencia($_SESSION['cnpj']);
        $num = count($vet);

        for ($i=0; $i < $num; $i++) { 
            
            $expo = $vet[$i];    
            
            $id         = $expo->getCodigo();
			$comp       = $expo->getCompetencia();
            $nome_pt    = $expo->getPais();
            $kg_glos    = $expo->getKgGlosado();
            $valor_glos = $expo->getValorGlosado();
            $kg_vend    = $expo->getKgVend();
            $valor_vend = $expo->getValorVend();


            $tpl->newBlock('listar');

            $tpl->assign('id',$id);
            $tpl->assign('comp',$comp);
            $tpl->assign('nome_pt',$nome_pt);
            $tpl->assign('kg_glos',number_format($kg_glos,2,',','.'));
            $tpl->assign('valor_glos',number_format($valor_glos,2,',','.'));
            $tpl->assign('kg_vend',number_format($kg_vend,2,',','.'));
            $tpl->assign('valor_vend',number_format($valor_vend,2,',','.'));
        }

	/**************************************************************/

	$tpl->printToScreen();
?>