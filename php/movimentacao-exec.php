<?php

	require_once('../inc/inc.autoload.php');
	require_once('geral_config.php');
	
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':								
				/*echo "<pre>";
				print_r($_REQUEST);*/
				
				if(empty($_REQUEST['tipo'])){					
					$tipo = "E";	
				}else{					
					$tipo = $_REQUEST['tipo'];				
				}
				
				if(empty($_REQUEST['numero'])){					
					$numero = "0";	
				}else{	
					if($tipo == "S"){				
						$numero = $_REQUEST['numero'];				
					}else{
						$numero = "0";	
					}
				}
				
				if(empty($_REQUEST['produto'])){					
					$produto = "0";	
				}else{					
					$produto = $_REQUEST['produto'];				
				}
				
				if(empty($_REQUEST['data'])){					
					$data = date('Y-m-d');	
				}else{					
					$data = implode("-", array_reverse(explode("/", "".$_REQUEST['data']."")));				
				}
				
				if(empty($_REQUEST['valor'])){					
					$valor = "0";	
				}else{					
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));				
				}
				
				if(empty($_REQUEST['quantidade'])){					
					$quantidade = "0";	
				}else{					
					$quantidade = $_REQUEST['quantidade'];				
				}
				
				$total = $valor * $quantidade;		
				
				$movim = new Movimentacao();

				//$movim->setCodigo($cod);
				$movim->setNumeroControl($numero);
				$movim->setIdProduto($produto);
				$movim->setData($data);
				$movim->setTipo($tipo);
				$movim->setValorUnitario($valor);
				$movim->setQuantidade($quantidade);
				$movim->setTotal($total);
				
				$dao = new MovimentacaoDAO();
				
				$vetm 	 = $dao->proximoid2();
				$prox 	 = $vetm[0];		
				$proxcod = $prox->getProxid();								
				
				$dao->inserir($movim);
				
				
				$daop = new ProdutosDAO();	
				$vetp = $daop->ListaProdutosUm($produto);
				$nump = count($vetp);
				
				if($nump > 0){										
					$prod   = $vetp[0];					
					$nome   = $prod->getNome();
					$id		= $prod->getCodigo();																
				}else{
					$nome   = "";
					$id		= "";
				}
				
				$result = array();
				
				array_push($result,array(
					'proxcod'=>''.$proxcod.'',
					'numero'=>''.$numero.'',
					'produto'=>''.$produto.'',
					'nome'=>''.$nome.'',
					'data'=>''.implode("/", array_reverse(explode("-", "".$data.""))).'',
					'tipo'=>''.$tipo.'',
					'quantidade'=>''.$quantidade.'',
					'valor'=>''.number_format($valor,2,',','.').'',
					'total'=>''.number_format($total,2,',','.').'',
					'msg'=>'Inserido com sucesso!',
				));
				
				echo(json_encode($result));					
				
		break;

		case 'inserir2':								
				/*echo "<pre>";
				print_r($_REQUEST);*/
				setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );
				date_default_timezone_set( 'America/Sao_Paulo' );
				
				if(empty($_REQUEST['tipo'])){					
					$tipo = "E";	
				}else{					
					$tipo = $_REQUEST['tipo'];				
				}
				
				if(empty($_REQUEST['numero'])){					
					$numero = "0";	
				}else{	
					if($tipo == "S"){				
						$numero = $_REQUEST['numero'];				
					}else{
						$numero = "0";	
					}
				}
				
				if(empty($_REQUEST['produto'])){					
					$produto = "0";	
				}else{					
					$produto = $_REQUEST['produto'];				
				}
				
				if(empty($_REQUEST['data'])){					
					$data = date('Y-m-d');	
				}else{					
					$data = implode("-", array_reverse(explode("/", "".$_REQUEST['data']."")));				
				}
				
				if(empty($_REQUEST['valor'])){					
					$valor = "0";	
				}else{					
					$valor = str_replace(',', '.', str_replace('.', '', $_REQUEST['valor']));				
				}
				
				if(empty($_REQUEST['quantidade'])){					
					$quantidade = "0";	
				}else{					
					$quantidade = $_REQUEST['quantidade'];				
				}
				
				if(empty($_REQUEST['obs'])){					
					$obs = "";	
				}else{					
					$obs = $_REQUEST['obs'];				
				}
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				if(empty($_REQUEST['xnome'])){
					$xnome = ".............................................";
				}else{
					$xnome = $_REQUEST['xnome'];
				}
				
				if(empty($_REQUEST['cpf'])){
					$cpf = ".............................................";
				}else{
					$cpf = $_REQUEST['cpf'];
				}
				
				$total = $valor * $quantidade;		
				
				$movim = new Movimentacao();

				//$movim->setCodigo($cod);
				$movim->setNumeroControl($numero);
				$movim->setIdProduto($produto);
				$movim->setData($data);
				$movim->setTipo($tipo);
				$movim->setValorUnitario($valor);
				$movim->setQuantidade($quantidade);
				$movim->setTotal($total);
				$movim->setObs($nome.': '.$obs);
				
				$dao = new MovimentacaoDAO();
				
				$vetm 	 = $dao->proximoid2();
				$prox 	 = $vetm[0];		
				$proxcod = $prox->getProxid();								
				
				$dao->inserir2($movim);
																
				$result = array();
				
				$comp = new Componentes();
				
				$valorextenso = $comp->valorPorExtenso($_REQUEST['valor'], true, false);
				
				array_push($result,array(
					'proxcod'=>''.$proxcod.'',
					'numero'=>''.$numero.'',					
					'nome'=>''.utf8_decode($nome).'',
					'data'=>''.utf8_decode(strftime( '%A, %d de %B de %Y', strtotime($data))).'',
					'tipo'=>''.$tipo.'',
					'quantidade'=>''.$quantidade.'',
					'valor'=>''.$_REQUEST['valor'].'',
					'valorextenso'=>''.utf8_decode($valorextenso).'',
					'obs'=>''.$obs.'',
					'xnome'=>''.$xnome.'',
					'cpf'=>''.$cpf.'',
					'msg'=>'Inserido com sucesso!',
				));
				
				echo(json_encode($result));					
				
		break;

		case 'imprimir':
				
				$ncontrol = $_REQUEST['ncontrol'];
				$str 	  = file_get_contents("../public/nota.txt"); //modelo da impressão
				
				if(!empty($_REQUEST['dimcard'])){	
					$item 	  = $_REQUEST['dimcard']; 		
					$numitem  = count($item);
					if($numitem > 0){
						
						for($x = 0; $x < $numitem; $x++){
						
							$items = $item[$x];
	
							$xtipo  = $items['forma'];
							$xvalor = str_replace(',', '.', str_replace('.', '', $items['valor'])); 
							
							$mist = new FormaMistaMovimentacoes();
							
							$mist->setIdNumeroControle($ncontrol);
							$mist->setTipo($xtipo);
							$mist->setValor($xvalor);
							
							$daom = new FormaMistaMovimentacoesDAO();
							
							$daom->inserir($mist);
							
						}								
					}
				}
				$dao = new MovimentacaoDAO();
				
				$vet = $dao->ListaMovimentacaoDetalhe($ncontrol);
				$num = count($vet);
				
				$movim = $vet[0];
					
				$numero = $movim->getNumeroControl();
				$data   = $movim->getData();
				$total	= $movim->getTotal();	
				
				
				$vetm     = $dao->ListaMovimentacaoUm($ncontrol);
				$numm 	  = count($vetm);
				$xproduto = "";
				$result   = array();
				
				for($i = 0; $i < $numm; $i++){
					
					$mov 		= $vetm[$i];
					
					$cod_mov	=	$mov->getCodigo();
					$id_produto = 	$mov->getIdProduto();
					$vlunit 	= 	$mov->getValorUnitario();
					$quantidade = 	$mov->getQuantidade();
					$totals		=	$mov->getTotal();
						
					$daop = new ProdutosDAO();	
					$vetp = $daop->ListaProdutosUm($id_produto);
					$nump = count($vetp);
													
					$prod   = $vetp[0];					
					
					$nome   = $prod->getNome();
					
					$movimenta = new Movimentacao();

				    $movimenta->setCodigo($cod_mov);
					$movimenta->setPagto($_REQUEST['formpagto']);
					
					$dao->updatepagto($movimenta);
					
					$xproduto .= "|".str_pad($nome, 41, ' ', STR_PAD_RIGHT)."|".str_pad($quantidade, 4, ' ', STR_PAD_LEFT)."|".str_pad(number_format($vlunit,2,',','.'), 6, ' ', STR_PAD_LEFT)."|".str_pad(number_format($totals,2,',','.'), 8, ' ', STR_PAD_LEFT)."|\n";
					
										
						array_push($result,array(							
							'id_produto'=>''.$id_produto.'',
							'nome'=>''.$nome.'',
							'quantidade'=>''.$quantidade.'',
							'valor'=>''.number_format($vlunit,2,',','.').'',
							'total'=>''.number_format($totals,2,',','.').'',
						));														
				
				}
				
					$str = str_replace(array('{nota}',
										 '{valor}',
										 '{xquantidade}',
										 '{xproduto}',
										 '{msg}'), 
									array("".str_pad($numero, 8, ' ', STR_PAD_RIGHT)."",									
										  "".str_pad(number_format($total,2,',','.'), 7, ' ', STR_PAD_RIGHT)."",
										  "".$quantidade."",
										  "".utf8_decode($xproduto)."",
										  "".utf8_decode($msgmensalidade).""), $str);
				
				
				$fp		 = fopen("../public/enviar/".$ncontrol.".txt", "w+");
				$escreve = fwrite($fp,$str);
				fclose($fp);
				
				$vetm 	 = $dao->proximoid();
				$prox 	 = $vetm[0];		
				$proxcod = $prox->getProxid();
				
				
				//echo utf8_encode($str);
				$data = array('result'=>$result,'total'=>$total,'numero'=>$numero,'data'=>implode("/", array_reverse(explode("-", "".$data.""))),'msgmensalidade'=>utf8_encode($msgmensalidade),'proxcod'=>''.$proxcod.'');
				echo json_encode($data);
				
		break;

		

		case 'delete':
				
			$cod   =  $_REQUEST['id'];	
							
			$movim = new Movimentacao();

			$movim->setCodigo($cod);
		
			$dao = new MovimentacaoDAO();
			$dao->deletar($movim);
			
			$result = array();
			
			array_push($result,array(
				'cod'=>''.$cod.'',				
			));
			
			echo(json_encode($result));	
			
		break;


		}
	}
?>