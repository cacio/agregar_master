<?php

	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];
		
		switch($act){
			
			
			
			case 'save':

				$res		   = array();
				$instrucoes1   = filter_input(INPUT_POST, 'vr', FILTER_SANITIZE_STRING);
				$vrtxt         = filter_input(INPUT_POST, 'vrtxt', FILTER_SANITIZE_STRING);			
				$abtxt		   = filter_input(INPUT_POST, 'abtxt', FILTER_SANITIZE_STRING);	
				$abmanual	   = filter_input(INPUT_POST, 'abmanual', FILTER_SANITIZE_STRING);	
				$radio		   = filter_input(INPUT_POST, 'radio');
				$radioabate    = filter_input(INPUT_POST, 'radioabate');
				$tipoabate     = filter_input(INPUT_POST, 'tppt');
				$tipoabatetxt  = filter_input(INPUT_POST, 'radioabatetxt');

				$notas = array(
					'vivorend'=>''.$instrucoes1.'',					
					'vivorendtxt'=>''.$vrtxt.'',
				);

				$aConfig = array(    				
    				'notas' => $notas,					
					'abtxt'=>$abtxt,
					'abmanual'=>$abmanual,
					'apuracao'=>$radio,
					'abate'=>$radioabate,
					'tppt'=>$tipoabate, 
					'abatetxt'=>$tipoabatetxt,    				
				);

				$content  = json_encode($aConfig);				
				$filePath = '../arquivos/'.$_SESSION['cnpj'].'/config.json';

				 if (! file_put_contents($filePath, $content)) {
					// echo "erro ao gravar aquivo";
					 array_push($res,array(
						'titulo'=>"Mensagem da configuração",
						'mensagem'=>"erro ao gravar aquivo",
						'url'=>'config.php'	,
						'tipo'=>'2'
					));
				 }else{

					array_push($res,array(
						'titulo'=>"Mensagem da configuração",
						'mensagem'=>"Gravado com sucesso!",
						'url'=>'admin.php'	,
						'tipo'=>'1'
					));
				 }				
				 
				 
				 $re      = json_encode($res);
				 $reponse = urlencode($re);

				 $destino = "response.php?mg={$reponse}";
				 header("Location:{$destino}");
				 
			break;
			case 'saveg':

				$res		   = array();
				$nomeemail     = filter_input(INPUT_POST, 'nomemail', FILTER_SANITIZE_STRING);				
				$smtpsegure    = filter_input(INPUT_POST, 'smtpsegure', FILTER_SANITIZE_STRING);
				$host          = filter_input(INPUT_POST, 'host', FILTER_SANITIZE_STRING);
				$port          = filter_input(INPUT_POST, 'port', FILTER_SANITIZE_STRING);
				$username      = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
				$senhaem       = filter_input(INPUT_POST, 'senhaem', FILTER_SANITIZE_STRING);
				$titulo        = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_STRING);
				$mensagem      = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_SPECIAL_CHARS);

				$mail = array(
					'nomeemail'=>''.$nomeemail.'',
					'smtpsegure'=>''.$smtpsegure.'',
					'host'=>''.$host.'',
					'port'=>''.$port.'',
					'username'=>''.$username.'',
					'senhaem'=>''.$senhaem.'',	
				);

				$tela = array(
					'titulo'=>''.$titulo.'',
					'corpo'=>''.$mensagem.'',
				);

				$aConfig = array(    				
					'emails' => $mail,
					'tela'=>$tela										
				);

				$content  = json_encode($aConfig);				
				$filePath = '../arquivos/config.json';

				if (! file_put_contents($filePath, $content)) {
					// echo "erro ao gravar aquivo";
					 array_push($res,array(
						'titulo'=>"Mensagem da configuração",
						'mensagem'=>"erro ao gravar aquivo",
						'url'=>'ConfigGeral.php'	,
						'tipo'=>'2'
					));
				 }else{

					array_push($res,array(
						'titulo'=>"Mensagem da configuração",
						'mensagem'=>"Gravado com sucesso!",
						'url'=>'admin.php'	,
						'tipo'=>'1'
					));
				 }				
				 
				 
				 $re      = json_encode($res);
				 $reponse = urlencode($re);

				 $destino = "response.php?mg={$reponse}";
				 header("Location:{$destino}");
			break;	
		}

	}

?>