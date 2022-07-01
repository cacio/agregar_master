<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class ProtocoloDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaProtocoloPorEmmpresaCompetencia($mesano,$cnpjemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT * FROM tab_protocolos p where p.competencia = "'.$mesano.'" and p.cnpj_empresa = "'.$cnpjemp.'" order by p.id desc limit 1';			  
		// echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
						
			$prot = new Protocolo();

			$prot->setCodigo($cod);			

			$vet[$i] = $prot;

		}

		return $vet;

	}


	public function VerificaCriptografia($mesano,$cnpjemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT p.id,p.cripty FROM tab_protocolos p where p.competencia = "'.$mesano.'" and p.cnpj_empresa = "'.$cnpjemp.'" order by p.id desc limit 1';			  
		 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){

			$cripty  = $dba->result($res,$i,'cripty');
			$cod     = $dba->result($res,$i,'id');

			$prot = new Protocolo();

			$prot->setCripty($cripty);			
			$prot->setCodigo($cod);

			$vet[$i] = $prot;

		}

		return $vet;

	}
	
	public function NumerosDeCompetenciaEnviada(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
				    COUNT(p.id) AS numcomp
				FROM
				    tab_protocolos p
				        INNER JOIN
				    status s ON (s.id = p.status)
				WHERE
				    s.id = 8
				        AND SUBSTRING(p.competencia, 4, 7) = DATE_FORMAT(CURDATE(), "%Y")';			  
		 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){

			$numcomp  = $dba->result($res,$i,'numcomp');
			
			$prot = new Protocolo();

			$prot->setNumeroComp($numcomp);			

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function BuscaDadosProtocoloUm($prot){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT p.id FROM tab_protocolos p where p.protocolo = "'.$prot.'" ';			  
		 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){
			
			$cod     = $dba->result($res,$i,'id');

			$prot = new Protocolo();						
			$prot->setCodigo($cod);

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function BuscaDadosProtocoloEnviados($prot){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT p.id FROM tab_protocolos p where p.protocolo = "'.$prot.'" order by p.id desc limit 1';			  
		 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){
			
			$cod     = $dba->result($res,$i,'id');

			$prot = new Protocolo();						
			$prot->setCodigo($cod);

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function ListaCompetenciasEmProgresso($cnpj){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					p.id, p.competencia, s.nome,p.tipo_arq
				FROM
					tab_protocolos p
						INNER JOIN
					status s ON (s.id = p.status)
				WHERE
					p.cnpj_empresa = "'.$cnpj.'"
						AND p.id IN (SELECT 
							MAX(p.id)
						FROM
							tab_protocolos p
						WHERE
							p.cnpj_empresa = "'.$cnpj.'"
						GROUP BY p.competencia
						ORDER BY MAX(p.id) DESC) and (/*s.nome <> "Entregue" and*/ s.nome <> "Arquivo Enviado")
				GROUP BY p.id , p.competencia , s.nome
				ORDER BY MAX(p.id) DESC';			  
		 
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){
			
			$cod     	 = $dba->result($res,$i,'id');
			$competencia = $dba->result($res,$i,'competencia');
			$nome     	 = $dba->result($res,$i,'nome');
			$tipo_arq  	 = $dba->result($res,$i,'tipo_arq');

			$prot = new Protocolo();						
			$prot->setCodigo($cod);
			$prot->setCompetencia($competencia);
			$prot->setStatus($nome);
			$prot->setTipoArq($tipo_arq);

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function VerificaCompetencia($mesano,$cnpjemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT p.status,p.protocolo FROM tab_protocolos p where p.competencia = "'.$mesano.'" and p.cnpj_empresa = "'.$cnpjemp.'" order by p.id desc limit 1';			  
		// echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){

			$status    = $dba->result($res,$i,'status');
			$protocolo = $dba->result($res,$i,'protocolo');

			$prot = new Protocolo();

			$prot->setStatus($status);			
			$prot->setProtocolo($protocolo);

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function ListaProtocoloExclusao($mesano,$cnpjemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT id FROM tab_protocolos p where p.competencia = "'.$mesano.'" and p.cnpj_empresa = "'.$cnpjemp.'" ';			  
		// echo $sql;
		$res = $dba->query($sql);

		$num = $dba->rows($res); 
		
		for($i = 0; $i<$num; $i++){

			$cod  = $dba->result($res,$i,'id');
						
			$prot = new Protocolo();

			$prot->setCodigo($cod);			

			$vet[$i] = $prot;

		}

		return $vet;

	}

	public function inserir($prot){

		$dba = $this->dba;
		
		$competencia  = $prot->getCompetencia();
		$protocolo    = $prot->getProtocolo();
		$cripty       = $prot->getCripty();
		$status       = $prot->getStatus();
		$cnpjemp      = $prot->getCnpjEmp();
		$tipoarq	  = $prot->getTipoArq();

		$sql = 'INSERT INTO `tab_protocolos`
				(`competencia`,
				 `protocolo`,
				 `cripty`,
				 `status`,
				 `cnpj_empresa`,
				 `tipo_arq`)
				VALUES
				("'.$competencia.'",
				"'.$protocolo.'",
				"'.$cripty.'",
				'.$status.',
				"'.$cnpjemp.'",
				"'.$tipoarq.'")';					

		$dba->query($sql);	
	
	}

	public function updateStatus($prot){

		$dba = $this->dba;

		$id      = $prot->getCodigo();
		$status  = $prot->getStatus();
		
		$sql = 'UPDATE `tab_protocolos`
				SET
				`status` = "'.$status.'"
				WHERE `id` = '.$id;		
		$dba->query($sql);	

	}	

	public function updateStatusProtocolo($prot){

		$dba = $this->dba;

		$id      	  = $prot->getCodigo();
		$status  	  = $prot->getStatus();
		$protocolo    = $prot->getProtocolo();
		$cripty       = $prot->getCripty();

		$sql = 'UPDATE `tab_protocolos`
				SET
				`status` = "'.$status.'",
				`protocolo` = "'.$protocolo.'",
				`cripty` = "'.$cripty.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function removeProtocolo($prot){

		$dba = $this->dba;

		$id    = $prot->getCodigo();
		
		
		$sql = 'DELETE FROM `tab_protocolos`
				WHERE `id` ='.$id;

		$dba->query($sql);	

	}

	public function removeProtocoloEmp($prot){

		$dba = $this->dba;

		$id      = $prot->getCodigo();
		$cnpjemp = $prot->getCnpjEmp();
		
		$sql = 'DELETE FROM `tab_protocolos`
				WHERE `id` = "'.$id.'" and cnpj_empresa = "'.$cnpjemp.'" ';

		$dba->query($sql);	

	}
	

}

?>