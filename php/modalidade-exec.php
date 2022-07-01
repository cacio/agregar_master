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
				
				
				$mod =  new Modalidade();
				
				$mod->setNome(mysql_real_escape_string($nome));
				
				$dao = new ModalidadeDAO();
				$dao->inserir($mod);
				
				echo "Inserido com sucesso!";
				header('Location:../php/lista-modalidade.php');
				
			break;
			case 'alterar':
					
				$id  = $_REQUEST['id'];							
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				$mod =  new Modalidade();
				
				$mod->setCodigo($id);
				$mod->setNome(mysql_real_escape_string($nome));
				
				$dao = new ModalidadeDAO();
				$dao->update($mod);
				
				echo "Alterado com sucesso!";
				header('Location:../php/lista-modalidade.php');
			break;
			case 'delete':
				
				$id  = $_REQUEST['id'];	
				
				
				$daom = new ModalidadeDocumentoDAO();
				$vetm = $daom->VerificaDocumentos2($id);
				$numm = count($vetm);
				$results = array();
				
				for($i = 0;$i < $numm; $i++){
					
					$mod = $vetm[$i];	
					
					$id_moddoc	   = $mod->getCodigo();	
				
					$md = new ModalidadeDocumento();
					
					$md->setCodigo($id_moddoc);	
					
					$daom->deletar2($md);											
					
				}
				
				
				$mod =  new Modalidade();
				
				$mod->setCodigo($id);
				
				$dao = new ModalidadeDAO();
				$dao->deletar($mod);
				
			break;
			
		}

	}
	

?>