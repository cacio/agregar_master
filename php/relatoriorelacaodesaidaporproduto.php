<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriorelacaodesaidaporproduto.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
			
		$condicao = array();		
        if(isset($_REQUEST['comp']) and !empty($_REQUEST['comp'])){
            $dataini       =  $_REQUEST['comp'];	
            $condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM m.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM m.data_emissao)) between '".$dataini."' ";						
            $condicao[]    = " '".$dataini."' ";
		}

        if(isset($_REQUEST['cfop']) and !empty($_REQUEST['cfop'])){

			$cfop       =  $_REQUEST['cfop'];	
			$condicao[]    = " s.cfop = '".preg_replace('/[^0-9]/', '', $cfop)."' ";	
					
		}

		$cnpjemp       = $_SESSION['cnpj'];
		
		$condicao[]    = " s.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " p.cod_secretaria <> '99999' ";
		$condicao[]    = " (s.insc_estadual = e.insc_estadual) ";
		
		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao = new NotasSa1TxtDAO();
        $vet = $dao->RelRelacaoSaidaProduto($where);
        $num = count($vet);
        $tot_sub = 0;
        for ($i=0; $i < $num; $i++) {
            
            $notasa1   = $vet[$i];
            $cod_prod  = $notasa1->getCodigoProduto();		
			$desc_prod = $notasa1->getDescProd();
			$peso      = $notasa1->getPeso();
            $subtotal  = $notasa1->getSubtotal();
            $tot_sub   = $tot_sub + $subtotal;

            $tpl->newBlock('listar');

            $tpl->assign('cod_prod',$cod_prod);
            $tpl->assign('desc_prod',$desc_prod);
            $tpl->assign('peso',number_format($peso,2,',','.'));
            $tpl->assign('subtotal',number_format($subtotal,2,',','.'));
        }
        $tpl->newBlock('total');
        $tpl->assign('tot_sub',number_format($tot_sub,2,',','.'));
        
	/**************************************************************/
	$tpl->printToScreen();
		
?>