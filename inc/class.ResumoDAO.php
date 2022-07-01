<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class ResumoDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaResumoEmpresa($competenc, $cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					r.id,
					r.COMPETENC,
					r.BOVINOS,
					r.BUBALINOS,
					r.OVINOS,
					r.ICMSNOR,
					r.SUBSTIT,
					r.CREDITOENT,
					r.CREDITOSRS,
					r.CREDITOSOE,
					r.BASEENT,
					r.BASESAIRS,
					r.BASESAIOE,
					r.NUMEFUNC,
					r.VALOFOLHA,
					r.DATAPAGT,
					r.BASESAIRS4,
					r.CRIDITOSR4,
					r.CREDITOSR4,
					r.cnpj_emp,
					r.CREDITOSOE4,
					r.BASESAIOE4
				FROM
					resumo r
				WHERE
					r.COMPETENC = "'.$competenc.'" AND r.cnpj_emp = "'.$cnpj.'"
				ORDER BY r.id DESC
				LIMIT 1';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod		 = $dba->result($res,$i,'id');
			$COMPETENC   = $dba->result($res,$i,'COMPETENC');
			$BOVINOS  	 = $dba->result($res,$i,'BOVINOS');
			$BUBALINOS   = $dba->result($res,$i,'BUBALINOS');
			$OVINOS  	 = $dba->result($res,$i,'OVINOS');
			$ICMSNOR  	 = $dba->result($res,$i,'ICMSNOR');
			$SUBSTIT  	 = $dba->result($res,$i,'SUBSTIT');
			$CREDITOENT  = $dba->result($res,$i,'CREDITOENT');
			$CREDITOSRS  = $dba->result($res,$i,'CREDITOSRS');
			$CREDITOSOE  = $dba->result($res,$i,'CREDITOSOE');
			$BASEENT     = $dba->result($res,$i,'BASEENT');
			$BASESAIRS   = $dba->result($res,$i,'BASESAIRS');
			$BASESAIOE   = $dba->result($res,$i,'BASESAIOE');
			$NUMEFUNC    = $dba->result($res,$i,'NUMEFUNC');
			$VALOFOLHA   = $dba->result($res,$i,'VALOFOLHA');	
			$DATAPAGT    = $dba->result($res,$i,'DATAPAGT');	
			$BASESAIRS4  = $dba->result($res,$i,'BASESAIRS4');	
			$CRIDITOSR4  = $dba->result($res,$i,'CRIDITOSR4');		
			$CREDITOSR4  = $dba->result($res,$i,'CREDITOSR4');
			$CREDITOSOE4 = $dba->result($res,$i,'CREDITOSOE4');		
			$BASESAIOE4  = $dba->result($res,$i,'BASESAIOE4');
			
			$resu = new Resumo();

			$resu->setCodigo($cod);
			$resu->setCompetenc($COMPETENC);
			$resu->setBovinos($BOVINOS);
			$resu->setBubalinos($BUBALINOS);
			$resu->setOvinos($OVINOS);
			$resu->setIcmsNor($ICMSNOR);
			$resu->setSubstit($SUBSTIT);
			$resu->setCreditoEnt($CREDITOENT);
			$resu->setCreditosRS($CREDITOSRS);
			$resu->setCreditosOE($CREDITOSOE);
			$resu->setBaseEnt($BASEENT );
			$resu->setBaseSaiRS($BASESAIRS);
			$resu->setBaseSaiOE($BASESAIOE);
			$resu->setNumeroFuncionario($NUMEFUNC);
			$resu->setValorFolha($VALOFOLHA);
			$resu->setDataPagto($DATAPAGT);
			$resu->setBaseSaiRS4($BASESAIRS4);
			$resu->setCriditosR4($CRIDITOSR4);
			$resu->setCreditosR4($CREDITOSR4);
			$resu->setCreditosOE4($CREDITOSOE4);
			$resu->setBaseSaiOE4($BASESAIOE4);
			
			$vet[$i] = $resu;

		}

		return $vet;

	}


	public function ListaResumoEmpresaUm($cod, $cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					r.id,
					r.COMPETENC,
					r.BOVINOS,
					r.BUBALINOS,
					r.OVINOS,
					r.ICMSNOR,
					r.SUBSTIT,
					r.CREDITOENT,
					r.CREDITOSRS,
					r.CREDITOSOE,
					r.BASEENT,
					r.BASESAIRS,
					r.BASESAIOE,
					r.NUMEFUNC,
					r.VALOFOLHA,
					r.DATAPAGT,
					r.BASESAIRS4,
					r.CRIDITOSR4,
					r.CREDITOSR4,
					r.cnpj_emp,
					r.NUMERO_ENTRADA,
					r.NUMERO_SAIDA
				FROM
					resumo r
				WHERE
					r.id = "'.$cod.'" AND r.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod		 	= $dba->result($res,$i,'id');
			$COMPETENC   	= $dba->result($res,$i,'COMPETENC');
			$BOVINOS  	 	= $dba->result($res,$i,'BOVINOS');
			$BUBALINOS   	= $dba->result($res,$i,'BUBALINOS');
			$OVINOS  	 	= $dba->result($res,$i,'OVINOS');
			$ICMSNOR  	 	= $dba->result($res,$i,'ICMSNOR');
			$SUBSTIT  	 	= $dba->result($res,$i,'SUBSTIT');
			$CREDITOENT  	= $dba->result($res,$i,'CREDITOENT');
			$CREDITOSRS  	= $dba->result($res,$i,'CREDITOSRS');
			$CREDITOSOE  	= $dba->result($res,$i,'CREDITOSOE');
			$BASEENT     	= $dba->result($res,$i,'BASEENT');
			$BASESAIRS   	= $dba->result($res,$i,'BASESAIRS');
			$BASESAIOE   	= $dba->result($res,$i,'BASESAIOE');
			$NUMEFUNC    	= $dba->result($res,$i,'NUMEFUNC');
			$VALOFOLHA   	= $dba->result($res,$i,'VALOFOLHA');	
			$DATAPAGT    	= $dba->result($res,$i,'DATAPAGT');	
			$BASESAIRS4  	= $dba->result($res,$i,'BASESAIRS4');	
			$CRIDITOSR4  	= $dba->result($res,$i,'CRIDITOSR4');		
			$NUMERO_ENTRADA = $dba->result($res,$i,'NUMERO_ENTRADA');
			$NUMERO_SAIDA   = $dba->result($res,$i,'NUMERO_SAIDA');
				
			$resu = new Resumo();

			$resu->setCodigo($cod);
			$resu->setCompetenc($COMPETENC);
			$resu->setBovinos($BOVINOS);
			$resu->setBubalinos($BUBALINOS);
			$resu->setOvinos($OVINOS);
			$resu->setIcmsNor($ICMSNOR);
			$resu->setSubstit($SUBSTIT);
			$resu->setCreditoEnt($CREDITOENT);
			$resu->setCreditosRS($CREDITOSRS);
			$resu->setCreditosOE($CREDITOSOE);
			$resu->setBaseEnt($BASEENT );
			$resu->setBaseSaiRS($BASESAIRS);
			$resu->setBaseSaiOE($BASESAIOE);
			$resu->setNumeroFuncionario($NUMEFUNC);
			$resu->setValorFolha($VALOFOLHA);
			$resu->setDataPagto($DATAPAGT);
			$resu->setBaseSaiRS4($BASESAIRS4);
			$resu->setCriditosR4($CRIDITOSR4);
			$resu->setCreditosR4($CRIDITOSR4);
			$resu->setNumeroSaida($NUMERO_SAIDA);
			$resu->setNumeroEntrada($NUMERO_ENTRADA);

			$vet[$i] = $resu;

		}

		return $vet;

	}

	public function ValidaMesAnoCompetencia($competenc, $cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
				    r.id, p.status,p.protocolo
				FROM
				    resumo r
				        LEFT JOIN
				    tab_protocolos p ON (p.cnpj_empresa = r.cnpj_emp)
				        AND (p.competencia = r.COMPETENC)
				WHERE
					r.COMPETENC = "'.$competenc.'" AND r.cnpj_emp = "'.$cnpj.'" order by p.id desc LIMIT 1';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod       = $dba->result($res,$i,'id');
			$status    = $dba->result($res,$i,'status');
			$protocolo = $dba->result($res,$i,'protocolo');

			$resu = new Resumo();

			$resu->setCodigo($cod);			
			$resu->setStatus($status);
			$resu->setProtocolo($protocolo);

			$vet[$i] = $resu;

		}

		return $vet;

	}

	public function MesAnoCompetenciaFinalizadaParaEnvio($competenc, $cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
			    r.id, 
			    p.status, 
			    p.protocolo, 
			    p.competencia, 
			    s.nome,
			    e.razao_social
			FROM
			    resumo r
			        LEFT JOIN
			    tab_protocolos p ON (p.cnpj_empresa = r.cnpj_emp)
			        AND (p.competencia = r.COMPETENC)
			        INNER JOIN
			    status s ON (s.id = p.status)
			    	INNER JOIN
    			empresas e ON (e.cnpj = r.cnpj_emp)
				WHERE
					r.COMPETENC = "'.$competenc.'" AND r.cnpj_emp = "'.$cnpj.'" order by p.id desc LIMIT 1';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod         = $dba->result($res,$i,'id');
			$status      = $dba->result($res,$i,'status');
			$protocolo   = $dba->result($res,$i,'protocolo');
			$competencia = $dba->result($res,$i,'competencia');
			$nome        = $dba->result($res,$i,'nome');
			$razao_social= $dba->result($res,$i,'razao_social');	

			$resu = new Resumo();

			$resu->setCodigo($cod);			
			$resu->setStatus($status);
			$resu->setProtocolo($protocolo);
			$resu->setCompetenc($competencia);
			$resu->setNomeStatus($nome);
			$resu->setRazaoSocialEmp($razao_social);

			$vet[$i] = $resu;

		}

		return $vet;

	}

	public function ListaCompetencias($cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					R.ID, R.COMPETENC, s.nome,t.protocolo,t.tipo_arq
				FROM
					resumo R
						INNER JOIN
					tab_protocolos t ON (t.competencia = R.COMPETENC)
						INNER JOIN
					status s ON (s.id = t.status)
				WHERE
					R.cnpj_emp = "'.$cnpj.'" and 
                    t.id IN (SELECT 
							MAX(p.id)
						FROM
							tab_protocolos p
						WHERE
							p.cnpj_empresa = "'.$cnpj.'"
						GROUP BY p.competencia
						ORDER BY MAX(p.id) DESC)
				GROUP BY R.ID , R.COMPETENC
				ORDER BY MAX(t.id) DESC ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod 		= $dba->result($res,$i,'ID');
			$competenc  = $dba->result($res,$i,'COMPETENC');
			$status	    = $dba->result($res,$i,'nome');		
			$protocolo  = $dba->result($res,$i,'protocolo');
			$tipoarq    = $dba->result($res,$i,'tipo_arq');

			$resu = new Resumo();

			$resu->setCodigo($cod);			
			$resu->setCompetenc($competenc);
			$resu->setNomeStatus($status);
			$resu->setProtocolo($protocolo);
			$resu->setTipoArq($tipoarq);
			
			$vet[$i] = $resu;

		}

		return $vet;

	}

	public function CompetenciasEnviadasAgregar($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					r.id, 
					p.status, 
					p.protocolo, 
					p.competencia, 
					s.nome,
					e.razao_social,
				    e.fantasia,
				    e.cnpj,
				    concat(e.endereco,", ",e.nro) as endereco,
				    e.fone1,
				    e.fone2,
				    e.cidade,
				    e.estado,    
				    e.bairro,
				    e.cep
				FROM
				resumo r
					LEFT JOIN
				tab_protocolos p ON (p.cnpj_empresa = r.cnpj_emp)
					AND (p.competencia = r.COMPETENC)
					INNER JOIN
				status s ON (s.id = p.status)
					INNER JOIN
				empresas e ON (e.cnpj = r.cnpj_emp)
				'.$where.'
				order by e.cnpj ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod         = $dba->result($res,$i,'id');
			$status      = $dba->result($res,$i,'status');
			$protocolo   = $dba->result($res,$i,'protocolo');
			$competencia = $dba->result($res,$i,'competencia');
			$nome        = $dba->result($res,$i,'nome');
			$razao_social= $dba->result($res,$i,'razao_social');	
			$fantasia    = $dba->result($res,$i,'fantasia');	
			$cnpj        = $dba->result($res,$i,'cnpj');	
			$endereco    = $dba->result($res,$i,'endereco');	
			$fone1       = $dba->result($res,$i,'fone1');
			$fone2       = $dba->result($res,$i,'fone2');
			$cidade      = $dba->result($res,$i,'cidade');
			$estado      = $dba->result($res,$i,'estado');
			$bairro      = $dba->result($res,$i,'bairro');
			$cep         = $dba->result($res,$i,'cep');

			$resu = new Resumo();

			$resu->setCodigo($cod);			
			$resu->setStatus($status);
			$resu->setProtocolo($protocolo);
			$resu->setCompetenc($competencia);
			$resu->setNomeStatus($nome);
			$resu->setRazaoSocialEmp($razao_social);
			$resu->setEmpFantasia($fantasia);
			$resu->setCnpjEmp($cnpj);
			$resu->setEmpEndereco($endereco);
			$resu->setEmpFone1($fone1);
			$resu->setEmpFone2($fone2);
			$resu->setEmpCidade($cidade);
			$resu->setEmpEstado($estado);
			$resu->setEmpBairro($bairro);
			$resu->setEmpCep($cep);

			$vet[$i] = $resu;

		}

		return $vet;

	}

	public function inserir($resu){

		$dba = $this->dba;							
		
		$competenc  	= $resu->getCompetenc();
		$bovinos		= $resu->getBovinos();
		$bubalinos  	= $resu->getBubalinos();
		$ovinos			= $resu->getOvinos();
		$icmsnor 		= $resu->getIcmsNor();
		$substit		= $resu->getSubstit();
		$creditoent 	= $resu->getCreditoEnt();
		$creditosrs 	= $resu->getCreditosRS();
		$creditosoe 	= $resu->getCreditosOE();
		$baseent    	= $resu->getBaseEnt();
		$basesairs		= $resu->getBaseSaiRS();
		$basesaioe  	= $resu->getBaseSaiOE();
		$numefunc		= $resu->getNumeroFuncionario();
		$valorfolha		= $resu->getValorFolha();
		$datapagto		= $resu->getDataPagto();
		$basesairs4 	= $resu->getBaseSaiRS4();
		$criditosr4 	= $resu->getCriditosR4();
		$creditosr4 	= $resu->getCreditosR4();
		$cnpjemp    	= $resu->getCnpjEmp();
		$numero_entrada = $resu->getNumeroEntrada();
		$numero_saida   = $resu->getNumeroSaida();
		$creditosoe4    = $resu->getCreditosOE4();
		$basesaioe4     = $resu->getBaseSaiOE4();	
		
		$sql = 'INSERT INTO `resumo`
				(`COMPETENC`,
				`BOVINOS`,
				`BUBALINOS`,
				`OVINOS`,
				`ICMSNOR`,
				`SUBSTIT`,
				`CREDITOENT`,
				`CREDITOSRS`,
				`CREDITOSOE`,
				`BASEENT`,
				`BASESAIRS`,
				`BASESAIOE`,
				`NUMEFUNC`,
				`VALOFOLHA`,
				`DATAPAGT`,
				`BASESAIRS4`,
				`CRIDITOSR4`,
				`CREDITOSR4`,
				`NUMERO_ENTRADA`,
				`NUMERO_SAIDA`,
				`cnpj_emp`,
				`CREDITOSOE4`,
				`BASESAIOE4`)
				VALUES
				("'.$competenc.'",
				 "'.$bovinos.'",
				 "'.$bubalinos.'",
				 "'.$ovinos.'",
				  '.$icmsnor.',
				  '.$substit.',
				  '.$creditoent.',
				  '.$creditosrs.',
				  '.$creditosoe.',
				  '.$baseent.',
				  '.$basesairs.',
				  '.$basesaioe.',
				 "'.$numefunc.'",
				  '.$valorfolha.',
				 "'.$datapagto.'",
				  '.$basesairs4.',
				  '.$criditosr4.',
				  '.$creditosr4.',
				  "'.$numero_entrada.'",
				  "'.$numero_saida.'",
				 "'.$cnpjemp.'",
				 '.$creditosoe4.',
				 '.$basesaioe4.')';
		
		$dba->query($sql);	
							
	}

	public function update($resu){

		$dba = $this->dba;							
		
		$codigo         = $resu->getCodigo();	
		$competenc  	= $resu->getCompetenc();
		$bovinos		= $resu->getBovinos();
		$bubalinos  	= $resu->getBubalinos();
		$ovinos			= $resu->getOvinos();
		$icmsnor 		= $resu->getIcmsNor();
		$substit		= $resu->getSubstit();
		$creditoent 	= $resu->getCreditoEnt();
		$creditosrs 	= $resu->getCreditosRS();
		$creditosoe 	= $resu->getCreditosOE();
		$baseent    	= $resu->getBaseEnt();
		$basesairs		= $resu->getBaseSaiRS();
		$basesaioe  	= $resu->getBaseSaiOE();
		$numefunc		= $resu->getNumeroFuncionario();
		$valorfolha		= $resu->getValorFolha();
		$datapagto		= $resu->getDataPagto();
		$basesairs4 	= $resu->getBaseSaiRS4();
		$criditosr4 	= $resu->getCriditosR4();
		$creditosr4 	= $resu->getCreditosR4();
		$cnpjemp    	= $resu->getCnpjEmp();
		$numero_entrada = $resu->getNumeroEntrada();
		$numero_saida   = $resu->getNumeroSaida();
		$creditosoe4    = $resu->getCreditosOE4();
		$basesaioe4     = $resu->getBaseSaiOE4();
		
		$sql = 'UPDATE `resumo`
				SET
				`COMPETENC` = "'.$competenc.'",
				`BOVINOS` = "'.$bovinos.'",
				`BUBALINOS` = "'.$bubalinos.'",
				`OVINOS` = "'.$ovinos.'",
				`ICMSNOR` = '.$icmsnor.',
				`SUBSTIT` = '.$substit.',
				`CREDITOENT` = '.$creditoent.',
				`CREDITOSRS` = '.$creditosrs.',
				`CREDITOSOE` = '.$creditosoe.',
				`BASEENT` = '.$baseent.',
				`BASESAIRS` = '.$basesairs.',
				`BASESAIOE` = '.$basesaioe.',
				`NUMEFUNC` = "'.$numefunc.'",
				`VALOFOLHA` = '.$valorfolha.',
				`DATAPAGT` = "'.$datapagto.'",
				`BASESAIRS4` = '.$basesairs4.',
				`CRIDITOSR4` = '.$criditosr4.',
				`CREDITOSR4` = '.$creditosr4.',
				`NUMERO_ENTRADA` = "'.$numero_entrada.'",
				`NUMERO_SAIDA` = "'.$numero_saida.'",
				`cnpj_emp` = "'.$cnpjemp.'",
				`CREDITOSOE4` = '.$creditosoe4.',
				`BASESAIOE4` = '.$basesaioe4.'
				WHERE `id` ='.$codigo;
		
		$dba->query($sql);
	}
	

	public function deletar($usu){
	
		$dba = $this->dba;

		$idu = $usu->getCodigo();
		
		$sql = ''.$idu;

		$dba->query($sql);	
				
	}	
	
	public function createZip($path = 'arquivo.zip', $files = array(), $deleleOriginal = false) {
	/**
	 * Cria o arquivo .zip
	 */
	$zip = new ZipArchive;
	$zip->open( $path, ZipArchive::CREATE);
	
	/**
	 * Checa se o array não está vazio e adiciona os arquivos
	 */
	if ( !empty( $files ) ) {
		/**
		 * Loop do(s) arquivo(s) enviado(s) 
		 */
		foreach ( $files as $file ) {
			/**
			 * Adiciona os arquivos ao zip criado
			 */
			$zip->addFile( $file, basename( $file ) );
					
			/**
			 * Verifica se $deleleOriginal está setada como true,
			 * se sim, apaga os arquivos
			 */
			if ( $deleleOriginal === true ) {
				/**
				 * Apaga o arquivo
				 */
				unlink( $file );
				
				/**
				 * Seta o nome do diretório
				 */
				$dirname = dirname( $file );
			}
		}
		
		/**
		 * Verifica se $deleleOriginal está setada como true,
		 * se sim, apaga a pasta dos arquivos
		 */
		if ( $deleleOriginal === true && !empty( $dirname ) ) {
			rmdir( $dirname );
		}
	}
	
	/**
	 * Fecha o arquivo zip
	 */
	$zip->close();
}
		
}

?>