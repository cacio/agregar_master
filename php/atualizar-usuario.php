<?php
	require_once('../inc/inc.autoload.php');

	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/atualizar-usuario.htm');
	$tpl->prepare();

	

	/**************************************************************/	

		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		/*require_once('../inc/inc.permissao.php');*/		
		$tpl->assign('log',$_SESSION['login']);
		($_REQUEST['id'])  ? $id  = $_REQUEST['id']  :false;		
		
		if(empty($_REQUEST['id'])){
			header('Location:admin.php');
		}		
		
		$dao = new UsuarioDAO();
		$vet = $dao->listausuarioum($id);

		$num = count($vet);

		if($num > 0){

			$user = $vet[0];

			$cod  = $user->getCodigo();
			$nom  = $user->getNome();
			$ema  = $user->getEmail();
			$cnpj = $user->getCnpj();
			$use  = empty(preg_replace("/[^0-9]/", "", $user->getLogin())) ? $cnpj : $user->getLogin();
			$ids  = $user->getIdsys();
			$ide  = $user->getIdEmpresa();			
			$sel1 = '';
			$sel2 = '';

			if($ids == 1){
				$adm = "Administrador";
				$sel1 = 'selected';
			}else{
				$adm = "Usuário";
				$sel2 = 'selected';
			}

			$tpl->assign('cod',$cod);
			$tpl->assign('nom',$nom);
			$tpl->assign('ema',$ema);
			$tpl->assign('login',$use);			
			$tpl->assign('adm',$adm);
			$tpl->assign('ids',$ids);

			$tpl->assign('sel1',$sel1);
			$tpl->assign('sel2',$sel2);
			
			$daoe = new EmpresasDAO();
			$vete = $daoe->ListaEmpresaSelecionada($ide);
			$nume = count($vete);
			
			for($y = 0; $y < $nume; $y++){
				
			
				$emp = $vete[$y];
				
				
				$code 		    = $emp->getCodigo();
				$razao_social   = $emp->getRazaoSocial();
				$fantasia	    = $emp->getFantasia();
				$seleciona		= $emp->getSeleciona();
							
				$tpl->newBlock('listaempresa');
				
				
				$tpl->assign('code',$code);			
				$tpl->assign('razao_social',$razao_social);
				$tpl->assign('fantasia',$fantasia);
				$tpl->assign('seleciona',$seleciona);			
									
		
			}
			
		}

		

		$dao = new MenuDAO();
		$vet = $dao->ListaMenuCadastroUsuario($ids);
		$num = count($vet);

		for($i = 0; $i < $num; $i++){

			$user = $vet[$i];

			$cod    = $user->getCodigo();
			$nom    = $user->getNome();
			$link   = $user->getLink();
			$icone  = $user->getIcone();
			$numtp  = $user->getNumTp();

			if($numtp > 0){
				$tpl->newBlock('menus');
				
				$tpl->assign('cod',$cod);
				$tpl->assign('nom',$nom);
				$tpl->assign('link',$link);
				$tpl->assign('icone',$icone);
			}
			$daos = new SubmenuDAO();
			$vets = $daos->listasubmenuUm($cod);
			$nums = count($vets);

			$checked = '';

			for($x = 0; $x < $nums; $x++){

				$sub = $vets[$x];

				$cods    = $sub->getCodigo();
				$noms    = $sub->getNome();
				$links   = $sub->getLink();
				$icones  = $sub->getIcone();
				$idmenu  = $sub->getIdmenu();
				$idtipo	 = $sub->getIdTipo();

				$daop = new PermissoesDAO();
				$vetp = $daop->listapermissaoporusuario($id,$cods);
				$nump = count($vetp);

				if($nump > 0){

					$per = $vetp[0];

					$idsubmenu = $per->getIdsubmenu();		

					$checked = 'checked';

				}else{

					$checked = "";	

				}

				if($idtipo == $ids){				

					$tpl->newBlock('submenus');


					$tpl->assign('checked',$checked);
					$tpl->assign('cods',$cods);
					$tpl->assign('noms',$noms);
					$tpl->assign('links',$links);
					$tpl->assign('idmenu',$idmenu);
					$tpl->assign('icones',$icones);
				}

			}

		}

	/**************************************************************/

	$tpl->printToScreen();
?>