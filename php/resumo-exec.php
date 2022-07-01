<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'listaum':

				$id  	= $_POST['id'];
				$dao 	= new ResumoDAO();
				$vet 	= $dao->ListaResumoEmpresaUm($id,$_SESSION['cnpj']);
				$num 	= count($vet);
				$result = array();	

				if($num > 0){

					$resu           = $vet[0];
						
					$competenc  	= $resu->getCompetenc();
					$bovinos		= $resu->getBovinos();
					$bubalinos  	= $resu->getBubalinos();
					$ovinos			= $resu->getOvinos();
					$icmsnor 		= $resu->getIcmsNor();
					$substit		= $resu->getSubstit();
					$creditoent 	= $resu->getCreditoEnt();
					$creditosrs 	= $resu->getCreditosRS();
					$creditosoe 	= $resu->getCreditosOE();
					$baseent    	= $resu->getBaseEnt();
					$basesairs		= $resu->getBaseSaiRS();
					$basesaioe  	= $resu->getBaseSaiOE();
					$numefunc		= $resu->getNumeroFuncionario();
					$valorfolha		= $resu->getValorFolha();
					$datapagto		= $resu->getDataPagto();
					$basesairs4 	= $resu->getBaseSaiRS4();
					$criditosr4 	= $resu->getCriditosR4();
					$creditosr4 	= $resu->getCreditosR4();
					$cnpjemp    	= $resu->getCnpjEmp();
					$numero_entrada = $resu->getNumeroEntrada();
					$numero_saida   = $resu->getNumeroSaida();
					$numcabeca		= $bovinos + $bubalinos + $ovinos;

					array_push($result, array(
						'numero_entrada'=>''.$numero_entrada.'',
						'numero_saida'=>''.$numero_saida.'',
						'creditoent'=>''.number_format($creditoent,2,',','.').'',
						'numcabeca'=>''.$numcabeca.'',
					));	


				}

				echo json_encode($result);	

			break;	
										
		}
	}

?>