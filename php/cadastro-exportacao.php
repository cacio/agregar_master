<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/cadastro-exportacao.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
        $dao = new PaisDAO();
        $vet = $dao->listapais();
        $num = count($vet);
        
        for ($i=0; $i < $num; $i++) { 
            
            $pais = $vet[$i];

            $id      = $pais->getCodigo();
            $nome_pt = $pais->getNome();

            $tpl->newBlock('lista');

            $tpl->assign('id',$id);
            $tpl->assign('nome',utf8_decode($nome_pt));
        }
	
	/**************************************************************/
	$tpl->printToScreen();

?>