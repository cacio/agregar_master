<?php

	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){	

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':

				//print_r($_REQUEST);
				
				if(empty($_REQUEST['dtcaixa'])){
					$dtcaixa = date('Y-m-d');
				}else{
					$dtcaixa = implode("-", array_reverse(explode("/", "".$_REQUEST['dtcaixa']."")));
				}
				
				if(empty($_REQUEST['departamento'])){
					$departamento = "";
				}else{
					$departamento = $_REQUEST['departamento'];
				}
				
				if(empty($_REQUEST['tipoentsai'])){
					$tipoentsai = "";
				}else{
					$tipoentsai = $_REQUEST['tipoentsai'];
				}
				
				if(empty($_REQUEST['documento'])){
					$documento = "";
				}else{
					$documento = $_REQUEST['documento'];
				}
				
				if(empty($_REQUEST['valorcaixa'])){
					$valorcaixa = "";
				}else{
					$valorcaixa = str_replace(',', '.', str_replace('.', '',$_REQUEST['valorcaixa']));
				}
				
				if(empty($_REQUEST['historico'])){
					$hist = "";
				}else{
					$hist = $_REQUEST['historico'];
				}
				
				$caixa = new Caixa();
				
				//$caixa->setCodigo($cod);
				$caixa->setData($dtcaixa);
				$caixa->setDocumento($documento);
				$caixa->setHistorico($hist);
				$caixa->setTipo($tipoentsai);
				$caixa->setValor($valorcaixa);
				$caixa->setIdDep($departamento);
				
				$dao = new CaixaDAO();	
				$dao->inserir($caixa);
					
				echo "Inserido com suceso!";
				
				
				
				
				
		break;

		case 'alterar':
					
			$id = $_REQUEST['id'];	
				
			if(empty($_REQUEST['dtcaixa'])){
					$dtcaixa = date('Y-m-d');
				}else{
					$dtcaixa = implode("-", array_reverse(explode("/", "".$_REQUEST['dtcaixa']."")));
				}
				
				if(empty($_REQUEST['departamento'])){
					$departamento = "";
				}else{
					$departamento = $_REQUEST['departamento'];
				}
				
				if(empty($_REQUEST['tipoentsai'])){
					$tipoentsai = "";
				}else{
					$tipoentsai = $_REQUEST['tipoentsai'];
				}
				
				if(empty($_REQUEST['documento'])){
					$documento = "";
				}else{
					$documento = $_REQUEST['documento'];
				}
				
				if(empty($_REQUEST['valorcaixa'])){
					$valorcaixa = "";
				}else{
					$valorcaixa = str_replace(',', '.', str_replace('.', '',$_REQUEST['valorcaixa']));
				}
				
				if(empty($_REQUEST['historico'])){
					$hist = "";
				}else{
					$hist = $_REQUEST['historico'];
				}
				
				$caixa = new Caixa();
				
				$caixa->setCodigo($id);
				$caixa->setData($dtcaixa);
				$caixa->setDocumento($documento);
				$caixa->setHistorico($hist);
				$caixa->setTipo($tipoentsai);
				$caixa->setValor($valorcaixa);
				$caixa->setIdDep($departamento);
				
				$dao = new CaixaDAO();	
				$dao->alterar($caixa);
					
				echo "Alterado com suceso!";			

		break;

		

		case 'delete':

			$id = $_REQUEST['id'];
			
			$caixa = new Caixa();
				
			$caixa->setCodigo($id);	

			$dao = new CaixaDAO();
			
			$dao->deletar($caixa);
				
		break;
		
		case 'lista':
					
			$dao = new CaixaDAO();
			$vet = $dao->ListaCaixaUltimosInseridos();
			$num = count($vet);	
			$results = array();
				
			for($i = 0; $i < $num; $i++){
				
				$caixa = $vet[$i];
				
				$codigo = $caixa->getCodigo();
				$data   = $caixa->getData();
				$doc    = $caixa->getDocumento();
				$hist   = $caixa->getHistorico();
				$tipo   = $caixa->getTipo();
				$valor  = $caixa->getValor();
				$iddep  = $caixa->getIdDep();
				
				$daod = new DepartamentoDAO();
				$vetd = $daod->ListaDepartamentoUm($iddep);
				$numd = count($vetd);
				
				if($numd > 0){					
					$dep    = $vetd[0];					
					$nome   = $dep->getNome();
				}else{
					$nome   = "";
				}
				
				
				array_push($results, array(
					'codigo' => ''.$codigo.'',
					'data' => ''.implode("/", array_reverse(explode("-", "".$data.""))).'',
					'doc'=>''.$doc.'',
					'hist'=>''.$hist.'',
					'tipo'=>''.$tipo.'',
					'valor'=>''.number_format($valor,2,',','.').'',
					'nome'=>''.$nome.'',
					'iddep'=>''.$iddep.'',
				));	
				
				
			}
			
			echo (json_encode($results));	

		break;	
				
		}

	}
?>