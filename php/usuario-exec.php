<?php

	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

		case 'inserir':

			$nome   = !empty($_REQUEST['nome'])   ? $_REQUEST['nome']   : "";
			$email  = !empty($_REQUEST['email'])  ? $_REQUEST['email']  : "";
			$login  = !empty($_REQUEST['login'])  ? $_REQUEST['login']  : "";			
			$senha  = !empty($_REQUEST['senha'])  ? $_REQUEST['senha']  : "";
			$sys    = !empty($_REQUEST['sys'])    ? $_REQUEST['sys']    : "";
			$id_emp = !empty($_REQUEST['id_emp']) ? $_REQUEST['id_emp'] : "";			

			$user = new Usuario();
			$dao  = new UsuarioDAO();
			$vetu      = $dao->proximoid();
			$prox      = $vetu[0];	
			$proximoid = $prox->getProxid();					
		
			foreach($_REQUEST['perusers'] as $key=>$value){
		
					$per = explode(',',$value);
					$idmenu    = $per[0];
					$idsubmenu = $per[1];					 
					$per = new Permissoes();
					
					$per->setIdmenu($idmenu);
					$per->setIdsubmenu($idsubmenu);
					$per->setIdusuario($proximoid);
				
					$daop = new PermissoesDAO();
					$daop->inserirpermissao($per);
			}

			$user->setNome($nome);
			$user->setEmail($email);
			$user->setLogin($login);
			$user->setSenha(sha1($senha));
			$user->setIdRota(1);
			$user->setIdsys($sys);
			$user->setIdEmpresa($id_emp);

			$dao->inserir($user);

			

		    echo 'Adicionado com sucesso !';

			

			header('Location:Listar-usuario.php');	

							

		break;

		case 'alterar':

			
			$id		  = !empty($_REQUEST['id'])		  ? $_REQUEST['id']			 : ""; 
			$nome	  = !empty($_REQUEST['nome'])	  ? $_REQUEST['nome']		 : "";
			$email	  = !empty($_REQUEST['email'])	  ? $_REQUEST['email']		 : "";
			$login	  = !empty($_REQUEST['login'])	  ? $_REQUEST['login']		 : "";
			$codigos  = !empty($_REQUEST['id']) 	  ?	$_REQUEST['id']			 : 0;
			$operador = !empty($_REQUEST['sys'])	  ? $_REQUEST['sys'] 	     : 0;	
			$senha	  = !empty($_REQUEST['senha'])    ? sha1($_REQUEST['senha']) : "";
			//deletar todos as permissoes desse usuario


			$daoper = new PermissoesDAO();
			$vetper = $daoper->listapermissaousuario($id);
			$numper = count($vetper);			

			for($i =0; $i < $numper; $i++){				

				$pers =	$vetper[$i];

				$idpermi   = $pers->getCodigo();
				$iduser    = $pers->getIdusuario();

				$per = new Permissoes();
				$per->setCodigo($idpermi);
				$per->setIdusuario($id);

				$daoper->deleteporuser($per);

			}		

			foreach($_REQUEST['perusers'] as $key=>$value){

					$per = explode(',',$value);

					$idmenu    = $per[0];
					$idsubmenu = $per[1];

					$per = new Permissoes();

					$per->setIdmenu($idmenu);
					$per->setIdsubmenu($idsubmenu);
					$per->setIdusuario($id);				

					$daop = new PermissoesDAO();

					$daop->inserirpermissao($per);

			}


			$user = new Usuario();
			
			$user->setCodigo($id);
			$user->setNome($nome);
			$user->setEmail($email);
			$user->setLogin($login);
			$user->setSenha($senha);
			$user->setIdRota(1);
			$user->setIdsys($operador);

			$dao = new UsuarioDAO();

			$dao->update($user);



		echo 'Alterado com sucesso !';	

		header('Location:Listar-usuario.php');

		

		break;

		

		case 'delete':

	

			($_REQUEST['id'])            ? $id            = $_REQUEST['id']               :false;

			$user = new Usuario();
		

			$user->setCodigo($id);

			

			$dao = new UsuarioDAO();

			$dao->deletar($user);

				

		echo 'Removido com sucesso !';

		

		break;

		

		}

	

	

	}

	

?>