<?php 
require_once('inc.autoload.php');

class EmailDAO{

	private $SMTPAuth;
	private $SMTPSecure;
	private $Host;
	private $Port;
	private $Username;
	private $Password;

	function __construct($config = array()){


		$this->SMTPAuth   = $config['SMTPAuth'];
		$this->SMTPSecure = $config['SMTPSecure'];
		$this->Host 	  = $config['Host'];
		$this->Port       = $config['Port'];
		$this->Username   = $config['Username'];
		$this->Password   = $config['Password'];		

	}


	public function mandaEmail(stdClass $std){

		//var_dump($std);
		require_once('../PHPMailer/PHPMailerAutoload.php');

		$retorno = array();
		$Mailer  = new PHPMailer();

		$body  = file_get_contents('../tpl/corpoemail.html');
		$body  = str_replace(array('{mensagem}',
							 '{data}',
							 '{Name}',
							 '{assinatura}',
							 '{url}',
							 '{txt_btn}'), 
						array("{$std->mensagem}",									
							  "{$std->data}",
							  "{$std->nome}",
							  "{$std->assinatura}",
							  "{$std->url}",
							  "{$std->txt_btn}"), $body);


		$Mailer->isSMTP();
		$Mailer->isHTML(true);

		$Mailer->Charset    = 'UTF-8';	
		$Mailer->SMTPAuth   = $this->SMTPAuth;
		$Mailer->SMTPSecure = $this->SMTPSecure;
		$Mailer->Host       = $this->Host;
		$Mailer->Port       = $this->Port;
		$Mailer->Username   = $this->Username;
		$Mailer->Password   = $this->Password;
		
		$Mailer->From       = $std->email;
		$Mailer->FromName   = $std->nome;
		$Mailer->Subject    = $std->titulo;
						
		$Mailer->MsgHTML($body);
		
		$Mailer->AltBody = utf8_encode($std->mensagem);
		$para = !empty($std->para) ? $std->para : $std->email;
		$Mailer->addAddress($para);
		if(empty($std->para)){
			$Mailer->addAddress('agregar-carnes@seapdr.rs.gov.br');
			$Mailer->addAddress('ddsag@seapdr.rs.gov.br');
			
		}
		$Mailer->AddAttachment($std->Attachment['caminho'].$std->Attachment['arquivo']);	

		if ($Mailer->Send()){

			array_push($retorno, array('mensagem'=>$std->msgretorno,'tipo'=>'1'));

		}else{

			array_push($retorno, array('mensagem'=>' '.$std->msgretorno.'<br/> E-Mail Nao foi enviado!','tipo'=>'2'));

		}

		return $retorno;

	}





}	

?>