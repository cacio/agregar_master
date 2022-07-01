<?php

require_once('inc.autoload.php');

class FuncoesDAO{		

	public function __construct(){


	}

	
	public	function getExtension($str){
			
		$i = strrpos($str,".");
		if (!$i){
			return "";
		}
		$l = strlen($str) - $i;
		$ext = substr($str,$i+1,$l);
		return $ext;
		
	}
	
	
	public function Valida_Cnpj_Cpf($str){
		
		$cpf_cnpj  = new ValidaCpfCnpj($str);				
		$formatado = $cpf_cnpj->formata();
		$return    = ""; 
		
		if ($formatado) {
			$return =  true; 
		} else {
			$return = false;
		}	
		
		return $return;
	}
    
    public function isCnpjValid($cnpj)
	 	{
			//Etapa 1: Cria um array com apenas os digitos numéricos, isso permite receber o cnpj em diferentes formatos como "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
			$j=0;
			for($i=0; $i<(strlen($cnpj)); $i++)
				{
					if(is_numeric($cnpj[$i]))
						{
							$num[$j]=$cnpj[$i];
							$j++;
						}
				}
			//Etapa 2: Conta os dígitos, um Cnpj válido possui 14 dígitos numéricos.
			if(count($num)!=14)
				{
					$isCnpjValid=false;
				}
			//Etapa 3: O número 00000000000 embora não seja um cnpj real resultaria um cnpj válido após o calculo dos dígitos verificares e por isso precisa ser filtradas nesta etapa.
			if ($num[0]==0 && $num[1]==0 && $num[2]==0 && $num[3]==0 && $num[4]==0 && $num[5]==0 && $num[6]==0 && $num[7]==0 && $num[8]==0 && $num[9]==0 && $num[10]==0 && $num[11]==0)
				{
					$isCnpjValid=false;
				}
			//Etapa 4: Calcula e compara o primeiro dígito verificador.
			else
				{
					$j=5;
					for($i=0; $i<4; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);
					$j=9;
					for($i=4; $i<12; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);	
					$resto = $soma%11;			
					if($resto<2)
						{
							$dg=0;
						}
					else
						{
							$dg=11-$resto;
						}
					if($dg!=$num[12])
						{
							$isCnpjValid=false;
						} 
				}
			//Etapa 5: Calcula e compara o segundo dígito verificador.
			if(!isset($isCnpjValid))
				{
					$j=6;
					for($i=0; $i<5; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);
					$j=9;
					for($i=5; $i<13; $i++)
						{
							$multiplica[$i]=$num[$i]*$j;
							$j--;
						}
					$soma = array_sum($multiplica);	
					$resto = $soma%11;			
					if($resto<2)
						{
							$dg=0;
						}
					else
						{
							$dg=11-$resto;
						}
					if($dg!=$num[13])
						{
							$isCnpjValid=false;
						}
					else
						{
							$isCnpjValid=true;
						}
				}
			//Trecho usado para depurar erros.
			/*
			if($isCnpjValid==true)
				{
					echo "<p><font color="GREEN">Cnpj é Válido</font></p>";
				}
			if($isCnpjValid==false)
				{
					echo "<p><font color="RED">Cnpj Inválido</font></p>";
				}
			*/
			//Etapa 6: Retorna o Resultado em um valor booleano.
			return $isCnpjValid;			
		}
    
       public function valida_cnpj( $cnpj ) {
            // Deixa o CNPJ com apenas números
            $cnpj = preg_replace( '/[^0-9]/', '', $cnpj );
            
            // Garante que o CNPJ é uma string
            $cnpj = (string)$cnpj;
            
            // O valor original
            $cnpj_original = $cnpj;
            
            // Captura os primeiros 12 números do CNPJ
            $primeiros_numeros_cnpj = substr( $cnpj, 0, 12 );
            
            /**
             * Multiplicação do CNPJ
             *
             * @param string $cnpj Os digitos do CNPJ
             * @param int $posicoes A posição que vai iniciar a regressão
             * @return int O
             *
             */
            if ( ! function_exists('multiplica_cnpj') ) {
                function multiplica_cnpj( $cnpj, $posicao = 5 ) {
                    // Variável para o cálculo
                    $calculo = 0;
                    
                    // Laço para percorrer os item do cnpj
                    for ( $i = 0; $i < strlen( $cnpj ); $i++ ) {
                        // Cálculo mais posição do CNPJ * a posição
                        $calculo = $calculo + ( $cnpj[$i] * $posicao );
                        
                        // Decrementa a posição a cada volta do laço
                        $posicao--;
                        
                        // Se a posição for menor que 2, ela se torna 9
                        if ( $posicao < 2 ) {
                            $posicao = 9;
                        }
                    }
                    // Retorna o cálculo
                    return $calculo;
                }
            }
            
            // Faz o primeiro cálculo
            $primeiro_calculo = multiplica_cnpj( $primeiros_numeros_cnpj );
            
            // Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
            // Dígito é zero (0), caso contrário é 11 - o resto da divisão entre o cálculo e 11
            $primeiro_digito = ( $primeiro_calculo % 11 ) < 2 ? 0 :  11 - ( $primeiro_calculo % 11 );
            
            // Concatena o primeiro dígito nos 12 primeiros números do CNPJ
            // Agora temos 13 números aqui
            $primeiros_numeros_cnpj .= $primeiro_digito;
        
            // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
            $segundo_calculo = multiplica_cnpj( $primeiros_numeros_cnpj, 6 );
            $segundo_digito = ( $segundo_calculo % 11 ) < 2 ? 0 :  11 - ( $segundo_calculo % 11 );
            
            // Concatena o segundo dígito ao CNPJ
            $cnpj = $primeiros_numeros_cnpj . $segundo_digito;
            
            // Verifica se o CNPJ gerado é idêntico ao enviado
            if ( $cnpj === $cnpj_original ) {
                return true;
            }
        }

        public function ValidaCPF($cpf)
    {

        // Extrair somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        return true;
    }

	function ValidaData($data){
		// data é menor que 8
		if ( strlen($data) < 8){
			return false;
		}else{
			// verifica se a data possui
			// a barra (/) de separação
			if(strpos($data, "/") !== FALSE){
				//
				$partes = explode("/", $data);
				// pega o dia da data
				$dia = $partes[0];
				// pega o mês da data
				$mes = $partes[1];
				// prevenindo Notice: Undefined offset: 2
				// caso informe data com uma única barra (/)
				$ano = isset($partes[2]) ? $partes[2] : 0;
	 
				if (strlen($ano) < 4) {
					return false;
				} else {
					// verifica se a data é válida
					if (checkdate($mes, $dia, $ano)) {
						 return true;
					} else {
						 return false;
					}
				}
			}else{
				return false;
			}
		}
	}
	
	public function inscricao_estadual($ie, $estado)
    {

        $estado = strtoupper($estado);

        $pesos = array(
            'p1' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '2',
                '11' => '3',
                '12' => '4',
                '13' => '5',
                '14' => '6',
            ),
            'p2' => array(
                '1' => '0',
                '2' => '0',
                '3' => '2',
                '4' => '3',
                '5' => '4',
                '6' => '5',
                '7' => '6',
                '8' => '7',
                '9' => '8',
                '10' => '9',
                '11' => '2',
                '12' => '3',
                '13' => '4',
                '14' => '5',
            ),
            'p3' => array(
                '1' => '2',
                '2' => '0',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '2',
                '11' => '3',
                '12' => '4',
                '13' => '5',
                '14' => '6',
            ),
            'p4' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '0',
                '8' => '0',
                '9' => '0',
                '10' => '0',
                '11' => '0',
                '12' => '0',
                '13' => '0',
                '14' => '0',
            ),
            'p5' => array(
                '1' => '0',
                '2' => '8',
                '3' => '7',
                '4' => '6',
                '5' => '5',
                '6' => '4',
                '7' => '3',
                '8' => '2',
                '9' => '1',
                '10' => '0',
                '11' => '0',
                '12' => '0',
                '13' => '0',
                '14' => '0',
            ),
            'p6' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '0',
                '9' => '0',
                '10' => '8',
                '11' => '9',
                '12' => '0',
                '13' => '0',
                '14' => '0',
            ),
            'p7' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '1',
                '11' => '2',
                '12' => '3',
                '13' => '4',
                '14' => '5',
            ),
            'p8' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '2',
                '9' => '3',
                '10' => '4',
                '11' => '5',
                '12' => '6',
                '13' => '7',
                '14' => '8',
            ),
            'p9' => array(
                '1' => '0',
                '2' => '0',
                '3' => '2',
                '4' => '3',
                '5' => '4',
                '6' => '5',
                '7' => '6',
                '8' => '7',
                '9' => '2',
                '10' => '3',
                '11' => '4',
                '12' => '5',
                '13' => '6',
                '14' => '7',
            ),
            'p10' => array(
                '1' => '0',
                '2' => '0',
                '3' => '2',
                '4' => '1',
                '5' => '2',
                '6' => '1',
                '7' => '2',
                '8' => '1',
                '9' => '2',
                '10' => '1',
                '11' => '1',
                '12' => '2',
                '13' => '1',
                '14' => '0',
            ),
            'p11' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '11',
                '12' => '2',
                '13' => '3',
                '14' => '0',
            ),
            'p12' => array(
                '1' => '0',
                '2' => '0',
                '3' => '0',
                '4' => '0',
                '5' => '10',
                '6' => '8',
                '7' => '7',
                '8' => '6',
                '9' => '5',
                '10' => '4',
                '11' => '3',
                '12' => '1',
                '13' => '0',
                '14' => '0',
            ),
            'p13' => array(
                '1' => '0',
                '2' => '2',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8',
                '9' => '9',
                '10' => '10',
                '11' => '2',
                '12' => '3',
                '13' => '0',
                '14' => '0',
            ),
        );


        $md = '';

        $peso = '';

        $tamanho = 0;

        // Acre
        if ($estado == 'AC')
        {

            $fator = 0;


            /*
             * IEs após 11/99
             */
            if (strlen($ie) == 13)
            {
                $md = array(11, 11);

                $peso = array('p2', 'p1');

                $rot = array(array('E'), array('E'));

                $verificadores = array(12, 13);

                $tamanho = 13;
            }
            /*
             * IEs até 11/99
             */
            else if (strlen($ie) == 9)
            {
                $md = array(11);

                $peso = array('p1');

                $rot = array(array('E'));

                $verificadores = array(9);

                $tamanho = 9;
            }
            else
            {
                return false;
            }

            if (substr($ie, 0, 2) != '01')
            {
                return false;
            }
        }
        else if ($estado == 'AL')
        {
            $md = array(11);

            $rot = array(array('B', 'D'));

            $fator = 0;

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            if (substr($ie, 0, 2) != '24')
            {
                return false;
            }


            /*
             * Desabilitado pois existem IEs válidas que não se enqudram nessa regra
             * if (!in_array(substr($ie, 2, 1), array(0, 3, 5, 7, 8)))
              {
              return false;
              } */
        }
        else if ($estado == 'AP')
        {
            $md = array(11);

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            if ((int)$ie <= 30170009)
            {
                $rot = array(array('C', 'E'));

                $fator = 0;
            }
            else if ((int)$ie >= 30170010 AND (int)$ie <= 30190229)
            {    

                $rot = array(array('C', 'E'));

                $fator = 1;
            }
            else
            {  
                $rot = array(array('E'));

                $fator = 0;
            }

            if (substr($ie, 0, 2) != '03')
            {
                return false;
            }
        }
        else if ($estado == 'AM')
        {
            $md = array(11);

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $fator = 0;

            if (substr($ie, 0, 2) != '04' AND substr($ie, 0, 2) != '07')
            {
                return false;
            }
        }
        else if ($estado == 'BA')
        {

            $peso = array('p2', 'p3');

            $tamanho = 8;

            $verificadores = array(8, 7); // O 2° dígito vem primeiro

            $rot = array(array('E'), array('E'));

            $fator = 0;

            if (!in_array(substr($ie, 0, 1), array(6, 7, 9)))
            {
                $md = array(10, 10);
            }
            else
            {
                $md = array(11, 11);
            }
        }
        else if ($estado == 'CE')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $fator = 0;

            $md = array(11);

            if (substr($ie, 0, 1) != '0')
            {
                return false;
            }
        }
        else if ($estado == 'DF')
        {           
            
            $peso = array('p2', 'p1');

            $tamanho = 13;

            $verificadores = array(12, 13);

            $rot = array(array('E'), array('E'));

            $fator = 0;

            $md = array(11, 11);

            if (substr($ie, 0, 3) != '073' AND substr($ie, 0, 3) != '074' AND substr($ie, 0, 3) != '075')
            {
                return false;
            }
        }
        else if ($estado == 'ES')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $fator = 0;

            $md = array(11);

            if (substr($ie, 0, 2) != '00' AND substr($ie, 0, 2) != '08')
            {
                return false;
            }
        }
        else if ($estado == 'GO')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            if ($ie >= 101031050 AND $ie <= 101199979)
            {

                $fator = 0;
            }
            else
            {
                $fator = 1;
            }

            if (substr($ie, 0, 2) != '10' AND substr($ie, 0, 2) != '11' AND substr($ie, 0, 2) != '15')
            {
                return false;
            }
        }
        else if ($estado == 'MA')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '12')
            {
                return false;
            }
        }
        else if ($estado == 'MT')
        {

            $peso = array('p1');

            /*
             * No site da Fazenda diz que é 11, mas as IEs todas do MT que vi é 9
             */
            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;
        }
        else if ($estado == 'MS')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '28')
            {
                return false;
            }
        }
        else if ($estado == 'MG')
        {

            $peso = array('p10', 'p11');

            $tamanho = 13;

            $verificadores = array(12, 13);

            $rot = array(array('A', 'E'), array('E'));

            $md = array(10, 11);

            $fator = 0;
        }
        else if ($estado == 'PA')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '15')
            {
                return false;
            }
        }
        else if ($estado == 'PB')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '16')
            {
                return false;
            }
        }
        else if ($estado == 'PR')
        {

            $peso = array('p9', 'p8');

            $tamanho = 10;

            $verificadores = array(9, 10);

            $rot = array(array('E'), array('E'));

            $md = array(11, 11);

            $fator = 0;
        }
        else if ($estado == 'PE')
        {

            $peso = array('p7');

            $tamanho = 14;

            $verificadores = array(14);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 1;

            if (substr($ie, 0, 2) != '18')
            {
                return false;
            }
        }
        else if ($estado == 'PI')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '19')
            {
                return false;
            }
        }
        else if ($estado == 'RJ')
        {

            $peso = array('p8');

            $tamanho = 8;

            $verificadores = array(8);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (!in_array(substr($ie, 0, 1), array(1, 7, 8, 9)))
            {
                return false;
            }
        }
        else if ($estado == 'RN')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('B', 'D'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '20')
            {
                return false;
            }
        }
        else if ($estado == 'RS')
        {

            $peso = array('p1');

            $tamanho = 10;

            $verificadores = array(10);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 1) > 4)
            {
                return false;
            }
        }
        else if ($estado == 'RO')
        {

            $rot = array(array('E'));

            $md = array(11);

            /*
             * IEs até 07/2000
             */
            if (strlen($ie) == 9)
            {
                $tamanho = 9;

                $fator = 1;

                $verificadores = array(9);

                $peso = array('p4');

                if (substr($ie, 0, 1) == 0)
                {
                    return false;
                }
            }
            /*
             * IEs após 08/2000
             */
            else if (strlen($ie) == 14)
            {
                $tamanho = 14;

                $fator = 0;

                $verificadores = array(14);

                $peso = array('p1');
            }
        }
        else if ($estado == 'RR')
        {

            $peso = array('p5');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('D'));

            $md = array(9);

            $fator = 0;

            if (substr($ie, 0, 2) != '24')
            {
                return false;
            }
        }
        else if ($estado == 'SC')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;
        }
        else if ($estado == 'SP')
        {
            /*
             * IE comécio e indústria
             */
            if (substr($ie, 0, 1) != 'P')
            {
                $tamanho = 12;

                $verificadores = array(9, 12);

                $peso = array('p12', 'p13');

                $md = array(11, 11);

                $rot = array(array('D'), array('D'));
            }
            /*
             * IE rural
             */
            else
            {
                $tamanho = 13;

                $verificadores = array(10);

                $peso = array('p12');

                $md = array(11);

                $rot = array(array('D'));
            }

            $fator = 0;
        }
        else if ($estado == 'SE')
        {

            $peso = array('p1');

            $tamanho = 9;

            $verificadores = array(9);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;
        }
        else if ($estado == 'TO')
        {

            $peso = array('p6');

            $tamanho = 11;

            $verificadores = array(11);

            $rot = array(array('E'));

            $md = array(11);

            $fator = 0;

            if (substr($ie, 0, 2) != '29')
            {
                return false;
            }

            if (!in_array(substr($ie, 3, 1), array(1, 2, 3, 9)))
            {
                return false;
            }
        }

        if ($tamanho <> strlen($ie))
        {
            return false;
        }

        $j = 0;
    
        foreach ($verificadores as $verificador)
        {            
            $soma = 0;

            for ($i = 0; $i < ($verificador - 1); $i++)
            {
                $digito = substr($ie, $i, 1);

                $indice_peso = strlen($ie) - $i;

                $valor_peso = $pesos[$peso[$j]][$indice_peso];

                $mi = $digito * $valor_peso;

                if (in_array('A', $rot[$j]))
                {
                    $mi = $mi + floor($mi / 10);
                }

                $soma += $mi;
            }

            /*
             * Inclui o último dígito na soma
             * BA exceção digíto 2 ven antes
             */
            if ($estado == 'BA' AND $verificador == '7')
            {
                $soma += substr($ie, 7, 1) * 2;
            }

            if (in_array('B', $rot[$j]))
            {
                $soma = $soma * 10;
            }

            if (in_array('C', $rot[$j]))
            {
                $soma = $soma + 5 + (4 * $fator);
            }

            if (in_array('D', $rot[$j]))
            {
                $digito_verificador = $soma % $md[$j];
            }
            else if (in_array('E', $rot[$j]))
            {
                $digito_verificador = $md[$j] - ($soma % $md[$j]);
            }

            if ($digito_verificador == 10)
            {
                $digito_verificador = 0;
            }
            else if ($digito_verificador == 11)
            {
                $digito_verificador = $fator;
            }

            if ($digito_verificador != substr($ie, $verificador - 1, 1))
            {
                return false;
            }

            $j++;
        }
        
        return true;
    }
	public function is_valid_xml($xml,$name){
		$erros = "";
		
		$sxe = simplexml_load_file($xml);
		
		if(empty($sxe->NFe->infNFe->total->ICMSTot->vProd)){
			$erros .= "<br/> Erro na tag Valor total Produtos - ".$name." <br/>";
		}
		
		if(empty($sxe->NFe->infNFe->total->ICMSTot->vNF)){
			$erros .= "<br/> Erro na tag Valor total nota - ".$name."<br/> ";
		}
		
		if(empty($sxe->NFe->infNFe->ide->cNF)){
			$erros .= "<br/> Erro nas tags XML Incorreto - ".$name." <br/>";
		} 
		
		return $erros;
	}
	
	public function array_sort($array, $on, $order=SORT_ASC){
		
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
    
    
    public function limpar_string($string) {
          $res = array();   
          foreach($string as $key=>$val){
                
                if($val !== mb_convert_encoding(mb_convert_encoding($val, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
                $val = mb_convert_encoding($val, 'UTF-8', mb_detect_encoding($val));
                $val = htmlentities($val, ENT_NOQUOTES, 'UTF-8');
                $val = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\1', $val);
                $val = html_entity_decode($val, ENT_NOQUOTES, 'UTF-8');
                $val = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), ' ', $val);
                $val = preg_replace('/( ){2,}/', '$1', $val);
                $val = strtoupper(trim($val));
                $val = str_replace(' ','',$val);
                $res[] = $val;
          }  

          
        return $res;
    }

    public function zip_flatten($zipfile, $dest='.')
    {
        $zip = new ZipArchive;
        if ( $zip->open( $zipfile ) )
        {
            for ( $i=0; $i < $zip->numFiles; $i++ )
            {
                $entry = $zip->getNameIndex($i);
                if ( substr( $entry, -1 ) == '/' ) continue;
               
                $fp = $zip->getStream( $entry );
                $ofp = fopen( $dest.'/'.basename($entry), 'w' );
               
                if ( ! $fp )
                    throw new Exception('Não foi possível extrair o arquivo.');
               
                while ( ! feof( $fp ) )
                    fwrite( $ofp, fread($fp, 8192) );
               
                fclose($fp);
                fclose($ofp);
            }
    
            $zip->close();
        }else{
            return false;
        }

        return $zip;
    }  

    public function alt_stat($file) {

        clearstatcache();
        $ss=@stat($file);
        if(!$ss) return false; //Couldnt stat file
        
        $ts=array(
          0140000=>'ssocket',
          0120000=>'llink',
          0100000=>'-file',
          0060000=>'bblock',
          0040000=>'ddir',
          0020000=>'cchar',
          0010000=>'pfifo'
        );
        
        $p=$ss['mode'];
        $t=decoct($ss['mode'] & 0170000); // File Encoding Bit
        
        $str =(array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
        $str.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
        $str.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
        $str.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
        $str.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
        $str.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
        $str.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));
        
        $s=array(
        'perms'=>array(
          'umask'=>sprintf("%04o",@umask()),
          'human'=>$str,
          'octal1'=>sprintf("%o", ($ss['mode'] & 000777)),
          'octal2'=>sprintf("0%o", 0777 & $p),
          'decimal'=>sprintf("%04o", $p),
          'fileperms'=>@fileperms($file),
          'mode1'=>$p,
          'mode2'=>$ss['mode']),
        
        'owner'=>array(
          'fileowner'=>$ss['uid'],
          'filegroup'=>$ss['gid'],
          'owner'=>
          (function_exists('posix_getpwuid'))?
          @posix_getpwuid($ss['uid']):'',
          'group'=>
          (function_exists('posix_getgrgid'))?
          @posix_getgrgid($ss['gid']):''
          ),
        
        'file'=>array(
          'filename'=>$file,
          'realpath'=>(@realpath($file) != $file) ? @realpath($file) : '',
          'dirname'=>@dirname($file),
          'basename'=>@basename($file)
          ),
        
        'filetype'=>array(
          'type'=>substr($ts[octdec($t)],1),
          'type_octal'=>sprintf("%07o", octdec($t)),
          'is_file'=>@is_file($file),
          'is_dir'=>@is_dir($file),
          'is_link'=>@is_link($file),
          'is_readable'=> @is_readable($file),
          'is_writable'=> @is_writable($file)
          ),
         
        'device'=>array(
          'device'=>$ss['dev'], //Device
          'device_number'=>$ss['rdev'], //Device number, if device.
          'inode'=>$ss['ino'], //File serial number
          'link_count'=>$ss['nlink'], //link count
          'link_to'=>($s['type']=='link') ? @readlink($file) : ''
          ),
        
        'size'=>array(
          'size'=>$ss['size'], //Size of file, in bytes.
          'blocks'=>$ss['blocks'], //Number 512-byte blocks allocated
          'block_size'=> $ss['blksize'] //Optimal block size for I/O.
          ),
        
        'time'=>array(
          'mtime'=>$ss['mtime'], //Time of last modification
          'atime'=>$ss['atime'], //Time of last access.
          'ctime'=>$ss['ctime'], //Time of last status change
          'accessed'=>@date('Y M D H:i:s',$ss['atime']),
          'modified'=>@date('Y M D H:i:s',$ss['mtime']),
          'created'=>@date('Y M D H:i:s',$ss['ctime'])
          ),
        );
        
        clearstatcache();
        return $s;
        }


        public function verificatagxml($strx){
            
            if(isset($strx->nfeProc)){
				$tag = $strx->nfeProc;
			}else{
				$tag = $strx;
			}

            return $tag;
        }

     public   function money_format($format, $number)
{
    $regex  = '/%((?:[\^!\-]|\+|\(|\=.)*)([0-9]+)?'.
              '(?:#([0-9]+))?(?:\.([0-9]+))?([in%])/';
    if (setlocale(LC_MONETARY, 0) == 'C') {
        setlocale(LC_MONETARY, '');
    }
    $locale = localeconv();
    preg_match_all($regex, $format, $matches, PREG_SET_ORDER);
    foreach ($matches as $fmatch) {
        $value = floatval($number);
        $flags = array(
            'fillchar'  => preg_match('/\=(.)/', $fmatch[1], $match) ?
                           $match[1] : ' ',
            'nogroup'   => preg_match('/\^/', $fmatch[1]) > 0,
            'usesignal' => preg_match('/\+|\(/', $fmatch[1], $match) ?
                           $match[0] : '+',
            'nosimbol'  => preg_match('/\!/', $fmatch[1]) > 0,
            'isleft'    => preg_match('/\-/', $fmatch[1]) > 0
        );
        $width      = trim($fmatch[2]) ? (int)$fmatch[2] : 0;
        $left       = trim($fmatch[3]) ? (int)$fmatch[3] : 0;
        $right      = trim($fmatch[4]) ? (int)$fmatch[4] : $locale['int_frac_digits'];
        $conversion = $fmatch[5];

        $positive = true;
        if ($value < 0) {
            $positive = false;
            $value  *= -1;
        }
        $letter = $positive ? 'p' : 'n';

        $prefix = $suffix = $cprefix = $csuffix = $signal = '';

        $signal = $positive ? $locale['positive_sign'] : $locale['negative_sign'];
        switch (true) {
            case $locale["{$letter}_sign_posn"] == 1 && $flags['usesignal'] == '+':
                $prefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 2 && $flags['usesignal'] == '+':
                $suffix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 3 && $flags['usesignal'] == '+':
                $cprefix = $signal;
                break;
            case $locale["{$letter}_sign_posn"] == 4 && $flags['usesignal'] == '+':
                $csuffix = $signal;
                break;
            case $flags['usesignal'] == '(':
            case $locale["{$letter}_sign_posn"] == 0:
                $prefix = '(';
                $suffix = ')';
                break;
        }
        if (!$flags['nosimbol']) {
            $currency = $cprefix .
                        ($conversion == 'i' ? $locale['int_curr_symbol'] : $locale['currency_symbol']) .
                        $csuffix;
        } else {
            $currency = '';
        }
        $space  = $locale["{$letter}_sep_by_space"] ? ' ' : '';

        $value = number_format($value, $right, $locale['mon_decimal_point'],
                 $flags['nogroup'] ? '' : $locale['mon_thousands_sep']);
        $value = @explode($locale['mon_decimal_point'], $value);

        $n = strlen($prefix) + strlen($currency) + strlen($value[0]);
        if ($left > 0 && $left > $n) {
            $value[0] = str_repeat($flags['fillchar'], $left - $n) . $value[0];
        }
        $value = implode($locale['mon_decimal_point'], $value);
        if ($locale["{$letter}_cs_precedes"]) {
            $value = $prefix . $currency . $space . $value . $suffix;
        } else {
            $value = $prefix . $value . $space . $currency . $suffix;
        }
        if ($width > 0) {
            $value = str_pad($value, $width, $flags['fillchar'], $flags['isleft'] ?
                     STR_PAD_RIGHT : STR_PAD_LEFT);
        }

        $format = str_replace($fmatch[0], $value, $format);
    }
    return $format;
}
    function flash($type = null,$message = null){
        if($type && $message){
            $_SESSION['flash'] = [
                "type"=>$type,
                "message"=>$message
            ];
            return null;
        }
        
        if(!empty($_SESSION['flash']) && $flash = $_SESSION['flash']){
            unset($_SESSION['flash']);
            return "<div class=\"message {$flash["type"]}\">{$flash["message"]}</div>";
        }

        return null;
    }

    public function ajaxresponse($param,$values){
        return json_encode([$param => $values]);
    }
	
	public function formatar_cpf_cnpj($doc) {
 
        $doc = preg_replace("/[^0-9]/", "", $doc);
        $qtd = strlen($doc);
 
        if($qtd >= 11) {
 
            if($qtd === 11 ) {
 
                $docFormatado = substr($doc, 0, 3) . '.' .
                                substr($doc, 3, 3) . '.' .
                                substr($doc, 6, 3) . '.' .
                                substr($doc, 9, 2);
            } else {
                $docFormatado = substr($doc, 0, 2) . '.' .
                                substr($doc, 2, 3) . '.' .
                                substr($doc, 5, 3) . '/' .
                                substr($doc, 8, 4) . '-' .
                                substr($doc, -2);
            }
 
            return $docFormatado;
 
        } else {
            return 'Documento invalido';
        }
    }

}

?>