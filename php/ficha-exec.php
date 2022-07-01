<?php

	require_once('../inc/inc.autoload.php');

	

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

	

		

		$act = $_REQUEST['act'];

		

		switch($act){

			

		case 'inserir':

				echo "<pre>";	
				print_r($_REQUEST);

			if(empty($_REQUEST['categoria'])){
				$categoria = 0;
			}else{
				$categoria = $_REQUEST['categoria'];
			}
			
			if(empty($_REQUEST['nome'])){
				$nome = "";
			}else{
				$nome = $_REQUEST['nome'];
			}
			
			if(empty($_REQUEST['dtnascimento'])){
				$dtnascimento = "";
			}else{
				$dtnascimento = implode("-", array_reverse(explode("-", "".$_REQUEST['dtnascimento']."")));
			}			
			
			if(empty($_REQUEST['dtexclu'])){
				$dtexclu = "";
				$ativo = 1;
			}else{
				$dtexclu = implode("-", array_reverse(explode("-", "".$_REQUEST['dtexclu']."")));
				$ativo = 2;
			}
				
			if(empty($_REQUEST['endereco'])){
				$endereco = "";
			}else{
				$endereco = $_REQUEST['endereco'];
			}
			
			if(empty($_REQUEST['numero'])){
				$numero = "";
			}else{
				$numero = $_REQUEST['numero'];
			}
			
			if(empty($_REQUEST['bairro'])){
				$bairro = "";
			}else{
				$bairro = $_REQUEST['bairro'];
				
			}
			if(empty($_REQUEST['cidade'])){
				$cidade = "";
			}else{
				$cidade = $_REQUEST['cidade'];
				
			}	
			
			if(empty($_REQUEST['telresid'])){
				$telresid = "";
			}else{
				$telresid = $_REQUEST['telresid'];
				
			}

			if(empty($_REQUEST['celular'])){
				$celular = "";
			}else{
				$celular = $_REQUEST['celular'];
				
			}
			
			if(empty($_REQUEST['recado'])){
				$recado = "";
			}else{
				$recado = $_REQUEST['recado'];
				
			}
			
			if(empty($_REQUEST['esde'])){
				$esde = "";
			}else{
				$esde = $_REQUEST['esde'];
				
			}
			
			if(empty($_REQUEST['obs'])){
				$obs = "";
			}else{
				$obs = $_REQUEST['obs'];
				
			}
			
			if(empty($_REQUEST['dtadmissao'])){
				$dtadmissao = "";
			}else{
				$dtadmissao = implode("-", array_reverse(explode("-", "".$_REQUEST['dtadmissao']."")));
				
			}
			
			if(empty($_REQUEST['rg'])){
				$rg = "";
			}else{
				$rg = preg_replace("/\D+/", "", $_REQUEST['rg']);
				
			}
			
			if(empty($_REQUEST['cpf'])){
				$cpf = "";
			}else{
				$cpf = preg_replace("/\D+/", "", $_REQUEST['cpf']);
				
			}
			
			if(empty($_REQUEST['vlmensal'])){
				$vlmensal = "0";
			}else{
				$vlmensal = str_replace(',', '.', str_replace('.', '', $_REQUEST['vlmensal']));
				
			}
			
			if(empty($_REQUEST['esde'])){
				$esde = "0";
			}else{
				$esde = $_REQUEST['esde'];
				
			}				
			
			if(empty($_REQUEST['email'])){
				$email = "";
			}else{
				$email = $_REQUEST['email'];
				
			}
			
			if(empty($_REQUEST['profissao'])){
				$profissao = "";
			}else{
				$profissao = $_REQUEST['profissao'];
				
			}
			
			if(empty($_REQUEST['cep'])){
				$cep = "";
			}else{
				$cep = $_REQUEST['cep'];
				
			}
			
			$ficha = new Ficha();
			
			$ficha->setNome($nome);
			$ficha->setEndereco($endereco);
			$ficha->setNumero($numero);
			$ficha->setBairro($bairro);
			$ficha->setCidade($cidade);
			$ficha->setTelefone($telresid);
			$ficha->setCelular($celular);
			$ficha->setRecados($recado);
			$ficha->setDataAdmissao($dtadmissao);
			$ficha->setRg($rg);	
			$ficha->setValorMensalidade($vlmensal);
			$ficha->setObs($obs);
			$ficha->setDataNascimento($dtnascimento);
			$ficha->setDataExclusao($dtexclu);
			$ficha->setCodCategoria($categoria);
			$ficha->setAtivo($ativo);
			$ficha->setCodEsde($esde);
			$ficha->setCpf($cpf);
			$ficha->setEmail($email);
			$ficha->setProfissao($profissao);
			$ficha->setCep($cep);
			
			$dao = new FichaDAO();
			$dao->inserir($ficha);
			
			
			header('Location:Listar-ficha.php');							
		break;

		case 'alterar':

		echo "<pre>";	
				print_r($_REQUEST);


			($_REQUEST['codficha'])            ? $codficha            = $_REQUEST['codficha']               :false;

			if(empty($_REQUEST['categoria'])){
				$categoria = 0;
			}else{
				$categoria = $_REQUEST['categoria'];
			}
			
			if(empty($_REQUEST['nome'])){
				$nome = "";
			}else{
				$nome = $_REQUEST['nome'];
			}
			
			if(empty($_REQUEST['dtnascimento'])){
				$dtnascimento = "";
			}else{
				$dtnascimento = implode("-", array_reverse(explode("-", "".$_REQUEST['dtnascimento']."")));
			}	
					
			if(empty($_REQUEST['dtexclu']) or $_REQUEST['dtexclu'] == "00-00-0000"){
				$dtexclu = "";
				$ativo = 1;
			}else{
				$dtexclu = implode("-", array_reverse(explode("-", "".$_REQUEST['dtexclu']."")));
				$ativo = 2;
			}
			
			if(empty($_REQUEST['endereco'])){
				$endereco = "";
			}else{
				$endereco = $_REQUEST['endereco'];
			}
			
			if(empty($_REQUEST['numero'])){
				$numero = "";
			}else{
				$numero = $_REQUEST['numero'];
			}
			
			if(empty($_REQUEST['bairro'])){
				$bairro = "";
			}else{
				$bairro = $_REQUEST['bairro'];
				
			}
			if(empty($_REQUEST['cidade'])){
				$cidade = "";
			}else{
				$cidade = $_REQUEST['cidade'];
				
			}	
			
			if(empty($_REQUEST['telresid'])){
				$telresid = "";
			}else{
				$telresid = $_REQUEST['telresid'];
				
			}

			if(empty($_REQUEST['celular'])){
				$celular = "";
			}else{
				$celular = $_REQUEST['celular'];
				
			}
			
			if(empty($_REQUEST['recado'])){
				$recado = "";
			}else{
				$recado = $_REQUEST['recado'];
				
			}
			
			if(empty($_REQUEST['esde'])){
				$esde = "";
			}else{
				$esde = $_REQUEST['esde'];
				
			}
			
			if(empty($_REQUEST['obs'])){
				$obs = "";
			}else{
				$obs = $_REQUEST['obs'];
				
			}
			
			if(empty($_REQUEST['dtadmissao'])){
				$dtadmissao = "";
			}else{
				$dtadmissao = implode("-", array_reverse(explode("-", "".$_REQUEST['dtadmissao']."")));
				
			}
			
			if(empty($_REQUEST['rg'])){
				$rg = "";
			}else{
				$rg = preg_replace("/\D+/", "", $_REQUEST['rg']);
				
			}
			
			if(empty($_REQUEST['vlmensal'])){
				$vlmensal = "0";
			}else{
				$vlmensal = str_replace(',', '.', str_replace('.', '', $_REQUEST['vlmensal']));
				
			}
			
			if(empty($_REQUEST['esde'])){
				$esde = "0";
			}else{
				$esde = $_REQUEST['esde'];
				
			}				
			
			if(empty($_REQUEST['cpf'])){
				$cpf = "";
			}else{
				$cpf = preg_replace("/\D+/", "", $_REQUEST['cpf']);
				
			}
			
			if(empty($_REQUEST['email'])){
				$email = "";
			}else{
				$email = $_REQUEST['email'];
				
			}
			
			if(empty($_REQUEST['profissao'])){
				$profissao = "";
			}else{
				$profissao = $_REQUEST['profissao'];
				
			}
			
			if(empty($_REQUEST['cep'])){
				$cep = "";
			}else{
				$cep = $_REQUEST['cep'];
				
			}
			
			
			$ficha = new Ficha();
			
			$ficha->setCodigo($codficha);
			$ficha->setNome($nome);
			$ficha->setEndereco($endereco);
			$ficha->setNumero($numero);
			$ficha->setBairro($bairro);
			$ficha->setCidade($cidade);
			$ficha->setTelefone($telresid);
			$ficha->setCelular($celular);
			$ficha->setRecados($recado);
			$ficha->setDataAdmissao($dtadmissao);
			$ficha->setRg($rg);	
			$ficha->setValorMensalidade($vlmensal);
			$ficha->setObs($obs);
			$ficha->setDataNascimento($dtnascimento);
			$ficha->setDataExclusao($dtexclu);
			$ficha->setCodCategoria($categoria);
			$ficha->setAtivo($ativo);
			$ficha->setCodEsde($esde);
			$ficha->setCpf($cpf);
			$ficha->setEmail($email);
			$ficha->setProfissao($profissao);
			$ficha->setCep($cep);
			
			$dao = new FichaDAO();
			$dao->alterar($ficha);
			header('Location:Listar-ficha.php');
		break;

		

		case 'delete':

	

			($_REQUEST['id'])  ? $id  = $_REQUEST['id']   :false;
			
			$ficha = new Ficha();
			
			$ficha->setCodigo($id);			
			
			$dao = new FichaDAO();
			$dao->excluir($ficha);
		

		break;
		case 'termo':
				
			$ids    = $_REQUEST['id'];	
			
			$result = array();		
			$strs   = "";
			foreach($ids as $key=>$value){
				
				$dao    = new FichaDAO();
				$vet    = $dao->ListaFichaTermo($value);
				$num    = count($vet);
				$strs   = "";
				if($num > 0){ 
						
					$ficha 	  = $vet[0];
					
					$nome     = $ficha->getNome();
					$endereco = $ficha->getEndereco();
					$numero   = $ficha->getNumero();
					$bairro	  = $ficha->getBairro();
					$cidade	  = $ficha->getCidade();
					$telefone = $ficha->getTelefone();
					$celular  = $ficha->getCelular();
					$recados  = $ficha->getRecados();
					$rg		  = $dao->mask($ficha->getRg(),'##########');	
					$dtnasc	  = $ficha->getDataNascimento();
					$cpf	  = $dao->mask($ficha->getCpf(),'###.###.###-##');
					$email	  = $ficha->getEmail();
					$cep	  = $dao->mask($ficha->getCep(),'#####-###');
					$ano      = date('Y');
					
					$filename="../public/termo.html";
					$str 	  = file_get_contents($filename);
					
					
					$str 	= str_replace(array('{nome}',
												 '{rg}',
												 '{cpf}',
												 '{fone}',
												 '{cel}',
												 '{email}',
												 '{end}',
												 '{numero}',
												 '{bairro}',
												 '{cidade}',
												 '{cep}',
												 '{dtnasc}',
												 '{ano}'), 
											array("".$nome."",									
												  "".$rg."",
												  "".$cpf."",
												  "".$telefone."",
												  "".$celular."",
												  "".$email."",
												  "".$endereco."",
												  "".$numero."",
												  "".$bairro."",
												  "".$cidade."",
												  "".$cep."",
												  "".implode("/", array_reverse(explode("-", "".$dtnasc."")))."",
												  "".$ano.""), $str);
												  
					$strs .=$str;							  		
				}
				array_push($result,array(
					'pdf'=>''.$strs.''
				));									
			}
			
			echo json_encode($result);
			
		break;
		

		}

	

	

	}

	

?>