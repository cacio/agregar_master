<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatorionotassaida.htm');
	//$tpl->assignInclude('conteudo','../tpl/relacaoabates.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		//require_once('../inc/inc.permissao.php');
		//require_once('../inc/inc.menu.php');
		$tpl->assign('log',$_SESSION['login']);
			
		$condicao = array();		

		$mesano 	   = $_REQUEST['mesano'];
		$cnpjemp       = $_REQUEST['cnpjemp'];

		$condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM m.data_emissao), 2,'0'), '/', EXTRACT(YEAR FROM m.data_emissao)) = '".$mesano."' ";
		$condicao[]    = " m.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " p.cod_secretaria <> '99999' ";
		//$condicao[]    = " p.cod_secretaria <= 10000 ";
		$mesano1       = explode('/', $mesano);
		$ultimo_dia = date("t", mktime(0,0,0,$mesano1[0],'01',$mesano1[1])); 
		$tpl->assign('primeiro_dia','01/'.$mesano);
		$tpl->assign('ultimo_dia',$ultimo_dia.'/'.$mesano);

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		## Dados da empresa
		$daoemp = new EmpresasTxt2DAO();
		$vetemp = $daoemp->ListViewCli($_SESSION['cnpj']);

		$dao = new NotasSaiTxtDAO();
		$vet = $dao->RelNotasDeSaida($where);
		$num = count($vet);

		include_once '../pdf-php/src/Cezpdf.php';
		
		$pdf = new CezPDF('a4');

		$pdf->selectFont('Helvetica');
		$pdf->ezSetMargins(10,10,10,10);
		
		$pdf->ezText("\n {$vetemp[0]['razao_social']} - {$daoemp->formatCnpjCpf($_SESSION['cnpj'])} \n",'',['justification'=>'center']);
		$pdf->ezText("\nLISTAGEM DE NOTAS DE SAIDA DE 01/{$mesano} a {$ultimo_dia}/{$mesano} \n",'',['justification'=>'center']);

		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
		$totcred3  = 0;
		$totcred4  = 0;
        $tot_nota  = 0;
        $tot_prod  = 0;
		$data	   = array();
		$option	   = array();
		$cols	   = [
				'secretaria' => '', 
				'descricao' => '', 
				'pecas' => '',
				'peso' => '',				
				'unit'=>''
			];

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

						$data[] = array(
							'secretaria' => '3,00% TOTAL DOS PRODUTOS COM CREDITO: '.number_format($totalprod3,2,',','.').'', 
							'descricao' => 'CREDITO: '.number_format($totalcred3,2,',','.').' ',//'4,00% TOTAL DOS PRODUTOS COM CREDITO: '.number_format($totalprod4,2,',','.').' CREDITO: '.number_format($totalcred4,2,',','.').' ', 
							'pecas' => '4,00% TOTAL DOS PRODUTOS COM CREDITO: '.number_format($totalprod4,2,',','.').'',
							'peso' => 'CREDITO: '.number_format($totalcred4,2,',','.').'',				
							'unit'=>'',
							'unitFill'=>[0.3, 0.3, 0.3],
							'pecasFill'=>[0.3, 0.3, 0.3],
							'pesoFill'=>[0.3, 0.3, 0.3],							
							'descricaoFill'=>[0.3, 0.3, 0.3],
							'secretariaFill'=>[0.3, 0.3, 0.3],
							'secretariaColor'=>[255, 255, 255],
							'descricaoColor'=>[255, 255, 255],
							'pecasColor'=>[255, 255, 255],
							'pesoColor'=>[255, 255, 255],	
						);

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

					$data[] = array(
						'secretaria' => '<strong>CNPJ/CPF :</strong>'.$cnpj_cpf.' ', 
						'descricao' => '<strong>NOME:</strong> '.$razao.' ', 
						'pecas' => '<strong>INSCRIÇÃO:</strong> '.$insc.' ',
						'peso' => '<strong>DATA:</strong> '.implode("/", array_reverse(explode("-", $data_emissao))).' ',						
						'unit'=>'<strong>NOTA:</strong> '.$numero_nota.' <strong>VL.NF:</strong>'.number_format($valor_total_nota,2,',','.').'<strong>ICMS:</strong> '.number_format($valor_icms,2,',','.').' <strong>SUBTS:</strong> '.number_format($valor_icms_subs,2,',','.').' ',
						'unitFill'=>[255, 255, 255],						
						'pesoFill'=>[255, 255, 255],
						'pecasFill'=>[255, 255, 255],
						'descricaoFill'=>[255, 255, 255],
						'secretariaFill'=>[255, 255, 255]
					);

					$data[] = array(
						'secretaria' => '<strong>COD/SECRETAR.</strong>', 
						'descricao' => '<strong>DESCRIÇÃO</strong>', 
						'pecas' => '<strong>PECAS.</strong>',
						'peso' => '<strong>PESO</strong>',						
						'unit'=>'<strong>PR.UNIT</strong>',
						'unitFill'=>[255, 255, 255],						
						'pesoFill'=>[255, 255, 255],
						'pecasFill'=>[255, 255, 255],
						'descricaoFill'=>[255, 255, 255],
						'secretariaFill'=>[255, 255, 255]
					);
					
				}	

				$tpl->newBlock('detalhe');				
				$tpl->assign('cod_secretaria',$cod_secretaria);
				$tpl->assign('descricao',$descricao);
				$tpl->assign('peso',number_format($peso,2,',','.'));
				$tpl->assign('qtd_pecas',number_format($qtd_pecas,2,',','.'));
				$tpl->assign('preco_unitario',number_format($preco_unitario,2,',','.'));
				$tpl->assign('pkrel',$pkrel);
				$tpl->assign('codigo_produto',$codigo_produto);

				$data[] = array(
					'secretaria' => ''.$cod_secretaria.' ', 
					'descricao' => ''.$descricao.' ', 
					'pecas' => ''.number_format($qtd_pecas,2,',','.').' ',
					'peso' => ''.number_format($peso,2,',','.').' ',					
					'unit'=>''.number_format($preco_unitario,2,',','.').''	,
					'unitFill'=>[0.8, 0.8, 0.8],					
					'pesoFill'=>[0.8, 0.8, 0.8],
					'pecasFill'=>[0.8, 0.8, 0.8],
					'descricaoFill'=>[0.8, 0.8, 0.8],
					'secretariaFill'=>[0.8, 0.8, 0.8]
				);

				$totalprod = $totalprod + ($peso * $preco_unitario);
				$totalnota = $valor_total_nota;
                $tot_prod  = $tot_prod + ($peso * $preco_unitario);

				if($cod_secretaria < 10000){
					$totcred3 = $totcred3 + ($peso * $preco_unitario);
				}else if($cod_secretaria > 10000){
					$totcred4 = $totcred4 + ($peso * $preco_unitario);
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
        
        $totalgeralcred3 = $tot_prod * 0.03;
        $totalgeralcred4 = $tot_prod * 0.04;

		$tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));
        $tpl->assign('totalgeralcred3',number_format($totalgeralcred3,2,',','.'));
        $tpl->assign('totalgeralcred4',number_format($totalgeralcred4,2,',','.'));	
		$data[] = array(
			'secretaria' => '3,00% TOTAL DOS PRODUTOS COM CREDITO: '.number_format($totalprod3,2,',','.').'', 
			'descricao' => 'CREDITO: '.number_format($totalcred3,2,',','.').' ',
			'pecas' => '4,00% TOTAL DOS PRODUTOS COM CREDITO: '.number_format($totalprod4,2,',','.').'',
			'peso' => 'CREDITO: '.number_format($totalcred4,2,',','.').'',				
			'unit'=>'',
			'unitFill'=>[0.3, 0.3, 0.3],
			'pecasFill'=>[0.3, 0.3, 0.3],
			'pesoFill'=>[0.3, 0.3, 0.3],							
			'descricaoFill'=>[0.3, 0.3, 0.3],
			'secretariaFill'=>[0.3, 0.3, 0.3],
			'secretariaColor'=>[255, 255, 255],
			'descricaoColor'=>[255, 255, 255],
			'pecasColor'=>[255, 255, 255],
			'pesoColor'=>[255, 255, 255],	
		);
		
		$data[] = array(
			'secretaria' => '<strong>TOTAL GERAL:</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',			
			'unit'=>'',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],	
				
		);
		$data[] = array(
			'secretaria' => '<strong>Nota:</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',	
			'unit'=>'<strong>R$ '.number_format($tot_nota,2,',','.').'</strong>',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],	
				
		);
	
		$data[] = array(
			'secretaria' => '<strong>Porduto:</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',	
			'unit'=>'<strong>R$ '.number_format($tot_prod,2,',','.').'</strong>',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],				
		);	

		$data[] = array(
			'secretaria' => '<strong>CRÉDITOS:</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',			
			'unit'=>'',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],	
				
		);
		$data[] = array(
			'secretaria' => '<strong>Total Produtos (3%)</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',	
			'unit'=>'<strong>R$ '.number_format($totalgeralcred3,2,',','.').'</strong>',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],	
				
		);
	
		$data[] = array(
			'secretaria' => '<strong>Total Produtos (4%)</strong>', 
			'descricao' => '', 
			'pecas' => '',
			'peso' => '',	
			'unit'=>'<strong>R$ '.number_format($totalgeralcred4,2,',','.').'</strong>',
			'unitFill'=>[255, 255, 255],			
			'pesoFill'=>[255, 255, 255],
			'pecasFill'=>[255, 255, 255],
			'descricaoFill'=>[255, 255, 255],
			'secretariaFill'=>[255, 255, 255],				
		);	

	$pdf->ezStartPageNumbers(585, 820, 10, 'right', 'Pag. {PAGENUM} de {TOTALPAGENUM}', 1);
	

	$pdf->ezTable($data, $cols,'',array(
		'showHeadings' => 1, 'shaded' => 5, 'showLines' => 3,
		'cols' => ['secretaria' => ['justification' => 'left'],'peso' => ['justification' => 'right'], 'pecas' => ['justification' => 'right','evenColumns'=>5],'unit' => ['justification' => 'right']],
	));
		
	$arquivo ="../arquivos/{$cnpjemp}/relatorio/";
	if(!file_exists($arquivo)){
		mkdir($arquivo,0777, true);
		file_put_contents("{$arquivo}{$cnpjemp}_notassaida.pdf", $pdf->ezOutput(true));
	}else{
		file_put_contents("{$arquivo}{$cnpjemp}_notassaida.pdf", $pdf->ezOutput(true));
	}

	/**************************************************************/
	$tpl->printToScreen();
		
?>