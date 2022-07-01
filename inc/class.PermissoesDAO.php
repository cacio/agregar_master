<?php

require_once('inc.autoload.php');

require_once('inc.connect.php');

class PermissoesDAO{

	

	private $dba;

	

	public function __construct(){



		$dba = new DbAdmin('mysql');

		$dba->connect(HOST,USER,SENHA,BD);

		$this->dba = $dba;



	}

	

	public function inserirmenu($per){

		$dba = $this->dba;

		

		$idmenu    = $per->getIdmenu();

		$iduser    = $per->getIdusuario();

		

		$sql = 'INSERT INTO `permissoes2`

				(

				`idmenu`,

				`idusuario`)

				VALUES

				(				

				'.$idmenu.',

				'.$iduser.'

				)';

		$res = $dba->query($sql);

	}

	

	public function listapermissaoporusuario($idu,$idsb){

		$dba = $this->dba;

		$vet = array();

		

		$sql = "select * from permissoes2

				 where permissoes2.idusuario = ".$idu." and 

				 permissoes2.idsubmenu = ".$idsb."";

	

		$res = $dba->query($sql);

		$num = $dba->rows($res);

		

		for($i=0; $i < $num; $i++){

				

			$idpermissoes  = $dba->result($res,$i,'idpermissoes');	

			$idmenu        = $dba->result($res,$i,'idmenu');	  

			$idsubmenu     = $dba->result($res,$i,'idsubmenu');	   

			$idusuario     = $dba->result($res,$i,'idusuario');	   

			     

			$per  = new Permissoes();

			

			$per->setCodigo($idpermissoes);

			$per->setIdmenu($idmenu);

			$per->setIdsubmenu($idsubmenu);

			$per->setIdusuario($idusuario);

			

			$vet[$i] = $per;

			

		}

		return $vet;

		

	

	}

	public function listapermissaousuario($idu){

		$dba = $this->dba;

		$vet = array();

		

		$sql = "select * from permissoes2

				 where permissoes2.idusuario = ".$idu."";

				

		$res = $dba->query($sql);

		$num = $dba->rows($res);

		

		for($i=0; $i < $num; $i++){

				

			$idpermissoes  = $dba->result($res,$i,'idpermissoes');	

			$idmenu        = $dba->result($res,$i,'idmenu');	  

			$idsubmenu     = $dba->result($res,$i,'idsubmenu');	   

			$idusuario     = $dba->result($res,$i,'idusuario');	   

			     

			$per  = new Permissoes();

			

			$per->setCodigo($idpermissoes);

			$per->setIdmenu($idmenu);

			$per->setIdsubmenu($idsubmenu);

			$per->setIdusuario($idusuario);

			

			$vet[$i] = $per;

			

		}

		return $vet;

		

	

	}

	public function inserirsubmenu($per){

		$dba = $this->dba;

		

		$idsubmenu = $per->getIdsubmenu();	

		$iduser    = $per->getIdusuario();

		$idmenu    = $per->getIdmenu();

		

		$sql = 'INSERT INTO `permissoes2`

				(				

				`idsubmenu`,

				`idusuario`)

				VALUES

				(				

				'.$idsubmenu.',

				'.$iduser.'

				)';		

		$res = $dba->query($sql);

	}

	

	public function inserirpermissao($per){

		$dba = $this->dba;

		

		$idsubmenu = $per->getIdsubmenu();	

		$iduser    = $per->getIdusuario();

		$idmenu    = $per->getIdmenu();

		



		$sql = 'INSERT INTO `permissoes2`

				(

				`idmenu`,				

				`idsubmenu`,

				`idusuario`)

				VALUES

				(	

				'.$idmenu.',			

				'.$idsubmenu.',

				'.$iduser.'

				)';	

		//echo $sql; 

		$res = $dba->query($sql);

	}

	

	public function verificaper($idu,$idp){

		$dba = $this->dba;

		$vet = array();

		

		$sql = "select * from permissoes2 p

				where 	p.idusuario = ".$idu." and p.idsubmenu = '".$idp."' or p.idmenu = '".$idp."'";

				

		$res = $dba->query($sql);

		$num = $dba->rows($res);

		

		for($i=0; $i < $num; $i++){

				

			$idpermissoes  = $dba->result($res,$i,'idpermissoes');	

			$idmenu        = $dba->result($res,$i,'idmenu');	  

			$idsubmenu     = $dba->result($res,$i,'idsubmenu');	   

			$idusuario     = $dba->result($res,$i,'idusuario');	   

			     

			$per  = new Permissoes();

			

			$per->setCodigo($idpermissoes);

			$per->setIdmenu($idmenu);

			$per->setIdsubmenu($idsubmenu);

			$per->setIdusuario($idusuario);

			

			$vet[$i] = $per;

			

		}

		return $vet;

	}

	

	public function update($per){

		$dba = $this->dba;

		

		$idpermi   = $per->getCodigo();

		$idmenu    = $per->getIdmenu();

		$idsubmenu = $per->getIdsubmenu();	

		$iduser    = $per->getIdusuario();

		

		$sql = 'UPDATE `permissoes2`

				SET

				`idmenu` = '.$idmenu.',

				`idsubmenu` = '.$idsubmenu.',

				`idusuario` = '.$iduser.'

				WHERE `idpermissoes` ='.$idpermi;

		$res = $dba->query($sql);

		

	} 

	public function delete($per){

		$dba = $this->dba;

		$idpermi   = $per->getCodigo();

		$sql = 'DELETE FROM `permissoes2`

				WHERE `idpermissoes` ='.$idpermi;

		$res = $dba->query($sql);

	}

	public function deleteporuser($per){

		$dba = $this->dba;

		$idpermi   = $per->getCodigo();

		$iduser    = $per->getIdusuario();

		

		$sql = 'DELETE FROM permissoes2

				where permissoes2.idusuario = '.$iduser.'  

				and permissoes2.idpermissoes = '.$idpermi.'';

				

		$res = $dba->query($sql);

	}
	
	public function verificapermissaourl($iduser, $urls){
		
		$dba = $this->dba;
		$vet = array();
		
		$sql = "select SUBSTRING_INDEX(submenu2.link, '/', -1) as link from permissoes2
				inner join submenu2 on (submenu2.id = permissoes2.idsubmenu)
				where permissoes2.idusuario = ".$iduser." and SUBSTRING_INDEX(submenu2.link, '/', -1) = '".$urls."'";
		//echo $sql;
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i=0; $i < $num; $i++){
				
			
			$url     = $dba->result($res,$i,'link');	   
			     
			$per  = new Permissoes();
			
			$per->setUrl($url);
			
			
			$vet[$i] = $per;
			
		}
		return $vet;
		
	
	}
	
}

?>