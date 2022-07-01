<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatorionotasdesaida.htm');
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
		$condicao[]    = " COALESCE(p.cod_secretaria, 0) <> '99999' ";
		//$condicao[]    = " COALESCE(p.cod_secretaria, 0) > 10000 ";
		
		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao = new NotasSaiTxtDAO();
		$vet = $dao->RelNotasDeSaida($where);
		$num = count($vet);
		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
		$totcred3  = 0;
		$totcred4  = 0;
		$tot_cred3 = 0;
		$tot_cred4 = 0;
        $tot_nota  = 0;
        $tot_prod  = 0;
		for ($i=0; $i < $num; $i++) { 
				
				$notasai          = $vet[$i];

				$cnpj_cpf     	  = $notasai->getCnpjCpf();
				$razao        	  = $notasai->getRazao();
				$insc     	  	  = $notasai->getInscEstadual();
				$data_emissao 	  = $notasai->getDataEmissao();			
				$numero_nota  	  = $notasai->getNumeroNota();
				$valor_icms   	  = $notasai->getValorIcms();
				$valor_icms_subs  = $notasai->getValorIcmsSubs();			
				$cod_secretaria   = $notasai->getCodSecretaria();
				$descricao        = $notasai->getDescSecretaria();
				$peso      	      = $notasai->getPeso();
				$qtd_pecas        = $notasai->getPecas();			
				$preco_unitario   = $notasai->getPrecoUnitario();
				$valor_total_nota = $notasai->getValorTotalNota();
				$pkrel            = $notasai->getPkRelacionamento();
				$codigo_produto   = $notasai->getCodigoProduto();
				$ent_sai		  = $notasai->getEntSai();
				$subtotal		  = $notasai->getSubTotal();

				if($numero_nota != $xnota){
					$xnota = $numero_nota;

					if($contador > 0){
						$dif = $totalprod - $totalnota;
						$tpl->newBlock('lista');	
						$tpl->assign('totalprod',number_format($totalnota,2,',','.'));
						//$tpl->assign('valor_total_nota',$totalnota);
						$tpl->newBlock('total');

						$totalcred3 = $totcred3 * 0.03;
						$totalcred4 = $totcred4 * 0.04; 

						if(empty($totalcred4)){$totalprod4 = 0;}else{$totalprod4 = $totalprod;}
						if(empty($totalcred3)){$totalprod3 = 0;}else{$totalprod3 = $totalprod;}

						$tpl->assign('totalprod3',number_format($totalprod3,2,',','.'));
						$tpl->assign('totalprod4',number_format($totalprod4,2,',','.'));
						$tpl->assign('totalcred3',number_format($totalcred3,2,',','.'));
						$tpl->assign('totalcred4',number_format($totalcred4,2,',','.'));
						$tpl->assign('dif',number_format($dif,2,',','.'));	
					}
					$totalprod = 0;
					$totcred3  = 0;
					$totcred4  = 0;
				}

				if($numero_nota != $xnota2){
					$xnota2 = $numero_nota;
                    $tot_nota = $tot_nota + $valor_total_nota;
					$tpl->newBlock('listar');	
					$tpl->assign('cnpj_cpf',$cnpj_cpf);
					$tpl->assign('razao',$razao);
					$tpl->assign('insc_estadual',$insc);
					$tpl->assign('numero_nota',$numero_nota);
					$tpl->assign('data_emissao',implode("/", array_reverse(explode("-", $data_emissao))));					
					$tpl->assign('valor_icms',number_format($valor_icms,2,',','.'));
					$tpl->assign('valor_icms_subs',number_format($valor_icms_subs,2,',','.'));
				}	

				$tpl->newBlock('detalhe');				
				$tpl->assign('cod_secretaria',$cod_secretaria);
				$tpl->assign('descricao',$descricao);
				$tpl->assign('peso',number_format($peso,2,',','.'));
				$tpl->assign('qtd_pecas',number_format($qtd_pecas,2,',','.'));
				$tpl->assign('preco_unitario',number_format($preco_unitario,2,',','.'));
				$tpl->assign('pkrel',$pkrel);
				$tpl->assign('codigo_produto',$codigo_produto);

			
				$totalprod = $totalprod + $subtotal;
				$tot_prod  = $tot_prod + $subtotal;
				$totalnota = $valor_total_nota;
                

				if($cod_secretaria < 10000){
					$totcred3 = $totcred3 + $subtotal;
					$tot_cred3 = $tot_cred3 + $subtotal;
				}else if($cod_secretaria > 10000){
					$totcred4  = $totcred4 + $subtotal;
					$tot_cred4 = $tot_cred4 + $subtotal;
				}


				$contador++;				
        }
        $dif = $totalprod - $totalnota;
        $tpl->newBlock('lista');	
        $tpl->assign('totalprod',number_format($totalnota,2,',','.'));

		$tpl->newBlock('total');
		$totalcred3 = $totcred3 * 0.03;
		$totalcred4 = $totcred4 * 0.04; 

		if(empty($totalcred4)){$totalprod4 = 0;}else{$totalprod4 = $totalprod;}
		if(empty($totalcred3)){$totalprod3 = 0;}else{$totalprod3 = $totalprod;}

		$tpl->assign('totalprod3',number_format($totalprod3,2,',','.'));
		$tpl->assign('totalprod4',number_format($totalprod4,2,',','.'));
		$tpl->assign('totalcred3',number_format($totalcred3,2,',','.'));
		$tpl->assign('totalcred4',number_format($totalcred4,2,',','.'));
		$tpl->assign('dif',number_format($dif,2,',','.'));	
		
        $tpl->newBlock('totalgeral');
        
        $totalgeralcred3 = $tot_cred3 * 0.03;
        $totalgeralcred4 = $tot_cred4 * 0.04;

		$tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));
        $tpl->assign('totalgeralcred3',number_format($totalgeralcred3,2,',','.'));
        $tpl->assign('totalgeralcred4',number_format($totalgeralcred4,2,',','.'));
	/**************************************************************/
	$tpl->printToScreen();
		
?>