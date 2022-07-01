<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriototaldeabatepordata.htm');
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
            if($_REQUEST['radio'] == 1){
                
                $condicao[]    = " n.data_abate between '".$dataini."' ";	
            }else{
                $condicao[]    = " n.data_emissao between '".$dataini."' ";	
            }
			
					
		}
		
		if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

			$datafin       =  implode("-", array_reverse(explode("/",$_REQUEST['datafin'])));	
			$condicao[]    = " '".$datafin."' ";	
					
		}


		$cnpjemp       = $_SESSION['cnpj'];		
		$condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " a.codigo in ('1','2','3','1001','1002','1003') ";
	    $condicao[]    = " COALESCE(p.cod_secretaria ,0) < 10000 ";
        $condicao[]    = " COALESCE(p.cod_secretaria ,0) <> '99999' ";

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

        $dao = new NotasEn1TxtDAO();
        $vet = $dao->RelatorioTotaldeabatepordata($where);
        $num = count($vet);
        $tot_cabeca = 0;
        for ($i=0; $i < $num; $i++) { 
            
            $notasen1   = $vet[$i];

            $data_abate = $notasen1->getDataAbate();
			$especie    = $notasen1->getEspecie();
			$qtd_cabeca = $notasen1->getQtdCabeca();
            $tot_cabeca = $tot_cabeca + $qtd_cabeca;

            $tpl->newBlock('listar');

            $tpl->assign('data_abate',date('d/m/Y',strtotime($data_abate)));
            $tpl->assign('especie',$especie);
            $tpl->assign('qtdcabeca',$qtd_cabeca);
        }

        $tpl->newBlock('total');
        $tpl->assign('tot_cabeca',$tot_cabeca);

        $vet2 = $dao->RelatorioTotaldeabatepordataNota($where);
        $num2 = count($vet2);
        $tot_cabecas = 0;
        for ($i=0; $i < $num2; $i++) { 

            $notasen   = $vet2[$i];

            $data_abates = $notasen->getDataAbate();
			$especies    = $notasen->getEspecie();
			$qtd_cabecas = $notasen->getQtdCabeca();
            $numero_nota = $notasen->getNumeroNota();
            $tot_cabecas = $tot_cabecas + $qtd_cabecas;

            $tpl->newBlock('lista');

            $tpl->assign('data_abates',date('d/m/Y',strtotime($data_abates)));
            $tpl->assign('especies',$especies);
            $tpl->assign('qtdcabecas',$qtd_cabecas);
            $tpl->assign('numero_nota',$numero_nota);
        }

        $tpl->newBlock('totals');
        $tpl->assign('tot_cabecas',$tot_cabecas);

	/**************************************************************/
	$tpl->printToScreen();
		
?>