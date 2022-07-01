<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'busca':

			$p        = $_REQUEST['term'];			
			$cnpj_emp = $_SESSION['cnpj'];
				
			$dao    = new EmpresasTxtDAO();
			$vet    = $dao->BuscaEmpresas($p,$cnpj_emp); 
			$num    = count($vet);	
			$result = array();
				
			for($i = 0; $i < $num; $i++){
				
				$emptxt = $vet[$i];
				
				$cod	  = $emptxt->getCodigo();
				$cnpj_cpf = $emptxt->getCnpjCpf();
				$insc_est = $emptxt->getInscEstadual();
				$razao	  = $emptxt->getRazao();
				$cidade	  = $emptxt->getCidade();
				$uf		  = $emptxt->getUf();
				$tipo	  = $emptxt->getTipo();
				$cnpjemp  = $emptxt->getCnpjEmp();
				
				
				array_push($result, array(
						'label'=>''.$cod.' - '.trim($razao).'',
						'value' => ''.trim($razao).'',
						'razao' => ''.trim($razao).'',
						'cnpj_cpf'=>$cnpj_cpf,
						'cod' => ''.$cod.'',							
				));	
				
			}	
			
			echo (json_encode($result));
				
		break;
		
		}
	}

?>