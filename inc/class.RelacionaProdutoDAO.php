<?php
require_once('inc.autoload.php');
require_once('inc.connect.php');

class RelacionaProdutoDAO{

	private $dba;

	public function __construct(){
		$dba = new DbAdmin('mysql');
		$dba->connect(HOST,USER,SENHA,BD);
		$this->dba = $dba;
	}

	public function ListaRelacionado($nprod,$cnpj){

		$dba = $this->dba;

		$vet = array();

		$sql ='SELECT 
					r.id, 
					r.id_rel, 
					r.id_prod, 
					r.n_nota, 
					r.cnpj_emp,
					p.descricao
				FROM
					relaciona_produto r
				left join produtos_agregar p on (p.codigo = r.id_rel)   	
				WHERE
					r.id_prod = "'.$nprod.'"  AND 
					r.cnpj_emp = "'.$cnpj.'" ';
			  		
		$res = $dba->query($sql);
		$num = $dba->rows($res); 

		for($i = 0; $i<$num; $i++){		

			$cod       = $dba->result($res,$i,'id');
			$id_rel    = $dba->result($res,$i,'id_rel');
			$id_prod   = $dba->result($res,$i,'id_prod');
			$n_nota    = $dba->result($res,$i,'n_nota');
			$descricao = $dba->result($res,$i,'descricao');	
				
			$rel = new RelacionaProduto();

			$rel->setCodigo($cod);
			$rel->setIdRelacionado($id_rel);			
			$rel->setIdProduto($id_prod);
			$rel->setNumeroNota($n_nota);
			$rel->setDescProd($descricao);
			
			$vet[$i] = $rel;

		}

		return $vet;

	}

	public function inserir($rel){

		$dba = $this->dba;		
		
		$id_rel  = $rel->getIdRelacionado();			
		$id_prod = $rel->getIdProduto();
		$n_nota  = $rel->getNumeroNota();				
		$cnpjemp = $rel->getCnpjEmp();
		
		$sql = 'INSERT INTO `relaciona_produto`
				(`id_rel`,
				`id_prod`,
				`n_nota`,
				`cnpj_emp`)
				VALUES
				("'.$id_rel.'",
				 "'.$id_prod.'",
				 "'.$n_nota.'",
				 "'.$cnpjemp.'")';

		$dba->query($sql);	
							
	}
	
	public function update($rel){
	
		$dba = $this->dba;

		$id 	 = $rel->getCodigo();
		$id_rel  = $rel->getIdRelacionado();			
		$id_prod = $rel->getIdProduto();
		$n_nota  = $rel->getNumeroNota();				
		$cnpjemp = $rel->getCnpjEmp();
		
		$sql = 'UPDATE `relaciona_produto`
				SET
				`id_rel` = "'.$id_rel.'",
				`id_prod` = "'.$id_prod.'",
				`n_nota` = "'.$n_nota.'",
				`cnpj_emp` = "'.$cnpjemp.'"
				WHERE `id` = '.$id;

		$dba->query($sql);	
				
	}	

	public function deletar($rel){
	
		$dba = $this->dba;

		$id = $rel->getCodigo();
		
		$sql = 'DELETE FROM `relaciona_produto`
				WHERE  `id` = '.$id;

		$dba->query($sql);	
				
	}	
	
	public function proximoid(){
		
		$dba = $this->dba;

		$vet = array();		

		$sql = 'SHOW TABLE STATUS LIKE "relaciona_produto"';

		$res = $dba->query($sql);

		$i = 0;

		$prox_id = $dba->result($res,$i,'Auto_increment');	 

		$rel = new RelacionaProduto();

		$rel->setProximoId($prox_id);

		$vet[$i] = $rel;

		return $vet;	

	}
	
		
}

?>