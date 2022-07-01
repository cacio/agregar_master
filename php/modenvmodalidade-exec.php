<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
										
				$ids  = $_REQUEST['ids'];
				$idmd = $_REQUEST['idmod'];		
				
				
				$dao = new ModalidadeEnvModalidadeEmpDAO();
				$vet = $dao->ListaModalidadeEnvModalidade($idmd);
				$num = count($vet);
		 				
				for($i = 0; $i < $num; $i++){
					
					$mod = $vet[$i];
						
					$cod_mod = $mod->getCodigo();
					$id_mod  = $mod->getIdModalidade();
					
					$md = new ModalidadeEnvModalidadeEmp();
					
					$md->setCodigo($cod_mod);
					$md->setIdModalidade($id_mod);
					
					$dao->deletar($md);
					
				}
				
					


				foreach($ids as $key=>$value){
					
					
					$mds = new ModalidadeEnvModalidadeEmp();	
		
					$mds->setIdModalidade($idmd);
					$mds->setIdModalidadeEnv($value);
					if($value){				
						$dao->inserir($mds);	
					}
				}	
					

				echo "tudo certo";
				
			break;
			case 'verifica':
			
				$idmd = $_REQUEST['idmod'];	
				
				$dao = new ModalidadeDocumentoDAO();
				$vet = $dao->VerificaDocumentos($idmd);
				$num = count($vet);
				$results = array();
				
				for($i = 0;$i < $num; $i++){
					
					$mod = $vet[$i];	
					
					$idmodalidade  = $mod->getIdModalidade();
					$modalidade	   = $mod->getNomeModalidade();
					
					array_push($results, array(
							'idmodalidade' => ''.$idmodalidade.'',									
							'modalidade' => ''.$modalidade.'',							
					));
					
					
				}
				
				echo (json_encode($results));
				
			break;
			case 'verifica2':
			
				$iddoc = $_REQUEST['iddoc'];	
				
				$dao = new ModalidadeDocumentoDAO();
				$vet = $dao->VerificaDocumentos2($iddoc);
				$num = count($vet);
				$results = array();
				
				for($i = 0;$i < $num; $i++){
					
					$mod = $vet[$i];	
					
					$iddocumento   = $mod->getIdDocumento();
					$documento	   = $mod->getNomeDocumento();
					
					array_push($results, array(
							'iddocumento' => ''.$iddocumento.'',									
							'documento' => ''.$documento.'',							
					));
					
					
				}
				
				echo (json_encode($results));
				
			break;
			
			
			
		}

	}
	

?>