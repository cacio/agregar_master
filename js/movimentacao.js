// JavaScript Document

var balao;
var balao2;
var boxmodal;
var cabbox;
var $dTable;
var arrayItens  =[];
var arrayItens2 =[];

$(function()
{
	// Variável para armazenar seus arquivos
	var files;
	
	// adicionado o evento
	$('input[type=file]').on('change', prepareUpload);
	
	$('form[id="upload"]').on('submit', uploadFiles);

	// Pegue os arquivos e colocá-las à nossa variável
	function prepareUpload(event)
	{
		files = event.target.files;
	}

	// Pegar o envio de formulário e enviar os arquivos
	function uploadFiles(event)
	{
		event.stopPropagation(); // Pare de coisas acontecendo
        event.preventDefault(); // Totalmente parar coisas acontecendo

        // / Iniciar um spinner CARREGANDO AQUI

        // Criar um objeto FormData e adicionar os arquivos
		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
		});
        
        $.ajax({
            url: '../php/lancamentos-exec.php?act=box&files',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, 
            contentType: false, 
            success: function(data, textStatus, jqXHR)
            {
	
            	if(typeof data.error === 'undefined')
            	{
            		// Sucesso até chamar a função para processar o formulário
            		submitForm(event, data);
            	}
            	else
            	{

            		console.log('ERRORS: ' + data.error);
            	}
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
            	
            	console.log('ERRORS: ' + textStatus);
            	
            }
        });
    }

    function submitForm(event, data)
	{
		// Create a jQuery object from the form
		$form = $(event.target);
		
		// Serialize the form data
		var formData = $form.serialize();
		var retorno;
		// You should sterilise the file names
		$.each(data.files, function(key, value)
		{
			formData = formData + '&filenames[]=' + value;
		});

		$.ajax({
			url: '../php/lancamentos-exec.php?act=box',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
			beforeSend: function(){
				retorno = $.dialog({
					title: 'Aguarde pode demorar alguns minutos!',
					closeIcon:false,
					content: '<div align="center"><img src="../images/ajax_loading.gif"/></div>',
				});
			},
            success: function(data, textStatus, jqXHR)
            {
				
				$("#dyntable_notasprodutos tbody").html('');
				$("#dadosprodnota").hide();
				$("#dadosnotas").show();
								  
				var htm = "";
				var notasexist   = "";
				var notas        = new Array();
				var xmlnotaexist = new Array();
				for(var i = 0; i < data.length; i++){
					
					var marc = "";
					
					if(data[i].msg == ""){
					
						if(data[i].entsai == 'E'){
							var dts = '<a href="#" class="details"><span class="fa fa-pencil-square fa-2x"></span></a>';
							marc    = "entrada";
												
						}else{
							var dts = '';
						}
						
						htm += '<tr id="'+data[i].Numero+'" data-caminho="'+data[i].caminho+'" class="warning">'+
								  '<td>'+data[i].Numero+'</td>'+
								  '<td>'+data[i].dEmi+'</td>'+
								  '<td style="width: 33%">'+data[i].chave+'</td>'+
								  '<td style="text-align:center; width: 7%;">'+data[i].entsai+'</td>'+
								  '<td>'+data[i].cliente+'</td>'+
								  '<td style="text-align:right; width: 10%;">'+data[i].valor+'</td>'+
								  '<td  class="centeralign '+marc+'" style="text-align: right">'+
									''+dts+''+
									'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
								  '</td>'+
								'</tr>';		
					}else{
					
						notasexist += data[i].Numero;
						notas.push(data[i].Numero);						
						xmlnotaexist.push(data[i].caminho);
					}
				}
				
							
					
				if(notasexist != ""){
					var xnt = "";		
					$.each(notas, function (index, value) {
						//alert( index + ' : ' + value );
						
						if(index % 8 == 0){
							xnt += '-'+value+'<br/>';	
						}else{
							xnt += '-'+value+'';
						}
						
					});
					
					balao = $.confirm({
						title: 'INFORMA!',
						content: 'Nota Fiscal <br/> '+xnt+' <br/> já Consta na base de dados, Deseja Importar Novamente ?',
						type: 'orange',
						typeAnimated: true,
						buttons: {
							sim: {
								text: 'SIM',
								btnClass: 'btn-green',
								action: function(){
									deletanotas(xmlnotaexist);
								}
							},
							nao: {
								text: 'NÃO',
								btnClass: 'btn-red',
								action: function(){
									
								}
							}
						}
					});
				}
				
				if(notasexist == ""){

					$dTable = $('#dyntable_notas').dataTable({					
						 "bSort" : false,
						 "paging":   false,
						 "ordering": true,
						 "info":     false,
						 "bDestroy": true,
						 "bFilter": true
					});
					
				}
				
				$("#dyntable_notas tbody").html(htm);
											
				retorno.close();
            },
            error: function(jqXHR, textStatus, errorThrown)
            {
				retorno.close();
            	alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!\r\n"+jqXHR.responseText);
            	console.log('ERRORS: ' + textStatus);
            },
            complete: function()
            {
            	// STOP LOADING SPINNER
            }
		});
	}
});

function convertevalores(valor2){
	if(valor2.length > 2 && valor2.length <= 6){
		var valstr2 = parseFloat(valor2.replace(",","."));	
	}else{
		var valstr2 = parseFloat(valor2.replace(",",".").replace(".",""));		
	}
	
	return valstr2.toFixed(2);
}
function convertevaloresteste(valor2){
	console.log(valor2.length+' - '+valor2) ;

	if(valor2.length > 2 && valor2.length <= 6){
		var valstr2 = parseFloat(valor2.replace(",","."));	
	}else{
		var valstr2 = parseFloat(valor2.replace(",",".").replace(".",""));		
	}		
	
	
	return valstr2.toFixed(2);
}

$(document).on('click','.details',function(){

		var cod = $(this).parents('tr').attr('id');
		var cam = $(this).parents('tr').attr('data-caminho');
		var entsai = $("#dyntable_notas_valid tbody tr[id='"+cod+"'] td:eq(3)").html(); 
		var retornodetal;
		
		//alert(entsai);
		//$("#dyntable_notas tbody tr[id='"+cod+"']").removeClass('warning');
		
		//$("#dyntable_notas tbody tr[id='"+cod+"']").addClass('info');
		
		//$("#numeronota").val(cod);
		
		//$("#filenames").val(cam);
		
		$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'dadosprod',cod:cod,arquivo:cam,entsai:entsai},
		beforeSend: function(){
			retornodetal = $.dialog({
					title: 'Aguarde pode demorar alguns minutos!',
					closeIcon:false,
					content: '<div align="center"><img src="../images/ajax_loading.gif"/></div>',
				});
		},
		success: function(data){
				retornodetal.close();				
			
			//$("#dadosprodnota").show();
			
			//$("#dyntable_notasprodutos tbody").html('');									
			
			/*$('html, body').animate({
				scrollTop: $('#dadosprodnota').offset().top
			}, 1000);*/
			
			var htm  = '';
			var htm2 = '';
			var hide = entsai == 'SAIDA' ? 'hide':'';
			
					
			htm2 +='<form id="frmnotaxml" action="lancamentos-exec.php">'+
						'<input type="hidden" name="act" value="updatenotas"/>'+
						'<input type="hidden" name="numeronota" id="numeronota" value="'+cod+'"/>'+
						'<input type="hidden" name="filenames[]" id="filenames" value="'+cam+'"/>'+
					   '<div id="dadosprodnota">';
			
			htm2 +='<table id="dyntable_notasprodutos" data-toggle-column="first" data-sorting="false" class="table table-striped dataTable no-footer" role="grid" onmouseover="Xtooltip()">'+
					  '<thead>'+
						'<tr>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Codigo do produto">Código</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Descrição do produto">Descrição</th>'+
						  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Quantidade do produto">Quantidade</th>'+
						  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Valor unitário">R$ Unitário</th>'+
						  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Subtotal da NF-e">R$ Total</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Relacionamento de produto com agregar!">Relação AGREGAR</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Número de cabeça">Nº Cabeça</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Peso Carcaça">Kg Carcaça</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Peso Vivo">Kg Vivo</th>'+
						  '<th style="text-align:center;" data-tooltip="tooltip" title="Vivo ou Rendimento">V/R</th>'+
						  '<th data-hide="all" style="text-align:center;">Data do Abate</th>'+
						  '<th data-toggle="tooltip" style="text-align:center;">#</th>'+
						'</tr>'+
					  '</thead>'+
					  '<tbody>';
			
			
			htm2 +='</tbody>'+
					'</table>';
				
			htm2 +='<br/>'+
						'<br/>'+
					 '<p class="stdformbutton">'+
						'<button class="btn btn-large btn-block btn-primary '+hide+'">GRAVAR DADOS</button>'+						
					 '</p>'+
				   '</div>'+
				 	'</form>';
			
			balao2 = $.confirm({
					title: 'Formulário',
					content: ''+htm2+'',
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-12 col-md-offset-8 col-xs-4 col-xs-offset-8',
					containerFluid: true,
					buttons: {					
						tryAgain: {
							text: 'FECHAR',
							btnClass: 'btn-red',
							action: function(){
							}
						},
					}
				});						
			
			var setint = setInterval(function(){
								
				for(var i = 0; i < data.length; i++){
						
						if(data[i].id_rel == '99999'){
								hide = 'hide';
						}/*else{
							hide = '';
						}*/				
						var selected = '<select class="vivorend vivorend_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][vivorend]" required>'+
											'<option value="">Selecione</option>'+
											'<option value="V">VIVO</option>'+
											'<option value="R">RENDIMENTO</option>'+
										'</select>';

						var formu = "";
						var lin   = '';
						
						if(data[i].tipo_r_v == 'V'){
							var xvivo = "disabled";
							var xrend = "";
						}else if(data[i].tipo_r_v == 'R'){
							var xrend = "disabled";
							var xvivo = "required";	
						}else{
							var xvivo = "disabled";
							var xrend = "disabled";
						}
						
						if(data[i].id_rel == ""){
							
							 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
										  "<input type='hidden' name='act' value='inserir'/>"+
										  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
										  "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
										  "<div class='input-prepend'>"+
												"<label><strong>Produto:</strong></label>"+
												"<span class='add-on fa fa-search'></span>"+
												"<input type='text' name='relprod' id='relprod' class='form-control'  />"+
												"<input type='hidden' name='idprodrel' id='idprodrel'/>"+
											"</div>"+
										  "<button class='btn btn-primary btn-block relaciona' type='button'>SALVAR</button>"+
										 "</form>"; 

							 //lin = '<button class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">Relacionar</button>';
							 lin='<a href="#" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter">Relacionar</a>';					
						}else{
							 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
										  "<input type='hidden' name='act' value='alterar'/>"+
										  "<input type='hidden' name='id' value='"+data[i].idre+"'/>"+
										  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
										   "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
										  "<div class='input-prepend'>"+
												"<label><strong>Produto:</strong></label>"+
												"<span class='add-on fa fa-search'></span>"+
												"<input type='text' name='relprod' id='relprod' class='form-control' value='"+data[i].descricao+"'/>"+
												"<input type='hidden' name='idprodrel' id='idprodrel' value='"+data[i].id_rel+"'/>"+
											"</div>"+
										  "<button class='btn btn-primary btn-block relaciona' type='button'>ALTERAR</button>"+
										 "</form>";

							 //lin = '<a href="#" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">'+data[i].descricao+' <i class="fa fa-pencil" aria-hidden="true"></i></a>';

							 lin='<a href="#" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter">'+data[i].descricao+'</a>';
						}

						htm = '<tr id="'+data[i].id+'">'+
								'<td style="text-align:center;">'+data[i].codigo_produto+'<input type="hidden" name="item['+i+'][cprod]" value="'+data[i].codigo_produto+'" /></td>'+
								'<td>'+data[i].desc_produto+'</td>'+
								'<td style="text-align:right;">'+data[i].qtd_prod+'</td>'+
								'<td style="text-align:right;">'+data[i].preco_quilo+'</td>'+
								'<td style="text-align:right;">'+data[i].subtotal+'</td>'+
								'<td tyle="text-align:center;">'+lin+'</td>'+
								'<td><input type="text" class="form-control ncabeca ncabeca_'+data[i].codigo_produto+' span1 '+hide+'" name="item['+i+'][ncabeca]" style="text-align:center;" value="'+data[i].qtd_cabeca+'" required/></td>'+
								'<td><input type="text" name="item['+i+'][npesocarcaca]" class="form-control span1 npesocarcaca_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;" value="'+data[i].peso_carcasa+'" '+xrend+'/></td>'+
								'<td><input type="text" name="item['+i+'][npesovivo]" class="form-control span1 npesovivo_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;" value="'+data[i].pesovivo+'" '+xvivo+'/></td>'+
								'<td>'+selected+'</td>'+
								'<td>'+
								'<input type="text" class="dtabate span2 dtabate_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][dtabate]" style="text-align:center;" value="'+data[i].data_abate+'" required/>'+
								'<input type="hidden" name="item['+i+'][id]" value="'+data[i].id+'" /><input type="hidden" name="item['+i+'][qCom]" value="'+data[i].qCom+'" />'+
								'</td>'+
								'<td data-toggle="tooltip" data-tooltip="tooltip" title="Clique aqui para mais detalhes!"></td>'+							  
							'</tr>';

							$("#dyntable_notasprodutos tbody").append(htm);					

							$('select[name="item['+i+'][vivorend]"] option[value="'+data[i].tipo_r_v+'"]').attr('selected', 'selected');

				}														
				$('#dyntable_notasprodutos').footable();
												
				$('.editprod').select2({
					source: ListaProdutoAgregar(),
				    select2: {
						"language": "pt-BR",
			            width: '345px',
			            placeholder: 'Selecione um produto agregar',			            			            			       
				    },
				    mode: 'inline',
				    emptytext: 'Vazio',				
					success: function(response, newValue) {
						var obj = JSON.parse(response);
						 //console.log(response);
						 //console.log(newValue);
						 if(obj[0].idprodrel != '99999'){ 	
							 $(".ncabeca_"+obj[0].idprod+"").show();
							 $(".npesocarcaca_"+obj[0].idprod+"").show();
							 $(".npesovivo_"+obj[0].idprod+"").show();
							 $(".dtabate_"+obj[0].idprod+"").show();
							 $(".vivorend_"+obj[0].idprod+"").show();
						 }else{
 							 $(".ncabeca_"+obj[0].idprod+"").hide();
							 $(".npesocarcaca_"+obj[0].idprod+"").hide();
							 $(".npesovivo_"+obj[0].idprod+"").hide();
							 $(".dtabate_"+obj[0].idprod+"").hide();
							 $(".vivorend_"+obj[0].idprod+"").hide();							
						 }
						 
						 var iset = setInterval(function(){
							clearInterval(iset);							
							valida_nota_novamente();						
						 },600);
					},
				});

				clearInterval(setint);
			},500);
			//$("#boxprodnota").hide();
						
		},
		error:function(data){
			retornodetal.close();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
		
});

function Xtooltip(){
	$('#dyntable_notasprodutos thead tr th[data-tooltip="tooltip"]').tooltip({
		"delay": 0,
		"track": true,
		"fade": 250
   });

   $('#dyntable_notasprodutos tbody tr td[data-tooltip="tooltip"]').tooltip({
	"delay": 0,
	"track": true,
	"fade": 250
	});
}

$(document).on('mouseover','.footable-toggle',function(){
	$(this).attr('title',"Clique aqui para mais detalhes da nota!");
	$(this).attr('data-toggle',"tooltip");
	//$(this).addClass('tooltipAgreg');
	/*var f = $(this);
	var sr = setInterval(function(){
		$('.footable-toggle').tooltip({						
			'placement':'top',
			'title':'Clique aqui para mais detalhes da nota!'
	   });
	   clearInterval(sr);
	},300);*/
	

});

function detailsnotasOLD(cod){

		var cod    = cod;
		var cam    = $("#dyntable_notas_valid tbody tr[id='"+cod+"']").attr('data-caminho');
		var entsai = $("#dyntable_notas_valid tbody tr[id='"+cod+"'] td:eq(3)").html(); 
		
					
		$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'dadosprod',cod:cod,arquivo:cam,entsai:entsai},
		beforeSend: function(){
			/*retorno = $.dialog({
					title: 'Aguarde pode demorar alguns minutos!',
					closeIcon:false,
					content: '<div align="center"><img src="../images/ajax_loading.gif"/></div>',
				});*/
		},
		success: function(data){
				//retorno.isClosed();				
			
			//$("#dadosprodnota").show();
			
			//$("#dyntable_notasprodutos tbody").html('');									
			
			/*$('html, body').animate({
				scrollTop: $('#dadosprodnota').offset().top
			}, 1000);*/
			
			var htm  = '';
			var htm2 = '';
			var hide = entsai == 'SAIDA' ? 'hide':'';
			htm2 += 'Número da Nota: '+cod+' ';
			htm2 +='<form id="frmnotaxml" action="lancamentos-exec.php">'+
						'<input type="hidden" name="act" value="updatenotas"/>'+
						'<input type="hidden" name="numeronota" id="numeronota" value="'+cod+'"/>'+
						'<input type="hidden" name="filenames[]" id="filenames" value="'+cam+'"/>'+
					   '<div id="dadosprodnota">';
			
			htm2 +='<table id="dyntable_notasprodutos" data-toggle-column="last" data-sorting="false" class="table table-striped dataTable no-footer" role="grid" onmouseover="Xtooltip()">'+
					  '<thead>'+
						'<tr>'+
						'<th style="text-align:center;" data-tooltip="tooltip" title="Codigo do produto">Código</th>'+
						'<th style="text-align:center;" data-tooltip="tooltip" title="Descrição do produto">Descrição</th>'+
						'<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Quantidade do produto">Quantidade</th>'+
						'<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Valor unitário">R$ Unitário</th>'+
						'<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Subtotal da NF-e">R$ Total</th>'+
						'<th style="text-align:center;" data-tooltip="tooltip" title="Relacionamento de produto com agregar!">Relação AGREGAR</th>'+
						'<th style="text-align:center;" data-tooltip="tooltip" title="Número de cabeça">Nº Cabeça</th>'+
						'<th style="text-align:right;" data-tooltip="tooltip" title="Peso Carcaça">Kg Carcaça</th>'+
						'<th style="text-align:right;" data-tooltip="tooltip" title="Peso Vivo">Kg Vivo</th>'+
						'<th style="text-align:center;" data-tooltip="tooltip" title="Vivo ou Rendimento">V/R</th>'+
						'<th data-hide="all" style="text-align:center;">Data do Abate</th>'+
						'<th data-toggle="tooltip" style="text-align:center;">#</th>'+
						'</tr>'+
					  '</thead>'+
					  '<tbody>';
			
			
			htm2 +='</tbody>'+
					'</table>';
				
			htm2 +='<br/>'+
						'<br/>'+
					 '<p class="stdformbutton">'+
						'<button class="btn btn-large btn-block btn-primary ">GRAVAR DADOS</button>'+						
					 '</p>'+
				   '</div>'+
				 	'</form>';
			//'+hide+' tirei do botão gravar dados
			balao2 = $.confirm({
					title: 'Formulário',
					content: ''+htm2+'',
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-12 col-md-offset-8 col-xs-4 col-xs-offset-8',
					containerFluid: true,
					buttons: {
						tryAgain: {
							text: 'FECHAR',
							btnClass: 'btn-red',
							action: function(){
							}
						},					
						
					}
				});						
			
			var setint = setInterval(function(){
								
				for(var i = 0; i < data.length; i++){
						
						if(data[i].id_rel == '99999'){
								hide = 'hide';
						}/*else{
							hide = '';
						}*/				
						var selected = '<select class="vivorend vivorend_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][vivorend]" required>'+
											'<option value="">Selecione</option>'+
											'<option value="V">VIVO</option>'+
											'<option value="R">RENDIMENTO</option>'+
										'</select>';

						var formu = "";
						var lin   = '';

						if(data[i].tipo_r_v == 'V'){
							var xvivo = "disabled";
							var xrend = "";
						}else if(data[i].tipo_r_v == 'R'){
							var xrend = "disabled";
							var xvivo = "required";	
						}/*else{
							var xvivo = "disabled";
							var xrend = "disabled";
						}*/

						if(data[i].id_rel == ""){

							 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
										  "<input type='hidden' name='act' value='inserir'/>"+
										  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
										  "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
										  "<div class='input-prepend'>"+
												"<label><strong>Produto:</strong></label>"+
												"<span class='add-on fa fa-search'></span>"+
												"<input type='text' name='relprod' id='relprod' class='form-control'  />"+
												"<input type='hidden' name='idprodrel' id='idprodrel'/>"+
											"</div>"+
										  "<button class='btn btn-primary btn-block relaciona' type='button'>SALVAR</button>"+
										 "</form>"; 

							/* lin = '<button class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">Relacionar</button>';*/	
							
							 lin = '<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a>';
							
						}else{
							 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
										  "<input type='hidden' name='act' value='alterar'/>"+
										  "<input type='hidden' name='id' value='"+data[i].idre+"'/>"+
										  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
										   "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
										  "<div class='input-prepend'>"+
												"<label><strong>Produto:</strong></label>"+
												"<span class='add-on fa fa-search'></span>"+
												"<input type='text' name='relprod' id='relprod' class='form-control' value='"+data[i].descricao+"'/>"+
												"<input type='hidden' name='idprodrel' id='idprodrel' value='"+data[i].id_rel+"'/>"+
											"</div>"+
										  "<button class='btn btn-primary btn-block relaciona' type='button'>ALTERAR</button>"+
										 "</form>";

							 /*lin = '<a href="#" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">'+data[i].descricao+' <i class="fa fa-pencil" aria-hidden="true"></i></a>';*/
							
							lin = '<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter">'+data[i].descricao+'</a>';
							
						}

						htm = '<tr id="'+data[i].id+'">'+
								'<td style="text-align:center;">'+data[i].codigo_produto+'<input type="hidden" name="item['+i+'][cprod]" value="'+data[i].codigo_produto+'" /></td>'+
								'<td>'+data[i].desc_produto+'</td>'+
								'<td style="text-align:right;">'+data[i].qCom+'</td>'+
								'<td class="text-right">'+data[i].preco_quilo+'</td>'+
								'<td class="text-right">'+data[i].subtotal+'</td>'+
								'<td tyle="text-align:center;">'+lin+'</td>'+
								'<td><input type="text" class="ncabeca span1 ncabeca_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][ncabeca]" style="text-align:center;" value="'+data[i].qtd_cabeca+'" required/></td>'+
								'<td><input type="text" name="item['+i+'][npesocarcaca]" class="form-control span1 npesocarcaca_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;" value="'+data[i].peso_carcasa+'" '+xrend+'/></td>'+
								'<td><input type="text" name="item['+i+'][npesovivo]" class="form-control span1 npesovivo_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;" value="'+data[i].pesovivo+'" '+xvivo+'/></td>'+
								'<td>'+selected+'</td>'+
								'<td>'+
								'<input type="text" class="dtabate dtabate_'+data[i].codigo_produto+' span2 '+hide+' form-control" name="item['+i+'][dtabate]" style="text-align:center;" value="'+data[i].data_abate+'" required/>'+
								'<input type="hidden" name="item['+i+'][id]" value="'+data[i].id+'" /><input type="hidden" name="item['+i+'][qCom]" value="'+data[i].qCom+'" />'+
								'</td>'+
								'<td data-toggle="tooltip" data-tooltip="tooltip" title="Clique aqui para mais detalhes!"></td>'+							  
							'</tr>';

							$("#dyntable_notasprodutos tbody").append(htm);					

							$('select[name="item['+i+'][vivorend]"] option[value="'+data[i].tipo_r_v+'"]').attr('selected', 'selected');

				}														
				
				$('#dyntable_notasprodutos').footable();
				
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

						 if(response[0].idprodrel != '99999'){ 	
							 $(".ncabeca_"+response[0].idprod+"").show();
							 $(".npesocarcaca_"+response[0].idprod+"").show();
							 $(".npesovivo_"+response[0].idprod+"").show();
							 $(".dtabate_"+response[0].idprod+"").show();
							 $(".vivorend_"+response[0].idprod+"").show();
						 }else{
 							 $(".ncabeca_"+response[0].idprod+"").hide();
							 $(".npesocarcaca_"+response[0].idprod+"").hide();
							 $(".npesovivo_"+response[0].idprod+"").hide();
							 $(".dtabate_"+response[0].idprod+"").hide();
							 $(".vivorend_"+response[0].idprod+"").hide();							
						 }
						 
						 var iset = setInterval(function(){
							clearInterval(iset);
							valida_nota_novamente();						
						 },600);
				}
			});
				
				clearInterval(setint);
			},500);
			//$("#boxprodnota").hide();
		},
		error:function(data){
			//retorno.close();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
}

function detailsnotas(cod,tipo){

	var cod    = cod;
	var cam    = $("#dyntable_notas tbody tr[id='"+cod+"']").attr('data-caminho');
	var entsai = $("#dyntable_notas tbody tr[id='"+cod+"'] td:eq(3)").html(); 
	var valotot= $("#dyntable_notas tbody tr[id='"+cod+"'] td:eq(5)").html(); 
	var compt  = $('input[name="mesanocomptxt"]').val();

	if(entsai == undefined){
		cam    = $("#dyntable_notas_valid tbody tr[id='"+cod+"']").attr('data-caminho');
		entsai = $("#dyntable_notas_valid tbody tr[id='"+cod+"'] td:eq(3)").html(); 
		valotot= $("#dyntable_notas_valid tbody tr[id='"+cod+"'] td:eq(5)").html(); 
	}

	$.ajax({
	type:'POST',
	cache:false, 
	dataType: "json",
	url:"../php/lancamentos-exec.php",
	data:{act:'detalhenota',numero:cod,tipo:tipo,competencia:compt},
	beforeSend: function(){
		$("#myModal_pdf").show();
		$(".modal-body_PDF img").show();
		$(".modal-body_PDF p").html("Aguarde...!");
	},
	success: function(data){
			//retorno.isClosed();				
		
		//$("#dadosprodnota").show();
		
		//$("#dyntable_notasprodutos tbody").html('');									
		
		/*$('html, body').animate({
			scrollTop: $('#dadosprodnota').offset().top
		}, 1000);*/
		$("#myModal_pdf").hide();
		var htm  = '';
		var htm2 = '';
		var hide = entsai == 'SAIDA' ? 'hide':'';
		//Valor Total   : ${valotot} 
		
		htm2 += `<div class="row">`;
		htm2 += `		
		<div class="col-md-6 col-lg-3 col-xlg-3">
			<div class="card card-inverse card-info">
				<div class="box bg-info text-center">
				<strong class="font-light text-white">Número do Documento</strong>
				<br>
			   <p class="font-light text-white" style="font-size: 31px;">${cod}</p>
				</div>
			</div>
		</div>		
		`;

		if(entsai == 'ENTRADA'){
			
			var tpabate     = $("input[id='"+cod+"_tpabate']").val();
			var tpabtcheck  = "";
			var tpabtcheck2 = "";

			if(tpabate == 'P'){
				tpabtcheck = "selected";
			}else if(tpabate == 'T'){
				tpabtcheck2 = "selected";
			}

			htm2 += `		
			<div class="col-md-6 col-lg-3 col-xlg-3">
			<div class="card card-inverse card-info">
				<div class="box bg-info text-center">
					<strong class="font-light text-white">Próprio/Terceiros</strong>
					<div class="form-group">				                               
					<select name="tpptform" class="form-control">
						<option value="">Selecione</option>
						<option value="P" ${tpabtcheck}>Próprio</option>
						<option value="T" ${tpabtcheck2}>Terceiros</option>
					</select>
				</div>
				</div>
			</div>
		</div>
		`;
		}
		htm2 += `</div>`;
		htm2 +='<form id="frmnotaxml" action="lancamentos-exec.php">'+
					'<input type="hidden" name="act" value="updatenotas"/>'+
					'<input type="hidden" name="tpnota" value="'+entsai+'"/>'+
					'<input type="hidden" name="numeronota" id="numeronota" value="'+cod+'"/>'+
					'<input type="hidden" name="filenames[]" id="filenames" value="'+cam+'"/>'+
				   '<div id="dadosprodnota">';
		if(entsai == 'SAIDA'){
			htm2 +='<table id="dyntable_notasprodutos" data-paging="false" data-toggle-column="last" data-sorting="false" class="table table-striped dataTable no-footer" role="grid" onmouseover="Xtooltip()">'+
			'<thead>'+
			  '<tr>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Codigo do produto">Código</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Descrição do produto">Descrição</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Relacionamento de produto com agregar!">Relação AGREGAR</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Valor unitário">R$ Unitário</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Quantidade do produto">Quantidade</th>'+			  
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Subtotal da NF-e">R$ Total</th>'+			  
			  /*'<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Numero de cabeça">Nº Cabeça</th>'+
			  '<th data-hide="all" style="text-align:right;" data-tooltip="tooltip" title="Peso Carcaça">Kg Carcaça</th>'+
			  '<th data-hide="all" style="text-align:right;" data-tooltip="tooltip" title="Peso Vivo">Kg Vivo</th>'+
			  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Vivo ou Rendimento">V/R</th>'+
			  '<th data-hide="all" style="text-align:center;">Data do Abate</th>'+*/
			  '<th data-tooltip="tooltip" style="text-align:center;" title="Ação de exclução do item">AÇÃO</th>'+
			  '</tr>'+
			'</thead>'+
			'<tbody>';
		}else{

			htm2 +='<table id="dyntable_notasprodutos" data-paging="false" data-toggle-column="last" data-sorting="false" class="table table-striped dataTable no-footer" role="grid" onmouseover="Xtooltip()">'+
			'<thead>'+
			  '<tr>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Codigo do produto">Código</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Descrição do produto">Descrição</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Relacionamento de produto com agregar!">Relação AGREGAR</th>'+
			  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Quantidade do produto">Quantidade</th>'+
			  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Valor unitário">R$ Unitário</th>'+
			  '<th data-hide="all" style="text-align:center;" data-tooltip="tooltip" title="Subtotal da NF-e">R$ Total</th>'+			  
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Número de cabeça">Nº Cabeça</th>'+
			  '<th style="text-align:right;" data-tooltip="tooltip" title="Peso Carcaça">Kg Carcaça</th>'+
			  '<th style="text-align:right;" data-tooltip="tooltip" title="Peso Vivo">Kg Vivo</th>'+
			  '<th style="text-align:center;" data-tooltip="tooltip" title="Vivo ou Rendimento">V/R</th>'+
			  '<th style="text-align:center;">Data do Abate</th>'+
			  '<th data-toggle="tooltip" style="text-align:center;">#</th>'+
			  '<th data-hide="all" data-tooltip="tooltip" style="text-align:center;" title="Ação de exclução do item">AÇÃO</th>'+
			  '</tr>'+
			'</thead>'+
			'<tbody>';
		}

		
		
		htm2 +='</tbody>'+
				'</table>';
			
		htm2 +='<br/>'+
					'<br/>'+
				 '<p class="stdformbutton">'+
					'<button class="btn btn-large btn-block btn-primary">GRAVAR DADOS</button>'+						
				 '</p>'+
			   '</div>'+
				 '</form>';
		//'+hide+' tirei do botão gravar dados
		balao2 = $.confirm({
				title: 'Detalhamento de Documento Fiscal',
				content: ''+htm2+'',
				type: 'green',
				typeAnimated: true,
				columnClass: 'col-md-12 col-md-offset-8 col-xs-4 col-xs-offset-8',
				containerFluid: true,				
				buttons: {
					tryAgain: {
						text: 'FECHAR',
						btnClass: 'btn-red',
						action: function(){
						}
					},					
					
				}
			});						
		
		var setint = setInterval(function(){
							
			for(var i = 0; i < data.length; i++){
					
					if(data[i].id_rel == '99999'){
							hide = 'hide';
					}/*else{
						hide = '';
					}*/				
					var selected = '<select id="vivorend_'+data[i].codigo_produto+'_'+i+'" class="vivorend vivorend_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][vivorend]" required>'+
										'<option value="">Selecione</option>'+
										'<option value="V">VIVO</option>'+
										'<option value="R">RENDIMENTO</option>'+
									'</select>';

					var formu = "";
					var lin   = '';

					if(data[i].tipo_r_v == 'V'){
						//var xvivo = "disabled";
						var xvivo = "";
						var xrend = "";
					}else if(data[i].tipo_r_v == 'R'){
						//var xrend = "disabled";
						var xrend = "";
						var xvivo = "";	
					}/*else{
						var xvivo = "disabled";
						var xrend = "disabled";
					}*/

					if(data[i].id_rel == ""){

						 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
									  "<input type='hidden' name='act' value='inserir'/>"+
									  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
									  "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
									  "<div class='input-prepend'>"+
											"<label><strong>Produto:</strong></label>"+
											"<span class='add-on fa fa-search'></span>"+
											"<input type='text' name='relprod' id='relprod' class='form-control'  />"+
											"<input type='hidden' name='idprodrel' id='idprodrel'/>"+
										"</div>"+
									  "<button class='btn btn-primary btn-block relaciona' type='button'>SALVAR</button>"+
									 "</form>"; 

						/* lin = '<button class="btn btn-mini btn-primary" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">Relacionar</button>';*/	
						
						 lin = '<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a>';
						
					}else{
						 formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
									  "<input type='hidden' name='act' value='alterar'/>"+
									  "<input type='hidden' name='id' value='"+data[i].idre+"'/>"+
									  "<input type='hidden' name='idprod' value='"+data[i].codigo_produto+"'/>"+
									   "<input type='hidden' name='iddet' value='"+data[i].id+"'/>"+
									  "<div class='input-prepend'>"+
											"<label><strong>Produto:</strong></label>"+
											"<span class='add-on fa fa-search'></span>"+
											"<input type='text' name='relprod' id='relprod' class='form-control' value='"+data[i].descricao+"'/>"+
											"<input type='hidden' name='idprodrel' id='idprodrel' value='"+data[i].id_rel+"'/>"+
										"</div>"+
									  "<button class='btn btn-primary btn-block relaciona' type='button'>ALTERAR</button>"+
									 "</form>";

						 /*lin = '<a href="#" data-toggle="popover" data-placement="right"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">'+data[i].descricao+' <i class="fa fa-pencil" aria-hidden="true"></i></a>';*/
						
						lin = '<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+data[i].codigo_produto+'|'+data[i].id+'" data-title="Enter">'+data[i].descricao+'</a>';
						
					}
							//tipo

					if(data[i].tipo == 'S'){
						htm = '<tr id="'+data[i].id+'" class="trshow">'+
							'<td style="text-align:center;">'+data[i].codigo_produto+'<input type="hidden" id="cprod_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][cprod]" value="'+data[i].codigo_produto+'" /></td>'+
							'<td>'+data[i].desc_produto+'</td>'+
							'<td tyle="text-align:center;">'+lin+'</td>'+
							'<td class="text-right"><input type="text" id="preco_quilo_'+data[i].codigo_produto+'_'+i+'" class="preco_quilo span1 preco_quilo_'+data[i].codigo_produto+' form-control" name="item['+i+'][preco_quilo]" style="text-align:right;margin: auto;width: 100%;" value="'+data[i].preco_quilo+'" /></td>'+
							'<td style="text-align:right;"><input type="text" id="qtd_prod_'+data[i].codigo_produto+'_'+i+'" class="qtd_prod span1 qtd_prod_'+data[i].codigo_produto+' form-control" name="item['+i+'][qtd_prod]" style="text-align:right;margin: auto;width: 100%;" value="'+number_format(data[i].qtd_prod,2,",",".")+'" /></td>'+							
							'<td class="text-right"><input class="form-control text-right" type="text" id="subtotal_'+data[i].codigo_produto+'_'+i+'" placeholder="'+data[i].subtotal+'" readonly=""></td>'+							
							'<td class="text-center"><input type="hidden" id="id_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][id]" value="'+data[i].id+'" /><input type="hidden" id="qCom_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][qCom]" value="'+data[i].qCom+'" /><a href="#" class="btnremoveitemnotasaida" data-id="item_'+data[i].id+'"><i class="fa fa-trash-o fa-2x"></i></a></td>'+						  
						'</tr>';
					}else{
						htm = '<tr id="'+data[i].id+'" class="trshow">'+
							'<td style="text-align:center;">'+data[i].codigo_produto+'<input id="cprod_'+data[i].codigo_produto+'_'+i+'" type="hidden" name="item['+i+'][cprod]" value="'+data[i].codigo_produto+'" /></td>'+
							'<td style="width:25%;">'+data[i].desc_produto+'</td>'+
							'<td style="width:18%;">'+lin+'</td>'+
							'<td style="text-align:right;"><input type="text" id="qtd_prod_'+data[i].codigo_produto+'_'+i+'" class="qtd_prod span1 qtd_prod_'+data[i].codigo_produto+' form-control" name="item['+i+'][qtd_prod]" style="text-align:right;margin: auto;" value="'+number_format(data[i].qtd_prod,2,",",".")+'" /></td>'+
							'<td class="text-right"><input type="text" id="preco_quilo_'+data[i].codigo_produto+'_'+i+'" class="preco_quilo span1 preco_quilo_'+data[i].codigo_produto+' form-control" name="item['+i+'][preco_quilo]" style="text-align:right;margin: auto;" value="'+data[i].preco_quilo+'" /></td>'+
							'<td class="text-right"><input class="form-control text-right" type="text" id="subtotal_'+data[i].codigo_produto+'_'+i+'" placeholder="'+data[i].subtotal+'" readonly=""></td>'+							
							'<td><input type="text" id="ncabeca_'+data[i].codigo_produto+'_'+i+'" class="ncabeca span1 ncabeca_'+data[i].codigo_produto+' '+hide+' form-control" name="item['+i+'][ncabeca]" style="text-align:center;margin: auto;" value="'+data[i].qtd_cabeca+'" required/></td>'+
							'<td><input type="text" id="npesocarcaca_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][npesocarcaca]" class="form-control span1 npesocarcaca npesocarcaca_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;margin: auto;float: right;" value="'+data[i].peso_carcasa+'" '+xrend+'/></td>'+
							'<td><input type="text" id="npesovivo_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][npesovivo]" class="form-control span1 npesovivo npesovivo_'+data[i].codigo_produto+' '+hide+'" style="text-align:right;margin: auto;float: right;" value="'+data[i].pesovivo+'" '+xvivo+'/></td>'+
							'<td>'+selected+'</td>'+
							'<td>'+
							'<input type="text" id="dtabate_'+data[i].codigo_produto+'_'+i+'" class="dtabate dtabate_'+data[i].codigo_produto+' span2 '+hide+' form-control" name="item['+i+'][dtabate]" style="text-align:center;margin: auto;float: right;" value="'+data[i].data_abate+'" required/>'+
							'<input type="hidden" id="id_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][id]" value="'+data[i].id+'" /><input type="hidden" id="qCom_'+data[i].codigo_produto+'_'+i+'" name="item['+i+'][qCom]" value="'+data[i].qCom+'" />'+
							'</td>'+
							'<td data-toggle="tooltip" data-tooltip="tooltip" title="Clique aqui para mais detalhes!"></td>'+
							'<td class="text-center"><a href="#" class="btnremoveitemnotaentrada" data-id="item_'+data[i].id+'"><i class="fa fa-trash-o fa-2x"></i></a></td>'+							  
						'</tr>';
					}

					

						$("#dyntable_notasprodutos tbody").append(htm);					

						$('select[name="item['+i+'][vivorend]"] option[value="'+data[i].tipo_r_v+'"]').attr('selected', 'selected');

			}														
			
			$('#dyntable_notasprodutos').footable();
			
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

					 if(response[0].idprodrel != '99999'){ 	
						 $(".ncabeca_"+response[0].idprod+"").show();
						 $(".npesocarcaca_"+response[0].idprod+"").show();
						 $(".npesovivo_"+response[0].idprod+"").show();
						 $(".dtabate_"+response[0].idprod+"").show();
						 $(".vivorend_"+response[0].idprod+"").show();
					 }else{
						  $(".ncabeca_"+response[0].idprod+"").hide();
						 $(".npesocarcaca_"+response[0].idprod+"").hide();
						 $(".npesovivo_"+response[0].idprod+"").hide();
						 $(".dtabate_"+response[0].idprod+"").hide();
						 $(".vivorend_"+response[0].idprod+"").hide();							
					 }
					 
					 var iset = setInterval(function(){
						clearInterval(iset);
						valida_nota_novamente();						
					 },600);
			}
		});
			
			clearInterval(setint);
		},900);
		//$("#boxprodnota").hide();
	},
	error:function(data){
		//retorno.close();
		$("#myModal_pdf").hide();
		alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
	}		
});
	
return false;
}

$(document).on('click', '.btnremoveitemnotasaida',function(e){
	e.preventDefault();
	
	var id = $(this).attr('data-id').split('_')[1];
	console.log(id);
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/notascopetencia-exec.php",
		data:{act:"removeitemsaida",id:id},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde removendo item..!");
		},
		success: function(data){
			$("#myModal_pdf").hide();
			$('#dyntable_notasprodutos tbody tr[id="'+data[0].id+'"]').remove();										
		},
		error:function(data){			
			$("#myModal_pdf").hide();
		}		
	});
		
	return false;

});


$(document).on('click', '.btnremoveitemnotaentrada',function(e){
	e.preventDefault();
	
	var id = $(this).attr('data-id').split('_')[1];
	console.log(id);
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/notascopetencia-exec.php",
		data:{act:"removeitementrada",id:id},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde removendo item..!");
		},
		success: function(data){
			$("#myModal_pdf").hide();
			$('#dyntable_notasprodutos tbody tr[id="'+data[0].id+'"]').remove();										
		},
		error:function(data){			
			$("#myModal_pdf").hide();
		}		
	});
		
	return false;


});	

$(document).on('change','select[name="tpptform"]',function(){

	if($(this).val()){
		var compt  = $('input[name="mesanocomp"]').val();
		var tpform = $(this).val();
		console.log($('input[name="numeronota"]').val());
		$.ajax({
			type:'POST',
			cache:false, 
			dataType: "json",
			url:"../php/lancamentos-exec.php",
			data:{act:'atupt',tipo:$(this).val(),cod:$('input[name="numeronota"]').val(),mesano:compt},
			beforeSend: function(){
				$("#myModal_pdf").show();
			   $(".modal-body_PDF p").html("Aguarde Alterando..!");
			},
			success: function(data){
				$("#myModal_pdf").hide();	
				alert(data[0].msg);
				
				$("#"+$('input[name="numeronota"]').val()+"_tpabate").val(tpform);
			},
			error:function(data){	
				$("#myModal_pdf").hide();		
				alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
			}		
		});
	}else{
		alert("Não pode ser vazio!");
	}

});
$(document).on('click','.relaciona',function(){
	
	 var params = $("form[id='relaciona']").serialize();
	
	 $.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/relaciona-exec.php",
		data:params,
		beforeSend: function(){
			
		},
		success: function(data){
			$('[data-toggle="popover"]').popover('hide');	
			var formu = "<form method='post' action='relaciona-exec.php' id='relaciona'>"+
						  "<input type='hidden' name='act' value='alterar'/>"+
						  "<input type='hidden' name='id' value='"+data[0].codigo+"'/>"+
						  "<input type='hidden' name='idprod' value='"+data[0].idprod+"'/>"+
						   "<input type='hidden' name='iddet' value='"+data[0].iddet+"'/>"+
						  "<div class='input-prepend'>"+
								"<label><strong>Produto:</strong></label>"+
								"<span class='add-on fa fa-search'></span>"+
								"<input type='text' name='relprod' id='relprod' class='form-control' value='"+data[0].relprod+"'/>"+
								"<input type='hidden' name='idprodrel' id='idprodrel' value='"+data[0].idprodrel+"'/>"+
							"</div>"+
						  "<button class='btn btn-primary btn-block relaciona' type='button'>ALTERAR</button>"+
						 "</form>";
						 
			var  lin = '<a href="#" data-toggle="popover" data-placement="top"  data-html="true" data-content="'+formu+'" title="" data-original-title="Relacionar" type="button">'+data[0].relprod+' <i class="fa fa-pencil" aria-hidden="true"></i></a>';	
			
			$("#dyntable_notasprodutos tbody tr[id='"+data[0].iddet+"'] td:eq(5)").html(lin);
			
			
			
		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;	

});

$(document).on('mouseover','[data-toggle="popover"]',function(){
	$(this).popover();
});


$(document).on('focus','.qtd_prod',function(){
	$(this).maskMoney({
		decimal:",",
		thousands:"."			
   });
});

$(document).on('keyup','.qtd_prod',function(){

	var id       = $(this).attr('id').split('qtd_prod')[1];
	var qrdprod  = convertevalores($(this).val());
	var preco    = convertevalores($('#preco_quilo'+id+'').val());
	var subtotal = (parseFloat(preco) * parseFloat(qrdprod));
	var tpvr     = $("#vivorend"+id+" option:selected").val();

	if(tpvr == 'V'){
		$("#npesovivo"+id+"").val(number_format(qrdprod,2,',','.'));
	}else{
		$("#npesocarcaca"+id+"").val(number_format(qrdprod,2,',','.'));
	}

	$('#subtotal'+id+'').attr('placeholder',number_format(subtotal,2,',','.'));	

});

$(document).on('keyup','.npesovivo',function(){

	var id       = $(this).attr('id').split('npesovivo')[1];
	var qrdprod  = convertevalores($(this).val());
	var preco    = convertevalores($('#preco_quilo'+id+'').val());
	var subtotal = (parseFloat(preco) * parseFloat(qrdprod));
	var tpvr     = $("#vivorend"+id+" option:selected").val();
	
	if(tpvr == 'V'){
		$("#qtd_prod"+id+"").val(number_format(qrdprod,2,',','.'));	
		$('#subtotal'+id+'').attr('placeholder',number_format(subtotal,2,',','.'));	
	}else{
		$('#npesovivo'+id+'').val('');
		alert('Para alterar Kg Vivo a opção vivo ou redimento tem que estar selecionado Vivo!');
	}

});

$(document).on('keyup','.npesocarcaca',function(){

	var id       = $(this).attr('id').split('npesocarcaca')[1];
	var qrdprod  = convertevalores($(this).val());
	var preco    = convertevalores($('#preco_quilo'+id+'').val());
	var subtotal = (parseFloat(preco) * parseFloat(qrdprod));
	var tpvr     = $("#vivorend"+id+" option:selected").val();
	
	if(tpvr == 'R'){
		$("#qtd_prod"+id+"").val(number_format(qrdprod,2,',','.'));	
		$('#subtotal'+id+'').attr('placeholder',number_format(subtotal,2,',','.'));	
	}else{
		$('#npesocarcaca'+id+'').val('');
		alert('Para alterar Kg Carcaça a opção vivo ou redimento tem que estar selecionado Rendimento!');
	}

});

$(document).on('focus','.preco_quilo',function(){
	$(this).maskMoney({
		decimal:",",
		thousands:"."			
   });
});

$(document).on('keyup','.preco_quilo',function(){
	var id       = $(this).attr('id').split('preco_quilo')[1];
	var qrdprod  = convertevalores($('#qtd_prod'+id+'').val());
	var preco    = convertevalores($(this).val());
	var subtotal = (parseFloat(preco) * parseFloat(qrdprod));
	
	$('#subtotal'+id+'').attr('placeholder',number_format(subtotal,2,',','.'));	

});


$('body').on('focus',".dtabate", function(){
    $(this).datepicker({
		dateFormat: 'dd/mm/yy',		
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
			        ],
			    dayNamesMin: [
			    'D','S','T','Q','Q','S','S','D'
			    ],
			    dayNamesShort: [
			    'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
			    ],
			    monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
			    'Outubro','Novembro','Dezembro'
			    ],
			    monthNamesShort: [
			    'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
			    'Out','Nov','Dez'
			    ],
			    nextText: 'Próximo',
			    prevText: 'Anterior'
	});
	$(this).mask("99/99/9999");

});

$(document).on('keyup','#relprod',function(e){
		
	$(this).autocomplete(
	{	
	 source:'../php/produto-exec.php?act=busca',
	 minLength: 1,
	select: function(event, ui) {
			
		$("#idprodrel").val(ui.item.cod);	
			
	},
	focus: function( event, ui ) {
		
		
	}	
	});

});


$(document).on('click','.deletenotas',function(e){
	
	var nnota  = $(this).parents('tr').attr('id');
	var entsai = $("#dyntable_notas tbody tr[id='"+nnota+"'] td:eq(3)").html();
	
	var confr = confirm('Deseja Realmente Excluir?');		
	if(confr == true){		
		$.ajax({
			type:'POST',
			cache:false, 
			dataType: "json",
			url:"../php/lancamentos-exec.php",
			data:{act:'excluir',cod:nnota,entsai:entsai},
			beforeSend: function(){
				
			},
			success: function(data){
					
				alert(data[0].msg);	
				$("#dyntable_notas tbody tr[id='"+data[0].id+"']").remove();				
				$("#numeronota").val('');							
				$("#filenames").val('');
				$("#dadosprodnota").hide();				
				$("#filenames").val('');
			},
			error:function(data){			
				alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
			}		
		});
	}
	return false;	
		
});

function removerarquivopasta(){

	var retorno;
	
	$.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'eliminaarquivos'},		
		success: function(data){
				
			retorno = 'ok';
		},
		error:function(data){	
			retorno = false;		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return retorno;			

}

function deletanotas(){
	
	
	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'excluirnotas'},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF img").show();
			$(".modal-body_PDF p").html("Aguarde Deletando!");			
		},
		success: function(data){
				
			//aqui insere novamente na grid
			$("#myModal_pdf").hide();
			var notas  = new Array();
			
			for(var i =0; i < data.length; i++){				
				notas.push(data[i].caminho);				
			}
						
			//regravanota(notas);
			var pos = $('.num_erros').position().top;
		    $('html, body').animate({
		        scrollTop: pos		
		    }, 1000);
		},
		error:function(data){	
			$("#myModal_pdf").hide();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;			
	
}

function deletanotasdesconsiderada(cfop,mesano){
		
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

function regravanota(dt){
	

	$.ajax({
		url: '../php/lancamentos-exec.php',
		type: 'POST',
		data: {act:'regrava',filenames:dt},
		cache: false,
		dataType: 'json',
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde regravando isso pode demorar alguns minutos!");			
		},
		success: function(data, textStatus, jqXHR)
		{
			
			
			var htm = "";				
			for(var i = 0; i < data.length; i++){
				
				var marc = "";
				
				if(data[i].entsai == 'E'){
					var dts = '<a href="#" class="details"><span class="fa fa-pencil-square fa-2x"></span></a>';
					marc    = "entrada";
										
				}else{
					var dts = '';
				}
				
				htm += '<tr id="'+data[i].Numero+'" data-caminho="'+data[i].caminho+'" class="warning">'+
						  '<td>'+data[i].Numero+'</td>'+
						  '<td>'+data[i].dEmi+'</td>'+
						  '<td style="width: 33%">'+data[i].chave+'</td>'+
						  '<td style="text-align:center; width: 7%;">'+data[i].entsai+'</td>'+
						  '<td>'+data[i].cliente+'</td>'+
						  '<td style="text-align:right; width: 10%;">'+data[i].valor+'</td>'+
						  '<td  class="centeralign '+marc+'" style="text-align: right">'+
							''+dts+''+
							'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';		
				
			}
			
							
			
			$("#dyntable_notas tbody").append(htm);
			
		$dTable = $('#dyntable_notas').dataTable({					
						 "bSort" : false,
						 "paging":   false,
						 "ordering": true,
						 "info":     false,
						 "bDestroy": true,
						 "bFilter": true
			});
			$("#myModal_pdf").hide();
			balao.close();
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			$("#myModal_pdf").hide();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!\r\n"+jqXHR.responseText);
			console.log('ERRORS: ' + textStatus);
		},
		complete: function()
		{
			// STOP LOADING SPINNER
		}
	});
	
		return false;
}


    // Crio uma variável chamada $forms que pega o valor da tag form	
	$(document).on('submit','form[id="frmnotaxml"]', function(){			
	
		var params	   = $(this.elements).serialize();
		var serialized = $(this).serializeArray();
		var result 	   =  {items:[]};
		
		serialized.forEach(function(valor, chave){
			nameArray = valor.name.split(/[[\]]/);		
		    item = nameArray[1];
    		prop = nameArray[3];
			if(item != undefined && item.trim() != ''){
				if(typeof result.items[item] !== 'object'){
					result.items[item]={};
				}

				if(typeof result.items[item][prop] !== 'undefined'){
					//Consistency check the name attribute
					console.log('Warning duplicate "name" property =' + valor.name);
				}

				result.items[item][prop]=valor.value;
				//console.log(" v = "+valor.value);
				//console.log(item,prop);
			}
		
		});
		
		var xrro = "";
		if(serialized[1]['value'] == 'ENTRADA'){
			for (let index = 0; index < result.items.length; index++) {
				const element = result.items[index];
				//console.log(element.npesovivo);
				if(element.ncabeca <= 0){
					xrro += ' - Falta informar quantidade de cabeças na linha do produto: '+element.cprod+'';
					$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
				}

				if(element.ncabeca > 300){
					xrro += ' - quantidade de cabeças na linha do produto: '+element.cprod+' esta Incorreta!';
					$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
				}
				
				if(element.vivorend == 'V'){
					//console.log('vivo');
					if(parseFloat(convertevalores(element.npesovivo)) <= 0){
						xrro += ' - Falta informar Kg Vivo na linha do produto: '+element.cprod+'<br/>';
						$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
					}
					if(isNaN(parseFloat(convertevalores(element.npesovivo)))){
						xrro += ' - Falta informar Kg Vivo na linha do produto: '+element.cprod+'<br/>';
						$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
					}
					
				}else if(element.vivorend == 'R'){
					//console.log('rendimento');
					if(parseFloat(element.npesocarcaca) <= 0){
						xrro += ' - Falta informar Kg Carcaça na linha do produto: '+element.cprod+'<br/>';
						$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
					}

					if(isNaN(parseFloat(convertevalores(element.npesocarcaca)))){
						xrro += ' - Falta informar Kg Carcaça na linha do produto: '+element.cprod+'<br/>';
						$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
					}

				}else{
					xrro += ' - Falta informar se é Vivo ou Rendimento: '+element.cprod+'<br/>';
					$("#dyntable_notasprodutos tr[id='"+element.id+"']").addClass('bg-warning');
				}
				if(xrro != ''){
					xrro +='<br/>------------------------------------------------------<br/>';
				}
			}
		}
		if(xrro != ''){
			//alert(xrro);
			$.alert({
				title: 'Correções!',
				content: ''+xrro+'',
				columnClass: 'col-md-8 col-md-offset-4',
			});
			return false;
		}

		
		var self = this;
		
		/*if($(".entrada").length == 0){			
			window.location.reload();						
		}*/
		
		$.ajax({
			type: 'POST',
			 url: this.action,
			data: params,
			dataType: "json",
			// Antes de enviar
			beforeSend: function(){
			
				
			},
			success: function(data){
								
				var x = 0; 
				
				for(i in data.result){
				
					if(data.result[i].msg == ""){
						//$("#dyntable_notasprodutos tbody tr[id='"+data.result[i].id+"']").remove();							
						x++;
					}else{
						alert(data.result[i].msg);
					}
										
				}				
				
				for(y in data.result){
					
					if(parseFloat(data.result[y].ncabeca) > 0 ){
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").removeClass('text-danger');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").addClass("text-success");
					}
					
					if(parseFloat(data.result[y].pesocarcasa) > 0){
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").removeClass('text-danger');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").addClass("text-success");
					}

					if(parseFloat(data.result[y].pesovivo) > 0){
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").removeClass('text-danger');
						$("a[data-id='nota_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").addClass("text-success");
					}


					if(data.result[y].vivorend != ""){
						$("a[data-id='notavr_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='notavr_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").removeClass('text-danger');
						$("a[data-id='notavr_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").addClass("text-success");
						
					}
					
					if(data.result[y].dtabate != "" && data.result[y].dtabate != "0000-00-00"){

						$("a[data-id='notadtabate_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='notadtabate_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").removeClass('text-primary');
						$("a[data-id='notadtabate_"+data.result[y].numnota+"_"+data.result[y].cprod+"']").addClass("text-success");

					}

				}
				if(serialized[1]['value'] =='ENTRADA'){
					atualizaGridEntradas();
				}else{
					atualizaGridSaida();
				}
				
				
				if(x == data.result.length){
					
					//$("#dyntable_notas tbody tr[id='"+data.numeronota+"']").removeClass('info');				
					//$("#dyntable_notas tbody tr[id='"+data.numeronota+"']").addClass('success');					
					//$("#dyntable_notas tbody tr[id='"+data.numeronota+"'] td:eq(5)").html('<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>');
					//$("#dyntable_notas tbody tr[id='"+data.numeronota+"'] td:eq(6)").removeClass("entrada");
					
					$("#numeronota").val('');
					$("#filenames").val('');							
					$("#dadosprodnota").hide();
					$("input[name='mostranovaapuracao']").val(0);
					balao2.close();
					
					/*if($(".entrada").length == 0){
						window.location.reload();
					}*/
					
				}
			},
			error: function(data){
				
			}
		});
		return false;
	});



$(document).ready(function (e) {
	
	$('.dyntable_notas').dataTable({
		 "bSort" : false,
		 "paging":   false,
		 "ordering": false,
		 "info":     true,
		 "bDestroy": true,
		 "bFilter": true,
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
        },
	});
	
	$('.dtcomp').hide();
	$('#dadosnotas').hide();
	$('.valid_erros').hide();
	$(".valid_infos").hide();
	$(".validacaoarquivos").hide();
	
	$("input[id='file_upload']").on('click',(function(e){
		//e.preventDefault();
		var dtmesano = $('input[name="mesanocomp"]').val();
		if(dtmesano == ""){
			alert("Selecionar o Mês e ano da competencia !");
			$('input[name="mesanocomp"]').focus();
			return false;
		}
	}));

	$("input[id='file_upload']").on('change', (function (e) {
				
		var myfiles = document.getElementById("file_upload");
		var files = myfiles.files;
		var data = new FormData();
		
		removerarquivopasta();

		if(files.length == 0){
			return false;
		}
		
		var dtmesano = $('input[name="mesanocomp"]').val();
		
		//validamesanocometencia(dtmesano);

		if(dtmesano == ""){
			alert("Selecionar o Mês e ano da competencia !");
			$('input[name="mesanocomp"]').focus();
			return false;
		}

		for (i = 0; i < files.length; i++) {
			data.append(i, files[i]);
		}

		data.append("dtmesano",dtmesano);	
		data.append("act","calculadados");

		//alert(data);
		e.preventDefault();
		$.ajax({
			url: "../php/lancamentos-exec.php",
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
				
				if(arr.erro == ''){		
					
					$('.dtcomp').show();
					
					$(".num_entradas").html(arr.dados[0].numero_entradas);
					$(".num_saida").html(arr.dados[0].numero_saida);
					$(".val_entrada").html(arr.dados[0].total_entradas);
					$(".val_saida").html(arr.dados[0].total_saida);
					
					var conta = parseInt(arr.dados[0].numero_entradas) + parseInt(arr.dados[0].numero_saida);
										
				
					var htm = "";
					var htmnotadifcomp = "";
					var contanotadifcomp = 0;
					var dtcompdif = "";
					var msg2 = "";
					for(i in arr.dadosn){
						//tipo
						//dhEmi
						if(arr.dadosn[i].tipo == 1){
							contanotadifcomp++;
							dtcompdif = arr.dadosn[i].dhEmi;
							htmnotadifcomp +='<div class="alert alert-warning alert-rounded">'+
							'<i class="ti-user"></i>'+
								' '+arr.dadosn[i].msg+' => Número: '+arr.dadosn[i].nNF+' Nota de:'+arr.dadosn[i].ent_sai+' Chave: '+arr.dadosn[i].chave+' Data Competêcia: '+arr.dadosn[i].dhEmi+' '+
								'<button type="button" class="close" data-dismiss="alert" aria-labl="Close"> <span aria-hidden="true">×</span> </button>'+
							'</div>';
						}

						htm +='<div class="alert alert-warning alert-rounded">'+
							'<i class="ti-user"></i>'+
								' '+arr.dadosn[i].msg+' => Número: '+arr.dadosn[i].nNF+' Nota de:'+arr.dadosn[i].ent_sai+' Chave: '+arr.dadosn[i].chave+''+
								'<button type="button" class="close" data-dismiss="alert" aria-labl="Close"> <span aria-hidden="true">×</span> </button>'+
							'</div>';
						
					}
					
					if(conta == 0){
						//alert("XMLS INSERIDOS NÃO SÃO DESSA COMPETÊCIA INFORMADA , TENTE NOVAMENTE COM OS XMLS CORRETOS!");
						/*$('#upload').each(function(){
						  this.reset();
						});*/
						//$('input[name="mesanocomp"]').focus();
					//	$('.dtcomp').hide();	

						//msg2 = "Analisei e a compentecia dos xml são da competência "+dtcompdif+", ";
						
					}

					if(contanotadifcomp > 0){

						$.confirm({
							title: 'Mensagem do sistema!',
							content: '<h3>Existem Notas que não são dessa competência ('+dtmesano+'), '+msg2+' deseja realmente importar mesmo assim ? </h3><br> <div style="height:500px;overflow:auto;">'+htmnotadifcomp+'</div>',
							type: 'orange',
							typeAnimated: true,
							columnClass: 'col-md-9',
							buttons: {
								sim: {
									text: 'Sim',
									btnClass: 'btn-green',
									action: function(){
										/*if(conta == 0){
											$('input[name="mesanocomp"]').val(dtcompdif);
											$('input[name="setmesanocomp"]').val(dtcompdif);
											$('input[name="mesanocomp"]').focus();	
																					
											removeCompetenciaSemNada();	
										}*/

										reenviacompetenciaquenaoedamesma();										
									}
								},
								nao: {
									text: 'Não',
									btnClass: 'btn-red',
									action: function(){

										if(conta == 0){
											$('#upload').each(function(){
												this.reset();
											});
											$('input[name="mesanocomp"]').focus();
											$('.dtcomp').hide();	
											removeCompetenciaSemNada();	
										}
										grava_valida_nota(2);
									}
								},
							}
						});
						return false;
					}else{
						grava_valida_nota(3);
					}

					if( arr.dadosn.length > 0){
						$(".notificacaosn").html(htm);
						$("#notificaimp").removeClass('hide');
					}

					
					
				}
				
				
				
				
								
			},
			error: function () {
				$("#myModal_pdf").hide();
			}
		});
	}));
	
	$(document).on('blur','input[name="mesanocomp"]',function(){

		var mesano = $(this).val();
		$('input[name="setmesanocomp"]').val(mesano);
		
		var hr  = window.location.href;
		var hrm = hr.split('?');
		var msa = $('input[name="setmesanocomp"]').val();		
		window.history.pushState( null, null, hrm[0]+'?act=Validado&anomes='+mesano+'');	
		
		validamesanocometencia(mesano);

	});

	
	$(".filtro_entsai").click(function(){
		
		var value = $(this).attr('data-id');
		$('#dadosnotas').show();
		if(value == 1){
			
			$dTable.api().columns(3).search("ENTRADA").draw();
		}else if (value == 2){
			$dTable.api()
				.columns( 3 )
				.search('SAIDA')
				.draw();
		}else{
			$dTable.api()
				.columns( 3 )
				.search('')
				.draw();
		}
		
	});
	
	
	
});

function reenviacompetenciaquenaoedamesma(){
	var myfiles = document.getElementById("file_upload");
		var files = myfiles.files;
		var data = new FormData();
		
		removerarquivopasta();

		if(files.length == 0){
			return false;
		}
		
		var dtmesano = $('input[name="mesanocomp"]').val();
		
		//validamesanocometencia(dtmesano);

		if(dtmesano == ""){
			alert("Selecionar o Mês e ano da competencia !");
			$('input[name="mesanocomp"]').focus();
			return false;
		}

		for (i = 0; i < files.length; i++) {
			data.append(i, files[i]);
		}

		data.append("dtmesano",dtmesano);	
		data.append("act","calculadados");
		data.append("dif","1");
		//alert(data);		
		$.ajax({
			url: "../php/lancamentos-exec.php",
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
				
				if(arr.erro == ''){		
					
					$('.dtcomp').show();
					
					$(".num_entradas").html(arr.dados[0].numero_entradas);
					$(".num_saida").html(arr.dados[0].numero_saida);
					$(".val_entrada").html(arr.dados[0].total_entradas);
					$(".val_saida").html(arr.dados[0].total_saida);
					
					var conta = parseInt(arr.dados[0].numero_entradas) + parseInt(arr.dados[0].numero_saida);
					
					if(conta == 0){
						alert("XMLS INSERIDOS NÃO SÃO DESSA COMPETÊCIA INFORMADA , TENTE NOVAMENTE COM OS XMLS CORRETOS!");
						$('#upload').each(function(){
						  this.reset();
						});
						$('input[name="mesanocomp"]').focus();
						$('.dtcomp').hide();	
						removeCompetenciaSemNada();	
					}

				
					var htm = "";
					var htmnotadifcomp = "";
					var  contanotadifcomp = 0;
					for(i in arr.dadosn){
						//tipo
						
						if(arr.dadosn[i].tipo == 1){
							contanotadifcomp++;
							htmnotadifcomp +='<div class="alert alert-warning alert-rounded">'+
							'<i class="ti-user"></i>'+
								' '+arr.dadosn[i].msg+' => Número: '+arr.dadosn[i].nNF+' Nota de:'+arr.dadosn[i].ent_sai+' Chave: '+arr.dadosn[i].chave+''+
								'<button type="button" class="close" data-dismiss="alert" aria-labl="Close"> <span aria-hidden="true">×</span> </button>'+
							'</div>';
						}

						htm +='<div class="alert alert-warning alert-rounded">'+
							'<i class="ti-user"></i>'+
								' '+arr.dadosn[i].msg+' => Número: '+arr.dadosn[i].nNF+' Nota de:'+arr.dadosn[i].ent_sai+' Chave: '+arr.dadosn[i].chave+''+
								'<button type="button" class="close" data-dismiss="alert" aria-labl="Close"> <span aria-hidden="true">×</span> </button>'+
							'</div>';
						
					}
					
					if(contanotadifcomp > 0){

						$.confirm({
							title: 'Mensagem do sistema!',
							content: '<h3>Existem Notas que não são dessa competência ('+dtmesano+'), deseja realmente importar mesmo assim ? </h3><br> <div style="height:500px;overflow:auto;">'+htmnotadifcomp+'</div>',
							type: 'orange',
							typeAnimated: true,
							columnClass: 'col-md-9',
							Sim: {
								sim: {
									text: 'Sim',
									btnClass: 'btn-green',
									action: function(){
										
									}
								},
								nao: {
									text: 'Não',
									btnClass: 'btn-red',
									action: function(){
									}
								},
							}
						});

					}

					if( arr.dadosn.length > 0){
						$(".notificacaosn").html(htm);
						$("#notificaimp").removeClass('hide');
					}

					grava_valida_nota(1);
					
				}
				
				
				
				
								
			},
			error: function () {
				$("#myModal_pdf").hide();
			}
		});
}

function removeCompetenciaSemNada(){
		
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'removecompetencia'},
		beforeSend: function(){
				
		},
		success: function(data){							
			console.log(data[0].msg);				
		},
		error:function(data){					
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
	
}

function validamesanocometencia(mesano){
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
				
				if(data[0].tipo == '1'){
					dmsg.close();
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
									$("input[id='file_upload']").click();
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
					dmsg.close();
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
					dmsg.close();								
					var gm = document.querySelector('#agresemmovi').checked;
					if(gm == true){
						GeraSemMovimento();
					}else{						
						$("input[id='file_upload']").click();
					}
				}
			},
			error:function(data){	
					
				alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
			}		
		});
	}	

	return false;

}

$(document).on('click','.deletexmlnotas',function(){
	
	var caminho = $(this).parents('tr').attr('data-caminho');
	var codigo	= $(this).parents('tr').attr('id');
	var tr		= $(this).parents('tr');
	
	
	//alert(caminho+' - '+codigo);
	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'removexml',caminho:caminho},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde Deletando!");					
		},
		success: function(arr){							
			$("#myModal_pdf").hide();
			var htm  = "";
			//alert($(this).parents('tr').attr('data-caminho'));
			$dTable.api().row(tr).remove().draw();
						
			if(arr.erro == ''){		
					
					$('.dtcomp').show();
					
					$(".num_entradas").html(arr.dados[0].numero_entradas);
					$(".num_saida").html(arr.dados[0].numero_saida);
					$(".val_entrada").html(arr.dados[0].total_entradas);
					$(".val_saida").html(arr.dados[0].total_saida);
					
					var conta = parseInt(arr.dados[0].numero_entradas) + parseInt(arr.dados[0].numero_saida);
					
					if(conta == 0){
						alert("XMLS INSERIDOS NÃO SÃO DESSA COMPETÊCIA INFORMADA , TENTE NOVAMENTE COM OS XMLS CORRETOS!");
						$('#upload').each(function(){
						  this.reset();
						});
						$('input[name="mesanocomp"]').focus();
						$('.dtcomp').hide();	

					}

					for(i in arr.dados_grid){
						
						htm += '<tr id="'+arr.dados_grid[i].nNF+'" data-caminho="'+arr.dados_grid[i].caminho+'" class="warning tbimportado">'+
								  '<td>'+arr.dados_grid[i].nNF+'</td>'+
								  '<td>'+arr.dados_grid[i].dhEmi+'</td>'+
								  '<td style="width: 33%">'+arr.dados_grid[i].chave+'</td>'+
								  '<td style="text-align:center; width: 7%;">'+arr.dados_grid[i].ent_sai+'</td>'+
								  '<td>'+arr.dados_grid[i].cli_for+'</td>'+
								  '<td style="text-align:right; width: 10%;">'+arr.dados_grid[i].valor_nota+'</td>'+
								  '<td  class="centeralign style="text-align: right">'+									
									'<a href="#" class="deletexmlnotas"><span class="fa fa-remove fa-2x"></span></a>'+
								  '</td>'+
								'</tr>';
						
					}
					
				   
					
					$("#dyntable_notas tbody").html(htm);
					
					$dTable = $('#dyntable_notas').dataTable({					
						 "bSort" : false,
						 "paging":   false,
						 "ordering": true,
						 "info":     true,
						 "bDestroy": true,
						 "bFilter": true,
					     "bRetrieve": true,
						"language": {
							"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
						},
					});
					
				}
			
		},
		error:function(data){	
			$("#myModal_pdf").hide();	
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
		
	
});

$(document).on('click','.list_erros',function(){
	//$(".valid_erros").slideToggle();
	$(".valid_infos").hide();
	valida_nota_novamente();
	
	$.confirm({
		title: 'Detalhamento de Erros: <div class="form-group row pull-right"><div class="col-sm-12"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="text" class="form-control" placeholder="Buscar" id="autocompleteerros"></div></div></div>',
		content: '<div class="printer_content"><div class="titulovalidacao text-center hide"><div style="text-align:right; margin-right: 50px;">{dthj}</div><div style="font-size: 18px; font-weight: bold;border: 1px #000000 solid;">VALIDAÇÕES AGREGAR</div><hr></div><div class="valid_erros" style="border-left: 5px solid #009efb;"></div></div>',
		type: 'red',
		typeAnimated: true,
		columnClass: 'col-md-12',
		containerFluid: true, // this will add 'container-fluid' instead of 'container'
		draggable: false,
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					$('[data-toggle="popover"]').popover('hide');
					$('.popover').popover('hide');
					//$('[role="tooltip"]').popover('hide');
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
$(document).on('click','.list_infos',function(){
	//$(".valid_infos").slideToggle();
	$(".valid_erros").hide();
	valida_nota_novamente();
	$.confirm({
		title: 'Detalhamento de Alertas: <div class="form-group row pull-right"><div class="col-sm-12"><div class="input-group"><span class="input-group-addon"><i class="fa fa-search"></i></span><input type="text" class="form-control" placeholder="Buscar" id="autocompletealert"></div></div></div>',
		content: '<div class="printer_content"><div class="titulovalidacao text-center hide"><div style="text-align:right; margin-right: 50px;">{dthj}</div><div style="font-size: 18px; font-weight: bold;border: 1px #000000 solid;">VALIDAÇÕES AGREGAR</div><hr></div><div class="valid_infos" style="border-left: 5px solid #009efb;"></div></div>',
		type: 'orange',
		typeAnimated: true,
		columnClass: 'col-md-12',
		containerFluid: true, // this will add 'container-fluid' instead of 'container'
		draggable: false,
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					$('[data-toggle="popover"]').popover('hide');
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

	/*$.fn.dataTableExt.afnFiltering.push(
	function(oSettings, aData, iDataIndex){
		//alert(aData);
		
			var dateStart = parseDateValue($("#dataini").val());
			var dateEnd = parseDateValue($("#datafin").val());
			// aData represents the table structure as an array of columns, so the script access the date value 
			// in the first column of the table via aData[0]
			
			var evalDate= parseDateValue(aData[1]);
			
			if (evalDate >= dateStart && evalDate <= dateEnd) {
				return true;					
			}
			else {
				//alert('FALSE'+aData[1]);	
			    return false;
				
			}
		
	});*/

var url   		 = window.location.search.replace("?", "");
var pathArray2   = window.location.pathname.split('/');
var newPathname2 = "";
for (x = 0; x < pathArray2.length; x++) {
	newPathname2 += "/";
	newPathname2 += pathArray2[x];
	
	if(pathArray2[x] == 'importa_agregar_xml.php'){
		if(url != ""){		
			var items = url.split("&");
			var act   = items[0].split("=")[1];
			var comp  = items[1].split("=")[1];
		}
	}else if(pathArray2[x] == 'importa_agregar_txt.php'){
		if(url != ""){
			var items = url.split("&");
			var act   = items[0].split("=")[1];
			var comp  = items[1].split("=")[1];
		}
	}
}



$(document).ready(function(){
	var pathArray = window.location.pathname.split('/');
	var newPathname = "";
	for (i = 0; i < pathArray.length; i++) {
	  newPathname += "/";
	  newPathname += pathArray[i];
		//alert(pathArray[i]);
		if(pathArray[i] == 'importa_agregar_xml.php'){
			
			var patternData = /^[0-9]{2}\/[0-9]{4}$/;
			if(!patternData.test(comp)){
				 //alert("Digite a data no formato Dia/Mês/Ano");
				 //form_registra_entrada.dataentrada.focus();
				 return false;
			 }

			 var vld = ValidaMesAnoCompInicio(comp);

			if(url != ""){
		
				$('input[name="mesanocomp"]').val(comp);
				$('input[name="setmesanocomp"]').val(comp);
				
				if(vld[0].status == '8'){
					$.confirm({
						title: 'Mensagem do sistema',
						content: 'Competência já Recebida!, Recibo de número '+vld[0].protocolo+' já esta em processo, caso queira regerar entrar em contato com Agregar, Obrigado!',
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
				}

				if(vld[0].status == '7'){
					//grava_valida_nota();
					valida_nota_novamente();
				}
				if(vld[0].status == '10'){
					
					if(act == 'Apurado'){
						step.steps("next");			
					}
					
				}
				
				if(vld[0].status == '9'){
					if(act == 'Entregue'){
						step.steps("next");	
					}		
				}
				
			}
		}else if(pathArray[i] == 'importa_agregar_txt.php'){
			
			   var patternData = /^[0-9]{2}\/[0-9]{4}$/;
			   if(!patternData.test(comp)){
					//alert("Digite a data no formato Dia/Mês/Ano");
					//form_registra_entrada.dataentrada.focus();
					return false;
				}
				var vld = ValidaMesAnoCompInicio(comp);									
				
				if(url != ""){
			
					$('input[name="mesanocomptxt"]').val(comp);
					$('input[name="setmesanocomp"]').val(comp);
					
					if(vld[0].status == '8'){
						$.confirm({
						    title: 'Mensagem do sistema',
						    content: 'Competência já Recebida!, Recibo de número '+vld[0].protocolo+' já esta em processo, caso queira regerar entrar em contato com Agregar, Obrigado!',
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
					}

					if(vld[0].status == '7'){
						
						valida_nota_novamenteTXT($('input[name="setmesanocomp"]').val());
					}

					if(vld[0].status == '10'){
						if(act == 'Apurado'){
							step2.steps("next");			
						}							
						$('input[name="numeroerros"]').val(1);
						//valida_nota_novamenteTXTSession();				
					}
					
					if(vld[0].status == '9'){
						if(act == 'Entregue'){
							step2.steps("next");			
						}
					}
					
				}

		}
	}
	
	
	
	/*$(document).on("click", ".popover .close" , function(){
        $(this).parents(".popover").popover('hide');
	});*/
	
	$(document).on("click", ".close" , function(){
        $(this).parents(".popover").popover('hide');
	});

	
	
});

$('body').on('click' , '[rel="tooltip"]' , function(e){
    e.stopPropagation();

    var i = $(this);
    var thisPopover = $('.popoverClose').filter('[data-info-id="' +i.data('info-id')+ '"]').closest('.popover');        
    if( thisPopover.is(':visible') ){
        $('.popover').remove();
    }
    else{
        $(this).popover('show');
    }
});

var step = $(".tab-wizard").steps({
    headerTag: "h6"
    , bodyTag: "section"
    , transitionEffect: "fade"
    , titleTemplate: '<span class="step">#index#</span> #title#'
    , labels: {
        finish: "ENVIAR"
    }, onStepChanging: function (event, currentIndex, newIndex) {
		
		if(parseInt($(".num_erros").html()) > 0){
			alert("Existem ("+$(".num_erros").html()+") erros a ser corrigido! ");
			return false;
		}
		//alert((newIndex+1));
		if((newIndex+1) == 1){
			//$(".validacaoarquivos").hide();
			//deletanotas();
			var hr  = window.location.href;
			var hrm = hr.split('?');
			var msa = $('input[name="setmesanocomp"]').val();					
			window.history.pushState( null, null, hrm[0]+'?act=Validando&anomes='+msa+'');
			grava_valida_nota(3);
			return true;
		}
		/*if((newIndex+1) == 1){
			//deletanotas();			
			if($(".tbimportado").length > 0){					
				grava_valida_nota();
				return true;
			}else{
				return false;
			}		
		}			*/				
				
		if((newIndex+1) == 2){
			
			
			
			if($('input[name="setmesanocomp"]').val() == '__/____'){
				alert("Competencia com erros rever os seus XMLS e a resposta ao enviar seus XMLS");
				return false;
				
			}

			var gm = document.querySelector('#agresemmovi').checked;
			if(gm == true){
				var vl = $("input[name='prox']").val();
				
				if(vl == undefined || vl == ""){
					GeraSemMovimento();
					return false;
				}/*else if(vl == 'proximo'){
					
				}*/
			}
			//$("#myModal_pdf").hide();
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde verificando dados!");
					
				//aqui apuração
				var setval = setInterval(function(){
					var valid = validacaoproxpaso();

					if(valid == 0){
						apuracaodosdados();
						
						var hr  = window.location.href;
						var hrm = hr.split('?');
						var msa = $('input[name="setmesanocomp"]').val();					
						window.history.pushState( null, null, hrm[0]+'?act=Apurado&anomes='+msa+'');		
						
						clearInterval(setval);
						if(act == 'Entregue'){
							step.steps("next");
						}
					}else{
						clearInterval(setval);
						alert("Existem ("+valid+") erros a ser corrigido! ");
						step.steps("previous");
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
			gerarprotocolodeapauracao(1);
			return true;
		}
		
		
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

function sleep(milliseconds) {
	const date = Date.now();
	let currentDate = null;
	do {
	  currentDate = Date.now();
	} while (currentDate - date < milliseconds);
  }

function gerarprotocolodeapauracao(tipo){

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/apuracao-exec.php",
		data: {act:'protocolo',tipo:tipo},
		beforeSend: function(){
			
		},
		success: function(data){
			
			$(".codigoprotocolo").html("<img src='../images/sucess.png'/><br/> <div style='margin:10px; padding:10px; background-color: #90a4ae;color: #fff;'>PROTOCOLO NÚMERO:<br/>"+data[0].protocolo+"</div>");
			
		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;

}

function apuracaodosdados(){

	function setProgress(precis) {
        var progress = $('#progress');
       
        progress.toggleClass('active', precis < 100);

        progress.css({
            width: precis = precis.toPrecision(3)+'%'
        }).html('<span>'+precis+'</span>');
    }
    //var xload;

    $.ajax({
	  type: 'POST',
	  url: "../php/apuracao-exec.php",
	  data: {act:'apurar'},
	  beforeSend: function(XMLHttpRequest)
	  {
		$("#myModal_pdf").show();
		$(".modal-body_PDF p").html("Aguarde fazendo a apuração dos dados!");
	  },
	  success: function(data){
	    //Do something success-ish
	   // var arr = $.parseJSON(data);
			var arr = $.parseJSON(data);
			const apuraString = JSON.stringify(arr);
			localStorage.setItem('apuracao',apuraString);
           
			if(arr.tipolayout == 1){
            	var text  = ApuracaoSinteticoXml();
				$('.apura_layout').html('VER LAYOUT NOVO');
				$('.apura_layout').attr('data-id',2);
				$('.apura_layout').popover('show');
			}else{
				var text  = ApuracaoAnaliticaXml();
				$('.apura_layout').html('VOLTAR PARA O LAYOUT ANTIGO');
				$('.apura_layout').attr('data-id',1);
				$('.apura_layout').popover('hide');
			}	

		
			$('#content').html(text);
			
			$("#myModal_pdf").hide();
			$(".loader").remove();

			//xload.close();
	  }
	});

    

    return false;
}
function ApuracaoSinteticoXml(){
	const obj = localStorage.getItem("apuracao");
	const arr = JSON.parse(obj);

	var txt = "";
            
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
function ApuracaoAnaliticaXml(){

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
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format((parseFloat(data.vendars[0].saida) + parseFloat(data.vendars[0].devolucao2)),2,',','.')}</td>
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
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format((parseFloat(data.vendars2[0].saida) + parseFloat(data.vendars2[0].devolucao2)),2,',','.')}</td>
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
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format((parseFloat(data.vendasdifrs[0].saida) + parseFloat(data.vendasdifrs[0].devolucao2)),2,',','.')}</td>
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
					<td class=xl73 align=right style='border-top:none;border-left:none'>R$ ${number_format((parseFloat(data.vendasdifrs2[0].saida) + parseFloat(data.vendasdifrs2[0].devolucao2)),2,',','.')}</td>
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

$(document).on("submit","#frmcabecas",function(){
	
	var param = $(this).serialize();
	
	if (isNaN($('input[name="ncabecas"]').val())) {  
		alert("Digite apenas números!");  
		$('input[name="ncabecas"]').select();  
		return false;  
	 }

	 if($("input[name='ncabecas']").val().trim() == ''){
		var view = '<div class="message info">Digite um número de cabeça válido!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	if($("input[name='ncabecas']").val() <= 0){
		var view = '<div class="message info">Digite um número de cabeça válido!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}
	 
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			
			cabbox.close();
			$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"_"+data[0].idseq+"'] > i").remove();
			$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"_"+data[0].idseq+"']").append('<i class="fa fa-check text-success"></i>');
			$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"_"+data[0].idseq+"']").removeClass('text-danger');
			$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"_"+data[0].idseq+"']").addClass("text-success");
			
			if($("#validanumerocabeca li a[class='text-danger']").length == 0){
				$("#validavivorendimento").css({
					'opacity':'1',
					'pointer-events':'all'
				});
			}
			
		},
		error:function(data){	
			cabbox.close();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
	
});

$(document).on("submit","#frmvivorend",function(){
	
	var param = $(this).serialize();
	
	var vlderr       = $(".valid_erros ol li a[class='text-danger vivorendvalid']");		
	var arraynotavr  = vlderr['length'];
	var todos		 = false;
	var tipo         = "";
	var mesano 		 = $("input[name='setmesanocomp']").val();

	if(arraynotavr > 1){
		if(param.split('&')[5].split('=')[1].trim() == 'V'){
			tipo = '[V]ivo';
		}else{
			tipo = '[R]endimento';
		}
		todos = confirm('Deseja passa o tipo: ('+tipo+'), para os demais ? ');
	}
	
	if($('select[name="vivorend"] option:selected').val() ==''){
		var view = '<div class="message info">Selecione um tipo!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;		
	}

	if($('select[name="vivorend"] option:selected').val() == 'V'){
		if($("input[name='npesovivo']").val() <= 0){
			var view = '<div class="message info">Digite um valor de Peso Vivo valido!</div>';
			$(".form_callback").html(view);
			$(".message").effect("bounce");
			return false;		
		}
	}else if($('select[name="vivorend"] option:selected').val() == 'R'){
		if($("input[name='npesocarcaca']").val() <= 0){
			var view = '<div class="message info">Digite um valor de Peso Carcaça valido!</div>';
			$(".form_callback").html(view);
			$(".message").effect("bounce");
			return false;		
		}
	}
	

	var parametro = param+'&passa='+todos+'&mesano='+mesano;

	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:parametro,
		beforeSend: function(){
			
		},
		success: function(data){
			
			boxmodal.close();

			if(data.length > 1){

				for(var i=0; i < data.length; i++){

					$("a[data-id='notavr_"+data[i].nnota+"_"+data[i].cprod+"']").append('<i class="fa fa-check text-success"></i>');
					$("a[data-id='notavr_"+data[i].nnota+"_"+data[i].cprod+"']").removeClass('text-danger');
					$("a[data-id='notavr_"+data[i].nnota+"_"+data[i].cprod+"']").addClass("text-success");
					
					if(parseFloat(data[0].npesocarcaca) > 0){
						$("a[data-id='nota_"+data[i].nnota+"_"+data[i].cprod+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='nota_"+data[i].nnota+"_"+data[i].cprod+"']").removeClass('text-danger');
						$("a[data-id='nota_"+data[i].nnota+"_"+data[i].cprod+"']").addClass("text-success");
					}

				}

			}else{

				$("a[data-id='notavr_"+data[0].nnota+"_"+data[0].cprod+"']").append('<i class="fa fa-check text-success"></i>');
				$("a[data-id='notavr_"+data[0].nnota+"_"+data[0].cprod+"']").removeClass('text-danger');
				$("a[data-id='notavr_"+data[0].nnota+"_"+data[0].cprod+"']").addClass("text-success");
				
				if(parseFloat(data[0].npesocarcaca) > 0){
					$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"']").append('<i class="fa fa-check text-success"></i>');
					$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"']").removeClass('text-danger');
					$("a[data-id='nota_"+data[0].nnota+"_"+data[0].cprod+"']").addClass("text-success");
				}

			}
			


		},
		error:function(data){	
			boxmodal.close();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
	
});

$(document).on("submit","#frmnumfunc",function(){
	
	var param = $(this).serialize();
	if($("input[name='nfuncionario']").val().trim() == ''){
		var view = '<div class="message info">Digite um número de funcionários válido!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/folha-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			boxmodal.close();
			//$('[data-toggle="popover"]').popover('hide');
			$(".funcionario").append('<i class="fa fa-check text-success"></i>');
			$(".funcionario").removeClass('text-warning');
			$(".funcionario").addClass("text-success");
			
		},
		error:function(data){	
			boxmodal.close();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
});

$(document).on("submit","#frmvalorfolha",function(){
	
	var param = $(this).serialize();
	if($("input[name='vlpagto']").val().trim() == ''){
		var view = '<div class="message info">Digite um valor da folha valido!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/folha-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			
			boxmodal.close();
			$(".folhavalor").append('<i class="fa fa-check text-success"></i>');
			$(".folhavalor").removeClass('text-warning');
			$(".folhavalor").addClass("text-success");
			
		},
		error:function(data){	
			boxmodal.close();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
});

$(document).on('submit','#frmivmsnormal',function(){

	var param = $(this).serialize();
	if($("input[name='codicmsnormal']").val().trim() == ''){
		var view = '<div class="message info">Código ICMS NORMAL não pode ser vazio!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	if($("input[name='vlicmsnormal']").val().trim() == ''){
		var view = '<div class="message info">Digite um valor ICMS NORMAL!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/guiaicms-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			
			boxmodal.close();
			$(".icmsnormal").append('<i class="fa fa-check text-success"></i>');
			$(".icmsnormal").removeClass('text-warning');
			$(".icmsnormal").addClass("text-success");
			
		},
		error:function(data){	
			boxmodal.close();		
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;

});

$(document).on('submit','#frmicmsst',function(){

	var param = $(this).serialize();
	
	if($("input[name='codicmsst']").val().trim() == ''){
		var view = '<div class="message info">Código ICMS ST não pode ser vazio!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	if($("input[name='vlicmsst']").val().trim() == ''){
		var view = '<div class="message info">Digite um valor ICMS ST!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}
	

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/guiaicms-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			
			boxmodal.close();
			$(".icmsst").append('<i class="fa fa-check text-success"></i>');
			$(".icmsst").removeClass('text-warning');
			$(".icmsst").addClass("text-success");
			
		},
		error:function(data){			
			boxmodal.close();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;

});

$(document).on('focus', '.vlpagto', function(){
	$(this).maskMoney({
		 decimal:",",
		 thousands:"."			
	});
});
$(document).on('focus', '.vlicmsnormal', function(){
	$(this).maskMoney({
		 decimal:",",
		 thousands:"."			
	});
});

$(document).on('focus', '.vlicmsst', function(){
	$(this).maskMoney({
		 decimal:",",
		 thousands:"."			
	});
});

$(document).on('focus','input[name="npesocarcaca"]',function(){

	$(this).maskMoney({
		 decimal:",",
		 thousands:"."			
	});

});

$(document).on('focus','input[name="npesovivo"]',function(){
	$(this).maskMoney({
		 decimal:",",
		 thousands:"."			
	});	
});

/*$('.date-picker').datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy',
        onClose: function (dateText, inst) {
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            $(".ui-datepicker-calendar").hide();
        },
        beforeShow : function(input, inst) {
        	var set = setInterval(function(){
        		$(".ui-datepicker-calendar").hide();
        		clearInterval(set);
        	},300);
			
			
		}
 });*/

$(function() {
jQuery(".date-picker").mask("99/9999");
jQuery("#datapago").mask("99/9999");

$("#valors").maskMoney({
		 decimal:",",
		 thousands:"."			
	});
 /*$('.date-picker').datepicker(
	{
		dateFormat: "mm/yy",
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
			dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado','Domingo'
			        ],
			    dayNamesMin: [
			    'D','S','T','Q','Q','S','S','D'
			    ],
			    dayNamesShort: [
			    'Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'
			    ],
			    monthNames: [  'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro',
			    'Outubro','Novembro','Dezembro'
			    ],
			    monthNamesShort: [
			    'Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set',
			    'Out','Nov','Dez'
			    ],
			    nextText: 'Próximo',
			    prevText: 'Anterior',
		onClose: function(dateText, inst) {


			function isDonePressed(){
				return ($('#ui-datepicker-div').html().indexOf('ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all ui-state-hover') > -1);
			}

			if (isDonePressed()){
				var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				var mont = new Date(year, month, 1);
				$(this).datepicker('setDate', mont).trigger('change');
				
				//alert($('input[name="mesanocomp"]').val());				
				$('input[name="mesanocomp"]').focusout()//Added to remove focus from datepicker input box on selecting date
				//validamesanocometencia($('input[name="mesanocomp"]').val());
			}
		},
		beforeShow : function(input, inst) {

			inst.dpDiv.addClass('month_year_datepicker')

			if ((datestr = $(this).val()).length > 0) {
				year = datestr.substring(datestr.length-4, datestr.length);
				month = datestr.substring(0, 2);
				$(this).datepicker('option', 'defaultDate', new Date(year, month-1, 1));
				$(this).datepicker('setDate', new Date(year, month-1, 1));
				$(".ui-datepicker-calendar").hide();
			}
		}
	})*/
});

function grava_valida_nota(difcompmesano){
	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'grava_valida',difcompmesano:difcompmesano},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde gravando os dados!");
			
		},
		success: function(data){							
			
			$("#myModal_pdf").hide();
		
			valida_nota_novamente();
			removerarquivopasta();
			
		},
		error:function(data){	
			$("#myModal_pdf").hide();
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
}

function grava_valida_nota2(){
	
	var mesano = $('input[name="mesanocomp"]').val();
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'grava_valida2',mesano:mesano},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde a validação!");			
		},
		success: function(data){							
			
			$("#myModal_pdf").hide();
			var htm           	   = "";									
			var htmerr  		   = "";
			var htminf  		   = "";
			var contador 		   = 0;	
			var contadorcfop 	   = 0;
			var contadorprod 	   = 0;
			var contadornota 	   = 0;
			var contadorvr	 	   = 0;
			var contadorfunc 	   = 0;
			var contadorfolha	   = 0;
			var contadoricmsnormal = 0;
			var contadoricmsst     = 0;
			var contadorgta		   = 0;
			
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
							'<a href="#" onclick="BuscaNota(\''+data.dados_grid[i].Numero+'\',\''+data.dados_grid[i].entsai+'\');"><span class="fa fa-pencil fa-2x"></span></a>'+
							'<a href="#" class="detailsgta"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';

			}
			
			htmerr  += "<ol>";	
			htminf  += "<ol>";
			
				if(data.erro.cfop.length > 0){		
					htmerr += "<h3>CFOP</h3>";
					var xcodigo = "";
					
					for(y in data.erro.cfop){									
						var codcfop = data.erro.cfop[y].codigo;

						if(codcfop != xcodigo){
							xcodigo = codcfop;

							htmerr += "<li><a href='javascript:void(0);' class='geraagregarvalid text-danger' id='valid_"+codcfop+"' data-xml='"+data.erro.cfop[y].nota+"|"+data.erro.cfop[y].arquivo+"' data-id='"+data.erro.cfop[y].idvinc+"'>"+data.erro.cfop[y].msg+"</a></li>";
							contadorcfop++;	
						}

					}
				}
					
				if(data.erro.produto.length > 0){
					
					htmerr += "<h3>Relacionamento Produtos</h3>";
					var xcprod = "";
					for(s in data.erro.produto){
							
						var cprod = data.erro.produto[s].codigo;
						
						if(cprod != xcprod){
							xcprod = cprod;
							htmerr += '<li><a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+cprod+'|0" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a> <a href="javascript:void(0);" onclick="BuscaNota('+data.erro.produto[s].cnota+');" class="text-danger" data-id="relprod_'+cprod+'" data-placement="right" data-html="true"  title="" data-original-title="Relacionar">'+data.erro.produto[s].msg+'</a></li>';
							contadorprod++;
						}
												
					}
					
				}
								
				
				if(data.erro.nota.length > 0){
					htmerr += "<h3>N° De Cabeças</h3>";
					htmerr += '<div class="col-md-6">'+
								'<label>Selecione o arquivo:</label>'+
									'<i class="help-block"><small>Obs: Fazer download de planilha modelo para informações de cabeças.</small></i>'+
										'<label class="custom-file">'+				
										'<input type="file" id="filearqcabeca" name="filearqcabeca" class="custom-file-input" aria-describedby="fileHelp" accept=".xlsx">'+
										'<span class="custom-file-control"></span>'+								
									'</label>'+
									'<div style="display:inline-grid;margin-left: 14px;"><a href="#" class="btn btn-primary waves-effect waves-light downloadexcelqtd"><span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Baixar Planilha</a></div>'+
								'</div>';
								htmerr += "<hr>";	
					for(n in data.erro.nota){
						htmerr += '<li><a href="javascript:void(0);" data-id="nota_'+data.erro.nota[n].codigo+'_'+data.erro.nota[n].cProd+'_'+data.erro.nota[n].idseq+'" onclick="BuscaNota('+data.erro.nota[n].codigo+');" class="text-danger">'+data.erro.nota[n].msg+'</a><a href="javascript:void(0);" style="border-bottom: dashed 1px #0088cc;"  data-toggle="popover" data-placement="top" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmcabecas\'><input type=\'hidden\' name=\'act\' value=\'updatecabecas\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.nota[n].idseq+'\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.nota[n].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.nota[n].cProd+'\'/><label><strong>Cabeças:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'ncabecas\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>" title="" data-original-title="Informar Número de Cabeças"><strong>CLIQUE AQUI PARA INFORMAR NÚMERO DE CABEÇAS</strong></a></li>';	
						contadornota++;
					}
				}
					
				
					
				if(data.erro.vivorendmento.length > 0){
					htmerr += "<h3>Vivo/Rendimento</h3>";
					for(vr in data.erro.vivorendmento){
						htmerr += '<li><a href="javascript:void(0);" data-id="notavr_'+data.erro.vivorendmento[vr].codigo+'_'+data.erro.vivorendmento[vr].cProd+'" onclick="BuscaNota('+data.erro.vivorendmento[vr].codigo+');" class="text-danger vivorendvalid">'+data.erro.vivorendmento[vr].msg+'</a><i class="fa fa-dedent" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmvivorend\'><input type=\'hidden\' name=\'act\' value=\'updatevivorend\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.vivorendmento[vr].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.vivorendmento[vr].cProd+'\'/><input type=\'hidden\' name=\'qcom\' value=\''+data.erro.vivorendmento[vr].qCom+'\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.vivorendmento[vr].idseq+'\'/><label><strong>Vivo/Rendimento:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><select name=\'vivorend\' class=\'xvivorend\'><option value=\'\'>Selecionar</option><option value=\'V\'>Vivo</option><option value=\'R\'>Rendimento</option></select><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div><label><strong>Peso Carcaça:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesocarcaca\' class=\'form-control\' disabled/></div><label><strong>Peso Vivo:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesovivo\' class=\'form-control\' disabled/></div></form>" title="" data-original-title="Informar Vivo/Rendimento"></i></li>';
						contadorvr++;
					}
					
				}
				
			
				if(data.info.funcionario.length > 0){
					
					htminf += "<h3>N° de funcionários</h3>";
						
					for(f in data.info.funcionario){
						
						
						htminf += '<li><a href="javascript:void(0);" class="funcionario text-warning" data-id="'+data.info.funcionario[f].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmnumfunc\'><input type=\'hidden\' name=\'act\' value=\'inserirnumfun\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.funcionario[f].id+'\'/><label><strong>Número:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'nfuncionario\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.funcionario[f].msg+'<a/></li>';
						contadorfunc++;
					}
				}	
				
			
				if(data.info.folha.length > 0){
					
					htminf += "<h3>Valor da Folha de Pagamento</h3>";
						
					for(l in data.info.folha){						
						
						htminf += '<li><a href="javascript:void(0);" class="folhavalor text-warning" data-id="'+data.info.folha[l].id+'" data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'folha-exec.php\' id=\'frmvalorfolha\'><input type=\'hidden\' name=\'act\' value=\'inserirvalorfolha\'/><input type=\'hidden\' name=\'id\' value=\''+data.info.folha[l].id+'\'/><label><strong>Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlpagto\' class=\'form-control vlpagto\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.folha[l].msg+'<a/></li>';
						contadorfolha++;
					}
				}
				

				if(data.info.icmsnormal.length > 0){
					htminf += "<h3>Valor ICMS Normal</h3>";	
					for(a in data.info.icmsnormal){	
						htminf += '<li><a href="javascript:void(0);" class="icmsnormal text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmivmsnormal\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsnormal\'/><label><strong>Codigo ICMS NORMAL/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsnormal\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsnormal\' class=\'form-control vlicmsnormal\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsnormal[a].msg+'<a/></li>';
						contadoricmsnormal++;	
					}
				}	
				
				if(data.info.icmsst.length > 0){
					htminf += "<h3>Valor ICMS ST</h3>";		
					for(st in data.info.icmsst){	
						htminf += '<li><a href="javascript:void(0);" class="icmsst text-warning"  data-toggle="popover" data-placement="right" data-html="true" data-content="<button type=\'button\' id=\'close\' class=\'close\'>&times;</button><form method=\'post\' action=\'guiaicms-exec.php\' id=\'frmicmsst\'><input type=\'hidden\' name=\'act\' value=\'inseriricmsst\'/><label><strong>Codigo ICMS ST/Valor:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-list\'></span><input type=\'text\' name=\'codicmsst\' class=\'form-control\' /></div><div class=\'input-group\'><span class=\'input-group-addon fa fa-usd\'></span><input type=\'text\' name=\'vlicmsst\' class=\'form-control vlicmsst\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>">'+data.info.icmsst[st].msg+'<a/></li>';
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
			
			var num_erro = contadorcfop + contadorprod + contadornota + contadorvr;
			var num_info = contadorfunc + contadorfolha + contadoricmsnormal + contadoricmsst + contadorgta;
			$('.num_erros').html(num_erro);	
			$(".num_info").html(num_info);
			
			$(".valid_erros").html(htmerr);
			$(".valid_infos").html(htminf);
			
			$(".validacaoarquivos").show();
			
			$("#dyntable_notas_valid tbody").html(htm);

			$dTable = $('#dyntable_notas_valid').dataTable({					
				 "bSort" : false,
				 "paging":   false,
				 "ordering": true,
				 "info":     true,
				 "bDestroy": true,
				 "bFilter": true,
				 "bRetrieve": true,
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
				},
			});
			
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
					 var iset = setInterval(function(){
						clearInterval(iset);
						valida_nota_novamente();						
					 },600);
					 
				}
			});

			var setv = setInterval(function(){

				var pos = $('.num_erros').position().top;
			    $('html, body').animate({
			        scrollTop: pos		
			    }, 1000);
			    clearInterval(setv);	
			    
			},800);
			

		},
		error:function(data){	
			$("#myModal_pdf").hide();	
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
}

function valida_nota_novamente(){
	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'valida_processo'},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF img").show();
			$(".modal-body_PDF p").html("Aguarde a validação!");			
		},
		success: function(data){							
			
			var htm     = "";									
			var htmerr  = "";
			var htminf  = "";
			var contador = 0;	
			var contadorcfop = 0;
			var contadorprod = 0;
			var contadornota = 0;
			var contadorvr	 = 0;
			var contadtabate = 0;
			var containfdtabate = 0;
			var contadorfunc = 0;
			var contadorfolha= 0;
			var contadoricmsnormal = 0;
			var contadoricmsst     = 0;	
			var contadorgta		   = 0;
			var contavaloricms     = 0;
			var contasesao		   = 0;
			var contadorterceriros = 0;
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
						    '<input type="hidden" id="'+data.dados_grid[i].Numero+'_tpabate" value="'+data.dados_grid[i].abate+'"/>'+									
							'<a href="javascript:void(0);" onclick="BuscaNota(\''+data.dados_grid[i].Numero+'\',\''+data.dados_grid[i].entsai+'\');" ><span class="fa fa-pencil fa-2x"></span></a>'+
							'<a href="#" class="detailsgta"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';

			}
			
			htmerr  += "<ol>";	
			htminf  += "<ol>";
			var optcity = "";
				if(data.erro.cfop.length > 0){		
					htmerr += "<div id='validacfop'>";
					htmerr += "<h3>CFOP</h3>";
					var xcodigo = "";
					
					for(y in data.erro.cfop){									
						var codcfop = data.erro.cfop[y].codigo;

						if(codcfop != xcodigo){
							xcodigo = codcfop;

							htmerr += "<li><a href='javascript:void(0);' class='geraagregarvalid text-danger' id='valid_"+codcfop+"' data-xml='"+data.erro.cfop[y].nota+"|"+data.erro.cfop[y].arquivo+"' data-id='"+data.erro.cfop[y].idvinc+"'>"+data.erro.cfop[y].msg+"</a></li>";
							contadorcfop++;	
						}

					}
					htmerr += "</div>";
					optcity = "style='pointer-events: none;opacity: 0.4;'";
				}
				
				if(data.erro.produtoterceiro.length > 0){
					var xprod2 = ListaProdutoFrigorifico();

					htmerr += "<div class='row'><div class='col-sm-10'><h3>Relacionamento de produtos (Documento Terceiros)</h3></div>";

					htmerr += `
							<div class="col-sm-3">
								<div class="form-check">
									<label class="custom-control custom-radio">
										<input id="radiomultiplos2" name="radiomultiplos2" type="checkbox" class="custom-control-input">
										<span class="custom-control-indicator"></span>
										<span class="custom-control-description">Relacionar múltiplos produtos</span>
									</label>						
								</div>
							</div>
											
					`;
					var ht = "";
					for (let index = 0; index < xprod2.length; index++) {
						const element = xprod2[index];
						ht +='<option value="'+element.id+'">'+element.text+'</option>';
					}							

					htmerr +='<div class="col-sm-4 ckmultiplos2">'+
									'<select class="m-b-10 select2-multiple2" style="width:100%;">'+								
										''+ht+''+
									'</select>'+								
								'</div>'+
								'<div class="col-sm-1 ckmultiplos2">'+
									'<button class="btn btn-sm btn-primary btnrelacionamult2" data-type="xml">Relacionar</button>'+
								'</div>'+
							'</div>';

					for(trc in data.erro.produtoterceiro){
						var cprods = data.erro.produtoterceiro[trc].codigo;
						var htmlMessages = '[{\'nnota\':\''+data.erro.produtoterceiro[trc].dados['nNF']+'\',\'entsai\':\''+data.erro.produtoterceiro[trc].dados['entsai']+'\',\'cliente\':\''+data.erro.produtoterceiro[trc].dados['cliente']+'\',\'valor\':\''+data.erro.produtoterceiro[trc].dados['valor']+'\',\'demi\':\''+data.erro.produtoterceiro[trc].dados['demi']+'\'}]';
						var dados = '[{\'nnota\':\''+data.erro.produtoterceiro[trc].dados['nNF']+'\',\'entsai\':\''+data.erro.produtoterceiro[trc].dados['entsai']+'\',\'cliente\':\''+data.erro.produtoterceiro[trc].dados['cliente']+'\',\'valor\':\''+data.erro.produtoterceiro[trc].dados['valor']+'\',\'demi\':\''+data.erro.produtoterceiro[trc].dados['demi']+'\',\'cprod\':\''+cprods+'\',\'clicnpj\':\''+data.erro.produtoterceiro[trc].dados['cnpjcli']+'\',\'cliie\':\''+data.erro.produtoterceiro[trc].dados['iecli']+'\',\'numero_item_nota\':\''+data.erro.produtoterceiro[trc].dados['numero_item_nota']+'\'}]';
						
						var rdprod2 = `
						<label class="custom-control custom-radio radioprod2">
							<input name="radioprod2[]" type="checkbox" value="${cprods}" data-pk="${dados}" class="radioprods2 custom-control-input">
							<span class="custom-control-indicator"></span>							
						</label>
						`;
						var indForn  = data.erro.produtoterceiro[trc].dados['cnpjcli']+''+data.erro.produtoterceiro[trc].dados['iecli'];
						htmerr += '<li>'+rdprod2+'<a href="javascript:void(0);" class="editprodfrig" data-type="select2" data-url="relaciona-exec.php?act=upinsertterceiro" data-pk="'+dados+'|0" data-prod="'+cprods+'|'+indForn+'|'+data.erro.produtoterceiro[trc].dados['nNF']+'" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a> <a href="javascript:void(0);" class="popupnota relacionaprodutotereceiro text-danger" data-content="'+htmlMessages+'" onclick="BuscaNota(\''+data.erro.produtoterceiro[trc].cnota+'\',\''+data.erro.produtoterceiro[trc].dados['entsai']+'\');" data-id="relprodfrig_'+cprods+'" data-prod="relprodfrig_'+cprods+'|'+indForn+'|'+data.erro.produtoterceiro[trc].dados['nNF']+'" data-placement="right" data-html="true"  title="" data-original-title="Relacionar">'+data.erro.produtoterceiro[trc].msg+'</a></li>';
						contadorterceriros++;
					}
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
									'<button class="btn btn-sm btn-primary btnrelacionamult" data-type="xml">Relacionar</button>'+
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
							htmerr += '<li>'+rdprod+'<a href="javascript:void(0);" class="editprod" data-type="select2" data-url="relaciona-exec.php?act=upinsert" data-pk="'+cprod+'|0" data-title="Enter"><strong>Clique AQUI para relacionar</strong></a> <a href="javascript:void(0);" class="popupnota relacionaproduto text-danger" data-content="'+htmlMessage+'" onclick="BuscaNota(\''+data.erro.produto[s].cnota+'\',\''+data.erro.produto[s].dados['entsai']+'\');" class="text-danger" data-id="relprod_'+cprod+'" data-placement="right" data-html="true"  title="" data-original-title="Relacionar">'+data.erro.produto[s].msg+'</a></li>';
							contadorprod++;
						}
												
					}
					htmerr += "</div>";
					optcity2 = "style='pointer-events: none;opacity: 0.4;'";
				}
								
				var optcity3 = "";
				if(data.erro.nota.length > 0){
					htmerr += "<div id='validanumerocabeca' "+optcity2+">";
					htmerr += "<h3>N° De Cabeças</h3>";
					htmerr += '<div class="row"><div class="col-md-7">'+
								'<label>Selecione o arquivo:</label>'+
									'<i class="help-block"><small>Obs: Fazer download de planilha modelo para informações de cabeças.</small></i>'+
										'<label class="custom-file">'+				
										'<input type="file" id="filearqcabeca" name="filearqcabeca" class="custom-file-input" aria-describedby="fileHelp" accept=".xlsx">'+
										'<span class="custom-file-control"></span>'+								
									'</label>'+
									'<div style="display:inline-grid;margin-left: 14px;"><a href="#" class="btn btn-primary waves-effect waves-light downloadexcelqtd"><span class="btn-label"><i class="fa fa-file-excel-o"></i></span>Baixar Planilha</a></div>'+
								'</div></div>';
								htmerr += "<hr>";	
					for(n in data.erro.nota){
						htmerr += '<li><a href="javascript:void(0);" data-id="nota_'+data.erro.nota[n].codigo+'_'+data.erro.nota[n].cProd+'_'+data.erro.nota[n].idseq+'" onclick="BuscaNota(\''+data.erro.nota[n].codigo+'\',\''+data.erro.nota[n].entsai+'\');" class="text-danger">'+data.erro.nota[n].msg+'</a><a href="javascript:void(0);" style="border-bottom: dashed 1px #0088cc;" class="clickcabecas" data-cabeca="'+data.erro.nota[n].codigo+'|'+data.erro.nota[n].cProd+'|'+data.erro.nota[n].idseq+'"  data-placement="top" data-html="true" data-content="<div class=\'form_callback\'></div><form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmcabecas\'><input type=\'hidden\' name=\'act\' value=\'updatecabecas\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.nota[n].idseq+'\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.nota[n].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.nota[n].cProd+'\'/><label><strong>Cabeças:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'ncabecas\' class=\'form-control\' /><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div></form>" title="" data-original-title="Informar Número de Cabeças"><strong>CLIQUE AQUI PARA INFORMAR NÚMERO DE CABEÇAS</strong></a></li>';	
						contadornota++;
					}
					htmerr += "</div>";
					optcity3 = "style='pointer-events: none;opacity: 0.4;'";
				}
					
				
				var optcity4 = "";	
				if(data.erro.vivorendmento.length > 0){
					htmerr += "<div id='validavivorendimento' "+optcity3+">";
					htmerr += "<h3>Vivo/Rendimento</h3>";
					for(vr in data.erro.vivorendmento){
						htmerr += '<li><a href="javascript:void(0);" data-id="notavr_'+data.erro.vivorendmento[vr].codigo+'_'+data.erro.vivorendmento[vr].cProd+'" onclick="BuscaNota(\''+data.erro.vivorendmento[vr].codigo+'\',\''+data.erro.vivorendmento[vr].entsai+'\');" class="text-danger vivorendvalid">'+data.erro.vivorendmento[vr].msg+'</a><i class="fa fa-dedent boxmodal"  data-placement="right" data-html="true" data-content="<form method=\'post\' action=\'lancamentos-exec.php\' id=\'frmvivorend\'><input type=\'hidden\' name=\'act\' value=\'updatevivorend\'/><input type=\'hidden\' name=\'nnota\' value=\''+data.erro.vivorendmento[vr].codigo+'\'/><input type=\'hidden\' name=\'cprod\' value=\''+data.erro.vivorendmento[vr].cProd+'\'/><input type=\'hidden\' name=\'qcom\' value=\''+data.erro.vivorendmento[vr].qCom+'\'/><input type=\'hidden\' name=\'idseq\' value=\''+data.erro.vivorendmento[vr].idseq+'\'/><label><strong>Vivo/Rendimento:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><select name=\'vivorend\' class=\'xvivorend\'><option value=\'\'>Selecionar</option><option value=\'V\'>Vivo</option><option value=\'R\'>Rendimento</option></select><span class=\'input-group-btn\'><button class=\'btn btn-primary btn-block\' type=\'submit\'>SALVAR</button></span></div><label><strong>Peso Carcaça:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesocarcaca\' class=\'form-control\'/></div><label><strong>Peso Vivo:</strong></label><div class=\'input-group\'><span class=\'input-group-addon fa fa-terminal\'></span><input type=\'text\' name=\'npesovivo\' class=\'form-control\'/></div></form>" title="" data-original-title="Informar Vivo/Rendimento"></i></li>';
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
				
				if(data.info.valoricms.length > 0){
					var xnotas = "";					
					htminf += "<h3>ICMS</h3>";
					htminf += "<div>Documento sem valor do ICMS</div>";
					for(lsvaloricms in data.info.valoricms){

						if(data.info.valoricms[lsvaloricms].cnota != xnotas){
							xnotas = data.info.valoricms[lsvaloricms].cnota;
							htminf += '<div>Número documento: '+data.info.valoricms[lsvaloricms].cnota+'</div>';
						}

						htminf += '<li style="margin-left: 62px;">'+
									'<a href="javascript:void(0);"  onclick="BuscaNota(\''+data.info.valoricms[lsvaloricms].dados['nNF']+'\',\''+data.info.valoricms[lsvaloricms].dados['entsai']+'\');">'+
										''+data.info.valoricms[lsvaloricms].msg+''+
									'</a>'
								  +'</li>';

						contavaloricms++;
					}

					htminf += "</div>";										
				}
				
				if(data.info.sessao.length > 0){
					
					htminf += "<h3>Mensagem de sessão</h3>";
						
					for(ss in data.info.sessao){
												
						htminf += '<li><a href="javascript:void(0);" data-id="'+data.info.sessao[ss].cnota+'" >'+data.info.sessao[ss].msg+'<a/></li>';
						contasesao++;
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
			
			var num_erro = contadorcfop + contadorprod + contadornota + contadorvr + contadtabate + contadorterceriros;
			var num_info = contadorfunc + contadorfolha + contadoricmsnormal + contadoricmsst + contadorgta + containfdtabate + contavaloricms + contasesao;
			if(num_erro == 0){
				htmerr  += '<div class="text-center">Os seus arquivos estão corretos é so clicar para o proximo passo!<br/><img src="../images/sucess.png"/></div>';
			}
			$('.num_erros').html(num_erro);	
			$(".num_info").html(num_info);
			
			$(".valid_erros").html(htmerr);
			$(".valid_infos").html(htminf);
			
			$(".validacaoarquivos").show();
			
			$("#dyntable_notas_valid tbody").html(htm);

			$dTable = $('#dyntable_notas_valid').dataTable({					
				 "bSort" : false,
				 "paging":   false,
				 "ordering": true,
				 "info":     true,
				 "bDestroy": true,
				 "bFilter": true,
				 "bRetrieve": true,
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
				},
			});
			
			ListaNotasdesaida();
			ListaNotasdesentrada();
			$("#myModal_pdf").hide();
			$('.editprod').editable({
				source: ListaProdutoAgregar(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
					placeholder: 'Selecione um produto agregar',
					selectOnClose: true,					
				},				
			    mode: 'inline',
			    emptytext: 'Vazio',
				ajaxOptions: { dataType: 'json'},				
			    success: function(response, newValue) {
			    	 $("a[data-id='relprod_"+response[0].idprod+"']").append('<i class="fa fa-check text-success"></i>');
					 $("a[data-id='relprod_"+response[0].idprod+"']").removeClass('text-danger');
					 $("a[data-id='relprod_"+response[0].idprod+"']").addClass("text-success");
					 var iset = setInterval(function(){
						clearInterval(iset);
						
						var lenitemrel = $(".relacionaproduto").length;
						var lenitemsuc = $(".relacionaproduto .text-success").length;

						if(parseInt(lenitemrel)===parseInt(lenitemsuc)){
							valida_nota_novamente();
						}
						
						//valida_nota_novamente();
						//$('.editprod').next('.editprod').click();						
					 },600);
				}
			});
			
			$('.editprodfrig').editable({
				source: ListaProdutoFrigorifico(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
					placeholder: 'Selecione um produto',
					selectOnClose: true,					
				},				
			    mode: 'inline',
			    emptytext: 'Vazio',
				ajaxOptions: { dataType: 'json'},				
			    success: function(response, newValue) {
			    	 //$("a[data-id='relprodfrig_"+response[0].idprod+"']").append('<i class="fa fa-check text-success"></i>');
					 //$("a[data-id='relprodfrig_"+response[0].idprod+"']").removeClass('text-danger');
					 //$("a[data-id='relprodfrig_"+response[0].idprod+"']").addClass("text-success");

					 $("a[data-prod='relprodfrig_"+response[0].idprod+"|"+response[0].indForn+"|"+response[0].nnota+"']").append('<i class="fa fa-check text-success"></i>');
					 $("a[data-prod='relprodfrig_"+response[0].idprod+"|"+response[0].indForn+"|"+response[0].nnota+"']").removeClass('text-danger');
					 $("a[data-prod='relprodfrig_"+response[0].idprod+"|"+response[0].indForn+"|"+response[0].nnota+"']").addClass("text-success");
					 $("a[data-prod='"+response[0].idprod+"|"+response[0].indForn+"|"+response[0].nnota+"'] strong").html(response[0].idprodrel+' - '+response[0].relprod);
					 $("a[data-prod='"+response[0].idprod+"|"+response[0].indForn+"|"+response[0].nnota+"']").attr('data-pk',`${response[0].obj}|${response[0].codigo}`);

					 var iset = setInterval(function(){
						clearInterval(iset);
						var lenitemrelt = $(".relacionaprodutotereceiro").length;
						var lenitemsuct = $(".relacionaprodutotereceiro .text-success").length;

						if(parseInt(lenitemrelt)===parseInt(lenitemsuct)){
							valida_nota_novamente();
						}
						//valida_nota_novamente();
						//$('.editprod').next('.editprod').click();						
					 },600);
				}
			});

			$('.select2-multiple').select2({	
				"language": "pt-BR",											
				mode: 'inline',
				emptytext: 'Vazio',		
				placeholder: 'Selecione um produto agregar',				
			});

			$('.select2-multiple2').select2({	
				"language": "pt-BR",											
				mode: 'inline',
				emptytext: 'Vazio',		
				placeholder: 'Selecione um produto do frigorifico',				
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
			
			$('.editprodfrig').on('shown', function(e, editable) {
    
				$(document).on('change', editable, function(e) {

					var btns = '<input type="submit" value="Relacionar ?" class="btn btn-info btn-sm editable-submit fa fa-check" style="float: left;"/>';
					$('.editable-submit').remove();
					
					$(".editable-buttons").append(btns);

					var st = setInterval(function(){
						$('.editable-submit').focus();
						clearInterval(st);
					},300)
					

					return false;
					
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

$('body').on('click', '.select2-container', function (e) {
    e.stopPropagation();
});

$(document).on('mouseout','.editprod',function(e){
	e.stopPropagation();

	
	var tm = setInterval(function(){
		
		if($(".editprod").hasClass('editable-open') != true){
			console.log($(".editprod .editable-open").length);
			
			if($(".select2-container")[1]){
				$(".select2-container")[1].remove();
			}
			//console.log($(".select2-container"));
			clearInterval(tm);
		}
	},0);
});

/*$(document).on('shown','.editprod',function(e){
   
});*/
//select2-dropdown
//editableform
$(document).on('click','.clickcabecas',function(e){

	var nnota = $(this).attr('data-cabeca').split('|')[0];
	var cprod = $(this).attr('data-cabeca').split('|')[1];
	var iditem= $(this).attr('data-cabeca').split('|')[2];
	var htm    = $(this).attr('data-content');
	var title  = $(this).attr('data-original-title');
	var c 	  = $(this);		

	cabbox = $.confirm({
		title: ''+title+'',
		content: ''+htm+'',
		type: 'orange',
		typeAnimated: true,
		buttons: {
			tryAgain: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					cabbox.close();
				}
			}
		}
	});

	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json", 
		url:"../php/lancamentos-exec.php",
		data:{act:"pegaqtdcabeca",nnota:nnota,cprod:cprod,iditem:iditem},
		success: function(data){									
			$("input[name='ncabecas']").val(data[0].ncabecas);
			$('input[name="ncabecas"]').select();			
		},
		error:function(data){
			console.log(data);
		}
	});

});

function validacaoproxpaso(){
	
	var returno = 0;
	
	$.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/lancamentos-exec.php",
		data:{act:'valida_processo'},		
		success: function(data){							
							
				var contador     = 0;	
				var contadorcfop = 0;
				var contadorprod = 0;
				var contadornota = 0;
				var contadorvr   = 0;
			
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
			
			var num_erro = contadorcfop + contadorprod + contadornota + contadorvr;
			
			returno = num_erro;
			
		},
		error:function(data){						
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
	
	return returno;
	
}

$(document).on('click','.listanotaentrada',function(){
	var msa = $('input[name="setmesanocomp"]').val();	
	var lista_itens2 = JSON.parse(localStorage.getItem('lista-items2') || '[]');
	
	if(lista_itens2[0].length == 0){	
	$.confirm({
		columnClass: 'col-md-12',
		containerFluid: true,
		type: 'blue',
		buttons: {
			Fechar: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
				}
			}
		},		
		content: function () {
			var self = this;
			return $.ajax({
				url: '../php/notascopetencia-exec.php',
				dataType: 'json',
				method: 'post',
				data:{act:'entrada',mesano:msa},
			}).done(function (data) {
				var htm = "";
				var valor_total = 0;
				var valor_total_prod = 0;
				htm +='<table id="dyntable_notas" class="table table-bordered">'+
				  '<thead>'+
					'<tr>'+
					  '<th style="text-align:center;">NOTA</th>'+
					  '<th style="text-align:center;">DATA</th>'+
					  '<th style="text-align:center; display:none;">CHAVE DE ACESSO</th>'+
					  '<th style="text-align:center; display:none;">ENTRADA/SAIDA</th>'+
					  '<th style="text-align:center;">CLIENTE/FORNECEDOR</th>'+
					  '<th style="text-align:center;">VALOR TOTAL</th>'+
					  '<th style="text-align:center;">VALOR TOTAL PRODUTOS</th>'+
					  '<th style="text-align:center;">AÇÃO</th>'+
					'</tr>'+
				  '</thead>'+
				  '<tbody>';
						
					for(var i = 0; i < data.length; i++){
					
					var cor = data[i].cor != "" ? "style='background: "+data[i].cor+"; '"  : ""						

					htm += '<tr id="'+data[i].numero+'" '+cor+' data-caminho="">'+
							  '<td>'+data[i].numero+'</td>'+
							  '<td>'+data[i].dataemiss+'</td>'+
							  '<td style="width: 33%;display:none;">'+data[i].chave+'</td>'+
							  '<td style="text-align:center; width: 7%;display:none;">'+data[i].tipo+'</td>'+
							  '<td>'+data[i].razao+'</td>'+
							  '<td style="text-align:right; width: 10%;">'+number_format(data[i].valor,2,',','.')+'</td>'+
							  '<td style="text-align:right; width: 10%;">'+number_format(data[i].totprod,2,',','.')+'</td>'+
							  '<td  class="centeralign">'+
							    '<input type="hidden" id="'+data[i].numero+'_tpabate" value="'+data[i].abate+'"/>'+ 								
								'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
								'<a href="#" onclick="BuscaNota(\''+data[i].numero+'\',\''+data[i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
							  '</td>'+
							'</tr>';	
							
														
							
						valor_total = parseFloat(valor_total) + parseFloat(data[i].valor);	
						valor_total_prod = parseFloat(valor_total_prod) + parseFloat(data[i].totprod);			
					}	


				 htm +='</tbody>'+
					   '</table>';
				
				var htmtotals = '<div class="row listtotais"><div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-info">'+
										'<div class="box bg-info text-center">'+
											'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
											'<h6 class="text-white">Valor total notas</h6>'+
										'</div>'+
									'</div>'+
								'</div>';	   
					
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
						'<div class="card card-inverse card-info">'+
							'<div class="box bg-info text-center">'+
								'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
								'<h6 class="text-white">Valor total Produtos</h6>'+
							'</div>'+
						'</div>'+
					'</div>';
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-info">'+
										'<div class="box bg-info text-center">'+
											'<h1 class="font-light text-white">'+data.length+'</h1>'+
											'<h6 class="text-white">Total de registros</h6>'+
										'</div>'+
									'</div>'+
								'</div></div>';


				self.setContent(''+htmtotals+'<br>'+htm);
				self.setTitle('NOTAS DE ENTRADAS');
				var set = setInterval(function(){
					$dTable = $('#dyntable_notas').dataTable({					
						 "bSort" : false,
						 "paging":   false,
						 "ordering": true,
						 "info":     false,
						 "bDestroy": true,
						 "bFilter": true,
						 "dom": 'Bfrtip',
						 "order": [[ 5, "desc" ]],
						 "language": {
							"url": "../plugins/datatables/Portuguese-Brasildefault.json",
							"decimal": ",",
            				"thousands": "."
						},
					});	
					clearInterval(set);
				},900);
				
				
			}).fail(function(){
				self.setContent('Something went wrong.');
			});
		}
	});
	}else{
		$('.loadentrada').removeClass('hide');
				var htm = "";
				var valor_total = 0;
				var valor_total_prod = 0;
				htm +='<table id="dyntable_notas" class="table table-bordered">'+
				  '<thead>'+
					'<tr>'+
					  '<th style="text-align:center;">NOTA</th>'+
					  '<th style="text-align:center;">DATA</th>'+
					  '<th style="text-align:center; display:none;">CHAVE DE ACESSO</th>'+
					  '<th style="text-align:center; display:none;">ENTRADA/SAIDA</th>'+
					  '<th style="text-align:center;">CLIENTE/FORNECEDOR</th>'+
					  '<th style="text-align:center;">VALOR TOTAL</th>'+
					  '<th style="text-align:center;">VALOR TOTAL PRODUTOS</th>'+
					  '<th style="text-align:center;">AÇÃO</th>'+
					'</tr>'+
				  '</thead>'+
				  '<tbody>';
						
					for(var i = 0; i < lista_itens2[0].length; i++){
					
					var cor = lista_itens2[0][i].cor != "" ? "style='background: "+lista_itens2[0][i].cor+"; '"  : ""						

					htm += '<tr id="'+lista_itens2[0][i].numero+'" '+cor+' data-caminho="">'+
							  '<td>'+lista_itens2[0][i].numero+'</td>'+
							  '<td>'+lista_itens2[0][i].dataemiss+'</td>'+
							  '<td style="width: 33%;display:none;">'+lista_itens2[0][i].chave+'</td>'+
							  '<td style="text-align:center; width: 7%;display:none;">'+lista_itens2[0][i].tipo+'</td>'+
							  '<td>'+lista_itens2[0][i].razao+'</td>'+
							  '<td style="text-align:right; width: 10%;">'+number_format(lista_itens2[0][i].valor,2,',','.')+'</td>'+
							  '<td style="text-align:right; width: 10%;">'+number_format(lista_itens2[0][i].totprod,2,',','.')+'</td>'+
							  '<td  class="centeralign">'+
							    '<input type="hidden" id="'+lista_itens2[0][i].numero+'_tpabate" value="'+lista_itens2[0][i].abate+'"/>'+ 								
								'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
								'<a href="#" onclick="BuscaNota(\''+lista_itens2[0][i].numero+'\',\''+lista_itens2[0][i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
							  '</td>'+
							'</tr>';	
							
														
							
						valor_total = parseFloat(valor_total) + parseFloat(lista_itens2[0][i].valor);	
						valor_total_prod = parseFloat(valor_total_prod) + parseFloat(lista_itens2[0][i].totprod);			
					}	


				 htm +='</tbody>'+
					   '</table>';
				
				var htmtotals = '<div class="row listtotais"><div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-info">'+
										'<div class="box bg-info text-center">'+
											'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
											'<h6 class="text-white">Valor total notas</h6>'+
										'</div>'+
									'</div>'+
								'</div>';	   
					
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
						'<div class="card card-inverse card-info">'+
							'<div class="box bg-info text-center">'+
								'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
								'<h6 class="text-white">Valor total Produtos</h6>'+
							'</div>'+
						'</div>'+
					'</div>';
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-info">'+
										'<div class="box bg-info text-center">'+
											'<h1 class="font-light text-white">'+lista_itens2[0].length+'</h1>'+
											'<h6 class="text-white">Total de registros</h6>'+
										'</div>'+
									'</div>'+
								'</div></div>';


				//self.setContent(''+htmtotals+'<br>'+htm);
				//self.setTitle('NOTAS DE ENTRADAS');
				$.confirm({
					title: 'NOTAS DE ENTRADAS',
					content: ''+htmtotals+'<br>'+htm,
					type: 'blue',
					columnClass: 'col-md-12',
					containerFluid: true,
					animation: 'rotateXR',
					buttons: {
						Fechar: {
							text: 'Fechar',
							btnClass: 'btn-red',
							action: function(){
							}
						}
					}
				});		
				var set = setInterval(function(){
					$dTable = $('#dyntable_notas').dataTable({					
						 "bSort" : false,
						 "paging":   false,
						 "ordering": true,
						 "info":     false,
						 "bDestroy": true,
						 "bFilter": true,
						 "dom": 'Bfrtip',
						 "order": [[ 5, "desc" ]],
						 "language": {
							"url": "../plugins/datatables/Portuguese-Brasildefault.json",
							"decimal": ",",
            				"thousands": "."
						},
					});	
					$('.loadentrada').addClass('hide');
					clearInterval(set);
				},900);

	}
	return false;
});

function atualizaGridEntradas(){
	var msa = $('input[name="setmesanocomp"]').val();
	axios({
		method: 'post',
		url: 'notascopetencia-exec.php',
		data: 'act=entrada&mesano='+msa+''
	  })
		.then(function (data) {
			// handle success
			var htm = "";
			var valor_total = 0;
			var valor_total_prod = 0;
		
					
				for(var i = 0; i < data.data.length; i++){
				
				var cor = data.data[i].cor != "" ? "style='background: "+data.data[i].cor+"; '"  : ""						

				/*htm += '<tr id="'+data.data[i].numero+'" '+cor+' data-caminho="">'+
						  '<td>'+data.data[i].numero+'</td>'+
						  '<td>'+data.data[i].dataemiss+'</td>'+
						  '<td style="width: 33%;display:none;">'+data.data[i].chave+'</td>'+
						  '<td style="text-align:center; width: 7%;display:none;">'+data.data[i].tipo+'</td>'+
						  '<td>'+data.data[i].razao+'</td>'+
						  '<td style="text-align:right; width: 10%;">'+number_format(data.data[i].valor,2,',','.')+'</td>'+
						  '<td style="text-align:right; width: 10%;">'+number_format(data.data[i].totprod,2,',','.')+'</td>'+
						  '<td  class="centeralign">'+
							'<input type="hidden" id="'+data.data[i].numero+'_tpabate" value="'+data.data[i].abate+'"/>'+ 								
							'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
							'<a href="#" onclick="BuscaNota(\''+data.data[i].numero+'\',\''+data.data[i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
						  '</td>'+
						'</tr>';	*/
						//console.log(data.data[i].totprod);
						$("#dyntable_notas tr[id='"+data.data[i].numero+"']").attr('style','background: '+data.data[i].cor+';');
						$("#dyntable_notas tr[id='"+data.data[i].numero+"'] td:eq(5)").html(number_format(data.data[i].valor,2,',','.'));
						$("#dyntable_notas tr[id='"+data.data[i].numero+"'] td:eq(6)").html(number_format(data.data[i].totprod,2,',','.'));							
						
					valor_total = parseFloat(valor_total) + parseFloat(data.data[i].valor);	
					valor_total_prod = parseFloat(valor_total_prod) + parseFloat(data.data[i].totprod);			
				}	


			
			var htmtotals = '<div class="col-md-7 col-lg-4 col-xlg-4">'+
								'<div class="card card-inverse card-info">'+
									'<div class="box bg-info text-center">'+
										'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
										'<h6 class="text-white">Valor total notas</h6>'+
									'</div>'+
								'</div>'+
							'</div>';	   
				
				htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
					'<div class="card card-inverse card-info">'+
						'<div class="box bg-info text-center">'+
							'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
							'<h6 class="text-white">Valor total Produtos</h6>'+
						'</div>'+
					'</div>'+
				'</div>';
				htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
								'<div class="card card-inverse card-info">'+
									'<div class="box bg-info text-center">'+
										'<h1 class="font-light text-white">'+data.data.length+'</h1>'+
										'<h6 class="text-white">Total de registros</h6>'+
									'</div>'+
								'</div>'+
							'</div>';

					
			
			$(".listtotais").html(htmtotals);	
			
			
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});
}

function atualizaGridSaida(){
	var msa = $('input[name="setmesanocomp"]').val();
	axios({
		method: 'post',
		url: 'notascopetencia-exec.php',
		data: 'act=saida&mesano='+msa+'',		
	  })
		.then(function (data) {
			// handle success
			var htm = "";
			var valor_total = 0;
			var valor_total_prod = 0;
		
					
				for(var i = 0; i < data.data.length; i++){
							
					$("#dyntable_notas tr[id='"+data.data[i].numero+"'] td:eq(5)").html(number_format(data.data[i].valor,2,',','.'));
					$("#dyntable_notas tr[id='"+data.data[i].numero+"'] td:eq(6)").html(number_format(data.data[i].totprod,2,',','.'));							
						
					valor_total = parseFloat(valor_total) + parseFloat(data.data[i].valor);	
					valor_total_prod = parseFloat(valor_total_prod) + parseFloat(data.data[i].totprod);			
				}	


			
				var htmtotals = '<div class="col-md-7 col-lg-4 col-xlg-4">'+
										'<div class="card card-inverse card-warning">'+
											'<div class="box bg-warning text-center">'+
												'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
												'<h6 class="text-white">Valor total notas</h6>'+
											'</div>'+
										'</div>'+
									'</div>';	   

						htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
							'<div class="card card-inverse card-warning">'+
								'<div class="box bg-warning text-center">'+
									'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
									'<h6 class="text-white">Valor total Produtos</h6>'+
								'</div>'+
							'</div>'+
						'</div>';
						htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
										'<div class="card card-inverse card-warning">'+
											'<div class="box bg-warning text-center">'+
												'<h1 class="font-light text-white">'+data.data.length+'</h1>'+
												'<h6 class="text-white">Total de registros</h6>'+
											'</div>'+
										'</div>'+
									'</div>';

					
			
			$(".lstsaida").html(htmtotals);	
			
			
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});
}

var page = 1;
var currentscrollHeight = 0;
$('#contentscr').on('scroll', function() {
	
	const scrollHeight = $(this).height();
    const scrollPos    = Math.floor($(this).height() + $(this).scrollTop());
    const isBottom     = scrollHeight - 100 < scrollPos;
	const scrollPos2    = Math.floor($(this).scrollTop() + $(this).innerHeight());
	var msa = $('input[name="setmesanocomp"]').val();
	console.log(scrollPos2 +' >= '+ $(this)[0].scrollHeight);
			
	if((scrollPos2+1) >= $(this)[0].scrollHeight){
		page++;

		var actual_count = parseInt($(".num_saida").html().trim());
		
		if((page-1)* 100 > actual_count){
			console.log('remove o load');
		}else{
			console.log(page);
			axios({
				method: 'post',
				url: 'notascopetencia-exec.php',
				data: 'act=saidateste&mesano='+msa+'&page_num='+page+''
			  })
				.then(function (data) {
					// handle success
						var htm="";						
											
						for(var i = 0; i < data.data.length; i++){
						
												
						htm += '<tr id="'+data.data[i].numero+'"  data-caminho="" class="warning">'+
									'<td>'+data.data[i].numero+'</td>'+
									'<td>'+data.data[i].dataemiss+'</td>'+
									'<td style="width: 33%;display:none;">'+data.data[i].chave+'</td>'+
									'<td style="text-align:center; width: 7%;display:none;">'+data.data[i].tipo+'</td>'+
									'<td>'+data.data[i].razao+'</td>'+
									'<td style="text-align:right; width: 10%;">'+number_format(data.data[i].valor,2,',','.')+'</td>'+
									'<td style="text-align:right; width: 10%;">'+number_format(data.data[i].totprod,2,',','.')+'</td>'+
									'<td  class="centeralign">'+								
									'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
									'<a href="#" onclick="BuscaNota(\''+data.data[i].numero+'\',\''+data.data[i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
									'</td>'+
								'</tr>';			
						}	
				
						$("#dyntable_notas tbody").append(htm);					
						
						$dTable = $('#dyntable_notas').dataTable({					
							"bSort" : false,
							"paging":   false,
							"ordering": true,
							"info":     false,
							"bDestroy": true,
							"bFilter": true,
							"dom": 'Bfrtip',
							"order": [[ 5, "desc" ]],
							"language": {
							   "url": "../plugins/datatables/Portuguese-Brasildefault.json",
							   "decimal": ",",
							   "thousands": "."
						   },
					   });	
					
				})
				.catch(function (error) {
					// handle error
					console.log(error);
				})
				.then(function () {
					// always executed
				});

		}

		currentscrollHeight = scrollHeight;
	}
});

$(document).on('click','.listanotasaida',function(){
	var msa = $('input[name="setmesanocomp"]').val();
	
	/*$("#responsive-modal").modal('show');	
	$("#myModal_pdf").show();
	$(".modal-body_PDF p").html("Aguarde Listando dados de saida...!");*/
	var lista_itens = JSON.parse(localStorage.getItem('lista-items') || '[]');
	
	if(lista_itens[0].length == 0){

			
			$.confirm({
				columnClass: 'col-md-12',
				containerFluid: true,
				type: 'orange',
				buttons: {
					Fechar: {
						text: 'Fechar',
						btnClass: 'btn-red',
						action: function(){
						}
					}
				},		
				content: function () {
					var self = this;
					return $.ajax({
						url: '../php/notascopetencia-exec.php',
						dataType: 'json',
						method: 'post',
						data:{act:'saida',mesano:msa},
					}).done(function (data) {
						
						arrayItens.push(data);
						localStorage.setItem("lista-items", JSON.stringify(arrayItens));

						var htm="";
						var valor_total = 0;
						var valor_total_prod = 0;
						htm +='<table id="dyntable_notas" class="table table-bordered" style="width:100%;">'+
						'<thead>'+
							'<tr>'+
							'<th style="text-align:center;">NOTA</th>'+
							'<th style="text-align:center;">DATA</th>'+
							'<th style="text-align:center;display:none;">CHAVE DE ACESSO</th>'+
							'<th style="text-align:center;display:none;">ENTRADA/SAIDA</th>'+
							'<th style="text-align:center;">CLIENTE/FORNECEDOR</th>'+
							'<th style="text-align:center;">VALOR TOTAL</th>'+
							'<th style="text-align:center;">VALOR TOTAL PRODUTOS</th>'+
							'<th style="text-align:center;">AÇÃO</th>'+
							'</tr>'+
						'</thead>'+
						'<tbody>';
								
							for(var i = 0; i < data.length; i++){
							
													
							htm += '<tr id="'+data[i].numero+'"  data-caminho="" class="warning">'+
									'<td>'+data[i].numero+'</td>'+
									'<td>'+data[i].dataemiss+'</td>'+
									'<td style="width: 33%;display:none;">'+data[i].chave+'</td>'+
									'<td style="text-align:center; width: 7%;display:none;">'+data[i].tipo+'</td>'+
									'<td>'+data[i].razao+'</td>'+
									'<td style="text-align:right; width: 10%;">'+number_format(data[i].valor,2,',','.')+'</td>'+
									'<td style="text-align:right; width: 10%;">'+number_format(data[i].totprod,2,',','.')+'</td>'+
									'<td  class="centeralign">'+								
										'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
										'<a href="#" onclick="BuscaNota(\''+data[i].numero+'\',\''+data[i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
									'</td>'+
									'</tr>';	

								valor_total = parseFloat(valor_total) + parseFloat(data[i].valor);			
								valor_total_prod = parseFloat(valor_total_prod) + parseFloat(data[i].totprod);	
							}	


						htm +='</tbody>'+
							'</table>';
						
							var htmtotals = '<div class="row lstsaida"><div class="col-md-7 col-lg-4 col-xlg-4">'+
													'<div class="card card-inverse card-warning">'+
														'<div class="box bg-warning text-center">'+
															'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
															'<h6 class="text-white">Valor total notas</h6>'+
														'</div>'+
													'</div>'+
												'</div>';	   
									
									htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
										'<div class="card card-inverse card-warning">'+
											'<div class="box bg-warning text-center">'+
												'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
												'<h6 class="text-white">Valor total Produtos</h6>'+
											'</div>'+
										'</div>'+
									'</div>';
									htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
													'<div class="card card-inverse card-warning">'+
														'<div class="box bg-warning text-center">'+
															'<h1 class="font-light text-white">'+data.length+'</h1>'+
															'<h6 class="text-white">Total de registros</h6>'+
														'</div>'+
													'</div>'+
												'</div></div>';	   
						
						self.setContent(''+htmtotals+'<br>'+htm);
						self.setTitle('NOTAS DE SAÍDA');
						var set = setInterval(function(){
							$dTable = $('#dyntable_notas').dataTable({					
								"bSort" : false,
								"paging":   false,
								"ordering": true,
								"info":     false,
								"bDestroy": true,
								"bFilter": true,
								"dom": 'Bfrtip',
								"order": [[ 5, "desc" ]],
								"language": {
									"url": "../plugins/datatables/Portuguese-Brasildefault.json",
									"decimal": ",",
									"thousands": "."
								},
							});	
							clearInterval(set);
						},900);
						
						
					}).fail(function(){
						self.setContent('Something went wrong.');
					});
				}
			});
	}else{
		$('.loadsaida').removeClass('hide');
		var htm="";
		var valor_total = 0;
		var valor_total_prod = 0;
		htm +='<table id="dyntable_notas" class="table table-bordered" style="width:100%;">'+
		'<thead>'+
			'<tr>'+
			'<th style="text-align:center;">NOTA</th>'+
			'<th style="text-align:center;">DATA</th>'+
			'<th style="text-align:center;display:none;">CHAVE DE ACESSO</th>'+
			'<th style="text-align:center;display:none;">ENTRADA/SAIDA</th>'+
			'<th style="text-align:center;">CLIENTE/FORNECEDOR</th>'+
			'<th style="text-align:center;">VALOR TOTAL</th>'+
			'<th style="text-align:center;">VALOR TOTAL PRODUTOS</th>'+
			'<th style="text-align:center;">AÇÃO</th>'+
			'</tr>'+
		'</thead>'+
		'<tbody>';
				
			for(var i = 0; i < lista_itens[0].length; i++){
			
									
			htm += '<tr id="'+lista_itens[0][i].numero+'"  data-caminho="" class="warning">'+
					'<td>'+lista_itens[0][i].numero+'</td>'+
					'<td>'+lista_itens[0][i].dataemiss+'</td>'+
					'<td style="width: 33%;display:none;">'+lista_itens[0][i].chave+'</td>'+
					'<td style="text-align:center; width: 7%;display:none;">'+lista_itens[0][i].tipo+'</td>'+
					'<td>'+lista_itens[0][i].razao+'</td>'+
					'<td style="text-align:right; width: 10%;">'+number_format(lista_itens[0][i].valor,2,',','.')+'</td>'+
					'<td style="text-align:right; width: 10%;">'+number_format(lista_itens[0][i].totprod,2,',','.')+'</td>'+
					'<td  class="centeralign">'+								
						'<a href="#" class="deletenotas"><span class="fa fa-remove fa-2x"></span></a>'+
						'<a href="#" onclick="BuscaNota(\''+lista_itens[0][i].numero+'\',\''+lista_itens[0][i].tipo+'\');"><span class="fa fa fa-file-text-o fa-2x"></span></a>'+
					'</td>'+
					'</tr>';	

				valor_total = parseFloat(valor_total) + parseFloat(lista_itens[0][i].valor);			
				valor_total_prod = parseFloat(valor_total_prod) + parseFloat(lista_itens[0][i].totprod);	
			}	


		htm +='</tbody>'+
			'</table>';
		
			var htmtotals = '<div class="row lstsaida"><div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-warning">'+
										'<div class="box bg-warning text-center">'+
											'<h1 class="font-light text-white">R$ '+number_format(valor_total,2,',','.')+'</h1>'+
											'<h6 class="text-white">Valor total notas</h6>'+
										'</div>'+
									'</div>'+
								'</div>';	   
					
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
						'<div class="card card-inverse card-warning">'+
							'<div class="box bg-warning text-center">'+
								'<h1 class="font-light text-white">R$ '+number_format(valor_total_prod,2,',','.')+'</h1>'+
								'<h6 class="text-white">Valor total Produtos</h6>'+
							'</div>'+
						'</div>'+
					'</div>';
					htmtotals +='<div class="col-md-7 col-lg-4 col-xlg-4">'+
									'<div class="card card-inverse card-warning">'+
										'<div class="box bg-warning text-center">'+
											'<h1 class="font-light text-white">'+lista_itens[0].length+'</h1>'+
											'<h6 class="text-white">Total de registros</h6>'+
										'</div>'+
									'</div>'+
								'</div></div>';	   
		
		//self.setContent(''+htmtotals+'<br>'+htm);
		//self.setTitle('NOTAS DE SAÍDA');
		$.confirm({
			title: 'NOTAS DE SAÍDA',
			content: ''+htmtotals+'<br>'+htm,
			type: 'orange',
			columnClass: 'col-md-12',
			containerFluid: true,
			animation: 'rotateXR',
			buttons: {
				Fechar: {
					text: 'Fechar',
					btnClass: 'btn-red',
					action: function(){
					}
				}
			}
		});

		var set = setInterval(function(){
			$dTable = $('#dyntable_notas').dataTable({					
				"bSort" : false,
				"paging":   false,
				"ordering": true,
				"info":     false,
				"bDestroy": true,
				"bFilter": true,
				"dom": 'Bfrtip',
				"order": [[ 5, "desc" ]],
				"language": {
					"url": "../plugins/datatables/Portuguese-Brasildefault.json",
					"decimal": ",",
					"thousands": "."
				},
			});	
			$('.loadsaida').addClass('hide');
			clearInterval(set);
		},900);
	}
	return false;
});

function ListaNotasdesaida(){
	var msa = $('input[name="setmesanocomp"]').val();
	axios({
		method: 'post',
		url: 'notascopetencia-exec.php',
		data: 'act=saida&mesano='+msa+''
	  })
		.then(function (data) {
			// handle success
			
			arrayItens.push(data.data);
			localStorage.setItem("lista-items", JSON.stringify(arrayItens));
			$('.loadsaida').addClass('hide');
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});
}

function ListaNotasdesentrada(){
	var msa = $('input[name="setmesanocomp"]').val();
	axios({
		method: 'post',
		url: 'notascopetencia-exec.php',
		data: 'act=entrada&mesano='+msa+''
	  })
		.then(function (data) {
			// handle success
			
			arrayItens2.push(data.data);
			localStorage.setItem("lista-items2", JSON.stringify(arrayItens2));
			$('.loadentrada').addClass('hide');
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});
}

$(document).on('click','.revalidarprocesso',function(){
	
	valida_nota_novamente();
	
});

$(document).on('click','.detailsgta',function(){

	var numero = $(this).parents('tr').attr('id');
	listagtanota(numero);

});

function errocfop(cfo){
	//var dialog;
	$.ajax({
		type: 'POST',			
		url : '../php/lancamentos-exec.php',
		data: {'act':'validacaocfop',arr:cfo},
		cache:false,	
		beforeSend: function(){
			/*dialog = $.dialog({
						title: 'AGUARDE!',
						closeIcon: false,
						content: '<div align="center"><img src="../images/ajax_loading.gif" width:"20"/></div>',
					});*/
		},
		success: function(data){
			//dialog.close();
																		
		},
		error: function(data){
			//dialog.close();
			//alert(data);	
		}
	});
	
}

function parseDateValue(rawDate) {
	var dateArray= rawDate.split("/");
	var parsedDate= dateArray[2] + dateArray[0] + dateArray[1];
	return parsedDate;
}

function BuscaNota(str,tipo){
	/*$dTable.api()
        .columns( 0 )
        .search( str )
        .draw();*/
	
	
	/*var pos = $('#dyntable_notas_valid tbody tr[id="'+str+'"]').position().top;
    $('html, body').animate({
        scrollTop: pos		
    }, 1000);*/
	//$(".details").click();
	detailsnotas(str,tipo);
}

function BuscaNotaGta(str){
	$dTable.api()
        .columns( 0 )
        .search( str )
        .draw();

        var pos = $('#dyntable_notas_valid tbody tr[id="'+str+'"]').position().top;
    $('html, body').animate({
        scrollTop: pos		
    }, 1000);

    listagtanota(str);

}

$('select[name="entsai"]').on( 'change',function(){
   
    $dTable.api()
        .columns( 3 )
        .search( this.value )
        .draw();
});

function listagtanota(nnota){

	
	$.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/gta-exec.php",
		data:{act:'listar',numero:nnota},
		beforeSend: function(){
			
		},
		success: function(data){
			
			var htm = ''; 	

			htm += '<form id="frmgta">';
			htm += '<div class="form_callback"></div>';
				htm += '<input type="hidden" name="act" value="inserir"/>';
				htm += '<input type="hidden" name="nota" value="'+nnota+'"/>';
				htm += '<div class="input-group">';
                    htm += '<span class="input-group-addon" id="basic-addon1">*</span>';
                    htm += '<input type="text" class="form-control" placeholder="número gta" name="gta" aria-describedby="basic-addon1">';
                htm += '</div><br/>';
                htm += '<button class="btn btn-primary btn-block" type="submit">SALVAR</button>';
			htm += '</form>';	

			htm += '<table id="tabgta" class="table table-bordered dataTable no-footer">';
				htm += '<thead>';
					htm += '<tr>';				
						htm += '<th>#</th>';
						htm += '<th>GTA</th>';
						htm += '<th>AÇÃO</th>';
					htm += '</tr>';		
				htm += '</thead>';
			htm += '<tbody>';	
			for(var i =0; i < data.length; i++){

				htm += '<tr id="'+data[i].cod+'">';	
					htm += '<td>'+data[i].cod+'</td>';
					htm += '<td>'+data[i].numero_gta+'</td>';
					htm += '<td><a href="#" class="gtadelete"><span class="fa fa fa-times-circle fa-2x"></span></a></td>';
				htm += '</tr>';

			}	
			htm += '</tbody>';
			htm += '</table>';	
			

			balao2 = $.confirm({
					title: 'Formulário GTA',
					content: ''+htm+'',
					type: 'green',
					typeAnimated: true,
					columnClass: 'col-md-4 col-md-offset-0 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
					buttons: {					
						close: function () {
						}
					}
				});


		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;


}

$(document).on('submit','#frmgta',function(){

	var param = $(this).serialize();
	var dialog;
	
	if($("input[name='gta']").val().trim() == ''){
		var view = '<div class="message info">Digite um número de gta!</div>';
		$(".form_callback").html(view);
		$(".message").effect("bounce");
		return false;
	}

	$.ajax({
		type: 'POST',			
		url : '../php/gta-exec.php',
		data: param,
		dataType: "json",
		cache:false,	
		beforeSend: function(){
			dialog = $.dialog({
						title: 'AGUARDE!',
						closeIcon: false,
						content: '<div align="center"><img src="../images/ajax_loading.gif" width:"20"/></div>',
					});
		},
		success: function(data){
			dialog.close();
			
			var htm = '';
			
			htm += '<tr id="'+data[0].cod+'" class="rowgta">';	
					htm += '<td>'+data[0].cod+'</td>';
					htm += '<td>'+data[0].gta+'</td>';
					htm += '<td><a href="#" class="gtadelete"><span class="fa fa fa-times-circle fa-2x"></span></a></td>';
			htm += '</tr>';				

			$("#tabgta tbody").append(htm);	
			$("input[name='gta']").val('');
			$("input[name='gta']").focus();	

				

			$("[data-id='gtanota_"+data[0].nota+"']").append('<i class="fa fa-check text-success"></i>');
			$("[data-id='gtanota_"+data[0].nota+"']").removeClass('text-warning');
			$("[data-id='gtanota_"+data[0].nota+"']").addClass("text-success");

		},
		error: function(data){
			alert(data);	
		}
	});

	return false;
});

$(document).on("click",".gtadelete",function(){

	var cod  = $(this).parents('tr').attr('id');
	var nota = $("input[name='nota']").val();

	var conf = confirm("Deseja deletar ?");
	if(conf){
		$.ajax({
			type:'POST',
			async:false, 
			url:"../php/gta-exec.php",
			data:{act:'delete',cod:cod},
			success: function(data){
				
				var arr = $.parseJSON(data);
				
				$("#tabgta tbody tr[id='"+cod+"']").remove();
				
				if($(".rowgta").length == 0){
					$("[data-id='gtanota_"+nota+"']").html(' Número da nota ('+nota+') Não existem números de GTA informados!');
					$("[data-id='gtanota_"+nota+"']").removeClass('text-success');
					$("[data-id='gtanota_"+nota+"']").addClass("text-warning");
				}

			},
			error:function(data){

			}
		});
	}
	return false;
});

$(document).ready(function(e) {
	
	$dateControls= $("#baseDateControl").children("div").clone();
	$("#feedbackTable_filter").prepend($dateControls);
		
	
	/*$("#dataini").keyup ( function() { $dTable.fnDraw(); } );
	$("#dataini").change( function() { $dTable.fnDraw(); } );
	$("#datafin").keyup ( function() { $dTable.fnDraw(); } );
	$("#datafin").change( function() { $dTable.fnDraw(); } );*/
		
});

$('input[name="RadioGroup1"]').on( 'change',function(){
	
	var rad = $(this).is(":checked");
	
	$('#dyntable_notas > tbody  > tr').each(function() {
		//alert($(this).attr('id')+' - '+$(this).not(".warning"));					
				
		if(rad == true){
			$(this).not(".warning,.info").hide();
			//$(this).not(".info").hide();		
		}else{
			$(this).show();
		}
		
	});

});

$(".nav-tabs li a").on('click',function(){
	
	if($(this).attr("href") == "#xml"){
		$(".importaporxml").show();
		$(".importamanual").hide();
	}else if($(this).attr("href") == "#manual"){
		$(".importaporxml").hide();
		$(".importamanual").show();
	}else{
		$(".importamanual").hide();
		$(".importaporxml").hide();
	}
	
});


$(document).ready(function(){
	
	$("#clifor").autocomplete(
	{	
		 source:'../php/empresastxt-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {

			$("#idclifor").val(ui.item.cod);	

			var idcfop  = $('#idcfop').val();
			var devol   = $('#idcfop').attr('data-tipo');
			var cnpjemp = $(`input[name="cnpjemp"]`).val();
			
			$("#idclifor").attr('data-tipo',cnpjemp);

			//console.log(idcfop,' - ',devol,' - ',ui.item.cnpj_cpf);
			if(idcfop.trim() < 5000 && devol.trim() == 'N'){
				$('select[name="es"]').val('E').change();
				$('select[name="es"] option[value=E]').attr('selected','selected').change();

			}else if(idcfop.trim() > 5000 && devol.trim() == 'S' && ui.item.cnpj_cpf == cnpjemp){
				$('select[name="es"]').val('E').change();
				$('select[name="es"] option[value=E]').attr('selected','selected').change();
			}else if(idcfop.trim() > 5000 && devol.trim() == 'S' && ui.item.cnpj_cpf != cnpjemp){
				$('select[name="es"]').val('S').change();
				$('select[name="es"] option[value=S]').attr('selected','selected').change();
			}else if(idcfop.trim() > 5000 && devol.trim() == 'N' && ui.item.cnpj_cpf == cnpjemp){
				$('select[name="es"]').val('S').change();
				$('select[name="es"] option[value=S]').attr('selected','selected').change();
			}else if(idcfop.trim() > 5000 && devol.trim() == 'N' && ui.item.cnpj_cpf == cnpjemp){
				$('select[name="es"]').val('S').change();
				$('select[name="es"] option[value=S]').attr('selected','selected').change();
			}else if(idcfop.trim() < 5000 && devol.trim() == 'S'){
				$('select[name="es"]').val('S').change();
				$('select[name="es"] option[value=S]').attr('selected','selected').change();
			}

		},
		focus: function( event, ui ) {


		}
	});
	
	$("#cfop").autocomplete(
	{	
		 source:'../php/cfop-exec.php?act=busca',
		 minLength: 1,
		select: function(event, ui) {

			$("#idcfop").val(ui.item.cod);			
			$("#idcfop").attr('data-tipo',ui.item.dev);
			var cnpjemp  = $(`input[name="cnpjemp"]`).val();
			var cnpj_cpf = $("#idclifor").attr('data-tipo');
			var devol    = ui.item.dev;

			if(cnpj_cpf != ''){
				if(ui.item.cod.trim() < 5000 && devol.trim() == 'N'){
					$('select[name="es"]').val('E').change();
					$('select[name="es"] option[value=E]').attr('selected','selected').change();

				}else if(ui.item.cod.trim() > 5000 && devol.trim() == 'S' && cnpj_cpf == cnpjemp){
					$('select[name="es"]').val('E').change();
					$('select[name="es"] option[value=E]').attr('selected','selected').change();
				}else if(ui.item.cod.trim() > 5000 && devol.trim() == 'S' && cnpj_cpf != cnpjemp){
					$('select[name="es"]').val('S').change();
					$('select[name="es"] option[value=S]').attr('selected','selected').change();
				}else if(ui.item.cod.trim() > 5000 && devol.trim() == 'N' && cnpj_cpf == cnpjemp){
					$('select[name="es"]').val('S').change();
					$('select[name="es"] option[value=S]').attr('selected','selected').change();
				}else if(ui.item.cod.trim() > 5000 && devol.trim() == 'N' && cnpj_cpf == cnpjemp){
					$('select[name="es"]').val('S').change();
					$('select[name="es"] option[value=S]').attr('selected','selected').change();
				}else if(ui.item.cod.trim() < 5000 && devol.trim() == 'S'){
					$('select[name="es"]').val('S').change();
					$('select[name="es"] option[value=S]').attr('selected','selected').change();
				}
			}
		},
		focus: function( event, ui ) {


		}
	});
	
	$("#produto").autocomplete(
	{	
		 source:'../php/produto-exec.php?act=buscaproduto',
		 minLength: 1,
		select: function(event, ui) {

			$("#idproduto").val(ui.item.cod);	

		},
		focus: function( event, ui ) {


		}
	});
	
	$('#dyntable_movim').dataTable({					
		 "bSort" : false,
		 "paging":   false,
		 "ordering": false,
		 "info":     false,
		 "bFilter": false,
		  "order": [],
		  "language": {
			"url": "../plugins/datatables/Portuguese-Brasildefault.json"
		},	
	});	
	
	//$("#valor").attr("disabled",true);
	//$("#valorcarcasa").attr("disabled",true);
});

$(document).on('change','select[name="tipo"]',function(){
	
	//alert($(this).val());
	/*
	if($(this).val() == "V"){
		
		$("#valor").attr("disabled",false);
		$("#valorcarcasa").attr("disabled",true);
		
	}else if($(this).val() == "R"){
		
		$("#valor").attr("disabled",true);
		$("#valorcarcasa").attr("disabled",false);
		
	}else{
		$("#valor").attr("disabled",true);
		$("#valorcarcasa").attr("disabled",true);
		
	}*/
	
});


$(document).on('submit','form[id="frmmovimentacao"]',function(){
	
	//alert($(this).serialize());
	var params = $(this).serialize();
	var dialog;
	var cont   = 0;
	
	if($("#qtd").val() == ""){
		alert("Digite a quantidade!");
		$("#qtd").focus();
		return false;
	}
	
	/*if($('select[name="tipo"]').val() == 'V'){
		
		if($("#valor").val() == "" || $("#valor").val() == 0 || $("#valor").val() == "0,00" || $("#valor").val() == "0.00" || $("#valor").val() == "00"){
					
			alert("Digite um valor!");
			$("#valor").focus();
			return false;	
		}
	}else if($('select[name="tipo"]').val() == 'R'){
		if($("#valorcarcasa").val() == "" || $("#valorcarcasa").val() == 0 || $("#valorcarcasa").val() == "0,00" || $("#valorcarcasa").val() == "0.00" || $("#valorcarcasa").val() == "00"){
					
			alert("Digite um valor!");
			$("#valorcarcasa").focus();
			return false;	
		}
	}else{
		$('select[name="tipo"]').focus();
		return false;
	}*/
	
	
	if($("#precoquilo").val() == "" || $("#precoquilo").val() == 0 || $("#precoquilo").val() == "0,00" || $("#precoquilo").val() == "0.00" || $("#precoquilo").val() == "00"){					
			alert("Digite um valor!");
			$("#precoquilo").focus();
			return false;	
		}
	
	
	$.ajax({
		type: 'POST',			
		url : '../php/lancamentos-exec.php',
		data: params,
		dataType: "json",
		cache:false,	
		beforeSend: function(){
			dialog = $.dialog({
						title: 'AGUARDE!',
						closeIcon: false,
						content: '<div align="center"><img src="../images/ajax_loading.gif" width:"20"/></div>',
					});
		},
		success: function(data){
			dialog.close();
			
			var htm = "";
			/*var numero = $("#dyntable_movim tbody tr[class='regmovim']").attr('id') == undefined ? 0 : parseInt($("#dyntable_movim tbody tr[class='regmovim']").attr('id'));
			alert(numero);
			cont =  parseInt(numero) + 1;*/
			if(data[0].tipo == 'inserir'){
				if($('select[name="es"]').val() == 'E'){
					var peso = '<input type="hidden" name="item['+data[0].idprox+'][valor]" value="'+data[0].cabeca+'"/> <input type="hidden" name="item['+data[0].idprox+'][valorcarcasa]" value="0,00"/>'+data[0].cabeca+'';	
				}else{
					var peso = '<input type="hidden" name="item['+data[0].idprox+'][valor]" value="'+data[0].valor+'"/> <input type="hidden" name="item['+data[0].idprox+'][valorcarcasa]" value="0,00"/>'+data[0].valor+'';
				}
				$("#dyntable_movim tbody tr[class='odd']").remove();
				var total = parseFloat(convertevalores(data[0].precoquilo)) * parseFloat(data[0].quantidade);
				htm += '<tr id="'+data[0].idprox+'" class="itensmov">'+
						'<td><input type="hidden" name="item['+data[0].idprox+'][idproduto]" value="'+data[0].idproduto+'"/>'+data[0].produto+'</td>'+
						'<td style="text-align:right;">'+peso+'</td>'+
						'<td style="text-align:center;"><input type="hidden" name="item['+data[0].idprox+'][quantidade]" value="'+number_format(data[0].quantidade,2,',','.')+'"/> '+number_format(data[0].quantidade,2,',','.')+'</td>'+					
						'<td style="text-align:right;"><input type="hidden" name="item['+data[0].idprox+'][precoquilo]" value="'+data[0].precoquilo+'"/>'+data[0].precoquilo+'</td>'+
						'<td style="text-align:right;">'+data[0].total+'</td>'+
						'<td style="text-align:center;"><a href="#" class="regmovimremov"><i class="fa fa-times fa-2x"></i></a><a href="#" class="regedit"><i class="fa fa-pencil fa-2x"></i></a></td>'+
					'</tr>';
				
			
				$("#dyntable_movim tbody").append(htm);

				var subtotalprodnfe = $("#subtotalprodnfe").val().replace(/[.]/g, '');				
				var sb  = parseFloat(subtotalprodnfe) == '0,00' ? 0 : parseFloat(subtotalprodnfe.replace(/[,]/g, '.'));	
				console.log(subtotalprodnfe);	
				console.log(sb);				
				var sbp = parseFloat(total);				
				var res = parseFloat(sb) + parseFloat(sbp);
				console.log(sb);
				console.log(sbp);				
				$("#subtotalprodnfe").val(number_format(res,2,',','.'));
				$(".totsubtotal").html("<b>"+number_format(res,2,',','.')+"</b>");	

				$(".nd").hide();
			}else{
				$("#id").remove();
				
				var total = parseFloat(convertevalores(data[0].precoquilo)) * parseFloat(data[0].quantidade);
				$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(0)').html('<input type="hidden" name="item['+data[0].idprox+'][idproduto]" value="'+data[0].idproduto+'"/><strong>'+data[0].produto+'</strong>');
				if($('select[name="es"]').val() == 'E'){
					var peso = '<input type="hidden" name="item['+data[0].idprox+'][valor]" value="'+data[0].cabeca+'"/> <input type="hidden" name="item['+data[0].idprox+'][valorcarcasa]" value="0,00"/>'+data[0].cabeca+'';				
					$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(1)').html(peso);
				}else{			
					var peso = '<input type="hidden" name="item['+data[0].idprox+'][valor]" value="'+data[0].valor+'"/> <input type="hidden" name="item['+data[0].idprox+'][valorcarcasa]" value="0,00"/>'+data[0].valor+'';						
					$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(1)').html(peso);
				}
				
				$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(2)').html('<input type="hidden" name="item['+data[0].idprox+'][quantidade]" value="'+number_format(data[0].quantidade,2,',','.')+'"/> '+number_format(data[0].quantidade,2,',','.'));
				$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(3)').html('<input type="hidden" name="item['+data[0].idprox+'][precoquilo]" value="'+data[0].precoquilo+'"/>'+data[0].precoquilo);
				$('#dyntable_movim tbody tr[id="'+data[0].idprox+'"] td:eq(4)').html(data[0].total);
				$('#actdados').val('pegadados');
				
			var subtotalprodnfe = $("#subtotalprodnfe").val().replace(/[.]/g, '');				
			var sb  = parseFloat(subtotalprodnfe) == '0,00' ? 0 : parseFloat(subtotalprodnfe.replace(/[,]/g, '.'));					
			var sbp = parseFloat(total);				
			var res = parseFloat(sb) + parseFloat(sbp);
							
			$("#subtotalprodnfe").val(number_format(res,2,',','.'));
			$(".totsubtotal").html("<b>"+number_format(res,2,',','.')+"</b>");					
				
			}


			$('#idproduto').val("");
			$('#produto').val("");
			$("#qtd").val("");
			$("#valor").val("");
			$("#pecas").val("");
			$("#cabeca").val("");			
			$("#precoquilo").val("");
			$("#subtotals").val("");
			$("#subtotal").val("");	
					
			$('#produto').focus();
			
																		
		},
		error: function(data){
			alert(data);	
		}
	});
		
	
	
	
	return false;
	
});



$(document).ready(function(e) {
	  $("#precoquilo").keyup(function(e){				
			/*var vlid = validate($(this).val());
			if(vlid == true){*/
			
				if($("input[name='produto']").val() == ""){
					$("input[name='produto']").focus();
					alert('Vamos pesquisar um produto primeiro!');
					return false;
				}
				
				if($(this).val()){
					//$("#valorcarcasa").val('');
					var qtd  = convertevalores($("#qtd").val());			
					var vl   = convertevalores($(this).val());
					var subt = qtd * vl;
					$("#subtotals").val(number_format(subt,2,',','.'));
					$("#subtotal").val(number_format(subt,2,',','.'));
		
				}else{
				
					$("#subtotals").val('');
					$("#subtotal").val('');
				}
			/*}else{
				$(this).val(vlid);
				alert("Somente numeros");
			}*/
	  });

	
	$("#qtd").keyup(function(e){
		
	
		if($("select[name='produto'] option:selected").val() == ""){
			$("select[name='produto']").focus();
			alert('Vamos pesquisar um produto primeiro!');
			return false;
		}
		
		
		if($("#precoquilo").val()){

			var qtd  = convertevalores($(this).val());			
			var vl   = convertevalores($("#precoquilo").val());
			var subt = qtd * vl;
			$("#subtotals").val(number_format(subt,2,',','.'));
			$("#subtotal").val(number_format(subt,2,',','.'));

		}else{

			$("#subtotals").val('');
			$("#subtotal").val('');
		}
		
		
	});
	
});

$(document).on('click','#dyntable_movim tbody tr[class="regmovim"]',function(){

		$('.selected').removeClass('selected');
		$(this).addClass("selected");
		var product = $(this).attr("id");		
		
		//alert(product);
		$('.selected').removeClass('selected');
		var conf = confirm('Continue delete?');
			
	    if(conf){
               
			DiminuiSobtotalProdutos($.trim($('#dyntable_movim tbody tr[id="'+product+'"] td:eq(4)').html()));
														
				$('#dyntable_movim tbody tr[id="'+product+'"]').remove();

				if($("#dyntable_movim tbody tr[class='regmovim']").length == 0){
					
					$("#dyntable_movim tbody").html('<tr class="odd"><td colspan="5" class="dataTables_empty" valign="top">Não há dados disponíveis na tabela</td></tr>');
					
				}		
					
					
		}else{
			$('.selected').removeClass('selected');
		}
			
	    return false;
		
		
});


$(document).on('click','.regmovimremov',function(){

	
	var product = $(this).parents('tr').attr('id');		
	
	console.log(product);
	
	var conf = confirm('Continue delete?');
	//console.log($("#dyntable_movim tbody tr[class='itensmov']").length);
	if(conf){
		   
		DiminuiSobtotalProdutos($.trim($('#dyntable_movim tbody tr[id="'+product+'"] td:eq(4)').html()));
													
			$('#dyntable_movim tbody tr[id="'+product+'"]').remove();

			if($("#dyntable_movim tbody tr[class='itensmov']").length == 0){
				
				$("#dyntable_movim tbody").html('<tr class="odd"><td colspan="5" class="dataTables_empty" valign="top">Não há dados disponíveis na tabela</td></tr>');
				
			}
				
				
	}else{
		$('.selected').removeClass('selected');
	}
		
	return false;
	
	
});

$(document).on('click','.regedit',function(e){
	String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}
	var id =  $(this).parents('tr').attr('id');
	var codprod  = $('input[name="item['+id+'][idproduto]"]').val();
	var descprod = $('#dyntable_movim tbody tr[id="'+id+'"] td:eq(0)').html().stripHTML().split('-')[1].trim();
	var pecas    = $('#dyntable_movim tbody tr[id="'+id+'"] td:eq(1)').html().stripHTML();
	var qtd      = $('#dyntable_movim tbody tr[id="'+id+'"] td:eq(2)').html().stripHTML();
	var preco    = $('#dyntable_movim tbody tr[id="'+id+'"] td:eq(3)').html().stripHTML();
	var subtotal = $('#dyntable_movim tbody tr[id="'+id+'"] td:eq(4)').html().stripHTML();
	
	var subtotalprodnfe =  $("#subtotalprodnfe").val().replace(/[.]/g, '');
	var subtotalpr      =  subtotal.replace(/[.]/g, '');
	var novovalor       = parseFloat(subtotalprodnfe.replace(/[,]/g, '.')) - parseFloat(subtotalpr.replace(/[,]/g, '.'));

	$("#subtotalprodnfe").val(number_format(novovalor,2,',','.'));

	$('#produto').val(descprod);
	$("#idproduto").val(codprod);
	$('#pecas').val(pecas);
	$('#cabeca').val(pecas);
	$('#qtd').val(qtd);
	$('#precoquilo').val(preco);
	$('#subtotals').val(subtotal);
	$("#actdados").val('alterdados');
	$("#frmmovimentacao").append('<input type="hidden" name="id" id="id" value="'+id+'"/>');
});


function SomaSobtotalProdutos(val){
	//console.log($("#subtotalprodnfe").val());
	
	var sb  = parseFloat($("#subtotalprodnfe").val()) == '0,00' ? 0 : convertevalores($("#subtotalprodnfe").val());	
	var sbp = convertevalores(val);
	//console.log(sb+ ' - '+sbp);
	var res = parseFloat(sb) + parseFloat(sbp);
	
	$("#subtotalprodnfe").val(number_format(res,2,',','.'));
	$(".totsubtotal").html("<b>"+number_format(res,2,',','.')+"</b>");
}

function DiminuiSobtotalProdutos(val){
	var subtotalprodnfe = $("#subtotalprodnfe").val().replace(/[.]/g, '');
	var val 			= val.replace(/[.]/g, '');
	var sb  			= parseFloat(subtotalprodnfe.replace(/[,]/g, '.'));	
	var sbp 			= parseFloat(val.replace(/[,]/g, '.'));

	var res = parseFloat(sb) - parseFloat(sbp);
	
	$("#subtotalprodnfe").val(number_format(res,2,',','.'));
	$(".totsubtotal").html("<b>"+number_format(res,2,',','.')+"</b>");
}

$(document).on('click','#finaliza',function(){
	
	/*alert($('form[id="frmmovimentacao"]').serialize());
	alert($('form[id="frmmanualnotas"]').serialize());*/
	
	if($("#dyntable_movim tbody tr[class='itensmov']").length == 0){
		alert('Falta Inserir os produtos');
		return false;
	}
	
	if($('select[name="es"]').val() == ""){
		alert("Selecione se Entrada ou Saida");
		$('select[name="es"]').focus();
		return false;
	}
	
	if($("#dataemiss").val()== ""){
		alert("Digite uma Data De Emissão");
		$("#dataemiss").focus();
		return false;
	}
	
	if($("#numero_nota").val() == ""){
		alert("Digite um Número da nota");
		$("#numero_nota").focus();
		return false;
	}
	
	if($("#idclifor").val() == ''){
		alert("Pesquise e clique em um Cliente ou fornecedor!");
		$("#clifor").focus();
		return false;
	}
	
	if($('select[name="es"]').val() == 'E'){
		if($('select[name="tipo"]').val() == ""){
			alert("Selecione um Tipo Vivo ou Rendimento!");  
			$('select[name="tipo"]').focus();
			return false;
		}
		
		/*if($("#gta").val() == ""){
			alert("Digite uma Gta");
			$("#gta").focus();
			return false;
		}	*/	   
				
		if($('select[name="condenas"]').val() == ""){
			alert("Seleciona se condena SIM OU NÃO!");
			$('select[name="condenas"]').focus();
			return false;
		}

		if($('select[name="abate"]').val() == ""){
			alert("Selecione se abate Proprio ou terceiro!");
			$('select[name="abate"]').focus();
			return false;
		}
		
		if($('#dataabate').val() == ""){
			alert("Selecione ou digite uma data de abate!");
			$('#dataabate').focus();
			return false;
		}
	}
	if($("#idcfop").val() == ""){
		alert("Pesquise e selecione uma CFOP!");
		$("#cfop").focus();
		return false;
		
	}
	
	if($('select[name="es"]').val() == "S"){
		/*if($("#valoricms").val() == ""){
			alert("Digite um ICMS");
			$("#valoricms").focus();
			return false;
		}	*/
		
		/*if($("#valoricmssubs").val() == ""){
			alert("Digite um ICMS SUBS");
			$("#valoricmssubs").focus();
			return false;
		}*/
	}
	
	var param = $('form[id="frmmanualnotas"]').serialize()+'&'+$('form[id="frmmovimentacaoprod"]').serialize();
	
	//alert(param);
	
	$.ajax({
		type: 'POST',			
		url : '../php/lancamentos-exec.php',
		data: param,
		dataType: "json",
		cache:false,	
		beforeSend: function(){
			dialog = $.dialog({
						title: 'AGUARDE!',
						closeIcon: false,
						content: '<div align="center"><img src="../images/ajax_loading.gif" width:"20"/></div>',
					});
		},
		success: function(data){
			dialog.close();
			
			alert(data[0].msg);
			window.location.reload();
																		
		},
		error: function(data){
			dialog.close();
			alert(data);	
		}
	});
	
	
});

$(document).ready(function(e){
	$(".tpsaida").hide();
	$(".tpentrada").hide();
	$('select[name="es"]').change(function(){
		var act    = $('input[name="act"]').val();
		if(act != 'alteracanotamanual'){
			$(".hidetipo").removeClass('hide');
			if($(this).val() == 'E'){
				$(".sosaida").hide();
				$(".sosaida").addClass('hide');
				$(".tpsaida").hide();
				$(".tpentrada").show();
			}else if($(this).val() == 'S'){
				$(".sosaida").show();
				$(".sosaida").removeClass('hide');
				$(".tpsaida").show();
				$(".tpentrada").hide();
			}else{
				$(".sosaida").hide();
				$(".sosaida").addClass('hide');
				$(".tpsaida").hide();
				$(".tpentrada").hide();
				$(".hidetipo").addClass('hide');
			}
		}
	});
	

	$("#numero_nota").blur(function() {
		var numero = $(this).val();
		var tipo   = $('select[name="es"] option:selected').val();
		var act    = $('input[name="act"]').val();

		if(act == 'alteracanotamanual'){
			axios({
				method: 'post',
				url: 'lancamentos-exec.php',
				data: 'act=verificanotaalteracao&tipo='+tipo+'&numero='+numero+''
			})
				.then(function (data) {
						
					//sconsole.log(data.data);

						
					if(data.data.length > 0){
						$(".form_callback").html('');

						if($("#dyntable_movim tbody tr[class='itensmov']").length > 0){
							var conf = confirm("Deseja carregar os itens novamente ?");
							if(conf == false){
								return false;
							}else{
								$("#dyntable_movim tbody").html('');
							}
						}
						$(".hidetipo").removeClass('hide');

						if(data.data[0].tipo == 'E'){
							$(".sosaida").hide();
							$(".sosaida").addClass('hide');
							$(".tpsaida").hide();
							$(".tpentrada").show();
							
							var htm = '';
							$('#dataemiss').val(data.data[0].data_emissao);
							$("#clifor").val(data.data[0].razao);
							$("#idclifor").val(data.data[0].idemp);
							$("#cfop").val(data.data[0].cfop+' - '+data.data[0].nomecfop);
							$("#idcfop").val(data.data[0].cfop);
							$('select[name="tipo"] option[value="'+data.data[0].tipo_v_r_a+'"]').attr('selected',true);
							$('#gta').val(data.data[0].gta);
							$('select[name="condenas"] option[value="'+data.data[0].condenas+'"]').attr('selected',true);
							$('select[name="abate"] option[value="'+data.data[0].abate+'"]').attr('selected',true);
							$('#dataabate').val(data.data[0].data_abate);
							$("#dyntable_movim tbody tr[class='odd']").remove();

							var tot_sub = 0;	
							for(var i = 0; i < data.data[0].det.length; i++){
								var item 	 = data.data[0].det[i];						
								var peso 	 = '<input type="hidden" name="item['+item.id+'][valor]" value="'+item.qtd_cabeca+'"/> <input type="hidden" name="item['+item.id+'][valorcarcasa]" value="0,00"/>'+item.qtd_cabeca+'';
								if(item.tipo_r_v == 'R'){
									var subtotal = parseFloat(item.preco_quilo) * parseFloat(item.peso_carcasa);
									var qtd = item.peso_carcasa;
								}else{
									var subtotal = parseFloat(item.preco_quilo) * parseFloat(item.peso_vivo_cabeca);
									var qtd = item.peso_vivo_cabeca;
								}	
								tot_sub = parseFloat(tot_sub) + parseFloat(subtotal);
								htm = '<tr id="'+item.id+'" class="itensmov">'+
											'<td><input type="hidden" name="item['+item.id+'][idproduto]" value="'+item.codigo_produto+'"/><strong>'+item.codigo_produto+' - '+item.desc_prod+'</strong></td>'+
											'<td style="text-align:right;">'+peso+'</td>'+
											'<td style="text-align:center;"><input type="hidden" name="item['+item.id+'][quantidade]" value="'+number_format(qtd,2,',','.')+'"/> '+number_format(qtd,2,',','.')+'</td>'+					
											'<td style="text-align:right;"><input type="hidden" name="item['+item.id+'][precoquilo]" value="'+number_format(item.preco_quilo,2,',','.')+'"/>'+number_format(item.preco_quilo,2,',','.')+'</td>'+
											'<td style="text-align:right;">'+number_format(subtotal,2,',','.')+'</td>'+
											'<td style="text-align:center;"><a href="#" class="regmovimremov"><i class="fa fa-times fa-2x"></i></a><a href="#" class="regedit"><i class="fa fa-pencil fa-2x"></i></a></td>'+
										'</tr>';
										
								$("#dyntable_movim tbody").append(htm);
							}

						}else if(data.data[0].tipo == 'S'){
							
							$(".sosaida").show();
							$(".sosaida").removeClass('hide');
							$(".tpsaida").show();
							$(".tpentrada").hide();					
							$('#dataemiss').val(data.data[0].data_emissao);
							$("#clifor").val(data.data[0].razao);
							$("#idclifor").val(data.data[0].idemp);
							$("#cfop").val(data.data[0].cfop+' - '+data.data[0].nomecfop);
							$("#idcfop").val(data.data[0].cfop);
							$("#valoricms").val(number_format(data.data[0].valor_icms,2,',','.'));
							$("#valoricmssubs").val(number_format(data.data[0].valor_icms_subs,2,',','.'));	
							$("#dyntable_movim tbody tr[class='odd']").remove();

							var htm = '';
							var tot_sub = 0;	

							for(var i = 0; i < data.data[0].det.length; i++){
								var item 	 = data.data[0].det[i];						
								var peso 	 = '<input type="hidden" name="item['+item.id+'][valor]" value="'+item.qtd_pecas+'"/> <input type="hidden" name="item['+item.id+'][valorcarcasa]" value="0,00"/>'+item.qtd_pecas+'';
								var subtotal = parseFloat(item.preco_unitario) * parseFloat(item.peso);
								tot_sub = parseFloat(tot_sub) + parseFloat(subtotal);
								htm = '<tr id="'+item.id+'" class="itensmov">'+
											'<td><input type="hidden" name="item['+item.id+'][idproduto]" value="'+item.codigo_produto+'"/><strong>'+item.codigo_produto+' - '+item.desc_prod+'</strong></td>'+
											'<td style="text-align:right;">'+peso+'</td>'+
											'<td style="text-align:center;"><input type="hidden" name="item['+item.id+'][quantidade]" value="'+number_format(item.peso,2,',','.')+'"/> '+number_format(item.peso,2,',','.')+'</td>'+					
											'<td style="text-align:right;"><input type="hidden" name="item['+item.id+'][precoquilo]" value="'+item.preco_unitario+'"/>'+number_format(item.preco_unitario,2,',','.')+'</td>'+
											'<td style="text-align:right;">'+number_format(subtotal,2,',','.')+'</td>'+
											'<td style="text-align:center;"><a href="#" class="regmovimremov"><i class="fa fa-times fa-2x"></i></a><a href="#" class="regedit"><i class="fa fa-pencil fa-2x"></i></a></td>'+
										'</tr>';
										
								$("#dyntable_movim tbody").append(htm);
							}

							
						}
					

					$('#dyntable_movim').dataTable({					
						"bSort" : true,
						"paging":   false,
						"ordering": false,
						"info":     false,
						"bDestroy": true,
						"bFilter": false,
						"bRetrieve": true,						
						"language": {
						"url": "../plugins/datatables/Portuguese-Brasildefault.json"
					},
				});

					$("#subtotalprodnfe").val(number_format(tot_sub,2,',','.'));
					$(".totsubtotal").html("<b>"+number_format(tot_sub,2,',','.')+"</b>");
					}else{
						var view = '<div class="message alert">Nota não encontrada, digite uma nota correta!</div>';
						$(".form_callback").html(view);
						$(".message").effect("bounce");
						$(".sosaida").hide();
						$(".sosaida").addClass('hide');
						$(".tpsaida").hide();
						$(".tpentrada").hide();
						$(".hidetipo").addClass('hide');
					}
				})
				.catch(function (error) {
					// handle error
					
					console.log(error);
				})
				.then(function () {
					// always executed
				});
		}
	});


});
var cnconf;
$(document).on("click",".geraagregarvalid",function(){
	
	var cfop = $(this).html().replace(/[^0-9]/g,'');
	var id   = $(this).attr('data-id');
	var sn   = id == "" ? "inserir":"alterar";
	var nota = $(this).attr('data-xml').split('|')[0];
	var arq  = $(this).attr('data-xml').split('|')[1];

	var htm=  "<form id='frmgeraagreg'>"+
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
	 
	/* $(this).popover({
                html: true,
                trigger: 'manual',
                placement: 'right',
				content: "<button type='button' id='close' class='close'>&times;</button>"+htm+"",
    }).popover('toggle');*/

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

$(document).on('submit','#frmgeraagreg',function(){
	
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
			//$('.geraagregarvalid').popover('hide');
			
			//$('[data-toggle="popover"]').popover('hide');
			//$('[rel=tooltip]').tooltip();
			//$('.popover').popover('hide');
			cnconf.close();
			if(arr[0].agregarsn == 2){

				var mesano = $('input[name="mesanocomptxt"]').val() ? $('input[name="mesanocomptxt"]').val() : $('input[name="setmesanocomp"]').val();
				var ret    = deletanotasdesconsiderada(arr[0].cfop,mesano);
				
				if(ret == true){
					valida_nota_novamente();					
				}

			}

			if(arr[0].msg == "Sucesso!"){
				$("#valid_"+arr[0].cfop+" > i").remove();
				$("#valid_"+arr[0].cfop+"").append('<i class="fa fa-check text-success"></i>');
				$("#valid_"+arr[0].cfop+"").removeClass('text-danger');
				$("#valid_"+arr[0].cfop+"").addClass("text-success");
				
			}
			
			if($("#validacfop li a[class='geraagregarvalid text-danger']").length == 0){
				$("#validaprodutos").css({
					'opacity':'1',
					'pointer-events':'all'
				});
				
			}
			
		},
		error:function(data){
			cnconf.close();
		}
	});
	
	return false;
	
});


$(document).on('submit','#relaciona_list_err',function(){
		
	var param = $(this).serialize();
	//alert(param);
	 $.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/relaciona-exec.php",
		data:param,
		beforeSend: function(){
			
		},
		success: function(data){
			$('[data-toggle="popover"]').popover('hide');	
			
			var formu = "<form method='post' action='relaciona-exec.php' id='relaciona_list_err'>"+
						  "<input type='hidden' name='act' value='alterar'/>"+
						  "<input type='hidden' name='id' value='"+data[0].codigo+"'/>"+
						  "<input type='hidden' name='idprod' value='"+data[0].idprod+"'/>"+
						   "<input type='hidden' name='iddet' value='"+data[0].iddet+"'/>"+
						  "<div class='input-prepend'>"+
								"<label><strong>Produto:</strong></label>"+
								"<span class='add-on fa fa-search'></span>"+
								"<input type='text' name='relprod' id='relprod' class='form-control' value='"+data[0].relprod+"'/>"+
								"<input type='hidden' name='idprodrel' id='idprodrel' value='"+data[0].idprodrel+"'/>"+
							"</div>"+
						  "<button class='btn btn-primary btn-block' type='submit'>ALTERAR</button>"+
						 "</form>";
						 
			$("a[data-id='relprod_"+data[0].idprod+"']").attr('data-content',formu);
			$("a[data-id='relprod_"+data[0].idprod+"']").append('<i class="fa fa-check text-success"></i>');
			$("a[data-id='relprod_"+data[0].idprod+"']").removeClass('text-danger');
			$("a[data-id='relprod_"+data[0].idprod+"']").addClass("text-success");
			
		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;
	
	
});

/*function goToByScroll(id){
          // Reove "link" from the ID
        id = id.replace("link", "");
          // Scroll
        $('html,body').animate({
            scrollTop: $("#"+id).offset().top});
    }*/

$(document).on('click','.printer_erros_infos',function(){
		
	$(".titulovalidacao").removeClass("hide");
		
	$(".printer_content").print({
			globalStyles: true,
            mediaPrint: false,
            stylesheet: '../plugins/bootstrap/css/bootstrap.min.css',
            noPrintSelector: ".icon-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred()
		});
	
	$(".titulovalidacao").addClass('hide');
});

$(document).on('click','.print_apura',function(){

	$("#content").print({
			globalStyles: true,
            mediaPrint: false,
            //stylesheet: '../plugins/bootstrap/css/bootstrap.min.css',
            noPrintSelector: ".icon-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred()
		});

});

$(document).on('change','.vivorend',function(){

	var nome = $(this).val();
	var cod  = soNumero($(this).attr('name'));	
	var nota = $(this).parents('tr').attr('id');

	//alert(cod);
	if(nome == 'R'){
		
		
		//var peso = $('#dyntable_notasprodutos tbody tr[id="'+nota+'"] td:eq(2)').html();
		var peso = $("input[name='item["+cod+"][npesovivo]']").val() != '' ? $("input[name='item["+cod+"][npesovivo]']").val() : $("input[name='item["+cod+"][qCom]']").val();
		
		$("input[name='item["+cod+"][npesocarcaca]']").val(peso);
		$("input[name='item["+cod+"][npesovivo]']").val('');

		//$("input[name='item["+cod+"][npesocarcaca]']").prop("disabled", true);
		//$("input[name='item["+cod+"][npesovivo]']").prop("disabled", false);
		//$("input[name='item["+cod+"][npesovivo]']").prop("required", true);		
		$("input[name='item["+cod+"][npesovivo]']").maskMoney({
			 decimal:",",
			 thousands:"."			
		});

	}else if(nome == 'V'){
		
		//var peso = $('#dyntable_notasprodutos tbody tr[id="'+nota+'"] td:eq(2)').html();
		var peso = $("input[name='item["+cod+"][npesocarcaca]']").val() != '' ? $("input[name='item["+cod+"][npesocarcaca]']").val() :  $("input[name='item["+cod+"][qCom]']").val();

		$("input[name='item["+cod+"][npesocarcaca]']").val('');
		$("input[name='item["+cod+"][npesovivo]']").val(peso);
		//$("input[name='npesocarcaca']").prop("required", false);
		//$("input[name='item["+cod+"][npesocarcaca]']").prop("disabled", false);
		//$("input[name='item["+cod+"][npesovivo]']").prop("disabled", true);
		$("input[name='item["+cod+"][npesocarcaca]']").maskMoney({
			 decimal:",",
			 thousands:"."			
		});
	}else{
		//$("input[name='item["+cod+"][npesocarcaca]']").prop("disabled", true);
		//$("input[name='item["+cod+"][npesovivo]']").prop("disabled", true);
		$("input[name='item["+cod+"][npesocarcaca]']").val('');
		$("input[name='item["+cod+"][npesovivo]']").val('');
	}

});

$(document).on('change','.xvivorend',function(){

	var nome = $(this).val();

	if(nome == 'R'){
		
		
		var peso = $('input[name="qcom"]').val();
		
		$("input[name='npesocarcaca']").val(peso);
		$("input[name='npesovivo']").val('');

		//$("input[name='npesocarcaca']").prop("disabled", true);
		//$("input[name='npesovivo']").prop("disabled", false);
		//$("input[name='npesovivo']").prop("required", true);		
	}else if(nome == 'V'){
		
		var peso = $('input[name="qcom"]').val();
		
		$("input[name='npesocarcaca']").val('');
		$("input[name='npesovivo']").val(peso);
		//$("input[name='npesocarcaca']").prop("required", false);
		//$("input[name='npesocarcaca']").prop("disabled", false);
		//$("input[name='npesovivo']").prop("disabled", true);
		
	}else{
		//$("input[name='npesocarcaca']").prop("disabled", true);
		//$("input[name='npesovivo']").prop("disabled", true);
		$("input[name='npesocarcaca']").val('');
		$("input[name='npesovivo']").val('');
	}

});

function soNumero(str) {
    str = str.toString();
    return str.replace(/[^0-9]/g,'');
}
function print_r( input, _indent ) {
// Recuo

var indent = ( typeof( _indent ) == 'string' ) ? _indent + '    ' : '    '
var parent_indent = ( typeof( _indent ) == 'string' ) ? _indent : '';
var output = '';

// Tipo de Elemento do Array
switch( typeof( input ) ) {
case 'string':
     output = "'" + input + "'n";
     break;
case 'number':
     output = input + "n";
     break;
case 'boolean':
     output = ( input ? 'true' : 'false' ) + "n";
     break;
case 'object':
     output = ( ( input.reverse ) ? 'Array' : 'Object' ) + "n";
     output += parent_indent + "(n";
     for( var i in input ) {
          output += indent + '[' + i + '] => ' + print_r( input[ i ], indent );
     }
     output += parent_indent + ")n"
     break;
  }
return output;
}

$(document).ready(function() {
  $("input").attr("onkeypress","return enterTabula(this,event)");
  $("select").attr("onkeypress","return enterTabula(this,event)");
});

// funcao normal em javascript
function enterTabula (f, e) {
	var charCode = (e.keyCode) ? e.keyCode : e.which;
	if (charCode == 13) {
		var i;
		for (i = 0; i < f.form.elements.length; i++) {
			if (f == f.form.elements[i]) {
				break;
			}
		}

		i = (i + 1) % f.form.elements.length;
		for (ii = i; ii < f.form.elements.length; ii++)	{
			if ( (f.form.elements[ii].readOnly!=true) &&
(f.form.elements[ii].type!='button') ) {
				break;
			}
		}
		//alert('tipo='+f.form.elements[ii].type+'name='+f.form.elements[ii].name)
		f.form.elements[ii].focus();
		return false;

	} else
		return true;
}

$(document).on('click','.itemcomp',function(){

	var id = $(this).attr('data-id');

	 $.ajax({
		type:'POST',
		cache:false, 
		dataType: "json",
		url:"../php/apuracao-exec.php",
		data:{act:'buscaapuracao',mesano:id.split('|')[0],cnpj:id.split('|')[1]},
		beforeSend: function(){
			$(".load").html("<img src='../images/loader2.gif'/>");	
		},
		success: function(arr){
			
			//$(".num_entrada").html(data[0].numero_entrada);
			//$(".num_saida").html(data[0].numero_saida);
			//$(".num_cabeca").html(data[0].numcabeca);			
			//$(".vlcred").html(data[0].creditoent);
			
			$('.btncompenv').removeClass('hide');
			$('.apuitem').attr('data-id',id);
			$('.btn-ntentrada').attr('data-id',id);
			$('.btn-ntsaida').attr('data-id',id);
			$('.btn-xprorel').attr('data-id',id);

			window.location.reload();

			/*
			var txt = "";
            txt += '<div  align="center">';
            	txt += '<ul class="list-group list-group-full">'+
					        '<li class="list-group-item">'+
					          'NUMERO PROTOCOLO<br/> '+arr.prot[0].protocolo+' '+
					        '</li>'+
					        '<li class="list-group-item">'+
					          '<div class="form-group"><label>STATUS</label>'+
	                            '<select name="liststatus" class="custom-select form-control btn-block" disabled>';
	                            for(i = 0; i < arr.status.length; i++){

	                                txt +='<option value="'+arr.status[i].codstatus+'">'+arr.status[i].nomestatus+'</option>';	          
	                               }                    
	                          txt +='</select></div>'+
					        '</li>'+
					       			        					        
					      '</ul>';

            txt += '</div>';
            txt += '<div  align="center" id="content">'+
				'MÊS/ANO-> '+arr.mesano+'<br/>'+
			    '----------------------------------<br/>'+
			    '<br/>'+
			    'ANIMAIS ABATIDOS<br/>'+
			    '---------------------------------<br/>'+
			    '<br/>'+

			  
			  '<div style="text-align:center; display:inline-table;">'+
			    '<div style="display: inline-table; margin-right: 20px; text-align: left; width: 88px;">'+
			    	'BOVINOS'+
			    '</div>'+
			    '<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">'+
			    	''+arr.animais[0].bovinos+''+
			    '</div>'+
			    '<br/>'+
			    '<div style="display:inline-table; text-align:left; margin-right:20px; width:80px; ">'+
			    	'BUBALINOS'+
			    '</div>'+
			    '<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">'+
			    	''+arr.animais[0].bubalinos+''+
			    '</div>'+
			    '<br/>'+
			    '<div style="display:inline-table; text-align:left; margin-right:20px; width:87px; ">'+
			    	'OVINOS'+
			    '</div>'+
			    '<div style="display: inline-table; margin-left: -20px; margin-right: -5px; text-align: right; width: 38px; ">'+
			    	''+arr.animais[0].ovinos+''+
			    '</div>'+
			    
			  '</div>'+
			  '<br/>'+
			  '<br/>'+
			  'APURACAO DO CREDITO<br/>'+
			  '---------------------------------------- <br/>';


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
						  <td class="text-right">${arr.basecredito[0].base}</td>
						  <td class="text-right">${arr.basecredito[0].credito}</td>
					  </tr>
					  
					  <tr>
						  <td>VENDAS RIO GRANDE DO SUL 3%</td>
						  <td class="text-right">${arr.vendars[0].basers}</td>
						  <td class="text-right">${arr.vendars[0].creditors}</td>
					  </tr>

					  <tr>
						  <td>VENDAS RIO GRANDE DO SUL 4%</td>
						  <td class="text-right">${arr.vendars2[0].basers2}</td>
						  <td class="text-right">${arr.vendars2[0].creditors2}</td>
					  </tr>

					  <tr>
						  <td>VENDAS OUTROS ESTADOS 3%</td>
						  <td class="text-right">${arr.vendasdifrs[0].basedifrs}</td>
						  <td class="text-right">${arr.vendasdifrs[0].creditodifrs}</td>
					  </tr>

					  <tr>
						  <td>VENDAS OUTROS ESTADOS 4%</td>
						  <td class="text-right">${arr.vendasdifrs2[0].basedifrs2}</td>
						  <td class="text-right">${arr.vendasdifrs2[0].creditodifrs2}</td>
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

			$(".detalhecomp").html(txt);
			$(".detalhecomp").removeClass("hide");
			$(".load").html('');
			 var st = setInterval(function(){

	         	$("select[name='liststatus'] option[value='"+arr.prot[0].xstatus+"']").attr("selected","selected");
	         	clearInterval(st);
	         },600);
			 */
		},
		error:function(arr){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return false;


});

$(document).on('click','.btn-ntentrada',function(){

	var param   = $(this).attr('data-id').split('|');
	var mesano  = param[0];
	var cnpjemp = param[1];	

	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/relatorionotasentradas.php",
		data:{mesano:mesano,cnpjemp:cnpjemp},
		beforeSend: function(){
			$(".load").html("<img src='../images/loader2.gif'/>");	
		},
		success: function(data){					
			
			$(".detalhecomp").html(data);
			var info = "LISTAGEM DE NOTAS DE ENTRADAS DE ANIMAIS "+mesano+"";
			$('.tbntnotaent').dataTable({					
				 "bSort" : false,
				 "paging":   false,
				 "ordering": false,
				 "info":     false,
				 "bDestroy": true,
				 "bFilter": false,
				 "bRetrieve": true,
				 "dom": 'Bfrtip',
				 "buttons": [
					{ 
						extend: 'copyHtml5', 
						messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_ENTRADAS_DE_ANIMAIS_"+mesano+"",
						footer: true,							
					},
					{ 
					 extend: 'excelHtml5',
					 messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_ENTRADAS_DE_ANIMAIS_"+mesano+"",
					 footer: true,						  
					},
					{ extend: 'csvHtml5',messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_ENTRADAS_DE_ANIMAIS_"+mesano+"", footer: true },
					{ 
						text: 'PDF',					
						//filename:"LISTAGEM_DE_NOTAS_DE_ENTRADAS_DE_ANIMAIS_"+mesano+"",						
						action: function ( e, dt, node, config ) {
							console.log(window.location.pathname);
							console.log(window.location.hash);
							console.log(window.location.hostname);
							console.log(window.location.href);
							console.log(window.location.origin);
							console.log(`${window.location.origin}/projetos/agregar_master/arquivos/${cnpjemp}/relatorio/${cnpjemp}_notasentrada.pdf`);
							download_file(`${window.location.origin}/projetos/agregar_master/arquivos/${cnpjemp}/relatorio/${cnpjemp}_notasentrada.pdf`,`LISTAGEM_DE_NOTAS_DE_ENTRADAS_DE_ANIMAIS_${mesano}.pdf`);
						}						
					}
				],
				 "language": {
					"url": "../plugins/datatables/Portuguese-Brasildefault.json"
				},
			});

			$('.edit').editable({
	            showbuttons: 'bottom',
	            mode: 'inline',	            
	        });

	        $('.edit2').editable({
	            showbuttons: 'bottom',
	            mode: 'inline',	            
	        });

	      
			$('.editprod').editable({
				source: ListaProdutoAgregar(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
		            placeholder: 'Selecione um produto agregar',			            			            			       
			    },
			    mode: 'inline',
			    emptytext: 'Vazio'
			});

			$(".load").html("");	
		},
		error:function(data){

		}
	});
	
	return false;

});

function download_file(fileURL, fileName) {
	// for non-IE
	if (!window.ActiveXObject) {
		var save = document.createElement('a');
		save.href = fileURL;
		save.target = '_blank';
		var filename = fileURL.substring(fileURL.lastIndexOf('/')+1);
		save.download = fileName || filename;
		   if ( navigator.userAgent.toLowerCase().match(/(ipad|iphone|safari)/) && navigator.userAgent.search("Chrome") < 0) {
				document.location = save.href; 
	// window event not working here
			}else{
				var evt = new MouseEvent('click', {
					'view': window,
					'bubbles': true,
					'cancelable': false
				});
				save.dispatchEvent(evt);
				(window.URL || window.webkitURL).revokeObjectURL(save.href);
			}   
	}
	
	// for IE < 11
	else if ( !! window.ActiveXObject && document.execCommand)     {
		var _window = window.open(fileURL, '_blank');
		_window.document.close();
		_window.document.execCommand('SaveAs', true, fileName || fileURL)
		_window.close();
	}
	}

function ListaProdutoAgregar(){

	var retorno;
	 $.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/produto-exec.php",
		data:{act:'lista'},		
		success: function(data){
			
			retorno = data;
		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return retorno;
}

function ListaProdutoFrigorifico(){
	var retorno;
	 $.ajax({
		type:'POST',
		async:false, 
		dataType: "json",
		url:"../php/produto-exec.php",
		data:{act:'listaprodfrig'},		
		success: function(data){
			
			retorno = data;
		},
		error:function(data){			
			alert("Ops, desculpe pelo transtorno , Liga para o suporte para que podemos melhor le ajudar, pedimos desculpa pelo transtorno ocorrido!");
		}		
	});
		
	return retorno;
}

$(document).on('click','.btn-ntsaida',function(){

	var param   = $(this).attr('data-id').split('|');
	var mesano  = param[0];
	var cnpjemp = param[1];	

	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/relatorionotassaida.php",
		data:{mesano:mesano,cnpjemp:cnpjemp},
		beforeSend: function(){
			$(".load").html("<img src='../images/loader2.gif'/>");	
		},
		success: function(data){					
			
			$(".detalhecomp").html(data);
			
			var info = "LISTAGEM DE NOTAS DE SAIDA DE "+mesano+"";

			$('.tbntnotasai').dataTable({					
				 "bSort" : false,
				 "paging":   false,
				 "ordering": false,
				 "info":     false,
				 "bDestroy": true,
				 "bFilter": false,
				 "bRetrieve": true,
				 "dom": 'Bfrtip',
				 "buttons": [
					{ 
						extend: 'copyHtml5', 
						messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_SAIDA_DE_"+mesano+"",
						footer: true,							
					},
					{ 
					 extend: 'excelHtml5',
					 messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_SAIDA_DE_"+mesano+"",
					 footer: true,						  
					},
					{ extend: 'csvHtml5',messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_SAIDA_DE_"+mesano+"", footer: true },
					{ 
						extend: 'pdfHtml5', messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_NOTAS_DE_SAIDA_DE_"+mesano+"",footer: true,						
					}
				],
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
				},
			});

			$('.editprod').editable({
				source: ListaProdutoAgregar(),
			    select2: {
					"language": "pt-BR",
		            width: '345px',
		            placeholder: 'Selecione um produto agregar',			            			            			       
			    },
			    mode: 'inline',
			    emptytext: 'Vazio'
			});

			$(".load").html("");
		},
		error:function(data){

		}
	});
	
	return false;

});

$(document).on('click','.btn-xprorel',function(){

	var param   = $(this).attr('data-id').split('|');
	var mesano  = param[0];
	var cnpjemp = param[1];	

	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/relatorioproduto.php",
		data:{cnpjemp:cnpjemp},
		beforeSend: function(){
			$(".load").html("<img src='../images/loader2.gif'/>");	
		},
		success: function(data){					
			
			$(".detalhecomp").html(data);
			var info = "LISTAGEM DE PRODUTOS RELACIONADO COM A SECRETARIA";
			$('.tbrelprod').dataTable({					
				 "bSort" : false,
				 "paging":   false,
				 "ordering": false,
				 "info":     false,
				 "bDestroy": true,
				 "bFilter": true,
				 "bRetrieve": true,
				 "dom": 'Bfrtip',
				 "buttons": [
					{ 
						extend: 'copyHtml5', 
						messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_PRODUTOS_RELACIONADO_COM_A_SECRETARIA",
						footer: true,							
					},
					{ 
					 extend: 'excelHtml5',
					 messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_PRODUTOS_RELACIONADO_COM_A_SECRETARIA",
					 footer: true,						  
					},
					{ extend: 'csvHtml5',messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_PRODUTOS_RELACIONADO_COM_A_SECRETARIA", footer: true },
					{ 
						extend: 'pdfHtml5', messageTop: '',title:''+info+'', filename:"LISTAGEM_DE_PRODUTOS_RELACIONADO_COM_A_SECRETARIA",footer: true,						
					}
				],
				"language": {
					"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
				},
			});			

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
			    	 
			    	 $(".tbrelprod tbody tr[data-id='cod_"+response[0].idprod+"'] td:eq(0)").html(response[0].idprodrel);					 					
				}
			});

			$(".load").html("");
		},
		error:function(data){

		}
	});
	
	return false;

});

$(document).on('submit','form[class="form-inline editableform"]',function(){

	alert('asas');
	 /*var data,
        $elems = $('.edit'),
        errors = $elems.editable('validate');

     if($.isEmptyObject(errors)) {
     	data = $elems.editable('getValue');

     	alert(data);

     }*/


});

$(document).on('click','.xclose',function(){
	$("#myModal_pdf").hide();
});

$(document).on('click','.xclosexls',function(){
	$("#myModal_pdf").hide();
});

$(document).on('click','.close_apura',function(){
	$('.btncompenv').addClass('hide');
	$(".detalhecomp").addClass("hide");
});

$(document).on('keyup', "#autocompleteerros", function() {
	//console.log($(this).val().toUpperCase());
	var val 	= $(this).val().toUpperCase();
	var select  = $(".valid_erros ol");
	var select2 = $("#validaprodutos");
	var select3 = $("#validacfop");

	if (val != '') {
		
		select.children('li').hide();
		select.children('li').filter(function() { 
      	//console.log($(this).text().indexOf($val))
		 // console.log($(this).val().toUpperCase());
      	return $(this).text().toUpperCase().indexOf(val) !== -1; 
      }).show();
	  
	  select2.children('li').hide();
	  select2.children('li').filter(function() { 
      	//console.log($(this).text().indexOf($val))
		 // console.log($(this).val().toUpperCase());
      	return $(this).text().toUpperCase().indexOf(val) !== -1; 
      }).show();

	  select3.children('li').hide();
	  select3.children('li').filter(function() { 
      	//console.log($(this).text().indexOf($val))
		 // console.log($(this).val().toUpperCase());
      	return $(this).text().toUpperCase().indexOf(val) !== -1; 
      }).show();
	  
	}else{
		select.children().show();
		select2.children().show();
		select3.children().show();	
	}
});
$(document).on('keyup', "#autocompletealert", function() {
	//console.log($(this).val().toUpperCase());
	var val = $(this).val().toUpperCase();
	var select = $(".valid_infos ol");
	if (val != '') {
		select.children('li').hide();
		select.children('li').filter(function() { 
      	//console.log($(this).text().indexOf($val))
      	return $(this).text().toUpperCase().indexOf(val) !== -1; 
      }).show();
	}else{
		select.children().show();	
	}
	
});
$(document).on('keyup', "#autocomplete", function() {
	tbcompenv.fnFilter(this.value);
});

$(document).on('keyup', "#autocomplete2", function() {
	tbcompand.fnFilter(this.value);	
});
var tbcompenv;
var tbcompand;
$(function(){

	$(".func").click(function(){
		
			$(this).siblings('div.box').slideToggle();
			if($(this).text() == "Mostrar todos os XML importados"){
				$(this).text('Ocultar todos os XML importados');
			}else{
				$(this).text('Mostrar todos os XML importados');
			}
	});

	tbcompenv = $('.tbcompenv').dataTable({					
		"bSort" : false,
		"paging":   false,
		"ordering": false,
		"info":     false,	
		"scrollY": '30vh',
		"scrollCollapse": true,		
		initComplete : function() {
        	$(".dataTables_filter").detach().appendTo('#autocomplete');
    	},
	   "language": {
		   "url": "../plugins/datatables/Portuguese-Brasil.json",			
	   },
   });

   /*$('#autocomplete').keyup(function(){
		tbcompenv.fnFilter(this.value);
 	});*/

   tbcompand = $('.tbcompand').dataTable({					
	"bSort" : false,
	"paging":   false,
	"ordering": false,
	"info":     false,	
	"scrollY": '30vh',
	"scrollCollapse": true,
	initComplete : function() {
		$("#DataTables_Table_1_filter").detach().appendTo('#autocomplete2');
	},
   "language": {
	   "url": "../plugins/datatables/Portuguese-Brasil2.json"
   },
});

});


$(document).on('change',"input[name='filearqcabeca']",function(e){
	var myfile = document.getElementById("filearqcabeca");
	var file   = myfile.files;
	var data   = new FormData();

	for(var i = 0; i < file.length; i++){
		data.append(i,file[i]);
	}

	data.append("act","arqxmlcabecas");

	e.preventDefault();

	$.ajax({
		url: "../php/lancamentos-exec.php",
		type: "POST",
		data: data,
		contentType: false,
		cache: false,
		processData: false,
		beforeSend: function(){
			$(".modal-body_PDF p").html('');
			$(".modal-body_PDF img").show();
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde coletando os dados");
		},
		success: function (data) {
			
			var arr  = $.parseJSON(data);			
			var msg  = ""
			//console.log(arr);
			if(arr.error){
				for(var i = 0; i < arr.error.length; i++){

					var res = arr.error[i];
					msg += res['msg']+'<br/>';
					
				}
				
			}else{
				
				for(var i = 0; i < arr.result.length; i++){

					var res = arr.result[i];
					
					if(parseInt(res['ncabecas'])  > 0){
						$("a[data-id='nota_"+res['nnota']+"_"+res['cprod']+"_"+res['item']+"']").append('<i class="fa fa-check text-success"></i>');
						$("a[data-id='nota_"+res['nnota']+"_"+res['cprod']+"_"+res['item']+"']").removeClass('text-danger');
						$("a[data-id='nota_"+res['nnota']+"_"+res['cprod']+"_"+res['item']+"']").addClass("text-success");
					}
					msg += res['msg']+'<br/>';
					
				}
				
			}
			
			$(".modal-body_PDF img").hide();			
			$(".modal-body_PDF p").html("<div style='text-align: center;margin-left: 24px;background: white;color: #000;width: 59%;margin: 0 auto;border-radius: 10px;margin-bottom: 10px;font-weight: bolder;padding: 5px;'>"+msg+"</div><a href='#' class='btn btn-warning xclosexls' style='margin-left: 24px;'>Fechar</a>");
			//$("#myModal_pdf").hide();	
			$("input[name='filearqcabeca']").val('');			
		},
		error: function () {
			$("#myModal_pdf").hide();
			$("input[name='filearqcabeca']").val('');
		}
	});


});


$(document).on('keyup','input[name="ncabecas"]',function(e){

	   if (isNaN($(this).val())) {  
			alert("Digite apenas números!");  
			$(this).val('');
			$(this).select();  
			return false;  
		} else {
			return false;
		}
	//console.log($(this).val());

});
$(document).on('blur','.ncabeca',function(e){
	console.log($(this).val());
	if($(this).val() > 300){
		alert("Número de cabeças informado é incorreto!");
		$(this).val('');
		var st = setInterval(function(){
			$(this).focus();
			clearInterval(st);
		},300);
		
		return false; 
	}
});
$(document).on('keyup','.ncabeca',function(e){

	
	if(parseFloat($(this).val()) > 0){
		return true;
	}else{
		$(this).val('');
		$(this).select(); 
		return false;
	}

	if (isNaN($(this).val())) {  
		 alert("Digite apenas números!");  
		 $(this).val('');
		 $(this).select();  
		 return false;  
	 } else {
		 return false;
	 }
 

});

var cnf;
/*$(document).on('click','#agresemmovi',function(e){
	var mesano = $('input[name="mesanocomp"]').val();

	if(mesano == ''){
		alert("Informar a competência primeiro!");
		$('input[name="mesanocomp"]').focus();
		return false;
	}

	var htm = 	'<form id="frmgerasemmovi">'+
					'<input type="hidden" name="mesano" value="'+mesano+'" />'+
					'<input type="hidden" name="act" value="semmovi" />'+
					'<div class="form-group">'+
					'<label>Motivo:</label>'+
					'<textarea class="form-control" name="obs" rows="5" required></textarea>'+
					'</div>'+
					'<button type="submit" class="btn btn-block btn-primary">Gravar</button>'+
				'</form>';
	
	 cnf = $.confirm({
		title: 'Mensagem do Sistema',
		content: '<h3>Digite o motivo da geração sem movimentação:</h3> '+htm+'',
		type: 'orange',
		typeAnimated: false,
		buttons: {
			fechar: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					cnf.close();
				}
			}
		}
	});


	return false;
});*/

function GeraSemMovimento(){

	var mesano = $('input[name="mesanocomp"]').val();

	if(mesano == ''){
		alert("Informar a competência primeiro!");
		$('input[name="mesanocomp"]').focus();
		return false;
	}

	var htm = 	'<form id="frmgerasemmovi">'+
					'<input type="hidden" name="mesano" value="'+mesano+'" />'+
					'<input type="hidden" name="act" value="semmovi" />'+
					'<input type="hidden" name="prox" value="" />'+
					'<div class="form-group">'+
					'<label>Motivo:</label>'+
					'<textarea class="form-control" name="obs" rows="5" maxlength="20" required></textarea>'+
					'<div class="count"><span></span></div>'+		
					'<div class="msgerro hide" style="color:#ef0000">No minimo 20 caracteres</div>'+
					'</div>'+
					'<button type="submit" class="btn btn-block btn-primary">Gravar</button>'+
				'</form>';
	
	 cnf = $.confirm({
		title: 'Mensagem do Sistema',
		content: '<h3>Digite o motivo da geração sem movimentação:</h3> '+htm+'',
		type: 'orange',
		typeAnimated: false,
		buttons: {
			fechar: {
				text: 'Fechar',
				btnClass: 'btn-red',
				action: function(){
					cnf.close();
				}
			}
		}
	});
	
	return false;

}


$(document).on('submit','form[id="frmgerasemmovi"]',function(){

	var param = $(this).serialize();
	
	var obs  = $('textarea[name="obs"]').val();

	/*if(obs.length < 20){
		$('textarea[name="obs"]').focus();
		$('.msgerro').removeClass('hide');
		return false;
	}else{
		$('.msgerro').addClass('hide');
	}*/
	
	
	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/lancamentos-exec.php",
		data:param,
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde..");
		},
		success: function(data){											
			$("#myModal_pdf").hide();
			var obj = JSON.parse(data);
			cnf.close();
			$("input[name='prox']").val(obj[0].msg);
			if(obj[0].msg == 'proximo'){
				step.steps("next");
			}

		},
		error:function(data){
			$("#myModal_pdf").hide();
		}
	});

	return false;
});

$(document).on('keyup','textarea[name="obs"]',function(){	 
	var current_lengh = 20 - $(this).val().length;
	
	if(current_lengh > 15) {
		current_lengh = '<span style="color: blue">Restam : ' + current_lengh + ' caracteres</span>';
	} else if(current_lengh > 0) {
		current_lengh = '<span style="color: green"> Restam: ' + current_lengh + ' caracteres </span>';
	} else if(current_lengh == 0){
		current_lengh = '<span style="color: red">Restam: ' + current_lengh + ' caracteres</span>';
	}
	$(this).next().html(current_lengh);
});


function excluicompetencia(mesano,cnpj){
	console.log(mesano);
	var msgcomp = $.confirm({
		title: 'Mensagem do sistema',
		content: 'Deseja Realmente remover essa competência ?',
		type: 'orange',
		typeAnimated: false,
		columnClass: 'col-md-9 col-md-offset-8 col-xs-4 col-xs-offset-8',
		buttons: {
			remcomp: {
				text: ' Remover Somente a competência',
				btnClass: 'btn-red fa fa-close',
				action: function(){
					RemoverCompetencia(mesano,cnpj,1);
				}
			},
			/*tryAgain: {
				text: ' Remover competência e relacionamentos',
				btnClass: 'btn-red fa fa-close',
				action: function(){
					RemoverCompetencia(mesano,cnpj,2);
				}
			},*/
			fechar: {
				text: 'Fechar',
				btnClass: 'btn-orange',
				action: function(){
					msgcomp.close();
				}
			},
		}
	});

}

function reenviardbfs(mesano){

	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/gerar-dbf-exec.php",
		data:{act:'gerar',mesano:mesano},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde.. reenviando a compentência!");
		},
		success: function(data){
			var obj = JSON.parse(data);											
			$("#myModal_pdf").hide();

			$("#myModal_pdf").show();
			$(".modal-header_PDF").html('');
			$(".modal-content_PDF").css({
				'background-color':'#2a363f !important',
				'color':'#fff',
			});
			$(".modal-body_PDF").html("<img src='../images/check.gif' /><br/><br/>"+obj[0].msg+"<br/><br/><a href='admin.php' class='btn btn-success'>FECHAR</a>");

		},
		error:function(data){
			$("#myModal_pdf").hide();
		}
	});

	return false;

}

function RemoverCompetencia(mesano,cnpj,tipo){

	$.ajax({
		type:'POST',
		cache:false, 
		url:"../php/lancamentos-exec.php",
		data:{act:'removercompentencia',mesano:mesano,cnpjemp:cnpj,tipo:tipo},
		beforeSend: function(){
			$("#myModal_pdf").show();
			$(".modal-body_PDF p").html("Aguarde.. excluindo a compentência!");
		},
		success: function(data){											
			$("#myModal_pdf").hide();

			$("#myModal_pdf").show();
			$(".modal-header_PDF").html('');
			$(".modal-content_PDF").css({
				'background-color':'#2a363f !important',
				'color':'#fff',
			});
			$(".modal-body_PDF").html("<img src='../images/check.gif' /><br/><br/>"+data+"<br/><br/><a href='admin.php' class='btn btn-success'>FECHAR</a>");

		},
		error:function(data){
			$("#myModal_pdf").hide();
		}
	});

	return false;
}


function copyvalida(){
	var copyText = document.getElementById("atvexcel");
	  
	  copyText.select();
	  copyText.setSelectionRange(0, 99999); /*For mobile devices*/
	  document.execCommand("copy");
	  var tooltip = document.getElementById("myTooltip");
	  tooltip.innerHTML = "Copied: " + copyText.value;
}

function outFunc() {
	var tooltip = document.getElementById("myTooltip");
	tooltip.innerHTML = "Copiar para área de transferência";
  }


  $( document ).on( "mouseover",'.valid_erros ol li a[class="popupnota"]',function( event ) { 
	  
	var content = $(this).attr('data-content');
	
	if(content != undefined){
		var obj = content.replace(/'/g, '"');
		var obj2= JSON.parse(obj);
		var htm = "";
		//console.log(obj2);
		htm += '<table class="table table-bordered dataTable no-footer popuptable">'+
				'<thead>'+
					'<tr>'+
						'<th>Número Nota</th>'+
						'<th>Data Emissão</th>'+
						'<th>Entrada/Saída</th>'+
						'<th>Cliente</th>'+
						'<th>Valor</th>'+
					'</tr>'+
				'</thead>'+
				'<tbody>';	
		for(i =0; i< obj2.length; i++){
			htm += '<tr>'+
						'<td>'+obj2[i].nnota+'</td>'+
						'<td>'+obj2[i].demi+'</td>'+
						'<td>'+obj2[i].entsai+'</td>'+
						'<td>'+obj2[i].cliente+'</td>'+
						'<td>'+obj2[i].valor+'</td>'+
				'</tr>';
		}
		htm += '</tbody>'+
			'</table>';
	$('<div class="div-tooltip"></div>').html(htm).appendTo('body').fadeIn('slow');   
	}
}).on('mousemove',function(e){	
	 $('.div-tooltip').css({ top: e.pageY + 10, left:  e.pageX + 20 });
	 
}).on('mouseout',function(){	
	 $('.div-tooltip').stop(false, true).fadeOut('slow');
	 $('.div-tooltip').remove();
});

$(document).ready(function(){
	$('.tbrelprod').dataTable({					
		"bSort" : false,
		"paging":   false,
		"ordering": false,
		"info":     false,
		"bDestroy": true,
		"bFilter": true,
		"bRetrieve": true,
		"dom": 'Bfrtip',
		"language": {
			"url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
		},
	});			

	$('.editprodsecret').editable({
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
				
				$(".tbrelprod tbody tr[data-id='cod_"+response[0].idprod+"'] td:eq(0)").html(response[0].idprodrel);					 					
		}
	});

});

$(document).on('click','.downloadexcelqtd',function(){
	$("#myModal_pdf").show();
	$(".modal-body_PDF p").html("Aguarde..!");

	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function(){
		if(this.readyState === 4){
			$("#myModal_pdf").hide();
			if(this.status === 200){
				//console.log(this.responseText);
							
			$("#myModal_pdf").show();
			$(".modal-body_PDF img").hide();
			$(".modal-body_PDF p").html("Arquivo gerado com sucesso!<br/><br/><div style='text-align: center; margin-left: 24px;'><a href='"+this.responseText+"' download='exemplocabeca' class='btn btn-success'>Clique aqui para baixar o arquivo</a><a href='#' class=' btn btn-warning xclosexls' style='margin-left: 24px;'>Fechar</a></div>");
			
			}else{
				console.log(this.status+' - '+this.statusText);
			}
		}
	};

	var mesano = $('input[name="setmesanocomp"]').val();
	xhttp.open("POST",'../php/xls-exec.php?act=gerar&mesano='+mesano+'',true);
	xhttp.setRequestHeader("Content-Type","application/json");
	xhttp.send();


});

$(document).on('click','#selecionarTodos',function(){

	//var elems = document.querySelectorAll('.js-switch');
	//$( this ).trigger( 'click' );
	
	   $('input:checkbox').not(this).prop('checked', this.checked);	
	   
});

$(document).on('click','input[name="peruser"]',function(){

	//var elems = document.querySelectorAll('.js-switch');
	//$( this ).trigger( 'click' );
		console.log($(this).val());
	   $('.peruser_'+$(this).val()+':checkbox').not(this).prop('checked', this.checked);	
	   
});


$(document).on('click','.deleteemp',function(e){

	var conf = confirm("Deseja realmente excluir essa empresa ?");
	var id  =  $(this).parents('tr').attr('id');
	if(conf){
		window.location.href = "../php/empresa-exec.php?act=remove&id="+id+"";
	}

	return false;

});

$(document).ready(function(){

	$("#clickmanual").click(function(e){
		//$('.toltipmanual').css({ top: e.pageY - 300, left:  e.pageX - 250,display:'inline-flex'});
		$.confirm({
			title: '',
			content: 'Oque deseja fazer ?',
			type: 'orange',
			buttons: {
				info: {
					text: 'Lancamentos',
					btnClass: 'btn-blue',
					action: function(){
						window.location.href = 'notasmanuais.php?act=lancamento';
					}
				},
				danger: {
					text: 'Alteração',
					btnClass: 'btn-red any-other-class',
					action: function(){
						window.location.href = 'notasmanuais.php?act=alteracao';
					}
					
				},
			}
		});
	});


	$("#btnalterapass").click(function(e){

		e.preventDefault();

		if($('#atusenha').val().trim() == ''){
			var view = '<div class="message alert">Informar a sua senha atual!</div>';
			$(".form_calback").html(view);
			$(".message").effect("bounce");
			return false;
		}

		if($('#nsenha').val().trim() == ''){
			var view = '<div class="message alert">Informar a sua nova senha!</div>';
			$(".form_calback").html(view);
			$(".message").effect("bounce");
			return false;
		}

		var atusenha = $('#atusenha').val();
		var nsenha   = $('#nsenha').val();

        $.ajax({
            url: '../php/empresa-exec.php',
            data: {act:'alterPass',atusenha:atusenha,nsenha:nsenha},
            type: "post",
            dataType: "json",
            beforeSend: function (load) {
               // ajax_load("open");
            },
            success: function (su) {
                //ajax_load("close");

                if (su.message) {
                    var view = '<div class="message ' + su.message.type + '">' + su.message.message + '</div>';
                    $(".form_calback").html(view);
                    $(".message").effect("bounce");
                    return;
                }

                if (su.redirect) {
                    window.location.href = su.redirect.url;
                }
            }
        });

        /*function ajax_load(action) {
            ajax_load_div = $(".ajax_load");

            if (action === "open") {
                ajax_load_div.fadeIn(200).css("display", "flex");
            }

            if (action === "close") {
                ajax_load_div.fadeOut(200);
            }
        }*/

	});
	
	$(".send_apura").click(function(){
		
		const obj 		= localStorage.getItem("apuracao");	
		const tipo      = $('input[name="tipoimport"]').val();
		const layout    = $(".apura_layout").attr('data-id');
		
		var data = new FormData();
		data.append("act", 'gravapdf');
		data.append("dados", obj);
		data.append("tipo", tipo);
		data.append("layout", layout);

		$("#myModal_pdf").show();
		$(".modal-body_PDF img").show();
		$(".modal-body_PDF p").html("Aguarde um momento enquanto eu coleto os dados para enviar o e-mail!");

		axios.post('../php/apuracao-exec.php',data)
			.then(function (response) {
				//handle success

				const data = response.data;

				$('.anexo-nome').html(data[0].assunto);
				$('#env-assunto').val(data[0].assunto);
				$('#env-para').val(data[0].email);
				$(".anexo").attr('data-url',data[0].url);
				$('textarea[name="obs"]').html(data[0].corpo);
			
				$("#myModal_pdf").hide();
				$('#modalemail').modal('show');
				
			})
			.catch(function (error) {
				// handle error
				console.log(error);
				$(".modal-body_PDF img").hide();
				(".modal-body_PDF p").html("Algo não ocoreu conforme o esperado: "+error+"");

			});
		
	});

});


	


$(document).on('click','.anexo', function(){
	$(".print_apura").click();
});

$(document).on('submit','#form-envmail-pdf', function(){
	var param = $(this).serialize();
	
	$("#myModal_pdf").show();
	$(".modal-body_PDF img").show();
	$(".modal-body_PDF p").html("Aguarde enviando E-Mail...");

	axios.post('../php/apuracao-exec.php',param)
	.then(function (response) {
		//handle success
		const data = response.data;


		console.log(response);
	
		
		$("#myModal_pdf").show();
		$(".modal-body_PDF img").hide();
		$(".modal-body_PDF p").html(""+data[0].mensagem+"<br/><br/><div style='text-align: center; margin-left: 24px;'><a href='#' class=' btn btn-warning xclosexls' style='margin-left: 24px;'>Fechar</a></div>");
		
	})
	.catch(function (error) {
		// handle error
		console.log(error);
		$(".modal-body_PDF img").hide();
		(".modal-body_PDF p").html("Algo não ocoreu conforme o esperado: "+error+"");

	});

	return false;
});

/*$(function () {
	axios.get('https://swapi.dev/api/people/1/')
		.then(function (response) {
			// handle success
			console.log(response);
		})
		.catch(function (error) {
			// handle error
			console.log(error);
		})
		.then(function () {
			// always executed
		});


});*/
