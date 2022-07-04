<?php
	require_once('../inc/inc.autoload.php');
	session_start();
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){

			case 'recuperar':
				
				$ma  = $_REQUEST['emailrest'];
				
				$dao    = new UsuarioDAO();
				$vet    = $dao->VerificaEmail($ma);
				$num    = count($vet);
				$result = array();

				if($num > 0){

					$usu = $vet[0];

					$cod   = $usu->getCodigo();									
					$sen   = $usu->getSenha();
					$ema   = $usu->getEmail();
					$nom   = $usu->getNome();

					$chave = sha1($cod.$sen);

					$dados = array(
						'SMTPAuth'=>true,
						'SMTPSecure'=>'ssl',
						'Host'=>'smtp.gmail.com',
						'Port'=>465,
						'Username'=>'',
						'Password'=>'',		
					);


					$daoe = new EmailDAO($dados);

					$std = new stdClass();

					$std->mensagem    = "Ola, Para redefinir sua senha clique no link abaixo";
					$std->titulo      = "Redefinir senha agregar";
					$std->nome 		  = "{$nom}";
					$std->assinatura  = "agregar";
					$std->data        = date('d/m/Y');
					$std->url         = "http://localhost/projetos/agregar_master/php/redefinirsenha.php?token={$chave}";
					$std->email       = "caciorenato@gmail.com";
					$std->msgretorno  = "REDEFINAÇÃO DE SENHA ENVIADO COM SUSSESO!";
					$std->txt_btn     = "REDEFINIR SENHA";

				 	$return =  $daoe->mandaEmail($std);

				 	//print_r($return);
				 	echo json_encode($return);
				}else{

					array_push($result, array('mensagem'=>'E-Mail não existe tente novamente ou fazer um contato com o agregar Obrigado!','tipo'=>'2'));	
					echo json_encode($result);
				}


			break;
			case 'redefinir':
				
				$token  		= $_POST['token'];
				$email  		= $_POST['email'];
				$senha  		= $_POST['passw'];
				$senhaconfitma  = $_POST['passw-conf'];
				$result         = array();

				if($senha == $senhaconfitma){
					$dao    = new UsuarioDAO();
					$vet    = $dao->VerificaEmail($email);
					$num    = count($vet);

					if($num > 0){

						$usu = $vet[0];

						$cod   = $usu->getCodigo();									
						$sen   = $usu->getSenha();
						$ema   = $usu->getEmail();
						$nom   = $usu->getNome();

						$chave = sha1($cod.$sen);

						if($token == $chave){

							//tudo certo;

							$usuario = new Usuario();
							$usuario->setCodigo($cod);
							$usuario->setSenha(sha1($senha));

							$dao->updatesenha($usuario);	

							array_push($result, array(
								'msg'=>'Senha alterada com sucesso!',
								'tipo'=>'1',	
							));

						}else{
							array_push($result, array(
								'msg'=>'Token não confere !',
								'tipo'=>'2',	
							));
						}


					}else{
						array_push($result, array(
								'msg'=>'Esse E-Mail não existe!',
								'tipo'=>'2',	
						));
					}
				}else{
					array_push($result, array(
						'msg'=>'Senhas não conferem',
						'tipo'=>'2',	
					));
				}

			echo json_encode($result);

			break;		
		}

	}	

?>