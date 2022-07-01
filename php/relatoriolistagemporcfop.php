<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriolistagemporcfop.htm');
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

        if(isset($_REQUEST['dataini']) and !empty($_REQUEST['dataini'])){
            $dataini       =  implode("-", array_reverse(explode("/",$_REQUEST['dataini'])));	
            if($_REQUEST['radio'] == 1){
                
                $condicao[]     = " n.data_abate between '".$dataini."' ";	
                $condicao2[]    = " n.data_abate between '".$dataini."' ";	
				$condicao3[]    = " m.data_emissao between '".$dataini."' ";
            }else{
                $condicao[]     = " n.data_emissao between '".$dataini."' ";	
                $condicao2[]    = " n.data_emissao between '".$dataini."' ";	
				$condicao3[]    = " m.data_emissao between '".$dataini."' ";
            }
			
					
		}
		
		if(isset($_REQUEST['datafin']) and !empty($_REQUEST['datafin'])){

			$datafin       =  implode("-", array_reverse(explode("/",$_REQUEST['datafin'])));	
			$condicao[]    = " '".$datafin."' ";	
            $condicao2[]   = " '".$datafin."' ";	
			$condicao3[]   = " '".$datafin."' ";
					
		}


		$cnpjemp       = $_SESSION['cnpj'];

		//$condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM n.data_abate), 2,'0'), '/', EXTRACT(YEAR FROM n.data_abate)) = '".$mesano."' ";
		$condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " p.cod_secretaria < 10000 ";
        $condicao2[]   = " n.cnpj_emp = '".$cnpjemp."' ";
				
		$condicao3[]    = " m.cnpj_emp = '".$cnpjemp."' ";
		$condicao3[]    = " p.cod_secretaria <> '99999' ";
		
		
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

		$dao = new NotasEn1TxtDAO();
		$vet = $dao->RelNotasEntrada($where);
		$num = count($vet);
		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
        $totalprod2= 0;
        $tot_prod  = 0;
        $tot_nota  = 0;
        $xcfop     = 0;
        $xcfop2    = 0;
        $contador2 = 0;
		for ($i=0; $i < $num; $i++) { 
				
				$notasen1         = $vet[$i];

				$id 		      = $notasen1->getCodigo();
				$numero_nota      = $notasen1->getNumeroNota();
				$data_emissao     = $notasen1->getDataEmissao();
				$cnpj_cpf         = $notasen1->getCnpjCpf();
				$insc_estadual    = $notasen1->getInsEstadual();
				$razao 			  = $notasen1->getRazaoSocialEmpresa();
				$cod_secretaria   = $notasen1->getCodSecretaria();
				$descricao        = $notasen1->getDescSecretaria();			
				$qtd_cabeca       = $notasen1->getQtdCabeca();
				$peso_vivo_cabeca = $notasen1->getPesoVivoCabeca();
				$peso_carcasa     = $notasen1->getPesoCarcasa();
				$preco_quilo      = $notasen1->getPrecoQuilo();									
				$tipo_r_v         = $notasen1->getTipo_R_V();
				$cfop             = $notasen1->getCfop();			
				$cnpj_emp         = $notasen1->getCnpjEmp();
				$valor_total_nota = $notasen1->getValorTotalNota();
				$codigo_produto   = $notasen1->getCodigoProduto();
				$pkrel            = $notasen1->getPkRelacionamento();
                $dif			  = 0;
               // $tot_nota         = 0;

                if($cfop != $xcfop2){
					$xcfop2 = $cfop;

					if($contador2 > 0){						
						$tpl->newBlock('totalcfop');
						$tpl->assign('vtotalprod2',number_format($totalprod2,2,',','.'));						
					}
					$totalprod2 = 0;
				}

				if($numero_nota != $xnota){
					$xnota = $numero_nota;

					if($contador > 0){
						$dif = $totalprod - $totalnota;
						$tpl->newBlock('lista');	
						$tpl->assign('totalprod',number_format(($totalprod - $dif),2,',','.'));
						//$tpl->assign('valor_total_nota',$totalnota);
						$tpl->newBlock('total');
						$tpl->assign('vtotalprod',number_format($totalprod,2,',','.'));
						$tpl->assign('dif',number_format($dif,2,',','.'));	
					}
					$totalprod = 0;
				}

                if($cfop != $xcfop){
                    $xcfop = $cfop;
                    $tpl->newBlock('listarcfop');	
                    $tpl->assign('cfop',$cfop);
                }

				if($numero_nota != $xnota2){
					$xnota2 = $numero_nota;
                    $tot_nota = $tot_nota + $valor_total_nota;
					$tpl->newBlock('listar');	
					$tpl->assign('cnpj_cpf',$cnpj_cpf);
					$tpl->assign('razao',$razao);
					$tpl->assign('insc_estadual',$insc_estadual);
					$tpl->assign('numero_nota',$numero_nota);
					$tpl->assign('data_emissao',implode("/", array_reverse(explode("-", $data_emissao))));					

				}	

				$tpl->newBlock('detalhe');				
				$tpl->assign('cod_secretaria',$cod_secretaria);
				$tpl->assign('descricao',$descricao);
				$tpl->assign('qtd_cabeca',$qtd_cabeca);
				$tpl->assign('peso_vivo_cabeca',number_format($peso_vivo_cabeca,2,',','.'));
				$tpl->assign('peso_carcasa',$peso_carcasa);
				$tpl->assign('preco_quilo',$preco_quilo);
				$tpl->assign('codigo_produto',$codigo_produto);
				$tpl->assign('numnota',$numero_nota);
				$tpl->assign('pkrel',$pkrel);
				$tpl->assign('id',$id);

				if(empty($peso_vivo_cabeca) or $peso_vivo_cabeca == '0.00'){					
					$peso = $peso_carcasa;
				}else{					
					$peso = $peso_vivo_cabeca;
				}
				
				$totalprod = $totalprod + ($peso * $preco_quilo);
                $totalprod2 = $totalprod2 + ($peso * $preco_quilo);
				$totalnota = $valor_total_nota;
                $tot_prod  = $tot_prod + ($peso * $preco_quilo);

				$contador++;	
                $contador2++;			
        }

        $dif = $totalprod - $totalnota;
        
        $tpl->newBlock('totalcfop');
		$tpl->assign('vtotalprod2',number_format($totalprod2,2,',','.'));

        $tpl->newBlock('lista');	
        $tpl->assign('totalprod',number_format(($totalprod - $dif),2,',','.'));
		$tpl->newBlock('total');
		$tpl->assign('vtotalprod',number_format($totalprod,2,',','.'));
		$tpl->assign('dif',number_format($dif,2,',','.'));	
		
        $tpl->newBlock('totalgeral');
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));
        $tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));

        $vet2 =  $dao->RelSumoCfop($where2);
        $num2 = count($vet2);
        $tot_valor2  = 0;
        for ($i=0; $i < $num2; $i++) { 
            $notasen          = $vet2[$i];

            $cfop             = $notasen->getCfop();			
			$valor_total_nota = $notasen->getValorTotalNota();
			$tot_valor2       = $tot_valor2 + $valor_total_nota;
            $tpl->newBlock('listaresumo');	
            $tpl->assign('cfop',$cfop);
            $tpl->assign('valor_total_nota',number_format($valor_total_nota,2,',','.'));
        }
		$tpl->newBlock('listaresumototal2');
		$tpl->assign('tot_valor2',number_format($tot_valor2,2,',','.'));

		$daos = new NotasSaiTxtDAO();
		$vets = $daos->RelNotasDeSaidaCfop($where3);
		$nums = count($vets);

		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
		$totcred3  = 0;
		$totcred4  = 0;
        $tot_nota  = 0;
        $tot_prod  = 0;
		$xcfop     = "";
		$xcfop2    = 0;
        $contador2 = 0;
		$totalprod2= 0;
		echo $nums;
		for ($i=0; $i < $nums; $i++) { 
				
				$notasai          = $vets[$i];

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
				$cfop			  = $notasai->getCfop();


				if($cfop != $xcfop2){
					$xcfop2 = $cfop;

					if($contador2 > 0){						
						$tpl->newBlock('totalcfop2');
						$tpl->assign('vtotalprod22',number_format($totalprod2,2,',','.'));						
					}
					$totalprod2 = 0;
				}

				if($numero_nota != $xnota){
					$xnota = $numero_nota;

					if($contador > 0){
						$dif = $totalprod - $totalnota;
						$tpl->newBlock('lista2');	
						$tpl->assign('totalprod',number_format($totalnota,2,',','.'));
						//$tpl->assign('valor_total_nota',$totalnota);
						$tpl->newBlock('total');

					}
					$totalprod = 0;
					$totcred3  = 0;
					$totcred4  = 0;
				}

				if($cfop != $xcfop){
                    $xcfop = $cfop;
                    $tpl->newBlock('listarcfop2');	
                    $tpl->assign('cfop2',$cfop);
                }

				if($numero_nota != $xnota2){
					$xnota2 = $numero_nota;
                    $tot_nota = $tot_nota + $valor_total_nota;
					$tpl->newBlock('listar2');	
					$tpl->assign('cnpj_cpf',$cnpj_cpf);
					$tpl->assign('razao',$razao);
					$tpl->assign('insc_estadual',$insc);
					$tpl->assign('numero_nota',$numero_nota);
					$tpl->assign('data_emissao',implode("/", array_reverse(explode("-", $data_emissao))));					
					$tpl->assign('valor_icms',number_format($valor_icms,2,',','.'));
					$tpl->assign('valor_icms_subs',number_format($valor_icms_subs,2,',','.'));
				}	

				$tpl->newBlock('detalhe2');				
				$tpl->assign('cod_secretaria',$cod_secretaria);
				$tpl->assign('descricao',$descricao);
				$tpl->assign('peso',number_format($peso,2,',','.'));
				$tpl->assign('qtd_pecas',number_format($qtd_pecas,2,',','.'));
				$tpl->assign('preco_unitario',number_format($preco_unitario,2,',','.'));
				$tpl->assign('pkrel',$pkrel);
				$tpl->assign('codigo_produto',$codigo_produto);

				$totalprod = $totalprod + ($peso * $preco_unitario);
				$totalprod2 = $totalprod2 + ($peso * $preco_unitario);
				$totalnota = $valor_total_nota;
                $tot_prod  = $tot_prod + ($peso * $preco_unitario);
			

				$contador++;
				$contador2++;				
        }
        $dif = $totalprod - $totalnota;

		$tpl->newBlock('totalcfop2');
		$tpl->assign('vtotalprod22',number_format($totalprod2,2,',','.'));

        $tpl->newBlock('lista2');	
        $tpl->assign('totalprod',number_format($totalnota,2,',','.'));

		$tpl->newBlock('totalgeral2');
		$tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));

		$vetso = $daos->RelNotasDeSaidaSumaCfop($where3);
		$numso = count($vetso);
		$tot_valor  = 0;
		for ($x=0; $x < $numso; $x++) { 
			$notasais	= $vetso[$x];

			$valor = $notasais->getValorTotalNota();
			$cfop  = $notasais->getCfop();
			$tot_valor = $tot_valor + $valor;
			$tpl->newBlock('listaresumo2');	
            $tpl->assign('cfop2',$cfop);
            $tpl->assign('valor',number_format($valor,2,',','.'));

		}
		$tpl->newBlock('listaresumototal');
		$tpl->assign('tot_valor',number_format($tot_valor,2,',','.'));
	/**************************************************************/
	$tpl->printToScreen();
		
?>