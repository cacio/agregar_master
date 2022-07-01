<?php 

/* 
 * Classe: Criptografia Teix 
 * Criada por: VinÃ­cius Teixeira 
 * Classe responsÃ¡vel por criptografar e descriptografar 
 * qualquer tipo de caracter, sendo especial ou nÃ£o. 
 * Contato: vttsouza@gmail.com | facebook.com/vteixeiras 
 */ 


Class MyCripty { 

    //privadas 
    private $msgCriptografada; 
    private $msgDescriptografada; 

    public  function __construct(){}
    public function criptografaMensagem($msg) { 
        //Converte a mensagem a ser criptografada em array 
        $arrayMsg = str_split($msg); //Aqui converto a mensagem em array 
        $this->msgCriptografada = ""; 
        $itemArray = 0; 

        //Percorre toda a mensagem 
        for($i=0; $i<count($arrayMsg); $i++) { 

            $itemArray = ord($arrayMsg[$i]) * 3; //Converte para o codigo da tabela ASC multiplicado por 3 

            if($itemArray < 100) { 
                $this->msgCriptografada .= "0".$itemArray; 
            } else { 
                $this->msgCriptografada .= "".$itemArray; 
            } 

            $itemArray = ""; 
        } 

        return $this->msgCriptografada; 
    } 

    public function descriptografaMensagem($msg) { 
        //Variaveis 
        $novaMsg = ""; 
        $this->msgDescriptografada = ""; 

        //Transforma os numeros em array 
        $arrayMsg = str_split($msg); 

        //Armazena a quantidade de blocos com 3 itens 
        $qntBlocos = count($arrayMsg) / 3; 

        //Percorre a mensagem e a cada 3 valores adiciona um separador (/) 
        //Varivavel de contador 
        $cont3 = 1; 

        //Montando a novo array da mensagem, dividindo com barra cada 3 caracteres 
        for($i=0; $i<count($arrayMsg); $i++) { 

            $novaMsg .= $arrayMsg[$i]; 

            if($cont3 % 3 == 0) { 
                $novaMsg .= "/"; 
            } 

            $cont3++; 

        } 

        //Cria um array com 3 numeros em cada posiÃ§Ã£o 
        $arrayEmBlocos = explode("/", $novaMsg); 

        //Percorre o novo array e descriptografa a mensagem 
        for($i=0; $i<count($arrayEmBlocos); $i++) { 

            $this->msgDescriptografada .= chr($arrayEmBlocos[$i]/3); 

        } 

        return $this->msgDescriptografada; 
    } 

} 
?> 