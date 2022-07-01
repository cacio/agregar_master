<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/ConfigGeral.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		
		$tpl->assign('log',$_SESSION['login']);
        $pathFile      = '../arquivos/config.json';
        if(file_exists($pathFile)){
            $configJson    = file_get_contents($pathFile);
            $installConfig = json_decode($configJson);

                        
            $nomemail      = isset($installConfig->emails->nomeemail) ? $installConfig->emails->nomeemail : '';
            $smtpsegure    = isset($installConfig->emails->smtpsegure) ? $installConfig->emails->smtpsegure : '';
            $host          = isset($installConfig->emails->host) ? $installConfig->emails->host : '';
            $port          = isset($installConfig->emails->port) ? $installConfig->emails->port : '';
            $username      = isset($installConfig->emails->username) ? $installConfig->emails->username : '';
            $senhaem       = isset($installConfig->emails->senhaem) ? $installConfig->emails->senhaem : '';
            $titulo        = isset($installConfig->tela->titulo) ? $installConfig->tela->titulo : '';        
            $corpo         = isset($installConfig->tela->corpo) ? $installConfig->tela->corpo : '';

            $tpl->assign('nomemail',$nomemail);
            $tpl->assign('smtpsegure',$smtpsegure);
            $tpl->assign('host',$host);
            $tpl->assign('port',$port);
            $tpl->assign('username',$username);
            $tpl->assign('senhaem',$senhaem);
            $tpl->assign('titulo',$titulo);
            $tpl->assign('corpo',$corpo);
        }
			
		

	/**************************************************************/
	$tpl->printToScreen();

?>