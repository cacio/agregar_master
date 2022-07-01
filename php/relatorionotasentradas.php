<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatorionotasentradas.htm');
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

		$condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM n.data_abate), 2,'0'), '/', EXTRACT(YEAR FROM n.data_emissao)) = '".$mesano."' ";
		$condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " p.cod_secretaria < 10000 ";
		$mesano1       = explode('/', $mesano);
		$ultimo_dia = date("t", mktime(0,0,0,$mesano1[0],'01',$mesano1[1])); 
		$tpl->assign('primeiro_dia','01/'.$mesano);
		$tpl->assign('ultimo_dia',$ultimo_dia.'/'.$mesano);

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao = new NotasEn1TxtDAO();
		$vet = $dao->RelNotasEntrada($where);
		$num = count($vet);

		include_once '../pdf-php/src/Cezpdf.php';
		
		$pdf = new CezPDF('a4');

		$pdf->selectFont('Helvetica');
		$pdf->ezSetMargins(10,10,10,10);
		
		$pdf->ezText("\nLISTAGEM DE NOTAS DE ENTRADAS DE ANIMAIS DE 01/{$mesano} a {$ultimo_dia}/{$mesano} \n",'',['justification'=>'center']);

		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
		$tot_prod  = 0;
        $tot_nota  = 0;
		$tot_nota  = 0;
		$data	   = array();
		$option	   = array();
		$cols	   = [
				'secretaria' => '', 
				'descricao' => '', 
				'cab' => '',
				'pesov' => '',
				'pesoc'=>'',
				'unit'=>''
			];

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
				$dif			  =0;
				

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

						$data[] = array(
							'secretaria' => '', 
							'descricao' => '', 
							'cab' => '',
							'pesov' => 'TOTAL DOS PRODUTOS: '.number_format($totalprod,2,',','.').' ',
							'pesoc'=>'DIFERENÇA: '.number_format($dif,2,',','.').'',
							'unit'=>'',
							'unitFill'=>[0.3, 0.3, 0.3],
							'pesocFill'=>[0.3, 0.3, 0.3],
							'pesovFill'=>[0.3, 0.3, 0.3],
							'cabFill'=>[0.3, 0.3, 0.3],
							'descricaoFill'=>[0.3, 0.3, 0.3],
							'secretariaFill'=>[0.3, 0.3, 0.3],
							'pesovColor'=>[255, 255, 255],
							'pesocColor'=>[255, 255, 255],
								
						);

					}
					$totalprod = 0;
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

					$data[] = array(
						'secretaria' => '<strong>CNPJ/CPF :</strong>'.$cnpj_cpf.' ', 
						'descricao' => '<strong>NOME:</strong> '.$razao.' ', 
						'cab' => '<strong>INSCRIÇÃO:</strong> '.$insc_estadual.' ',
						'pesov' => '<strong>DATA:</strong> '.implode("/", array_reverse(explode("-", $data_emissao))).' ',
						'pesoc'=>'<strong>NOTA:</strong> '.$numero_nota.'',
						'unit'=>'<strong>VL.NF:</strong>'.number_format($valor_total_nota,2,',','.').'',
						'unitFill'=>[255, 255, 255],
						'pesocFill'=>[255, 255, 255],
						'pesovFill'=>[255, 255, 255],
						'cabFill'=>[255, 255, 255],
						'descricaoFill'=>[255, 255, 255],
						'secretariaFill'=>[255, 255, 255]	
					);
	
					$data[] = array(
						'secretaria' => '<strong>COD/SECRETAR.</strong>', 
						'descricao' => '<strong>DESCRIÇÃO</strong>', 
						'cab' => '<strong>CAB.</strong>',
						'pesov' => '<strong>PESO VIVO.</strong>',
						'pesoc'=>'<strong>PESO CARC.</strong>',
						'unit'=>'<strong>PR.UNIT</strong>',
						'unitFill'=>[255, 255, 255],
						'pesocFill'=>[255, 255, 255],
						'pesovFill'=>[255, 255, 255],
						'cabFill'=>[255, 255, 255],
						'descricaoFill'=>[255, 255, 255],
						'secretariaFill'=>[255, 255, 255]
					);

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

				$data[] = array(
					'secretaria' => ''.$cod_secretaria.' ', 
					'descricao' => ''.$descricao.' ', 
					'cab' => ''.$qtd_cabeca.' ',
					'pesov' => ''.number_format($peso_vivo_cabeca,2,',','.').' ',
					'pesoc'=>''.$peso_carcasa.'',
					'unit'=>''.$preco_quilo.''	,
					'unitFill'=>[0.8, 0.8, 0.8],
					'pesocFill'=>[0.8, 0.8, 0.8],
					'pesovFill'=>[0.8, 0.8, 0.8],
					'cabFill'=>[0.8, 0.8, 0.8],
					'descricaoFill'=>[0.8, 0.8, 0.8],
					'secretariaFill'=>[0.8, 0.8, 0.8]
				);

				if(empty($peso_vivo_cabeca) or $peso_vivo_cabeca == '0.00'){					
					$peso = $peso_carcasa;
				}else{					
					$peso = $peso_vivo_cabeca;
				}
				
				$totalprod = $totalprod + ($peso * $preco_quilo);
				$totalnota = $valor_total_nota;
				$tot_prod  = $tot_prod + ($peso * $preco_quilo);

				$contador++;				
		}
		$dif = $totalprod - $totalnota;
        $tpl->newBlock('lista');	
        $tpl->assign('totalprod',number_format(($totalprod - $dif),2,',','.'));
		$tpl->newBlock('total');
		$tpl->assign('vtotalprod',number_format($totalprod,2,',','.'));
		$tpl->assign('dif',number_format($dif,2,',','.'));	
		
        $tpl->newBlock('totalgeral');
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));
        $tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));
		
		$data[] = array(
		'secretaria' => '', 
		'descricao' => '', 
		'cab' => '',
		'pesov' => 'TOTAL DOS PRODUTOS: '.number_format($totalprod,2,',','.').' ',
		'pesoc'=>'DIFERENÇA: '.number_format($dif,2,',','.').'',
		'unit'=>'',
		'unitFill'=>[0.3, 0.3, 0.3],
		'pesocFill'=>[0.3, 0.3, 0.3],
		'pesovFill'=>[0.3, 0.3, 0.3],
		'cabFill'=>[0.3, 0.3, 0.3],
		'descricaoFill'=>[0.3, 0.3, 0.3],
		'secretariaFill'=>[0.3, 0.3, 0.3],
		'pesovColor'=>[255, 255, 255],
		'pesocColor'=>[255, 255, 255],
			
	);
	
	$data[] = array(
		'secretaria' => '<strong>TOTAL GERAL:</strong>', 
		'descricao' => '', 
		'cab' => '',
		'pesov' => '',
		'pesoc'=>'',
		'unit'=>'',
		'unitFill'=>[255, 255, 255],
		'pesocFill'=>[255, 255, 255],
		'pesovFill'=>[255, 255, 255],
		'cabFill'=>[255, 255, 255],
		'descricaoFill'=>[255, 255, 255],
		'secretariaFill'=>[255, 255, 255],	
			
	);
	$data[] = array(
		'secretaria' => '<strong>Nota:</strong>', 
		'descricao' => '', 
		'cab' => '',
		'pesov' => '',
		'pesoc'=>'',
		'unit'=>'<strong>R$ '.number_format($tot_nota,2,',','.').'</strong>',
		'unitFill'=>[255, 255, 255],
		'pesocFill'=>[255, 255, 255],
		'pesovFill'=>[255, 255, 255],
		'cabFill'=>[255, 255, 255],
		'descricaoFill'=>[255, 255, 255],
		'secretariaFill'=>[255, 255, 255],	
			
	);

	$data[] = array(
		'secretaria' => '<strong>Porduto:</strong>', 
		'descricao' => '', 
		'cab' => '',
		'pesov' => '',
		'pesoc'=>'',
		'unit'=>'<strong>R$ '.number_format($tot_prod,2,',','.').'</strong>',
		'unitFill'=>[255, 255, 255],
		'pesocFill'=>[255, 255, 255],
		'pesovFill'=>[255, 255, 255],
		'cabFill'=>[255, 255, 255],
		'descricaoFill'=>[255, 255, 255],
		'secretariaFill'=>[255, 255, 255],	
			
	);
	
	$pdf->ezStartPageNumbers(585, 820, 10, 'right', 'Pag. {PAGENUM} de {TOTALPAGENUM}', 1);
	

	$pdf->ezTable($data, $cols,'',array(
		'showHeadings' => 1, 'shaded' => 2, 'showLines' => 3,
		'cols' => ['pesov' => ['justification' => 'right'], 'pesoc' => ['justification' => 'right','evenColumns'=>1]],
	));
		
	$arquivo ="../arquivos/{$cnpjemp}/relatorio/";
	if(!file_exists($arquivo)){
		mkdir($arquivo,0777, true);
		file_put_contents("{$arquivo}{$cnpjemp}_notasentrada.pdf", $pdf->ezOutput(true));
	}else{
		file_put_contents("{$arquivo}{$cnpjemp}_notasentrada.pdf", $pdf->ezOutput(true));
	}
	/**************************************************************/
	$tpl->printToScreen();
		
?>