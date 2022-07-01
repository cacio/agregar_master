<?php

	

	require_once('../inc/inc.autoload.php');

	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		

		

		$act = $_REQUEST['act'];	

		

		switch($act){

			case 'inserir':
										
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				
				$env =  new ModalidadeEnv();
				
				$env->setNome(mysql_real_escape_string($nome));
				
				$dao = new ModalidadeEnvDAO();
				$dao->inserir($env);
				
				echo "Inserido com sucesso!";
				header('Location:../php/lista-modalidadeenvio.php');
				
			break;
			case 'alterar':
					
				$id  = $_REQUEST['id'];							
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				$env =  new ModalidadeEnv();
				
				$env->setCodigo($id);
				$env->setNome(mysql_real_escape_string($nome));
				
				$dao = new ModalidadeEnvDAO();
				$dao->update($env);
				
				echo "Alterado com sucesso!";
				header('Location:../php/lista-modalidadeenvio.php');
			break;
			case 'delete':
				
				$id  = $_REQUEST['id'];	
				
				$daom = new ModalidadeDocumentoDAO();
				$vetm = $daom->VerificaDocumentos2($id);
				$numm = count($vetm);
				
				
				for($i = 0;$i < $numm; $i++){
					
					$mod = $vetm[$i];	
					
					$id_moddoc	   = $mod->getCodigo();	
				
					$md = new ModalidadeDocumento();
					
					$md->setCodigo($id_moddoc);	
					
					$daom->deletar2($md);		
					
				}
				
				
				$env =  new ModalidadeEnv();
				
				$env->setCodigo($id);
				
				$dao = new ModalidadeEnvDAO();
				$dao->deletar($env);
				
			break;
			case 'lista':
			
				$id  = $_REQUEST['id'];				
				$dao = new ModalidadeEnvDAO();
				$vet = $dao->ListaModalidadeEnvSelecionada($id);
				$num = count($vet);
				$results = array();
				
				for($i = 0; $i < $num; $i++){
					
					
					$env = $vet[$i];
											
					$id_env    = $env->getCodigo();						
					$nome      = $env->getNome();	
					
					$daom = new ModalidadeEnvModalidadeEmpDAO();
					$vetm = $daom->ListaModalidadeEnvModalidadeSel($id,$id_env);
					$numm = count($vetm);
					
					if($numm > 0){
						$sel   = 'selected';
					}else{
						$sel   = '';	
					}
					array_push($results, array(
							'id' => ''.$id_env.'',									
							'nome' => ''.$nome.'',	
							'sel' => ''.$sel.'',		
					));					
					
				}
								
				echo (json_encode($results));
			
			break;
		}

	}
	

?>