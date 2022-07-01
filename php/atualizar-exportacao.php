<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-exportacao.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
        
        if(!empty($_REQUEST['id'])){

            $id = $_REQUEST['id'];

            $dao = new ExportacaoDAO();
            $vet = $dao->ListaExportacaoCompetenciaAlteracao($_SESSION['cnpj'],$id);
            $num = count($vet);

            if($num > 0){
                
                $expo       = $vet[0];

                //$id         = $expo->getCodigo();
                $comp       = $expo->getCompetencia();
                $id_pais    = $expo->getPais();
                $kg_glos    = $expo->getKgGlosado();
                $valor_glos = $expo->getValorGlosado();
                $kg_vend    = $expo->getKgVend();
                $valor_vend = $expo->getValorVend();

                $tpl->assign('id',$id);
                $tpl->assign('comp',$comp);
                $tpl->assign('kgglos',number_format($kg_glos,2,',','.'));
                $tpl->assign('valor_glos',number_format($valor_glos,2,',','.'));
                $tpl->assign('kg_vend',number_format($kg_vend,2,',','.'));
                $tpl->assign('valor_vend',number_format($valor_vend,2,',','.'));

                $daop = new PaisDAO();
                $vetp = $daop->listapais();
                $nump = count($vetp);
                
                for ($i=0; $i < $nump; $i++) { 
                    
                    $pais = $vetp[$i];

                    $idps    = $pais->getCodigo();
                    $nome_pt = $pais->getNome();

                    $tpl->newBlock('lista');

                    if($idps ==  $id_pais){
                        $tpl->assign('selected','selected');    
                    }else{
                        $tpl->assign('selected','');    
                    }

                    $tpl->assign('idps',$idps);
                    $tpl->assign('nome',utf8_decode($nome_pt));


                }


            }else{

            }


        }else{


        }

	
	/**************************************************************/
	$tpl->printToScreen();

?>