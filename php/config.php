<?php
	
	require_once('../inc/inc.autoload.php');
	
	$tpl = new TemplatePower('../tpl/_master.htm');
	$tpl->assignInclude('conteudo','../tpl/config.htm');
	$tpl->prepare();
	
	/**************************************************************/
		
		require_once('../inc/inc.session.php');
		require_once('../inc/inc.menu.php');
		
		$tpl->assign('log',$_SESSION['login']);
		$pathFile      = '../arquivos/'.$_SESSION['cnpj'].'/config.json';
		$configJson    = file_get_contents($pathFile);
		$installConfig = json_decode($configJson);

			
		$atvexcel	   = sha1($_SESSION['id_emp'].''.$_SESSION['cnpj'].''.$_SESSION['inscemp']);
			
		$sel  ="";
		$sel1 ="";
		if($installConfig->notas->vivorend == 'V'){
			$sel  ="selected";
		}else if($installConfig->notas->vivorend == 'R'){
			$sel1 ="selected";
		}

		$tpabtcheck  = "";
		$tpabtcheck2 = "";
		
		if(!empty($installConfig->tppt)){
			if($installConfig->tppt == 'P'){
				$tpabtcheck  = "selected";
			}else if($installConfig->tppt == 'T'){
				$tpabtcheck2 = "selected";
			}
		}

		$tpl->assign('tpabtcheck',$tpabtcheck);
		$tpl->assign('tpabtcheck2',$tpabtcheck2);

		$tpabtchecktxt  = "";
		$tpabtcheck2txt = "";
		
		if(!empty($installConfig->tppttxt)){
			if($installConfig->tppttxt == 'P'){
				$tpabtchecktxt  = "selected";
			}else if($installConfig->tppttxt == 'T'){
				$tpabtcheck2txt = "selected";
			}
		}

		$tpl->assign('tpabtchecktxt',$tpabtchecktxt);
		$tpl->assign('tpabtcheck2txt',$tpabtcheck2txt);


		$abtxt 	   = "";
		$abmanual  = "";
		if($installConfig->abtxt == 'on'){
			$abtxt = "checked";
		}

		if($installConfig->abmanual == 'on'){
			$abmanual = "checked";
		}

		$tpl->assign('sel',$sel);
		$tpl->assign('sel1',$sel1);
		
		$seltxt  ="";
		$sel1txt ="";
		
		if(!empty($installConfig->notas->vivorendtxt )){
			if($installConfig->notas->vivorendtxt == 'V'){
				$seltxt  ="selected";
			}else if($installConfig->notas->vivorendtxt == 'R'){
				$sel1txt ="selected";
			}
		}

		$tpl->assign('seltxt',$seltxt);
		$tpl->assign('sel1txt',$sel1txt);


		$apucheck  = "";
		$apucheck2 = "";
		if(!empty($installConfig->apuracao)){
			if($installConfig->apuracao == '1'){
				$apucheck  = "checked";
			}else{
				$apucheck2  = "checked";
			}
		}
		
		$tpl->assign('apucheck',$apucheck);
		$tpl->assign('apucheck2',$apucheck2);

		$tpl->assign('atvexcel',$atvexcel);
		$tpl->assign('abmanual',$abmanual);
		$tpl->assign('abtxt',$abtxt);
		
		$abatcheck  = "";
		$abatcheck2 = "";
		$abatcheck3 = "";
		if(!empty($installConfig->abate)){
			if($installConfig->abate == '1'){
				$abatcheck  = "checked";
			}else if($installConfig->abate == '2'){
				$abatcheck2  = "checked";
			}else if($installConfig->abate == '3'){
				$abatcheck3  = "checked";
			}
		}
		
		$tpl->assign('abatcheck',$abatcheck);
		$tpl->assign('abatcheck2',$abatcheck2);
		$tpl->assign('abatcheck3',$abatcheck3);
		
		$abatchecktxt  = "";
		$abatcheck2txt = "";

		if(!empty($installConfig->abatetxt)){
			if($installConfig->abatetxt == '1'){
				$abatchecktxt  = "checked";
			}else if($installConfig->abatetxt == '2'){
				$abatcheck2txt  = "checked";
			}
		}else{
			$abatcheck2txt  = "checked";
		}
		
		$tpl->assign('abatchecktxt',$abatchecktxt);
		$tpl->assign('abatcheck2txt',$abatcheck2txt);
		

	/**************************************************************/
	$tpl->printToScreen();

?>