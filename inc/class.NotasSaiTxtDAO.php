<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasSaiTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	
	public function ListandoNotasSai($mesano,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					distinct
					t.id,
					t.numero_nota,
					t.data_emissao,
					t.cnpj_cpf,
					t.valor_total_nota,
					(select cast(sum(s.peso * s.preco_unitario) as DECIMAL(10,2))  from notassa1txt s where s.numero_nota = t.numero_nota and s.cnpj_emp = t.cnpj_emp) as totprod,
					t.valor_icms,
					t.valor_icms_subs,
					t.ent_sai,
					t.insc_estadual,
					t.cfop,
                    e.razao,
                    t.xml,
                    t.chave_acesso
				FROM
					
					notassaitxt t
                    left join empresastxt e on (e.cnpj_cpf = t.cnpj_cpf) and (e.insc_estadual = t.insc_estadual)
				where
					concat(lpad(EXTRACT(MONTH FROM t.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM t.data_emissao)) = "'.$mesano.'"
						and t.cnpj_emp = "'.$cnpj.'" /*and e.cnpj_emp = "'.$cnpj.'"*/ group by t.id ';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');	
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$ent_sai          = $dba->result($res,$i,'ent_sai');
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');							
			$cfop		      = $dba->result($res,$i,'cfop');
			$razao			  = $dba->result($res,$i,'razao');
			$xml			  = $dba->result($res,$i,'xml');
			$chave_acesso	  = $dba->result($res,$i,'chave_acesso');
			$totprod		  = $dba->result($res,$i,'totprod');
			
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setDataEmissao($data_emissao);
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setValorTotalNota($valor_total_nota);	
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);
			$notasai->setEntSai($ent_sai);
			$notasai->setInscEstadual($insc_estadual);
			$notasai->setCfop($cfop);
			$notasai->setNomeCli($razao);
			$notasai->setXml($xml);
			$notasai->setChave($chave_acesso);
			$notasai->setTotalProd($totprod);

			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	public function ListandoNotasSaiScroll($mesano,$cnpj,$limit){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					distinct
					t.id,
					t.numero_nota,
					t.data_emissao,
					t.cnpj_cpf,
					t.valor_total_nota,
					(select cast(sum(s.peso * s.preco_unitario) as DECIMAL(10,2))  from notassa1txt s where s.numero_nota = t.numero_nota and s.cnpj_emp = t.cnpj_emp) as totprod,
					t.valor_icms,
					t.valor_icms_subs,
					t.ent_sai,
					t.insc_estadual,
					t.cfop,
                    e.razao,
                    t.xml,
                    t.chave_acesso
				FROM
					
					notassaitxt t
                    left join empresastxt e on (e.cnpj_cpf = t.cnpj_cpf) and (e.insc_estadual = t.insc_estadual)
				where
					concat(lpad(EXTRACT(MONTH FROM t.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM t.data_emissao)) = "'.$mesano.'"
						and t.cnpj_emp = "'.$cnpj.'" /*and e.cnpj_emp = "'.$cnpj.'"*/ group by t.id limit '.$limit.' ';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');	
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$ent_sai          = $dba->result($res,$i,'ent_sai');
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');							
			$cfop		      = $dba->result($res,$i,'cfop');
			$razao			  = $dba->result($res,$i,'razao');
			$xml			  = $dba->result($res,$i,'xml');
			$chave_acesso	  = $dba->result($res,$i,'chave_acesso');
			$totprod		  = $dba->result($res,$i,'totprod');
			
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setDataEmissao($data_emissao);
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setValorTotalNota($valor_total_nota);	
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);
			$notasai->setEntSai($ent_sai);
			$notasai->setInscEstadual($insc_estadual);
			$notasai->setCfop($cfop);
			$notasai->setNomeCli($razao);
			$notasai->setXml($xml);
			$notasai->setChave($chave_acesso);
			$notasai->setTotalProd($totprod);

			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function ListandoNotasSaiDBF($mesano,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					distinct
					t.id,
					t.numero_nota,
					t.data_emissao,
					t.cnpj_cpf,
					t.valor_total_nota,
					t.valor_icms,
					t.valor_icms_subs,
					t.ent_sai,
					t.insc_estadual,
					t.cfop,
                    e.razao,
                    t.xml,
                    t.chave_acesso
				FROM
					
					notassaitxt t
                    left join empresastxt e on (e.cnpj_cpf = t.cnpj_cpf) and (e.insc_estadual = t.insc_estadual)
				where
					concat(lpad(EXTRACT(MONTH FROM t.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM t.data_emissao)) = "'.$mesano.'"
						and t.cnpj_emp = "'.$cnpj.'" and e.cnpj_emp = "'.$cnpj.'" group by t.id';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');	
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$ent_sai          = $dba->result($res,$i,'ent_sai');
			$insc_estadual    = $dba->result($res,$i,'insc_estadual');							
			$cfop		      = $dba->result($res,$i,'cfop');
			$razao			  = $dba->result($res,$i,'razao');
			$xml			  = $dba->result($res,$i,'xml');
			$chave_acesso	  = $dba->result($res,$i,'chave_acesso');
			
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setDataEmissao($data_emissao);
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setValorTotalNota($valor_total_nota);	
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);
			$notasai->setEntSai($ent_sai);
			$notasai->setInscEstadual($insc_estadual);
			$notasai->setCfop($cfop);
			$notasai->setNomeCli($razao);
			$notasai->setXml($xml);
			$notasai->setChave($chave_acesso);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function PegaRestristroParaExclusao($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					t.id, 
					t.cnpj_emp
				FROM
					notassaitxt t
				where
					t.data_emissao between "'.$dtini.'" and "'.$dtfim.'"
						and t.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$cnpj_emp 		  = $dba->result($res,$i,'cnpj_emp');
			
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	public function ListandoNotasSaiEmpresa($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					t.id,
					t.numero_nota,
					t.data_emissao,
					t.cnpj_cpf,
					t.valor_total_nota,
					t.valor_icms,
					t.valor_icms_subs,
					t.ent_sai,
					t.insc_estadual,
					t.cfop,
					t.cnpj_emp
				FROM
					
					notassaitxt t
				where
					concat(EXTRACT(MONTH FROM t.data_emissao),"/",EXTRACT(YEAR FROM t.data_emissao)) between "'.$dtini.'" and "'.$dtfim.'"
						and t.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$data_emissao	  = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$insc_estadual	  = $dba->result($res,$i,'insc_estadual');
			$cfop			  = $dba->result($res,$i,'cfop');
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setDataEmissao($data_emissao);
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setValorTotalNota($valor_total_nota);
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);
			$notasai->setEntSai($ent_sai);
			$notasai->setInscEstadual($insc_estadual);
			$notasai->setCfop($cfop);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	
	public function ListandoNotasSaiCompetancia($cnpj,$dtini){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					SUM(n.valor_icms) AS valor_icms,
					SUM(round(n.valor_icms_subs,2)) AS valor_icms_subs
				FROM
					notassaitxt n
				WHERE
					CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),2,"0"),
							"/",
							EXTRACT(YEAR FROM n.data_emissao)) = "'.$dtini.'"
						AND n.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		


			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			
			$notasai = new NotasSaiTxt();
			
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);
			
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	public function TotalVendasRS($where,$cnpj,$mesano,$perc){

		$dba = $this->dba;

		$vet = array();
			
							
		$sql = 'select 
						SUM(entrada) AS entrada,
						SUM(saida) AS saida  
					from 
					(
					SELECT 
						distinct
						s.numero_item_nota,
						s.numero_nota,
						CAST(CASE
							WHEN s.ent_sai = "E" THEN 
								s.peso * s.preco_unitario 
							ELSE 0
						END as DECIMAL(10 , 2 )) AS entrada,
						CAST(CASE
							WHEN s.ent_sai = "S" THEN 
								s.peso * s.preco_unitario 
							ELSE 0
						END as DECIMAL(10,2)) AS saida
					FROM
						notassa1txt s    
							INNER JOIN
						prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
							INNER JOIN
						empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf)
						INNER JOIN
							cfop_empresa c ON (c.id_cfop = s.cfop)
						inner join 
							cfop cc on (cc.Codigo = s.cfop)

						'.$where.'
							) AS tabs';					
		//echo $sql."\n\n";

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$entrada = $dba->result($res,$i,'entrada');
			$saida	 = $dba->result($res,$i,'saida');
							
			$notasai = new NotasSaiTxt();

			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	
	public function TotalVendasRSXml($where,$cnpj,$mesano,$perc){

		$dba = $this->dba;

		$vet = array();
			

		$sql = 'select 
						SUM(entrada) - (SELECT  COALESCE(GETDEVOLUCAO("'.$cnpj.'","'.$mesano.'","S","'.$perc.'"), 0)) AS entrada,
						SUM(saida) - (SELECT    COALESCE(GETDEVOLUCAO("'.$cnpj.'","'.$mesano.'","E","'.$perc.'"), 0)) AS saida  
					from 
					(
					SELECT 
						distinct
						s.numero_item_nota,
						s.numero_nota,
						CAST(CASE
							WHEN s.ent_sai = "E" THEN 
								s.peso * s.preco_unitario 
							ELSE 0
						END as DECIMAL(10 , 2 )) AS entrada,
						CAST(CASE
							WHEN s.ent_sai = "S" THEN 
								s.peso * s.preco_unitario 
							ELSE 0
						END as DECIMAL(10,2)) AS saida
					FROM
						notassa1txt s    
							INNER JOIN
						prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
							INNER JOIN
						empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf)
						INNER JOIN
							cfop_empresa c ON (c.id_cfop = s.cfop)
						inner join 
							cfop cc on (cc.Codigo = s.cfop)

						'.$where.'
							) AS tabs';
							
							
					
		//echo $sql."\n\n";

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$entrada = $dba->result($res,$i,'entrada');
			$saida	 = $dba->result($res,$i,'saida');
							
			$notasai = new NotasSaiTxt();

			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	

	/*public function TotalVendasRS($where,$cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();
			

		$sql = 'SELECT 
					sum(case
						when s.ent_sai = "E" then cast(s.peso * s.preco_unitario as DECIMAL(10,2))
						else 0
					end) - (select COALESCE(getdevolucao("'.$cnpj.'","'.$mesano.'","S"),0) as valor) as entrada,
					sum(case
						when s.ent_sai = "S" then cast(s.peso * s.preco_unitario as DECIMAL(10,2)) 
						else 0
					end) - (select COALESCE(getdevolucao("'.$cnpj.'","'.$mesano.'","E"),0) as valor) as saida
				FROM
					notassaitxt m
						inner join
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						inner join
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)	
						inner join
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf)
				'.$where.'	';
		//echo $sql."\n\n";

		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$entrada = $dba->result($res,$i,'entrada');
			$saida	 = $dba->result($res,$i,'saida');
							
			$notasai = new NotasSaiTxt();

			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}*/
	
	public function PegaExclusao($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					t.id
				FROM
					notassaitxt t
				where
					t.numero_nota = "'.$cod.'"
						and t.cnpj_emp = "'.$cnpj.'" ';
	  	//echo $sql;  	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	
	public function VerificaNota($cod,$cnpj,$dta){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					t.id,
					t.xml
				FROM
					notassaitxt t
				where
					t.numero_nota = "'.$cod.'"
						and t.cnpj_emp = "'.$cnpj.'" and concat(EXTRACT(MONTH FROM t.data_emissao),"/",EXTRACT(YEAR FROM t.data_emissao)) = "'.$dta.'"';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$xml 			  = $dba->result($res,$i,'xml');

			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setXml($xml);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
		
	public function NotasSaiCompetenciaExclui($dtcomp,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT ns.id, ns.numero_nota FROM notassaitxt ns where DATE_FORMAT(ns.data_emissao, "%m/%Y") = "'.$dtcomp.'" and ns.cnpj_emp = "'.$cnpj.'" ';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$numero_nota	  = $dba->result($res,$i,'numero_nota');

			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setNumeroNota($numero_nota);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}


	public function NumeroDeSaidas($dtcomp,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT count(ns.id) as num_Saida FROM notassaitxt ns where DATE_FORMAT(ns.data_emissao, "%m/%Y") = "'.$dtcomp.'" and ns.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$num_Saida  = $dba->result($res,$i,'num_Saida');
				
			$notasai = new NotasSaiTxt();

			$notasai->setNumeroSaida($num_Saida);
			
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function RelatorioTotalVendasbeneficioArrecadacao($where,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					sum(case
						when s.ent_sai = "E" then cast(s.peso * s.preco_unitario as DECIMAL(10,2))
						else 0
					end) as entrada,
					sum(case
						when s.ent_sai = "S" then cast(s.peso * s.preco_unitario as DECIMAL(10,2)) 
						else 0
					end) as saida
				FROM
					notassaitxt m
						inner join
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						inner join
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)	
						inner join
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf)
				'.$where.' 
				AND s.cnpj_emp = "'.$cnpj.'"
		        AND m.cnpj_emp = "'.$cnpj.'"
		        AND e.cnpj_emp = "'.$cnpj.'"
		        AND p.cnpj_emp = "'.$cnpj.'" ';
		//echo $sql; 			
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$entrada = $dba->result($res,$i,'entrada');
			$saida	 = $dba->result($res,$i,'saida');
							
			$notasai = new NotasSaiTxt();

			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	

	public function RelNotasDeSaida($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					e.cnpj_cpf,
					e.razao,
					e.insc_estadual,
					s.data_emissao,
					m.numero_nota,
					m.valor_icms,
					m.valor_icms_subs,
					s.codigo_produto,
					a.codigo as cod_secretaria,
					a.descricao,
					cast(s.peso  AS DECIMAL (10 , 2 )) as peso,
					s.qtd_pecas,
					s.preco_unitario,
					m.valor_total_nota,
					p.id as pkrel,
					s.ent_sai,
					if(s.ent_sai = "E", cast(s.preco_unitario * s.peso * -1  AS DECIMAL (10 , 2 )), cast(s.preco_unitario * s.peso * 1  AS DECIMAL (10 , 2 ))) as subtotal
				FROM
					notassaitxt m
						INNER JOIN
					notassa1txt s ON (s.numero_nota = m.numero_nota) 
						AND (s.cnpj_emp = m.cnpj_emp) and (s.insc_estadual = m.insc_estadual ) 
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf) 
						AND (e.cnpj_emp = m.cnpj_emp) 
					'.$where.' ORDER BY m.numero_nota ';
				//echo "{$sql}";
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$razao            = $dba->result($res,$i,'razao');
			$insc             = $dba->result($res,$i,'insc_estadual');	
			$data_emissao	  = $dba->result($res,$i,'data_emissao');	
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$cod_secretaria   = $dba->result($res,$i,'cod_secretaria');
			$descricao		  = $dba->result($res,$i,'descricao');			
			$peso		      = $dba->result($res,$i,'peso');
			$qtd_pecas        = $dba->result($res,$i,'qtd_pecas');
			$preco_unitario   = $dba->result($res,$i,'preco_unitario');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');
			$pkrel 		      = $dba->result($res,$i,'pkrel');
			$codigo_produto   = $dba->result($res,$i,'codigo_produto');
			$ent_sai		  = $dba->result($res,$i,'ent_sai');
			$subtotal		  = $dba->result($res,$i,'subtotal');
				
			$notasai = new NotasSaiTxt();
			
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setRazao($razao);
			$notasai->setInscEstadual($insc);
			$notasai->setDataEmissao($data_emissao);			
			$notasai->setNumeroNota($numero_nota);
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);			
			$notasai->setCodSecretaria($cod_secretaria);
			$notasai->setDescSecretaria($descricao);
			$notasai->setPeso($peso);
			$notasai->setPecas($qtd_pecas);			
			$notasai->setPrecoUnitario($preco_unitario);
			$notasai->setValorTotalNota($valor_total_nota);
			$notasai->setPkRelacionamento($pkrel);
			$notasai->setCodigoProduto($codigo_produto);
			$notasai->setEntSai($ent_sai);
			$notasai->setSubTotal($subtotal);

			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function RelNotasDeSaidaSimplificada($where){
		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					data_emissao,
					numero_nota,
					cfop,
					SUM(DISTINCT valor_total_nota) AS valor_total_nota,
					SUM(valorproduto) AS valorproduto
				FROM
					(SELECT 
				
					s.data_emissao,
					m.numero_nota,    
					m.valor_total_nota,
					s.numero_item_nota,
					if(s.ent_sai = "E", cast(s.preco_unitario * s.peso * -1  AS DECIMAL (10 , 2 )), cast(s.preco_unitario * s.peso * 1  AS DECIMAL (10 , 2 ))) as valorproduto,
					s.cfop
				FROM
					notassaitxt m
						INNER JOIN
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						AND (s.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf)
						AND (e.cnpj_emp = m.cnpj_emp)	
					'.$where.') as sai group by data_emissao,numero_nota,cfop';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$dataemisao 	  = $dba->result($res,$i,'data_emissao');
			$numero_nota      = $dba->result($res,$i,'numero_nota');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');
			$valorproduto     = $dba->result($res,$i,'valorproduto');
			$cfop		      = $dba->result($res,$i,'cfop');
				
			$notasai = new NotasSaiTxt();
			
			$notasai->setDataEmissao($dataemisao);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setTotalProd($valorproduto);
			$notasai->setValorTotalNota($valor_total_nota);
			$notasai->setCfop($cfop);

			$vet[$i] = $notasai;

		}

		return $vet;
	}
	
	public function ListaNotasSaiEmp($mesano,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
						t.id,
						t.xml
				  FROM						
				notassaitxt t
				where
				concat(lpad(EXTRACT(MONTH FROM t.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM t.data_emissao)) = "'.$mesano.'"
				and t.cnpj_emp = "'.$cnpj.'"';
	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  		  = $dba->result($res,$i,'id');
			$xml		  = $dba->result($res,$i,'xml');
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setXml($xml);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	public function ListaNotasSaiEmpUm($nnota){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
						t.id,
						t.xml
				  FROM						
				notassaitxt t
				where
				t.numero_nota = "'.$nnota.'"';
	  	//echo $sql."<br>"; 	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  		  = $dba->result($res,$i,'id');
			$xml		  = $dba->result($res,$i,'xml');
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);
			$notasai->setXml($xml);
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function PegaNotasDesconcideradaExcluir($idc,$cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT DISTINCT
					ns.id
				FROM
					notassaitxt ns
						LEFT JOIN
					cfop_empresa c ON (c.id_cfop = ns.cfop)
				WHERE
					c.gera_agregar = "2"
						AND ns.numero_nota in('.$idc.')
						AND ns.cnpj_emp = "'.$cnpj.'"
						AND CONCAT(LPAD(EXTRACT(MONTH FROM ns.data_emissao),
									2,
									"0"),
							"/",
							EXTRACT(YEAR FROM ns.data_emissao)) = "'.$mesano.'" ';
	  	//echo $sql."<br>"; 	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id      = $dba->result($res,$i,'id');			
				
			$notasai = new NotasSaiTxt();

			$notasai->setCodigo($id);			
			
			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function RelNotasDeSaidaNaoConciderar($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT DISTINCT
					s.numero_item_nota,
					s.numero_nota,
					CAST(CASE
							WHEN s.ent_sai = "E" THEN s.peso * s.preco_unitario
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) AS entrada,
					CAST(CASE
							WHEN s.ent_sai = "S" THEN s.peso * s.preco_unitario
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) AS saida,
						s.codigo_produto,
						pt.desc_prod,                
						p.cod_secretaria,
						a.descricao as dessecretaria,
						s.qtd_pecas,
						s.peso,
						s.preco_unitario,
						s.ent_sai,
						s.cfop,
						if(c.gera_agregar = 1 ,"Cfop Considera","Cfop Desconsiderar") as cfopcon
					FROM
						notassa1txt s
					INNER JOIN prodfrigtxt p ON (p.cod_prod = s.codigo_produto) and (p.cnpj_emp = s.cnpj_emp)
					INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
					inner join produtotxt pt on (pt.cod_prod = s.codigo_produto) and (pt.cnpj_emp = s.cnpj_emp)
					INNER JOIN empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf) and (e.cnpj_emp = s.cnpj_emp)
					INNER JOIN cfop_empresa c ON (c.id_cfop = s.cfop) and (c.cnpj_emp = s.cnpj_emp)
				'.$where.' ';
	  		//echo $sql;
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$numero_item_nota		  = $dba->result($res,$i,'numero_item_nota');
			$numero_nota			  = $dba->result($res,$i,'numero_nota');
			$entrada				  = $dba->result($res,$i,'entrada');
			$saida					  = $dba->result($res,$i,'saida');
			$codigo_produto			  = $dba->result($res,$i,'codigo_produto');
			$desc_prod				  = $dba->result($res,$i,'desc_prod');
			$cod_secretaria			  = $dba->result($res,$i,'cod_secretaria');
			$dessecretaria			  = $dba->result($res,$i,'dessecretaria');
			$qtd_pecas				  = $dba->result($res,$i,'qtd_pecas');
			$peso					  = $dba->result($res,$i,'peso');
			$preco_unitario			  = $dba->result($res,$i,'preco_unitario');
			$ent_sai				  = $dba->result($res,$i,'ent_sai');
			$cfop					  = $dba->result($res,$i,'cfop');
			$cfopcon				  = $dba->result($res,$i,'cfopcon');

			$notasai = new NotasSaiTxt();
			
			$notasai->setItemNota($numero_item_nota);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			$notasai->setCodigoProduto($codigo_produto);
			$notasai->setDescProduto($desc_prod);
			$notasai->setCodSecretaria($cod_secretaria);
			$notasai->setDescSecretaria($dessecretaria);
			$notasai->setPecas($qtd_pecas);
			$notasai->setPeso($peso);
			$notasai->setPrecoUnitario($preco_unitario);
			$notasai->setEntSai($ent_sai);
			$notasai->setCfop($cfop);
			$notasai->setCfopSN($cfopcon);

			$vet[$i] = $notasai;

		}

		return $vet;

	}
	
	public function RelNotasDeSaidaNaoConciderarIcms($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT DISTINCT
					s.numero_item_nota,
					s.numero_nota,
					CAST(CASE
							WHEN s.ent_sai = "E" THEN s.peso * s.preco_unitario
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) AS entrada,
					CAST(CASE
							WHEN s.ent_sai = "S" THEN s.peso * s.preco_unitario
							ELSE 0
						END
						AS DECIMAL (10 , 2 )) AS saida,
						s.codigo_produto,
						pt.desc_prod,                
						p.cod_secretaria,
						a.descricao as dessecretaria,
						s.qtd_pecas,
						s.peso,
						s.preco_unitario,
						s.ent_sai,
						s.cfop						
					FROM
						notassa1txt s
					INNER JOIN prodfrigtxt p ON (p.cod_prod = s.codigo_produto) and (p.cnpj_emp = s.cnpj_emp)
					INNER JOIN produtos_agregar a ON (a.codigo = p.cod_secretaria)
					inner join produtotxt pt on (pt.cod_prod = s.codigo_produto) and (pt.cnpj_emp = s.cnpj_emp)
					INNER JOIN empresastxt e ON (e.cnpj_cpf = s.cnpj_cpf) and (e.cnpj_emp = s.cnpj_emp)					
				'.$where.' ';
		
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$numero_item_nota		  = $dba->result($res,$i,'numero_item_nota');
			$numero_nota			  = $dba->result($res,$i,'numero_nota');
			$entrada				  = $dba->result($res,$i,'entrada');
			$saida					  = $dba->result($res,$i,'saida');
			$codigo_produto			  = $dba->result($res,$i,'codigo_produto');
			$desc_prod				  = $dba->result($res,$i,'desc_prod');
			$cod_secretaria			  = $dba->result($res,$i,'cod_secretaria');
			$dessecretaria			  = $dba->result($res,$i,'dessecretaria');
			$qtd_pecas				  = $dba->result($res,$i,'qtd_pecas');
			$peso					  = $dba->result($res,$i,'peso');
			$preco_unitario			  = $dba->result($res,$i,'preco_unitario');
			$ent_sai				  = $dba->result($res,$i,'ent_sai');
			$cfop					  = $dba->result($res,$i,'cfop');			

			$notasai = new NotasSaiTxt();
			
			$notasai->setItemNota($numero_item_nota);
			$notasai->setNumeroNota($numero_nota);
			$notasai->setEntrada($entrada);
			$notasai->setSaida($saida);
			$notasai->setCodigoProduto($codigo_produto);
			$notasai->setDescProduto($desc_prod);
			$notasai->setCodSecretaria($cod_secretaria);
			$notasai->setDescSecretaria($dessecretaria);
			$notasai->setPecas($qtd_pecas);
			$notasai->setPeso($peso);
			$notasai->setPrecoUnitario($preco_unitario);
			$notasai->setEntSai($ent_sai);
			$notasai->setCfop($cfop);			

			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function RelNotasDeSaidaCfop($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					e.cnpj_cpf,
					e.razao,
					e.insc_estadual,
					s.data_emissao,
					m.numero_nota,
					m.valor_icms,
					m.valor_icms_subs,
					s.codigo_produto,
					a.codigo as cod_secretaria,
					a.descricao,
					s.peso,
					s.qtd_pecas,
					s.preco_unitario,
					m.valor_total_nota,
					p.id as pkrel,
					s.cfop
				FROM
					notassaitxt m
						INNER JOIN
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						AND (s.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf) 
						AND (e.cnpj_emp = m.cnpj_emp) 
					'.$where.' and (s.insc_estadual = e.insc_estadual ) ORDER BY s.cfop,m.numero_nota ';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cnpj_cpf 		  = $dba->result($res,$i,'cnpj_cpf');
			$razao            = $dba->result($res,$i,'razao');
			$insc             = $dba->result($res,$i,'insc_estadual');	
			$data_emissao	  = $dba->result($res,$i,'data_emissao');	
			$numero_nota	  = $dba->result($res,$i,'numero_nota');
			$valor_icms		  = $dba->result($res,$i,'valor_icms');
			$valor_icms_subs  = $dba->result($res,$i,'valor_icms_subs');
			$cod_secretaria   = $dba->result($res,$i,'cod_secretaria');
			$descricao		  = $dba->result($res,$i,'descricao');			
			$peso		      = $dba->result($res,$i,'peso');
			$qtd_pecas        = $dba->result($res,$i,'qtd_pecas');
			$preco_unitario   = $dba->result($res,$i,'preco_unitario');
			$valor_total_nota = $dba->result($res,$i,'valor_total_nota');
			$pkrel 		      = $dba->result($res,$i,'pkrel');
			$codigo_produto   = $dba->result($res,$i,'codigo_produto');
			$cfop			  = $dba->result($res,$i,'cfop');
				
			$notasai = new NotasSaiTxt();
			
			$notasai->setCnpjCpf($cnpj_cpf);
			$notasai->setRazao($razao);
			$notasai->setInscEstadual($insc);
			$notasai->setDataEmissao($data_emissao);			
			$notasai->setNumeroNota($numero_nota);
			$notasai->setValorIcms($valor_icms);
			$notasai->setValorIcmsSubs($valor_icms_subs);			
			$notasai->setCodSecretaria($cod_secretaria);
			$notasai->setDescSecretaria($descricao);
			$notasai->setPeso($peso);
			$notasai->setPecas($qtd_pecas);			
			$notasai->setPrecoUnitario($preco_unitario);
			$notasai->setValorTotalNota($valor_total_nota);
			$notasai->setPkRelacionamento($pkrel);
			$notasai->setCodigoProduto($codigo_produto);
			$notasai->setCfop($cfop);

			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function RelNotasDeSaidaSumaCfop($where){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					sum(s.peso * s.preco_unitario) as valor,
    				s.cfop
				FROM
					notassaitxt m
						INNER JOIN
					notassa1txt s ON (s.numero_nota = m.numero_nota)
						AND (s.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					prodfrigtxt p ON (p.cod_prod = s.codigo_produto)
						AND (p.cnpj_emp = m.cnpj_emp)
						INNER JOIN
					produtos_agregar a ON (a.codigo = p.cod_secretaria)
						INNER JOIN
					empresastxt e ON (e.cnpj_cpf = m.cnpj_cpf) 
						AND (e.cnpj_emp = m.cnpj_emp) 
					'.$where.' and (s.insc_estadual = e.insc_estadual ) group by s.cfop ';
	  	//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$valor	  = $dba->result($res,$i,'valor');
			$cfop     = $dba->result($res,$i,'cfop');
				
			$notasai = new NotasSaiTxt();
						
			$notasai->setValorTotalNota($valor);
			$notasai->setCfop($cfop);

			$vet[$i] = $notasai;

		}

		return $vet;

	}

	public function inserir($notasaitxt){

		$dba = $this->dba;		
		
		$numero_nota    	  = $notasaitxt->getNumeroNota();
		$dataemissao    	  = $notasaitxt->getDataEmissao();
		$cnpjcpf	    	  = $notasaitxt->getCnpjCpf();
		$valortotalnota		  =	$notasaitxt->getValorTotalNota();
		$valoricms			  = $notasaitxt->getValorIcms();
		$valoricmssubs		  = $notasaitxt->getValorIcmsSubs();
		$entsai 			  = $notasaitxt->getEntSai();
		$inscestadual		  = $notasaitxt->getInscEstadual();
		$cfop				  = $notasaitxt->getCfop();
		$cnpjemp              = $notasaitxt->getCnpjEmp();
		$chave				  = $notasaitxt->getChave();
		$xml 				  = str_replace('\n','',$dba->real_escape_string($notasaitxt->getXml()));
		
		$sql = 'INSERT INTO `notassaitxt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`valor_total_nota`,
				`valor_icms`,
				`valor_icms_subs`,
				`ent_sai`,
				`insc_estadual`,
				`cfop`,
				`cnpj_emp`,
				`chave_acesso`,
				`xml`)
				VALUES
				("'.$numero_nota.'",
				"'.$dataemissao.'",
				"'.$cnpjcpf.'",
				'.$valortotalnota.',
				'.$valoricms.',
				'.$valoricmssubs.',
				"'.$entsai.'",
				"'.$inscestadual.'",
				"'.$cfop.'",
				"'.$cnpjemp.'",
				"'.$chave.'",
				"'.$xml.'")';

		$dba->query($sql);	
							
	}
	
	public function update($notasaitxt){

		$dba = $this->dba;		
		
		$codigo				  =	$notasaitxt->getCodigo();
		$dataemissao    	  = $notasaitxt->getDataEmissao();
		$cnpjcpf	    	  = $notasaitxt->getCnpjCpf();
		$valortotalnota		  =	$notasaitxt->getValorTotalNota();
		$valoricms			  = $notasaitxt->getValorIcms();
		$valoricmssubs		  = $notasaitxt->getValorIcmsSubs();
		$entsai 			  = $notasaitxt->getEntSai();
		$inscestadual		  = $notasaitxt->getInscEstadual();
		$cfop				  = $notasaitxt->getCfop();
		
		
		$sql = 'UPDATE `notassaitxt`
		SET
		`data_emissao` = "'.$dataemissao.'",
		`cnpj_cpf` = "'.$cnpjcpf.'",
		`valor_total_nota` = '.$valortotalnota.',
		`valor_icms` = '.$valoricms.',
		`valor_icms_subs` = '.$valoricmssubs.',
		`ent_sai` = "'.$entsai.'",
		`insc_estadual` = "'.$inscestadual.'",
		`cfop` = "'.$cfop.'"
		WHERE `id` = "'.$codigo.'" ';

		$dba->query($sql);	
							
	}

	public function deletar($notasaitxt){
	
		$dba = $this->dba;

		$id 	 = $notasaitxt->getCodigo();
		$cnpjemp = $notasaitxt->getCnpjEmp();
		
		$sql = 'DELETE FROM `notassaitxt`
				WHERE cnpj_emp = "'.$cnpjemp.'" and id = '.$id.' ';

		$dba->query($sql);	
				
	}
	
	public function setProgress($progress) {
		$file = __DIR__ . '/p';
		if (!is_file($file))
			touch($file);
		file_put_contents($file, $progress);
	}
}

?>