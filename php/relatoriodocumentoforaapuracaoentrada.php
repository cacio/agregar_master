<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriodocumentoforaapuracaoentrada.htm');
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
        if(isset($_REQUEST['comp']) and !empty($_REQUEST['comp'])){
            $dataini       = $_REQUEST['comp'];	
            if($_REQUEST['radio'] == 1){
                
                $condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM n.data_abate), 2, '0'), '/', EXTRACT(YEAR FROM n.data_abate)) between '".$dataini."' ";	
            }else{
                $condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM n.data_emissao)) between '".$dataini."' ";	
            }
            
            $condicao2[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM s.data_emissao)) between '".$dataini."' ";
			$condicao3[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, '0'), '/', EXTRACT(YEAR FROM s.data_emissao)) between '".$dataini."' ";						
					
		}
		
		if(isset($_REQUEST['comp']) and !empty($_REQUEST['comp'])){

			$datafin       =  $_REQUEST['comp'];	
			$condicao[]    = " '".$datafin."' ";	
			$condicao2[]   = " '".$datafin."' ";
			$condicao3[]   = " '".$datafin."' ";			
		}


		$cnpjemp       = $_SESSION['cnpj'];		
        $condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
        $condicao[]    = " p.cnpj_emp = '".$cnpjemp."' ";   
		$condicao[]    = " (a.codigo not IN ('1' , '2', '3', '1001', '1002', '1003') OR COALESCE(p.cod_secretaria, 0) IN('99999'))";
        $condicao[]    = " COALESCE(p.cod_secretaria, 0) < 10000  ";  
        //$condicao[]    = " COALESCE(p.cod_secretaria, 0) <> '99999'  ";  
        
        $condicao2[]    = " s.cnpj_emp = '".$cnpjemp."' ";
        $condicao2[]    = " e.uf = 'RS' ";
        $condicao2[]    = " (a.codigo IN ('1' , '2', '3', '1001', '1002', '1003','99999') OR p.cod_secretaria IN('99999')) ";
		
		$condicao3[]    = " s.cnpj_emp = '".$cnpjemp."' ";
        $condicao3[]    = " s.vICMS = '0.00' ";
        $condicao3[]    = " p.cod_secretaria NOT IN('99999') ";

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
		
        /*
            NOTAS DE ENTRADA
        */
		$dao = new NotasEn1TxtDAO();
        $vet = $dao->RelDocumentoForaApuracaoEntrada($where);
        $num = count($vet);
        $tot_nota = 0;

        for ($i=0; $i < $num; $i++) { 
            
            $notasen1       = $vet[$i];

            $vivo           = $notasen1->getVivo();
			$rendimento     = $notasen1->getRendimento();
			$numero_nota    = $notasen1->getNumeroNota();
			$codigo_produto = $notasen1->getCodigoProduto();
			$desc_prod      = $notasen1->getDescProd();
			$cod_secretaria = $notasen1->getCodSecretaria();
			$dessecretaria  = $notasen1->getDescSecretaria();
			$peso_carcasa   = $notasen1->getPesoCarcasa();
			$peso_vivo_cabe = $notasen1->getPesoVivoCabeca();
            $preco_quilo    = $notasen1->getPrecoQuilo();
            $tipo_r_v       = $notasen1->getTipo_R_V();

            if($tipo_r_v == 'V'){
                $peso  = $peso_vivo_cabe;
                $total = $vivo;
            }else if($tipo_r_v == 'R'){
                $peso  = $peso_carcasa;
                $total = $rendimento;
            }else{
                $peso  = 0;
                $total = 0;
            }

            $tot_nota = $tot_nota + $total;

            $tpl->newBlock('lista');

            $tpl->assign('vivo',$vivo);
            $tpl->assign('redimento',$rendimento);
            $tpl->assign('numeronota',$numero_nota);
            $tpl->assign('codigo_produto',$codigo_produto);
            $tpl->assign('desc_prod',$desc_prod);
            $tpl->assign('cod_secretaria',$cod_secretaria);
            $tpl->assign('dessecretaria',$dessecretaria);
            $tpl->assign('peso_carcasa',$peso_carcasa);
            $tpl->assign('peso_vivo_cabe',$peso_vivo_cabe);
            $tpl->assign('preco_quilo',number_format($preco_quilo,2,',','.'));
            $tpl->assign('peso',number_format($peso,2,',','.'));
            $tpl->assign('total',number_format($total,2,',','.'));
        }

        $tpl->newBlock('total');
        $tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));

        /*
            NOTAS DE SAIDAS
        */
        $daos =  new NotasSaiTxtDAO();
        $vets = $daos->RelNotasDeSaidaNaoConciderar($where2);
        $nums = count($vets);
        $tot_nota_saida = 0;
        for ($x=0; $x <  $nums; $x++) { 
            
            $notasai          = $vets[$x];

            $numero_item_nota = $notasai->getItemNota();
			$numero_nota      = $notasai->getNumeroNota();
			$entrada          = $notasai->getEntrada();
			$saida            = $notasai->getSaida();
			$codigo_produto   = $notasai->getCodigoProduto();
			$desc_prod        = $notasai->getDescProduto();
			$cod_secretaria   = $notasai->getCodSecretaria();
			$dessecretaria    = $notasai->getDescSecretaria();
			$qtd_pecas        = $notasai->getPecas();
			$peso             = $notasai->getPeso();
			$preco_unitario   = $notasai->getPrecoUnitario();
			$ent_sai          = $notasai->getEntSai();
			$cfop             = $notasai->getCfop();
			$cfopcon          = $notasai->getCfopSN();

            if($ent_sai == 'S'){
                $sub = $saida;
            }else{
                $sub = $entrada;
            }
            $tot_nota_saida = $tot_nota_saida + $sub;
            $tpl->newBlock('listasaidas');

            $tpl->assign('numero_item_nota',$numero_item_nota);
            $tpl->assign('numero_nota',$numero_nota);
            $tpl->assign('codigo_produto',$codigo_produto);
            $tpl->assign('desc_prod',$desc_prod);
            $tpl->assign('cod_secretaria',$cod_secretaria);
            $tpl->assign('dessecretaria',$dessecretaria);
            $tpl->assign('qtd_pecas',number_format($qtd_pecas,2,',','.'));
            $tpl->assign('peso',number_format($peso,2,',','.'));
            $tpl->assign('preco_unitario',number_format($preco_unitario,2,',','.'));
            $tpl->assign('ent_sai',$ent_sai);
            $tpl->assign('cfop',$cfop);
            $tpl->assign('cfopcon',$cfopcon);
            $tpl->assign('sub',number_format($sub,2,',','.'));

        }
        $tpl->newBlock('totalsaida');
        $tpl->assign('tot_nota_saida',number_format($tot_nota_saida,2,',','.'));
		
		$vetss = $daos->RelNotasDeSaidaNaoConciderarIcms($where3);
        $numss = count($vetss);
        $tot_nota_saidas = 0;
        for ($xx=0; $xx <  $numss; $xx++) { 
            
            $notasais          = $vetss[$xx];

            $numero_item_notas = $notasais->getItemNota();
			$numero_notas      = $notasais->getNumeroNota();
			$entradas          = $notasais->getEntrada();
			$saidas            = $notasais->getSaida();
			$codigo_produtos   = $notasais->getCodigoProduto();
			$desc_prods        = $notasais->getDescProduto();
			$cod_secretarias   = $notasais->getCodSecretaria();
			$dessecretarias    = $notasais->getDescSecretaria();
			$qtd_pecass        = $notasais->getPecas();
			$pesos             = $notasais->getPeso();
			$preco_unitarios   = $notasais->getPrecoUnitario();
			$ent_sais          = $notasais->getEntSai();
			$cfops             = $notasais->getCfop();			

            if($ent_sais == 'S'){
                $subs = $saidas;
            }else{
                $subs = $entradas;
            }
            $tot_nota_saidas = $tot_nota_saidas + $subs;
            $tpl->newBlock('listasaidasicms');

            $tpl->assign('numero_item_notas',$numero_item_notas);
            $tpl->assign('numero_notas',$numero_notas);
            $tpl->assign('codigo_produtos',$codigo_produtos);
            $tpl->assign('desc_prods',$desc_prods);
            $tpl->assign('cod_secretarias',$cod_secretarias);
            $tpl->assign('dessecretarias',$dessecretarias);
            $tpl->assign('qtd_pecass',number_format($qtd_pecass,2,',','.'));
            $tpl->assign('pesos',number_format($pesos,2,',','.'));
            $tpl->assign('preco_unitarios',number_format($preco_unitarios,2,',','.'));
            $tpl->assign('ent_sais',$ent_sais);
            $tpl->assign('cfops',$cfops);            
            $tpl->assign('subs',number_format($subs,2,',','.'));
            $tpl->assign('mot',utf8_encode("CFOP e produto configurados para gerar crédito,<br> porém a nota fiscal não possui débito de ICMS."));            

        }
		
	/**************************************************************/
	$tpl->printToScreen();
		
?>