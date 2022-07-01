<?php

	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){	

		$act = $_REQUEST['act'];

		switch($act){

			case 'inserir':

				($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
				($_REQUEST['tprdep'])        ? $tprdep        = $_REQUEST['tprdep']           :false;	

				$dep = new Departamento();

				$dep->setNome($nome);
				$dep->setTipoDepartamento($tprdep);

				$dao = new DepartamentoDAO();

				$dao->inserir($dep);	

				header('Location:lista-departamento.php');					

			break;

			case 'alterar':		

				($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;
				($_REQUEST['nome'])          ? $nome          = $_REQUEST['nome']             :false;
				($_REQUEST['tprdep'])        ? $tprdep        = $_REQUEST['tprdep']           :false;

				$dep = new Departamento();

				$dep->setCodigo($id);
				$dep->setNome($nome);
				$dep->setTipoDepartamento($tprdep);

				$dao = new DepartamentoDAO();

				$dao->alterar($dep);	

				header('Location:lista-departamento.php');

			break;

			case 'delete':

				($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;

				$dep = new Departamento();

				$dep->setCodigo($id);

				$dao = new DepartamentoDAO();

				$dao->deletar($dep);	

				echo "Deletado com sucesso";		

			break;
			case 'busca':
				
				$q 		= !empty($_REQUEST['term']) ? $_REQUEST['term'] : '';
				
				$dao 	= new DepartamentoDAO();
				$vet 	= $dao->BuscaDepartamento($q);
				$num 	= count($vet);
				$result = array();
				
				for($i = 0; $i < $num; $i++){
					
					$dep = $vet[$i];
					
					$cod  = $dep->getCodigo();
					$nome = $dep->getNome();
					//$tipo_dep = $dep->getTipoDepartamento();
					
					
					array_push($result,array(
						'label' => ''.$cod.'-'.$nome.'',
						'value' => ''.$cod.'',
					));
					
					
				}
				
				echo (json_encode($result));				
			break;

		}

	}

?>