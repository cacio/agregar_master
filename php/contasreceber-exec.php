<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){
			
			case 'inserir':

				
				if(empty($_REQUEST['ndoc'])){
					$numero = '0';
				}else{
					$numero = $_REQUEST['ndoc'];
				}
				
				if(empty($_REQUEST['dtemis'])){
					
					$dtemis  = '';
				}else{
					$dtemis  = implode("-", array_reverse(explode("-", "".$_REQUEST['dtemis']."")));
				}
				if(empty($_REQUEST['dtvenc'])){
					$dtvenc = '';
				}else{
					$dtvenc = implode("-", array_reverse(explode("-", "".$_REQUEST['dtvenc']."")));
				}
				if(empty($_REQUEST['valor'])){
					$valor = 0.00;
				}else{
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));
				}
				
				if(empty($_REQUEST['hist'])){
					$hist   = '';	
				}else{
					$hist   = $_REQUEST['hist'];
				}
				if(empty($_REQUEST['codcli'])){
					$codcli = '';
				}else{
					$codcli = $_REQUEST['codcli'];
				}
				if(empty($_REQUEST['banco'])){
					$banco = '';
				}else{
					$banco = $_REQUEST['banco'];
				}
				
				if(empty($_REQUEST['formpagto'])){
					$formpagto = '1';
				}else{
					$formpagto = $_REQUEST['formpagto'];
				}				
				
				$duplic = new DuplicReceber();
						
				$duplic->setCodigoCliente($codcli);
				$duplic->setEmissao($dtemis);
				$duplic->setNumero($numero);
				$duplic->setVencimento($dtvenc);
				$duplic->setValorDoc($valor);		
				$duplic->setHistorico($hist);
				$duplic->setDataPag('');
				$duplic->setSaldo(0);
				$duplic->setValorPago(0);
				$duplic->setTipo('');
				$duplic->setBanco($banco);
				$duplic->setNome('');
				$duplic->setFormPagto('NULL');
				
				$daod = new DuplicReceberDAO(); 
				$daod->inserir($duplic);
									
				echo "Inserido com Sucesso!";
				
				header('Location:contasreceber.php');
				
			break;
			case 'alterar':

								
				if(empty($_REQUEST['cod'])){
					
					$id  = '0';
				}else{
					$id  = $_REQUEST['cod'];
				}
							
				if(empty($_REQUEST['dtemis'])){
					
					$dtemis  = '';
				}else{
					$dtemis  = implode("-", array_reverse(explode("-", "".$_REQUEST['dtemis']."")));
				}
				if(empty($_REQUEST['dtpag'])){
					$dtpag = '';
				}else{
					$dtpag = implode("-", array_reverse(explode("-", "".$_REQUEST['dtpag']."")));
				}
				
				if(empty($_REQUEST['dtvenc'])){
					$dtvenc = '';
				}else{
					$dtvenc = implode("-", array_reverse(explode("-", "".$_REQUEST['dtvenc']."")));
				}
				
				if(empty($_REQUEST['valor'])){
					$valor = 0.00;
				}else{
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));
				}
				
				if(empty($_REQUEST['hist'])){
					$hist   = '';	
				}else{
					$hist   = $_REQUEST['hist'];
				}
				if(empty($_REQUEST['codcli'])){
					$codcli = '';
				}else{
					$codcli = $_REQUEST['codcli'];
				}
				if(empty($_REQUEST['banco'])){
					$banco = '';
				}else{
					$banco = $_REQUEST['banco'];
				}
				
				if(empty($_REQUEST['formpagto'])){
					$formpagto = '1';
				}else{
					$formpagto = $_REQUEST['formpagto'];
				}


				if(!empty($_REQUEST['dtpag'])){

					$formceb = !empty($_REQUEST['formceb']) ? $_REQUEST['formceb'] : "";					

					if(!empty($_REQUEST['vldocrec'])){
						if(strlen($_REQUEST['vldocrec']) > 2 && strlen($_REQUEST['vldocrec']) <= 6){						
							$vldocrec   = str_replace(',', '.', $_REQUEST['vldocrec']); 
						}else{
							$vldocrec   = str_replace(',', '.', str_replace('.', '',$_REQUEST['vldocrec'])); 
						}
					}else{				
						$vldocrec = 0.00;
					}
											
					$data = Array ( "numero" => $id,
									"tppagto" => "{$formceb}",
									"valor" => $vldocrec);

					$daos = new RecebimentoDAO();				
					$daos->Gravar($data);
				}

				
				$duplic = new DuplicReceber();
	
				$duplic->setCodigo($id);
				$duplic->setCodigoCliente($codcli);
				$duplic->setEmissao($dtemis);
				$duplic->setVencimento($dtvenc);
				$duplic->setValorDoc($valor);
				$duplic->setHistorico($hist);
				$duplic->setDataPag($dtpag);
				//$duplic->setSaldo($saldo);
				//$duplic->setValorPago($valorpago);
				//$duplic->setTipo($tipo);
				$duplic->setBanco($banco);
				//$duplic->setNomeCliente($nomecli);	
				$duplic->setFormPagto($formpagto);			
							
				$dao =  new DuplicReceberDAO();			
				$dao->alterar($duplic);
									
				

				echo "alterado com Sucesso!";
				
				header('Location:atualizar-receber.php?id='.$id.'');
				
			break;
			case 'excluirreb':

				$id   = $_REQUEST['id'];
				$codr = $_REQUEST['codr'];
				
				$dao  = new RecebimentoDAO();

				$dao->Excluir($id);

				$vet = $dao->ListaRecebimentoUm($codr);	
				
				if(count($vet) == 0){

					$conr = new DuplicReceber();

					$conr->setCodigo($codr);								
					$conr->setDataPag('NULL');				

					$daor  = new DuplicReceberDAO();
					$daor->updatedatapag($conr);

				}

			break;
			case 'delete':
								
				if(empty($_REQUEST['id'])){
					$id = '0';
				}else{
					$id = $_REQUEST['id'];
				}
				$duplic = new DuplicReceber();
				$duplic->setCodigo($id);
				
				$dao =  new DuplicReceberDAO();
				$dao->deletar($duplic);
				
			break;
			case 'pagar':
				require_once('geral_config.php');
				
				setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
						
				if(empty($_REQUEST['ids'])){
					$id = array();
				}else{
					$id = explode(',',$_REQUEST['ids']);
				}
				if(empty($_REQUEST['dtpag'])){
					$dtpag = '';
				}else{
					$dtpag = implode("-", array_reverse(explode("-", "".$_REQUEST['dtpag']."")));	
				}
				
				if(empty($_REQUEST['formpagto'])){
					$formpagto = '1';
				}else{
					$formpagto = $_REQUEST['formpagto'];
				}
				$arr = array();
				array_push($arr,$id);
				

				$pagto  = $_REQUEST['pagto'];
				$arrapgto = array();
				//array_push($arrapgto,$pagto);
				//print_r($_REQUEST);
				//die();
				$codc = "";
				$result  = array();
				$result2 = array();
				$str = "";
				$total = 0;
				
				
				$n_colunas = 28;
				$txt_cabecalho = array();
				$txt_itens = array();
				$txt_valor_total = '';
				$txt_rodape = array();
				$i = 0;
				$x = 0;
				$tot_itens = 0;
				foreach($arr[0] as $key=>$value){
				
					//print_r();
					$arraydata = array_values(array_filter($pagto[$value]));
					
					for ($y=0; $y < count($arraydata); $y++) { 
						
						$data = Array ("numero" => $value,
										"tppagto" => "{$arraydata[$y]['idpagto']}",
										"valor" => str_replace(',', '.', str_replace('.', '', $arraydata[$y]['vlpagto'])));

						$daos = new RecebimentoDAO();				
						$daos->Gravar($data);
					}


					$duplic = new DuplicReceber();
					
					$duplic->setCodigo($value);
					$duplic->setDataPag($dtpag);
					$duplic->setFormPagto('');
					
					$dao =  new DuplicReceberDAO();			
					$dao->alterardatapagamento($duplic);
					
					$vetc =  $dao->ListaDuplicRecebePegaCilente($value);
					$nunc = count($vetc);
															
					if($nunc > 0){
						
						$rc = $vetc[0];
							
						$codcli  = $rc->getCodigoCliente();		
						$nomecli = $rc->getNomeCliente();	
						
						if($codcli != $codc){
						
							$codc = $codcli;
							
							
							$txt_cabecalho[] = '----- RECIBO DE MENSALIDADE -----';  							
							$txt_cabecalho[] = ' ';
							$txt_cabecalho[] = 'Data: '.date('d/m/Y').'';
							$txt_cabecalho[] = 'Nome Cliente: '.$nomecli.'';																					
							
							//$str .= "De: ".$nomecli."\r\n";
						}
						
					}
					
					$vet = $dao->ListaDuplicReceberUm($value);
					$dup = $vet[0];
					$idcli      = $dup->getCodigoCliente();	
					
					$txt_itens[] = array('#', 'Mes', 'Valor');		
					
					
					if($codc == $idcli){
					
						$emissao    = $dup->getEmissao();
						$numero     = $dup->getNumero();
						$vencimento = $dup->getVencimento();
						$valordoc   = $dup->getValorDoc();
						$hist       = $dup->getHistorico();
						$datapag    = $dup->getDataPag();
						$banco      = $dup->getBanco();
						$mes = explode("-",$vencimento);
						
						$total = $total + $valordoc;
						
						//$str .= "Mês :".$mes[1]." - ".number_format($valordoc,2,',','.')."\r\n";
																		
						$txt_itens[] = array($i++, ''.$mes[1].'/'.$mes[0].'', ''.number_format($valordoc,2,',','.').'');
						$tot_itens   = $tot_itens + $valordoc;
						
					}
					
						
				}
				
				$daorc =  new DuplicReceberDAO();
				
				$aux_valor_total = 'Total R$: '.number_format($tot_itens,2,',','.').' ('.$daorc->valorPorExtenso(number_format($tot_itens,2,',','.'),true,false).')';
				
				 $total_espacos = $n_colunas - strlen($aux_valor_total);
        
				$espacos = '';
				
				for($i = 0; $i < $total_espacos; $i++){
					$espacos .= '';
				}
				
				$txt_valor_total = $espacos.$aux_valor_total;               				
				$txt_rodape[] = '__________________________________';
				$txt_rodape[] = ''.$empresa.' ';
				$txt_rodape[] = 'Gravatai RS '.utf8_encode(strftime('%d de %B de %Y', strtotime(date('d.m.Y')))).'';
				$txt_rodape[] = ' ';
				$txt_rodape[] = ''.utf8_decode($msgmensalidade).'';
				$txt_rodape[] = ' ';
				//$cabecalho = array_map("centraliza", $txt_cabecalho);
			    $cabecalho = $txt_cabecalho;
									
				 foreach ($txt_itens as $item) {
            
					/*
				 * Cod. => máximo de 5 colunas
				 * Produto => máximo de 11 colunas
				 * Env. => máximo de 6 colunas
				 * Qtd => máximo de 4 colunas
				 * V. UN => máximo de 7 colunas
				 * Total => máximo de 7 colunas
				 *
				 * $itens[] = 'Cod. Produto      Env. Qtd  V. UN  Total'
				 */
					
					$itens[] = $daorc->addEspacos($item[0], 7, 'F')
							 . $daorc->addEspacos($item[1], 10, 'F')
							 . $daorc->addEspacos($item[2], 15, 'F')							
						;
					
				}
				
			/* concatena o cabelhaço, os itens, o sub-total e rodapé
			 * adicionando uma quebra de linha "\r\n" ao final de cada
			 * item dos arrays $cabecalho, $itens, $txt_rodape
			 */
				$txt = implode("\r\n", $cabecalho)
					. "\r\n"
					. implode("\r\n", $itens)
					. "\r\n\r\n"
					. $txt_valor_total // Sub-total
					. "\r\n\r\n"
					. implode("\r\n", $txt_rodape);
				
				// caminho e nome onde o TXT será criado no servidor
				$file = 'nome_arquivo.txt';
		
				// cria o arquivo
				$_file  = fopen($file,"w");
				fwrite($_file,$txt);
				fclose($_file);
		
				header("Pragma: public"); 
				// Força o header para salvar o arquivo
				header("Content-type: application/save");
				header("X-Download-Options: noopen "); // For IE8
				header("X-Content-Type-Options: nosniff"); // For IE8
				// Pré define o nome do arquivo
				header("Content-Disposition: attachment; filename=nome_arquivo.txt"); 
				header("Expires: 0"); 
				header("Pragma: no-cache");
		
				// Lê o arquivo para download
				readfile($file);

			
				
				/*if($printer = printer_open("\\\\187.58.220.117\\MP-4200 TH")){

					printer_set_option($printer, PRINTER_MODE, "raw");
					$font = printer_create_font("Arial", 23, 13, PRINTER_FW_ULTRABOLD, false, false, false, 900);
					printer_select_font($printer, $font);
					
					$strs = file_get_contents("nome_arquivo.txt");
					
					printer_write($printer,$strs);
					printer_close($printer);
				}*/
				
				echo "Pagamentos efetuados com sucesso!";
				
			break;
			
			case 'contribuicao':
				require_once('geral_config.php');
				
				setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
				
				$cont = file_get_contents("numero.txt");
				$posicao2 = $cont;
			
				$numero = '#'.str_pad($posicao2, 3, "0", STR_PAD_LEFT)."/".date('mY'); 
				if(empty($_REQUEST['valor'])){
					$valor = 0.00;
				}else{
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));
				}
				
				
				if(empty($_REQUEST['codcli'])){
					$codcli = 0;
					$nome	= $_REQUEST['cliente'];
				}else{
					$codcli = $_REQUEST['codcli'];
					$nome	= $_REQUEST['cliente'];
				}
				
				if(empty($_REQUEST['formpagto'])){
					$formpagto = '1';
				}else{
					$formpagto = $_REQUEST['formpagto'];
				}
				
				$duplic = new DuplicReceber();
						
				$duplic->setCodigoCliente($codcli);
				$duplic->setEmissao(date('Y-m-d'));
				$duplic->setNumero($numero);
				$duplic->setVencimento(date('Y-m-d'));
				$duplic->setValorDoc($valor);		
				$duplic->setHistorico(utf8_encode('Contribuição no dia '.date('d-m-Y').''));
				$duplic->setDataPag(date('Y-m-d'));
				$duplic->setSaldo(0);
				$duplic->setValorPago(0);
				$duplic->setTipo('C');
				$duplic->setBanco('');
				$duplic->setNome($nome);
				$duplic->setFormPagto($formpagto);
				
				$daod = new DuplicReceberDAO(); 

				$vtprox = $daod->proximoid();
				$prox   = $vtprox[0];
				$idprox = $prox->getProximoId();

				$daod->inserir($duplic);

				$data = Array ("numero" => $idprox,
										"tppagto" => "{$formpagto}",
										"valor" => $valor);

				$daos = new RecebimentoDAO();				
				$daos->Gravar($data);

				$posicao2 = $_REQUEST['nseq'] + 1;
				
				$cont = file_get_contents("numero.txt");
				$cont = $cont+1;
				file_put_contents("numero.txt",$cont);
				
				$result = array();
				
				/*$result[] = array(
					'posicao'=>''.$cont.'',	
					'msg'=>'contribuido com sucesso :D',
				);*/
				
				
				
				$n_colunas = 28;
				$txt_cabecalho = array();
				$txt_itens = array();
				$txt_valor_total = '';
				$txt_rodape = array();
				$i = 0;
				$tot_itens = 0;
				
				$txt_cabecalho[] = '-------- RECIBO DE DOACAO -------';   							
				$txt_cabecalho[] = ' ';
				$txt_cabecalho[] = 'Data: '.date('d/m/Y').'';
				$txt_cabecalho[] = 'De: '.$daod->filter($nome).'';
				$mes = explode("-",date('Y-m-d'));
				$txt_itens[] = array('#', 'Mes', 'Valor');	
				$txt_itens[] = array($i++, ''.$mes[1].'/'.$mes[0].'', ''.number_format($valor,2,',','.').'');
				$tot_itens   = $tot_itens + $valor;
				
				
				$aux_valor_total = 'Valor de  R$: '.number_format($tot_itens,2,',','.').' ('.$daod->valorPorExtenso(number_format($tot_itens,2,',','.'),true,false).')';
				
				 $total_espacos = $n_colunas - strlen($aux_valor_total);
        
				$espacos = '';
				
				for($i = 0; $i < $total_espacos; $i++){
					$espacos .= '';
				}
				
				$txt_valor_total = $espacos.$aux_valor_total;               				
				$txt_rodape[] = '________________________________';
				$txt_rodape[] = ''.$empresa.' ';
				$txt_rodape[] = 'Gravatai RS '.utf8_encode(strftime('%d de %B de %Y', strtotime(date('d.m.Y')))).'';
				$txt_rodape[] = ' ';
				$txt_rodape[] = ''.utf8_decode($msgdoacao).'';
				$txt_rodape[] = ' ';
				//$cabecalho = array_map("centraliza", $txt_cabecalho);
			    $cabecalho = $txt_cabecalho;
									
				 foreach ($txt_itens as $item) {
            
					/*
				 * Cod. => máximo de 5 colunas
				 * Produto => máximo de 11 colunas
				 * Env. => máximo de 6 colunas
				 * Qtd => máximo de 4 colunas
				 * V. UN => máximo de 7 colunas
				 * Total => máximo de 7 colunas
				 *
				 * $itens[] = 'Cod. Produto      Env. Qtd  V. UN  Total'
				 */
					
					$itens[] = $daod->addEspacos($item[0], 7, 'F')
							 . $daod->addEspacos($item[1], 10, 'F')
							 . $daod->addEspacos($item[2], 15, 'F')							
						;
					
				}
				
			/* concatena o cabelhaço, os itens, o sub-total e rodapé
			 * adicionando uma quebra de linha "\r\n" ao final de cada
			 * item dos arrays $cabecalho, $itens, $txt_rodape
			 */
				$txt = implode("\r\n", $cabecalho)
					. "\r\n"
					. implode("\r\n", $itens)
					. "\r\n\r\n"
					. $txt_valor_total // Sub-total
					. "\r\n\r\n"
					. implode("\r\n", $txt_rodape);
				
				// caminho e nome onde o TXT será criado no servidor
				$file = 'nome_arquivo.txt';
		
				// cria o arquivo
				$_file  = fopen($file,"w");
				fwrite($_file,$txt);
				fclose($_file);
		
				header("Pragma: public"); 
				// Força o header para salvar o arquivo
				header("Content-type: application/save");
				header("X-Download-Options: noopen "); // For IE8
				header("X-Content-Type-Options: nosniff"); // For IE8
				// Pré define o nome do arquivo
				header("Content-Disposition: attachment; filename=nome_arquivo.txt"); 
				header("Expires: 0"); 
				header("Pragma: no-cache");
		
				// Lê o arquivo para download
				readfile($file);

			
				
				/*$printer = printer_open("\\\\187.58.220.117\\MP-4200 TH");
				printer_set_option($printer, PRINTER_MODE, "raw");
				$font = printer_create_font("Arial", 23, 13, PRINTER_FW_ULTRABOLD, false, false, false, 900);
				printer_select_font($printer, $font);
				
				$strs = file_get_contents("nome_arquivo.txt");
				
				printer_write($printer,$strs);
				printer_close($printer);*/
				
			break;
			case 'promocao':
				require_once('geral_config.php');
				
				setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
				date_default_timezone_set('America/Sao_Paulo');
				
				$cont = file_get_contents("numero.txt");
				$posicao2 = $cont;
			
				$numero = 'P'.str_pad($posicao2, 3, "0", STR_PAD_LEFT)."/".date('mY'); 
				if(empty($_REQUEST['valor'])){
					$valor = 0.00;
				}else{
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));
				}
				
				
								
				if(empty($_REQUEST['codcli'])){
					$codcli = 0;
					$nome	= $_REQUEST['cliente'];
				}else{
					$codcli = $_REQUEST['codcli'];
					$nome	= $_REQUEST['cliente'];
				}
				
				if(empty($_REQUEST['formpagto'])){
					$formpagto = '1';
				}else{
					$formpagto = $_REQUEST['formpagto'];
				}
				
				$duplic = new DuplicReceber();
						
				$duplic->setCodigoCliente($codcli);
				$duplic->setEmissao(date('Y-m-d'));
				$duplic->setNumero($numero);
				$duplic->setVencimento(date('Y-m-d'));
				$duplic->setValorDoc($valor);		
				$duplic->setHistorico(utf8_encode('Promoção no dia '.date('d-m-Y').''));
				$duplic->setDataPag(date('Y-m-d'));
				$duplic->setSaldo(0);
				$duplic->setValorPago(0);
				$duplic->setTipo('C');
				$duplic->setBanco('');
				$duplic->setNome($nome);
				$duplic->setFormPagto($formpagto);
				
				$daod = new DuplicReceberDAO(); 

				$vtprox = $daod->proximoid();
				$prox   = $vtprox[0];
				$idprox = $prox->getProximoId();

				$daod->inserir($duplic);
				
				$data = Array ("numero" => $idprox,
										"tppagto" => "{$formpagto}",
										"valor" => $valor);

				$daos = new RecebimentoDAO();				
				$daos->Gravar($data);
				
				$posicao2 = $_REQUEST['nseq'] + 1;
				
				$cont = file_get_contents("numero.txt");
				$cont = $cont+1;
				file_put_contents("numero.txt",$cont);
				
				$result = array();
				
				/*$result[] = array(
					'posicao'=>''.$cont.'',	
					'msg'=>'contribuido com sucesso :D',
				);*/
				
				
				
				$n_colunas = 28;
				$txt_cabecalho = array();
				$txt_itens = array();
				$txt_valor_total = '';
				$txt_rodape = array();
				$i = 0;
				$tot_itens = 0;
				
				$txt_cabecalho[] = '-------- RECIBO DE PROMOÇÃO -------';   							
				$txt_cabecalho[] = ' ';
				$txt_cabecalho[] = 'Data: '.date('d/m/Y').'';
				$txt_cabecalho[] = 'De: '.$daod->filter($nome).'';
				$mes = explode("-",date('Y-m-d'));
				$txt_itens[] = array('#', 'Mes', 'Valor');	
				$txt_itens[] = array($i++, ''.$mes[1].'/'.$mes[0].'', ''.number_format($valor,2,',','.').'');
				$tot_itens   = $tot_itens + $valor;
				
				
				$aux_valor_total = 'Valor de  R$: '.number_format($tot_itens,2,',','.').' ('.$daod->valorPorExtenso(number_format($tot_itens,2,',','.'),true,false).')';
				
				 $total_espacos = $n_colunas - strlen($aux_valor_total);
        
				$espacos = '';
				
				for($i = 0; $i < $total_espacos; $i++){
					$espacos .= '';
				}
				
				$txt_valor_total = $espacos.$aux_valor_total;               				
				$txt_rodape[] = '________________________________';
				$txt_rodape[] = ''.$empresa.' ';
				$txt_rodape[] = 'Gravatai RS '.utf8_encode(strftime('%d de %B de %Y', strtotime(date('d.m.Y')))).'';
				$txt_rodape[] = ' ';
				$txt_rodape[] = ''.utf8_decode($msgdoacao).'';
				$txt_rodape[] = ' ';
				//$cabecalho = array_map("centraliza", $txt_cabecalho);
			    $cabecalho = $txt_cabecalho;
									
				 foreach ($txt_itens as $item) {
            
					/*
				 * Cod. => máximo de 5 colunas
				 * Produto => máximo de 11 colunas
				 * Env. => máximo de 6 colunas
				 * Qtd => máximo de 4 colunas
				 * V. UN => máximo de 7 colunas
				 * Total => máximo de 7 colunas
				 *
				 * $itens[] = 'Cod. Produto      Env. Qtd  V. UN  Total'
				 */
					
					$itens[] = $daod->addEspacos($item[0], 7, 'F')
							 . $daod->addEspacos($item[1], 10, 'F')
							 . $daod->addEspacos($item[2], 15, 'F')							
						;
					
				}
				
			/* concatena o cabelhaço, os itens, o sub-total e rodapé
			 * adicionando uma quebra de linha "\r\n" ao final de cada
			 * item dos arrays $cabecalho, $itens, $txt_rodape
			 */
				$txt = implode("\r\n", $cabecalho)
					. "\r\n"
					. implode("\r\n", $itens)
					. "\r\n\r\n"
					. $txt_valor_total // Sub-total
					. "\r\n\r\n"
					. implode("\r\n", $txt_rodape);
				
				// caminho e nome onde o TXT será criado no servidor
				$file = 'nome_arquivo.txt';
				$arquivoLocal = '/Downloads/'.$file;
				// cria o arquivo
				$_file  = fopen($file,"w");
				fwrite($_file,$txt);
				fclose($_file);
		
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename="'.$file.'"');
				header('Content-Type: application/octet-stream');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($file));
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Expires: 0');
		
				// Lê o arquivo para download
				readfile($file);

											
			break;
			case 'nova_impressao':
			
				require_once('geral_config.php');
				
				$codc = "";
				$result  = array();
				$result2 = array();
				$str = "";
				$total = 0;
				$id = $_REQUEST['id'];
				
				$n_colunas = 28;
				$txt_cabecalho = array();
				$txt_itens = array();
				$txt_valor_total = '';
				$txt_rodape = array();
				$i = 0;
				$tot_itens = 0;
				$dao =  new DuplicReceberDAO();	
				foreach($id as $key=>$value){
									
					$vetc =  $dao->ListaDuplicRecebePegaCilente($value);
					$nunc = count($vetc);
															
					if($nunc > 0){
						
						$rc = $vetc[0];
							
						$codcli  = $rc->getCodigoCliente();		
						$nomecli = $rc->getNomeCliente();	
						
						if($codcli != $codc){
						
							$codc = $codcli;
							
							
							$txt_cabecalho[] = '----- RECIBO DE MENSALIDADE -----';  							
							$txt_cabecalho[] = ' ';
							$txt_cabecalho[] = 'Data: '.date('d/m/Y').'';
							$txt_cabecalho[] = 'Nome Cliente: '.$nomecli.'';																					
							
							//$str .= "De: ".$nomecli."\r\n";
						}
						
					}
					
					$vet = $dao->ListaDuplicReceberUm($value);
					$dup = $vet[0];
					$idcli      = $dup->getCodigoCliente();	
					
					$txt_itens[] = array('#', 'Mes', 'Valor');		
					
					
					if($codc == $idcli){
					
						$emissao    = $dup->getEmissao();
						$numero     = $dup->getNumero();
						$vencimento = $dup->getVencimento();
						$valordoc   = $dup->getValorDoc();
						$hist       = $dup->getHistorico();
						$datapag    = $dup->getDataPag();
						$banco      = $dup->getBanco();
						$mes = explode("-",$vencimento);
						
						$total = $total + $valordoc;
						
						//$str .= "Mês :".$mes[1]." - ".number_format($valordoc,2,',','.')."\r\n";
																		
						$txt_itens[] = array($i++, ''.$mes[1].'/'.$mes[0].'', ''.number_format($valordoc,2,',','.').'');
						$tot_itens   = $tot_itens + $valordoc;
						
					}
					
						
				}
				
				$daorc =  new DuplicReceberDAO();
				
				$aux_valor_total = 'Total R$: '.number_format($tot_itens,2,',','.').' ('.$daorc->valorPorExtenso(number_format($tot_itens,2,',','.'),true,false).')';
				
				 $total_espacos = $n_colunas - strlen($aux_valor_total);
        
				$espacos = '';
				
				for($i = 0; $i < $total_espacos; $i++){
					$espacos .= '';
				}
				
				$txt_valor_total = $espacos.$aux_valor_total;               				
				$txt_rodape[] = '__________________________________';
				$txt_rodape[] = ''.$empresa.' ';
				$txt_rodape[] = 'Gravatai RS '.utf8_encode(strftime('%d de %B de %Y', strtotime(date('d.m.Y')))).'';
				$txt_rodape[] = ' ';
				$txt_rodape[] = ''.utf8_decode($msgmensalidade).'';
				$txt_rodape[] = ' ';
				//$cabecalho = array_map("centraliza", $txt_cabecalho);
			    $cabecalho = $txt_cabecalho;
									
				 foreach ($txt_itens as $item) {
            
					/*
				 * Cod. => máximo de 5 colunas
				 * Produto => máximo de 11 colunas
				 * Env. => máximo de 6 colunas
				 * Qtd => máximo de 4 colunas
				 * V. UN => máximo de 7 colunas
				 * Total => máximo de 7 colunas
				 *
				 * $itens[] = 'Cod. Produto      Env. Qtd  V. UN  Total'
				 */
					
					$itens[] = $daorc->addEspacos($item[0], 7, 'F')
							 . $daorc->addEspacos($item[1], 10, 'F')
							 . $daorc->addEspacos($item[2], 15, 'F')							
						;
					
				}
				
			/* concatena o cabelhaço, os itens, o sub-total e rodapé
			 * adicionando uma quebra de linha "\r\n" ao final de cada
			 * item dos arrays $cabecalho, $itens, $txt_rodape
			 */
				$txt = implode("\r\n", $cabecalho)
					. "\r\n"
					. implode("\r\n", $itens)
					. "\r\n\r\n"
					. $txt_valor_total // Sub-total
					. "\r\n\r\n"
					. implode("\r\n", $txt_rodape);
				
				// caminho e nome onde o TXT será criado no servidor
				$file = 'nome_arquivo.txt';
		
				// cria o arquivo
				$_file  = fopen($file,"w");
				fwrite($_file,$txt);
				fclose($_file);
		
				header("Pragma: public"); 
				// Força o header para salvar o arquivo
				header("Content-type: application/save");
				header("X-Download-Options: noopen "); // For IE8
				header("X-Content-Type-Options: nosniff"); // For IE8
				// Pré define o nome do arquivo
				header("Content-Disposition: attachment; filename=nome_arquivo.txt"); 
				header("Expires: 0"); 
				header("Pragma: no-cache");
		
				// Lê o arquivo para download
				readfile($file);
				
				
				
				
			break;
			case 'print':
					
				error_reporting(E_ALL); 
				ini_set('display_errors', '1'); 
				 
				 
				//carrega o componente pelo GUID (pelo nome não funcionou)
				//$bema = new COM("{310DBDAC-85FF-4008-82A8-E22A09F9460B}");
				//abre porta
				//$init = $bema->IniciaPorta("LPT1");
				//$init = $bema->IniciaPorta("COM4");
				/*echo $init;
				if ($init <= 0) {
				echo "erro!";
				exit;
				}*/		

				/*$bema->FormataTX("--------------------------------- \n", 2, 0 , 0, 0, 0);
				
				//fecha a porta de impressao
				$bema->FechaPorta();*/
				
				$printer = printer_open("MP-4200 TH");
				printer_set_option($printer, PRINTER_MODE, "raw");
			
				$str = file_get_contents("nome_arquivo.txt");
				
				
							
				//$str = str_replace(array('{img}'), array("". $ean13->create_image().""), $str);	
				
				printer_write($printer,$str);
				printer_close($printer);
				
				echo "Foi para impressão...";	
			break;
			
		}

	

	

	}

	

	//header('Location:'.$destino);

?>