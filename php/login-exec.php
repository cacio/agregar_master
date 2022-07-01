<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'login':
				
				$tipo = $_POST['options'];
				if($tipo == 'EMPRESA'){
					$ma   = preg_replace("/\D+/", "", $_POST['ema']);	
				}else{
					$ma   = addslashes($_POST['ema']);	
				}
				
				$sen  = addslashes($_POST['sen']);

				$sen = sha1($sen);

				$dao = new UsuarioDAO();
				$vet = $dao->listaLogin($ma,$sen);
				$num = count($vet);
				
				if($num > 0){

					$usu = $vet[0];

					$_SESSION['login']   = $usu->getNome();
					$_SESSION['coduser'] = $usu->getCodigo();
					$_SESSION['idsys']   = $usu->getIdsys();
					$_SESSION['id_emp']  = $usu->getIdEmpresa();
					$_SESSION['cnpj']    = $usu->getCnpj();
					$_SESSION['email']   = $usu->getEmail();
					$_SESSION['inscemp'] = $usu->getInscEmp();
					
					$dir  = "../arquivos/";
			
					if(!is_dir("".$dir."".$_SESSION['cnpj']."")){				
						mkdir("".$dir."".$_SESSION['cnpj']."", 0777, true);
					}
					
					if(!is_dir("".$dir."".$_SESSION['cnpj']."/dbf")){
						mkdir("".$dir."".$_SESSION['cnpj']."/dbf", 0777, true);
					}
					if(!is_dir("".$dir."".$_SESSION['cnpj']."/importado")){
						mkdir("".$dir."".$_SESSION['cnpj']."/importado", 0777, true);						
					}
					if(!is_dir("".$dir."".$_SESSION['cnpj']."/removexml")){
						mkdir("".$dir."".$_SESSION['cnpj']."/removexml", 0777, true);						
					}
					
					if(!is_dir("".$dir."".$_SESSION['cnpj']."/xml")){
						mkdir("".$dir."".$_SESSION['cnpj']."/xml", 0777, true);
						mkdir("".$dir."".$_SESSION['cnpj']."/xml/entrada", 0777, true);
						mkdir("".$dir."".$_SESSION['cnpj']."/xml/inseridas", 0777, true);
					}
					
					$instrucoes1   = "V";			
					$nomeemail     = "";				
					$smtpsegure    = "";
					$host          = "";
					$port          = "";
					$username      = "";
					$senhaem       = "";
					
					$notas = array(
						'vivorend'=>''.$instrucoes1.'',					
						
					);

				
					$aConfig = array(    				
	    				'notas' => $notas,
						'abtxt'=>null,
						'abmanual'=>null ,
						'apuracao'=>"1",
						'abate'=>"1"    				
					);

					$content  = json_encode($aConfig);				
					$filePath = '../arquivos/'.$_SESSION['cnpj'].'/config.json';

					if(!file_exists($filePath)){
						 if (! file_put_contents($filePath, $content)) {
						 	echo "erro ao gravar aquivo";
						 }	
					}

					$destino = 'admin.php';					

				}else{
					
					if($tipo == 'EMPRESA'){	
						$_SESSION['inf'] = 'CNPJ ou senha, incorretos, verifique e tente novamente!';
						$destino = 'login.php';
					}else{
						$_SESSION['inf'] = 'Usuário ou senha, incorretos, verifique e tente novamente!';
						$destino = 'loginrestrito.php';						
					}
					
				}

			break;

			case 'logout':

				session_destroy();

				$destino = 'login.php';

			break;
		}

	}	

	header('Location:'.$destino);
?>