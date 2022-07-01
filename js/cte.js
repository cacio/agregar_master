// JavaScript Document
$(document).ready(function(){
	//$("#menurelatorio").hide();
	$("#menurelatorio").hide();
	$("#element").click(function(){

		//$(this).popover('show');
		$("#menurelatorio").slideToggle('fast');

	});
	
	
	$("#confsenha").blur(function(){
		
		if($(this).val() != $("#senha").val()){
			
			$(this).popover('show');
			$(this).val('');
				
			
		setTimeout(function(){
				$("#confsenha").popover('hide');		
				$("#senha").focus();
				//$("#senha").val('');
		},5000);	
		}
		
	});
		
		
	$("#gerenciais").click(function(){
		$(this).popover('show');
	});
	$("#inf").click(function(event) {
		event.preventDefault();
		event.stopPropagation();
		$("#inf").popover('show');
	});
	
	/*$forms.keypress(function (e) {
			var code = null;
			code = (e.keyCode ? e.keyCode : e.which);                
			return (code == 13) ? false : true;
	   });*/	
});

function toltip(string){
	$('#'+string+'').show();
	setTimeout(function(){
		$('#'+string+'').hide();
	},5000)
	
}





  function printcte(elementId,cod) {
		
		alert("DACTE gerado com sucesso!");
		var oJan;
		var pg = ""+elementId+"";
	   oJan = window.open(pg,'','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,fullscreen=1,resizable=yes,width=9'+screen.width+',height='+screen.height+',directories=no,location=no');
		oJan.document.write('<iframe id="iFramePdf526"  src="'+string+'" style="display:none;"></iframe>');
		oJan.window.print();
		oJan.document.close();
		oJan.focus();
	}
 

  
$(document).ready(function() {

            function limpa_formulário_cep() {
                // Limpa valores do formulário de cep.
                $("#endereco").val("");
                $("#Bairro").val("");
                $("#cidade").val("");
                $("#estado").val("");
                $("#ibge").val("");
            }
            
            //Quando o campo cep perde o foco.
            $("#cep").blur(function() {

                //Nova variável "cep" somente com dígitos.
                var cep = $(this).val().replace(/\D/g, '');

                //Verifica se campo cep possui valor informado.
                if (cep != "") {

                    //Expressão regular para validar o CEP.
                    var validacep = /^[0-9]{8}$/;

                    //Valida o formato do CEP.
                    if(validacep.test(cep)) {

                        //Preenche os campos com "..." enquanto consulta webservice.
                        $("#endereco").val("...");
                        $("#Bairro").val("...");
                        $("#cidade").val("...");
                        $("#estado").val("...");
                        $("#ibge").val("...");

                        //Consulta o webservice viacep.com.br/
                        $.getJSON("//viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                            if (!("erro" in dados)) {
                                //Atualiza os campos com os valores da consulta.
                                $("#endereco").val(dados.logradouro);
                                $("#Bairro").val(dados.bairro);
                                $("#cidade").val(dados.localidade);
                                $("#estado").val(dados.uf);
                                $("#ibge").val(dados.ibge);
                            } //end if.
                            else {
                                //CEP pesquisado não foi encontrado.
                                limpa_formulário_cep();
                                alert("CEP não encontrado.");
                            }
                        });
                    } //end if.
                    else {
                        //cep é inválido.
                        limpa_formulário_cep();
                        alert("Formato de CEP inválido.");
                    }
                } //end if.
                else {
                    //cep sem valor, limpa formulário.
                    limpa_formulário_cep();
                }
            });
        });

jQuery(document).ready(function(){
		jQuery("#compvalor").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#compvalor1").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#compvalor2").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vltotaser").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vlreceber").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#valormerc").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		
		jQuery("#vtprest").maskMoney({
		  decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#vrec").maskMoney({
		  decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#vtotaltrib").maskMoney({
		  decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#predbc").maskMoney({
		    decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#vlmensal").maskMoney({
		    decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#picms").maskMoney({
		  decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		
		jQuery("#vlcms").maskMoney({
		   decimal:",",
		 thousands:".",
		 allowZero:false
			
		});
		jQuery("#vcred").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});	
		jQuery("#vcarga").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vmcarga").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vcomp").maskMoney({
		   symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});	
		jQuery("#vldoc").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});	
		jQuery("#valor").maskMoney({
		 decimal:",",
		 thousands:"."
			
		});
   	
		jQuery("#vdoc").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vpro").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		
		jQuery("#preco").maskMoney({
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vlricms").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#bcicmsst").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		jQuery("#vlricmsst").maskMoney({
		  symbol:"R$",
		 decimal:",",
		 thousands:"."
			
		});
		
		jQuery("#kg").maskMoney({
		 decimal:",",
		 thousands:".",
		  precision:3,	
		});
	
	jQuery('.deleterow1').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/contas-exec.php",
						data:{act:'delete',idc: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
	jQuery('.deleteuser').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/usuario-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
	jQuery('.deleterowvi').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/viagem-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
		jQuery('.deleterowreceb').click(function(){
            var conf = confirm('Continue delete?');
			
	    if(conf)
			 var cod = jQuery(this).parents('tr').attr('id').split('|');
			 if(cod[1] != ''){
				 
				 alert("Documento recebido,aceito apenas alteração");
				 
				 }else{
					 
                	jQuery(this).parents('tr').fadeOut(function(){
					
					var cods = jQuery(this).attr('id').split('|');	

					jQuery.ajax({
						type:'POST',
						url:"../php/contasareceber-exec.php",
						data:{act:'delete',id: cods[0]},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
				});
			}
	    return false;
	});	
	
	jQuery('.deleterowpaga').click(function(){
            var conf = confirm('Continue delete?');
			
	    if(conf)
			 var cod = jQuery(this).parents('tr').attr('id').split('|');
			 if(cod[1] != ''){
				 
				 alert("Documento pago,aceito apenas alteração!");
				 
				 }else{
                jQuery(this).parents('tr').fadeOut(function(){
					var cods = jQuery(this).attr('id').split('|');
					jQuery.ajax({
						type:'POST',
						url:"../php/contasapagar-exec.php",
						data:{act:'delete',id: cods[0]},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
			});
		}
	    return false;
	});	
		jQuery('.deleterowmenu').click(function(){
            var conf = confirm('Continue delete?');
			
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/menu-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
	
		jQuery('.deleterow').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/fornecedor-exec.php",
						data:{act:'delete',idf: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
	jQuery('.deleterowmoto').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/motorista-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
	
	jQuery('.deleterowca').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/caminhao-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
	
	jQuery('.deleterow2').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/natureza-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
	jQuery('.deleterowmovientr').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/movientradas-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
				
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
	
	jQuery('.deleterow3').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/ctemovi-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
    
        
});
	
	jQuery('.deleterowmov2').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/movi-exec.php",
						data:{act:'delete',idm: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
	
	
    jQuery('.deleteprod').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/produto-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	
    
jQuery('.deletedep').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/departamento-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});

jQuery('.deletegroup').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/grupo-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
jQuery('.deletetp').click(function(){
            var conf = confirm('Continue delete?');
	    if(conf)
                jQuery(this).parents('tr').fadeOut(function(){
					
					jQuery.ajax({
						type:'POST',
						url:"../php/tipodepartamento-exec.php",
						data:{act:'delete',id: jQuery(this).attr('id')},
						success: function(data){
							
							jQuery(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});
 jQuery(document).ready(function() {
	
   		 jQuery("#datepicker").mask("99-99-9999");
  		 jQuery("#datepicker2").mask("99-99-9999");
		 jQuery("#cnpj").mask("99.999.999/9999-99");
		 jQuery("#forcnpj").mask("99.999.999/9999-99");
    	 jQuery("#rescnpj").mask("99.999.999/9999-99");
		 jQuery("#cpfmotorista").mask("999.999.999-99");
		 jQuery("#telresid").mask("(99)9999-9999");
		  jQuery("#celular").mask("(99)99999-9999");
		 jQuery("#cep").mask("99999999");
		 jQuery('#rg').mask('99.999.999-99');
		  jQuery('#cpf').mask('999.999.999-99');
		 //jQuery("#numero").mask("99999");
		 jQuery("#dataini").mask("99.99.9999");
		 
		 jQuery("#datainiemi").mask("99.99.9999");
		 jQuery("#datafin").mask("99.99.9999");
		 jQuery("#datafinemi").mask("99.99.9999");
		 jQuery("#dtadmissao").mask("99-99-9999");
		 jQuery("#dtadmissaoini").mask("99-99-9999");
		  jQuery("#dtadmissaofin").mask("99-99-9999");
		 jQuery("#dtnascimento").mask("99-99-9999");
		 jQuery("#dtexclu").mask("99-99-9999");
		 jQuery("#dtvenc").mask("99-99-9999");
		 jQuery("#dtemis").mask("99-99-9999");
		 jQuery("#dtpag").mask("99-99-9999");
		 jQuery('#cte-hora').mask('99:99');
		 jQuery("#dtmovim").mask("99/99/9999");
	 	  jQuery("#dtcaixa").mask("99/99/9999");	
	 	
		 jQuery('#atano').mask('9999');
		 jQuery("#print").click(function(){
			
			jQuery(".table").jqprint({operaSupport: true})
			
		});	
	
});

jQuery(function() {
	jQuery('#data').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});

	jQuery(function() {
	jQuery('#dtini').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});

jQuery(function() {
	jQuery('#dtfim').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});
	
	
jQuery(function() {
	jQuery('#dtmovim').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
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
			 

	});

jQuery(function() {
	jQuery('#dtcaixa').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
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
			 

	});

jQuery(function() {
	jQuery('#dtvenc').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});
	
jQuery(function() {
jQuery('#dtemis').datepicker({ 
	/*showOn: "button",
	buttonImage: "../images/calendar3.png",
	buttonImageOnly: false,*/
	dateFormat: 'dd-mm-yy', 
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
		 

});	
	
jQuery(function() {
jQuery('#dtpag').datepicker({ 
	/*showOn: "button",
	buttonImage: "../images/calendar3.png",
	buttonImageOnly: false,*/
	dateFormat: 'dd-mm-yy', 
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
		 

});	



$(function() {
 $('#dtmesano').datepicker(
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
				$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
				
				 $('#dtmesano').focusout()//Added to remove focus from datepicker input box on selecting date
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
	})
});
$(function() {
 $('#dtmesanofim').datepicker(
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
				$(this).datepicker('setDate', new Date(year, month, 1)).trigger('change');
				
				 $('#dtmesanofim').focusout()//Added to remove focus from datepicker input box on selecting date
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
	})
});
jQuery(function() {
	jQuery('#dtexclu').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});

jQuery(function() {
	jQuery('#dtnascimento').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});

jQuery(function() {
	jQuery('#dtadmissao').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});
	
jQuery(function() {
	jQuery('#dtadmissaoini').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});	
jQuery(function() {
	jQuery('#dtadmissaofin').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});		
	jQuery(function() {
	jQuery('#dtaexclusaoini').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});
		jQuery(function() {
	jQuery('#dtaexclusaofin').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});
	
jQuery(function() {
	jQuery('#dtrecebimento').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd-mm-yy', 
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
			 

	});

    jQuery(function() {
            jQuery('#iddata').datepicker({ 
            /*showOn: "button",
            buttonImage: "../images/calendar3.png",
            buttonImageOnly: true,*/
            dateFormat: 'dd-mm-yy', 
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
                 
    
        });

  jQuery(function() {
            jQuery('#dataini').datepicker({ 
            /*showOn: "button",
            buttonImage: "../images/calendar3.png",
            buttonImageOnly: true,*/
            dateFormat: 'dd.mm.yy', 
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
                 
    
        });	
  jQuery(function() {
            jQuery('#datainiemi').datepicker({ 
            /*showOn: "button",
            buttonImage: "../images/calendar3.png",
            buttonImageOnly: true,*/
            dateFormat: 'dd.mm.yy', 
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
                 
    
        });	
  

jQuery(function() {
	jQuery('#datafin').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd.mm.yy', 
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
			 

	});

jQuery(function() {
	jQuery('#datafinemi').datepicker({ 
		/*showOn: "button",
	    buttonImage: "../images/calendar3.png",
    	buttonImageOnly: false,*/
		dateFormat: 'dd.mm.yy', 
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
			 

	});


	








function calcular() {
    var soma = 0;
    jQuery( ".soma" ).each(function( indice, item ){
        var valor = parseFloat(jQuery(item).val());
        console.log(valor);
        if ( !isNaN( valor ) ) {
            soma += valor;
        }
    });
    jQuery("#vltotaser").val(soma);
	jQuery("#vlreceber").val(soma);
}

  









jQuery(document).ready(function(){
		// dynamic table
		jQuery('#dyntablee').dataTable({
			"sPaginationType": "full_numbers"
		});
		
		jQuery('#datatableextrat').dataTable({
			"sPaginationType": "full_numbers",
			"iDisplayLength": 1000,
			"sDom": '<"H"lfr>t<"F"ip>T',
			"sPaginationType": "full_numbers"
		});
		
	});
	jQuery(document).ready(function(){
		// dynamic table
		jQuery('#dyntableimpostos').dataTable({
			"sPaginationType": "full_numbers"
		});
		
	});
	jQuery(document).ready(function(){
		// dynamic table
		jQuery('#dyntablectenormal').dataTable({
			"sPaginationType": "full_numbers"
		});
		
	});
		jQuery(document).ready(function(){
		// dynamic table
		jQuery('#dyntableinfseguro').dataTable({
			"sPaginationType": "full_numbers"
		});
		
	});
	






jQuery(document).ready(function(){
	// dynamic table
	jQuery('#dyntablemotorista').dataTable();
	
});

function formatadata(data){
	var novadata = data.split("-");
	var nvdt = ""+novadata[2] +"-"+novadata[1]+"-"+novadata[0]+"";
	return nvdt;
}

jQuery(document).ready(function(){
	// dynamic table
	jQuery('#dyntablecontaspagar').dataTable();
	
});
jQuery(document).ready(function(){
	// dynamic table
	jQuery('#dyntablepagar').dataTable({
			"sPaginationType": "full_numbers"
		});
	
});
















$(document).ready(function() {
    $(".listsub").click(function(){
		$("#idmenu").val($(this).parents('tr').attr('id'));
		
				$.ajax({
					type:'POST',
					async:false, 
					url:"../php/ajax-submenu.php",
					data:{id: $(this).parents('tr').attr('id')},
					success: function(data){
						
						$("#listsubmenu").html(data);
						
					},
					error:function(data){
						
						alert('Erro'+data);
					}
					
				});
		
	});
});
function listasubmenu(cod){
	$.ajax({
		type:'POST',
		async:false, 
		url:"../php/ajax-submenu.php",
		data:{id: cod},
		success: function(data){
			
			$("#listsubmenu").html(data);
			
		},
		error:function(data){
			
			alert('Erro'+data);
		}
		
	});
}
$(document).ready(function(){
	
    // Crio uma variável chamada $forms que pega o valor da tag form
    $forms = $('form[id="submenuexec"]');
	$('#loaders').hide();
    $forms.bind('submit', function(){

        var $button = $('button',this).attr('disabled',true);

        var params = $(this.elements).serialize();
		var cod = $('#nf_nctevinc').val();
			
        var self = this;
        $.ajax({
            type: 'POST',
             url: this.action,
            data: params,
            // Antes de enviar
            beforeSend: function(){
                $('#loaders').show();
                $('#loaders').html("<img src='../images/loading.gif' alt='load'/>");
            },
            success: function(txt){
                $button.attr('disabled',false);
                
				$('#loaders').html(''+txt+'');
				alert(txt);	
				listasubmenu($("#idmenu").val());
				//$('#contaspagar').modal('hide');
            },
            error: function(txt){
                 $('#loaders').html('<pre>'+txt+'</pre>');
            }
        })
        return false;
    });
});


function verificapermissao(cod,url){
	$.ajax({
		type:'POST',
		url:"permicao-exec.php",
		data:{act:'veridicapermisao',id:cod},
		success: function(data){

			if(String(data) != ""){
				
				window.location.href = url;
				
			}else{
				
				alert("Usuário sem permissão para esta opção!\n ´´Entre em contato com o administrador do sistema´´ ");
			}
			
		},
		error:function(data){
			alert(data);							
		}
		
	});
}
function verificapermissaore(cod,url){
	$.ajax({
		type:'POST',
		url:"permicao-exec.php",
		data:{act:'veridicapermisao',id:cod},
		success: function(data){

			if(String(data) != ""){
				
				window.location.href = url;
					//$(this).popover('show');
				$("#menurelatorio").slideToggle('fast');

			}else{
				
				alert("esse usuario nao tem permissão");
			}
			
		},
		error:function(data){
			alert(data);							
		}
		
	});
}



$(document).ready(function() {
    $('#selecionarTodos').click(function() {
        if(this.checked == true){
            $("input[type=checkbox]").each(function() {
                this.checked = true;
            });
        } else {
            $("input[type=checkbox]").each(function() {
                this.checked = false;
            });
        }
    });
});


function numberFormat(n) {
    var parts=n.toString().split(".");
    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".") + (parts[1] ? "," + parts[1] : "");
	}
function dimnui(x,y){
	var val;
	val = x - y;
	return val.toFixed(2);
}









function convertevalores(valor2){
	if(valor2.length > 2 && valor2.length <= 6){
			var valstr2 = parseFloat(valor2.replace(",","."));
	}else{
		var valstr2 = parseFloat(valor2.replace(",",".").replace(".",""));
	}
	
	return valstr2.toFixed(2);
}





function number_format(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
			var k = Math.pow(10, prec);
			return '' + Math.round(n * k) / k;
		};
	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}
	return s.join(dec);
}


function print_r(arr,level) {
var dumped_text = "";
if(!level) level = 0;

//The padding given at the beginning of the line.
var level_padding = "";
for(var j=0;j<level+1;j++) level_padding += "    ";

if(typeof(arr) == 'object') { //Array/Hashes/Objects 
    for(var item in arr) {
        var value = arr[item];

        if(typeof(value) == 'object') { //If it is an array,
            dumped_text += level_padding + "'" + item + "' ...\n";
            dumped_text += print_r(value,level+1);
        } else {
            dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
        }
    }
} else { //Stings/Chars/Numbers etc.
    dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
}
return dumped_text;
}

$(document).ready(function(e) {
    $("#filtrocte").popover({
		
		html: true,
		title: function(){
			return $(".po-title").html();
		},
		content: function(){
			return $(".po-body").html();
		},
		
		placement:'bottom',
		container: 'body',
		trigger:'click'
				
	});
	
	$('#filtrocte').click(function (e) {
				e.stopPropagation();
	});
	$(document).click(function (e) {
				if (($('.popover').has(e.target).length == 0) || $(e.target).is('.close')) {
				$('#horasc').popover('hide');
				}
			});	
			
			
			
	$('#dyntable').dataTable({
		"order": []		
	});
		
	$('#dyntable_ex').dataTable({
		"order": []		
	});	
		
});
