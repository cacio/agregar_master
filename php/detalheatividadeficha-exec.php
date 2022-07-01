<?php

	require_once('../inc/inc.autoload.php');

	

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

	

		

		$act = $_REQUEST['act'];

		

		switch($act){

			

		case 'inserir':
				
				($_REQUEST['codficha']) 		 ? $codficha 		 = $_REQUEST['codficha']   		:false;
				($_REQUEST['codatividade'])  	 ? $codatividade  	 = $_REQUEST['codatividade']    :false;
				($_REQUEST['nomeatividade'])  	 ? $nomeatividade  	 = $_REQUEST['nomeatividade']   :false;
				($_REQUEST['tivano'])  	 		 ? $tivano  		 = $_REQUEST['tivano']  		:false;
				
				
				$dao = new DetalheAtividadeFichaDAO();
				$vet = $dao->proximoid();
				$prox = $vet[0];			
				$proximoid = $prox->getProxid();
				$result = array();
				
				
				$dtf = new DetalheAtividadeFicha();
				
				$dtf->setCodFicha($codficha);
				$dtf->setCodAtividade($codatividade);
				$dtf->setAno($tivano);
				
				$dao->inserir($dtf);
				
				$result[] = array(
						'proximoid'=>''.$proximoid.'',	
						'nomeatividade'=>''.$nomeatividade.'',
						'codatividade'=>''.$codatividade.'',
						'tivano'=>''.$tivano.'',																																								
					);
				echo(json_encode($result));
		break;

	
		

		case 'delete':

	

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;

	
			 $id  = $_REQUEST['id'];
	 		 $result = array();	
		
		
			foreach($id as  $key=>$value){
				
				$dtf = new DetalheAtividadeFicha();	
				$dtf->setCodigo($value);
				
				$dao = new DetalheAtividadeFichaDAO();	
				$dao->deletar($dtf);
				
				$result[] = array(
					'id'=>''.$value.'',																																								
				);			
			}
			echo(json_encode($result));
		break;

		

		}

	

	

	}

	

?>