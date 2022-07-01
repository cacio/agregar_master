<?php

require_once('inc.autoload.php');
require_once('inc.connect.php');

class SubmenuDAO{

	private $dba;

	public function __construct(){

		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;		

	}

		

	public function listasubmenuusuario(){

		$dba = $this->dba;		

		$vet = array();		

		$sql = 'SELECT
				s.id,
				s.Nome,
				s.idmenu,
				s.idusuario,
				s.link,
				s.icone
				FROM submenu2 s';

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){

			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$idmenu     = $dba->result($res,$i,'idmenu');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');

			$sub = new Submenu();

			$sub->setCodigo($cod);
			$sub->setNome($nome);
			$sub->setIdusuario($idusuario);
			$sub->setIdmenu($idmenu);
			$sub->setLink($link);
			$sub->setIcone($icone);			

			$vet[$i] = $sub;			

		}		

		return $vet;	

	}	

	

	public function listasubmenuporusuario($iduser,$idmenu){		

		$dba = $this->dba;
		$vet = array();
		

		$sql = 'select * from submenu2 s
				inner join permissoes2 p on (p.idsubmenu = s.id)
				where p.idusuario = '.$iduser.' and s.idmenu = '.$idmenu.' ORDER BY sort ASC';
				

		$res = $dba->query($sql);

		$num = $dba->rows($res);



		for($i=0; $i < $num; $i++){
			
			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$idmenu     = $dba->result($res,$i,'idmenu');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');
			$action_id  = $dba->result($res,$i,'action_id');
			
			$sub = new Submenu();

			
			$sub->setCodigo($cod);
			$sub->setNome($nome);
			$sub->setIdusuario($idusuario);
			$sub->setIdmenu($idmenu);
			$sub->setLink($link);
			$sub->setIcone($icone);
			$sub->setActionId($action_id);	
						
			$vet[$i] = $sub;			

		}

		return $vet;

	}
	

	public function verificasetemsubmenu($idu){

		$dba = $this->dba;
	
		$vet = array();
	
		$sql = 'SELECT * FROM submenu2 s
				inner join permissoes2 p on (p.idsubmenu = s.id)
				where p.idusuario = '.$idu.'';


		$res = $dba->query($sql);
		$num = $dba->rows($res);


		for($i=0; $i < $num; $i++){
			
			$cod           = $dba->result($res,$i,'id');	
			$nome          = $dba->result($res,$i,'Nome');	
			$idusuario     = $dba->result($res,$i,'idusuario');
			$idmenu        = $dba->result($res,$i,'idmenu');
			$link          = $dba->result($res,$i,'link');
			$icone         = $dba->result($res,$i,'icone');
			$idpermissoes  = $dba->result($res,$i,'idpermissoes');			

			$sub = new Submenu();			

			$sub->setCodigo($cod);
			$sub->setNome($nome);
			$sub->setIdusuario($idusuario);
			$sub->setIdmenu($idmenu);
			$sub->setLink($link);
			$sub->setIcone($icone);
			$sub->setIdpermissoes($idpermissoes);		

			$vet[$i] = $sub;			

		}		

		return $vet;

	}

	

	public function listasubmenuUm($idsub){

		$dba = $this->dba;


		$vet = array();

		
		$sql = 'SELECT
				s.id,
				s.Nome,
				s.idmenu,
				s.idusuario,
				s.link,
				s.icone,
				s.idtipo
				FROM submenu2 s
				WHERE s.idmenu = '.$idsub.'';

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){
		
			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$idmenu     = $dba->result($res,$i,'idmenu');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');
			$idtipo     = $dba->result($res,$i,'idtipo');

			$sub = new Submenu();
			

			$sub->setCodigo($cod);
			$sub->setNome($nome);
			$sub->setIdusuario($idusuario);
			$sub->setIdmenu($idmenu);
			$sub->setLink($link);
			$sub->setIcone($icone);
			$sub->setIdTipo($idtipo);

			$vet[$i] = $sub;			

		}

		return $vet;

	}

	public function listasubmenuporuser($idu,$idm){

		$dba = $this->dba;
			
		$vet = array();

		$sql = '(select distinct u.*, (CASE p.idusuario 
					WHEN '.$idu.' THEN ("checked") 
					END) as selected from submenu2 u
					LEFT JOIN permissoes2 p on (p.idsubmenu = u.id) and p.idusuario = '.$idu.' and u.idmenu = '.$idm.' group by p.idpermissoes)
					UNION ALL
					(select distinct MEN.*, (CASE p.idusuario 
					WHEN '.$idu.' THEN ("checked") 
					END) as selected from submenu2 MEN
					left JOIN permissoes2 p on (p.idsubmenu = MEN.id) where p.idusuario = '.$idu.' and MEN.idmenu = '.$idm.' )';
		
		$res = $dba->query($sql);
		$num = $dba->rows($res);


		for($i=0; $i < $num; $i++){
			
			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$idmenu     = $dba->result($res,$i,'idmenu');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');
			$checked    = $dba->result($res,$i,'selected');
		
			$sub = new Submenu();

			
			$sub->setCodigo($cod);
			$sub->setNome($nome);
			$sub->setIdusuario($idusuario);
			$sub->setIdmenu($idmenu);
			$sub->setLink($link);
			$sub->setIcone($icone);
			$sub->setChecked($checked);

			$vet[$i] = $sub;

		}
		
		return $vet;
	}

	public function SubMenuPermissao($iduser,$tag,$idsys){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					SUBSTRING_INDEX(s.link, "/", - 1) AS stringurl
				FROM
					submenu2 s
						INNER JOIN
					permissoes2 p ON (p.idsubmenu = s.id)
				WHERE
					p.idusuario = "'.$iduser.'"
						AND SUBSTRING_INDEX(s.link, "/", - 1) = "'.$tag.'" and s.idtipo = "'.$idsys.'" ';
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){		

			$stringurl        = $dba->result($res,$i,'stringurl');	

			$sub = new Submenu();
			
			$sub->setLink($stringurl);


			$vet[$i] = $sub;

		}
		
		return $vet;
	}

	public function inserir($sub){

		$dba = $this->dba;		
		
		$nom    = $sub->getNome();
		$idmenu = $sub->getIdmenu();		
		$link   = $sub->getLink();
		$icone  = $sub->getIcone();		

		$sql = 'INSERT INTO `submenu2`
				(
				`Nome`,
				`idmenu`,
				`link`,
				`icone`)
				VALUES

				(
				"'.$nom.'",
				'.$idmenu.',
				"'.$link.'",
				"'.$icone.'"
				)';		

		$res = $dba->query($sql);

	}

	

	public function update($sub){

		$dba = $this->dba;

		$cod    = $sub->getCodigo(); 	
		$nom    = $sub->getNome();
		$iduser = $sub->getIdusuario();
		$idmenu = $sub->getIdmenu();		
		$link   = $sub->getLink();
		$icone  = $sub->getIcone();

		$sql = 'UPDATE `submenu2`
				SET
				`Nome` = "'.$nom.'",
				`idmenu` = '.$idmenu.',
				`idusuario` = '.$iduser.',
				`link` = "'.$link.'",
				`icone`	= "'.$icone.'"
				WHERE `id` ='.$cod;		

		$res = $dba->query($sql);

	}
	
	public function updateorder($sub){

		$dba = $this->dba;
		$cod    = $sub->getCodigo(); 	
		$sort   = $sub->getSort();
	
		$sql = 'UPDATE `submenu2`
				SET
				`sort` = '.$sort.'
				WHERE `id` ='.$cod;
		
		$res = $dba->query($sql);

	}
	
	public function delete($sub){

		$dba = $this->dba;

		$cod    = $sub->getCodigo(); 		

		$sql = 'DELETE FROM `submenu2`
				WHERE `id` ='.$cod;		

		$res = $dba->query($sql);		

	}

}

?>