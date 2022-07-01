<?php
	session_start();
	require_once('../inc/inc.autoload.php');
	
	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){
	
		
		$act = $_REQUEST['act'];
		
		switch($act){
				
		case'veridicapermisao':
		
			($_REQUEST['id'])      ? $df = $_REQUEST['id']      :false;
			
			$dao = new PermissoesDAO();
			$vet = $dao->verificaper($_SESSION['coduser'],$df);
			$num = count($vet);
			
			$permi = array();
			

			if($num	!= 0){		
				
				$per = $vet[0];
					
				echo 'permissao';
			}
				
	
		
		break;
		
		
		}
	
	
	}
	
?>