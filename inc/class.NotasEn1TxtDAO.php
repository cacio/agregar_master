<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasEn1TxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}
	
	
	public function ListaNotasEntradaDetalhe($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.codigo_produto,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n 
					where concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" and 
						 n.cnpj_emp = "'.$cnpj.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function ListaNotasEntradaDetalheParaExclusao($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.codigo_produto,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n 
					where (concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" or
					concat(lpad(EXTRACT(MONTH FROM n.data_abate),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'") and 
						 n.cnpj_emp = "'.$cnpj.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function VerificaSeExisteAbate($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.codigo_produto,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n 
				'.$where.' ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	
	public function DetalheProdutoNota($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.codigo_produto,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n 
				'.$where.' ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function PegaRegistroParaExclusao($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,	
					n.cnpj_emp
				FROM
					notasen1txt n 
				 WHERE (n.data_emissao between "'.$dtini.'" and "'.$dtfim.'" or n.data_abate between "'.$dtini.'" and "'.$dtfim.'") and n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	
	public function ListagemTotalDeAnimaisAbatidos($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
			sum(case
				when trim(a.especie) = "BOVINOS" then n.qtd_cabeca
				else 0
			end) as BOVINOS,
			sum(case
				when trim(a.especie) = "OVINOS" then n.qtd_cabeca
				else 0
			end) as OVINOS,
			sum(case
				when trim(a.especie) = "BUBALINOS" then n.qtd_cabeca
				else 0
			end) as BUBALINOS
			from
				notasen1txt n
					inner join
				prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
					inner join
				produtos_agregar a ON (a.codigo = p.cod_secretaria)
				'.$where.' ';
		 //echo "{$sql}"; 
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$bovinos   = $dba->result($res,$i,'BOVINOS');
			$ovinos    = $dba->result($res,$i,'OVINOS');
			$bubalinos = $dba->result($res,$i,'BUBALINOS');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setBovinos($bovinos);
			$notasen1->setBubalinos($bubalinos);
			$notasen1->setOvinos($ovinos);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function ListagemTotalDeAnimaisAbatidosDev($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
			sum(case
				when trim(a.especie) = "BOVINOS" then n.qtd_cabeca
				else 0
			end) as BOVINOS,
			sum(case
				when trim(a.especie) = "OVINOS" then n.qtd_cabeca
				else 0
			end) as OVINOS,
			sum(case
				when trim(a.especie) = "BUBALINOS" then n.qtd_cabeca
				else 0
			end) as BUBALINOS
			from
				notasen1txt n
					inner join
				prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
					inner join
				produtos_agregar a ON (a.codigo = p.cod_secretaria)
				'.$where.' ';
		 //echo "{$sql}"; 
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$bovinos   = $dba->result($res,$i,'BOVINOS');
			$ovinos    = $dba->result($res,$i,'OVINOS');
			$bubalinos = $dba->result($res,$i,'BUBALINOS');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setBovinos($bovinos);
			$notasen1->setBubalinos($bubalinos);
			$notasen1->setOvinos($ovinos);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	
	public function TotalDeProdutores($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT SUM(rendimento) AS rendimento,SUM(vivo) AS vivo FROM ( SELECT 
					distinct
						n.numero_item_nota,
						n.numero_nota,
						CAST(CASE
								WHEN n.tipo_r_v = "R" THEN n.preco_quilo * n.peso_carcasa
								ELSE 0
							END
							AS DECIMAL(10 , 2 )) rendimento,
						CAST(CASE
								WHEN n.tipo_r_v = "V" THEN n.preco_quilo * n.peso_vivo_cabeca
								ELSE 0
							END
							AS DECIMAL(10 , 2 )) AS vivo
					FROM
						notasen1txt n
						 INNER JOIN
						prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
						INNER JOIN
						produtos_agregar a ON (a.codigo = p.cod_secretaria)
					   '.$where.') AS TAB ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$vivo   	= $dba->result($res,$i,'vivo');
			$rendimento = $dba->result($res,$i,'rendimento');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setVivo($vivo);
			$notasen1->setRendimento($rendimento);
			
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function TotalDeProdutoresPorNota($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
				SUM(valor_total_nota) AS valor_total_nota
				FROM
					(SELECT DISTINCT        
						n.numero_nota,
						nt.valor_total_nota    
					FROM
						notasenttxt nt
					inner join notasen1txt n on (n.numero_nota = nt.numero_nota) and (n.cnpj_emp = nt.cnpj_emp)          
					INNER JOIN prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
					INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
					'.$where.') AS TAB';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$totalnota = $dba->result($res,$i,'valor_total_nota');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setValorTotalNota($totalnota);
									
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function PegaExclusao($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id
				FROM
					notasen1txt n 
				 WHERE n.numero_nota = "'.$cod.'" and n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	      = $dba->result($res,$i,'id');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function VerificaCabecasPreenchidas($nnota,$cod,$cnpj,$nitem,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.qtd_cabeca,
					n.tipo_r_v,
					n.peso_carcasa
				FROM
					notasen1txt n
					LEFT JOIN
				    prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
				    LEFT JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria)
					inner join cfop c on (c.Codigo = n.cfop)
				WHERE
					n.numero_nota = "'.$nnota.'"
						AND n.codigo_produto = "'.$cod.'"
						AND n.cnpj_emp = "'.$cnpj.'" 
						and p.cnpj_emp = "'.$cnpj.'" and 
						cast(n.numero_item_nota as UNSIGNED) = "'.$nitem.'"  
						and COALESCE(p.cod_secretaria ,0) <> "99999" 
						and COALESCE(p.cod_secretaria ,0) <> "99998" and 
						COALESCE(p.cod_secretaria ,0) <> "99997" and 
						COALESCE(p.cod_secretaria ,0) <> "99996" and c.devolucao <> "S" AND (concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" or
					concat(lpad(EXTRACT(MONTH FROM n.data_abate),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'")';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$qtd_cabeca     = $dba->result($res,$i,'qtd_cabeca');
			$tipo_r_v       = $dba->result($res,$i,'tipo_r_v');	
			$peso_carcasa   = $dba->result($res,$i,'peso_carcasa');

			$notasen1 = new NotasEn1Txt();

			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setPesoCarcasa($peso_carcasa);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function VerificaIdCabecas($nnota,$cod,$cnpj,$iditem){

		$dba = $this->dba;

		$vet = array();
		/*and n.id = '.$idc.'*/
		$sql = 'SELECT 
					n.id
				FROM
					notasen1txt n
				WHERE
					n.numero_nota = "'.$nnota.'"
						AND n.codigo_produto = "'.$cod.'"
						AND n.cnpj_emp = "'.$cnpj.'"
						and n.numero_item_nota = "'.$iditem.'"  ';
		//echo $sql; 	   		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id     = $dba->result($res,$i,'id');
			
			
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function VerificaIdCabecas2($nnota,$cod,$cnpj,$item,$mesano){

		$dba = $this->dba;

		$vet = array();
		/*and n.id = '.$idc.'*/
		$sql = 'SELECT 
					n.id
				FROM
					notasen1txt n
				WHERE
					n.numero_nota = "'.$nnota.'"
						AND n.codigo_produto = "'.$cod.'"
						AND n.cnpj_emp = "'.$cnpj.'" AND n.numero_item_nota = "'.$item.'" AND (concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" or
					concat(lpad(EXTRACT(MONTH FROM n.data_abate),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'")';
		//echo $sql; 	   		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id     = $dba->result($res,$i,'id');
			
			
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function PegaQtdCabecas($nnota,$cod,$cnpj,$iditem){

		$dba = $this->dba;

		$vet = array();
		/*and n.id = '.$idc.'*/
		$sql = 'SELECT 
					n.qtd_cabeca
				FROM
					notasen1txt n
				WHERE
					n.numero_nota = "'.$nnota.'"
						AND n.codigo_produto = "'.$cod.'"
						AND n.cnpj_emp = "'.$cnpj.'"
						and n.numero_item_nota = "'.$iditem.'"  ';
		//echo $sql; 	   		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$qtd_cabeca  = $dba->result($res,$i,'qtd_cabeca');
			
			
			$notasen1 = new NotasEn1Txt();

			$notasen1->setQtdCabeca($qtd_cabeca);
			
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function PegaExclusaoCompetencia($dtcomp,$cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT en.id FROM notasen1txt en where DATE_FORMAT(en.data_emissao, "%m/%Y") = "'.$dtcomp.'" and en.numero_nota = "'.$cod.'" and en.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	      = $dba->result($res,$i,'id');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function RelatorioRelacaoAbate($where){

		$dba = $this->dba;

		$vet = array();

		$sql = "SELECT 
				    e.cnpj,
				    e.razao_social,
				    e.cidade,
				    SUM(CASE
				        WHEN a.sexo = 'M' AND a.especie = 'BOVINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS macho_bovinos,
				    SUM(CASE
				        WHEN a.sexo = 'F' AND a.especie = 'BOVINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS femea_bovinos,
				    SUM(CASE
				        WHEN a.sexo = 'M' AND a.especie = 'BUBALINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS macho_bubalinos,
				    SUM(CASE
				        WHEN a.sexo = 'F' AND a.especie = 'BUBALINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS femea_bubalinos,
				    SUM(CASE
				        WHEN a.sexo = 'M' AND a.especie = 'OVINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS macho_ovinos,
				    SUM(CASE
				        WHEN a.sexo = 'F' AND a.especie = 'OVINOS' THEN n.qtd_cabeca
				        ELSE 0
				    END) AS femea_ovinos,
				    SUM(CASE
				        WHEN n.tipo_r_v = 'R' THEN n.peso_carcasa * n.preco_quilo
				        ELSE 0
				    END) AS valor_total_rendimento,
				    SUM(CASE
				        WHEN n.tipo_r_v = 'V' THEN n.peso_vivo_cabeca * n.preco_quilo
				        ELSE 0
				    END) AS valor_total_vivo
				FROM
				    empresas e
				        INNER JOIN
				    notasen1txt n ON (n.cnpj_emp = e.cnpj)
				        INNER JOIN
				    prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
				        INNER JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria) 
				    ".$where." GROUP BY e.cnpj , e.razao_social , e.cidade ";
//		echo $sql;		    
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cnpj  		   	 		= $dba->result($res,$i,'cnpj');
			$razao_social  	 		= $dba->result($res,$i,'razao_social');
			$cidade		     		= $dba->result($res,$i,'cidade');
			$macho_bovinos	 		= $dba->result($res,$i,'macho_bovinos');
			$femea_bovinos 	 		= $dba->result($res,$i,'femea_bovinos');
			$macho_bubalinos 		= $dba->result($res,$i,'macho_bubalinos');
			$femea_bubalinos 		= $dba->result($res,$i,'femea_bubalinos');
			$macho_ovinos    		= $dba->result($res,$i,'macho_ovinos');
			$femea_ovinos    		= $dba->result($res,$i,'femea_ovinos');
			$valor_total_rendimento = $dba->result($res,$i,'valor_total_rendimento');
			$valor_total_vivo		= $dba->result($res,$i,'valor_total_vivo');

			$notasen1 = new NotasEn1Txt();
						
			$notasen1->setCnpjEmp($cnpj);
			$notasen1->setRazaoSocialEmpresa($razao_social);
			$notasen1->setCidadeEmpresa($cidade);
			$notasen1->setMachoBovino($macho_bovinos);
			$notasen1->setFemaBovino($femea_bovinos);
			$notasen1->setMachoBubalino($macho_bubalinos);
			$notasen1->setFemeaBubalino($femea_bubalinos);
			$notasen1->setMachoOvinos($macho_ovinos);
			$notasen1->setFemeaOvinos($femea_ovinos);
			$notasen1->setValorTotalRendimento($valor_total_rendimento);
			$notasen1->setValorTotalVivo($valor_total_vivo);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}


	public function RelatorioRelacaoBeneficioArrecadacao($where,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = "SELECT 				    
				    SUM(CASE
				        WHEN n.tipo_r_v = 'R' THEN n.peso_carcasa * n.preco_quilo
				        ELSE 0
				    END) AS valor_total_rendimento,
				    SUM(CASE
				        WHEN n.tipo_r_v = 'V' THEN n.peso_vivo_cabeca * n.preco_quilo
				        ELSE 0
				    END) AS valor_total_vivo
				FROM
				    empresas e
				        INNER JOIN
				    notasen1txt n ON (n.cnpj_emp = e.cnpj)
				        INNER JOIN
				    prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
				        INNER JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria) 
				    ".$where." and e.cnpj = '".$cnpj."' ";
	    
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		
		for($i = 0; $i<$num; $i++){		
			
			$valor_total_rendimento = $dba->result($res,$i,'valor_total_rendimento');
			$valor_total_vivo		= $dba->result($res,$i,'valor_total_vivo');

			$notasen1 = new NotasEn1Txt();
								
			$notasen1->setValorTotalRendimento($valor_total_rendimento);
			$notasen1->setValorTotalVivo($valor_total_vivo);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}


	public function RelNotasEntrada($where){

		$dba = $this->dba;

		$vet = array();

		$sql = ' SELECT 					
				    n.id,
				    n.numero_nota,
				    n.data_emissao,
				    n.cnpj_cpf,
				    e.insc_estadual,
				    e.razao,
				    a.codigo as cod_secretaria,
				    a.descricao,
				    n.qtd_cabeca,
				    n.peso_vivo_cabeca,
				    n.peso_carcasa,
				    n.preco_quilo,
				    n.tipo_r_v,
				    n.cfop,    
				    n.cnpj_emp,
				    en.valor_total_nota,
				    n.codigo_produto,
				    p.id as pkrel
				FROM
				    notasen1txt n
				    	INNER JOIN
					    notasenttxt en ON (en.numero_nota = n.numero_nota)
					        AND (en.cnpj_emp = n.cnpj_emp)
				        left JOIN
				    prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
				        AND (p.cnpj_emp = n.cnpj_emp)
				        left JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria)
				        INNER JOIN
				    empresastxt e ON (e.cnpj_cpf = n.cnpj_cpf)
				        AND (e.cnpj_emp = n.cnpj_emp) and (e.insc_estadual = n.insc_estadual)
				        '.$where.' ORDER BY n.numero_nota';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$razao     	        = $dba->result($res,$i,'razao');
			$cod_secretaria	    = $dba->result($res,$i,'cod_secretaria');
			$descricao          = $dba->result($res,$i,'descricao');			
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');			
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');			
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');								
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');			
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
			$valor_total_nota   = $dba->result($res,$i,'valor_total_nota');
			$codigo_produto     = $dba->result($res,$i,'codigo_produto');
			$pkrel 		        = $dba->result($res,$i,'pkrel');

			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setRazaoSocialEmpresa($razao);
			$notasen1->setCodSecretaria($cod_secretaria);
			$notasen1->setDescSecretaria($descricao);			
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);									
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);			
			$notasen1->setCnpjEmp($cnpj_emp);
			$notasen1->setValorTotalNota($valor_total_nota);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setPkRelacionamento($pkrel);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	

	public function DetalheNotaEntrada($numero,$comp,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.codigo_produto,
					p.desc_prod,
					n.qtd_prod,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n 
					LEFT JOIN produtotxt p on (p.cod_prod = n.codigo_produto) and (p.cnpj_emp = n.cnpj_emp) 	
									WHERE
					n.numero_nota = "'.$numero.'"
						AND (CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
									2,
									"0"),
							"/",
							EXTRACT(YEAR FROM n.data_emissao)) = "'.$comp.'" or CONCAT(LPAD(EXTRACT(MONTH FROM n.data_abate),
									2,
									"0"),
							"/",
							EXTRACT(YEAR FROM n.data_abate)) = "'.$comp.'")
						AND n.cnpj_emp = "'.$cnpj.'" ';
			  		//echo "{$sql}";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
			$desc_prod		    = $dba->result($res,$i,'desc_prod');
			$qtd_prod		    = $dba->result($res,$i,'qtd_prod');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			$notasen1->setDescProd($desc_prod);
			$notasen1->setProdQtd($qtd_prod);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	
	public function ListaNotasEntradaDetalheDBF($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT
					distinct	 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					a.codigo AS codigo_produto,
					n.qtd_cabeca,
					n.peso_vivo_cabeca,
					n.peso_carcasa,
					n.preco_quilo,
					n.numero_item_nota,
					n.insc_estadual,
					n.data_abate,
					n.tipo_r_v,
					n.cfop,
					n.aliquota_icms,
					n.cnpj_emp
				FROM
					notasen1txt n
					LEFT JOIN
				    prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
				    LEFT JOIN
				    produtos_agregar a ON (a.codigo = p.cod_secretaria) 
					where concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" and 
						 n.cnpj_emp = "'.$cnpj.'" and p.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
			$data_emissao	    = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf	   		= $dba->result($res,$i,'cnpj_cpf');
			$codigo_produto	    = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca	    	= $dba->result($res,$i,'qtd_cabeca');
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');
			$preco_quilo	    = $dba->result($res,$i,'preco_quilo');
			$numero_item_nota   = $dba->result($res,$i,'numero_item_nota');
			$insc_estadual	    = $dba->result($res,$i,'insc_estadual');
			$data_abate		    = $dba->result($res,$i,'data_abate');
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');
			$cfop			    = $dba->result($res,$i,'cfop');
			$aliquota_icms	    = $dba->result($res,$i,'aliquota_icms');	
			$cnpj_emp		    = $dba->result($res,$i,'cnpj_emp');
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setCnpjCpf($cnpj_cpf);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setNumeroItemNota($numero_item_nota);
			$notasen1->setInsEstadual($insc_estadual);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCfop($cfop);
			$notasen1->setAliquotaIcms($aliquota_icms);
			$notasen1->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}


	public function getVivoRendimentoVazio($mesano,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
		n.id, n.peso_vivo_cabeca, n.peso_carcasa, n.tipo_r_v,n.codigo_produto,n.numero_nota
	FROM
		notasen1txt n
	WHERE
		CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
						2,
						"0"),
				"/",
				EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'"
			AND n.cnpj_emp = "'.$cnpj.'"
			AND (n.tipo_r_v IS NULL OR n.tipo_r_v = "")';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');			
			$peso_vivo_cabeca	= $dba->result($res,$i,'peso_vivo_cabeca');
			$peso_carcasa	    = $dba->result($res,$i,'peso_carcasa');									
			$tipo_r_v		    = $dba->result($res,$i,'tipo_r_v');		
			$codprod			= $dba->result($res,$i,'codigo_produto');
			$numnota			= $dba->result($res,$i,'numero_nota');				
				
			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);			
			$notasen1->setPesoVivoCabeca($peso_vivo_cabeca);
			$notasen1->setPesoCarcasa($peso_carcasa);			
			$notasen1->setTipo_R_V($tipo_r_v);
			$notasen1->setCodigoProduto($codprod);
			$notasen1->setNumeroNota($numnota);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function PegaExclusaoCfopDesconsiderar($cfop,$cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
		distinct
		n.id,
		n.numero_nota    
	 FROM notasen1txt n
	 LEFT JOIN
		cfop_empresa c ON (c.id_cfop = n.cfop)
		where 
		 c.gera_agregar = "2"
			AND c.id_cfop = "'.$cfop.'"  AND n.cnpj_emp = "'.$cnpj.'"
			AND CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
						2,
						"0"),
				"/",
				EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" ';
			//echo $sql;  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	         = $dba->result($res,$i,'id');
			$numero_nota = $dba->result($res,$i,'numero_nota');

			$notasen1 = new NotasEn1Txt();

			$notasen1->setCodigo($id);
			$notasen1->setNumeroNota($numero_nota);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function ListaQtdCabeçaParaExcel($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
				distinct
				n.numero_nota,
				(select substring(nt.chave_acesso,23,3) from notasenttxt nt where nt.data_emissao = n.data_emissao and nt.numero_nota = n.numero_nota and nt.cnpj_emp = n.cnpj_emp) as serie,
				n.codigo_produto,
				n.numero_item_nota,
				n.qtd_cabeca    
			FROM
				notasen1txt n
					LEFT JOIN
					prodfrigtxt p ON (p.cod_prod = n.codigo_produto) AND (p.cnpj_emp = n.cnpj_emp)
					LEFT JOIN
				produtos_agregar a ON (a.codigo = p.cod_secretaria)
					INNER JOIN
				cfop c ON (c.Codigo = n.cfop)
			WHERE
				n.cnpj_emp = "'.$cnpj.'"
					/*AND p.cnpj_emp = "'.$cnpj.'" */
					AND CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
						2,
						"0"),
				"/",
				EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'"
					AND COALESCE(p.cod_secretaria, 0) <> "99999"
					AND COALESCE(p.cod_secretaria, 0) <> "99998"
					AND COALESCE(p.cod_secretaria, 0) <> "99997"
					AND COALESCE(p.cod_secretaria, 0) <> "99996"
					AND c.devolucao <> "S" and 
					(n.qtd_cabeca = 0 or n.qtd_cabeca is null or n.qtd_cabeca = "" or n.qtd_cabeca > 1000) order by n.numero_nota,n.numero_item_nota asc ';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$numero_nota    = $dba->result($res,$i,'numero_nota');	
			$serie		    = $dba->result($res,$i,'serie');
			$codigo_produto = $dba->result($res,$i,'codigo_produto');
			$qtd_cabeca     = $dba->result($res,$i,'qtd_cabeca');
			$item 		    = $dba->result($res,$i,'numero_item_nota');

			$notasen1 = new NotasEn1Txt();

			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setSerie($serie);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setNumeroItemNota($item);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function RelDocumentoForaApuracaoEntrada($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT DISTINCT
					n.numero_item_nota,
					n.numero_nota,
					CAST(CASE
							WHEN n.tipo_r_v = "R" THEN n.preco_quilo * n.peso_carcasa
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) rendimento,
					CAST(CASE
							WHEN n.tipo_r_v = "V" THEN n.preco_quilo * n.peso_vivo_cabeca
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) AS vivo,                
						n.codigo_produto,
						pt.desc_prod,                
						p.cod_secretaria,
						p.desc_prod as dessecretaria,
						n.peso_carcasa,
						n.peso_vivo_cabeca,
						n.preco_quilo,
						n.tipo_r_v
			FROM
				notasen1txt n
			INNER JOIN prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
			INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
			inner join produtotxt pt on (pt.cod_prod = n.codigo_produto) and (pt.cnpj_emp = n.cnpj_emp)
			'.$where.' ';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$vivo   	    = $dba->result($res,$i,'vivo');
			$rendimento     = $dba->result($res,$i,'rendimento');
			$numero_nota    = $dba->result($res,$i,'numero_nota');
			$codigo_produto = $dba->result($res,$i,'codigo_produto');
			$desc_prod      = $dba->result($res,$i,'desc_prod');
			$cod_secretaria = $dba->result($res,$i,'cod_secretaria');
			$dessecretaria  = $dba->result($res,$i,'dessecretaria');
			$peso_carcasa   = $dba->result($res,$i,'peso_carcasa');
			$peso_vivo_cabe = $dba->result($res,$i,'peso_vivo_cabeca');
			$preco_quilo    = $dba->result($res,$i,'preco_quilo');
			$tipo_r_v       = $dba->result($res,$i,'tipo_r_v');

			$notasen1 = new NotasEn1Txt();

			$notasen1->setVivo($vivo);
			$notasen1->setRendimento($rendimento);
			$notasen1->setNumeroNota($numero_nota);
			$notasen1->setCodigoProduto($codigo_produto);
			$notasen1->setDescProd($desc_prod);
			$notasen1->setCodSecretaria($cod_secretaria);
			$notasen1->setDescSecretaria($dessecretaria);
			$notasen1->setPesoCarcasa($peso_carcasa);
			$notasen1->setPesoVivoCabeca($peso_vivo_cabe);
			$notasen1->setPrecoQuilo($preco_quilo);
			$notasen1->setTipo_R_V($tipo_r_v);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}


	public function VerificaDataAbate($mesano,$cnpjemp,$numero_nota,$cprod,$numitem){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select s.data_emissao,s.data_abate from notasen1txt s 
				where CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, "0"), "/", EXTRACT(YEAR FROM s.data_emissao)) = "'.$mesano.'" 
				and  s.cnpj_emp = "'.$cnpjemp.'" 
				and s.numero_nota = "'.$numero_nota.'" 
				and s.codigo_produto = "'.$cprod.'" 
				and cast(s.numero_item_nota as UNSIGNED) = "'.$numitem.'"';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$data_emissao   = $dba->result($res,$i,'data_emissao');	
			$data_abate	    = $dba->result($res,$i,'data_abate');
			
			$notasen1 = new NotasEn1Txt();

			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setDataAbate($data_abate);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function VerificaDataAbateTxt($mesano,$cnpjemp,$numero_nota,$cprod,$numitem){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select s.data_emissao,s.data_abate,p.desc_prod from notasen1txt s
				INNER JOIN prodfrigtxt p ON (p.cod_prod = s.codigo_produto) and (p.cnpj_emp = s.cnpj_emp) 
				where CONCAT(LPAD(EXTRACT(MONTH FROM s.data_emissao), 2, "0"), "/", EXTRACT(YEAR FROM s.data_emissao)) = "'.$mesano.'" 
				and  s.cnpj_emp = "'.$cnpjemp.'" 
				and s.numero_nota = "'.$numero_nota.'" 
				and s.codigo_produto = "'.$cprod.'" 
				and cast(s.numero_item_nota as UNSIGNED) = "'.$numitem.'" limit 1';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			$data_emissao   = $dba->result($res,$i,'data_emissao');	
			$data_abate	    = $dba->result($res,$i,'data_abate');
			$desc_prod	    = $dba->result($res,$i,'desc_prod');
			
			$notasen1 = new NotasEn1Txt();

			$notasen1->setDataEmissao($data_emissao);
			$notasen1->setDataAbate($data_abate);
			$notasen1->setDescProd($desc_prod);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function RelatorioTotaldeabatepordata($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 	
				n.data_abate,
				a.especie , 
				sum(n.qtd_cabeca) as ABATE			
				from
					notasen1txt n
						inner  join
					prodfrigtxt p ON (p.cod_prod = n.codigo_produto) and (p.cnpj_emp = n.cnpj_emp)
						inner join
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
					'.$where.'  group by n.data_abate, a.especie ';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			
			$data_abate	    = $dba->result($res,$i,'data_abate');
			$especie	    = $dba->result($res,$i,'especie');
			$qtd_cabeca     = $dba->result($res,$i,'ABATE');	

			$notasen1 = new NotasEn1Txt();
			
			$notasen1->setDataAbate($data_abate);
			$notasen1->setEspecie($especie);
			$notasen1->setQtdCabeca($qtd_cabeca);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function RelatorioTotaldeabatepordataNota($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 	
					n.data_abate, 
					n.numero_nota,
					a.especie, 
					sum(n.qtd_cabeca) AS ABATE
				FROM
					notasen1txt n
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = n.codigo_produto) and (p.cnpj_emp = n.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)  
					'.$where.' group by n.data_abate, a.especie,n.numero_nota order by n.data_abate,n.numero_nota ';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			
			$data_abate	    = $dba->result($res,$i,'data_abate');
			$especie	    = $dba->result($res,$i,'especie');
			$qtd_cabeca     = $dba->result($res,$i,'ABATE');
			$numero_nota    = $dba->result($res,$i,'numero_nota');	

			$notasen1 = new NotasEn1Txt();
			
			$notasen1->setDataAbate($data_abate);
			$notasen1->setEspecie($especie);
			$notasen1->setQtdCabeca($qtd_cabeca);
			$notasen1->setNumeroNota($numero_nota);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}
	

	public function RelSumoCfop($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 		
					n.cfop, 
					SUM(n.preco_quilo * if(n.peso_carcasa is null or n.peso_carcasa = "",n.peso_vivo_cabeca, n.peso_carcasa)) as total
				FROM
					notasen1txt n
				inner join prodfrigtxt t on (t.cod_prod = n.codigo_produto) and (t.cnpj_emp = n.cnpj_emp)
				'.$where.' GROUP BY n.cfop;';
		//echo $sql;	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			
			$cfop			    = $dba->result($res,$i,'cfop');						
			$valor_total_nota   = $dba->result($res,$i,'total');
			

			$notasen1 = new NotasEn1Txt();

			$notasen1->setCfop($cfop);			
			$notasen1->setValorTotalNota($valor_total_nota);

			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function RelatorioEntradaFechamentoCfop($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'select 
					cod_secretaria,
					descprod,
					sum(sobsotal) as subtotal,
					cfop,
					Nome,
					devolucao,
					entsai
				from(
				
				SELECT 
					n.numero_item_nota,
					n.numero_nota,          
					a.codigo AS cod_secretaria,
					concat("(",p.cod_prod,") - ", p.desc_prod) as descprod,    					
					cast(IF(n.tipo_r_v = "V", n.peso_vivo_cabeca * n.preco_quilo, n.peso_carcasa * n.preco_quilo) AS DECIMAL (10 , 2 )) AS sobsotal, 
					n.cfop,
					cf.Nome,
					cf.devolucao,
					"E" as entsai
				FROM
					notasen1txt n
						/*INNER JOIN
					notasenttxt en ON (en.numero_nota = n.numero_nota)
						AND (en.cnpj_emp = n.cnpj_emp)*/
						LEFT JOIN
					prodfrigtxt p ON (p.cod_prod = n.codigo_produto)
						AND (p.cnpj_emp = n.cnpj_emp)
						LEFT JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = n.cnpj_cpf)
						AND (e.cnpj_emp = n.cnpj_emp)
						AND (e.insc_estadual = n.insc_estadual)
					inner join
					cfop cf on (cf.Codigo = n.cfop)
				'.$where.'				
		) as tab group by cod_secretaria,descprod,cfop, Nome, devolucao order by cfop';

		
		//echo $sql."\n\n";
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		
			
			
			$cod_secretaria	= $dba->result($res,$i,'cod_secretaria');
			$descprod	    = $dba->result($res,$i,'descprod');
			$subtotal	    = $dba->result($res,$i,'subtotal');			
			$cfop			= $dba->result($res,$i,'cfop');	
			$Nome			= $dba->result($res,$i,'Nome');
			$devolucao      = $dba->result($res,$i,'devolucao');
			$entsai	        = $dba->result($res,$i,'entsai');
			
			$notasen1 = new NotasEn1Txt();
			
			$notasen1->setCodSecretaria($cod_secretaria);
			$notasen1->setDescProd($descprod);	
			$notasen1->setSubTotal($subtotal);			
			$notasen1->setCfop($cfop);
			$notasen1->setNomeCfop($Nome);
			$notasen1->setDevolucao($devolucao);
			$notasen1->setEntSai($entsai);
			
			$vet[$i] = $notasen1;

		}

		return $vet;

	}

	public function inserir($notasen1txt){

		$dba = $this->dba;		
		
		$numero_nota  	  = $notasen1txt->getNumeroNota();
		$data_emissao 	  = $notasen1txt->getDataEmissao();
		$cnpj_cpf	  	  = $notasen1txt->getCnpjCpf();
		$codigo_produto   = $notasen1txt->getCodigoProduto();
		$qtd_cabeca		  = $notasen1txt->getQtdCabeca();
		$peso_vivo_cabeca = $notasen1txt->getPesoVivoCabeca();
		$peso_carcasa	  = $notasen1txt->getPesoCarcasa();
		$preco_quilo	  = $notasen1txt->getPrecoQuilo();
		$numero_item_nota = $notasen1txt->getNumeroItemNota();
		$insc_estadual	  = $notasen1txt->getInsEstadual();
		$data_abate		  = $notasen1txt->getDataAbate();
		$tipo_r_v		  = $notasen1txt->getTipo_R_V();
		$cfop			  = $notasen1txt->getCfop();
		$aliquota_icms	  = $notasen1txt->getAliquotaIcms();
		$cnpj_emp		  = $notasen1txt->getCnpjEmp();
		$prod_qtd		  = $notasen1txt->getProdQtd();

		$sql = 'INSERT INTO `notasen1txt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`codigo_produto`,
				`qtd_cabeca`,
				`peso_vivo_cabeca`,
				`peso_carcasa`,
				`preco_quilo`,
				`numero_item_nota`,
				`insc_estadual`,
				`data_abate`,
				`tipo_r_v`,
				`cfop`,
				`aliquota_icms`,
				`cnpj_emp`,
				`qtd_prod`)
				VALUES
				("'.$numero_nota.'",
				 "'.$data_emissao.'",
				 "'.$cnpj_cpf.'",
				 "'.$codigo_produto.'",
				 "'.$qtd_cabeca.'",
				 '.$peso_vivo_cabeca.',
				 '.$peso_carcasa.',
				 '.$preco_quilo.',
				 "'.$numero_item_nota.'",
				 "'.$insc_estadual.'",
				 "'.$data_abate.'",
				 "'.$tipo_r_v.'",
				 "'.$cfop.'",
				 '.$aliquota_icms.',
				 "'.$cnpj_emp.'",
				 '.$prod_qtd.')';
		
		//echo $sql;
		$dba->query($sql);	
							
	}
	
	
	public function update($notasen1txt){
		
		$dba = $this->dba;
		
		$id 			  = $notasen1txt->getCodigo();
		$qtd_cabeca		  = $notasen1txt->getQtdCabeca();
		$data_abate		  = $notasen1txt->getDataAbate();
		$tipo_r_v		  = $notasen1txt->getTipo_R_V();
		$pesocarcaca	  = $notasen1txt->getPesoCarcasa();
		$pesovivo         = $notasen1txt->getPesoVivoCabeca();
		$prod_qtd		  = $notasen1txt->getProdQtd();
		$preco_quilo	  = $notasen1txt->getPrecoQuilo();

		$sql = 'UPDATE `notasen1txt`
				SET
				`qtd_cabeca` = "'.$qtd_cabeca.'",
				`data_abate` = "'.$data_abate.'",
				`tipo_r_v` = "'.$tipo_r_v.'",
				`peso_carcasa` = '.$pesocarcaca.',
				`peso_vivo_cabeca` = '.$pesovivo.',
				`qtd_prod` = '.$prod_qtd.',
				`preco_quilo` = '.$preco_quilo.'
				WHERE `id` = '.$id.' ';		
		
		
		$dba->query($sql);	
		
	}
	
	public function updatecabecas($notasen1txt){
		
		$dba = $this->dba;
		
		$id 			  = $notasen1txt->getCodigo();
		$qtd_cabeca		  = $notasen1txt->getQtdCabeca();		
		
		$sql = 'UPDATE `notasen1txt`
				SET
				`qtd_cabeca` = "'.$qtd_cabeca.'"				
				WHERE `id` = '.$id.' ';		
		//echo $sql; 
		$dba->query($sql);	
		
	}
	
	public function updatevivorend($notasen1txt){
		
		$dba = $this->dba;
		
		$id 			  = $notasen1txt->getCodigo();
		$tipo_r_v		  = $notasen1txt->getTipo_R_V();		
		$pesocarcaca	  = $notasen1txt->getPesoCarcasa();
		$pesovivo         = $notasen1txt->getPesoVivoCabeca();

		$sql = 'UPDATE `notasen1txt`
				SET
				`tipo_r_v` = "'.$tipo_r_v.'",
				`peso_carcasa` = '.$pesocarcaca.',
				`peso_vivo_cabeca` = '.$pesovivo.'	
				WHERE `id` = '.$id.' ';
		
			//echo $sql;  
		$dba->query($sql);	
		
	}
	
	public function deletar($notasen1txt){
	
		$dba = $this->dba;

		$id 	  = $notasen1txt->getCodigo();
		$cnpj_cpf = $notasen1txt->getCnpjEmp();
		
		$sql = 'DELETE FROM `notasen1txt`
				WHERE cnpj_emp = "'.$cnpj_cpf.'" and id ='.$id.'';								
		//echo $sql;		
		$dba->query($sql);	
				
	}		
}

?>