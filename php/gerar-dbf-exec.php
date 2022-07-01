<?php
	session_start();
	require_once('../inc/inc.autoload.php');

	if(isset($_REQUEST['act']) and !empty($_REQUEST['act'])){

		$act = $_REQUEST['act'];

		switch($act){

			case 'gerar':
              error_reporting(E_ALL);
              ini_set('display_errors', 'On');
              if(!empty($_SESSION['cnpj'])){
                
                
                $pathFile      = '../arquivos/config.json';
                $configJson    = file_get_contents($pathFile);
                $installConfig = json_decode($configJson);
              
                  $cnpjemp        =  $_SESSION['cnpj'];
                  $mesano         = !empty($_SESSION['apura']['mesano']) ? $_SESSION['apura']['mesano'] : $_REQUEST['mesano'];
                  $pastamesano    = str_replace('/','',$mesano);
                  $caminhopadrao  = "../arquivos/{$cnpjemp}/dbf/{$pastamesano}/";
                  $caminhopadrao2 = "../arquivos/{$cnpjemp}/dbf/";

                
                  $dados = array(
                     'mesano'=>"{$mesano}",
                     'cnpjemp'=>"{$cnpjemp }",
                     'caminho'=>"{$caminhopadrao}",
                     'caminho2'=>"{$caminhopadrao2}",
                     'pastamesano'=>"{$pastamesano}" 
                  );


                  $dbf = new Dbf($dados);

                  $arqdbf = $dbf->Save();

                  if(count($arqdbf) > 0){
                    foreach ($arqdbf as $key => $value) {
                      unlink($caminhopadrao.$value);
                    }                    
                  }

                  
                  $dao = new ResumoDAO();
                  $vet = $dao->MesAnoCompetenciaFinalizadaParaEnvio($mesano,$cnpjemp);
                  $num = count($vet);	

                  if($num > 0){
                    
                      $resu         = $vet[0];
                          
                      $cod 	        = $resu->getCodigo();			
                      $status       = $resu->getStatus();
                      $protocolo    = $resu->getProtocolo();
                      $competencia  = $resu->getCompetenc();
                      $nome 		    = $resu->getNomeStatus();
                      $razao_social = $resu->getRazaoSocialEmp();	

                      $msg          = utf8_decode("Razão Social: {$razao_social}<br/> Cnpj:{$cnpjemp}<br>Recibo de protocolo de numero <mark>{$protocolo}</mark> gerado com sucesso ");	


                      $msgs = new MensagemEmpresa();
                      
                      $msgs->setTitulo('Apuração de arquivos agregar competencia '.$competencia.' ');
                      $msgs->setMensagem("Razão Social: {$razao_social}<br/> Cnpj:{$cnpjemp}<br>Recibo de protocolo de numero <mark>{$protocolo}</mark> gerado com sucesso;  ");
                      $msgs->setIdModalidade(1);
                      $msgs->setIdEmpresa($_SESSION['id_emp']);
                      $msgs->setData(date('Y-m-d'));
                      $msgs->setTimesTamp(time());
                      
                      $daoms  = new MensagemEmpresaDAO();
                      
                      $vetms  = $daoms->proximoid();
                      $prox   = $vetms[0];
                      $idprox = $prox->getProximoId();

                      $daoms->inserir($msgs);

                      
                      $dados = array(
                        'SMTPAuth'=>true,
                        'SMTPSecure'=>''.$installConfig->emails->smtpsegure.'',
                        'Host'=>''.$installConfig->emails->host.'',
                        'Port'=>$installConfig->emails->port,
                        'Username'=>''.$installConfig->emails->username.'',
                        'Password'=>''.$installConfig->emails->senhaem.'',		
                      );


                      $daoe = new EmailDAO($dados);

                      $std = new stdClass();

                      $std->mensagem    = "{$msg}";
                      $std->titulo      = utf8_decode("Apuração de arquivos agregar competencia {$competencia}");
                      $std->nome 		  = "Agregar";
                      $std->assinatura  = "agregar";
                      $std->data        = date('d/m/Y');
                      $std->url         = "http://agregarcarnesrs.com.br/";
                      $std->email       = "{$installConfig->emails->username}";
                      $std->msgretorno  = "COMPETÊNCIA ENVIADO COM SUCESSO!";
                      $std->txt_btn     = utf8_decode("VER COMPETÊNCIA");
                      $std->Attachment  = array(
                        'arquivo'=>'AGREGARS.ZIP',
                        'caminho'=>''.$caminhopadrao.'',
                      );

                      $results =  $daoe->mandaEmail($std);
                  }
                  //echo $results[0]['mensagem'];
                  $res           = array();
                  array_push($res,array(
                      'msg'=>$results[0]['mensagem']
                  ));
                  
                  echo json_encode($res);
                } // final do if do cnpj empresa

			break;	
										
		}
	}

?>