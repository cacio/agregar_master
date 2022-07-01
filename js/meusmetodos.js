// JavaScript Document
$(document).ready(function(){
	$('#myTab').tab('show');
	$(".select2usuario").select2();	
});

$(document).on('click','#importdados',function(){
		
	var htm  = '';	
		
		htm += '<form enctype="multipart/form-data" method="post" id="upload">';
		
			htm += '<div class="input-prepend">'+
						'<label><strong>ARQUIVOS:</strong></label>'+
						'<span class="add-on fa fa-list"></span>'+
						'<input type="file" accept=".txt" name="file_upload" class="form-control span3" id="file_upload" multiple>'+
					'</div>';
					
					htm += '<button class="btn btn-block btn-primary" type="submit">IMPORTAR ARQUIVOS</button>';
		
		htm += '</form>';
			
			
		$.confirm({
			title: 'Importa Dados',
			content: ''+htm+'',
			type: 'blue',
			typeAnimated: true,
			buttons: {				
				close: function () {
				}
			}
		});	
			
});


$(document).on('blur','input[name="mesanocomptxt"]',function(){

		var mesano = $(this).val();
		$('input[name="setmesanocomp"]').val(mesano);

		var hr  = window.location.href;
		var hrm = hr.split('?');			
		window.history.pushState( null, null, hrm[0]+'?act=Validado&anomes='+mesano+'');	

		validamesanocometenciatxt(mesano);

	});


	function validamesanocometenciatxt(mesano){

		if(soNumero(mesano) != ""){					
			$.ajax({
				type:'POST',
				cache:false, 
				dataType: "json",
				url:"../php/lancamentos-exec.php",
				data:{act:'validmesano',mesano:mesano},
				beforeSend: function(){
					
				},
				success: function(data){							
					
					if(data[0].tipo == '1'){
						
						$.confirm({
						    title: 'Mensagem do sistema',
						    content: ''+data[0].msg+'',
						    type: 'orange',
						    typeAnimated: true,
						    buttons: {
						        sim: {
						            text: 'SIM',
						            btnClass: 'btn-green',
						            action: function(){
						            	$("input[type='file']").click();
						            }
						        },
						        nao: {
						            text: 'NÃO',
						            btnClass: 'btn-orange',
						            action: function(){
						            	$('input[name="mesanocomp"]').val('');
										$('input[name="mesanocomp"]').focus();
						            }
						        },						        
						    }
						});
							
						return false;

					}else if(data[0].tipo == '3'){
						
						$.confirm({
						    title: 'Mensagem do sistema',
						    content: ''+data[0].msg+'',
						    type: 'orange',
						    typeAnimated: true,
						    buttons: {
						        sim: {
						            text: 'FECHAR',
						            btnClass: 'btn-green',
						            action: function(){
						            	window.location.reload();
						            }
						        },						        						       
						    }
						});
												


					}else{

						$("input[type='file']").click();
					}
				},
				error:function(data){	
						
					alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});
		}	

		return false;

	}

var step2 = $(".vl-wizard").steps({
    headerTag: "h6"
    , bodyTag: "section"
    , transitionEffect: "fade"
    , titleTemplate: '<span class="step">#index#</span> #title#'
    , labels: {
        finish: "ENVIAR"
    }, onStepChanging: function (event, currentIndex, newIndex) {
				
		var patternData = /^[0-9]{2}\/[0-9]{4}$/;
		if(!patternData.test($('input[name="setmesanocomp"]').val())){
				//alert("Digite a data no formato Dia/Mês/Ano");
				//form_registra_entrada.dataentrada.focus();
				return false;
		}


		if(parseInt($(".num_erros").html()) > 0){
			alert("Existem ("+$(".num_erros").html()+") erros a ser corrigido! ");
			return false;
		}
		
		if((newIndex+1) == 1){
			//$(".validacaoarquivos").hide();
			//deletanotas();
			var hr  = window.location.href;
			var hrm = hr.split('?');
			var msa = $('input[name="setmesanocomp"]').val();					
			window.history.pushState( null, null, hrm[0]+'?act=Validando&anomes='+msa+'');
			if($('input[name="numeroerros"]').val() > 0){
				valida_nota_novamenteTXT($('input[name="setmesanocomp"]').val());
			}
			return true;
		}

		if((newIndex+1) == 2){
			

			if($('input[name="setmesanocomp"]').val() == '__/____'){
				alert("Competencia com erros rever os seus XMLS e a resposta ao enviar seus XMLS");
				return false;
				
			}
			
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde verificando dados!");
							
			var setval = setInterval(function(){
				var nErro = $('input[name="numeroerros"]').val();
				if(nErro > 0){
					var valida = valida_nota_proxpassoTXT($('input[name="setmesanocomp"]').val());
				}else{
					var valida =0;
				}
				if(valida == 0){
					var dtinis = "01/"+$("#datainis").val();
					var dtfins = daysInMonth($("#datainis").val().split('/')[0],$("#datainis").val().split('/')[1])+'/'+$("#datainis").val();
					
					if($("input[name='mostranovaapuracao']").val() == 0){
						apuracaodosdados2(dtinis,dtfins);	
					}else{
						$("#myModal_pdf").hide();
					}
					

					var hr  = window.location.href;
					var hrm = hr.split('?');
					var msa = $('input[name="setmesanocomp"]').val();					
					window.history.pushState( null, null, hrm[0]+'?act=Apurado&anomes='+msa+'');
					clearInterval(setval);
					if(act == 'Entregue'){
						step2.steps("next");
					}
				}else{
					clearInterval(setval);
					alert("Existem ("+valida+") erros a ser corrigido! ");
					step2.steps("previous");
					//return false;				
				}	
			},500);
			
			return true;				
			
			
		}

		if((newIndex+1) == 3){
			var hr  = window.location.href;
			var hrm = hr.split('?');
			var msa = $('input[name="setmesanocomp"]').val();		
			
			window.history.pushState( null, null, hrm[0]+'?act=Entregue&anomes='+msa+'');	

			gerarprotocolodeapauracao(2);
			return true;
		}
		
		//return true;			
    }
    , onFinished: function (event, currentIndex) {

    	var cn = $.confirm({
			title: 'Mensagem do sistema',
			content: function () {
				var self = this;
				return $.ajax({
					url: '../php/apuracao-exec.php',
					dataType: 'json',
					method: 'POST',
					data: {act:'enviaremail'}
				}).done(function (response) {
					self.setContent(''+response[0].mensagem+'');
					//2a363f
					$("#myModal_pdf").show();
					$(".modal-header_PDF").html('');
					$(".modal-content_PDF").css({
						'background-color':'#2a363f !important',
						'color':'#fff',
					});
					$(".modal-body_PDF").html("<img src='../images/check.gif' /><br/><br/>"+response[0].mensagem+"<br/><br/><a href='admin.php' class='btn btn-success'>FECHAR</a>");
					cn.close();
				}).fail(function(){
					self.setContent('Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!');
				});
			},
			buttons: {
				sim: {
					text: 'FECHAR',
					btnClass: 'btn-green',
					action: function(){
						window.location.href = "admin.php";
					}
				},						        						       
			}

		});
        /*$.ajax({
				type:'POST',
				cache:false, 
				dataType: "json",
				url:"../php/apuracao-exec.php",
				data: {act:'enviaremail'},
				beforeSend: function(){
					
				},
				success: function(data){
					
					//aqui a mensagem

					$.confirm({
						    title: 'Mensagem do sistema',
						    content: ''+data[0].mensagem+'',
						    type: 'green',
						    typeAnimated: true,
						    buttons: {
						        sim: {
						            text: 'FECHAR',
						            btnClass: 'btn-green',
						            action: function(){
						            	window.location.reload();
						            }
						        },						        						       
						    }
						});
					
				},
				error:function(data){			
					alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});*/
				
			return false;
            
    }
});    
	
function apuracaodosdados2(dtini,dtfim){

	function setProgress(precis) {
        var progress = $('#progress');
       
        progress.toggleClass('active', precis < 100);

        progress.css({
            width: precis = precis.toPrecision(3)+'%'
        }).html('<span>'+precis+'</span>');
    }
    var xload;

    $.ajax({
	  type: 'POST',
	  url: "../php/apuracao-exec.php",
	  data: {act:'apurar2',mesanoini:dtini,mesanofim:dtfim},
	  beforeSend: function(XMLHttpRequest)
	  {
		$("#myModal_pdf").show();
		$(".modal-body_PDF p").html("Aguarde fazendo a apuração dos dados!");
	  	
	  },
	  success: function(data){
	    
	   		var arr = $.parseJSON(data);
			const apuraString = JSON.stringify(arr);
			localStorage.setItem('apuracao',apuraString);
			
			if(arr.tipolayout == 1){
            	var text  = ApuracaoSintetico();
				$('.apura_layout').html('VER LAYOUT NOVO');
				$('.apura_layout').attr('data-id',2);
				$('.apura_layout').popover('show');
				$(".apura_layout").removeClass('btn-secondary');
				$(".apura_layout").addClass('btn-warning');
			}else{
				var text  = ApuracaoAnalitica();
				$('.apura_layout').html('VOLTAR PARA O LAYOUT ANTIGO');
				$('.apura_layout').attr('data-id',1);
				$('.apura_layout').popover('hide');
				$(".apura_layout").removeClass('btn-warning');
				$(".apura_layout").addClass('btn-secondary');
			}
			

			$('#content').html(text);
			//$('#content-analitico').html(txtanalitoco);
			
			$("#myModal_pdf").hide();
			$(".loader").remove();

			$("input[name='mostranovaapuracao']").val(1);
						
	  }
	});

    

    return false;
}

$(document).on("click",'.apura_layout',function(){

	var id = $(this).attr('data-id');

	if(id == 1){
		var text  = ApuracaoSintetico();
		$('.apura_layout').html('VER LAYOUT NOVO');
		$('.apura_layout').attr('data-id',2);
		$('.apura_layout').popover('show');
		$(".apura_layout").removeClass('btn-secondary');
		$(".apura_layout").addClass('btn-warning');
	}else{
		var text  = ApuracaoAnalitica();
		$('.apura_layout').html('VOLTAR PARA O LAYOUT ANTIGO');
		$('.apura_layout').attr('data-id',1);
		$('.apura_layout').popover('hide');
		$(".apura_layout").removeClass('btn-warning');
		$(".apura_layout").addClass('btn-secondary');
	}
	
	$('#content').html(text);

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/apuracao-exec.php",
		data:{act:'uplayoutapuracao',id:id},
		beforeSend: function(){
			
		},
		success: function(data){
			console.log(data);		
		},
		error:function(data){				
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});

	
	return false;
});

function ApuracaoSintetico(){
	const obj = localStorage.getItem("apuracao");
	const arr = JSON.parse(obj);

	var txt     = "";
	var txt2    = "";
	var xexport = "";

	if(arr.exportacao.length > 0){
		xexport = `
			<tr class="b-1">
				<td class="fs-2 b-1-td bgt" colspan="3">Exportação</td>						
			</tr>
		`;
		for (let index = 0; index < arr.exportacao.length; index++) {
			const element = arr.exportacao[index];
			xexport += `				
				<tr>
					<td class="fs-2">${element.nome}</td>
					<td class="text-right fs-2">R$ ${element.valor}</td>
					<td></td>
				</tr>				
			`;
		}	
	}

	txt += `			
		<div  align="center" class="col-12" style="margin:0 auto; width:100%;">
		MÊS/ANO-> ${arr.mesano}<br/>
		----------------------------------<br/>
		<br/>
		ANIMAIS ABATIDOS<br/>
		---------------------------------<br/>
		<br/>
		<div style="text-align:center; display:inline-table;">
		<div style="display: inline-table; margin-right: 20px; text-align: left; width: 88px;">
			BOVINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			${arr.animais[0].bovinos}
		</div>
		<br/>
		<div style="display:inline-table; text-align:left; margin-right:20px; width:80px; ">
			BUBALINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			${arr.animais[0].bubalinos}
		</div>
		<br/>
		<div style="display:inline-table; text-align:left; margin-right:20px; width:87px; ">
			OVINOS
		</div>
		<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">
			${arr.animais[0].ovinos}
		</div>
		
	</div>
	<br/>
	<br/>
	APURACAO DO CREDITO<br/>
	---------------------------------------- <br/>
	`;

		txt += `			 	
			<div class="col-8">
			<table class="table" style="width:100%;"> 
				<thead>
					<tr>
						<th>NOTAS</th>
						<th class="text-right">BASE</th>
						<th class="text-right">CREDITO</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>DE PRODUTOR..........................</td>
						<td class="text-right">${number_format(arr.basecredito[0].base,2,',','.')}</td>
						<td class="text-right">${number_format(arr.basecredito[0].credito,2,',','.')}</td>
					</tr>
					
					<tr>
						<td>VENDAS RIO GRANDE DO SUL 3%</td>
						<td class="text-right">${number_format(arr.vendars[0].basers,2,',','.')}</td>
						<td class="text-right">${number_format(arr.vendars[0].creditors,2,',','.')}</td>
					</tr>

					<tr>
						<td>VENDAS RIO GRANDE DO SUL 4%</td>
						<td class="text-right">${number_format(arr.vendars2[0].basers2,2,',','.')}</td>
						<td class="text-right">${number_format(arr.vendars2[0].creditors2,2,',','.')}</td>
					</tr>

					<tr>
						<td>VENDAS OUTROS ESTADOS 3%</td>
						<td class="text-right">${number_format(arr.vendasdifrs[0].basedifrs,2,',','.')}</td>
						<td class="text-right">${number_format(arr.vendasdifrs[0].creditodifrs,2,',','.')}</td>
					</tr>

					<tr>
						<td>VENDAS OUTROS ESTADOS 4%</td>
						<td class="text-right">${number_format(arr.vendasdifrs2[0].basedifrs2,2,',','.')}</td>
						<td class="text-right">${number_format(arr.vendasdifrs2[0].creditodifrs2,2,',','.')}</td>
					</tr>

				</tbody>
				<tfoot>
					<tr>
						<td>TOTAL GERAIS</td>
						<td class="text-right">${arr.total_geral_base}</td>
						<td class="text-right">${arr.total_geral_credito}</td>
					</tr>
				</tfoot>

			</table>
		</div>
		</div>
		`;	

			return txt;

}


function ApuracaoAnalitica(){

	const obj  = localStorage.getItem("apuracao");
	const data = JSON.parse(obj);

	var dat = new Date();
	var dia     = dat.getDate();           // 1-31
	var dia_sem = dat.getDay();            // 0-6 (zero=domingo)
	var mes     = dat.getMonth();          // 0-11 (zero=janeiro)
	var ano2    = dat.getYear();           // 2 dígitos
	var ano4    = dat.getFullYear();       // 4 dígitos
	var hora    = dat.getHours();          // 0-23
	var min     = dat.getMinutes();        // 0-59
	var seg     = dat.getSeconds();        // 0-59
	var mseg    = dat.getMilliseconds();   // 0-999
	var tz      = dat.getTimezoneOffset(); // em minutos
	// Formata a data e a hora (note o mês + 1)
	var str_data = dia + '/' + (mes+1) + '/' + ano4;
	var str_hora = hora + ':' + min + ':' + seg;

	var xhtm = '';
	var xexport = '';
	for (let index = 0; index < data.exportacao.length; index++) {
		const element = data.exportacao[index];
		xexport += `
		<tr height=20 style='height:15.0pt'>
			<td height=20 class=xl75 style='height:15.0pt;border-top:none;border-left:none;padding-left: 23px;'>
				<span style='mso-spacerun:yes'></span>( - ) Valor Glosado Exportação - (${element.nome})
			</td>
			<td class=xl76 align=right style='border-top:none;border-left:none'>
				- R$ ${element.valor}
			</td>
			<td class=xl77 style='border-top:none;border-left:none'>&nbsp;</td>
		</tr>
		`;
	}
	var tot_base_dentroestado    = (parseFloat(data.vendars[0].basers) + parseFloat(data.vendars2[0].basers2));
	var tot_base_foraestado      = (parseFloat(data.vendasdifrs[0].basedifrs) + parseFloat(data.vendasdifrs2[0].basedifrs2));
	var tot_base_foradentro      =  parseFloat(tot_base_dentroestado) + parseFloat(tot_base_foraestado);

	var tot_credito_dentroestado = (parseFloat(data.vendars[0].creditors) + parseFloat(data.vendars2[0].creditors2));
	var tot_credito_foraestado   = (parseFloat(data.vendasdifrs[0].creditodifrs) + parseFloat(data.vendasdifrs2[0].creditodifrs2));
	var tot_credito_dentrofora   =  parseFloat(tot_credito_dentroestado) + parseFloat(tot_credito_foraestado);
	var competencia 			 = $('input[name="setmesanocomp"]').val();

	xhtm = `
			<table border="0" cellpadding="0" cellspacing="0" style='border-collapse:collapse;table-layout:fixed;margin: 0 auto;'>

				<col width="85" style='mso-width-source:userset;mso-width-alt:3108;width:64pt'>
				<col width="353" style='mso-width-source:userset;mso-width-alt:12909;width:265pt'>
				<col width="103" style='mso-width-source:userset;mso-width-alt:3766;width:77pt'>
				<col width="156" style='mso-width-source:userset;mso-width-alt:5705;width:117pt'>
				
				<tr height="21" style='height:15.75pt'>
					<td colspan="3" height="21" class="xl95" width="541" style='height:15.75pt; width:406pt'>Gerado em:${window.location.hostname}</td>
					<td class="xl69" width="156" style='width:117pt'>Emitido em: ${str_data} - ${str_hora}</td>
				</tr>
				
				<tr height="20" style='height:15.0pt'>
					<td colspan="4" rowspan="2" height="41" class="xl96" style='border-right:1.0pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>Apuração Mensal AGREGAR -  Mês/Ano: ${data.mesano}</td>
				</tr>
				
				<tr height="21" style='height:15.75pt'>
				</tr>
				
				<tr height="20" style='height:15.0pt'>
					<td colspan="4" height="20" class="xl102" style='height:15.0pt'>${data.empresa[0].razao_social} - ${data.empresa[0].cnpj.replace(/\D/g, '').replace(/^(\d{2})(\d{3})?(\d{3})?(\d{4})?(\d{2})?/, "$1.$2.$3/$4-$5")}</td>
				</tr>
				
				<tr height="20" style='height:15.0pt'>
					<td colspan="4" rowspan="2" height="41" class="xl103" style='height:30.75pt'>Nº DE ANIMAIS ABATIDOS</td>
				</tr>
				
				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td colspan=2 height=21 class=xl104 style='border-right:.5pt solid black;height:15.75pt'>Espécie</td>
					<td colspan=2 class=xl106 style='border-right:1.0pt solid black;border-left:none'>Nº de Cabeças</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl133 style='border-right:.5pt solid black;height:15.0pt'>BOVINOS</td>
					<td colspan=2 class=xl145 style='border-right:1.0pt solid black;border-left:none'>${data.animais[0].bovinos}</td>
				</tr>
				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl134 style='border-right:.5pt solid black;height:15.0pt'>BUBALINOS</td>
					<td colspan=2 class=xl139 style='border-right:1.0pt solid black;border-left:none'>${data.animais[0].bubalinos}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td colspan=2 height=21 class=xl135 style='border-right:.5pt solid black;height:15.75pt'>OVINOS</td>
					<td colspan=2 class=xl141 style='border-right:1.0pt solid black;border-left:none'>${data.animais[0].ovinos}</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 rowspan=2 height=41 class=xl137 style='border-right:.5pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE CABEÇAS ABATIDAS</td>
					<td colspan=2 rowspan=2 class=xl143 style='border-right:1.0pt solid black;border-bottom:1.0pt solid black'>${ parseInt(data.animais[0].bovinos) + parseInt(data.animais[0].bubalinos) + parseInt(data.animais[0].ovinos) }</td>
				</tr>
				
				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=4 rowspan=3 height=61 class=xl129 style='border-bottom:1.0pt solid black;height:45.75pt'>APURAÇÃO DE CRÉDITO</td>
				</tr>
				
				<tr height=20 style='height:15.0pt'>
				</tr>

				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td colspan=4 height=21 class=xl130 style='border-right:1.0pt solid black;height:15.75pt'>COMPRAS</td>
				</tr>
				
				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black; height:15.0pt'>Origem</td>
					<td class=xl70 style='border-left:none'>*Valor da Base</td>
					<td class=xl71 style='border-left:none'>Valor do Crédito</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=4 height=81 class=xl111 width=85 style='height:60.75pt;border-top:none;width:64pt'>Crédito 3,6%</td>
					<td class=xl72 style='border-top:none;border-left:none'>NOTAS DE COMPRA DE ANIMAIS (PRODUTORES)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.basecredito[0].valorentrada,2,',','.')}</td>
					<td class=xl74 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>
				
				${xexport}
				
				<tr height=21 style='height:15.75pt'>
					<td height=21 class=xl78 style='height:15.75pt;border-top:none;border-left:	none; padding-left: 23px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Compra</td>
					<td class=xl79 align=right style='border-top:none;border-left:none'>-R$	${number_format(data.basecredito[0].devolucao,2,',','.')}</td>
					<td class=xl80 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td colspan=${data.exportacao.length} height=21 class=xl132 style='height:15.75pt'>( = ) TOTAL DE COMPRAS</td>
					<td class=xl81 align=right>R$ ${number_format(data.basecredito[0].base,2,',','.')}</td>
					<td class=xl82 align=right>R$ ${number_format(data.basecredito[0].credito,2,',','.')}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td height=21 class=xl83 width=85 style='height:15.75pt;width:64pt'></td>
					<td class=xl66></td>
					<td class=xl67></td>
					<td class=xl67></td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td colspan=4 height=21 class=xl130 style='border-right:1.0pt solid black;height:15.75pt'>VENDAS</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
					<td class=xl70 style='border-left:none'>*Valor da Base</td>
					<td class=xl71 style='border-left:none'>Valor do Crédito</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=3 height=60 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.0pt;border-top:none;width:64pt'>Crédito 3%</td>
					<td class=xl72 style='border-top:none;border-left:none'>VENDAS DENTRO DO ESTADO (RS)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars[0].saida,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'>( - ) Devoluções de Vendas DENTRO do (RS)</td>
					<td class=xl76 align=right style='border-top:none;border-left:none'>-R$	${number_format(data.vendars[0].devolucao,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl72 style='height:15.0pt;border-top:none;border-left:none'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS (RS) - Crédito (3%)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars[0].basers,2,',','.')}</td>
					<td class=xl84 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars[0].creditors,2,',','.')}</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl114 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
					<td class=xl86 style='border-top:none;border-left:none'>*Valor da Base</td>
					<td class=xl87 style='border-top:none;border-left:none'>Valor do Crédito</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=3 height=61 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.75pt;border-top:none;width:64pt'>Crédito 4%</td>
					<td class=xl72 style='border-top:none;border-left:none'>VENDAS DENTRO DO ESTADO (RS)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars2[0].saida,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none; padding-left: 25px;'>( - ) Devoluções de Vendas DENTRO do (RS)</td>
					<td class=xl76 align=right style='border-top:none;border-left:none'>-R$	${number_format(data.vendars2[0].devolucao,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td height=21 class=xl88 style='height:15.75pt;border-top:none;border-left:none;padding-left: 25px;'>( = ) TOTAL FINAL VENDAS (RS) - Crédito (4%)</td>
					<td class=xl89 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars2[0].basers2,2,',','.')}</td>
					<td class=xl90 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendars2[0].creditors2,2,',','.')}</td>
				</tr>
				<tr height=20 style='height:15.0pt'>
					<td colspan=2 rowspan=2 height=41 class=xl116 style='border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE VENDAS DENTRO DO ESTADO (RS)</td>
					<td rowspan=2 class=xl117 align=right style='border-bottom:1.0pt solid black'>R$ ${ number_format((parseFloat(data.vendars[0].basers) + parseFloat(data.vendars2[0].basers2)),2,',','.') }</td>
					<td rowspan=2 class=xl118 align=right style='border-bottom:1.0pt solid black'>R$ ${ number_format((parseFloat(data.vendars[0].creditors) + parseFloat(data.vendars2[0].creditors2)),2,',','.')}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl109 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
					<td class=xl70 style='border-left:none'>*Valor da Base</td>
					<td class=xl71 style='border-left:none'>Valor do Crédito</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=3 height=60 class=xl111 width=85 style='border-bottom:.5pt solid black;	height:45.0pt;border-top:none;width:64pt'>Crédito 3%</td>
					<td class=xl72 style='border-top:none;border-left:none'>VENDAS FORA DO ESTADO (RS)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs[0].saida,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Vendas FORA do (RS)</td>
					<td class=xl76 align=right style='border-top:none;border-left:none'>-R$ ${number_format(data.vendasdifrs[0].devolucao,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl72 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS FORA (RS) - Crédito 3%</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs[0].basedifrs,2,',','.')}</td>
					<td class=xl84 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs[0].creditodifrs,2,',','.')}</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 height=20 class=xl114 style='border-right:.5pt solid black;height:15.0pt'>Origem</td>
					<td class=xl86 style='border-top:none;border-left:none'>*Valor da Base</td>
					<td class=xl87 style='border-top:none;border-left:none'>Valor do Crédito</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=3 height=61 class=xl111 width=85 style='border-bottom:.5pt solid black;height:45.75pt;border-top:none;width:64pt'>Crédito 4%</td>
					<td class=xl72 style='border-top:none;border-left:none'>VENDAS FORA DO ESTADO (RS)</td>
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs2[0].saida,2,',','.')}</td>
					<td class=xl84 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td height=20 class=xl85 style='height:15.0pt;border-top:none;border-left:none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( - ) Devoluções de Vendas FORA do (RS)</td>
					<td class=xl76 align=right style='border-top:none;border-left:none'>-R$ ${number_format(data.vendasdifrs2[0].devolucao,2,',','.')}</td>
					<td class=xl91 style='border-top:none;border-left:none'>&nbsp;</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
					<td height=21 class=xl88 style='height:15.75pt;border-top:none;border-left:	none;padding-left: 25px;'><span style='mso-spacerun:yes'></span>( = ) TOTAL FINAL VENDAS FORA (RS) - Crédito 4%</td>
					<td class=xl89 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs2[0].basedifrs2,2,',','.')}</td>
					<td class=xl90 align=right style='border-top:none;border-left:none'>R$ ${number_format(data.vendasdifrs2[0].creditodifrs2,2,',','.')}</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 rowspan=2 height=41 class=xl116 style='border-bottom:1.0pt solid black;height:30.75pt'>TOTAL DE VENDAS FORA DO ESTADO (RS)</td>
					<td rowspan=2 class=xl117 align=right style='border-bottom:1.0pt solid black'>R$ ${number_format((parseFloat(data.vendasdifrs[0].basedifrs) + parseFloat(data.vendasdifrs2[0].basedifrs2)),2,',','.')}</td>
					<td rowspan=2 class=xl118 align=right style='border-bottom:1.0pt solid black'>R$ ${number_format((parseFloat(data.vendasdifrs[0].creditodifrs) + parseFloat(data.vendasdifrs2[0].creditodifrs2)),2,',','.')}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=2 rowspan=2 height=41 class=xl119 style='border-right:.5pt solid black;border-bottom:1.0pt solid black;height:30.75pt'>( = ) TOTAL DE VENDAS</td>
					<td rowspan=2 class=xl122 align=right style='border-bottom:1.0pt solid black'>R$ ${number_format(tot_base_foradentro,2,',','.')}</td>
					<td rowspan=2 class=xl124 align=right style='border-bottom:1.0pt solid black'>R$ ${number_format(tot_credito_dentrofora,2,',','.')}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=21 style='height:15.75pt'>
				<td height=21 class=xl92 width=85 style='height:15.75pt;width:64pt'></td>
				<td class=xl68></td>
				<td class=xl68></td>
				<td class=xl93></td>
				</tr>
				
				<tr height=20 style='height:15.0pt'>
					<td colspan=3 rowspan=3 height=61 class=xl96 style='border-bottom:1.0pt solid black;height:45.75pt'>VALOR FINAL DA APURAÇÃO ${competencia}</td>
					<td class=xl94>VALOR DO CRÉDITO</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td rowspan=2 height=41 class=xl127 align=right style='border-bottom:1.0pt solid black;	height:30.75pt'>R$ ${number_format((parseFloat(data.basecredito[0].credito) + parseFloat(tot_credito_dentrofora)),2,',','.')}</td>
				</tr>

				<tr height=21 style='height:15.75pt'>
				</tr>

				<tr height=20 style='height:15.0pt'>
					<td colspan=4 rowspan=2 height=40 class=xl108 width=697 style='height:30.0pt;width:523pt'>
					(*) Para o Valor da Base é considerado o cálculo da QUANTIDADE
					(x) VALOR UNITÁRIO do produto.<br>
					NÃO são considerados descontos ou acréscimos informados na nota (Ex.:
					ICMS-ST, FRETE, OUTRAS DESPESAS...).</td>
				</tr>

				<tr height=20 style='height:15.0pt'>
				</tr>

				</table>
	
		`;

		return xhtm;			

}

function soNumeros(str) {
    str = str.toString();
    return str.replace(/[^0-9]/g,'');
}	
function daysInMonth(month,year) {
	var dd = new Date(year, month, 0);
	return dd.getDate();
}
function validacao_txt(){

		if($("#datainis").val() == ""){
				$.dialog({
					title: 'Ops!',
					content: 'Falta informar Mês/Ano Competência!',
				});
			return false;
		}

	 
		var dtinis = "01/"+$("#datainis").val();
		var dtfins = daysInMonth($("#datainis").val().split('/')[0],$("#datainis").val().split('/')[1])+'/'+$("#datainis").val();
			
		$.ajax({
			type:'POST',
			cache:false, 
			dataType: "json",
			url:"../php/arquivos-exec.php",
			data: {act:'analizar',dtini:dtinis,dtfim:dtfins},
			beforeSend: function(){
				$("#myModal_pdf").show();
				$(".modal-body_PDF p").html("Aguarde a validação!");
				
			},
			success: function(response){							
				
				$("#myModal_pdf").hide();
				
				var str_erro_notasent = "";
				var str_erro_prodfrig = "";
				var str_erro_emp	  = "";
				var str_erro_notasen1 = "";
				var str_erro_notassai = "";
				var str_erro_notassa1 = "";
				var htminf  		   = "";
				var contadorfunc       = 0;
				var contadorfolha      = 0;
				var contadoricmsnormal = 0;
				var contadoricmsst     = 0;	
				var contadorgta		   = 0;
				
				var valor_entrada = response.info.num_entrada;
				var valor_saida   = response.info.num_saida;
				
				$(".num_entradas").html(valor_entrada);
				$(".num_saida").html(valor_saida);

				$('.validacao_txt').addClass('errovalida_'+response.num_tota_erros+'');
				$('.num_erros').html(response.num_tota_erros);
				
				if(response.num_tota_erros > 0){ // verificando se existe erros
					
					// VERFICANDO ERROS DO ARQUIVO NOTASENT.TXT
					if(response.xerro_notasent.length > 0){ // verificando os erros
						
						str_erro_notasent += "#####################################################<br/>";
						str_erro_notasent += "ARQUIVO NOTASENT.TXT<br/>";
						str_erro_notasent += "NUMEROS DE ERROS : "+response.xerro_notasent.length+"<br/>";															
						str_erro_notasent += "#####################################################<br/>";			
						
						for(i in response.xerro_notasent){	
							
							str_erro_notasent += ""+response.xerro_notasent[i].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_notasent += "#####################################################<br/>";
						str_erro_notasent += "<hr>";
					}else{
						// caso não existe erro mostrar iqual
						str_erro_notasent += "#####################################################<br/>";
						str_erro_notasent += "ARQUIVO NOTASENT.TXT<br/>";
						str_erro_notasent += "NUMEROS DE ERROS : "+response.xerro_notasent.length+"<br/>";															
						str_erro_notasent += "#####################################################<br/>";														
						str_erro_notasent += "*************ARQUIVO NOTASENT.TXT CORRETO************<br/>";															
						str_erro_notasent += "#####################################################<br/>";
						str_erro_notasent += "<hr>";
					}
					
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO NOTASENT.TXT
					
					
					// VERFICANDO ERROS DO ARQUIVO PRODFRIG.TXT
					
					if(response.xerro_prodfrig.length > 0){ // verificando os erros
						
						str_erro_prodfrig += "#####################################################<br/>";
						str_erro_prodfrig += "ARQUIVO PRODFRIG.TXT<br/>";
						str_erro_prodfrig += "NUMEROS DE ERROS : "+response.xerro_prodfrig.length+"<br/>";															
						str_erro_prodfrig += "#####################################################<br/>";			
						
						for(x in response.xerro_prodfrig){	
							
							str_erro_prodfrig += ""+response.xerro_prodfrig[x].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_prodfrig += "#####################################################<br/>";
						str_erro_prodfrig += "<hr>";
					}else{
						
						// caso não existe erro mostrar iqual
						str_erro_prodfrig += "#####################################################<br/>";
						str_erro_prodfrig += "ARQUIVO PRODFRIG.TXT<br/>";
						str_erro_prodfrig += "NUMEROS DE ERROS : "+response.xerro_prodfrig.length+"<br/>";															
						str_erro_prodfrig += "#####################################################<br/>";														
						str_erro_prodfrig += "*************ARQUIVO PRODFRIG.TXT CORRETO************<br/>";															
						str_erro_prodfrig += "#####################################################<br/>";
						str_erro_prodfrig += "<hr>";
					}
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO PRODFRIG.TXT
					
					
					// VERFICANDO ERROS DO ARQUIVO EMPRESAS.TXT
					
					if(response.xerro_emp.length > 0){ // verificando os erros
					
						str_erro_emp += "#####################################################<br/>";
						str_erro_emp += "ARQUIVO EMPRESAS.TXT<br/>";
						str_erro_emp += "NUMEROS DE ERROS : "+response.xerro_emp.length+"<br/>";															
						str_erro_emp += "#####################################################<br/>";
						
						for(e in response.xerro_emp){	
							
							str_erro_emp += ""+response.xerro_emp[e].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_emp += "#####################################################<br/>";
						str_erro_emp += "<hr>";
					}else{
						
						// caso não existe erro mostrar iqual
						str_erro_emp += "#####################################################<br/>";
						str_erro_emp += "ARQUIVO EMPRESAS.TXT<br/>";
						str_erro_emp += "NUMEROS DE ERROS : "+response.xerro_emp.length+"<br/>";															
						str_erro_emp += "#####################################################<br/>";														
						str_erro_emp += "*************ARQUIVO EMPRESAS.TXT CORRETO************<br/>";															
						str_erro_emp += "#####################################################<br/>";
						str_erro_emp += "<hr>";
					
					}
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO EMPRESAS.TXT
					
					
					// VERFICANDO ERROS DO ARQUIVO NOTASEN1.TXT
					
					if(response.xerro_notasen1.length > 0){ // verificando os erros
						
						str_erro_notasen1 += "#####################################################<br/>";
						str_erro_notasen1 += "ARQUIVO NOTASEN1.TXT<br/>";
						str_erro_notasen1 += "NUMEROS DE ERROS : "+response.xerro_notasen1.length+"<br/>";															
						str_erro_notasen1 += "#####################################################<br/>";
						
						for(n in response.xerro_notasen1){	
							
							str_erro_notasen1 += ""+response.xerro_notasen1[n].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_notasen1 += "#####################################################<br/>";
						str_erro_notasen1 += "<hr>";
					}else{
						
						// caso não existe erro mostrar iqual
						str_erro_notasen1 += "#####################################################<br/>";
						str_erro_notasen1 += "ARQUIVO NOTASEN1.TXT<br/>";
						str_erro_notasen1 += "NUMEROS DE ERROS : "+response.xerro_notasen1.length+"<br/>";															
						str_erro_notasen1 += "#####################################################<br/>";														
						str_erro_notasen1 += "*************ARQUIVO NOTASEN1.TXT CORRETO************<br/>";															
						str_erro_notasen1 += "#####################################################<br/>";
						str_erro_notasen1 += "<hr>";
					}
					
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO NOTASEN1.TXT
					
					
					
					// VERFICANDO ERROS DO ARQUIVO NOTASSAI.TXT
					
					if(response.xerro_notassai.length > 0){ // verificando os erros
						
						str_erro_notassai += "#####################################################<br/>";
						str_erro_notassai += "ARQUIVO NOTASSAI.TXT<br/>";
						str_erro_notassai += "NUMEROS DE ERROS : "+response.xerro_notassai.length+"<br/>";															
						str_erro_notassai += "#####################################################<br/>";
						
						for(o in response.xerro_notassai){	
							
							str_erro_notassai += ""+response.xerro_notassai[o].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_notassai += "#####################################################<br/>";
						str_erro_notassai += "<hr>";
					}else{
						
						// caso não existe erro mostrar iqual
						str_erro_notassai += "#####################################################<br/>";
						str_erro_notassai += "ARQUIVO NOTASSAI.TXT<br/>";
						str_erro_notassai += "NUMEROS DE ERROS : "+response.xerro_notassai.length+"<br/>";															
						str_erro_notassai += "#####################################################<br/>";														
						str_erro_notassai += "*************ARQUIVO NOTASSAI.TXT CORRETO************<br/>";															
						str_erro_notassai += "#####################################################<br/>";
						str_erro_notassai += "<hr>";	
					}
					
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO NOTASSAI.TXT
					
					
					// VERFICANDO ERROS DO ARQUIVO NOTASSA1.TXT
					
					if(response.xerro_notassa1.length > 0){ // verificando os erros
						
						str_erro_notassa1 += "#####################################################<br/>";
						str_erro_notassa1 += "ARQUIVO NOTASSA1.TXT<br/>";
						str_erro_notassa1 += "NUMEROS DE ERROS : "+response.xerro_notassa1.length+"<br/>";															
						str_erro_notassa1 += "#####################################################<br/>";
						
						for(t in response.xerro_notassa1){	
							
							str_erro_notassa1 += ""+response.xerro_notassa1[t].msg+" <span class='fa fa-times text-error'></span><br/>";	
							
						}
						str_erro_notassa1 += "#####################################################<br/>";
						str_erro_notassa1 += "<hr>";
					}else{
						
						// caso não existe erro mostrar iqual
						str_erro_notassa1 += "#####################################################<br/>";
						str_erro_notassa1 += "ARQUIVO NOTASSA1.TXT<br/>";
						str_erro_notassa1 += "NUMEROS DE ERROS : "+response.xerro_notassa1.length+"<br/>";															
						str_erro_notassa1 += "#####################################################<br/>";														
						str_erro_notassa1 += "*************ARQUIVO NOTASSA1.TXT CORRETO************<br/>";															
						str_erro_notassa1 += "#####################################################<br/>";
						str_erro_notassa1 += "<hr>";
					}
					
					if(response.info.funcionario.length > 0){
					
						htminf += "<h3>N° de funcionários</h3>";
							
						for(f in response.info.funcionario){
							
							
							htminf += '<li><a href="javascript:void(0);" class="funcionario text-warning" data-id="'+response.info.funcionario[f].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmnumfunc\'><input type=\'hidden\' name=\'act\' value=\'inserirnumfun\'/><input type=\'hidden\' name=\'id\' value=\''+response.info.funcionario[f].id+'\'/><label><strong>Número:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'nfuncionario\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.funcionario[f].msg+'<a/></li>';
							contadorfunc++;
						}
					}	
					
				
					if(response.info.folha.length > 0){
						
						htminf += "<h3>Valor da Folha de Pagamento</h3>";
							
						for(l in response.info.folha){						
							
							htminf += '<li><a href="javascript:void(0);" class="folhavalor text-warning" data-id="'+response.info.folha[l].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmvalorfolha\'><input type=\'hidden\' name=\'act\' value=\'inserirvalorfolha\'/><input type=\'hidden\' name=\'id\' value=\''+response.info.folha[l].id+'\'/><label><strong>Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlpagto\' class=\'form-control vlpagto\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.folha[l].msg+'<a/></li>';
							contadorfolha++;
						}
					}
					
	
					if(response.info.icmsnormal.length > 0){
						htminf += "<h3>Valor ICMS Normal</h3>";	
						for(a in response.info.icmsnormal){	
							htminf += '<li><a href="javascript:void(0);" class="icmsnormal text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmivmsnormal\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsnormal\'/><label><strong>Codigo ICMS NORMAL/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsnormal\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsnormal\' class=\'form-control vlicmsnormal\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.icmsnormal[a].msg+'<a/></li>';
							contadoricmsnormal++;	
						}
					}	
					
					if(response.info.icmsst.length > 0){
						htminf += "<h3>Valor ICMS ST</h3>";		
						for(st in response.info.icmsst){	
							htminf += '<li><a href="javascript:void(0);" class="icmsst text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmicmsst\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsst\'/><label><strong>Codigo ICMS ST/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsst\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsst\' class=\'form-control vlicmsst\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.icmsst[st].msg+'<a/></li>';
							contadoricmsst++;	
						}
					}	
	
	
					if(response.info.gta.length > 0){
						htminf += "<h3>N° de GTA</h3>";
						for(gt in response.info.gta){
							htminf += '<li><a href="javascript:void(0);" data-id="gtanota_'+response.info.gta[gt].codigo+'" onclick="BuscaNotaGta('+response.info.gta[gt].codigo+');" class="text-warning">'+response.info.gta[gt].msg+'</a></li>';	
							contadorgta++;
						}
					}

					var num_info = contadorfunc + contadorfolha + contadoricmsnormal + contadoricmsst + contadorgta;
					// FIM DE VERFICAÇÃO DE ERROS DO ARQUIVO NOTASSA1.TXT
					
					$(".validacao_txt").html(''+str_erro_notasent+' '+str_erro_prodfrig+' '+str_erro_emp+' '+str_erro_notasen1+' '+str_erro_notassai+' '+str_erro_notassa1+'');
					//self.setContent(''+str_erro_notasent+' '+str_erro_prodfrig+' '+str_erro_emp+' '+str_erro_notasen1+' '+str_erro_notassai+' '+str_erro_notassa1+'');	
			
					//return true;
				}else{
					
					if(response.info.funcionario.length > 0){
					
						htminf += "<h3>N° de funcionários</h3>";
							
						for(f in response.info.funcionario){
							
							
							htminf += '<li><a href="javascript:void(0);" class="funcionario text-warning" data-id="'+response.info.funcionario[f].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmnumfunc\'><input type=\'hidden\' name=\'act\' value=\'inserirnumfun\'/><input type=\'hidden\' name=\'id\' value=\''+response.info.funcionario[f].id+'\'/><label><strong>Número:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'nfuncionario\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.funcionario[f].msg+'<a/></li>';
							contadorfunc++;
						}
					}	
					
				
					if(response.info.folha.length > 0){
						
						htminf += "<h3>Valor da Folha de Pagamento</h3>";
							
						for(l in response.info.folha){						
							
							htminf += '<li><a href="javascript:void(0);" class="folhavalor text-warning" data-id="'+response.info.folha[l].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmvalorfolha\'><input type=\'hidden\' name=\'act\' value=\'inserirvalorfolha\'/><input type=\'hidden\' name=\'id\' value=\''+response.info.folha[l].id+'\'/><label><strong>Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlpagto\' class=\'form-control vlpagto\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.folha[l].msg+'<a/></li>';
							contadorfolha++;
						}
					}
					
	
					if(response.info.icmsnormal.length > 0){
						htminf += "<h3>Valor ICMS Normal</h3>";	
						for(a in response.info.icmsnormal){	
							htminf += '<li><a href="javascript:void(0);" class="icmsnormal text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmivmsnormal\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsnormal\'/><label><strong>Codigo ICMS NORMAL/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsnormal\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsnormal\' class=\'form-control vlicmsnormal\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.icmsnormal[a].msg+'<a/></li>';
							contadoricmsnormal++;	
						}
					}	
					
					if(response.info.icmsst.length > 0){
						htminf += "<h3>Valor ICMS ST</h3>";		
						for(st in response.info.icmsst){	
							htminf += '<li><a href="javascript:void(0);" class="icmsst text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmicmsst\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsst\'/><label><strong>Codigo ICMS ST/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsst\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsst\' class=\'form-control vlicmsst\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+response.info.icmsst[st].msg+'<a/></li>';
							contadoricmsst++;	
						}
					}	
	
	
					if(response.info.gta.length > 0){
						htminf += "<h3>N° de GTA</h3>";
						for(gt in response.info.gta){
							htminf += '<li><a href="javascript:void(0);" data-id="gtanota_'+response.info.gta[gt].codigo+'" onclick="BuscaNotaGta('+response.info.gta[gt].codigo+');" class="text-warning">'+response.info.gta[gt].msg+'</a></li>';	
							contadorgta++;
						}
					}
					
					var num_info = contadorfunc + contadorfolha + contadoricmsnormal + contadoricmsst + contadorgta;

					$(".validacao_txt").html('Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/>');	
					//return true;	
					//self.setContent('TUDO CORRETO');								
						
				}

				$(".num_info").html(num_info);
				$("input[id='file_upload2']").val('');
				$(".validacaoarquivos").show();
			},
			error:function(data){	
				$("#myModal_pdf").hide();
				alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
			}		
		});
			
		return false;
	
	//return true;
}

function valida_nota_proxpassoTXT(mesano){
	var returno = 0;
	$.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/arquivos-exec.php",
		data:{act:'valida_novamente',mesano:mesano},
		success: function(data){											
			var contador = 0;	
			var contadorcfop = 0;
			var contadorprod = 0;
			var contadornota = 0;
			var contadorvr	 = 0;
			var contadorfunc = 0;
			var contadorfolha= 0;
			var contadoricmsnormal = 0;
			var contadoricmsst     = 0;	
			var contadorgta		   = 0;
			var contadornotasent   = 0;
			var contadornotasen1   = 0;
			var contadornotassai   = 0;
			var contadornotassai1  = 0;

						
			if(data.xerro_notasent.length > 0){ // verificando os erros																																	
				for(i in data.xerro_notasent){												
					contadornotasent++;
				}

			}
			
			if(data.xerro_notasen1.length > 0){ // verificando os erros														
				for(n in data.xerro_notasen1){												
					contadornotasen1++;
				}
				
			}
			
			if(data.xerro_notassai.length > 0){ // verificando os erros														
				for(o in data.xerro_notassai){													
					contadornotassai++;
				}					
			}

			if(data.xerro_notassa1.length > 0){ // verificando os erros												
				for(t in data.xerro_notassa1){												
					contadornotassai1++;
				}

			}

			if(data.erro.cfop.length > 0){							
				var xcodigo = "";					
				for(y in data.erro.cfop){									
					var codcfop = data.erro.cfop[y].codigo;
					if(codcfop != xcodigo){
						xcodigo = codcfop;							
						contadorcfop++;	
					}

				}
			}
				
			if(data.erro.produto.length > 0){										
				var xcprod = "";
				for(s in data.erro.produto){							
					var cprod = data.erro.produto[s].codigo;												
					if(cprod != xcprod){
						xcprod = cprod;							
						contadorprod++;
					}
											
				}
				
			}
							
			
			if(data.erro.nota.length > 0){
				for(n in data.erro.nota){						
					contadornota++;
				}
			}
						
				
			if(data.erro.vivorendmento.length > 0){					
				for(vr in data.erro.vivorendmento){					
					contadorvr++;
				}
				
			}
						
			var num_erro = contadorcfop + contadorprod + contadorvr + contadornotasent + contadornotasen1 + contadornotassai + contadornotassai1;
			
			returno = num_erro;

		},
		error:function(data){					
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return returno;
	
}

function valida_nota_novamenteTXT(mesano){
	var mesano = mesano;
	
	//var competencia = JSON.parse(localStorage.getItem(mesano) || '[]');

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/arquivos-exec.php",
		data:{act:'valida_novamente',mesano:mesano},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde a validação!");			
		},
		success: function(data){							
			$("#myModal_pdf").hide();
			var htm     = "";									
			var htmerr  = "";
			var htminf  = "";
			var contador = 0;	
			var contadorcfop = 0;
			var contadorprod = 0;
			var contadornota = 0;
			var contadorvr	 = 0;
			var contadorfunc = 0;
			var contadorfolha= 0;
			var contadoricmsnormal = 0;
			var contadoricmsst     = 0;	
			var contadorgta		   = 0;
			var contadornotasent   = 0;
			var contadornotasen1   = 0;
			var contadornotassai   = 0;
			var contadornotassai1  = 0;
			var contadtabate = 0;
			var containfdtabate = 0;
			var containfoempresa = 0;
			var valor_entrada = data.info.num_entrada;
			var valor_saida   = data.info.num_saida;
			
			$(".num_entradas").html(valor_entrada);
			$(".num_saida").html(valor_saida);
			//competencia.push(data);
			for(i in data.dados_grid){

				htm += '<tr id="'+data.dados_grid[i].Numero+'" data-caminho="'+data.dados_grid[i].caminho+'" class="warning">'+
						  '<td>'+data.dados_grid[i].Numero+'</td>'+
						  '<td>'+data.dados_grid[i].dEmi+'</td>'+
						  '<td style="width: 33%">'+data.dados_grid[i].chave+'</td>'+
						  '<td style="text-align:center; width: 7%;">'+data.dados_grid[i].entsai+'</td>'+
						  '<td>'+data.dados_grid[i].cliente+'</td>'+
						  '<td style="text-align:right; width: 10%;">'+data.dados_grid[i].valor+'</td>'+
						  '<td  class="centeralign style="text-align: right">'+									
							'<a href="#" onclick="BuscaNota('+data.dados_grid[i].Numero+');" ><span class="fa fa-pencil fa-2x"></span></a>'+
							'<a href="#" class="detailsgta"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';

			}
			
			htmerr  += "<ol>";	
			htminf  += "<ol>";
			
				if(data.xerro_notasent.length > 0){ // verificando os erros
																													
					htmerr += "<h3>NOTAS DE ENTRADA (NOTASENT.TXT)</h3>";
					for(i in data.xerro_notasent){	
						
						htmerr += "<li>"+data.xerro_notasent[i].msg+"</li>";	
						contadornotasent++;
												
					}

				}
				
				if(data.xerro_notasen1.length > 0){ // verificando os erros
										
					htmerr += "<h3>NOTAS ENTRADA DETALHE (NOTASEN1.TXT)</h3>";
					for(n in data.xerro_notasen1){	
						
						htmerr += '<li><a href="javascript:void(0);" onclick="BuscaNota(\''+data.xerro_notasen1[n].codigo+'\',\''+data.xerro_notasen1[n].entsai+'\');">'+data.xerro_notasen1[n].msg+'</a></li>';	
						contadornotasen1++;
					}
					
				}
				

				if(data.xerro_notassai.length > 0){ // verificando os erros
										
					htmerr += "<h3>NOTAS DE SAIDA (NOTASSAI.TXT)</h3>";

					for(o in data.xerro_notassai){							
						htmerr += "<li>"+data.xerro_notassai[o].msg+"</li>";
						contadornotassai++;
					}					
				}

				if(data.xerro_notassa1.length > 0){ // verificando os erros
								
					htmerr += "<h3>NOTAS DE SAIDA DETALHE (NOTASSA1.TXT)</h3>";
					for(t in data.xerro_notassa1){	
						
						htmerr += "<li>"+data.xerro_notassa1[t].msg+"</li>";	
						contadornotassai1++;
					}

				}
				var optcity = "";
	
				if(data.erro.cfop.length > 0){	
					htmerr += "<div id='validacfop'>";	
					htmerr += "<h3>CFOP</h3>";
					var xcodigo = "";
					
					for(y in data.erro.cfop){									
						var codcfop = data.erro.cfop[y].codigo;

						if(codcfop != xcodigo){
							xcodigo = codcfop;

							htmerr += "<li><a href='javascript:void(0);' class='geraagregarvalid2 text-danger' id='valid_"+codcfop+"' data-xml='"+data.erro.cfop[y].nota+"|"+data.erro.cfop[y].arquivo+"' data-id='"+data.erro.cfop[y].idvinc+"'>"+data.erro.cfop[y].msg+"</a></li>";
							contadorcfop++;	
						}

					}
					htmerr += "</div>";
					optcity = "style='pointer-events: none;opacity: 0.4;'";
				}
				var optcity2 = "";	
				if(data.erro.produto.length > 0){

					var xprod = ListaProdutoAgregar();

					htmerr += "<div id='validaprodutos' "+optcity+">";
					htmerr += "<div class='row'><h3 class='col-sm-3'>Relacionamento Produtos</h3>";
					htmerr += `
							<div class="col-sm-3">
								<div class="form-check">
									<label class="custom-control custom-radio">
										<input id="radiomultiplos" name="radiomultiplos" type="checkbox" class="custom-control-input">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">Relacionar múltiplos produtos</span>
									</label>						
								</div>
							</div>
											
					`;
					var ht = "";
					for (let index = 0; index < xprod.length; index++) {
						const element = xprod[index];
						ht +='<option value="'+element.id+'">'+element.text+'</option>';
					}							

					htmerr +='<div class="col-sm-4 ckmultiplos">'+
									'<select class="m-b-10 select2-multiple" style="width:100%;">'+								
										''+ht+''+
									'</select>'+								
								'</div>'+
								'<div class="col-sm-1 ckmultiplos">'+
									'<button class="btn btn-sm btn-primary btnrelacionamult" data-type="txt">Relacionar</button>'+
								'</div>'+
						   	'</div>';

					var xcprod = "";
					for(s in data.erro.produto){
							
						var cprod = data.erro.produto[s].codigo;
						var htmlMessage= '[{\'nnota\':\''+data.erro.produto[s].dados['nNF']+'\',\'entsai\':\''+data.erro.produto[s].dados['entsai']+'\',\'cliente\':\''+data.erro.produto[s].dados['cliente']+'\',\'valor\':\''+data.erro.produto[s].dados['valor']+'\',\'demi\':\''+data.erro.produto[s].dados['demi']+'\'}]';
						//console.log(htmlMessage.toString().replace('"',"'"));

						var rdprod = `
						<label class="custom-control custom-radio radioprod">
							<input name="radioprod[]" type="checkbox" value="${cprod}" class="radioprods custom-control-input">
							<span class="custom-control-indicator"></span>							
						</label>
						`;	

						if(cprod != xcprod){
							xcprod = cprod;
							htmerr += '<li data-content=\"'+htmlMessage+'\">'+rdprod+'<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+cprod+'|0" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a> <a href="javascript:void(0);" onclick="BuscaNota(\''+data.erro.produto[s].cnota+'\',\''+data.erro.produto[s].dados['entsai']+'\');" class="text-danger" data-id="relprod_'+cprod+'" data-placement="right" data-html="true"  title="" data-original-title="Relacionar">'+data.erro.produto[s].msg+'</a></li>';
							contadorprod++;
						}
												
					}
					htmerr += "</div>";
					optcity2 = "style='pointer-events: none;opacity: 0.4;'";
				}
								
				var optcity3 = "";
				if(data.info.nota.length > 0){
					htminf += "<div id='validanumerocabeca' "+optcity2+">";
					htminf += "<h3>N° De Cabeças</h3>";
					htminf += '<div class="row"><div class="col-md-7">'+
								'<label>Selecione o arquivo:</label>'+
									'<i class="help-block"><small>Obs: Fazer download de planilha modelo para informações de cabeças.</small></i>'+
										'<label class="custom-file">'+				
										'<input type="file" id="filearqcabeca" name="filearqcabeca" class="custom-file-input" aria-describedby="fileHelp" accept=".xlsx">'+
										'<span class="custom-file-control"></span>'+								
									'</label>'+
									'<div style="display:inline-grid;margin-left: 14px;"><a href="#" class="btn btn-primary waves-effect waves-light downloadexcelqtd"><span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Baixar Planilha</a></div>'+
								'</div></div>';
								htminf += "<hr>";	
					for(n in data.info.nota){
						htminf += '<li><a href="javascript:void(0);" data-id="nota_'+data.info.nota[n].codigo+'_'+data.info.nota[n].cProd+'_'+data.info.nota[n].idseq+'" onclick="BuscaNota(\''+data.info.nota[n].codigo+'\',\''+data.info.nota[n].entsai+'\');" class="text-danger">'+data.info.nota[n].msg+'</a><a href="javascript:void(0);" style="border-bottom: dashed 1px #0088cc;" class="clickcabecas" data-cabeca="'+data.info.nota[n].codigo+'|'+data.info.nota[n].cProd+'|'+data.info.nota[n].idseq+'"  data-placement="top" data-html="true" data-content="<div class=\'form_callback\'></div><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmcabecas\'><input type=\'hidden\' name=\'act\' value=\'updatecabecas\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.info.nota[n].idseq+'\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.info.nota[n].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.info.nota[n].cProd+'\'/><label><strong>Cabeças:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'ncabecas\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>" title="" data-original-title="Informar Número de Cabeças"><strong>CLIQUE AQUI PARA INFORMAR NÚMERO DE CABEÇAS</strong></a></li>';	
						contadornota++;
					}
					htminf += "</div>";
					optcity3 = "style='pointer-events: none;opacity: 0.4;'";
				}
					
				
				var optcity4 = "";	
				if(data.erro.vivorendmento.length > 0){
					htmerr += "<div id='validavivorendimento' "+optcity3+">";
					htmerr += "<h3>Vivo/Rendimento</h3>";
					for(vr in data.erro.vivorendmento){
						htmerr += '<li><a href="javascript:void(0);" data-id="notavr_'+data.erro.vivorendmento[vr].codigo+'_'+data.erro.vivorendmento[vr].cProd+'" onclick="BuscaNota(\''+data.erro.vivorendmento[vr].codigo+'\',\''+data.erro.vivorendmento[vr].entsai+'\');" class="text-danger vivorendvalid">'+data.erro.vivorendmento[vr].msg+'</a><i class="fa fa-dedent" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmvivorend\'><input type=\'hidden\' name=\'act\' value=\'updatevivorend\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.vivorendmento[vr].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.vivorendmento[vr].cProd+'\'/><input type=\'hidden\' name=\'qcom\' value=\''+data.erro.vivorendmento[vr].qCom+'\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.vivorendmento[vr].idseq+'\'/><label><strong>Vivo/Rendimento:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><select name=\'vivorend\' class=\'xvivorend\'><option value=\'\'>Selecionar</option><option value=\'V\'>Vivo</option><option value=\'R\'>Rendimento</option></select><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div><label><strong>Peso Carcaça:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesocarcaca\' class=\'form-control\' disabled/></div><label><strong>Peso Vivo:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesovivo\' class=\'form-control\' disabled/></div></form>" title="" data-original-title="Informar Vivo/Rendimento"></i></li>';
						contadorvr++;
					}
					htmerr += "</div>";
					optcity4 = "style='pointer-events: none;opacity: 0.4;'";
				}
				
				if(data.erro.abate.length > 0){
					var xnota = "";
					htmerr += "<div id='validadataabate' "+optcity4+">";
					htmerr += "<h3>Data de Abate</h3><br/>";
					htmerr += "<div class='text-danger'>Documento sem data de abate Informada<div/>";
					for(abt in data.erro.abate){

						if(data.erro.abate[abt].numero_nota != xnota){
							xnota = data.erro.abate[abt].numero_nota;
							htmerr += '<div class="text-danger">Número documento: '+data.erro.abate[abt].numero_nota+'</div>';
						}

						htmerr += '<li style="margin-left: 62px;">'+
									'<a href="javascript:void(0);" data-id="notadtabate_'+data.erro.abate[abt].codigo+'_'+data.erro.abate[abt].cProd+'" onclick="BuscaNota(\''+data.erro.abate[abt].codigo+'\',\''+data.erro.abate[abt].entsai+'\');" class="text-primary dtabate">'+
										'Produto: '+data.erro.abate[abt].dProd+''+
									'</a>'
								  +'</li>';

						contadtabate++;
					}

					htmerr += "</div>";										
				}


				if(data.info.infabate.length > 0){
					var xcnota = "";
					htminf += "<h3>Data de Abate</h3>";
					htminf += "<div class='text-danger'>Data informada fora da competência em andamento<div/>";
					for(infabt in data.info.infabate){

						if(data.info.infabate[infabt].codigo != xcnota){
							xcnota = data.info.infabate[infabt].codigo;
							htminf += '<div class="text-danger">Número do documento: '+data.info.infabate[infabt].codigo+'</div>';	
						}

						htminf += '<li style="margin-left: 62px;">'+
									'<a href="javascript:void(0);" data-id="notainfdtabate_'+data.info.infabate[infabt].codigo+'_'+data.info.infabate[infabt].cProd+'" onclick="BuscaNota(\''+data.info.infabate[infabt].codigo+'\',\''+data.info.infabate[infabt].entsai+'\');" class="text-danger dtabate">'+
										'Produto: '+data.info.infabate[infabt].dProd+' <br> Competência em andamento: '+data.info.infabate[infabt].data_emissao+' <> Data informada: '+data.info.infabate[infabt].data_abate+''+
									'</a>'
								   +'</li>';	
					
						containfdtabate++;
					}

				}	

				if(data.info.empresa.length > 0){
					
					htminf += "<h3>Cadastro de empresa</h3>";
						
					for(em in data.info.empresa){
												
						htminf += '<li><a href="javascript:void(0);" class="altempresa text-warning" data-id="'+data.info.empresa[em].id+'" >'+data.info.empresa[em].msg+'<a/></li>';
						containfoempresa++;
					}
				}

				if(data.info.funcionario.length > 0){
					
					htminf += "<h3>N° de funcionários</h3>";
						
					for(f in data.info.funcionario){
						
						
						htminf += '<li><a href="javascript:void(0);" class="funcionario boxmodal text-warning" data-id="'+data.info.funcionario[f].id+'"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'folha-exec.php\' id=\'frmnumfunc\'><input type=\'hidden\' name=\'act\' value=\'inserirnumfun\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.funcionario[f].id+'\'/><label><strong>Número:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'nfuncionario\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.funcionario[f].msg+'<a/></li>';
						contadorfunc++;
					}
				}	
				
			
				if(data.info.folha.length > 0){
					
					htminf += "<h3>Valor da Folha de Pagamento</h3>";
						
					for(l in data.info.folha){						
						
						htminf += '<li><a href="javascript:void(0);" class="folhavalor boxmodal text-warning" data-id="'+data.info.folha[l].id+'" data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'folha-exec.php\' id=\'frmvalorfolha\'><input type=\'hidden\' name=\'act\' value=\'inserirvalorfolha\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.folha[l].id+'\'/><label><strong>Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlpagto\' class=\'form-control vlpagto\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.folha[l].msg+'<a/></li>';
						contadorfolha++;
					}
				}
				

				if(data.info.icmsnormal.length > 0){
					htminf += "<h3>Valor ICMS Normal</h3>";	
					for(a in data.info.icmsnormal){	
						htminf += '<li><a href="javascript:void(0);" class="icmsnormal boxmodal text-warning"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmivmsnormal\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsnormal\'/><label><strong>Codigo ICMS NORMAL/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsnormal\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsnormal\' class=\'form-control vlicmsnormal\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsnormal[a].msg+'<a/></li>';
						contadoricmsnormal++;	
					}
				}	
				
				if(data.info.icmsst.length > 0){
					htminf += "<h3>Valor ICMS ST</h3>";		
					for(st in data.info.icmsst){	
						htminf += '<li><a href="javascript:void(0);" class="icmsst boxmodal text-warning"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmicmsst\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsst\'/><label><strong>Codigo ICMS ST/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsst\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsst\' class=\'form-control vlicmsst\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsst[st].msg+'<a/></li>';
						contadoricmsst++;	
					}
				}	


				if(data.info.gta.length > 0){
					htminf += "<h3>N° de GTA</h3>";
					for(gt in data.info.gta){
						htminf += '<li><a href="javascript:void(0);" data-id="gtanota_'+data.info.gta[gt].codigo+'" onclick="BuscaNotaGta('+data.info.gta[gt].codigo+');" class="text-warning">'+data.info.gta[gt].msg+'</a></li>';	
						contadorgta++;
					}
				}	

				
			htmerr  += "</ol>";
			htminf  += "</ol>";
			
			var num_erro = contadorcfop + contadorprod +  contadorvr + contadornotasent + contadornotasen1 + contadornotassai + contadornotassai1 + contadtabate;
			var num_info = contadorfunc + contadorfolha + contadornota + contadoricmsnormal + contadoricmsst + contadorgta + containfdtabate + containfoempresa;
			
			if(num_erro == 0){
				$(".validacao_txt").html('Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/>');	
				htmerr  += '<div class="text-center">Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/></div>';
			}else{
				$(".validacao_txt").html('Os seus arquivos contem alguns erros, rever!<br/><img src="../images/alert.png"/>');	
			}
			
			$('.num_erros').html(num_erro);	
			$(".num_info").html(num_info);
			$('input[name="numeroerros"]').val(num_erro);

			$(".valid_erros").html(htmerr);
			$(".valid_infos").html(htminf);
			
			$(".validacaoarquivos").show();
			
			$("input[id='file_upload2']").val('');
			
			ListaNotasdesaida();
			ListaNotasdesentrada();

			$('.editprod').editable({
				source: ListaProdutoAgregar(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
		            placeholder: 'Selecione um produto agregar',			            			            			       
			    },
			    mode: 'inline',
			    emptytext: 'Vazio',
			    ajaxOptions: { dataType: 'json' },
			    success: function(response, newValue) {
			    	 $("a[data-id='relprod_"+response[0].idprod+"']").append('<i class="fa fa-check text-success"></i>');
					 $("a[data-id='relprod_"+response[0].idprod+"']").removeClass('text-danger');
					 $("a[data-id='relprod_"+response[0].idprod+"']").addClass("text-success");

					 if($("#validaprodutos li a[class='text-danger']").length == 0){
						valida_nota_novamenteTXT(mesano); 
					 }
					/* var iset = setInterval(function(){
						clearInterval(iset);
						valida_nota_novamenteTXT(mesano);						
					 },600);*/
				}
			});

			
			$('.select2-multiple').select2({	
				"language": "pt-BR",											
				mode: 'inline',
				emptytext: 'Vazio',		
				placeholder: 'Selecione um produto agregar',				
			});

			$('.editprod').on('shown', function(e, editable) {
    
				$(document).on('change', editable, function(e) {
					/*var conf = confirm('Deseja realmente relacionar esse produto ?');
					if(conf == true){
						$('form[class="form-inline editableform"]').submit();
						
					}		*/

					/*$('.editable-submit').css({
						'background':'#000'
					});*/

					var btns = '<input type="submit" value="Relacionar ?" class="btn btn-info btn-sm editable-submit fa fa-check" style="float: left;"/>';
					$('.editable-submit').remove();
					
					$(".editable-buttons").append(btns);

					var st = setInterval(function(){
						$('.editable-submit').focus();
						clearInterval(st);
					},300)
					

					return false;
					//console.log(e);
					//alert('aqui')
					//$('.editable-submit').select();
					//$('button[type="submit"]')[0].focus();
					//$('.editable-submit').click();
				});
			});
			
		},
		error:function(data){	
			$("#myModal_pdf").hide();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
}

function valida_nota_novamenteTXTSession(mesano){
	var mesano = mesano;
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/arquivos-exec.php",
		data:{act:'valida_novamente_session',mesano:mesano},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde a validação!");			
		},
		success: function(data){							
			$("#myModal_pdf").hide();
			var htm     = "";									
			var htmerr  = "";
			var htminf  = "";
			var contador = 0;	
			var contadorcfop = 0;
			var contadorprod = 0;
			var contadornota = 0;
			var contadorvr	 = 0;
			var contadorfunc = 0;
			var contadorfolha= 0;
			var contadoricmsnormal = 0;
			var contadoricmsst     = 0;	
			var contadorgta		   = 0;
			var contadornotasent   = 0;
			var contadornotasen1   = 0;
			var contadornotassai   = 0;
			var contadornotassai1  = 0;
			var contadtabate = 0;
			var containfdtabate = 0;
			var containfoempresa = 0;
			var valor_entrada = data.info.num_entrada;
			var valor_saida   = data.info.num_saida;
			
			$(".num_entradas").html(valor_entrada);
			$(".num_saida").html(valor_saida);
			
			for(i in data.dados_grid){

				htm += '<tr id="'+data.dados_grid[i].Numero+'" data-caminho="'+data.dados_grid[i].caminho+'" class="warning">'+
						  '<td>'+data.dados_grid[i].Numero+'</td>'+
						  '<td>'+data.dados_grid[i].dEmi+'</td>'+
						  '<td style="width: 33%">'+data.dados_grid[i].chave+'</td>'+
						  '<td style="text-align:center; width: 7%;">'+data.dados_grid[i].entsai+'</td>'+
						  '<td>'+data.dados_grid[i].cliente+'</td>'+
						  '<td style="text-align:right; width: 10%;">'+data.dados_grid[i].valor+'</td>'+
						  '<td  class="centeralign style="text-align: right">'+									
							'<a href="#" onclick="BuscaNota('+data.dados_grid[i].Numero+');" ><span class="fa fa-pencil fa-2x"></span></a>'+
							'<a href="#" class="detailsgta"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';

			}
			
			htmerr  += "<ol>";	
			htminf  += "<ol>";
			
				if(data.xerro_notasent.length > 0){ // verificando os erros
																													
					htmerr += "<h3>NOTAS DE ENTRADA (NOTASENT.TXT)</h3>";
					for(i in data.xerro_notasent){	
						
						htmerr += "<li>"+data.xerro_notasent[i].msg+"</li>";	
						contadornotasent++;
					}

				}
				
				if(data.xerro_notasen1.length > 0){ // verificando os erros
										
					htmerr += "<h3>NOTAS ENTRADA DETALHE (NOTASEN1.TXT)</h3>";
					for(n in data.xerro_notasen1){	
						
						htmerr += '<li><a href="javascript:void(0);" onclick="BuscaNota(\''+data.xerro_notasen1[n].codigo+'\',\''+data.xerro_notasen1[n].entsai+'\');">'+data.xerro_notasen1[n].msg+'</a></li>';	
						contadornotasen1++;
					}
					
				}
				

				if(data.xerro_notassai.length > 0){ // verificando os erros
										
					htmerr += "<h3>NOTAS DE SAIDA (NOTASSAI.TXT)</h3>";

					for(o in data.xerro_notassai){							
						htmerr += "<li>"+data.xerro_notassai[o].msg+"</li>";
						contadornotassai++;
					}					
				}

				if(data.xerro_notassa1.length > 0){ // verificando os erros
								
					htmerr += "<h3>NOTAS DE SAIDA DETALHE (NOTASSA1.TXT)</h3>";
					for(t in data.xerro_notassa1){	
						
						htmerr += "<li>"+data.xerro_notassa1[t].msg+"</li>";	
						contadornotassai1++;
					}

				}
				var optcity = "";
	
				if(data.erro.cfop.length > 0){	
					htmerr += "<div id='validacfop'>";	
					htmerr += "<h3>CFOP</h3>";
					var xcodigo = "";
					
					for(y in data.erro.cfop){									
						var codcfop = data.erro.cfop[y].codigo;

						if(codcfop != xcodigo){
							xcodigo = codcfop;

							htmerr += "<li><a href='javascript:void(0);' class='geraagregarvalid2 text-danger' id='valid_"+codcfop+"' data-xml='"+data.erro.cfop[y].nota+"|"+data.erro.cfop[y].arquivo+"' data-id='"+data.erro.cfop[y].idvinc+"'>"+data.erro.cfop[y].msg+"</a></li>";
							contadorcfop++;	
						}

					}
					htmerr += "</div>";
					optcity = "style='pointer-events: none;opacity: 0.4;'";
				}
				var optcity2 = "";	
				if(data.erro.produto.length > 0){

					var xprod = ListaProdutoAgregar();

					htmerr += "<div id='validaprodutos' "+optcity+">";
					htmerr += "<div class='row'><h3 class='col-sm-3'>Relacionamento Produtos</h3>";
					htmerr += `
							<div class="col-sm-3">
								<div class="form-check">
									<label class="custom-control custom-radio">
										<input id="radiomultiplos" name="radiomultiplos" type="checkbox" class="custom-control-input">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">Relacionar múltiplos produtos</span>
									</label>						
								</div>
							</div>
											
					`;
					var ht = "";
					for (let index = 0; index < xprod.length; index++) {
						const element = xprod[index];
						ht +='<option value="'+element.id+'">'+element.text+'</option>';
					}							

					htmerr +='<div class="col-sm-4 ckmultiplos">'+
									'<select class="m-b-10 select2-multiple" style="width:100%;">'+								
										''+ht+''+
									'</select>'+								
								'</div>'+
								'<div class="col-sm-1 ckmultiplos">'+
									'<button class="btn btn-sm btn-primary btnrelacionamult" data-type="txt">Relacionar</button>'+
								'</div>'+
						   	'</div>';

					var xcprod = "";
					for(s in data.erro.produto){
							
						var cprod = data.erro.produto[s].codigo;
						var htmlMessage= '[{\'nnota\':\''+data.erro.produto[s].dados['nNF']+'\',\'entsai\':\''+data.erro.produto[s].dados['entsai']+'\',\'cliente\':\''+data.erro.produto[s].dados['cliente']+'\',\'valor\':\''+data.erro.produto[s].dados['valor']+'\',\'demi\':\''+data.erro.produto[s].dados['demi']+'\'}]';
						//console.log(htmlMessage.toString().replace('"',"'"));

						var rdprod = `
						<label class="custom-control custom-radio radioprod">
							<input name="radioprod[]" type="checkbox" value="${cprod}" class="radioprods custom-control-input">
							<span class="custom-control-indicator"></span>							
						</label>
						`;	

						if(cprod != xcprod){
							xcprod = cprod;
							htmerr += '<li data-content=\"'+htmlMessage+'\">'+rdprod+'<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+cprod+'|0" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a> <a href="javascript:void(0);" onclick="BuscaNota(\''+data.erro.produto[s].cnota+'\',\''+data.erro.produto[s].dados['entsai']+'\');" class="text-danger" data-id="relprod_'+cprod+'" data-placement="right" data-html="true"  title="" data-original-title="Relacionar">'+data.erro.produto[s].msg+'</a></li>';
							contadorprod++;
						}
												
					}
					htmerr += "</div>";
					optcity2 = "style='pointer-events: none;opacity: 0.4;'";
				}
								
				var optcity3 = "";
				if(data.info.nota.length > 0){
					htminf += "<div id='validanumerocabeca'>";
					htminf += "<h3>N° De Cabeças</h3>";
					htminf += '<div class="row"><div class="col-md-7">'+
								'<label>Selecione o arquivo:</label>'+
									'<i class="help-block"><small>Obs: Fazer download de planilha modelo para informações de cabeças.</small></i>'+
										'<label class="custom-file">'+				
										'<input type="file" id="filearqcabeca" name="filearqcabeca" class="custom-file-input" aria-describedby="fileHelp" accept=".xlsx">'+
										'<span class="custom-file-control"></span>'+								
									'</label>'+
									'<div style="display:inline-grid;margin-left: 14px;"><a href="#" class="btn btn-primary waves-effect waves-light downloadexcelqtd"><span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Baixar Planilha</a></div>'+
								'</div></div>';
								htminf += "<hr>";	
					for(n in data.info.nota){
						htminf += '<li><a href="javascript:void(0);" data-id="nota_'+data.info.nota[n].codigo+'_'+data.info.nota[n].cProd+'_'+data.info.nota[n].idseq+'" onclick="BuscaNota(\''+data.info.nota[n].codigo+'\',\''+data.info.nota[n].entsai+'\');" class="text-danger">'+data.info.nota[n].msg+'</a><a href="javascript:void(0);" style="border-bottom: dashed 1px #0088cc;" class="clickcabecas" data-cabeca="'+data.info.nota[n].codigo+'|'+data.info.nota[n].cProd+'|'+data.info.nota[n].idseq+'"  data-placement="top" data-html="true" data-content="<div class=\'form_callback\'></div><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmcabecas\'><input type=\'hidden\' name=\'act\' value=\'updatecabecas\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.info.nota[n].idseq+'\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.info.nota[n].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.info.nota[n].cProd+'\'/><label><strong>Cabeças:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'ncabecas\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>" title="" data-original-title="Informar Número de Cabeças"><strong>CLIQUE AQUI PARA INFORMAR NÚMERO DE CABEÇAS</strong></a></li>';	
						contadornota++;
					}
					htminf += "</div>";
					optcity3 = "style='pointer-events: none;opacity: 0.4;'";
				}
					
				
				var optcity4 = "";	
				if(data.erro.vivorendmento.length > 0){
					htmerr += "<div id='validavivorendimento' "+optcity3+">";
					htmerr += "<h3>Vivo/Rendimento</h3>";
					for(vr in data.erro.vivorendmento){
						htmerr += '<li><a href="javascript:void(0);" data-id="notavr_'+data.erro.vivorendmento[vr].codigo+'_'+data.erro.vivorendmento[vr].cProd+'" onclick="BuscaNota(\''+data.erro.vivorendmento[vr].codigo+'\',\''+data.erro.vivorendmento[vr].entsai+'\');" class="text-danger vivorendvalid">'+data.erro.vivorendmento[vr].msg+'</a><i class="fa fa-dedent boxmodal" data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmvivorend\'><input type=\'hidden\' name=\'act\' value=\'updatevivorend\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.vivorendmento[vr].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.vivorendmento[vr].cProd+'\'/><input type=\'hidden\' name=\'qcom\' value=\''+data.erro.vivorendmento[vr].qCom+'\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.vivorendmento[vr].idseq+'\'/><label><strong>Vivo/Rendimento:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><select name=\'vivorend\' class=\'xvivorend\'><option value=\'\'>Selecionar</option><option value=\'V\'>Vivo</option><option value=\'R\'>Rendimento</option></select><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div><label><strong>Peso Carcaça:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesocarcaca\' class=\'form-control\'/></div><label><strong>Peso Vivo:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesovivo\' class=\'form-control\' /></div></form>" title="" data-original-title="Informar Vivo/Rendimento"></i></li>';
						contadorvr++;
					}
					htmerr += "</div>";
					optcity4 = "style='pointer-events: none;opacity: 0.4;'";
				}
				
				if(data.erro.abate.length > 0){
					var xnota = "";
					htmerr += "<div id='validadataabate' "+optcity4+">";
					htmerr += "<h3>Data de Abate</h3><br/>";
					htmerr += "<div class='text-danger'>Documento sem data de abate Informada<div/>";
					for(abt in data.erro.abate){

						if(data.erro.abate[abt].numero_nota != xnota){
							xnota = data.erro.abate[abt].numero_nota;
							htmerr += '<div class="text-danger">Número documento: '+data.erro.abate[abt].numero_nota+'</div>';
						}

						htmerr += '<li style="margin-left: 62px;">'+
									'<a href="javascript:void(0);" data-id="notadtabate_'+data.erro.abate[abt].codigo+'_'+data.erro.abate[abt].cProd+'" onclick="BuscaNota(\''+data.erro.abate[abt].codigo+'\',\''+data.erro.abate[abt].entsai+'\');" class="text-primary dtabate">'+
										'Produto: '+data.erro.abate[abt].dProd+''+
									'</a>'
								  +'</li>';

						contadtabate++;
					}

					htmerr += "</div>";										
				}


				if(data.info.infabate.length > 0){
					var xcnota = "";
					htminf += "<h3>Data de Abate</h3>";
					htminf += "<div class='text-danger'>Data informada fora da competência em andamento<div/>";
					for(infabt in data.info.infabate){

						if(data.info.infabate[infabt].codigo != xcnota){
							xcnota = data.info.infabate[infabt].codigo;
							htminf += '<div class="text-danger">Número do documento: '+data.info.infabate[infabt].codigo+'</div>';	
						}

						htminf += '<li style="margin-left: 62px;">'+
									'<a href="javascript:void(0);" data-id="notainfdtabate_'+data.info.infabate[infabt].codigo+'_'+data.info.infabate[infabt].cProd+'" onclick="BuscaNota(\''+data.info.infabate[infabt].codigo+'\',\''+data.info.infabate[infabt].entsai+'\');" class="text-danger dtabate">'+
										'Produto: '+data.info.infabate[infabt].dProd+' <br> Competência em andamento: '+data.info.infabate[infabt].data_emissao+' <> Data informada: '+data.info.infabate[infabt].data_abate+''+
									'</a>'
								   +'</li>';	
					
						containfdtabate++;
					}

				}	

				if(data.info.empresa.length > 0){
					
					htminf += "<h3>Cadastro de empresa</h3>";
						
					for(em in data.info.empresa){
												
						htminf += '<li><a href="javascript:void(0);" class="altempresa boxmodal text-warning" data-id="'+data.info.empresa[em].id+'" >'+data.info.empresa[em].msg+'<a/></li>';
						containfoempresa++;
					}
				}

				if(data.info.funcionario.length > 0){
					
					htminf += "<h3>N° de funcionários</h3>";
						
					for(f in data.info.funcionario){
						
						
						htminf += '<li><a href="javascript:void(0);" class="funcionario boxmodal text-warning" data-id="'+data.info.funcionario[f].id+'"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'folha-exec.php\' id=\'frmnumfunc\'><input type=\'hidden\' name=\'act\' value=\'inserirnumfun\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.funcionario[f].id+'\'/><label><strong>Número:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'nfuncionario\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.funcionario[f].msg+'<a/></li>';
						contadorfunc++;
					}
				}	
				
			
				if(data.info.folha.length > 0){
					
					htminf += "<h3>Valor da Folha de Pagamento</h3>";
						
					for(l in data.info.folha){						
						
						htminf += '<li><a href="javascript:void(0);" class="folhavalor boxmodal text-warning" data-id="'+data.info.folha[l].id+'"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'folha-exec.php\' id=\'frmvalorfolha\'><input type=\'hidden\' name=\'act\' value=\'inserirvalorfolha\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.folha[l].id+'\'/><label><strong>Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlpagto\' class=\'form-control vlpagto\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.folha[l].msg+'<a/></li>';
						contadorfolha++;
					}
				}
				

				if(data.info.icmsnormal.length > 0){
					htminf += "<h3>Valor ICMS Normal</h3>";	
					for(a in data.info.icmsnormal){	
						htminf += '<li><a href="javascript:void(0);" class="icmsnormal boxmodal text-warning" data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmivmsnormal\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsnormal\'/><label><strong>Codigo ICMS NORMAL/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsnormal\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsnormal\' class=\'form-control vlicmsnormal\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsnormal[a].msg+'<a/></li>';
						contadoricmsnormal++;	
					}
				}	
				
				if(data.info.icmsst.length > 0){
					htminf += "<h3>Valor ICMS ST</h3>";		
					for(st in data.info.icmsst){	
						htminf += '<li><a href="javascript:void(0);" class="icmsst boxmodal text-warning"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmicmsst\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsst\'/><label><strong>Codigo ICMS ST/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsst\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsst\' class=\'form-control vlicmsst\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsst[st].msg+'<a/></li>';
						contadoricmsst++;	
					}
				}	


				if(data.info.gta.length > 0){
					htminf += "<h3>N° de GTA</h3>";
					for(gt in data.info.gta){
						htminf += '<li><a href="javascript:void(0);" data-id="gtanota_'+data.info.gta[gt].codigo+'" onclick="BuscaNotaGta('+data.info.gta[gt].codigo+');" class="text-warning">'+data.info.gta[gt].msg+'</a></li>';	
						contadorgta++;
					}
				}	

				
			htmerr  += "</ol>";
			htminf  += "</ol>";
			
			var num_erro = contadorcfop + contadorprod +  contadorvr + contadornotasent + contadornotasen1 + contadornotassai + contadornotassai1 + contadtabate;
			var num_info = contadorfunc + contadorfolha + contadornota + contadoricmsnormal + contadoricmsst + contadorgta + containfdtabate + containfoempresa;
			
			if(num_erro == 0){
				$(".validacao_txt").html('Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/>');	
				htmerr  += '<div class="text-center">Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/></div>';
			}else{
				$(".validacao_txt").html('Os seus arquivos contem alguns erros, rever!<br/><img src="../images/alert.png"/>');	
			}
			
			$('.num_erros').html(num_erro);	
			$(".num_info").html(num_info);
			
			$(".valid_erros").html(htmerr);
			$(".valid_infos").html(htminf);
			
			$(".validacaoarquivos").show();
			
			$("input[id='file_upload2']").val('');
			
			ListaNotasdesaida();
			ListaNotasdesentrada();

			$('.editprod').editable({
				source: ListaProdutoAgregar(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
		            placeholder: 'Selecione um produto agregar',			            			            			       
			    },
			    mode: 'inline',
			    emptytext: 'Vazio',
			    ajaxOptions: { dataType: 'json' },
			    success: function(response, newValue) {
			    	 $("a[data-id='relprod_"+response[0].idprod+"']").append('<i class="fa fa-check text-success"></i>');
					 $("a[data-id='relprod_"+response[0].idprod+"']").removeClass('text-danger');
					 $("a[data-id='relprod_"+response[0].idprod+"']").addClass("text-success");

					 if($("#validaprodutos li a[class='text-danger']").length == 0){
						valida_nota_novamenteTXT(mesano); 
					 }
					/* var iset = setInterval(function(){
						clearInterval(iset);
						valida_nota_novamenteTXT(mesano);						
					 },600);*/
				}
			});

			
			$('.select2-multiple').select2({	
				"language": "pt-BR",											
				mode: 'inline',
				emptytext: 'Vazio',		
				placeholder: 'Selecione um produto agregar',				
			});

			$('.editprod').on('shown', function(e, editable) {
    
				$(document).on('change', editable, function(e) {
					/*var conf = confirm('Deseja realmente relacionar esse produto ?');
					if(conf == true){
						$('form[class="form-inline editableform"]').submit();
						
					}		*/

					/*$('.editable-submit').css({
						'background':'#000'
					});*/

					var btns = '<input type="submit" value="Relacionar ?" class="btn btn-info btn-sm editable-submit fa fa-check" style="float: left;"/>';
					$('.editable-submit').remove();
					
					$(".editable-buttons").append(btns);

					var st = setInterval(function(){
						$('.editable-submit').focus();
						clearInterval(st);
					},300)
					

					return false;
					//console.log(e);
					//alert('aqui')
					//$('.editable-submit').select();
					//$('button[type="submit"]')[0].focus();
					//$('.editable-submit').click();
				});
			});
			
		},
		error:function(data){	
			$("#myModal_pdf").hide();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
}


$(document).on('click','.boxmodal',function(){
	var data = $(this).attr('data-content');	
	var title = $(this).attr('data-original-title') === undefined ? 'Formulário': $(this).attr('data-original-title');
	boxmodal = $.confirm({
					title: ''+title+'',
					content: '<div class="form_callback"></div>'+data,
					type: 'orange',
					typeAnimated: true,
					buttons: {
						tryAgain: {
							text: 'Fechar',
							btnClass: 'btn-red',
							action: function(){
								boxmodal.close();
							}
						}
					}
				});
});

$(document).on('click','.btnrelacionamult',function(e){
	var files = '';
	var array = [];
	var sel2  = $(".select2-multiple option:selected").val();
	var type  = $(this).attr('data-type');
	jQuery(".radioprods:checked").each(function(){
			files = this.value;		
			array.push(files);
	});
	
	if(sel2 != ''){

		if(array != ''){

			$.ajax({
				type:'POST',
				cache:false, 
				dataType: "json",
				url:"../php/relaciona-exec.php",
				data:{act:'upinsetselected',pk:sel2,prod:array},
				beforeSend: function(){
					
				},
				success: function(data){
					for (let index = 0; index < data.length; index++) {
						
						const element = data[index];
						
						$("a[data-id='relprod_"+element.idprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='relprod_"+element.idprod+"']").removeClass('text-danger');
						$("a[data-id='relprod_"+element.idprod+"']").addClass("text-success");
						$("a[data-pk='"+element.idprod+"|0'] strong").html(element.idprodrel+' - '+element.relprod);


					}

					if($("#validaprodutos li a[class='text-danger']").length == 0){
						var mesano = $('input[name="mesanocomptxt"]').val();
						if(type != 'xml'){
							valida_nota_novamenteTXT(mesano); 
						}else{
							valida_nota_novamente();
						}
						
					 }
				},
				error:function(data){							
					console.log(data);
					//alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});			


		}else{
			alert('Selecione um ou mais para relacionar!');
		}
	}else{

		alert('Primeiro selecione um produto agregar!')
	}
	
	
});

$(document).on('click','.btnrelacionamult2',function(e){
	var files = '';
	var array = [];
	var sel2  = $(".select2-multiple2 option:selected").val();
	var type  = $(this).attr('data-type');
	jQuery(".radioprods2:checked").each(function(){
			files = this.value;		
			
			console.log(this.dataset.pk);
			//console.log($(`.editprodfrig [data-id="relprodfrig_${files}"]`).attr('data-pk'));
			array.push({
				codprod :files,
				obj : this.dataset.pk
			});
	});
	
	if(sel2 != ''){

		if(array != ''){

			$.ajax({
				type:'POST',
				cache:false, 
				dataType: "json",
				url:"../php/relaciona-exec.php",
				data:{act:'upinsertterceiromulti',pk:sel2,prod:array},
				beforeSend: function(){
					
				},
				success: function(data){
					for (let index = 0; index < data.length; index++) {
						const element = data[index];
						$("a[data-prod='relprodfrig_"+element.idprod+"|"+element.indForn+"|"+element.nnota+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-prod='relprodfrig_"+element.idprod+"|"+element.indForn+"|"+element.nnota+"']").removeClass('text-danger');
						$("a[data-prod='relprodfrig_"+element.idprod+"|"+element.indForn+"|"+element.nnota+"']").addClass("text-success");
						$("a[data-prod='"+element.idprod+"|"+element.indForn+"|"+element.nnota+"'] strong").html(element.idprodrel+' - '+element.relprod);

						$("a[data-prod='"+element.idprod+"|"+element.indForn+"|"+element.nnota+"']").attr('data-pk',`${element.obj}|${element.codigo}`);


					}
					
					var lenitemrelt = $(".relacionaprodutotereceiro").length;
					var lenitemsuct = $(".relacionaprodutotereceiro .text-success").length;

					if(parseInt(lenitemrelt)===parseInt(lenitemsuct)){
						//var mesano = $('input[name="mesanocomptxt"]').val();						
							valida_nota_novamente();						
					 }
				},
				error:function(data){							
					console.log(data);
					//alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});			


		}else{
			alert('Selecione um ou mais para relacionar!');
		}
	}else{

		alert('Primeiro selecione um produto agregar!')
	}
	
	
});

$(document).on('change','#radiomultiplos',function(e){
	var radiom = $(this).is(':checked');

	if(radiom == true){
		$('.ckmultiplos').show();
		$('.radioprod').css({
			'display':'inline'
		});
	}else{
		$('.ckmultiplos').hide();
		$('.radioprod').hide();
	}

});

$(document).on('change','#radiomultiplos2',function(e){
	var radiom = $(this).is(':checked');
	
	if(radiom == true){
		console.log(radiom);
		$('.ckmultiplos2').show();
		$('.radioprod2').css({
			'display':'inline'
		});
	}else{
		console.log(radiom);
		$('.ckmultiplos2').hide();
		$('.radioprod2').hide();
	}

});

$(document).on('click','.list_errosTXT',function(){
	//$(".valid_erros").slideToggle();
	$(".valid_infos").hide();
	var mesano = $('input[name="mesanocomptxt"]').val();
	valida_nota_novamenteTXTSession(mesano);
	
	$.confirm({
		title: 'Detalhamento de Erros: <div class="form-group row pull-right"><div class="col-sm-12"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="text" class="form-control" placeholder="Buscar" id="autocompleteerros"></div></div></div>',
		content: '<div class="printer_content"><div class="titulovalidacao text-center hide"><div style="text-align:right; margin-right: 50px;">{dthj}</div><div style="font-size: 18px; font-weight: bold;border: 1px #000000 solid;">VALIDAÇÕES AGREGAR</div><hr></div><div class="valid_erros" style="border-left: 5px solid #009efb;"></div></div>',
		type: 'red',
		typeAnimated: true,
		columnClass: 'col-md-12',
		containerFluid: true, // this will add 'container-fluid' instead of 'container'
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					$('[data-toggle="popover"]').popover('hide');
					$('.popover').popover('hide');
					
				}
			},
			print: {
				text: 'Imprimir',
				btnClass: 'btn-orange fa fa-print',
				action: function(){
					$(".printer_erros_infos").click();
					return false;
				}
			}
		}
	});
	
});

$(document).on('click','.list_infosTXT',function(){
	//$(".valid_infos").slideToggle();
	$(".valid_erros").hide();
	var mesano = $('input[name="mesanocomptxt"]').val();
	valida_nota_novamenteTXTSession(mesano);
	$.confirm({
		title: 'Detalhamento de Alertas: <div class="form-group row pull-right"><div class="col-sm-12"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="text" class="form-control" placeholder="Buscar" id="autocompletealert"></div></div></div>',
		content: '<div class="printer_content"><div class="titulovalidacao text-center hide"><div style="text-align:right; margin-right: 50px;">{dthj}</div><div style="font-size: 18px; font-weight: bold;border: 1px #000000 solid;">VALIDAÇÕES AGREGAR</div><hr></div><div class="valid_infos" style="border-left: 5px solid #009efb;"></div></div>',
		type: 'orange',
		typeAnimated: true,
		columnClass: 'col-md-12',
		containerFluid: true, // this will add 'container-fluid' instead of 'container'
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
				}
			},
			print: {
				text: 'Imprimir',
				btnClass: 'btn-orange fa fa-print',
				action: function(){
					$(".printer_erros_infos").click();
					return false;
				}
			}
		}
	});
});

$(document).on("click",".geraagregarvalid2",function(){
	
	var cfop = $(this).html().replace(/[^0-9]/g,'');
	var id   = $(this).attr('data-id');
	var sn   = id == "" ? "inserir":"alterar";
	var nota = $(this).attr('data-xml').split('|')[0];
	var arq  = $(this).attr('data-xml').split('|')[1];

	var htm=  "<form id='frmgeraagreg2'>"+
				"<div class='form_callback'></div>"+
				"<input type='hidden' name='cfop' value='"+cfop+"'/>"+
				"<input type='hidden' name='act' value='"+sn+"'/>"+
				"<input type='hidden' name='cod' value='"+id+"'/>"+
				"<input type='hidden' name='nota' value='"+nota+"'/>"+
				"<input type='hidden' name='caminho' value='"+arq+"'/>"+
				"<div class='input-group'>"+
				 	"<span class='input-group-addon'><i class='fa fa-list'></i></span>"+
					"<select name='agregarsn' class='form-control'>"+
						"<option value=''>Selecione</option>"+
						"<option value='1'>Considerar</option>"+							
						"<option value='2'>Desconsiderar</option>"+
					"</select>"+
					"<span class='input-group-btn'><button type='submit' class='btn btn-block btn-primary'>SALVAR</button></span>"+
				"</div>"+
			  "</form>";
	 
			  cnconf = $.confirm({
				title: 'Cfop',
				content: ''+htm+'',
				type: 'orange',
				typeAnimated: true,
				draggable: false,
				buttons: {
					tryAgain: {
						text: 'Fechar',
						btnClass: 'btn-red',
						action: function(){
							cnconf.close();
						}
					},
					
				}
			});
		
});

$(document).on('submit','#frmgeraagreg2',function(){
	
	var param = $(this).serialize();
	
	if($('select[name="agregarsn"] option:selected').val() == ''){
		
		var view = '<div class="message info">Selecione Considerar OU Desconsiderar !</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	$.ajax({
		type:'POST',
		async:false, 
		url:"../php/cfopempresa-exec.php",
		data:param,
		success: function(data){
			
			var arr = $.parseJSON(data);
			$('.geraagregarvalid').popover('hide');
			
			//$('[data-toggle="popover"]').popover('hide');
			//$('.popover').popover('hide');

			if(arr[0].agregarsn == 2){
				var mesano = $('input[name="mesanocomptxt"]').val();

				var ret = deletanotasdesconsiderada2(arr[0].cfop,mesano);
				
				if(ret == true){
					
					valida_nota_novamenteTXT(mesano);					
				}

			}

			if(arr[0].msg == "Sucesso!"){
				$("#valid_"+arr[0].cfop+" > i").remove();
				$("#valid_"+arr[0].cfop+"").append('<i class="fa fa-check text-success"></i>');
				$("#valid_"+arr[0].cfop+"").removeClass('text-danger');
				$("#valid_"+arr[0].cfop+"").addClass("text-success");
			}
			//console.log($("#validacfop li a[class='geraagregarvalid2 text-danger']").length);
			if($("#validacfop li a[class='geraagregarvalid2 text-danger']").length == 0){
				$("#validaprodutos").css({
					'opacity':'1',
					'pointer-events':'all'
				});
				
			}
			
		},
		error:function(data){

		}
	});
	
	return false;
	
});

function deletanotasdesconsiderada2(cfop,mesano){
		
	var resp;

	$.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'excluirnotasdesconsiderada2',cfop:cfop,mesano:mesano},
		beforeSend: function(){
			
		},
		success: function(data){
			resp = true;			
		},
		error:function(data){	
			resp = false;			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return resp;			
	
}

$(document).on('click','.revalidarprocesso2',function(){
	var mesano = $('input[name="mesanocomptxt"]').val();
	valida_nota_novamenteTXT(mesano);
	
});

function MandaGravaDados(dtini,dtfin,indicador){

	//var retorno;
	
	$.ajax({
		type:'POST',
 	    cache:false, 
		dataType: "json",
		url:"../php/arquivos-exec.php",
		data:{act:'gravadados',dtini:dtini,dtfim:dtfin,indicador:indicador},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde gravando dados..");	
			
		},
		success: function(data){
			$(".loader").remove();
			//retorno.close();
			$("#myModal_pdf").hide();
			if(data[0].tipo == '3'){
								
			//aqui vem validação
			var mesano = $('input[name="mesanocomptxt"]').val();
			valida_nota_novamenteTXT(mesano);
			}else if(data[0].tipo == '2'){
			//alert('teste');
				/*$.dialog({
					title: 'Mensagem',
					content: ''+data[0].msg+'',
				});*/
			 MandaGravaDados(dtini,dtfin,'');
			 	
			}else if(data[0].tipo == '1'){
				
				/*$.dialog({
					title: 'Mensagem',
					content: ''+data[0].msg+'',
				});*/
				
				$.confirm({
					content:''+data[0].msg+'',
					title: 'Mensagem',
					type: 'orange',			 
					buttons: {
						specialKey: {
							text: 'SIM',
							keys: ['s'],
							btnClass: 'btn-green',
							action: function(){
								MandaGravaDados(dtini,dtfin,'1');
							}
						},
						alphabet: {
							text: 'NÃO',
							keys: ['n'],
							btnClass: 'btn-red',
							action: function(){
								window.location.reload();
							}
						}
					}
				});
				
			}
				
			
		},
		error:function(data, textStatus, jqXHR){
			$(".loader").remove();
			$("#myModal_pdf").hide();
			$.dialog({
					title: 'Ops!',
					content: 'Algo deu errado, contate o suporte para que possamos aprimorar, Obrigado!',
			});
				
			console.log('jqXHR:');
			console.log(jqXHR);
			console.log('textStatus:');
			console.log(textStatus);
			console.log('data:');
			console.log(data);
		}		
	});
	
}	
	
$(document).on("click","#dataini",function(){
	$("#dataini").mask("99/99/9999");
	 $(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" );
});	

$(document).on("focus","#dataini",function(){
	$("#dataini").mask("99/99/9999");
	 $(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" );
});		
	
$(document).on("click","#datafin",function(){
	$("#datafin").mask("99/99/9999");
	 
     $(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" )
    
});	

$(document).on("focus","#datafin",function(){
	$("#datafin").mask("99/99/9999");
	 
     $(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" )
    
});	

Dropzone.autoDiscover = false;

$(document).on('blur','input[name="mesanocomptxt"]',function(){
	$("input[id='file_upload2']").prop('disabled',true);	
	var mesano = $(this).val();
	$('input[name="setmesanocomp"]').val(mesano);
	
	var hr  = window.location.href;
	var hrm = hr.split('?');
	var msa = $('input[name="setmesanocomp"]').val();		
	window.history.pushState( null, null, hrm[0]+'?act=Validado&anomes='+mesano+'');	
	
	validamesanocometenciatxtnoup(mesano);

});

$("input[id='file_upload2']").on('click',(function(e){
	//e.preventDefault();
	var dtmesano = $('input[name="mesanocomptxt"]').val();
	if(dtmesano == ""){
		alert("Selecionar o Mês e ano da competencia !");
		$('input[name="mesanocomptxt"]').focus();
		return false;
	}
}));

$("input[id='file_upload2']").on('change', (function (e) { 
	removerarquivopasta();
	var myfiles = document.getElementById("file_upload2");
	var files = myfiles.files;
	var data = new FormData();
	
	var dtmesano = $('input[name="mesanocomptxt"]').val();
	
	//validamesanocometencia(dtmesano);

	if(dtmesano == ""){
		alert("Selecionar o Mês e ano da competencia !");
		$('input[name="mesanocomptxt"]').focus();
		return false;
	}

	for (i = 0; i < files.length; i++) {
		data.append(i, files[i]);
	}

	data.append("dtmesano",dtmesano);	
	data.append("act","box");
	data.append("files","");

	//alert(data);
	e.preventDefault();
	$.ajax({
		url: "../php/arquivos-exec.php",
		type: "POST",
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde..");				
		},
		success: function (data) {
			$("#myModal_pdf").hide();
			var arr  = $.parseJSON(data);
			var htm  = "";
			
			//console.log(arr);	
			if(arr.erro.length > 0){
				var xerro = "";
				for(x in arr.erro){
						
					xerro += ''+arr.erro[x].msg+'[<span class="fa fa-thumbs-o-down"></span>]<br/>';		
				}
				
				$.confirm({
					title: 'Ops!',
					content: ''+xerro+'',
					type: 'red',
					typeAnimated: true,						
						 buttons: {								
							close: function () {
							}
						}
					
				});
			}else{
				
				var dtinis = "01/"+$("#datainis").val();
				var dtfins = daysInMonth($("#datainis").val().split('/')[0],$("#datainis").val().split('/')[1])+'/'+$("#datainis").val();

				MandaGravaDados(dtinis,dtfins,'');
				
			}


							
		},
		error: function () {
			$("#myModal_pdf").hide();
		}
	});
}));

$(document).ready(function(e) {


	/*$(".uploadform").dropzone({	
		acceptedFiles: ".txt",		
		maxFiles: 8, // Number of files at a time
		maxFilesize: 1, //in MB
		uploadMultiple: true,
		addRemoveLinks: true,
		dictRemoveFile: 'X (remove)',
		dictDefaultMessage: "Soltar arquivos aqui ou clique para fazer upload",
		maxfilesexceeded: function(file) {
			alert('Você enviou mais de uma imagem. Apenas o primeiro arquivo será carregado!');
		},		
		init: function () {
			var thisDropzone = this;
			
			this.on("successmultiple", function(files, response) {
				console.log(thisDropzone.files);
			});
		},
		success: function (response) {

			var data = JSON.parse(response.xhr.responseText);
			console.log(data);
			if(data.erro.length > 0){
					
				for(x in data.erro){
						
					xerro += ''+data.erro[x].msg+'[<span class="fa fa-thumbs-o-down"></span>]<br/>';		
				}
				
				$.confirm({
					title: 'Ops!',
					content: ''+xerro+'',
					type: 'red',
					typeAnimated: true,						
						 buttons: {								
							close: function () {
							}
						}
					
				});
				
			}

		},		
		removedfile: function(file) {
		var _ref;
				 return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;  
		 }	
		
		});*/


   	$formsemi = $('form[id="formapuracao"]');
	$formsemi.validate({
	
		rules: {
			 dataini:{           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
			datafin: {           //input name: fullName
                    required: true,   //required boolean: true/false                 
                },
					
			},
		messages:{
			 dataini: {
                      required:"Selecione a data inicial.",                      
                      },
			datafin: {
                      required:"Selecione a data final",                      
                      },		  
					  			
			},
		submitHandler: function(form) {
			  		
		  var dlog = $.dialog({
					title: 'Aguarde enguanto eu apuro o seus dados ;D',
					closeIcon:false,
					content: '<div align="center"><img src="../images/ajax_loading.gif"/></div>',
			});
				
		   var $form = $(form);
           var params = $form.serialize();	
			
			$.ajax({
				type: 'POST',			
				url: '../php/relatorio-apuracao.php',
				data: params,	
				success: function(data){
					
					dlog.close();
					
					var info = "<h4><strong>Apuração dos dados de "+$("#datainis").val()+" a "+$("#datafins").val()+"</strong></h4>";
					
					
					$("#relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'<br/><button class="btn btn-large btn-block btn-primary" id="geraarqbackup" type="button">GERAR @BACKUP</button>');
					//$(".info").html(info);
										
					
					
					$('.table').dataTable({
						"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
						"iDisplayLength": -1,
						"dom": 'T<"clear">lfrtip',						
						"order": []					   													
					});													
					
					$('.dataTables_length').css({'display':'none'});
					$('.dataTables_filter').css({'display':'none'});
					$('.dataTables_paginate').css({'display':'none'});
					$('.dataTables_info').css({'display':'none'});
					
					$("#relatorio").show();
					
				},
				error: function(data){
					alert(data);	
				}
			});
			return false;
		}				
	});	 				
});

function validamesanocometenciatxtnoup(mesano){
		var dmsg;
		if(soNumero(mesano) != ""){					
			$.ajax({
				type:'POST',
				cache:false, 
				dataType: "json",
				url:"../php/lancamentos-exec.php",
				data:{act:'validmesano',mesano:mesano},
				beforeSend: function(){
					dmsg = $.dialog({
						title: 'Mensagem do sistema!',
						content: 'Aguarde Validando mês de competência!',
					});
				},
				success: function(data){							
					
					 if(data[0].tipo == '3'){
						//window.location.reload();
						$.confirm({
						    title: 'Mensagem do sistema',
						    content: ''+data[0].msg+'',
						    type: 'orange',
						    typeAnimated: true,
						    buttons: {
						        sim: {
						            text: 'FECHAR',
						            btnClass: 'btn-green',
						            action: function(){
										var hr  = window.location.href;
										var hrm = hr.split('?');
										$('input[name="setmesanocomp"]').val('');					
										window.history.pushState( null, null, hrm[0]+'');
						            	window.location.reload();
						            }
						        },						        						       
						    }
						});

						return false;
												
					   }else{
						$("input[id='file_upload2']").prop('disabled',true);
						var aset = setInterval(function(){
							clearInterval(aset);
							$("input[id='file_upload2']").prop('disabled',false);
							$("input[id='file_upload2']").click();							
						},600);
						
					   }
					   dmsg.close();
				},
				error:function(data){	
						
					alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});
		}	

		return false;

	}


	function ValidaMesAnoCompInicio(mesano){
		var ret = [];
		if(soNumero(mesano) != ""){					
			$.ajax({
				type:'POST',
				async:false, 
				dataType: "json",
				url:"../php/lancamentos-exec.php",
				data:{act:'validmesanoinicio',mesano:mesano},
				beforeSend: function(){
					
				},
				success: function(data){							
					ret = data;				
				},
				error:function(data){							
					alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
				}		
			});
		}	
		return ret;
	}

$(document).on('click','#geraarqbackup',function(e) {
    
		
	var dtini = $("#datainis").val();	
	var dtfim = $("#datafins").val();
	var retorno;
		
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/geradbf-exec.php",
		data:{act:'gerar',dataini:dtini,datafim:dtfim},
		beforeSend: function(){
			retorno = $.dialog({
					title: 'Aguarde pode demorar alguns minutos!',
					closeIcon:false,
					content: '<div align="center"><img src="../images/ajax_loading.gif"/></div>',
				});
		},
		success: function(data){
				
			retorno.close();	
			
			alert(data[0].msg);
						
			window.location.href = '../php/admin.php';
		},
		error:function(data){
			retorno.close();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;		
});

$(document).ready(function(){
	$('.deletefundesa').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/fundesa-exec.php",
						data:{act:'delete',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});

	$('.deletefundovi').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/fundovi-exec.php",
						data:{act:'delete',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
	$('.deletefolha').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/folha-exec.php",
						data:{act:'delete',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	

});

function validafolhacomp(dt){	
		//console.log(dt);
		if(dt != ''){
			$.ajax({
				type:'POST',
				url:"../php/folha-exec.php",
				data:{act:'verificacompfolha',dtpago:dt},
				cache:false, 
				dataType: "json",
				success: function(data){
										
					if(data[0].tipo == 1){
						alert(data[0].msg);
						$("#dtpago").val('');						
					}
					
				}	
			});
		}	
}

$(document).ready(function(){
	$("#cep").blur(function(){
		buscacep();
	});

	$("#cnpj").blur(function(){
		var cnpj = $(this).val();
		consultaCNPJ(''+cnpj+'').then((json) => {
			//console.log(json);
			//fantasia
			//cep
			//complemento
			//email
			//logradouro
			//municipio
			//numero
			//uf
			//cep	
			//bairro			
			$("#nome").val(json.nome);
			$("#fantasia").val(json.fantasia);
			$("#cep").val(json.cep.replace(/[^0-9]/g,''));
			$("#ende").val(json.logradouro);	
			$("#nro").val(json.numero);
			$("#bairro").val(json.bairro);
			$("#cidade").val(json.municipio);
			$("#uf").val(json.uf);
			$("#cpl").val(json.complemento);
			$("#email").val(json.email);
		}, (erro) => {
			console.log('ERRO:', erro);
		});

	});

	$(".select2usuario").change(function(){
		var d = $(this).val();
		console.log(d);

		$.ajax({
			type:'POST',
			url:"../php/empresa-exec.php",
			data:{act:'buscarum',id:d},
			cache:false, 
			dataType: "json",
			success: function(data){
									
				$("#nome").val(data[0].label);
				$("#email").val(data[0].email);
				$("#login").val(data[0].cnpj);
				
			}	
		});

	});
});




function buscacep(){
				
	jQuery("#ende").val("...");
	jQuery("#bairro").val("...");
	jQuery("#cidade").val("...");
	jQuery("#uf").val("...");
	
	var consulta = jQuery("#cep").val();
	
	$.ajax({
		type: 'POST',			
		url: 'http://cep.republicavirtual.com.br/web_cep.php',
		data: {cep:''+consulta+'',formato:'json'},	
		dataType: 'json',
		success: function(data){
				
	    var  rua    = unescape(data.logradouro)
	    var  bairro = unescape(data.bairro)
	    var  cidade = unescape(data.cidade)
	    var  uf     = unescape(data.uf)
		
		$("#ende").val("Rua "+rua+"");	
		$("#bairro").val(""+bairro+"");
		$("#cidade").val(""+cidade+"");
		$("#uf").val(""+uf+"");													
		},
		error: function(data){
			alert(data);	
		}
	});
	
	return false;
	
		
}

document.addEventListener('DOMContentLoaded', function () {
  if (!Notification) {
    alert('Desktop notifications not available in your browser. Try Chromium.'); 
    return;
  }

  if (Notification.permission !== "granted")
    Notification.requestPermission();
});

function notifyMe(title,mensagem) {
  if (Notification.permission !== "granted")
    Notification.requestPermission();
  else {
    var notification = new Notification(' '+title+' ', {
      icon: 'http://icons.iconarchive.com/icons/custom-icon-design/silky-line-user/512/users-2-icon.png',
      body: ""+mensagem+"",
    });

    notification.onclick = function () {
      window.open("http://stackoverflow.com/a/13328397/1269037");      
    };

  }

}

Pusher.logToConsole = true;
var pusher = new Pusher('d660df1b434be12934a5', {
      cluster: 'us2',
      encrypted: true
 });

 var channel = pusher.subscribe('my-channel');
 	   channel.bind('my-event', function(data) {
       	//alert(JSON.stringify(data));
       	var numeronaolida = parseInt($('.numeronaolida').html());

       	var dados  = data.message.split("|");
       	var codemp = dados[3];
       	
       	var dt = new Date();
       	
       	var hora     = dt.getHours();          
		var min      = dt.getMinutes();        
		var seg      = dt.getSeconds();        
       	var str_hora = hora + ':' + min;

       	var htm    = '<a href="../php/detalhe-notificacao.php?id='+dados[4]+'" class="alert-info">'+
                        '<div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>'+
                        '<div class="mail-contnet">'+
                            '<h5>'+dados[0]+'</h5><span class="mail-desc">'+dados[1]+'</span> <span class="time">'+str_hora+'</span> </div>'+
                      '</a>';

        var label = '<span class="heartbit"></span> <span class="point"></span>';              

       	$("#cliente_"+codemp+"").append(htm);     	
       	$(".adm").append(htm);
       	$(".notify_adm").html(label);
     	$("#notify_"+codemp+"").html(label);

     	notifyMe(dados[0],dados[1]);
     	$('.numeronaolida').html((numeronaolida + 1)); 
     	Anim('bounceIn');
 });

function Anim(x) {
        $('#nummsg').addClass(x + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function() {
            $(x + ' animated').removeClass();
        });
    };