<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');
class MensagemEmpresaDAO{

	private $dba;

	public function __construct(){
	
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;

	}


	public function ListaMensagem($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id,
					m.data,
					m.titulo,
					m.mensagem,
					m.id_modalidade,
					m.id_empresa,
					m.id_documento
				FROM
					mensagens_emp m
				where
					m.id_modalidade = (select 
							e.id_modalidade
						from
							empresas e
						where
							e.id = "'.$idemp.'")
						and (m.id_empresa = "'.$idemp.'" or m.id_empresa = "0") order by m.id desc';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);

			$vet[$i] = $msg;

		}

		return $vet;

	}
	
	
	public function ListaMensagemTempoReal($idemp,$timestamp){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
					m.id,
					m.data,
					m.titulo,
					m.mensagem,
					m.id_modalidade,
					m.id_empresa,
					m.id_documento,
					m.timestamp
				FROM
					mensagens_emp m
				where
					m.id_modalidade = (select 
							e.id_modalidade
						from
							empresas e
						where
							e.id = "'.$idemp.'")
						and (m.id_empresa = "'.$idemp.'" or m.id_empresa = "0") and m.timestamp > "'.$timestamp.'" order by m.id desc';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);

			$vet[$i] = $msg;

		}

		return $vet;

	}
	
	public function ListaMensagemDia($idemp){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
				    m.id,
				    m.data,
				    m.titulo,
				    m.mensagem,
				    m.id_modalidade,
				    m.id_empresa,
				    m.id_documento,
				    cast(m.timestamp as time) as timestamp
				FROM
				    mensagens_emp m
				WHERE
				    m.id_empresa = "'.$idemp.'"
				        AND  cast(m.lida as UNSIGNED) = 1 order by m.id desc';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);

			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function ListaMensagemDiaAdm(){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
				    m.id,
				    m.data,
				    m.titulo,
				    m.mensagem,
				    m.id_modalidade,
				    m.id_empresa,
				    m.id_documento,
				    cast(m.timestamp as time) as timestamp
				FROM
				    mensagens_emp m
				WHERE				    
				   cast(m.lida as UNSIGNED) = 1 order by m.id desc';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);

			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function ListaMensagemEmpresa($idemp){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
				    m.id,
				    m.data,
				    m.titulo,
				    m.mensagem,
				    m.id_modalidade,
				    m.id_empresa,
				    m.id_documento,
				    cast(m.timestamp as time) as timestamp,
				    m.lida
				FROM
				    mensagens_emp m
				WHERE
				    m.id_empresa = "'.$idemp.'"
				        order by m.id desc';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			$lida 			= $dba->result($res,$i,'lida');

			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);
			$msg->setLida($lida);

			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function ListaMensagemDiaAdmTodos(){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
				    m.id,
				    m.data,
				    m.titulo,
				    m.mensagem,
				    m.id_modalidade,
				    m.id_empresa,
				    m.id_documento,
				    cast(m.timestamp as time) as timestamp,
				    m.lida
				FROM
				    mensagens_emp m
				 order by m.id desc';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			$lida 			= $dba->result($res,$i,'lida');
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);
			$msg->setLida($lida);

			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function ListaMensagemDetalhe($id){
	
		$dba = $this->dba;
				
		$vet = array();

		$sql = 'SELECT 
				    m.id,
				    m.data,
				    m.titulo,
				    m.mensagem,
				    m.id_modalidade,
				    m.id_empresa,
				    m.id_documento,
				    cast(m.timestamp as time) as timestamp,
				    (select concat(e.razao_social,"|",e.email) from empresas e where e.id = m.id_empresa) as nome_empresa,
				     m.lida
				FROM
				    mensagens_emp m
				WHERE				    
				    m.id = "'.$id.'" ';			  
		//echo $sql;	
		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');
			$data 			= $dba->result($res,$i,'data');
			$titulo 		= $dba->result($res,$i,'titulo');
			$mensagem 		= $dba->result($res,$i,'mensagem');
			$id_modalidade  = $dba->result($res,$i,'id_modalidade');
			$id_empresa	    = $dba->result($res,$i,'id_empresa');
			$id_documento	= $dba->result($res,$i,'id_documento');
			$timestamp		= $dba->result($res,$i,'timestamp');
			$nome_empresa   = $dba->result($res,$i,'nome_empresa');
			$lida 			= $dba->result($res,$i,'lida');

			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			$msg->setData($data);		
			$msg->setTitulo($titulo);
			$msg->setMensagem($mensagem);
			$msg->setIdModalidade($id_modalidade);
			$msg->setIdEmpresa($id_empresa);
			$msg->setIdDocumento($id_documento);
			$msg->setTimesTamp($timestamp);
			$msg->setNomeEmp($nome_empresa);
			$msg->setLida($lida);

			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function CountMesagemDia($idemp){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
					m.id				
				FROM
					mensagens_emp m
				where
					m.id_modalidade = (select 
							e.id_modalidade
						from
							empresas e
						where
							e.id = "'.$idemp.'")
						and (m.id_empresa = "'.$idemp.'" or m.id_empresa = "0") and m.data = CURRENT_DATE order by m.id desc';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$cod 			= $dba->result($res,$i,'id');			
			
			$msg = new MensagemEmpresa();
			
			$msg->setCodigo($cod);
			
			$vet[$i] = $msg;

		}

		return $vet;

	}


	public function CountMesagemNaoLidas(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'SELECT 
				    COUNT(m.id) AS naolidas
				FROM
				    mensagens_emp m
				WHERE
				    CAST(m.lida AS UNSIGNED) = 1 ';			  

		$res = $dba->query($sql);

		$num = $dba->rows($res); 

		

		for($i = 0; $i<$num; $i++){

			$naolidas = $dba->result($res,$i,'naolidas');			
			
			$msg = new MensagemEmpresa();
			
			$msg->setNumNaoLidas($naolidas);
			
			$vet[$i] = $msg;

		}

		return $vet;

	}

	public function TempoAual(){
	
		$dba = $this->dba;
		
		$vet = array();

		$sql = 'Select NOW() AS now';			  

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){

			$now = $dba->result($res,$i,'now');			
			
			$msg = new MensagemEmpresa();
			
			$msg->setNow($now);
			
			$vet[$i] = $msg;

		}

		return $vet;

	}
	

	public function inserir($msg){

		$dba = $this->dba;
		
		$titulo       = $msg->getTitulo();
		$mensagem     = $msg->getMensagem();
		$idmodalidade = $msg->getIdModalidade();
		$idempresa	  = $msg->getIdEmpresa();
		$data         = $msg->getData();
		$timestamp    = $msg->getTimesTamp();
		
		$sql = 'INSERT INTO `mensagens_emp`
				(`titulo`,
				`mensagem`,
				`id_modalidade`,
				`id_empresa`,
				`data`)
				VALUES
				("'.$titulo.'",
				 "'.$mensagem.'",
				 '.$idmodalidade.',
				 '.$idempresa.',
				 "'.$data.'")';					

		$dba->query($sql);	
	
	}

	

	public function updatelida($msg){

		$dba = $this->dba;

		$id    = $msg->getCodigo();
		$lida  = $msg->getLida();
		
		$sql = 'UPDATE `mensagens_emp`
				SET
				`lida` = "'.$lida.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	

	}

	public function deletar($doc){
	
		$dba = $this->dba;

		$id = $doc->getCodigo();
		
		$sql = 'DELETE FROM documentos WHERE id='.$id;

		$dba->query($sql);	
		
	}

	public function proximoid(){

		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "mensagens_emp"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$msg = new MensagemEmpresa();

		$msg->setProximoId($prox_id);

		$vet[$i] = $msg;

		return $vet;

	}

}

?>