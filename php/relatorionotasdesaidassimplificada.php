<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatorionotasdesaidassimplificada.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
			
		$condicao = array();		
        if(isset($_REQUEST['dataini']) and !empty($_REQUEST['dataini'])){
            $dataini       =  implode("-", array_reverse(explode("/",$_REQUEST['dataini'])));	
            $condicao[]    = " m.data_emissao between '".$dataini."' ";						
		}
		
		if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

			$datafin       =  implode("-", array_reverse(explode("/",$_REQUEST['datafin'])));	
			$condicao[]    = " '".$datafin."' ";	
					
		}
		
		if(isset($_REQUEST['cfop']) and !empty($_REQUEST['cfop'])){

			$cfop       =  $_REQUEST['cfop'];	
			$condicao[]    = " s.cfop = '".preg_replace('/[^0-9]/', '', $cfop)."' ";	
					
		}

		$cnpjemp       = $_SESSION['cnpj'];
		
		$condicao[]    = " m.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " COALESCE(p.cod_secretaria ,0) <> '99999' ";
		$condicao[]    = " (s.insc_estadual = e.insc_estadual) ";
		
		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao = new NotasSaiTxtDAO();
		$vet = $dao->RelNotasDeSaidaSimplificada($where);
		$num = count($vet);
		
		$tot_vlnota = 0;
		$tot_vlprod = 0;

		for ($i=0; $i < $num; $i++) { 
			$notasai	   = $vet[$i];		
			$dataemisao    = $notasai->getDataEmissao();
			$numero_nota   = $notasai->getNumeroNota();
			$valorproduto  = $notasai->getTotalProd();
			$valor_total_n = $notasai->getValorTotalNota();
			$cfop		   = $notasai->getCfop();
			$tot_vlnota    = $tot_vlnota + $valor_total_n;
			$tot_vlprod    = $tot_vlprod + $valorproduto;

			$tpl->newBlock('listar');

			$tpl->assign('dataemissao',date('d/m/Y',strtotime($dataemisao)));
			$tpl->assign('numeronota',$numero_nota);
			$tpl->assign('valorproduto',number_format($valorproduto,2,',','.'));
			$tpl->assign('valortotnota',number_format($valor_total_n,2,',','.') );
			$tpl->assign('cfop',$cfop);
		}

		$tpl->newBlock('total');
		$tpl->assign('tot_vlnota',number_format($tot_vlnota,2,',','.') );
		$tpl->assign('tot_vlprod',number_format($tot_vlprod,2,',','.') );
	/**************************************************************/
	$tpl->printToScreen();
		
?>