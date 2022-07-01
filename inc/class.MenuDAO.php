<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class MenuDAO{

	private $dba;

	public function __construct(){

		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function listamenuusuario(){

		$dba = $this->dba;
		$vet = array();

		$sql = 'SELECT
				m.id,
				m.Nome,
				m.idusuario,
				m.link,
				m.icone
				FROM menu2 m ';				

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){

			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');			

			$men = new Menu();		

			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);			

			$vet[$i] = $men;			

		}

		return $vet;

	}	


	public function ListaMenuCadastroUsuario($tpsys){

		$dba = $this->dba;
		$vet = array();

		$sql = 'SELECT
				distinct
				m.id,
				m.Nome,
				m.idusuario,
				m.link,
				m.icone,
				(select count(s.id) from submenu2 s where s.idmenu = m.id and s.idtipo = "'.$tpsys.'") as num
			FROM menu2 m';				

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){

			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');			
			$idtipo     = $dba->result($res,$i,'num');

			$men = new Menu();		

			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);			
			$men->setNumTp($idtipo);

			$vet[$i] = $men;			

		}

		return $vet;

	}

	public function listamemupousuario($iduser,$idsys){

		$dba = $this->dba;		

		$vet = array();		

		$sql = 'select m.*,(select count(s.id) from submenu2 s where s.idmenu = m.id and s.idtipo = "'.$idsys.'") as num from menu2 m
				inner join permissoes2 p on (p.idmenu = m.id)
				where p.idusuario = '.$iduser.'  group by m.id';
				

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){

			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');
			$idtipo     = $dba->result($res,$i,'num');

			$men = new Menu();

			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);
			$men->setNumTp($idtipo);

			$vet[$i] = $men;
			
		}

		

		return $vet;

		

	}

	public function verificasetemmenu($idu){	

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT * FROM menu2 s
				inner join permissoes2 p on (p.idmenu = s.id)
				where p.idusuario ='.$idu;		

		$res = $dba->query($sql);
		$num = $dba->rows($res);

		for($i=0; $i < $num; $i++){

			

			$cod           = $dba->result($res,$i,'id');	
			$nome          = $dba->result($res,$i,'Nome');	
			$idusuario     = $dba->result($res,$i,'idusuario');
			$link          = $dba->result($res,$i,'link');
			$icone         = $dba->result($res,$i,'icone');
			$idpermissoes  = $dba->result($res,$i,'idpermissoes');			

			$men = new Menu();
			
			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);
			$men->setIdpermissoes($idpermissoes);
			
			$vet[$i] = $men;			

		}

		return $vet;
	}

	

	public function listamenuUm($id){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT
				m.id,
				m.Nome,
				m.idusuario,
				m.link,
				m.icone
				FROM menu2 m
				WHERE m.id ='.$id;

				
		$res = $dba->query($sql);

		$num = $dba->rows($res);


		for($i=0; $i < $num; $i++){			

			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');

			$men = new Menu();
	

			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);
			
			$vet[$i] = $men;			

		}

		

		return $vet;

	}

	public function listamenuporpermissao($id){

		
		$dba = $this->dba;	

		$vet = array();		

		$sql = '(select distinct u.*, (CASE p.idusuario 
				WHEN '.$id.' THEN ("checked") 
				END) as selected from menu2 u
				LEFT JOIN permissoes2 p on (p.idmenu = u.id) and p.idusuario = '.$id.')
				UNION ALL
				(select distinct MEN.*, (CASE p.idusuario 
				WHEN '.$id.' THEN ("checked") 
				END) as selected from menu2 MEN
				left JOIN permissoes2 p on (p.idmenu = MEN.id)
				WHERE not exists(select distinct id from menu2 u
				where u.id = MEN.id) and p.idusuario = '.$id.')';

				

		$res = $dba->query($sql);

		$num = $dba->rows($res);



		for($i=0; $i < $num; $i++){
			
			$cod        = $dba->result($res,$i,'id');	
			$nome       = $dba->result($res,$i,'Nome');	
			$idusuario  = $dba->result($res,$i,'idusuario');
			$link       = $dba->result($res,$i,'link');
			$icone      = $dba->result($res,$i,'icone');
			$checked    = $dba->result($res,$i,'selected');
	
			$men = new Menu();
		
			$men->setCodigo($cod);
			$men->setNome($nome);
			$men->setIdusuario($idusuario);
			$men->setLink($link);
			$men->setIcone($icone);
			$men->setChecked($checked);
			
			$vet[$i] = $men;			

		}		

		return $vet;		

	}

	public function inserir($user){

		$dba = $this->dba;		

		$nom    = $user->getNome();
		$link   = $user->getLink();
		$icone  = $user->getIcone();
	
		$sql = 'INSERT INTO `menu2`

				(

				`Nome`,
				`link`,
				`icone`)
				VALUES
				(
				"'.$nom.'",
				"'.$link.'",
				"'.$icone.'"
				)';		

		$res = $dba->query($sql);

	}

	

	public function update($user){

		$dba = $this->dba;		

		$cod    = $user->getCodigo();
		$nom    = $user->getNome();
		$link   = $user->getLink();
		$icone  = $user->getIcone();		

		$sql = 'UPDATE `menu2`
				SET
				`Nome` = "'.$nom.'",
				`link` = "'.$link.'",
				`icone` = "'.$icone.'"
				WHERE `id` ='.$cod;						

		$res = $dba->query($sql);

	}

	public function delete($user){

		$dba = $this->dba;
		$cod    = $user->getCodigo();
		$sql = 'DELETE FROM `menu2`
				WHERE `id` ='.$cod;		

		$res = $dba->query($sql);

	}	
	
}
?>