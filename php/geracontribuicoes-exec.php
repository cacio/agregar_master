<?php

	

	require_once('../inc/inc.autoload.php');

	

	$tpl = new TemplatePower('../tpl/geracontribuicoes-exec.htm');

	//$tpl->assignInclude('conteudo','../tpl/relatorioficha.htm');

	$tpl->prepare();

	

	/**************************************************************/

		
		require_once('../inc/inc.session.php');

		//require_once('../inc/inc.menu.php');

		$tpl->assign('log',$_SESSION['login']);

		$condicao  = array();
		$condicao2 = array();		
							
		$dao = new FichaDAO();

		$campoDataInicio = implode("-", array_reverse(explode("/", "".$_REQUEST['dtmesano'].""))). '-01';	
		$campoDataFinal  = implode("-", array_reverse(explode("/", "".$_REQUEST['dtmesanofim'].""))). '-01';

		//echo $campoDataFinal;	

		$dataInicio      = $dao->newDate(date($campoDataInicio), "Y-m-d", "-1 month");
        $dataFim         = date($campoDataFinal);
        $newDate         = $dataInicio;

        while ($newDate < $dataFim) {

        	   $newDate = $dao->newDate($newDate, "Y-m-d", "+1 month");

        	   $year  	  = date("Y", strtotime($newDate)); 
               $month 	  = date("m", strtotime($newDate));
               $datamesano = $month.'/'.$year;	
               $venc       = $year.'-'.$month.'-28';
             // echo $venc;
              //echo $datamesano.'<br/>';

	            $condicao     = array();  
	            if(isset($_REQUEST['cliente']) and !empty($_REQUEST['cliente'])){
					$cliente     =  $_REQUEST['cliente'];	
					$condicao[]  = " f.id = '".$cliente."' ";				
				}
					
	            $condicao[]   = " f.ativo = 1 and f.vlmensalidade > 0 ";
			 	$condicao[]   = " (select count(*) from duplic_receber r where f.id = r.cod_cliente and r.numero = concat(lpad(f.id,4,0),'/','".str_replace('/','',$datamesano)."')) = 0 ";		
			
						
				$where = '';
				if(count($condicao) > 0){
				
					$where = ' where'.implode('AND',$condicao);
						
				}	

				$dao = new FichaDAO();
				$vet = $dao->ListaFichaDuplicReceber($where);
				$num = count($vet);


				for($i = 0; $i < $num; $i++){
				
					$ficha = $vet[$i];	
						
					$codigo			= $ficha->getCodigo();	
					$nome      	    = $ficha->getNome();
					$vlmensalidade  = $ficha->getValorMensalidade();
					
						
					$numero = str_pad($codigo, 4, "0", STR_PAD_LEFT).'/'.str_replace('/','',$datamesano);
					
					$duplic = new DuplicReceber();
								
					$duplic->setCodigoCliente($codigo);
					$duplic->setEmissao(date('Y-m-d'));
					$duplic->setNumero($numero);
					$duplic->setVencimento($venc);
					$duplic->setValorDoc($vlmensalidade);		
					$duplic->setHistorico('');
					$duplic->setDataPag('');
					$duplic->setSaldo(0);
					$duplic->setValorPago(0);
					$duplic->setTipo('');
					$duplic->setBanco('');
					
					$daod = new DuplicReceberDAO(); 
					$daod->inserir($duplic);
					
					
					$tpl->newBlock('listacontribuicao');
					
					$tpl->assign('codigo',$codigo);
					$tpl->assign('nome',$nome);
					$tpl->assign('emissao',date('d/m/y'));
					$tpl->assign('numero',$numero);
					$tpl->assign('dtvenc',date('d/m/Y',strtotime($venc)));
					$tpl->assign('vlmensalidade',number_format($vlmensalidade,2,',','.'));
						
				}




        }


        // Datas nesse formato
		/*$dataInicio = date("2014-09-01");
		$dataFim = date("2015-03-01");

		$newDate = $dataInicio;
		echo date("m/Y", strtotime($newDate)) . "<br/>";

		while ($newDate < $dataFim) {
		    $newDate = $dao->newDate($newDate, "Y-m-d", "+1 month");
		    echo date("m/Y", strtotime($newDate)) . "<br/>";
		}*/

		/*$dao = new FichaDAO();
		$vet = $dao->ListaFichaDuplicReceber($where);
		$num = count($vet);
		
		
		for($i = 0; $i < $num; $i++){
			
			$ficha = $vet[$i];	
				
			$codigo			= $ficha->getCodigo();	
			$nome      	    = $ficha->getNome();
			$vlmensalidade  = $ficha->getValorMensalidade();
			
				
			$numero = str_pad($codigo, 4, "0", STR_PAD_LEFT).'/'.str_replace('/','',$_REQUEST['dtmesano']);
			
			$duplic = new DuplicReceber();
						
			$duplic->setCodigoCliente($codigo);
			$duplic->setEmissao(date('Y-m-d'));
			$duplic->setNumero($numero);
			$duplic->setVencimento(implode("-", array_reverse(explode("-", "".$_REQUEST['dtvenc'].""))));
			$duplic->setValorDoc($vlmensalidade);		
			$duplic->setHistorico('');
			$duplic->setDataPag('');
			$duplic->setSaldo(0);
			$duplic->setValorPago(0);
			$duplic->setTipo('');
			$duplic->setBanco('');
			
			$daod = new DuplicReceberDAO(); 
			$daod->inserir($duplic);
			
			
			$tpl->newBlock('listacontribuicao');
			
			$tpl->assign('codigo',$codigo);
			$tpl->assign('nome',$nome);
			$tpl->assign('emissao',date('d/m/y'));
			$tpl->assign('numero',$numero);
			$tpl->assign('dtvenc',str_replace('-','/',$_REQUEST['dtvenc']));
			$tpl->assign('vlmensalidade',number_format($vlmensalidade,2,',','.'));
				
		}*/
		
		
		
		

	/**************************************************************/

	$tpl->printToScreen();



?>