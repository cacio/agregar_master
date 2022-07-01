<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasSa1TxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	
	public function PegaRestristroParaExclusao($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id, 
					s.cnpj_emp
				FROM
					notassa1txt s
				where
					s.data_emissao between "'.$dtini.'" and "'.$dtfim.'"
						and s.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$cnpj_emp 		  = $dba->result($res,$i,'cnpj_emp');
			
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	
	public function ListaNotasSa1Empresa($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.numero_nota,
					s.data_emissao,
					s.cnpj_cpf,
					s.codigo_produto,
					s.qtd_pecas,
					s.peso,
					s.preco_unitario,
					s.ent_sai,
					s.numero_item_nota,
					s.insc_estadual,
					s.cfop,
					s.aliquota_icms,
					s.cnpj_emp
				FROM
					notassa1txt s
				where
					concat(EXTRACT(MONTH FROM s.data_emissao),"/",EXTRACT(YEAR FROM s.data_emissao)) between "'.$dtini.'" and "'.$dtfim.'"
						and s.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');			
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	  = $dba->result($res,$i,'codigo_produto');
			$qtd_pecas		  = $dba->result($res,$i,'qtd_pecas');
			$peso			  = $dba->result($res,$i,'peso');
			$preco_unitario	  = $dba->result($res,$i,'preco_unitario');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$numero_item_nota = $dba->result($res,$i,'numero_item_nota');	
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
			$aliquota_icms	  = $dba->result($res,$i,'aliquota_icms');	
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numero_nota);
			$notasa1->setDataEmissao($data_emissao);
			$notasa1->setCnpjCpf($cnpj_cpf);
			$notasa1->setCodigoProduto($codigo_produto);
			$notasa1->setQtdPecas($qtd_pecas);
			$notasa1->setPeso($peso);
			$notasa1->setPrecoUnitario($preco_unitario);
			$notasa1->setEntSai($ent_sai);
			$notasa1->setNumeroItemNota($numero_item_nota);		
			$notasa1->setInscEstadual($insc_estadual);
			$notasa1->setCfop($cfop);
			$notasa1->setAliquotaIcms($aliquota_icms);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	
	public function ListaNotasSa1Detalhe($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.numero_nota,
					s.data_emissao,
					s.cnpj_cpf,
					s.codigo_produto,
					s.qtd_pecas,
					s.peso,
					s.preco_unitario,
					s.ent_sai,
					s.numero_item_nota,
					s.insc_estadual,
					s.cfop,
					s.aliquota_icms,
					s.cnpj_emp
				FROM
					notassa1txt s
				where
				concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM s.data_emissao)) = "'.$mesano.'" and 
					s.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');			
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	  = $dba->result($res,$i,'codigo_produto');
			$qtd_pecas		  = $dba->result($res,$i,'qtd_pecas');
			$peso			  = $dba->result($res,$i,'peso');
			$preco_unitario	  = $dba->result($res,$i,'preco_unitario');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$numero_item_nota = $dba->result($res,$i,'numero_item_nota');	
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
			$aliquota_icms	  = $dba->result($res,$i,'aliquota_icms');	
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numero_nota);
			$notasa1->setDataEmissao($data_emissao);
			$notasa1->setCnpjCpf($cnpj_cpf);
			$notasa1->setCodigoProduto($codigo_produto);
			$notasa1->setQtdPecas($qtd_pecas);
			$notasa1->setPeso($peso);
			$notasa1->setPrecoUnitario($preco_unitario);
			$notasa1->setEntSai($ent_sai);
			$notasa1->setNumeroItemNota($numero_item_nota);		
			$notasa1->setInscEstadual($insc_estadual);
			$notasa1->setCfop($cfop);
			$notasa1->setAliquotaIcms($aliquota_icms);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}

	public function ListaNotasSa1DetalheDBF($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT
					distinct 
					s.id,
					s.numero_nota,
					s.data_emissao,
					s.cnpj_cpf,
					a.codigo as codigo_produto,
					s.qtd_pecas,
					s.peso,
					s.preco_unitario,
					s.ent_sai,
					s.numero_item_nota,
					s.insc_estadual,
					s.cfop,
					s.aliquota_icms,
					s.cnpj_emp
				FROM
					notassa1txt s
					LEFT JOIN
				    prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
				    LEFT JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria)
					LEFT JOIN
					cfop_empresa c ON (c.id_cfop = s.cfop)
				where
				concat(lpad(EXTRACT(MONTH FROM s.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM s.data_emissao)) = "'.$mesano.'" and 
					s.cnpj_emp = "'.$cnpj.'" and c.cnpj_emp = "'.$cnpj.'" and p.cnpj_emp = "'.$cnpj.'" and c.gera_agregar = "1" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');			
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	  = $dba->result($res,$i,'codigo_produto');
			$qtd_pecas		  = $dba->result($res,$i,'qtd_pecas');
			$peso			  = $dba->result($res,$i,'peso');
			$preco_unitario	  = $dba->result($res,$i,'preco_unitario');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$numero_item_nota = $dba->result($res,$i,'numero_item_nota');	
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
			$aliquota_icms	  = $dba->result($res,$i,'aliquota_icms');	
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numero_nota);
			$notasa1->setDataEmissao($data_emissao);
			$notasa1->setCnpjCpf($cnpj_cpf);
			$notasa1->setCodigoProduto($codigo_produto);
			$notasa1->setQtdPecas($qtd_pecas);
			$notasa1->setPeso($peso);
			$notasa1->setPrecoUnitario($preco_unitario);
			$notasa1->setEntSai($ent_sai);
			$notasa1->setNumeroItemNota($numero_item_nota);		
			$notasa1->setInscEstadual($insc_estadual);
			$notasa1->setCfop($cfop);
			$notasa1->setAliquotaIcms($aliquota_icms);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}

	public function DetalheProdutoNotaSaida($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.numero_nota,
					s.data_emissao,
					s.cnpj_cpf,
					s.codigo_produto,
					s.qtd_pecas,
					s.peso,
					s.preco_unitario,
					s.ent_sai,
					s.numero_item_nota,
					s.insc_estadual,
					s.cfop,
					s.aliquota_icms,
					s.cnpj_emp
				FROM
					notassa1txt s
				'.$where.' ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');			
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	  = $dba->result($res,$i,'codigo_produto');
			$qtd_pecas		  = $dba->result($res,$i,'qtd_pecas');
			$peso			  = $dba->result($res,$i,'peso');
			$preco_unitario	  = $dba->result($res,$i,'preco_unitario');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$numero_item_nota = $dba->result($res,$i,'numero_item_nota');	
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
			$aliquota_icms	  = $dba->result($res,$i,'aliquota_icms');	
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numero_nota);
			$notasa1->setDataEmissao($data_emissao);
			$notasa1->setCnpjCpf($cnpj_cpf);
			$notasa1->setCodigoProduto($codigo_produto);
			$notasa1->setQtdPecas($qtd_pecas);
			$notasa1->setPeso($peso);
			$notasa1->setPrecoUnitario($preco_unitario);
			$notasa1->setEntSai($ent_sai);
			$notasa1->setNumeroItemNota($numero_item_nota);		
			$notasa1->setInscEstadual($insc_estadual);
			$notasa1->setCfop($cfop);
			$notasa1->setAliquotaIcms($aliquota_icms);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	
	public function PegaExclusao($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id					
				FROM
					notassa1txt s
				where
					s.numero_nota = "'.$cod.'"
						and s.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	

	public function PegaExclusaoCompetencia($dtcomp,$cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id					
				FROM
					notassa1txt s
				where
					DATE_FORMAT(ns.data_emissao, "%m/%Y") = "'.$dtcomp.'" and
					s.numero_nota = "'.$cod.'"
						and s.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	
	public function DetalheNotaSaida($numero,$comp,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					s.id,
					s.numero_nota,
					s.data_emissao,
					s.cnpj_cpf,
					s.codigo_produto,
					p.desc_prod,
					s.qtd_pecas,
					s.qtd_prod,
					s.peso,
					s.preco_unitario,
					s.ent_sai,
					s.numero_item_nota,
					s.insc_estadual,
					s.cfop,
					s.aliquota_icms,
					s.cnpj_emp
				FROM
					notassa1txt s
				LEFT JOIN produtotxt p on (p.cod_prod = s.codigo_produto) and (p.cnpj_emp = s.cnpj_emp) 
					WHERE
					s.numero_nota = "'.$numero.'"
						AND CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao),
									2,
									"0"),
							"/",
							EXTRACT(YEAR FROM s.data_emissao)) = "'.$comp.'"
						AND s.cnpj_emp = "'.$cnpj.'" ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');			
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	  = $dba->result($res,$i,'codigo_produto');
			$qtd_pecas		  = $dba->result($res,$i,'qtd_pecas');
			$peso			  = $dba->result($res,$i,'peso');
			$preco_unitario	  = $dba->result($res,$i,'preco_unitario');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$numero_item_nota = $dba->result($res,$i,'numero_item_nota');	
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
			$aliquota_icms	  = $dba->result($res,$i,'aliquota_icms');	
			$desc_prod		  = $dba->result($res,$i,'desc_prod');	
			$qtd_prod		  = $dba->result($res,$i,'qtd_prod');	
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numero_nota);
			$notasa1->setDataEmissao($data_emissao);
			$notasa1->setCnpjCpf($cnpj_cpf);
			$notasa1->setCodigoProduto($codigo_produto);
			$notasa1->setQtdPecas($qtd_pecas);
			$notasa1->setPeso($peso);
			$notasa1->setPrecoUnitario($preco_unitario);
			$notasa1->setEntSai($ent_sai);
			$notasa1->setNumeroItemNota($numero_item_nota);		
			$notasa1->setInscEstadual($insc_estadual);
			$notasa1->setCfop($cfop);
			$notasa1->setAliquotaIcms($aliquota_icms);
			$notasa1->setDescProd($desc_prod);
			$notasa1->setProdQtd($qtd_prod);

			$vet[$i] = $notasa1;

		}

		return $vet;

	}
	
	public function PegaExclusaoCfopDesconsiderar($cfop,$cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT
					DISTINCT 
					n.id,
					n.numero_nota
				FROM
					notassa1txt n
						LEFT JOIN
					cfop_empresa c ON (c.id_cfop = n.cfop)
				WHERE
					c.gera_agregar = "2"
						AND c.id_cfop = "'.$cfop.'"  AND n.cnpj_emp = "'.$cnpj.'"
						AND CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
									2,
									"0"),
							"/",
							EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id         = $dba->result($res,$i,'id');
			$numeronota = $dba->result($res,$i,'numero_nota');
				
			$notasa1 = new NotasSa1Txt();

			$notasa1->setCodigo($id);
			$notasa1->setNumeroNota($numeronota);
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}

	public function RelRelacaoSaidaProduto($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
					cod_prod,
					xprod,
					sum(peso) as peso,
					sum(subtotal) as subtotal
					from
				(
					SELECT 
				
					pt.cod_prod, 
					pt.desc_prod as xprod, 
					s.numero_item_nota,
					cast(s.peso as decimal(10,2)) as peso,    
					if(s.ent_sai = "E", cast(s.preco_unitario * s.peso * -1  AS DECIMAL (10 , 2 )), cast(s.preco_unitario * s.peso * 1  AS DECIMAL (10 , 2 ))) as subtotal
				FROM
					notassaitxt m
						INNER JOIN
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						AND (s.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = m.cnpj_emp)
						INNER join 
						produtotxt pt on (pt.cod_prod = p.cod_prod) and (pt.cnpj_emp = m.cnpj_emp) 
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf)
						AND (e.cnpj_emp = m.cnpj_emp)
				'.$where.'
					) as tab group by cod_prod,xprod';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod_prod		  = $dba->result($res,$i,'cod_prod');
			$desc_prod		  = $dba->result($res,$i,'xprod');	
			$peso			  = $dba->result($res,$i,'peso');
			$subtotal		  = $dba->result($res,$i,'subtotal');

			$notasa1 = new NotasSa1Txt();
		
			$notasa1->setCodigoProduto($cod_prod);		
			$notasa1->setDescProd($desc_prod);
			$notasa1->setPeso($peso);
			$notasa1->setSubtotal($subtotal);
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}

	public function RelatorioFechamentoPorCfopSaida($where,$where2){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
					cod_secretaria,
					descprod,
					sum(subtotal) as subtotal,					
					cfop,
					Nome,
					devolucao,
					uf
				from (
				SELECT 
					s.numero_item_nota,
					s.numero_nota,          
					a.codigo AS cod_secretaria,
					concat("(",p.cod_prod,") - ", p.desc_prod) as descprod,    
					if(s.ent_sai = "E", cast(s.preco_unitario * s.peso * -1  AS DECIMAL (10 , 2 )), cast(s.preco_unitario * s.peso * 1  AS DECIMAL (10 , 2 ))) as subtotal,
					s.cfop,
					cf.Nome,
					cf.devolucao,
					e.uf
				FROM    
					notassa1txt s
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = s.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf)
						AND (e.cnpj_emp = s.cnpj_emp)
					inner join
					cfop cf on (cf.Codigo = s.cfop)   
				'.$where.'

				union all

				SELECT 
						s.numero_item_nota,
						s.numero_nota,
						a.codigo AS cod_secretaria,
						CONCAT("(", p.cod_prod, ") - ", p.desc_prod) AS descprod,						
						if(s.ent_sai = "E", cast(s.preco_unitario * s.peso * -1  AS DECIMAL (10 , 2 )), cast(s.preco_unitario * s.peso * 1  AS DECIMAL (10 , 2 ))) as subtotal,
						s.cfop,
						cf.Nome,
						cf.devolucao,						
						e.uf
				FROM
					notassa1txt s
				INNER JOIN prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
					AND (p.cnpj_emp = s.cnpj_emp)
				INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
				INNER JOIN empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf)
					AND (e.cnpj_emp = s.cnpj_emp)
				INNER JOIN cfop cf ON (cf.Codigo = s.cfop)    
        	'.$where2.'
				) as tab  group by cod_secretaria,descprod,cfop, Nome, devolucao,uf order by cfop';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod_secretaria	= $dba->result($res,$i,'cod_secretaria');
			$descprod		= $dba->result($res,$i,'descprod');	
			$subtotal    	= $dba->result($res,$i,'subtotal');			
			$cfop			= $dba->result($res,$i,'cfop');	
			$Nome			= $dba->result($res,$i,'Nome');
			$devolucao      = $dba->result($res,$i,'devolucao');
			$uf		        = $dba->result($res,$i,'uf');

			$notasa1 = new NotasSa1Txt();
				
			$notasa1->setCodSecretaria($cod_secretaria);
			$notasa1->setDescProd($descprod);
			$notasa1->setSubtotal($subtotal);
			$notasa1->setCfop($cfop);
			$notasa1->setNomeCfop($Nome);
			$notasa1->setDevolucao($devolucao);
			$notasa1->setUf($uf);
			
			$vet[$i] = $notasa1;

		}

		return $vet;

	}

	public function PegaValorDevolucaoSidaparaEntrada($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
					sum(if(ent_sai = "E", cast(preco_unitario * peso * -1  AS DECIMAL (10 , 2 )), cast(preco_unitario * peso * 1  AS DECIMAL (10 , 2 )))) as valor
				from (SELECT 
						s.numero_item_nota,
						s.numero_nota,					
						s.preco_unitario,
						s.peso,
						s.ent_sai       
					FROM
						notassa1txt s
					INNER JOIN prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = s.cnpj_emp)
					INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
					INNER JOIN empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf)
						AND (e.cnpj_emp = s.cnpj_emp)
					INNER JOIN cfop cf ON (cf.Codigo = s.cfop)
					'.$where.'
					ORDER BY s.cfop) as tab';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$valor	= $dba->result($res,$i,'valor');

			$notasa1 = new NotasSa1Txt();
				
			$notasa1->setValorDevolucao($valor);
		
			$vet[$i] = $notasa1;

		}

		return $vet;


	}
	


	public function inserir($notasa1txt){

		$dba = $this->dba;		
		
		$numero_nota    	  = $notasa1txt->getNumeroNota();
		$dataemissao    	  = $notasa1txt->getDataEmissao();
		$cnpjcpf	    	  = $notasa1txt->getCnpjCpf();
		$codigoproduto		  =	$notasa1txt->getCodigoProduto();
		$qtdpecas			  =	$notasa1txt->getQtdPecas();
		$peso				  =	$notasa1txt->getPeso();
		$precounitario		  =	$notasa1txt->getPrecoUnitario();
		$entsai				  =	$notasa1txt->getEntSai();
		$numeroitemnota		  =	$notasa1txt->getNumeroItemNota();		
		$inscestadual		  = $notasa1txt->getInscEstadual();
		$cfop				  = $notasa1txt->getCfop();
		$aliquotaicms		  = $notasa1txt->getAliquotaIcms();
		$cnpjemp              = $notasa1txt->getCnpjEmp();
		$prodqtd              = $notasa1txt->getProdQtd();
		$vicms				  = $notasa1txt->getVicms();

		$sql = 'INSERT INTO `notassa1txt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`codigo_produto`,
				`qtd_pecas`,
				`peso`,
				`preco_unitario`,
				`ent_sai`,
				`numero_item_nota`,
				`insc_estadual`,
				`cfop`,
				`aliquota_icms`,
				`cnpj_emp`,
				`qtd_prod`,
				`vICMS`)
				VALUES
				("'.$numero_nota.'",
				"'.$dataemissao.'",
				"'.$cnpjcpf.'",
				"'.$codigoproduto.'",
				'.$qtdpecas.',
				"'.$peso.'",
				'.$precounitario.',
				"'.$entsai.'",
				'.$numeroitemnota.',
				"'.$inscestadual.'",
				"'.$cfop.'",
				'.$aliquotaicms.',
				"'.$cnpjemp.'",
				'.$prodqtd.',
				'.$vicms.')';
						
		$dba->query($sql);	
							
	}
	
	public function update($notasa1txt){

		$dba = $this->dba;		
		
		
		$id 	 			  = $notasa1txt->getCodigo();
		$qtdpecas			  =	$notasa1txt->getQtdPecas();
		$peso				  =	$notasa1txt->getPeso();
		$precounitario		  =	$notasa1txt->getPrecoUnitario();		
		$prodqtd              = $notasa1txt->getProdQtd();

		$sql = 'UPDATE `notassa1txt`
				SET
				`qtd_pecas` = '.$qtdpecas.',
				`peso` = "'.$peso.'",
				`preco_unitario` = '.$precounitario.',
				`qtd_prod` = '.$prodqtd.'
				WHERE `id` = '.$id.' ';
						
		$dba->query($sql);	
							
	}

	public function deletar($notasa1txt){
	
		$dba = $this->dba;

		$id 	 = $notasa1txt->getCodigo();
		$cnpjemp = $notasa1txt->getCnpjEmp();
		
		$sql = 'DELETE FROM `notassa1txt`
				WHERE cnpj_emp = "'.$cnpjemp.'" and  id = '.$id.'';

		$dba->query($sql);	
				
	}		
}

?>