<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriofechamentoporcfop.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$condicao  = array();
        $condicao2 = array();
        $condicao3 = array();

        $cnpjemp   = $_SESSION['cnpj'];

        if(isset($_REQUEST['comp']) and !empty($_REQUEST['comp'])){
            $comp           =  $_REQUEST['comp'];	            
            $condicao[]     = " concat(lpad(EXTRACT(MONTH FROM n.data_abate),2,'0'),'/',EXTRACT(YEAR FROM n.data_abate)) = '".$comp."' ";
            $condicao2[]    = " concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) = '".$comp."' ";
            $condicao3[]    = " concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,'0'),'/',EXTRACT(YEAR FROM s.data_emissao)) = '".$comp."' ";    
		}
		
		$condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
		#$condicao[]    = " p.cod_secretaria < 10000 ";
		
        $condicao2[]   = " s.cnpj_emp = '".$cnpjemp."' ";
        $condicao2[]   = " p.cod_secretaria <> '99999' ";
        $condicao2[]   = " (s.insc_estadual = e.insc_estadual) ";
        $condicao2[]   = " s.cfop >  5000 ";

        $condicao3[]   = " s.cnpj_emp = '".$cnpjemp."' ";
        $condicao3[]   = " p.cod_secretaria <> '99999' ";
        $condicao3[]   = " (s.insc_estadual = e.insc_estadual) ";
        $condicao3[]   = " s.cfop < 5000 ";
        
		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

        $where2 = '';
		if(count($condicao2) > 0){		
			$where2 = ' where'.implode('AND',$condicao2);				
		}

        $where3 = '';
		if(count($condicao3) > 0){		
			$where3 = ' where'.implode('AND',$condicao3);				
		}


        if(!isset($_REQUEST['ckprod'])){
            $tpl->assign('hide','style="display:none;"');
            $tpl->assign('hide2','display:none;'); 
        }else{
            $tpl->assign('hide',''); 
            $tpl->assign('hide2',''); 
        }

        /*
            Entrada
        */
		$dao = new NotasEn1TxtDAO();
		$vet = $dao->RelatorioEntradaFechamentoCfop($where);
        $num = count($vet);
        $contador  = 0;
        $xcfop     = "";
        $xcfop2    = "";
        $tot_base  = 0;
        $tot_cred  = 0;

        $tot_base_cfop  = 0;
        $tot_cred_cfop  = 0;

        for ($i=0; $i < $num; $i++) { 
            
            $notasen1   = $vet[$i];

            $cod_secretaria = $notasen1->getCodSecretaria();
			$descprod       = $notasen1->getDescProd();	
			$subtotal       = $notasen1->getSubTotal();
			$cfop           = $notasen1->getCfop();
			$Nome           = $notasen1->getNomeCfop();
			$devolucao      = $notasen1->getDevolucao();
            $entsai         = $notasen1->getEntSai();
            $xp             = "";

            if($cfop != $xcfop2){
                $xcfop2 = $cfop;

                if($contador > 0){

                    $tpl->newBlock('totcfop');                    
                    $tpl->assign('tot_base', number_format($tot_base,2,',','.'));
                    $tpl->assign('tot_cred',number_format($tot_cred,2,',','.'));
                    $tot_base_cfop = $tot_base_cfop + $tot_base;
                    $tot_cred_cfop = $tot_cred_cfop + $tot_cred;
                }
                $tot_base  =0;
                $tot_cred  =0;
                //$tot_base_cfop1 = 0;
                //$tot_base_cfop1 = 0;
            }

            if($cfop != $xcfop){
                $xcfop = $cfop;

                $tpl->newBlock('listcfop');
                $tpl->assign('codcfop',$cfop);
                $tpl->assign('Nome',strtoupper($Nome));
                $xp = "PRODUTOS";
                if(!isset($_REQUEST['ckprod'])){
                    $tpl->assign('hide','style="display:none;"');
                    $tpl->assign('hide2','display:none;'); 
                }else{
                    $tpl->assign('hide',''); 
                    $tpl->assign('hide2',''); 
                }
            }

            if($devolucao == 'S'){
                $base    = -abs($subtotal);
            }else{
                $base    =  ($subtotal);
            }


            if($entsai == 'E'){
                $perc = 3.6;
                $credito = (($base * 3.6) / 100);
            }else if($entsai == 'S'){

                if($cod_secretaria < 10000){
                    $perc = 3;
                    $credito = (($base * 3) / 100);
                }else{
                    $perc = 4;
                    $credito = (($base * 4) / 100);
                }

            }

            
            
            $tot_base = $tot_base + $base;
            $tot_cred = $tot_cred + $credito;

            $tpl->newBlock('lista');

            if(!isset($_REQUEST['ckprod'])){
                $tpl->assign('hide','style="display:none;"'); 
            }else{
                $tpl->assign('hide',''); 
            }

            $tpl->assign('descprod',$descprod);
            $tpl->assign('base', number_format($base,2,',','.'));
            $tpl->assign('credito',number_format($credito,2,',','.'));
            $tpl->assign('perc',$perc.'%');
            $tpl->assign('cod_secretaria',$cod_secretaria);
            $tpl->assign('xp',$xp);

            $contador++;
        }

        $tpl->newBlock('totcfop');                    
        $tpl->assign('tot_base', number_format($tot_base,2,',','.'));
        $tpl->assign('tot_cred',number_format($tot_cred,2,',','.'));
        
        $tot_base_cfop = $tot_base_cfop + $tot_base;
        $tot_cred_cfop = $tot_cred_cfop + $tot_cred;

        $tpl->newBlock('total'); 
        $tpl->assign('tot_base_cfop', number_format($tot_base_cfop,2,',','.'));
        $tpl->assign('tot_cred_cfop',number_format($tot_cred_cfop,2,',','.'));

        if(!isset($_REQUEST['ckprod'])){
            $tpl->assign('hide','style="display:none;"');
            $tpl->assign('hide2','display:none;'); 
        }else{
            $tpl->assign('hide',''); 
            $tpl->assign('hide2',''); 
        }

        /*
           Fim Entrada
        */

        /*
            Saidas
        */

        
        $daos =  new NotasSa1TxtDAO();
        $vets = $daos->RelatorioFechamentoPorCfopSaida($where2,$where3);
        $num2 = count($vets);

        $contador2  = 0;
        $xcfop1     = "";
        $xcfop3     = "";
        $tot_base1  = 0;
        $tot_cred1  = 0;
        $tot_base_cfop1  = 0;
        $tot_cred_cfop1  = 0;

        for ($i=0; $i < $num2; $i++) { 

            $notasa1        = $vets[$i];

            $cod_secretaria = $notasa1->getCodSecretaria();
			$desc_prod      = $notasa1->getDescProd();
			$subtotal       = $notasa1->getSubtotal();
			$cfop           = $notasa1->getCfop();
			$Nome           = $notasa1->getNomeCfop();
			$devolucao      = $notasa1->getDevolucao();
            $uf             = $notasa1->getUf();
            $xp2             = "";

            if($cfop != $xcfop3){
                $xcfop3 = $cfop;

                if($contador2 > 0){

                    $tpl->newBlock('totcfop2');                    
                    $tpl->assign('tot_base1', number_format($tot_base1,2,',','.'));
                    $tpl->assign('tot_cred1',number_format($tot_cred1,2,',','.'));
                    $tot_base_cfop1 = $tot_base_cfop1 + $tot_base1;
                    $tot_cred_cfop1 = $tot_cred_cfop1 + $tot_cred1;
                }

                //$tot_base_cfop1 = 0;
                //$tot_base_cfop1 = 0;
                $tot_base1  =0;
                $tot_cred1  =0;
            }

            if($cfop != $xcfop1){
                $xcfop1 = $cfop;

                $tpl->newBlock('listcfop2');
                $tpl->assign('codcfop2',$cfop);
                $tpl->assign('Nome2',strtoupper($Nome));
                $xp2 = "PRODUTOS";
                if(!isset($_REQUEST['ckprod'])){
                    $tpl->assign('hide','style="display:none;"');
                    $tpl->assign('hide2','display:none;'); 
                }else{
                    $tpl->assign('hide',''); 
                    $tpl->assign('hide2',''); 
                }
            }

            //$uf
            if($cod_secretaria < 10000 and trim($uf) == 'RS'){
                
                if($devolucao == 'S'){
                    $base1    = -abs($subtotal);    
                }else{
                    $base1    = $subtotal;
                }
                
                $perc = 3;
                $credito1 = (($base1 * 3) / 100);
            }else if($cod_secretaria >= 10000 and trim($uf) == 'RS'){
                $perc = 4;
                if($devolucao == 'S'){
                    $base1    = -abs($subtotal);    
                }else{
                    $base1    = $subtotal;
                }
                $credito1 = (($base1 * 4) / 100);
            }else if($cod_secretaria < 10000 and trim($uf) <> 'RS'){
                $perc = 3;
                if($devolucao == 'S'){
                    $base1    = -abs($subtotal);    
                }else{
                    $base1    = $subtotal;
                }
                $credito1 = (($base1 * 3) / 100);
            }else if($cod_secretaria > 10000 and trim($uf) <> 'RS'){
                $perc = 4;
                if($devolucao == 'S'){
                    $base1    = -abs($subtotal);    
                }else{
                    $base1    = $subtotal;
                }
                $credito1 = (($base1 * 4) / 100);
            }   
            
            $tot_base1 = $tot_base1 + $base1;
            $tot_cred1 = $tot_cred1 + $credito1;

            $tpl->newBlock('lista2');
            if(!isset($_REQUEST['ckprod'])){
                $tpl->assign('hide','style="display:none;"'); 
            }else{
                $tpl->assign('hide',''); 
            }
            $tpl->assign('descprod',$desc_prod);
            $tpl->assign('base1', number_format($base1,2,',','.'));
            $tpl->assign('credito1',number_format($credito1,2,',','.'));
            $tpl->assign('perc',$perc.'%');
            $tpl->assign('cod_secretaria',$cod_secretaria);
            $tpl->assign('xp2',$xp2);

            $contador2++;
        }

        $tpl->newBlock('totcfop2');                    
        $tpl->assign('tot_base1', number_format($tot_base1,2,',','.'));
        $tpl->assign('tot_cred1',number_format($tot_cred1,2,',','.'));
        
        $tot_base_cfop1 = $tot_base_cfop1 + $tot_base1;
        $tot_cred_cfop1 = $tot_cred_cfop1 + $tot_cred1;

        $tpl->newBlock('total1'); 
        $tpl->assign('tot_base_cfop1', number_format($tot_base_cfop1,2,',','.'));
        $tpl->assign('tot_cred_cfop1',number_format($tot_cred_cfop1,2,',','.'));
        if(!isset($_REQUEST['ckprod'])){
            $tpl->assign('hide','style="display:none;"');
            $tpl->assign('hide2','display:none;'); 
        }else{
            $tpl->assign('hide',''); 
            $tpl->assign('hide2',''); 
        }
        
        $export = new ExportacaoDAO();
		$vetexp = $export->ComputaCompetenciaExportacao($comp,$_SESSION['cnpj']);
		$numexp = count($vetexp);

		if($numexp > 0){
			$expo		= $vetexp[0];
			$valor_glos = $expo->getValorGlosado();
		}else{
			$valor_glos = 0;
		}
        $tpl->newBlock('totalgeral'); 
        $tpl->assign('valor_glos',number_format($valor_glos,2,',','.'));
	/**************************************************************/
	$tpl->printToScreen();
		
?>