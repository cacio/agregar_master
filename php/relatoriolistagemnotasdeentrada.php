<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/relatoriolistagemnotasdeentrada.htm');
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

		if(isset($_REQUEST['cfop']) and !empty($_REQUEST['cfop'])){

			$cfop       =  $_REQUEST['cfop'];	
			$condicao[]    = " n.cfop = '".preg_replace('/[^0-9]/', '', $cfop)."' ";	
					
		}
		
		$cnpjemp       = $_SESSION['cnpj'];

		//$condicao[]    = " CONCAT(LPAD(EXTRACT(MONTH FROM n.data_abate), 2,'0'), '/', EXTRACT(YEAR FROM n.data_abate)) = '".$mesano."' ";
		$condicao[]    = " n.cnpj_emp = '".$cnpjemp."' ";
		$condicao[]    = " p.cod_secretaria < 10000 ";
				
		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao = new NotasEn1TxtDAO();
		$vet = $dao->RelNotasEntrada($where);
		$num = count($vet);
		$xnota     = "";
		$xnota2    = "";
		$contador  = 0;
		$totalprod = 0;
        $tot_prod  = 0;
        $tot_nota  = 0;		
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
		
		$export = new ExportacaoDAO();
		$vetexp = $export->ComputaCompetenciaExportacao(date('m/Y',strtotime($dataini)),$_SESSION['cnpj']);
		$numexp = count($vetexp);

		if($numexp > 0){
			$expo		= $vetexp[0];
			$valor_glos = $expo->getValorGlosado();
		}else{
			$valor_glos = 0;
		}
        $tpl->newBlock('totalgeral');
		
        $tpl->assign('tot_prod',number_format($tot_prod,2,',','.'));
        $tpl->assign('tot_nota',number_format($tot_nota,2,',','.'));
		$tpl->assign('valor_glos',number_format($valor_glos,2,',','.'));

	/**************************************************************/
	$tpl->printToScreen();
		
?>