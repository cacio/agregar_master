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
				
				
				$doc =  new Documentos();
				
				$doc->setNome(addslashes($nome));
				
				$dao = new DocumentosDAO();
				$dao->inserir($doc);
				
				echo "Inserido com sucesso!";
				header('Location:../php/lista-documentos.php');
				
			break;
			case 'alterar':
					
				$id  = $_REQUEST['id'];							
				
				if(empty($_REQUEST['nome'])){
					$nome = "";
				}else{
					$nome = $_REQUEST['nome'];
				}
				
				$doc =  new Documentos();
				
				$doc->setCodigo($id);
				$doc->setNome(addslashes($nome));
				
				$dao = new DocumentosDAO();
				$dao->update($doc);
				
				echo "Alterado com sucesso!";
				header('Location:../php/lista-documentos.php');
			break;
			case 'delete':
				
				$id  = $_REQUEST['id'];	
				
				$daom = new ModalidadeDocumentoDAO();
				$vetm = $daom->VerificaDocumentos($id);
				$numm = count($vetm);
				
				
				for($i = 0;$i < $numm; $i++){
					
					$mod = $vetm[$i];	
					
					$id_moddoc	   = $mod->getCodigo();	
				
					$md = new ModalidadeDocumento();
					
					$md->setCodigo($id_moddoc);	
					
					$daom->deletar2($md);		
					
				}
				
				
				$doc =  new Documentos();
				
				$doc->setCodigo($id);
				
				$dao = new DocumentosDAO();
				$dao->deletar($doc);
				
			break;
			case 'lista':
			
				$id  = $_REQUEST['id'];				
				$dao = new DocumentosDAO();
				$vet = $dao->ListaDocumentosSelecionada($id);
				$num = count($vet);
				$results = array();
				
				for($i = 0; $i < $num; $i++){
					
					
					$doc = $vet[$i];
											
					$id_doc    = $doc->getCodigo();						
					$nome      = $doc->getNome();	
					
					$daom = new ModalidadeDocumentoDAO();
					$vetm = $daom->ListaDocumentosModalidadeSel($id,$id_doc);
					$numm = count($vetm);
					
					if($numm > 0){
						$sel   = 'selected';
					}else{
						$sel   = '';	
					}
					array_push($results, array(
							'id' => ''.$id_doc.'',									
							'nome' => ''.$nome.'',	
							'sel' => ''.$sel.'',		
					));					
					
				}
								
				echo (json_encode($results));
			
			break;
		}

	}
	

?>