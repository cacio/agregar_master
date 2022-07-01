<?php

require_once('inc.autoload.php');
require_once('inc.connect.php');

class DuplicReceberDAO{


	private $dba;



	public function DuplicReceberDAO(){

		

		$dba = new DbAdmin('mysql');

		$dba->connect(HOST,USER,SENHA,BD);

		$this->dba = $dba;

	

	}

	
	public function ListaDuplicReceber($where){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'SELECT 
					d.id,
					d.cod_cliente,
					f.nome,
					d.emissao,
					d.numero,
					d.vencimento,
					d.valordoc,
					d.historico,
					d.datapag,
					d.saldo,
					d.valorpago,
					d.tipo,
					d.banco,
					d.nome	
				FROM
					duplic_receber d
				left join ficha f on (f.id = d.cod_cliente) '.$where.' order by d.vencimento asc';
		//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			
			$cod        = $dba->result($res,$i,'id');	
			$codcli     = $dba->result($res,$i,'cod_cliente');
			$nomecli    = $dba->result($res,$i,'nome');
			$emissao    = $dba->result($res,$i,'emissao');
			$numero     = $dba->result($res,$i,'numero');
			$vencimento = $dba->result($res,$i,'vencimento');
			$valordoc   = $dba->result($res,$i,'valordoc');
			$hist	    = $dba->result($res,$i,'historico');	
			$datapag	= $dba->result($res,$i,'datapag');
			$saldo	    = $dba->result($res,$i,'saldo');
			$valorpago  = $dba->result($res,$i,'valorpago');
			$tipo		= $dba->result($res,$i,'tipo');
			$banco	    = $dba->result($res,$i,'banco');
			$nome	    = $dba->result($res,$i,'nome');
			
			$duplic = new DuplicReceber();

			$duplic->setCodigo($cod);
			$duplic->setCodigoCliente($codcli);
			$duplic->setEmissao($emissao);
			$duplic->setNumero($numero);
			$duplic->setVencimento($vencimento);
			$duplic->setValorDoc($valordoc);
			$duplic->setHistorico($hist);
			$duplic->setDataPag($datapag);
			$duplic->setSaldo($saldo);
			$duplic->setValorPago($valorpago);
			$duplic->setTipo($tipo);
			$duplic->setBanco($banco);
			$duplic->setNomeCliente($nomecli);
			$duplic->setNome($nome);
			
			$vet[$i] = $duplic;

			

		}
		return $vet;
	}


	public function ListaDuplicReceberUm($id){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'SELECT 
					d.id,
					d.cod_cliente,
					f.nome,
					d.emissao,
					d.numero,
					d.vencimento,
					d.valordoc,
					d.historico,
					d.datapag,
					d.saldo,
					d.valorpago,
					d.tipo,
					d.banco,
					d.formpagto	
				FROM
					duplic_receber d
				left join ficha f on (f.id = d.cod_cliente)
				WHERE d.id = '.$id.'';
			
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			
			$cod        = $dba->result($res,$i,'id');	
			$codcli     = $dba->result($res,$i,'cod_cliente');
			$nomecli    = $dba->result($res,$i,'nome');
			$emissao    = $dba->result($res,$i,'emissao');
			$numero     = $dba->result($res,$i,'numero');
			$vencimento = $dba->result($res,$i,'vencimento');
			$valordoc   = $dba->result($res,$i,'valordoc');
			$hist	    = $dba->result($res,$i,'historico');	
			$datapag	= $dba->result($res,$i,'datapag');
			$saldo	    = $dba->result($res,$i,'saldo');
			$valorpago  = $dba->result($res,$i,'valorpago');
			$tipo		= $dba->result($res,$i,'tipo');
			$banco	    = $dba->result($res,$i,'banco');
			$formpagto  = $dba->result($res,$i,'formpagto');
			
			$duplic = new DuplicReceber();

			$duplic->setCodigo($cod);
			$duplic->setCodigoCliente($codcli);
			$duplic->setEmissao($emissao);
			$duplic->setNumero($numero);
			$duplic->setVencimento($vencimento);
			$duplic->setValorDoc($valordoc);
			$duplic->setHistorico($hist);
			$duplic->setDataPag($datapag);
			$duplic->setSaldo($saldo);
			$duplic->setValorPago($valorpago);
			$duplic->setTipo($tipo);
			$duplic->setBanco($banco);
			$duplic->setNomeCliente($nomecli);
			$duplic->setFormPagto($formpagto);
			$vet[$i] = $duplic;

			

		}
		return $vet;
	}
	
	
	public function ListaDuplicRecebePegaCilente($id){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'SELECT 
					d.cod_cliente,
					f.nome
				FROM
					duplic_receber d
				left join ficha f on (f.id = d.cod_cliente)
				WHERE d.id = '.$id.'';
			
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			

			$codcli     = $dba->result($res,$i,'cod_cliente');
			$nomecli    = $dba->result($res,$i,'nome');
					
			$duplic = new DuplicReceber();

			$duplic->setCodigoCliente($codcli);		
			$duplic->setNomeCliente($nomecli);
			
			$vet[$i] = $duplic;

		}
		return $vet;
	}
	
	
	public function RelatorioDuplicReceberPorRecebimento($where){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'select 
					id,datapag, vencimento, numero, nome, valordoc,formpagto
				from
					(select 
						d.id,
						d.datapag as datapag,
							d.vencimento as vencimento,
							d.numero as numero,
							f.nome as nome,
							d.cod_cliente,
							if(count(t.id) > 0, sum(t.valor), d.valordoc) AS valordoc,
							d.formpagto
					from
						duplic_receber d
					inner join ficha f ON (f.id = d.cod_cliente)
					left join tbreceber t on (t.numero = d.id)
					WHERE
						SUBSTRING(d.numero FROM 1 FOR 1) <> "P"						
						group by d.id,d.datapag,d.vencimento,f.nome,d.formpagto,d.cod_cliente
						 union all Select
							d.id, 
							d.datapag as datapag,
							d.vencimento as vencimento,
							d.numero as numero,
							d.nome as nome,
							d.cod_cliente,
							if(count(t.id) > 0, sum(t.valor), d.valordoc) AS valordoc,
							d.formpagto
					from
						duplic_receber d
						left join tbreceber t on (t.numero = d.id)						
							where SUBSTRING(d.numero FROM 1 FOR 1) = "P"
							group by d.id,d.datapag,d.vencimento,d.formpagto,d.cod_cliente) as tables '.$where.'  GROUP BY id,datapag, vencimento, numero, nome, valordoc order by nome asc';
		//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){

			$id			= $dba->result($res,$i,'id');
			$datapag	= $dba->result($res,$i,'datapag');
			$vencimento = $dba->result($res,$i,'vencimento');
			$numero     = $dba->result($res,$i,'numero');
			$nomecli    = $dba->result($res,$i,'nome');
			$valordoc   = $dba->result($res,$i,'valordoc');
			$formpagto  = $dba->result($res,$i,'formpagto');
			
			$duplic = new DuplicReceber();
			
			$duplic->setCodigo($id);
			$duplic->setDataPag($datapag);
			$duplic->setVencimento($vencimento);
			$duplic->setNumero($numero);
			$duplic->setNomeCliente($nomecli);			
			$duplic->setValorDoc($valordoc);
			$duplic->setFormPagto($formpagto);
			
			$vet[$i] = $duplic;

		}
		return $vet;
	}
	
	
	public function RelatorioVencidos($where){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'SELECT 
					f.id,
					f.nome,
					f.email,
					f.telefone,
					(select 
							dr.vencimento
						from
							duplic_receber dr
						where
							dr.cod_cliente = f.id
								and (dr.datapag is null
								or dr.datapag = "0000-00-00"
								or dr.datapag = "")
						order by dr.vencimento asc
						limit 1) as vencimento,
					c.nome as categoria
				FROM
					duplic_receber d
						inner join
					ficha f ON (f.id = d.cod_cliente)
						inner join 
					categoria c on (c.codigo = f.codcategoria)
					'.$where.'
				group by f.id , f.nome,c.nome,f.email,f.telefone
				order by vencimento';
		//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			
			$id			= $dba->result($res,$i,'id');
			$nome		= $dba->result($res,$i,'nome');
			$vencimento = $dba->result($res,$i,'vencimento');
			$categoria	= $dba->result($res,$i,'categoria');
			$email		= $dba->result($res,$i,'email');
			$telefone	= $dba->result($res,$i,'telefone');
			
			$duplic = new DuplicReceber();
			

			$duplic->setVencimento($vencimento);
			$duplic->setCodigoCliente($id);
			$duplic->setNomeCliente($nome);
			$duplic->setCategoria($categoria);
			$duplic->setEmail($email);
			$duplic->setTelefone($telefone);
			
			$vet[$i] = $duplic;

		}
		return $vet;
	}
	
	public function RelatorioDuplicReceberPorRecebimentoSintetico($where,$where2){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'select nome , valordoc,valordoccontri,dinheiro, cheque from (select 
						f.nome as nome,
						sum(d.valordoc) as valordoc,
						(Select 
								sum(dr.valordoc)
							from
								duplic_receber dr
									inner join
								ficha fc ON (fc.id = dr.cod_cliente)
							   '.$where2.' and SUBSTRING(dr.numero FROM 1 FOR 1) = "#") as valordoccontri,
						(Select 
							sum(dr.valordoc)
						from
							duplic_receber dr
						inner join ficha fc ON (fc.id = dr.cod_cliente)
						'.$where2.'
								AND dr.formpagto = "1") as dinheiro	,
						(Select 
							sum(dr.valordoc)
						from
							duplic_receber dr
						inner join ficha fc ON (fc.id = dr.cod_cliente)
						'.$where2.'
								AND dr.formpagto = "2") as cheque		   
					from
						duplic_receber d
							inner join
						ficha f ON (f.id = d.cod_cliente)
					'.$where.' and SUBSTRING(d.numero FROM 1 FOR 1) <> "P"
					group by f.nome
					union all

					select 
						"Promocoes" as nome,
						sum(d.valordoc) as valordoc,
						0 as valordoccontri,
						(select 
							sum(dr.valordoc) 
						from
							duplic_receber dr
							'.$where.'
								and SUBSTRING(dr.numero FROM 1 FOR 1) = "P"
								AND dr.formpagto = "1" and dr.numero = d.numero) as dinheiro,
						(select 
							sum(dr.valordoc) 
						from
							duplic_receber dr
							'.$where.'
								and SUBSTRING(dr.numero FROM 1 FOR 1) = "P"
								AND dr.formpagto = "2" and dr.numero = d.numero) as cheque
					from
						duplic_receber d
					   '.$where.' and  SUBSTRING(d.numero FROM 1 FOR 1) = "P"
					group by "Promocoes") as tables order by nome asc';
		//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			
		
			$nomecli    	 = $dba->result($res,$i,'nome');
			$valordoc   	 = $dba->result($res,$i,'valordoc');
			$valordoccontri  = $dba->result($res,$i,'valordoccontri');
			$dinheiro		 = $dba->result($res,$i,'dinheiro');
			$cheque			 = $dba->result($res,$i,'cheque');
			
			$duplic = new DuplicReceber();
						
			$duplic->setNomeCliente($nomecli);			
			$duplic->setValorDoc($valordoc);
			$duplic->setValorContribuicao($valordoccontri);
			$duplic->setDinheiro($dinheiro);
			$duplic->setCheque($cheque);
			
			$vet[$i] = $duplic;

		}
		return $vet;
	}
	
	public function RelatorioMensalCliente($where){

		$dba = $this->dba;	

		$vet = array();
		
		$sql = 'SELECT     
					d.datapag,
					d.emissao,
					d.numero,
					d.vencimento,
					d.valordoc    
				FROM
					duplic_receber d 
				'.$where.' ';
		
		//echo $sql;	
		$res = $dba->query($sql);
		$num = $dba->rows($res);
		 
		for($i = 0; $i < $num; $i++){
			
			$emissao	= $dba->result($res,$i,'emissao');
			$numero		= $dba->result($res,$i,'numero');
			$vencimento = $dba->result($res,$i,'vencimento');
			$valordoc   = $dba->result($res,$i,'valordoc');
			$datapag    = $dba->result($res,$i,'datapag');
			
			$duplic = new DuplicReceber();
			
			$duplic->setEmissao($emissao);
			$duplic->setVencimento($vencimento);
			$duplic->setNumero($numero);
			$duplic->setValorDoc($valordoc);
			$duplic->setDataPag($datapag);
			
			$vet[$i] = $duplic;

		}
		return $vet;
	}
	
	public function RelatorioSociosemdia($where){

			$dba = $this->dba;	

			$vet = array();

			$sql = 'SELECT 
						f.nome, 
						MAX(d.datapag) as datapag, 
						MAX(d.valordoc) as valordoc
					FROM
						duplic_receber d
							INNER JOIN
						ficha f ON (f.id = d.cod_cliente) 
					'.$where.' GROUP BY f.nome';

			//echo $sql;	
			$res = $dba->query($sql);
			$num = $dba->rows($res);

			for($i = 0; $i < $num; $i++){

				$nome		= $dba->result($res,$i,'nome');
				$datapag	= $dba->result($res,$i,'datapag');
				$valordoc	= $dba->result($res,$i,'valordoc');


				$duplic = new DuplicReceber();

				$duplic->setNome($nome);
				$duplic->setDataPag($datapag);
				$duplic->setValorPago($valordoc);


				$vet[$i] = $duplic;

			}
			return $vet;
		}
	
	public function RelatoAlteracaoValores($where){

			$dba = $this->dba;	

			$vet = array();

			$sql = 'SELECT
						d.id, 
						f.nome,    
						d.numero,
						d.vencimento,
						d.valordoc    
					FROM
						duplic_receber d
						inner join ficha f on (f.id = d.cod_cliente) 
					'.$where.'	';

			//echo $sql;	
			$res = $dba->query($sql);
			$num = $dba->rows($res);

			for($i = 0; $i < $num; $i++){
					
				$id			= $dba->result($res,$i,'id');
				$nome		= $dba->result($res,$i,'nome');
				$numero		= $dba->result($res,$i,'numero');
				$vencimento	= $dba->result($res,$i,'vencimento');
				$valordoc	= $dba->result($res,$i,'valordoc');


				$duplic = new DuplicReceber();
					
				$duplic->setCodigo($id);
				$duplic->setNome($nome);
				$duplic->setNumero($numero);
				$duplic->setVencimento($vencimento);
				$duplic->setValorPago($valordoc);


				$vet[$i] = $duplic;

			}
			return $vet;
		}

	public function inserir($duplic){
		
		$dba = $this->dba;
		
		$codcli    = $duplic->getCodigoCliente();
		$emissao   = $duplic->getEmissao();
		$numero	   = $duplic->getNumero();
		$vencim    = $duplic->getVencimento();
		$vldoc	   = $duplic->getValorDoc();		
		$historic  = $duplic->getHistorico();
		$datapag   = $duplic->getDataPag();
		$saldo	   = $duplic->getSaldo();
		$valorpag  = $duplic->getValorPago();
		$tipo      = $duplic->getTipo();
		$banco	   = $duplic->getBanco();
		$nome      = $duplic->getNome();
		$formpagto = $duplic->getFormPagto();
		
		$sql = 'INSERT INTO `duplic_receber`
				(`cod_cliente`,
				`emissao`,
				`numero`,
				`vencimento`,
				`valordoc`,
				`historico`,
				`datapag`,
				`saldo`,
				`valorpago`,
				`tipo`,
				`banco`,
				`nome`,
				`formpagto`)
				VALUES
				('.$codcli.',
				"'.$emissao.'",
				"'.$numero.'",
				"'.$vencim.'",
				'.$vldoc.',
				"'.$historic.'",
				"'.$datapag.'",
				'.$saldo.',
				'.$valorpag.',
				"'.$tipo.'",
				"'.$banco.'",
				"'.$nome.'",
				"'.$formpagto.'")';

		$res = $dba->query($sql);
		
	}
	
	public function alterar($duplic){
		$dba = $this->dba;
		
		$codigo   = $duplic->getCodigo();
		$codcli   = $duplic->getCodigoCliente();
		$emissao  = $duplic->getEmissao();
		//$numero	  = $duplic->getNumero();
		$vencim   = $duplic->getVencimento();
		$vldoc	  = $duplic->getValorDoc();		
		$historic = $duplic->getHistorico();
		$datapag  = $duplic->getDataPag();
		//$saldo	  = $duplic->getSaldo();
		//$valorpag = $duplic->getValorPago();
		//$tipo     = $duplic->getTipo();
		$banco	  = $duplic->getBanco();
		$formpagto = $duplic->getFormPagto();
		
		$sql = 'UPDATE `duplic_receber`
				SET
				`cod_cliente` = '.$codcli.',
				`emissao` = "'.$emissao.'",
				`vencimento` = "'.$vencim.'",
				`valordoc` = '.$vldoc.',
				`historico` = "'.$historic.'",
				`datapag` = "'.$datapag.'",
				`banco` = "'.$banco.'",
				`formpagto` = "'.$formpagto.'"
				WHERE `id` = '.$codigo.'';
 		
		$res = $dba->query($sql);
	}
	
	public function alterardatapagamento($duplic){
		$dba = $this->dba;
		
		$codigo    = $duplic->getCodigo();
		$datapag   = $duplic->getDataPag();
		$formpagto = $duplic->getFormPagto();
		
		$sql = 'UPDATE `duplic_receber`
				SET
				`datapag` = "'.$datapag.'",
				`formpagto` = "'.$formpagto.'"					
				WHERE `id` = '.$codigo.'';
 		
		$res = $dba->query($sql);
	}
	
	public function updatedatapag($duplic){
		$dba = $this->dba;
		
		$codigo    = $duplic->getCodigo();
		$datapag   = $duplic->getDataPag();		
		
		$sql = 'UPDATE `duplic_receber`
				SET
				`datapag` = "'.$datapag.'"									
				WHERE `id` = '.$codigo.'';
 		
		$res = $dba->query($sql);
	}
	
	public function deletar($duplic){
		$dba = $this->dba;
		
		$codigo = $duplic->getCodigo();
		
		$sql = 'DELETE FROM `duplic_receber`
				WHERE `id` = '.$codigo.'';
				
		$res = $dba->query($sql);
	}
	
	public function alterarValores($duplic){
		$dba = $this->dba;
		
		$codigo    = $duplic->getCodigo();
		$valordoc  = $duplic->getValorDoc();		
		
		$sql = 'UPDATE `duplic_receber`
				SET
				`valordoc` = '.$valordoc.'						
				WHERE `id` = '.$codigo.'';
 		
		$res = $dba->query($sql);
	}

	public function proximoid(){
		
		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "duplic_receber"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$duplic = new DuplicReceber();

		$duplic->setProximoId($prox_id);

		$vet[$i] = $duplic;

		return $vet;	

	}
	
	public function addEspacos($string, $posicoes, $onde)
        {
            
            $aux = strlen($string);
            
            if ($aux >= $posicoes)
                return substr ($string, 0, $posicoes);
            
            $dif = $posicoes - $aux;
            
            $espacos = '';
            
            for($i = 0; $i < $dif; $i++) {
                $espacos .= ' ';
            }
            
            if ($onde === 'I')
                return $espacos.$string;
            else
                return $string.$espacos;
            
        }
		
	public function centraliza($info)
        {
            global $n_colunas;
            
            $aux = strlen($info);
            
            if ($aux < $n_colunas) {
                // calcula quantos espaços devem ser adicionados
                // antes da string para deixa-la centralizada
                $espacos = floor(($n_colunas - $aux) / 2);
                
                $espaco = '';
                for ($i = 0; $i < $espacos; $i++){
                    $espaco .= ' ';
                }
                
                // retorna a string com os espaços necessários para centraliza-la
                return $espaco.$info;
                
            } else {
                // se for maior ou igual ao número de colunas
                // retorna a string cortada com o número máximo de colunas.
                return substr($info, 0, $n_colunas);
            }
            
        }
		
		 public  function removerFormatacaoNumero( $strNumero )
    {
 
        $strNumero = trim( str_replace( "R$", null, $strNumero ) );
 
        $vetVirgula = explode( ",", $strNumero );
        if ( count( $vetVirgula ) == 1 )
        {
            $acentos = array(".");
            $resultado = str_replace( $acentos, "", $strNumero );
            return $resultado;
        }
        else if ( count( $vetVirgula ) != 2 )
        {
            return $strNumero;
        }
 
        $strNumero = $vetVirgula[0];
        $strDecimal = mb_substr( $vetVirgula[1], 0, 2 );
 
        $acentos = array(".");
        $resultado = str_replace( $acentos, "", $strNumero );
        $resultado = $resultado . "." . $strDecimal;
 
        return $resultado;
 
    }
		
		public function valorPorExtenso( $valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false )
    	{
 
        $valor = $this->removerFormatacaoNumero($valor);
 
        $singular = null;
        $plural = null;
 
        if ( $bolExibirMoeda )
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
 
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
 
 
        if ( $bolPalavraFeminina )
        {
 
            if ($valor == 1) 
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else 
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
 
 
            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
 
 
        }
 
 
        $z = 0;
 
        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );
 
        for ( $i = 0; $i < count( $inteiro ); $i++ ) 
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ ) 
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }
 
        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
 
            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;
 
            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
 
            if ( $r )
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
        }
 
        $rt = mb_substr( $rt, 1 );
 
        return($rt ? trim( $rt ) : "zero");
 
    }
	
	public function filter($string)
    {   
    if ( !preg_match('/[\x80-\xff]/', $string) )
                return $string;

        if ($this->seems_utf8($string)) {
                $chars = array(
                // Decompositions for Latin-1 Supplement
                chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
                chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
                chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
                chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
                chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
                chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
                chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
                chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
                chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
                chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
                chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
                chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
                chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
                chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
                chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
                chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
                chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
                chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
                chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
                chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
                chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
                chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
                chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
                chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
                chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
                chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
                chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
                chr(195).chr(191) => 'y',
                // Decompositions for Latin Extended-A
                chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
                chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
                chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
                chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
                chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
                chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
                chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
                chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
                chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
                chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
                chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
                chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
                chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
                chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
                chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
                chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
                chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
                chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
                chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
                chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
                chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
                chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
                chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
                chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
                chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
                chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
                chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
                chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
                chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
                chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
                chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
                chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
                chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
                chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
                chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
                chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
                chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
                chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
                chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
                chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
                chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
                chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
                chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
                chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
                chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
                chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
                chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
                chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
                chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
                chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
                chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
                chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
                chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
                chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
                chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
                chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
                chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
                chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
                chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
                chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
                chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
                chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
                chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
                chr(197).chr(190) => 'z', chr(197).chr(191) => 's',
                // Euro Sign
                chr(226).chr(130).chr(172) => 'E',
                // GBP (Pound) Sign
                chr(194).chr(163) => '');

                $string = strtr($string, $chars);
        } else {
                // Assume ISO-8859-1 if not UTF-8
                $chars['in'] = chr(128).chr(131).chr(138).chr(142).chr(154).chr(158)
                        .chr(159).chr(162).chr(165).chr(181).chr(192).chr(193).chr(194)
                        .chr(195).chr(196).chr(197).chr(199).chr(200).chr(201).chr(202)
                        .chr(203).chr(204).chr(205).chr(206).chr(207).chr(209).chr(210)
                        .chr(211).chr(212).chr(213).chr(214).chr(216).chr(217).chr(218)
                        .chr(219).chr(220).chr(221).chr(224).chr(225).chr(226).chr(227)
                        .chr(228).chr(229).chr(231).chr(232).chr(233).chr(234).chr(235)
                        .chr(236).chr(237).chr(238).chr(239).chr(241).chr(242).chr(243)
                        .chr(244).chr(245).chr(246).chr(248).chr(249).chr(250).chr(251)
                        .chr(252).chr(253).chr(255);

                $chars['out'] = "EfSZszYcYuAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy";

                $string = strtr($string, $chars['in'], $chars['out']);
                $double_chars['in'] = array(chr(140), chr(156), chr(198), chr(208), chr(222), chr(223), chr(230), chr(240), chr(254));
                $double_chars['out'] = array('OE', 'oe', 'AE', 'DH', 'TH', 'ss', 'ae', 'dh', 'th');
                $string = str_replace($double_chars['in'], $double_chars['out'], $string);
        }

        return $string;
    }
	public function seems_utf8($str) {
        $length = strlen($str);
        for ($i=0; $i < $length; $i++) {
                $c = ord($str[$i]);
                if ($c < 0x80) $n = 0; # 0bbbbbbb
                elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
                elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
                elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
                elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
                elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
                else return false; # Does not match any model
                for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
                        if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                                return false;
                }
        }
        return true;
}

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}
	
}

?>