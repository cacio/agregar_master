<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class NotasEntTxtDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaNotasEntrada($cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					distinct
					n.id,
					n.numero_nota,
					n.data_emissao,
					en.data_abate,
					n.cnpj_cpf,
					n.tipo_v_r_a,
					n.valor_total_nota,
					COALESCE((SELECT 
						SUM(CAST(CASE
									WHEN en1.tipo_r_v = "V" THEN en1.preco_quilo * en1.peso_vivo_cabeca
									ELSE en1.preco_quilo * en1.peso_carcasa
								END
								AS DECIMAL (10 , 2 ))) AS valorproduto
					FROM
						notasen1txt en1
					WHERE
						en1.numero_nota = n.numero_nota
							AND en1.cnpj_emp = n.cnpj_emp) ,0) AS valorproduto,
					n.gta,
					n.numero_nota_produtor_ini,
					n.numero_nota_produtor_fin,
					n.condenas,
					n.abate,
					n.insc_produtor,
					n.cnpj_emp,
					e.razao,
					n.chave_acesso,
					n.xml
				FROM
				   notasenttxt n
				   left join notasen1txt en on (en.numero_nota = n.numero_nota) and (en.cnpj_emp = n.cnpj_emp)
				   left join empresastxt e on (e.cnpj_cpf = n.cnpj_cpf) and (e.insc_estadual = n.insc_produtor)
				WHERE
					(concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" 
					 or 
					 concat(lpad(EXTRACT(MONTH FROM en.data_abate),2,"0"),"/",EXTRACT(YEAR FROM en.data_abate)) = "'.$mesano.'")
					and 
						 n.cnpj_emp = "'.$cnpj.'" and e.cnpj_emp = "'.$cnpj.'" group by n.id';
		//echo "{$sql}";	  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  		   = $dba->result($res,$i,'id');
			$numero_nota 		  	   = $dba->result($res,$i,'numero_nota');
			$data_emissao 		  	   = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 			  	   = $dba->result($res,$i,'cnpj_cpf');
			$tipo_v_r_a 		  	   = $dba->result($res,$i,'tipo_v_r_a');
			$valor_total_nota		   = $dba->result($res,$i,'valor_total_nota');
			$gta		 			   = $dba->result($res,$i,'gta');
			$numero_nota_produtor_ini  = $dba->result($res,$i,'numero_nota_produtor_ini');
			$numero_nota_produtor_fin  = $dba->result($res,$i,'numero_nota_produtor_fin');
			$condenas				   = $dba->result($res,$i,'condenas');
			$abate					   = $dba->result($res,$i,'abate');
			$insc_produtor			   = $dba->result($res,$i,'insc_produtor');
			$cnpj_emp				   = $dba->result($res,$i,'cnpj_emp');
			$xml 					   = $dba->result($res,$i,'xml');		
			$chave_acesso			   = $dba->result($res,$i,'chave_acesso');
			$razao 					   = $dba->result($res,$i,'razao');		
			$valorproduto			   = $dba->result($res,$i,'valorproduto');		
			$data_abate				   = $dba->result($res,$i,'data_abate');

			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setNumeroNota($numero_nota);
			$notasent->setDataEmissao($data_emissao);
			$notasent->setCnpjCpf($cnpj_cpf);
			$notasent->setTipoV_R_A($tipo_v_r_a);
			$notasent->setValorTotalNota($valor_total_nota);
			$notasent->setGta($gta);
			$notasent->setNumeroNotaProdutorIni($numero_nota_produtor_ini);
			$notasent->setNumeroNotaProdutorFin($numero_nota_produtor_fin);
			$notasent->setCondenas($condenas);
			$notasent->setAbate($abate);
			$notasent->setInscProdutor($insc_produtor);
			$notasent->setCnpjEmp($cnpj_emp);
			$notasent->setXml($xml);
			$notasent->setChave($chave_acesso);
			$notasent->setNomeCli($razao);
			$notasent->setValorTotalProduto($valorproduto);
			$notasent->setDataAbate($data_abate);

			$vet[$i] = $notasent;

		}

		return $vet;

	}
	
	public function PegaRestristroParaExclusao($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,					
					n.cnpj_emp
				FROM
					notasenttxt n
				WHERE
					n.data_emissao between "'.$dtini.'" and "'.$dtfim.'"
						and n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$cnpj_emp 		  = $dba->result($res,$i,'cnpj_emp');
			
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function ListaNotasEntEmpresa($dtini,$dtfim,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.tipo_v_r_a,
					n.valor_total_nota,
					n.gta,
					n.numero_nota_produtor_ini,
					n.numero_nota_produtor_fin,
					n.condenas,
					n.abate,
					n.insc_produtor,
					n.cnpj_emp
				FROM
				   notasenttxt n
				WHERE
					concat(EXTRACT(MONTH FROM n.data_emissao),"/",EXTRACT(YEAR FROM n.data_emissao)) between "'.$dtini.'" and "'.$dtfim.'"
						and n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  		   = $dba->result($res,$i,'id');
			$numero_nota 		  	   = $dba->result($res,$i,'numero_nota');
			$data_emissao 		  	   = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 			  	   = $dba->result($res,$i,'cnpj_cpf');
			$tipo_v_r_a 		  	   = $dba->result($res,$i,'tipo_v_r_a');
			$valor_total_nota		   = $dba->result($res,$i,'valor_total_nota');
			$gta		 			   = $dba->result($res,$i,'gta');
			$numero_nota_produtor_ini  = $dba->result($res,$i,'numero_nota_produtor_ini');
			$numero_nota_produtor_fin  = $dba->result($res,$i,'numero_nota_produtor_fin');
			$condenas				   = $dba->result($res,$i,'condenas');
			$abate					   = $dba->result($res,$i,'abate');
			$insc_produtor			   = $dba->result($res,$i,'insc_produtor');
			$cnpj_emp				   = $dba->result($res,$i,'cnpj_emp');
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setNumeroNota($numero_nota);
			$notasent->setDataEmissao($data_emissao);
			$notasent->setCnpjCpf($cnpj_cpf);
			$notasent->setTipoV_R_A($tipo_v_r_a);
			$notasent->setValorTotalNota($valor_total_nota);
			$notasent->setGta($gta);
			$notasent->setNumeroNotaProdutorIni($numero_nota_produtor_ini);
			$notasent->setNumeroNotaProdutorFin($numero_nota_produtor_fin);
			$notasent->setCondenas($condenas);
			$notasent->setAbate($abate);
			$notasent->setInscProdutor($insc_produtor);
			$notasent->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function ListaNotasEntEmpresaUm($cod){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.numero_nota,
					n.data_emissao,
					n.cnpj_cpf,
					n.tipo_v_r_a,
					n.valor_total_nota,
					n.gta,
					n.numero_nota_produtor_ini,
					n.numero_nota_produtor_fin,
					n.condenas,
					n.abate,
					n.insc_produtor,
					n.cnpj_emp
				FROM
				   notasenttxt n
				WHERE
					n.numero_nota = "'.$cod.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  		   = $dba->result($res,$i,'id');
			$numero_nota 		  	   = $dba->result($res,$i,'numero_nota');
			$data_emissao 		  	   = $dba->result($res,$i,'data_emissao');
			$cnpj_cpf 			  	   = $dba->result($res,$i,'cnpj_cpf');
			$tipo_v_r_a 		  	   = $dba->result($res,$i,'tipo_v_r_a');
			$valor_total_nota		   = $dba->result($res,$i,'valor_total_nota');
			$gta		 			   = $dba->result($res,$i,'gta');
			$numero_nota_produtor_ini  = $dba->result($res,$i,'numero_nota_produtor_ini');
			$numero_nota_produtor_fin  = $dba->result($res,$i,'numero_nota_produtor_fin');
			$condenas				   = $dba->result($res,$i,'condenas');
			$abate					   = $dba->result($res,$i,'abate');
			$insc_produtor			   = $dba->result($res,$i,'insc_produtor');
			$cnpj_emp				   = $dba->result($res,$i,'cnpj_emp');
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setNumeroNota($numero_nota);
			$notasent->setDataEmissao($data_emissao);
			$notasent->setCnpjCpf($cnpj_cpf);
			$notasent->setTipoV_R_A($tipo_v_r_a);
			$notasent->setValorTotalNota($valor_total_nota);
			$notasent->setGta($gta);
			$notasent->setNumeroNotaProdutorIni($numero_nota_produtor_ini);
			$notasent->setNumeroNotaProdutorFin($numero_nota_produtor_fin);
			$notasent->setCondenas($condenas);
			$notasent->setAbate($abate);
			$notasent->setInscProdutor($insc_produtor);
			$notasent->setCnpjEmp($cnpj_emp);
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function PegaExclusao($cod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id
				FROM
					notasenttxt n
				WHERE
					n.numero_nota = "'.$cod.'"
						and n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);			
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function VerificaNotas($cod,$cnpj,$dta){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,
					n.xml
				FROM
					notasenttxt n
				WHERE
					n.numero_nota = "'.$cod.'"
						and n.cnpj_emp = "'.$cnpj.'" and concat(EXTRACT(MONTH FROM n.data_emissao),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$dta.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$xml 			  = $dba->result($res,$i,'xml');

			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);			
			$notasent->setXml($xml);
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function PegaNumerosNotaEntradas($nnota, $cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
				    n.id, n.numero_nota
				FROM
				    notasenttxt n
				WHERE
				    DATE_FORMAT(n.data_emissao, "%m/%Y") = "'.$nnota.'"
				        AND n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	   				= $dba->result($res,$i,'id');
			$numero_nota	    = $dba->result($res,$i,'numero_nota');
							
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setNumeroNota($numero_nota);			
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function NumeroDeEntradas($dtcomp,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					count(n.id) as num_entrada
				FROM
					notasenttxt n
				WHERE
					DATE_FORMAT(n.data_emissao, "%m/%Y") = "'.$dtcomp.'"
				        AND n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$num_entrada  = $dba->result($res,$i,'num_entrada');
				
			$notasent = new NotasEntTxt();

			$notasent->setNumeroEntrada($num_entrada);			
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}
	
	public function ListaNotasEntComp($mesano,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					distinct
					n.id,					
					n.xml,
					n.abate
				FROM
					notasenttxt n
					inner join notasen1txt en on (en.numero_nota = n.numero_nota) and (en.cnpj_emp = n.cnpj_emp)
				WHERE
					(concat(lpad(EXTRACT(MONTH FROM n.data_emissao),2,"0"),"/",EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'" 
					or 
					concat(lpad(EXTRACT(MONTH FROM en.data_abate),2,"0"),"/",EXTRACT(YEAR FROM en.data_abate)) = "'.$mesano.'")
					and 
						 n.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$xml	 		  = $dba->result($res,$i,'xml');
			$abate       	  = $dba->result($res,$i,'abate');			
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setXml($xml);
			$notasent->setAbate($abate);

			$vet[$i] = $notasent;

		}

		return $vet;

	}
	
	public function ListaNotasEntCompUm($nnota){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id,					
					n.xml
				FROM
					notasenttxt n
				WHERE
					n.numero_nota =  "'.$nnota.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id	  			  = $dba->result($res,$i,'id');
			$xml	 		  = $dba->result($res,$i,'xml');			
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);
			$notasent->setXml($xml);
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}

	public function PegaExclusaoCfopDesconciderar($cod,$cnpj,$mesano){

		$dba = $this->dba;

		$vet = array();

		$sql = 'SELECT 
					n.id
				FROM
					notasenttxt n
				WHERE
					n.numero_nota in ('.$cod.')
						and n.cnpj_emp = "'.$cnpj.'" and 
						AND CONCAT(LPAD(EXTRACT(MONTH FROM n.data_emissao),
                    2,
                    "0"),
            "/",
            EXTRACT(YEAR FROM n.data_emissao)) = "'.$mesano.'"';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$id       = $dba->result($res,$i,'id');
				
			$notasent = new NotasEntTxt();

			$notasent->setCodigo($id);			
			
			$vet[$i] = $notasent;

		}

		return $vet;

	}
	
	public function inserir($notasenttxt){

		$dba = $this->dba;		
		
		$numero_nota    	= $notasenttxt->getNumeroNota();
		$dataemissao    	= $notasenttxt->getDataEmissao();
		$cnpjcpf	    	= $notasenttxt->getCnpjCpf();
		$tipo_v_r_a     	= $notasenttxt->getTipoV_R_A();
		$valortotalnota  	= $notasenttxt->getValorTotalNota();
		$gta			 	= $notasenttxt->getGta();
		$numnotaprodutorini = $notasenttxt->getNumeroNotaProdutorIni();
		$numnotaprodutorfin = $notasenttxt->getNumeroNotaProdutorFin();
		$condenas 			= $notasenttxt->getCondenas();
		$abate				= $notasenttxt->getAbate();
		$inscprodutor		= $notasenttxt->getInscProdutor();
		$cnpjemp            = $notasenttxt->getCnpjEmp();
		$chave				= $notasenttxt->getChave();
		$xml 				= str_replace('\n','',$dba->real_escape_string($notasenttxt->getXml()));
		
		$sql = 'INSERT INTO `notasenttxt`
				(`numero_nota`,
				`data_emissao`,
				`cnpj_cpf`,
				`tipo_v_r_a`,
				`valor_total_nota`,
				`gta`,
				`numero_nota_produtor_ini`,
				`numero_nota_produtor_fin`,
				`condenas`,
				`abate`,
				`insc_produtor`,
				`cnpj_emp`,
				`chave_acesso`,
				`xml`)
				VALUES
				("'.$numero_nota .'",
				"'.$dataemissao.'",
				"'.$cnpjcpf.'",
				"'.$tipo_v_r_a.'",
				'.$valortotalnota.',
				"'.$gta.'",
				"'.$numnotaprodutorini.'",
				"'.$numnotaprodutorfin.'",
				"'.$condenas.'",
				"'.$abate.'",
				"'.$inscprodutor.'",
				"'.$cnpjemp.'",
				"'.$chave.'",
				"'.$xml.'")';
		//echo "{$sql}\n";
		$dba->query($sql);	
							
	}

	public function update($notasenttxt){

		$dba = $this->dba;		
		
		$id					= $notasenttxt->getCodigo();		
		$dataemissao    	= $notasenttxt->getDataEmissao();
		$cnpjcpf	    	= $notasenttxt->getCnpjCpf();
		$tipo_v_r_a     	= $notasenttxt->getTipoV_R_A();
		$valortotalnota  	= $notasenttxt->getValorTotalNota();
		$gta			 	= $notasenttxt->getGta();		
		$condenas 			= $notasenttxt->getCondenas();
		$abate				= $notasenttxt->getAbate();
		$inscprodutor		= $notasenttxt->getInscProdutor();
		
		$sql = 'UPDATE `notasenttxt`
		SET				
		`data_emissao` = "'.$dataemissao.'",
		`cnpj_cpf` = "'.$cnpjcpf.'",
		`tipo_v_r_a` = "'.$tipo_v_r_a.'",
		`valor_total_nota` = '.$valortotalnota.',
		`gta` = "'.$gta.'",		
		`condenas` = "'.$condenas.'",
		`abate` = "'.$abate.'",
		`insc_produtor` = "'.$inscprodutor.'"
		WHERE `id` = "'.$id.'" ';
		//echo "{$sql}\n";
		$dba->query($sql);	
							
	}
	

	public function deletar($notasenttxt){
	
		$dba = $this->dba;

		$id 			= $notasenttxt->getCodigo();
		$cnpjemp        = $notasenttxt->getCnpjEmp();
		
		$sql = 'DELETE FROM `notasenttxt`
				WHERE cnpj_emp = "'.$cnpjemp.'" and id ='.$id.'';

		$dba->query($sql);	
				
	}	

	public function updatepropterc($notasenttxt){
	
		$dba = $this->dba;

		$id 			= $notasenttxt->getCodigo();
		$cnpjemp        = $notasenttxt->getCnpjEmp();
		$abate			= $notasenttxt->getAbate();
		$dataemissao    = $notasenttxt->getDataEmissao();
		$sql = 'UPDATE `notasenttxt` 
				SET 
					abate = "'.$abate.'"
				WHERE
					id = "'.$id.'"';
							
		/*	$sql = 'UPDATE `notasenttxt` 
				SET 
					abate = "'.$abate.'"
				WHERE
					id = (select id from (SELECT 
							nt.id
						FROM
							notasenttxt nt
						WHERE
							nt.numero_nota = "'.$id.'"
								AND nt.cnpj_emp = "'.$cnpjemp.'"
								AND CONCAT(LPAD(EXTRACT(MONTH FROM nt.data_emissao), 2, "0"),
									"/",
									EXTRACT(YEAR FROM nt.data_emissao)) = "'.$dataemissao.'") as t)';*/								


								

		$dba->query($sql);	
				
	}		
}

?>