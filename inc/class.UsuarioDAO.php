<?php

require_once('inc.autoload.php');
require_once('inc.connect.php');

class UsuarioDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}	

	public function listaLogin($ema,$sen){

	$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					u.*,
					e.cnpj,
					e.ins_estadual
			    FROM usuario u
				left join empresas e on (e.id = u.id_empresa)

			  WHERE if(u.idsys = 1,u.login, e.cnpj) = "'.$ema.'" AND (senha = "'.$sen.'" or (SELECT 
					u.senha					
			    FROM usuario u
				left join empresas e on (e.id = u.id_empresa)                
			  WHERE u.idsys = 1 and u.senha = "'.$sen.'" limit 1) in("'.$sen.'")) ';
			  
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){		

			$cod  = $dba->result($res,$i,'id');
			$nom  = $dba->result($res,$i,'nome');
			$ema  = $dba->result($res,$i,'email');
			$log  = $dba->result($res,$i,'login');
			$sen  = $dba->result($res,$i,'senha');
			$idr  = $dba->result($res,$i,'idrota');
			$ids  = $dba->result($res,$i,'idsys');
			$ide  = $dba->result($res,$i,'id_empresa');
			$cnpj = $dba->result($res,$i,'cnpj');
			$insc = $dba->result($res,$i,'ins_estadual');

			$usu = new Usuario();

			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);		
			$usu->setIdEmpresa($ide);
			$usu->setCnpj($cnpj);
			$usu->setInscEmp($insc);

			$vet[$i] = $usu;

		}

		return $vet;

	}



	public function listausuario(){
		
		$dba = $this->dba;
		
		$vet = array();
		
		$sql ='SELECT * FROM usuario where nome <> "cacio" and nome <> "demo"';
		
		$res = $dba->query($sql);
		
		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){
		
			$cod = $dba->result($res,$i,'id');
			$nom = $dba->result($res,$i,'nome');
			$ema = $dba->result($res,$i,'email');
			$log = $dba->result($res,$i,'login');
			$sen = $dba->result($res,$i,'senha');
			$idr = $dba->result($res,$i,'idrota');
			$ids = $dba->result($res,$i,'idsys');
			
		
			$usu = new Usuario();
		
			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);			
		
			$vet[$i] = $usu;		
		
		}

		return $vet;

	}

	public function VerificaEmail($ema){

	$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					u.*,
					e.cnpj
			    FROM usuario u
				left join empresas e on (e.id = u.id_empresa)

			  WHERE u.email = "'.$ema.'" ';
			  
		
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){		

			$cod  = $dba->result($res,$i,'id');									
			$sen  = $dba->result($res,$i,'senha');			
			$ema = $dba->result($res,$i,'email');
			$nom  = $dba->result($res,$i,'nome');

			$usu = new Usuario();

			$usu->setCodigo($cod);									
			$usu->setSenha($sen);
			$usu->setEmail($ema);
			$usu->setNome($nom);

			$vet[$i] = $usu;

		}

		return $vet;

	}

	public function listausuarioum($idu){	

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
				u.*,
				s.cnpj
				FROM usuario u
				left join  empresas s on (s.id = u.id_empresa)
				where u.id='.$idu;

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){
		
			$cod  = $dba->result($res,$i,'id');
			$nom  = $dba->result($res,$i,'nome');
			$ema  = $dba->result($res,$i,'email');
			$log  = $dba->result($res,$i,'login');
			$sen  = $dba->result($res,$i,'senha');
			$idr  = $dba->result($res,$i,'idrota');
			$ids  = $dba->result($res,$i,'idsys');
			$ide  = $dba->result($res,$i,'id_empresa');
			$cnpj = $dba->result($res,$i,'cnpj');

			$usu = new Usuario();
			
			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);
			$usu->setIdEmpresa($ide);
			$usu->setCnpj($cnpj);

			$vet[$i] = $usu;

		}

		return $vet;

	}

	public function ListaUsuarioPorEmpresa($idu){	

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
				u.*,
				s.cnpj
				FROM usuario u
				left join  empresas s on (s.id = u.id_empresa)
				where s.id ='.$idu;

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){
		
			$cod  = $dba->result($res,$i,'id');
			$nom  = $dba->result($res,$i,'nome');
			$ema  = $dba->result($res,$i,'email');
			$log  = $dba->result($res,$i,'login');
			$sen  = $dba->result($res,$i,'senha');
			$idr  = $dba->result($res,$i,'idrota');
			$ids  = $dba->result($res,$i,'idsys');
			$ide  = $dba->result($res,$i,'id_empresa');
			$cnpj = $dba->result($res,$i,'cnpj');

			$usu = new Usuario();
			
			$usu->setCodigo($cod);
			$usu->setNome($nom);
			$usu->setEmail($ema);
			$usu->setLogin($log);
			$usu->setSenha($sen);
			$usu->setIdRota($idr);
			$usu->setIdsys($ids);
			$usu->setIdEmpresa($ide);
			$usu->setCnpj($cnpj);

			$vet[$i] = $usu;

		}

		return $vet;

	}

	public function inserir($usu){


		$dba = $this->dba;

		$nom = $usu->getNome();
		$ema = $usu->getEmail();
		$log = $usu->getLogin();
		$idr = $usu->getIdRota();
		$ids = $usu->getIdsys();
		$sen = $usu->getSenha();
		$emp = $usu->getIdEmpresa();	
		

		$sql = 'INSERT INTO usuario

							(

							 nome,
							 email,
							 login,
							 senha,
							 idrota,							
							 idsys,
							 id_empresa)

							VALUES

							(

							"'.$nom.'",
							"'.$ema.'",
							"'.$log.'",
							"'.$sen.'",
							'.$idr.',
							'.$ids.',
							'.$emp.'
							)';

										

		$dba->query($sql);	

	}

	

	public function update($usu){
		
		$dba = $this->dba;
	
		$idu = $usu->getCodigo();
		$nom = $usu->getNome();
		$ema = $usu->getEmail();
		$log = $usu->getLogin();
		$idr = $usu->getIdRota();
		$ids = $usu->getIdsys();
		$sen = $usu->getSenha();

	 	if($sen == ""){
			
			$sql = 'UPDATE  usuario
				SET
				nome = "'.$nom.'",
				email  = "'.$ema.'",
				login  = "'.$log.'",
				idrota = '.$idr.',
				idsys = '.$ids.'
				WHERE id='.$idu;
							
		}else{
			
			$sql = 'UPDATE  usuario
				SET
				nome = "'.$nom.'",
				email  = "'.$ema.'",
				login  = "'.$log.'",
				idrota = '.$idr.',
				idsys = '.$ids.',
				senha = "'.$sen.'"
				WHERE id='.$idu;	
			
		}
		
		//echo $sql;
		$dba->query($sql);	


	}

	public function updatesenha($usu){
		
		$dba = $this->dba;
	
		$idu = $usu->getCodigo();	
		$sen = $usu->getSenha();	 	
		
		$sql = 'UPDATE  usuario
			SET				
			senha = "'.$sen.'"
			WHERE id='.$idu;	
		
		//echo $sql;
		$dba->query($sql);	

	}

	public function deletar($usu){

		$dba = $this->dba;

		$idu = $usu->getCodigo();

		$sql = 'DELETE FROM usuario WHERE id='.$idu;

		$dba->query($sql);		

	}

	

	public function proximoid(){

		

		$dba = $this->dba;

		$vet = array();

		

		$sql = 'SELECT max(id) + 1 as max_id from usuario';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'max_id');	 

		$usu = new Usuario();

		$usu->setProxid($prox_id);

		$vet[$i] = $usu;

		return $vet;

	

	}	

}

?>