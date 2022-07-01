<?php

	require_once('../inc/inc.autoload.php');
	session_start();

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];	

		switch($act){
			
			case 'inserir':

			$nome = !empty($_REQUEST['nome']) ? $_REQUEST['nome'] : '';

            $forma =  new FormaPagto();
			
            $forma->setNome($nome);
            $forma->setIcon('');
                
            $dao = new FormaPagtoDAO();
            $dao->Gravar($forma);

			header('Location:formapagto.php');					

		break;

		case 'alterar':		

			$id   = !empty($_REQUEST['id']) ? $_REQUEST['id'] :false;			
            $nome = !empty($_REQUEST['nome']) ? $_REQUEST['nome'] : '';

            $forma =  new FormaPagto();

			$forma->setCodigo($id);
            $forma->setNome($nome);
            $forma->setIcon('');
                
            $dao = new FormaPagtoDAO();
            $dao->Alterar($forma);

			header('Location:formapagto.php');
				
		break;

		case 'delete':

			$id   = !empty($_REQUEST['id']) ? $_REQUEST['id'] :false;
			
            $forma =  new FormaPagto();

			$forma->setCodigo($id);

            $dao = new FormaPagtoDAO();

            $dao->Remover($forma);

		break;	
        case 'busca':
            
            $dao = new FormaPagtoDAO();
            $vet = $dao->ListaFormaPagamento();
            $num = count($vet);
            $res = array();

            for ($i=0; $i < $num; $i++) { 
                
                $forma = $vet[$i];

                $codigo =  $forma->getCodigo();
                $nome   =  $forma->getNome();

                array_push($res, array(
                    'codigo'=>$codigo,
                    'nome'=>$nome,
                ));

            }

            echo json_encode($res);
            
        break;

			
		}

	}

	

	//header('Location:'.$destino);

?>