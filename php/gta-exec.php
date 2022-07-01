<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'inserir':

				$nota  = !empty($_REQUEST['nota']) ? $_REQUEST['nota'] : '';
				$gta   = !empty($_REQUEST['gta'])  ? $_REQUEST['gta']  : ''; 

				$gtatxt = new GtaTxt();
				
				$gtatxt->setNumeroNota($nota);
				$gtatxt->setDataEmissao(date('Y-m-d'));		
				$gtatxt->setNumeroGta($gta);						
				$gtatxt->setCnpjEmp($_SESSION["cnpj"]);

				$dao  = new 	GtaTxtDAO();
				$vet  = $dao->proximoid();
				$prox = $vet[0];

				$cod = $prox->getProxId();	

				$dao->inserirgta($gtatxt);	

				$result = array();	

				array_push($result, array(
					'cod'=>''.$cod.'',
					'gta'=>''.$gta.'',
					'nota'=>''.$nota.'',
				));	

				echo json_encode($result);	

			break;	
			
			case 'delete':

				($_REQUEST['cod'])     ? $id    = $_REQUEST['cod']               :false;

				$gtatxt = new GtaTxt();

				$gtatxt->setCodigo($id);

				$dao  = new GtaTxtDAO();

				$dao->deletar($gtatxt);

				$result = array();

				array_push($result, array(
					'msg'=>'removido'
				));

				echo json_encode($result);

			break;
		
			case 'listar':

				$numero = $_REQUEST['numero'];

				$dao = new GtaTxtDAO();
				$vet = $dao->GtaEmpresas($numero,$_SESSION["cnpj"]);	
				$num = count($vet);
				$result = array();

				for($i = 0; $i < $num; $i++){

					$gtatxt 	 = $vet[$i];					

					$cod 		 = $gtatxt->getCodigo();
					$numero_nota = $gtatxt->getNumeroNota();			
					$numero_gta	 = $gtatxt->getNumeroGta();	

					array_push($result, array(
						'cod'=>''.$cod.'',
						'numero_nota'=>''.$numero_nota.'',
						'numero_gta'=>''.$numero_gta.'',
					));	

				}

				echo json_encode($result);	

			break;					
				
		}
	}

?>