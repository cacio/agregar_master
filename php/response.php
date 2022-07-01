<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/response.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
        if(!empty($_GET['mg'])){

            $mg        = $_REQUEST['mg'];            
            $getmg     = urldecode($mg);
            $me        = json_decode($getmg);
            $titulo    = $me[0]->titulo;
            $mensagem  = $me[0]->mensagem;
            $url       = $me[0]->url;
            $tipo 	   = $me[0]->tipo;
            $sec       = "3";
            
            if($tipo == 2){		 
                $tpl->assign('fa','fa-exclamation-triangle fa-4x');
            }else{
                $tpl->assign('fa','fa-check-circle fa-4x');
            }
            
            $tpl->assign('titulo',$titulo);
            $tpl->assign('mensagem',$mensagem);	
            $tpl->assign('url',$url);
       
            header("Refresh: $sec; url=$url");
            

        }else{

            $titulo   = "Ops! Acesso ínvalido!";
            $mensagem = "Acesso não está disponível no momento.";
            $sec      = "3";
            $url      = "admin.php";

            $tpl->assign('titulo',$titulo);
            $tpl->assign('mensagem',$mensagem);	
            $tpl->assign('url',$url);
            $tpl->assign('fa','fa-exclamation-triangle fa-4x');

            header("Refresh: $sec; url=$url");
            //caso algum paramentro não passado voltar
        }
				
	
	/**************************************************************/
	$tpl->printToScreen();

?>