<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/competencias.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		require_once('../inc/inc.pushmensagem.php');
		$tpl->assign('log',$_SESSION['login']);
		
		$condicoes = array();

		if(isset($_REQUEST['compini']) and !empty($_REQUEST['compini'])){

			$compini       =  $_REQUEST['compini'];					
			$condicao[]    = " p.competencia between '".$compini."' ";
								
		}

		/*if(isset($_REQUEST['compini']) and !empty($_REQUEST['compini'])){

			$compini       =  $_REQUEST['compini'];					
			$condicao[]    = " p.competencia between '".$compini."' ";
								
		}*/

		if(isset($_REQUEST['compfim']) and !empty($_REQUEST['compfim'])){

			$compfim       =  $_REQUEST['compfim'];					
			$condicao[]    = " '".$compfim."' ";
								
		}

		if(isset($_REQUEST['cnpjemp']) and !empty($_REQUEST['cnpjemp'])){

			$cnpjemp      =  $_REQUEST['cnpjemp'];	
			$condicao[]   = " e.cnpj = '".$cnpjemp."' ";            		
		}

		if(isset($_REQUEST['protocolo']) and !empty($_REQUEST['protocolo'])){

			$protocolo      =  $_REQUEST['protocolo'];	
			$condicao[]     = " p.protocolo = '".$protocolo."' ";            		
		}

		$condicao[]    = " p.status = '8' ";

		$where = '';
		if(count($condicao) > 0){		
			$where = ' where'.implode('AND',$condicao);				
		}

		$dao   = new ResumoDAO();
		$vet   = $dao->CompetenciasEnviadasAgregar($where);
		$num   = count($vet);
		$xcnpj = "";

		for($i = 0; $i < $num; $i++){

			$resu 		  = $vet[$i];

			$cod 	   	  = $resu->getCodigo();			
			$status    	  = $resu->getStatus();
			$protocolo 	  = $resu->getProtocolo();
			$competencia  = $resu->getCompetenc();
			$nome 		  = $resu->getNomeStatus();
			$razao_social = $resu->getRazaoSocialEmp();
			$fantasia 	  = $resu->getEmpFantasia();
			$cnpj 		  = $resu->getCnpjEmp();
			$endereco 	  = $resu->getEmpEndereco();
			$fone1 	  	  = $resu->getEmpFone1();
			$fone2 		  = $resu->getEmpFone2();
			$cidade 	  = $resu->getEmpCidade();
			$estado 	  = $resu->getEmpEstado();
			$bairro 	  = $resu->getEmpBairro();
			$cep 		  = $resu->getEmpCep();	
			$mesano		  = array();	
			if($cnpj != $xcnpj){
				$xcnpj = $cnpj;

				$tpl->newBlock('lista');

				$tpl->assign('razao_social',$razao_social);
				$tpl->assign('fantasia',$fantasia);
				$tpl->assign('cnpj',$cnpj);
				$tpl->assign('endereco',$endereco);
				$tpl->assign('fone1',$fone1);
				$tpl->assign('fone2',$fone2);	
				$tpl->assign('cidade',$cidade);
				$tpl->assign('estado',$estado);	
				$tpl->assign('bairro',$bairro);
				$tpl->assign('cep',$cep);
				
				$tpl->newBlock('detalhe');
			
			$ano = date('Y');
			$mesano  = array(
				"01/{$ano}" => "01/{$ano}",
				"02/{$ano}" => "02/{$ano}",
				"03/{$ano}" => "03/{$ano}",
				"04/{$ano}" => "04/{$ano}",
				"05/{$ano}" => "05/{$ano}",
				"06/{$ano}" => "06/{$ano}",
				"07/{$ano}" => "07/{$ano}",
				"08/{$ano}" => "08/{$ano}",
				"09/{$ano}" => "09/{$ano}",
				"10/{$ano}" => "10/{$ano}",
				"11/{$ano}" => "11/{$ano}",
				"12/{$ano}" => "12/{$ano}",
			 );

			foreach ($mesano as $key => $value) {
				
				$tpl->newBlock('detalhe');

				if($value == $competencia){
					$cor = "btn-success";
					$tpl->assign('competencia',$competencia);
					$tpl->assign('protocolo',$protocolo);
					$tpl->assign('cnpjs',$cnpj);
					$tpl->assign('cor',$cor);
				}else{
					$cor = "btn-danger";
					$tpl->assign('competencia',$value);
					$tpl->assign('protocolo','');
					$tpl->assign('cnpjs','');
					$tpl->assign('cor',$cor);
				}

				
				

			}
			
			}
			

			
		}

	
	/**************************************************************/
	$tpl->printToScreen();

?>