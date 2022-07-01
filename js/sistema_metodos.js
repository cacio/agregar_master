// JavaScript Document


$(document).ready(function(){
	//$('.toast').toast('show');
	$('[data-toggle="tooltip"]').tooltip();
	 $('#dyntable').dataTable({
		"order": [],
		"language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
        },		
	});
	
	 $('#dyntable_dig').dataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],						
		"iDisplayLength": -1,
		"order": [],
		"language": {
	        "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
	    },												
	  });

	 $('#dyntable_dig').dataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],						
		"iDisplayLength": -1,
		"order": []												
	  });

	 $("#valorpago").maskMoney({
		 decimal:",",
		 thousands:"."			
	});	

	$("#precoquilo").maskMoney({
		 decimal:",",
		 thousands:"."		
	});

	$("#valor").maskMoney({
		 decimal:",",
		 thousands:"."			
	});

	$("#valorcarcasa").maskMoney({
		 decimal:",",
		 thousands:"."		
	});

	$("#valoricms").maskMoney({
		 decimal:",",
		 thousands:"."		
	});
	
	$("#valoricmssubs").maskMoney({
	 decimal:",",
	 thousands:"."			
	});

	$("#kg_glos").maskMoney({
		decimal:",",
		thousands:"."			
	});

	$("#valor_glos").maskMoney({
		decimal:",",
		thousands:"."			
	});

	$("#kg_venda").maskMoney({
		decimal:",",
		thousands:"."			
	});

	$("#valor_venda").maskMoney({
		decimal:",",
		thousands:"."			
	});
	 
	 $("#fone").mask("(99)9999-9999");
	 $("#fone2").mask("(99)9999-9999");
	 $("#cep").mask("99999999");
	 $("#cnpj").mask("99.999.999/9999-99");
	 $("#cnpj2").mask("99.999.999/9999-99");	
	 $("#dataemiss").mask("99/99/9999");
	 $("#dataabate").mask("99/99/9999");
	 $("#dtpago").mask("99/99/9999");
	 $("#dtpag").mask("99/9999");
	 $("#login").mask("99.999.999/9999-99");
	 $("#comp").mask("99/9999");

	 $('.selectpicker').selectpicker();
		 function format(state) {
			if (!state.id) return state.text; // optgroup
			return "<img class='flag' src='images/flags/" + state.id.toLowerCase() + ".png'/>" + state.text;
		}
		$(".selectpickerpais").select2({
			//formatResult: format,
			//formatSelection: format,
			escapeMarkup: function(m) { return m; }
		});

	 $("#compini").datepicker({	changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) { 
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
			
        }/*,
        onSelect: function(dateText, inst) { 
        	alert(dateText);
    	}*/
    });

	 $("#compfim").datepicker({	changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'mm/yy',
        onClose: function(dateText, inst) { 
            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
        }/*,
        onSelect: function(dateText, inst) { 
        	alert(dateText);
    	}*/
    });

	 $("#compini").focus(function(){
	 	$(".ui-datepicker-calendar").css({
	 		'display': 'none'
	 	})

	 });

	  $("#compfim").focus(function(){
	 	$(".ui-datepicker-calendar").css({
	 		'display': 'none'
	 	})

	 });


	 jQuery('.deleteuser').click(function(){
		var conf = confirm('Deseja realmente excluir?');
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

	$( "#signupForm" ).validate( {
		rules: {
			nome: "required",			
			login: {
				required: true,
				minlength: 14
			},
			senha: {
				required: true,
				minlength: 5
			},
			confsenha: {
				required: true,
				minlength: 5,
				equalTo: "#senha"
			},
			email: {
				required: true,
				email: true
			},			
		},
		messages: {
			nome: "Informe um nome!",			
			login: {
				required: "Por favor coloque um CNPJ",
				minlength: "Seu nome de CNPJ deve conter no mínimo 14 caracteres"
			},
			senha: {
				required: "Forneça uma senha",
				minlength: "Sua senha deve ter pelo menos 5 caracteres"
			},
			confsenha: {
				required: "Forneça uma senha",
				minlength: "Sua senha deve ter pelo menos 5 caracteres",
				equalTo: "Digite a mesma senha ao lado"
			},
			email: "Por favor insira um endereço de e-mail válido",			
		},
		errorElement: "div",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			// Add `has-feedback` class to the parent div.form-group
			// in order to add icons to inputs
			element.parents( ".col-sm-5" ).addClass( "has-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}

			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !element.next( "span" )[ 0 ] ) {
				$( "<span class='fa fa-remove form-control-feedback'></span>" ).insertAfter( element );
			}
		},
		success: function ( label, element ) {
			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !$( element ).next( "span" )[ 0 ] ) {
				$( "<span class='fa fa-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).next( "span" ).addClass( "fa-remove" ).removeClass( "fa-ok" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).next( "span" ).addClass( "fa-ok" ).removeClass( "fa-remove" );
		}
	} );

	$( "#signupForm2" ).validate( {
		rules: {
			nome: "required",			
			login: {
				required: true,
				minlength: 14
			},
			senha: {
				required: false,
				minlength: 5
			},
			confsenha: {
				required: false,
				minlength: 5,
				equalTo: "#senha"
			},
			email: {
				required: true,
				email: true
			},			
		},
		messages: {
			nome: "Informe um nome!",			
			login: {
				required: "Por favor coloque um CNPJ",
				minlength: "Seu nome de CNPJ deve conter no mínimo 14 caracteres"
			},
			senha: {
				required: "Forneça uma senha",
				minlength: "Sua senha deve ter pelo menos 5 caracteres"
			},
			confsenha: {
				required: "Forneça uma senha",
				minlength: "Sua senha deve ter pelo menos 5 caracteres",
				equalTo: "Digite a mesma senha ao lado"
			},
			email: "Por favor insira um endereço de e-mail válido",			
		},
		errorElement: "div",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			// Add `has-feedback` class to the parent div.form-group
			// in order to add icons to inputs
			element.parents( ".col-sm-5" ).addClass( "has-feedback" );

			if ( element.prop( "type" ) === "checkbox" ) {
				error.insertAfter( element.parent( "label" ) );
			} else {
				error.insertAfter( element );
			}

			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !element.next( "span" )[ 0 ] ) {
				$( "<span class='fa fa-remove form-control-feedback'></span>" ).insertAfter( element );
			}
		},
		success: function ( label, element ) {
			// Add the span element, if doesn't exists, and apply the icon classes to it.
			if ( !$( element ).next( "span" )[ 0 ] ) {
				$( "<span class='fa fa-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			$( element ).next( "span" ).addClass( "fa-remove" ).removeClass( "fa-ok" );
		},
		unhighlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			$( element ).next( "span" ).addClass( "fa-ok" ).removeClass( "fa-remove" );
		}
	} );

});

$(document).ready(function(){
		
	$("#psqempresa").autocomplete({	
	 source:'../php/empresa-exec.php?act=buscar',
	 minLength: 1,
		select: function(event, ui) {
				
			$("#cnpjemp").val(ui.item.cnpj);	
				
		},
		focus: function( event, ui ) {
			
			
		}	
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

$(document).on('change','.agregarsn',function(){
	
	var cfop = $(this).attr('id').split('_')[1];
	var cod  = $(this).val().split('|')[1];
	var sn	 = $(this).val().split('|')[0];
	var act  = cod == '' ? 'inserir' :'alterar';
	
	$.ajax({
		type:'POST',
		async:false, 
		url:"../php/cfopempresa-exec.php",
		data:{act: act,cfop:cfop,cod:cod,agregarsn:sn},
		success: function(data){
			
			
			
		},
		error:function(data){

		}
	});
	
	return false;
	
});

$(document).ready(function(){

      var list = $(".message-widget2 a");
      var numToShow = 3;
      var button  = $("#next");
      var button2 = $("#back");

      var numInList = list.length;
      list.hide();
      if (numInList > numToShow) {
        button.show();
      }
      list.slice(0, numToShow).show();

      button.click(function(){
          var showing = list.filter(':visible').length;
          list.slice(showing - 1, showing + numToShow).fadeIn();
          var nowShowing = list.filter(':visible').length;
          if (nowShowing >= numInList) {
            //button.hide();
            var setv = setInterval(function(){
				button.attr('id', 'back');            	
				button.html('Menos -');				
				clearInterval(setv);
            },300);            
            
          }
      });
      

      $(".compsearch").click(function(){
			
			$(".pesquisacomp").removeClass('hide');
			$(".compsearch").html('<i class="fa fa-times fa-2x"></i>');
			$(".compsearch").attr('class','compsearch2');
			//$(".pesquisacomp").toggle('slow');      		
			$(".titlecompetenc").addClass('hide');	
      });

});
$(document).on("click",".compsearch2",function(){
	$(".pesquisacomp").addClass('hide');

	$(".compsearch2").html('<i class="fa fa-search fa-2x"></i>');
	$(".compsearch2").attr('class','compsearch');

	$(".titlecompetenc").removeClass("hide");
});

$(document).on('click','.compsearch3',function(){
	
	$(".pesquisacomp2").removeClass('hide');
	$(".compsearch3").html('<i class="fa fa-times fa-2x"></i>');
	$(".compsearch3").attr('class','compsearch4');
	$(".titlecompetenc2").addClass("hide");
});

$(document).on('click','.compsearch4',function(){
	
	$(".pesquisacomp2").addClass('hide');
	$(".compsearch4").html('<i class="fa fa-search fa-2x"></i>');
	$(".compsearch4").attr('class','compsearch3');
	$(".titlecompetenc2").removeClass("hide");
});

$(document).on('click','#back',function(){

     var button2   = $("#back");
	 var numToShow = 3;
	 var list 	   = $(".message-widget2 a");
	 list.hide();
	 
	 list.slice(0, numToShow).fadeIn();	
	  var setv = setInterval(function(){
		button2.attr('id', 'next');
		button2.html('Mais +');
		clearInterval(setv);
    },300);

});


$(document).ready(function() {
  var $input = $('#autocomplete');
  $(document).on('keyup', $input, function() {
  	//alert('as');
    var $val = $input.val(),
        $select = $('.message-widget2');
    if($val){    
	    // Check if the input isn't empty
	    if ($val != '') {
	      $select.children('a').hide();
	      $select.children('a').filter(function() { 
	      	//console.log($(this).text().indexOf($val))
	      	return $(this).text().toUpperCase().indexOf($val) !== -1; 
	      }).show();
	    } else {
	      $select.children().show();
	    	 var numToShow = 3;
			 var list 	   = $(".message-widget2 a");
			 list.hide();
			 
			 list.slice(0, numToShow).fadeIn();	
	    }
	}

  });
});


$(document).ready(function(){

	$(".deleteguiaicms").click(function(){


		var id = $(this).parents('tr').attr('id');
		var tr = $(this).parents('tr');
		var confir = confirm("Deseja realmente excluir ?");		

		if(confir){

			 $.ajax({
            type: 'POST',
             url: "../php/guiaicms-exec.php",
            data: {act:'delete',id:id},            
            success: function(txt){
              	tr.remove();  
            },
            error: function(txt){
                alert("Algo deu errado!");
            }
        })
        return false;


		}

	});

});

Number.prototype.pad = function(size, character = "0") {
	var s = String(this);
	while (s.length < (size || 2)) {s = character + s;}
	return s;
  }

jQuery(function() {
	
	jQuery('#dtpago').datepicker({ 
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
	jQuery('#dataini').datepicker({ 
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
	jQuery('#datafin').datepicker({ 
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
	jQuery('#dataemiss').datepicker({ 
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
	jQuery('#dataabate').datepicker({ 
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
	
$(document).ready(function(e){
			
		$("select[name='tpdoc']").change(function(){
			
			$("#xtitulo").val($("select[name='tpdoc'] option:selected").text());
			$.ajax({
				type:'POST',
				url:"../php/docmodalidade-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'listadocumento',idmodenv:$(this).val()},
				success: function(data){
					
					var htm = "";
					for(var i = 0; i < data.length; i++){
						
						htm +='<div class="col-md-4">'+
							'<input type="hidden" name="arq[titulo]['+i+']" value="'+data[i].nome+'"/>'+
							'<label><strong>Arquivo:</strong>'+data[i].nome+'</label>'+
							'<div class="input-group">'+
							'<span class="input-group-addon fa fa-file-text fa-2x"></span>'+
							'<input type="file" name="arq['+i+']" class="form-control"/>'+
							'</div>'+
							'</div><br/>';
						
					}
					
					$(".docenv").html(htm);
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
			
		});	
		
			
	});
	

	$(document).ready(function(e) {  
	   	 var ums = UM.getEditor('myEditor');		 
		 
		 $("#arq").change(function(){
								
			var input = document.getElementById('arq');
			var htm   = "";
			for (var x = 0; x < input.files.length; x++) {
				//alert(print_r(input.files[x]));
				alert(input.files[x].name);
				var ext = extrairArquivo(input.files[x].name);
				if(ext.extensao == "doc" || ext.extensao == "docx"){
					htm += '<li><img src="../images/ext/word.png"/><a href="#">'+input.files[x].name+'</a></li> ';	
				}else if(ext.extensao == "png"){
					htm += '<li><img src="../images/ext/pngs.png"/><a href="#">'+input.files[x].name+'</a></li> ';
				}else if(ext.extensao == "jpg"){
					htm += '<li><img src="../images/ext/jpgs.png"/><a href="#">'+input.files[x].name+'</a></li> ';
				}else if(ext.extensao == "xls" || ext.extensao == "csv"){
					htm += '<li><img src="../images/ext/Excel_15.png"/><a href="#">'+input.files[x].name+'</a></li> ';
				}else if(ext.extensao == "pdf"){
					htm += '<li><img src="../images/ext/pdf.png"/><a href="#">'+input.files[x].name+'</a></li> ';
				}else if(ext.extensao == "txt"){
					htm += '<li><img src="../images/ext/txts.png"/><a href="#">'+input.files[x].name+'</a></li> ';
				}else{
					htm += '<li><img src="../images/ext/docs.png"/><a href="#">'+input.files[x].name+'</a></li> ';	
				} 				
				
				alert(ext.extensao);
			}	
					
			$(".view_doc").html(htm);		
							 
		})		 
    });

	$(document).ready(function(e) {
    $forms = $('form[id="formdigitalizacaoreenvia"]');
    $forms.bind('submit', function(){

    	var file     = $('input[type="file"]');
    	var contador = 0;

    	for (var i = 0; i < file.length; i++) {
    		//alert($('.chec'+i+'').html());
    		if($('input[name="arq['+i+']"]').val().length == 0 ){
    			if($('.chec'+i+'').html().trim() == ""){
					var conf = confirm('Falta o arquivo '+$('input[name="arq[titulo]['+i+']"]').val()+' a ser informado, Deseja informar? ');    			
					if(conf == true){
						contador++;
					}
				}
    		}

	    	if($('.chec'+i+'').html().trim() != ""){
	    		if($('input[name="arq['+i+']"]').val().length > 0){
	    			var config = confirm('Deseja Alterar o arquivo '+$('input[name="arq[titulo]['+i+']"]').val()+' informado?');
	    			if(config == false){
	    				$('input[name="arq['+i+']"]').val('');
	    			}
	    		}
	    	}
    		
    	}


    	if(parseInt(contador) > 0){
    		return false;
    	}
    	
        var $button = $('button',this).attr('disabled',true);
	    var params = new FormData($(this)[0]);
		
        var self = this;
        $.ajax({
            type: 'POST',
             url: this.action,
            data: params,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
              
            },
            success: function(data){
                $button.attr('disabled',false);
               				
				if(data[0].tipo == '2'){
					alert(data[0].msg);
					window.location.href = '../php/lista-documentosdigitalizadosemp.php';
				}else{
					alert(data[0].msg);	
				}
				
            },
            error: function(data){
 				$button.attr('disabled',false);              
            }
        })
        return false;
    });
});

$(document).ready(function(e) {
    $forms = $('form[id="formdigitalizacao"]');
    $forms.bind('submit', function(){

    	var file     = $('input[type="file"]');

    	var contador = 0;

    	for (var i = 0; i < file.length; i++) {
    		if($('input[name="arq['+i+']"]').val().length == 0 ){

				var conf = confirm('Falta o arquivo '+$('input[name="arq[titulo]['+i+']"]').val()+' a ser informado, Deseja informar? ');    			
				if(conf == true){
					contador++;
				}
    		}
    		
    	}

    	if(parseInt(contador) > 0){
    		return false;
    	}

        var $button = $('button',this).attr('disabled',true);
	    var params = new FormData($(this)[0]);
		var dialog;
        var self = this;
        $.ajax({
            type: 'POST',
             url: this.action,
            data: params,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
              dialog = $.dialog({
					title: 'MENSAGEM',
					content: 'AGUARDE ENVIANDO!',
				});
            },
            success: function(data){
                $button.attr('disabled',false);
               	dialog.close();			
				if(data[0].tipo == '2'){
					alert(data[0].msg);
					window.location.href = '../php/lista-documentosdigitalizadosemp.php';
				}else{
					alert(data[0].msg);	
				}
				
            },
            error: function(data){
 				$button.attr('disabled',false);
				dialog.close();	
				$.dialog({
					title: 'MENSAGEM',
					content: 'OPS! ALGO DEU ERRADO CONTATE O ADMINISTRADOR DO SISTEMA OBRIGADO!',
				});		
            }
        })
        return false;
    });

  
});	
var addrow = $('#demo-foo-row-toggler');
 	addrow.footable();
$('#demo-input-search2').on('input', function (e) {
	e.preventDefault();
	addrow.trigger('footable_filter', {filter: $(this).val()});
});


/*$(document).on('click','select[name="status"]',function(){
	
	if($(this).val() == ""){


		$.ajax({
			type:'POST',
			url:"../php/status-exec.php",
			cache: false,
			dataType: 'json',
			data:{act:'lista'},
			success: function(data){
				
				var htm = '';
				
				for(var i = 0; i < data.length; i++){
					
					htm +='<option value="'+data[i].codigo+'">'+data[i].nome+'</option>';
					
				}
				
				$("#status").html(htm);
				
			},
			error: function(data){
				alert(data.status);
			}	
		});
	
		
	}
	
});*/

function ListaStatus(){
	var ret = [];
	$.ajax({
			type:'POST',
			url:"../php/status-exec.php",
			async: false,
			dataType: 'json',
			data:{act:'lista'},
			success: function(data){
							
				ret = data;				
				
			},
			error: function(data){
				ret = [];				
			}	
		});
	return ret;
}

$(document).on('click','select[name="cmodalid"]',function(){

	if($(this).val()){
		$.ajax({
			type:'POST',
			url:"../php/empresa-exec.php",
			cache: false,
			dataType: 'json',
			data:{act:'empresa_modalidade',idmod:$(this).val()},
			success: function(data){
				
				var htm = "<option value=''>Todos</option>";
				$('select[name="cvempresa"]').html('');
				for(var i = 0; i < data.length; i++){
					
					htm +='<option value="'+data[i].cod+'">'+data[i].razao_social+'</option>';
					
				}
				
				$('select[name="cvempresa"]').append(''+htm+'');
				//$('select[name="cvempresa"]').selectric('refresh');
				
			},
			error: function(data){
				alert(data.status);
			}	
		});
	}	
});

$(document).ready(function(e) {
	 
	
	$(".popmail").popover({
		title:function(){
			return $(".cv-title").html();
		},
		content:function(){
			return $(".cv-content").html();
		},
		placement:'bottom',
		html:true,
		container:'body'	
	});
	
	$(".bclicfilter").popover({
		title:function(){
			return $(".f-titulo").html();
		},
		content:function(){
			return $(".f-content").html();
		},
		placement:'bottom',
		html:true,
		container:'body'	
	});
	

});

$(document).on('focus','.dtini',function(){
	$(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" );
	$(this).mask("99/99/9999");
});

$(document).on('focus','.dtfin',function(){
	$(this).datepicker({dateFormat: 'dd/mm/yy'}).datepicker( "show" );
	$(this).mask("99/99/9999");
});

$(document).on('submit','form[id="formmensagem"]',function(){
  		
		var $form = $(this);
        var params = $form.serialize();	
		var dialog;
		
        var self = this;
        $.ajax({
            type: 'POST',
             url: this.action,
            data: params,
			cache: false,
			dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
             	dialog = $.dialog({
					title: 'MENSAGEM',
					content: 'AGUARDE ENVIANDO MENSAGEM!',
				});
            },
            success: function(data){
              	dialog.close();
			  					
				var ms = $.confirm({
					title: 'Sucesso',
					content: ''+data[0].msg+'',
					confirm: function(){							
						ms.close();								
					},
					cancel: function(){
						ms.close();
					}
				});
				
				
            },
            error: function(data){
				dialog.close();
 				alert("Ops Algo deu errado:\rEntre em contato com Administrador do sistema");	
            }
        });
        return false;
    });


$(document).ready(function(e) {
        $(".modal_docs").click(function(){
			
			$("#mod_id").val($(this).parents('tr').attr('id'));
			
			$.ajax({
				type:'POST',
				url:"../php/modalidadeenv-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'lista',id:$(this).parents('tr').attr('id')},
				success: function(data){
					
					var htm  = "";
					var xhtm = "";
					
					for(var i = 0; i < data.length; i++){
						
						if(data[i].sel	== 'selected'){
							
						  htm += '<div id="docs'+data[i].id+'" userid="'+data[i].id+'" class="innertxt2">'+
								'<img src="../images/docs.png" width="75" height="75"><strong>DOC: '+data[i].nome+'</strong>'+
								'<ul>'+
									/*'<li>DOC:<strong>'+data[i].nome+'</strong></li>'+*/
								    '<li style="padding-top:5px;"><label for="select'+data[i].id+'"><input type="checkbox" id="select'+data[i].id+'" value="'+data[i].id+'" class="selectit" />&nbsp;&nbsp;Selecioná-lo.</label></li>'+
								'</ul>'+
							 '</div>';
							
						}else{
							
							 xhtm += '<div id="docs'+data[i].id+'" userid="'+data[i].id+'" class="innertxt">'+
								'<img src="../images/docs.png" width="75" height="75"><strong>DOC: '+data[i].nome+'</strong>'+
								'<ul>'+
									/*'<li>DOC:<strong>'+data[i].nome+'</strong></li>'+*/
								    '<li style="padding-top:5px;"><label for="select'+data[i].id+'"><input type="checkbox" id="select'+data[i].id+'" value="'+data[i].id+'" class="selectit" />&nbsp;&nbsp;Selecioná-lo.</label></li>'+
								'</ul>'+
							 '</div>';
							
						}
						
						
					}
					
					$("#all_users").html(xhtm);
					$("#selected_users").html(htm);
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
			
			$('#modal_docs').modal('show');
		});
    });

$(document).ready(function () {
		// Uncheck each checkbox on body load
		$('#all_users .selectit').each(function() {this.checked = false;});
		$('#selected_users .selectit').each(function() {this.checked = false;});
		
		$(document).on('click','#all_users .selectit',function(){	
			var userid = $(this).val();			
			$('#docs' + userid).toggleClass('innertxt_bg');
		});
		$(document).on('click','#selected_users .selectit',function(){
	
			var userid = $(this).val();
			
			$('#docs' + userid).toggleClass('innertxt_bg');
		});
		
		$("#move_right").click(function() {
			var users = $('#selected_users .innertxt2').length;
			var selected_users = $('#all_users .innertxt_bg').length;
			
			/*if (users + selected_users > 5) {
				alert('You can only chose maximum 5 users.');
				return;
			}*/
			
			$('#all_users .innertxt_bg').each(function() {
				var user_id = $(this).attr('userid');
				$('#select' + user_id).each(function() {this.checked = false;});
				
				var user_clone = $(this).clone(true);
				$(user_clone).removeClass('innertxt');
				$(user_clone).removeClass('innertxt_bg');
				$(user_clone).addClass('innertxt2');
				
				$('#selected_users').append(user_clone);
				$(this).remove();
			});
		});
		
		$("#move_left").click(function() {
			$('#selected_users .innertxt_bg').each(function() {
				var user_id = $(this).attr('userid');
				$('#select' + user_id).each(function() {this.checked = false;});
				
				var user_clone = $(this).clone(true);
				$(user_clone).removeClass('innertxt2');
				$(user_clone).removeClass('innertxt_bg');
				$(user_clone).addClass('innertxt');
				
				$('#all_users').append(user_clone);
				$(this).remove();
			});
		});
		
		$('#view').click(function() {
			var users = '';
			$('#selected_users .innertxt2').each(function() {
				var user_id = $(this).attr('userid');
				if (users == '') 
					users += user_id;
				else
					users += ',' + user_id;
			});
		
			var arr = [];
				arr = users.split(',');

			$.ajax({
				type:'POST',
				url:"../php/modenvmodalidade-exec.php",
/*				cache: false,
				dataType: 'json',*/
				data:{act:'inserir',ids:arr,idmod:$("#mod_id").val()},
				success: function(data){
					
					alert(data);
					$('#modal_docs').modal('hide');
				},
				error: function(data){
					alert(data.status);
				}	
			});
			
			
		});
		
		$('#views').click(function() {
			var users = '';
			$('#selected_users .innertxt2').each(function() {
				var user_id = $(this).attr('userid');
				if (users == '') 
					users += user_id;
				else
					users += ',' + user_id;
			});
		
			var arr = [];
				arr = users.split(',');

			$.ajax({
				type:'POST',
				url:"../php/docmodalidade-exec.php",
/*				cache: false,
				dataType: 'json',*/
				data:{act:'inserir',ids:arr,idmod:$("#mod_idenv").val()},
				success: function(data){
					
					alert(data);
					$('#modal_documentos').modal('hide');
				},
				error: function(data){
					alert(data.status);
				}	
			});
			
			
		});
		
	});

$(document).on('click','.modal_documentos',function(){
      			
			$("#mod_idenv").val($(this).parents('tr').attr('id'));
			
			$.ajax({
				type:'POST',
				url:"../php/documento-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'lista',id:$(this).parents('tr').attr('id')},
				success: function(data){
					
					var htm  = "";
					var xhtm = "";
					
					for(var i = 0; i < data.length; i++){
						
						if(data[i].sel	== 'selected'){
							
						  htm += '<div id="docs'+data[i].id+'" userid="'+data[i].id+'" class="innertxt2">'+
								'<img src="../images/docsdig.png" width="75" height="75"><strong>DOC: '+data[i].nome+'</strong>'+
								'<ul>'+
									/*'<li>DOC:<strong>'+data[i].nome+'</strong></li>'+*/
								    '<li style="padding-top:5px;"><input type="checkbox" id="select'+data[i].id+'" value="'+data[i].id+'" class="selectit" /><label for="select'+data[i].id+'">&nbsp;&nbsp;Selecioná-lo.</label></li>'+
								'</ul>'+
							 '</div>';
							
						}else{
							
							 xhtm += '<div id="docs'+data[i].id+'" userid="'+data[i].id+'" class="innertxt">'+
								'<img src="../images/docsdig.png" width="75" height="75"><strong>DOC: '+data[i].nome+'</strong>'+
								'<ul>'+
									/*'<li>DOC:<strong>'+data[i].nome+'</strong></li>'+*/
								    '<li style="padding-top:5px;"><input type="checkbox" id="select'+data[i].id+'" value="'+data[i].id+'" class="selectit" /><label for="select'+data[i].id+'">&nbsp;&nbsp;Selecioná-lo.</label></li>'+
								'</ul>'+
							 '</div>';
							
						}
						
						
					}
					
					$("#all_users").html(xhtm);
					$("#selected_users").html(htm);
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
			
			$('#modal_documentos').modal('show');
		});
		
		
$(document).ready(function(){

	$('.deletedocs').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
		
				var veri = VerificaDocumento($(this).parents('tr').attr('id'));	
				var htm = '';
				if(veri.length >0){
					
					for(var i = 0; i < veri.length; i++){
						
						
							
						htm += ""+veri[i].idmodalidade+" - "+veri[i].modalidade+"\r";	
					}
					
					var pront = confirm('Existem vinculo com esses documentos\r'+htm+'\rDeseja relamente excluir?');
				}else{
					var pront = true;	
				}
				
			if(pront == true){
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/documento-exec.php",
						data:{act:'delete',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});										
			});
		}
	    return false;
	});


	$('.deletemod').click(function(){

        var conf = confirm('Deseja realmente excluir?');
	    if(conf)
			
			var veri2 = VerificaDocumento2($(this).parents('tr').attr('id'));	
				var htm2 = '';
				if(veri2.length >0){
					
					for(var i = 0; i < veri2.length; i++){
						
						
							
						htm2 += ""+veri2[i].iddocumento+" - "+veri2[i].documento+"\r";	
					}
					
					var pront2 = confirm('Existem vinculo com essa modalidade\r'+htm2+'\rDeseja relamente excluir?');
				}else{
					var pront2 = true;	
				}
		
				if(pront2 == true){
					$(this).parents('tr').fadeOut(function(){
						
							$.ajax({
								type:'POST',
								url:"../php/modalidade-exec.php",
								data:{act:'delete',id: $(this).attr('id')},
								success: function(data){
									
									$(this).remove();
									
								}	
							});
							
							
					});
				}
	    return false;
	});		

	$('.deletemodenv').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
			
			var veri2 = VerificaDocumento2($(this).parents('tr').attr('id'));	
				var htm2 = '';
				if(veri2.length >0){
					
					for(var i = 0; i < veri2.length; i++){
						
						
							
						htm2 += ""+veri2[i].iddocumento+" - "+veri2[i].documento+"\r";	
					}
					
					var pront2 = confirm('Existem vinculo com essa modalidade\r'+htm2+'\rDeseja realmente excluir?');
				}else{
					var pront2 = true;	
				}
		
				if(pront2 == true){
					$(this).parents('tr').fadeOut(function(){
						
							$.ajax({
								type:'POST',
								url:"../php/modalidadeenv-exec.php",
								data:{act:'delete',id: $(this).attr('id')},
								success: function(data){
									
									$(this).remove();
									
								}	
							});
							
							
					});
				}
	    return false;
	});


	$('.deletestatus').click(function(){
            var conf = confirm('Deseja realmente excluir?');
	    if(conf)
                $(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/status-exec.php",
						data:{act:'deletar',id: $(this).attr('id')},
						success: function(data){
							
							$(this).remove();
							
						}	
					});
					
					
			});
	    return false;
	});	


	$('.deleteexpo').click(function(){
		var conf = confirm('Deseja realmente excluir?');
		if(conf)
				$(this).parents('tr').fadeOut(function(){
					
					$.ajax({
						type:'POST',
						url:"../php/exportacao-exec.php",
						data:{act:'delete',id: $(this).attr('id')},
						success: function(data){							
							$(this).remove();							
						}	
					});										
			});
		return false;
	});


});

function VerificaDocumento2(cdoc){
	
	
	var retorno = "";
		
	$.ajax({
		 type: 'POST',
		 url:"../php/docmodalidade-exec.php",
		 data:{act:'verifica2',iddoc:cdoc},
		 async:false,
		 dataType: "json",
		 success: function(data){
							
			retorno = data;	
																	
		},
		error: function(jqXHR, exception){
			if (jqXHR.status === 0) {
			alert('Não conectar.\n Verifique Rede.');
			} else if (jqXHR.status == 404) {
				alert('A página solicitada não foi encontrado. [404]');
			} else if (jqXHR.status == 500) {
				alert('Erro do Servidor Interno [500].');
			} else if (exception === 'parsererror') {
				alert('JSON solicitada falhou analisar.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Solicitação Ajax abortado.');
			} else {
				alert('erro não detectado.\n' + jqXHR.responseText);
			}	
		}	
	});
	
	return retorno;	



}

function VerificaDocumento(cmod){
	
	
	var retorno = "";
		
	$.ajax({
		 type: 'POST',
		 url:"../php/docmodalidade-exec.php",
		 data:{act:'verifica',idmod:cmod},
		 async:false,
		 dataType: "json",
		 success: function(data){
							
			retorno = data;	
																	
		},
		error: function(jqXHR, exception){
			if (jqXHR.status === 0) {
			alert('Não conectar.\n Verifique Rede.');
			} else if (jqXHR.status == 404) {
				alert('A página solicitada não foi encontrado. [404]');
			} else if (jqXHR.status == 500) {
				alert('Erro do Servidor Interno [500].');
			} else if (exception === 'parsererror') {
				alert('JSON solicitada falhou analisar.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Solicitação Ajax abortado.');
			} else {
				alert('erro não detectado.\n' + jqXHR.responseText);
			}	
		}	
	});
	
	return retorno;	



}

$(document).on('click','.btn-compentencia',function(){
	var id = $(this).attr('data-id');

	$.confirm({
		title: 'Apuração dos dados da compentência '+id.split('|')[0]+' ',
		columnClass: 'col-md-10 col-md-offset-8 col-xs-4 col-xs-offset-8',
		buttons: {		
			nao: {
				text: 'FECHAR',
				btnClass: 'btn-red',
				action: function(){
					
				}
			}
		},
	    content: function () {
	        var self = this;
	        return $.ajax({
	            url: '../php/apuracao-exec.php',
	            dataType: 'json',
	            method: 'POST',
	            data:{act:'buscaapuracao',mesano:id.split('|')[0],cnpj:id.split('|')[1]}
	        }).done(function (arr) {

	        var txt = "";
            txt += '<div  align="center" style="margin:0 auto;width: 35%;float: left;">';
            	txt += '<ul class="list-group list-group-full">'+
					        '<li class="list-group-item">'+
					          'NUMERO PROTOCOLO<br/> '+arr.prot[0].protocolo+' '+
					        '</li>'+
					        '<li class="list-group-item">'+
							  '<input type="hidden" id="codprotocolo" value="'+arr.prot[0].protocolo+'" />'+
					          '<div class="form-group"><label>STATUS</label>'+
	                            '<select name="liststatus" class="custom-select form-control btn-block">';
	                            for(i = 0; i < arr.status.length; i++){

	                                txt +='<option value="'+arr.status[i].codstatus+'">'+arr.status[i].nomestatus+'</option>';	          
	                               }                    
	                          txt +='</select></div>'+
					        '</li>'+
					        '<li class="list-group-item">'+
					          '<a href="#" class="btn btn-success btn-block print_apura"><i class="fa fa-print"></i> IMPRIMIR</a>'+
					        '</li>'+					        					        
					      '</ul>';

            txt += '</div>';
            txt += '<div  align="center" id="content" style="margin:0 auto;width: 64%;float: right;border-left: 1px solid silver;">'+
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
			  '---------------------------------------- <br/>'+


			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:262px; border-bottom:1px dashed #000000;">'+
			    	'NOTAS'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:81px; border-bottom:1px dashed #000000;">'+
			    	'BASE'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:102px; border-bottom:1px dashed #000000;">'+
			    	'CREDITO'+
			    '</div>'+
			'</div>'+
			'<br/>'+
			'<br/>'+

			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:253px;">'+
			    	'DE PRODUTOR..........................'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.basecredito[0].base+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.basecredito[0].credito+''+
			    '</div>'+
			'</div>'+
			'<br/>'+
			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:40px; width:245px;">'+
			    	'VENDAS RIO GRANDE DO SUL 3%'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.vendars[0].basers+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.vendars[0].creditors+''+
			    '</div>'+
			'</div>'+
			'<br/>'+
			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:259px;">'+
			    	'VENDAS RIO GRANDE DO SUL 4%'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.vendars2[0].basers2+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.vendars2[0].creditors2+''+
			    '</div>'+
			'</div>'+
			'<br/>'+


			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:258px;">'+
			    	'VENDAS OUTROS ESTADOS 3%'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.vendasdifrs[0].basedifrs+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.vendasdifrs[0].creditodifrs+''+
			    '</div>'+
			'</div>'+
			'<br/>'+
			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:257px;">'+
			    	'VENDAS OUTROS ESTADOS 4%'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.vendasdifrs2[0].basedifrs2+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.vendasdifrs2[0].creditodifrs2+''+
			    '</div>'+
			'</div>'+
			'<br/>'+

			'<div style="text-align:left; display:inline-table;">'+
				'<div style="display:inline-table; margin-right:25px; width:245px;">'+
			    	'TOTAL GERAIS'+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:80px; ">'+
			    	''+arr.total_geral_base+''+
			    '</div>'+
			    '<div style="display:inline-table; text-align:right; margin-right:20px; width:103px; ">'+
			    	''+arr.total_geral_credito+''+
			    '</div>'+
			'</div>'+

			'</div>';
				
	         self.setContent(txt);	            

	         var st = setInterval(function(){

	         	$("select[name='liststatus'] option[value='"+arr.prot[0].xstatus+"']").attr("selected","selected");
	         	clearInterval(st);
	         },600);

	        }).fail(function(){
	            self.setContent('Something went wrong.');
	        });
	    }
	});

});

$(document).on('change','select[name="liststatus"]',function(){
	
	var cod =  $(this).val();
	var prot = $("#codprotocolo").val();
	console.log(cod+' - '+prot);
	$.ajax({
		 type: 'POST',
		 url:"../php/protocolo-exec.php",
		 data:{act:'alterar',codstatus:cod,codprot:prot},
		 cache:false,
		 dataType: "json",
		 success: function(data){
										
																		
		},
		error: function(jqXHR, exception){
			if (jqXHR.status === 0) {
			alert('Não conectar.\n Verifique Rede.');
			} else if (jqXHR.status == 404) {
				alert('A página solicitada não foi encontrado. [404]');
			} else if (jqXHR.status == 500) {
				alert('Erro do Servidor Interno [500].');
			} else if (exception === 'parsererror') {
				alert('JSON solicitada falhou analisar.');
			} else if (exception === 'timeout') {
				alert('Time out error.');
			} else if (exception === 'abort') {
				alert('Solicitação Ajax abortado.');
			} else {
				alert('erro não detectado.\n' + jqXHR.responseText);
			}	
		}	
	});
	
	
});


$(document).ready(function(){
	$(".linkarchive").click(function(){

		
		var id      = $(this).attr('id');
		var caminho = $(this).attr('data-caminho');
		var content = $(this).attr('data-content');
		$(".listbtnarch").show();
		$(".btninfoarchev").attr('data-content',content);
		$(".btninfoedit").attr('href','../php/reenv-dados.php?idtpdoc='+id+'');

		$.ajax({
			type:'POST',
			cache:false, 
			url:"../php/documentosdigitalizado-exec.php",
			data:{act:'consultaarchive',cod:id},
			dataType: 'json',
			beforeSend: function(){
				$(".load").html("<img src='../images/loader2.gif'/>");	
			},
			success: function(data){					
				
				var htm = '';

				for (var i = 0; i < data.length; i++) {
					
					var row = data[i];

					htm += '<div class="col-lg-3 col-md-6">'+                                
							'<div class="card">'+
								'<img class="card-img-top img-responsive" src="../images/ext/archive.jpg" alt="Card image cap">'+
								'<div class="card-block" style="padding: 6px;">'+
									'<h4 class="card-title">'+row.nomedocs+'</h4>'+                                        
									'<a href="'+caminho+''+row.docs+'" class="btn btn-primary btn-block popup-archive"><i class="fa fa-search-plus"></i> Visualizar</a>'+
									'<a href="'+caminho+''+row.docs+'" class="btn btn-primary btn-block" download="'+row.docs+'"><i class="fa fa-cloud-download"></i> Baixar</a>'+
								'</div>'+
							'</div>'+                                
						'</div>';

				}
					
				
				
				
				
				var pathArray = window.location.pathname.split('/');
				var newPathname = "";
				for (i = 0; i < pathArray.length; i++) {
				  newPathname += "/";
				  newPathname += pathArray[i];					
					if(pathArray[i] == 'lista-documentosdigitalizados.php'){
						
						var status = ListaStatus();
							var opt    = "";

							if(status.length > 0){
								
								for(var y = 0; y < status.length; y++){
									
									opt +='<option value="'+status[y].codigo+'">'+status[y].nome+'</option>';
									
								}
								
							}
						
						
						htm += '<form id="frmstatus" action="../php/documentosdigitalizado-exec.php" class="col-lg-12">'+ 
								'<input type="hidden" name="act" value="updstatus" />'+
								'<input type="hidden" name="id" value="'+id+'" />'+
								'<div class="col-md-4">'+
									'<label><strong>Status:</strong></label>'+
									'<div class="input-group"> '+
										'<span class="input-group-addon fa fa-list"></span>'+
										'<select name="status" class="form-control" id="status" required>'+
											'<option value="">Selecione</option>'+
											''+opt+''+
										'</select>'+
									'</div>'+
								'</div>'+
								'<div class="col-md-12">'+
									'<label><strong>Motivo:</strong></label>'+
									'<div class="input-group"> '+
										'<textarea type="text/plain" class="form-control" name="xmotivo" >'+content+'</textarea>'+
									'</div>'+
								'</div><br>'+
								'<button class="btn  btn-block btn-primary" type="submit">Gravar</button>'+
							'</form>';
					}
				}
				
				
				
				$(".conteudo_archive").html(htm);

				$('.popup-archive').magnificPopup({
						disableOn: 700,
						type: 'iframe',
						mainClass: 'mfp-fade',
						removalDelay: 160,
						preloader: false,
						srcAction: 'iframe_src',
						fixedContentPos: false
				});			

				$(".load").html("");
			},
			error:function(data){

			}
		});
		
		return false;

	});
	
	$("select[name='modalidade']").change(function(){
			//alert($(this).find(':selected').text());	
		if($(this).val() == '1'){
			
			$(".tpmodalidade").show();
			$(".ide_modalidade").html($(this).find(':selected').text());
			$(".tpmodalidade").append('<input type="hidden" name="agregar" id="selagregar" value="selecionado"/>');
				
		}else{
			$(".tpmodalidade").hide();	
			$("#selagregar").remove();
		}
		
		
	});

	$('.js-switch').each(function() {
            new Switchery($(this)[0], $(this).data());
    });

});

$(document).on('submit','form[id="frmstatus"]',function(){
  		
		var $form = $(this);
        var params = $form.serialize();	

		
        var self = this;
        $.ajax({
            type: 'POST',
             url: this.action,
            data: params,
			cache: false,
			dataType: 'json',
            // Antes de enviar
            beforeSend: function(){
              
            },
            success: function(data){
              
			  	alert(data[0].msg);
				
            },
            error: function(data){
 				alert("Ops Algo deu errado:\rEntre em contato com Administrador do sistema");	
            }
        });
        return false;
    });


$(document).on('keyup','#nome',function(){		
	$(".ide_razao").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#fantasia',function(){		
	$(".ide_fantasia").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#marca',function(){		
	$(".ide_marcapropria").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#cnpj',function(){		
	$(".ide_cnpj").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');
	$(".ide_cgc").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#ende',function(){		
	$(".ide_endereco").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#nro',function(){		
	$(".ide_numero").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#cpl',function(){		
	$(".ide_complemento").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).on('keyup','#bairro',function(){		
	$(".ide_bairro").html('<strong class="pontilhado">'+$(this).val().toUpperCase()+'</strong>');		
});

$(document).ready(function(e) {
	 $("input[name='terceiro_s_n']").change(function(){
			
		//	alert($(this).val());
			
			if($(this).val() == '1'){
				
				$("#serv_razao").attr('disabled',false);
				$("#serv_cgc").attr('disabled',false);
				$(".serv_terceiros").removeClass("serv_terceiros_sn");
				
			}else if($(this).val() == '2'){
				
				$("#serv_razao").attr('disabled',true);
				$("#serv_cgc").attr('disabled',true);
				$(".serv_terceiros").addClass("serv_terceiros_sn");
				
			}
	 });
 });

$(document).on('click','#add_serv',function(){		
	
	  var sv_razao = $("#serv_razao").val();
	  var sv_cgc   = $("#serv_cgc").val();	
	  var idemp	   = $("#idemp").val();		
	
	if(sv_razao != "" || sv_cgc != 	""){		
		  $.ajax({
				type:'POST',
				url:"../php/servicoterceiro-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'inserir',serv_razao:sv_razao,serv_cgc:sv_cgc,idemp:idemp},
				success: function(data){
					
					var html = "";										
					
					html +='<tr id="'+data[0].codigo+'">'+
							  '<td><span class="center">'+
								'<input type="checkbox" name="id[]" class="cinputserv" value="'+data[0].codigo+'" />'+
							  '</span></td>'+
							  '<td>'+data[0].serv_razao+'</td>'+
							  '<td>'+data[0].serv_cgc+'</td>'+
							'</tr>'; 
							
							
					$("#tb_serv_terceiros tbody").append(html);		
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
  	    }else{
			alert("Falta preencher alguns campos!");
		}
		
		return false;
});

$(document).on('click','#remove_serv',function(){	
		
		var files = '';
		var array = [];
		var idemp = $("#idemp").val();	
		 
		$(".cinputserv:checked").each(function(){
			
			files = this.value;
			//ids = array.push(files);
			array.push(files);	
			
		});
		if(files != ''){
			 $.ajax({
				type:'POST',
				url:"../php/servicoterceiro-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'delete',id:array,idemp:idemp},
				success: function(data){
					
					for(var i = 0; i < data.length; i++){
						
						
						$("#tb_serv_terceiros tbody tr[id='"+data[i].codigo+"']").remove();
						
					}
					
					
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
		}
		return false;
		
		
});


$(document).on('click','#add_socio_acionista',function(){		
	
	  var nome     		= $("#socio_acionista_nome").val();
	  var cpf	   		= $("#socio_acionista_cpf").val();	
	  var partcapsocial =  $("#socio_acionista_partcapsocial").val();	
	  var idemp	   		= $("#idemp").val();		
	
	if(nome != "" || cpf != "" || partcapsocial != ""){		
		  $.ajax({
				type:'POST',
				url:"../php/sociosacionistas-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'inserir',socio_acionista_nome:nome,socio_acionista_cpf:cpf,socio_acionista_partcapsocial:partcapsocial,idemp:idemp},
				success: function(data){
					
					var html = "";										
					
					html +='<tr id="'+data[0].codigo+'">'+
							  '<td><span class="center">'+
								'<input type="checkbox" name="ids[]" class="cinputsocio" value="'+data[0].codigo+'" />'+
							  '</span></td>'+
							  '<td>'+data[0].nome+'</td>'+
							  '<td>'+data[0].cpf+'</td>'+
							  '<td>'+data[0].partcapsocial+'</td>'+
							'</tr>'; 
							
							
					$("#tb_socio_acionista tbody").append(html);		
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
  	    }else{
			alert("Falta preencher alguns campos!");
		}
		
		return false;
});

$(document).on('click','#remove_socio_acionista',function(){	
		
		var files = '';
		var array = [];
		var idemp = $("#idemp").val();	
		 
		$(".cinputsocio:checked").each(function(){
			
			files = this.value;
			//ids = array.push(files);
			array.push(files);	
			
		});
		if(files != ''){
			 $.ajax({
				type:'POST',
				url:"../php/sociosacionistas-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'delete',id:array,idemp:idemp},
				success: function(data){
					
					for(var i = 0; i < data.length; i++){
						
						
						$("#tb_socio_acionista tbody tr[id='"+data[i].codigo+"']").remove();
						
					}
					
					
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
		}
		return false;
		
		
});

$(document).on('click','#add_bensimoveis',function(){		
	
	  var desc     		= $("#desc_bensimoveis").val();
	  var endereco 		= $("#endereco_bensimoveis").val();	
	  var matricula     =  $("#matricula_bensimoveis").val();	
	  var idemp	   		= $("#idemp").val();		
	
	if(desc != "" || endereco != "" || matricula != ""){		
		  $.ajax({
				type:'POST',
				url:"../php/bensimoveis-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'inserir',descricao:desc,endereco:endereco,matricula:matricula,idemp:idemp},
				success: function(data){
					
					var html = "";										
					
					html +='<tr id="'+data[0].codigo+'">'+
							  '<td><span class="center">'+
								'<input type="checkbox" name="ids[]" class="cinputbens" value="'+data[0].codigo+'" />'+
							  '</span></td>'+
							  '<td>'+data[0].descricao+'</td>'+
							  '<td>'+data[0].endereco+'</td>'+
							  '<td>'+data[0].matricula+'</td>'+
							'</tr>'; 
							
							
					$("#tb_bensimoveis tbody").append(html);		
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
  	    }else{
			alert("Falta preencher alguns campos!");
		}
		
		return false;
});

$(document).on('click','#remove_bensimoveis',function(){	
		
		var files = '';
		var array = [];
		var idemp = $("#idemp").val();	
		 
		$(".cinputbens:checked").each(function(){
			
			files = this.value;
			//ids = array.push(files);
			array.push(files);	
			
		});
		if(files != ''){
			 $.ajax({
				type:'POST',
				url:"../php/bensimoveis-exec.php",
				cache: false,
				dataType: 'json',
				data:{act:'delete',id:array,idemp:idemp},
				success: function(data){
					
					for(var i = 0; i < data.length; i++){
						
						
						$("#tb_bensimoveis tbody tr[id='"+data[i].codigo+"']").remove();
						
					}
					
					
					
				},
				error: function(data){
					alert(data.status);
				}	
			});
		}
		return false;
		
		
});
// pergunta convenio do veterinario (Questionario QA 7)
$(document).ready(function(e) {	
	 $("input[name='veterinario_estabelecimento_s_n']").change(function(){
			
		//	alert($(this).val());
			
			if($(this).val() == '1'){				
				$("#org_municipio_veterinario_estabelecimento").attr('disabled',false);												
			}else if($(this).val() == '2'){				
				$("#org_municipio_veterinario_estabelecimento").attr('disabled',true);				
				
			}
	 });
	 
	 
	 
 });

$(document).ready(function(){
	$('select[name="sys"]').change(function(){
		var tp = $(this).val();
		$(".menutipo_3").hide();
		$(".tipo_3").hide();

		if(tp == 1){
			$(".permicoes").removeClass('hide');
			$(".menutipo_1").show();
			$(".tipo_1").show();
			$(".menutipo_2").hide();
			$(".tipo_2").hide();

		}else if(tp == 2){
			$(".permicoes").removeClass('hide');
			$(".menutipo_2").show();
			$(".tipo_2").show();
			$(".menutipo_1").hide();
			$(".tipo_1").hide();
		}else{
			$(".menutipo_1").hide();
			$(".tipo_1").hide();
			$(".menutipo_2").hide();
			$(".tipo_2").hide();
			$(".menutipo_3").hide();
			$(".tipo_3").hide();
			$(".permicoes").addClass('hide');
		}

	});
});

$(document).ready(function(){
	$(".btnavancado").click(function(){		
		$("#avancado").slideToggle();
		$("#cfavancado").slideToggle('linear');
	});

//validaCpfCnpj
	$('#login').blur(function(e){
		if($(this).val()){
			var valid = validaCpfCnpj($(this).val());
			
			if(valid == false){
				alert('Cnpj incorreto!');
				$(this).val('');
				
			}/*else{
				alert('Correto!');
				$('select[name="id_emp"] option="'+$(this).val()+'"').attr('selected',true);
			}*/
		}else{
			alert("Informar um Cnpj!");
		}		
	});
});

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

function validaCpfCnpj(val) {
    if (val.length == 14) {
        var cpf = val.trim
     
        cpf = cpf.replace(/\./g, '');
        cpf = cpf.replace('-', '');
        cpf = cpf.split('');
        
        var v1 = 0;
        var v2 = 0;
        var aux = false;
        
        for (var i = 1; cpf.length > i; i++) {
            if (cpf[i - 1] != cpf[i]) {
                aux = true;   
            }
        } 
        
        if (aux == false) {
            return false; 
        } 
        
        for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
            v1 += cpf[i] * p; 
        } 
        
        v1 = ((v1 * 10) % 11);
        
        if (v1 == 10) {
            v1 = 0; 
        }
        
        if (v1 != cpf[9]) {
            return false; 
        } 
        
        for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
            v2 += cpf[i] * p; 
        } 
        
        v2 = ((v2 * 10) % 11);
        
        if (v2 == 10) {
            v2 = 0; 
        }
        
        if (v2 != cpf[10]) {
            return false; 
        } else {   
            return true; 
        }
    } else if (val.length == 18) {
        var cnpj = val.trim();
        
        cnpj = cnpj.replace(/\./g, '');
        cnpj = cnpj.replace('-', '');
        cnpj = cnpj.replace('/', ''); 
        cnpj = cnpj.split(''); 
        
        var v1 = 0;
        var v2 = 0;
        var aux = false;
        
        for (var i = 1; cnpj.length > i; i++) { 
            if (cnpj[i - 1] != cnpj[i]) {  
                aux = true;   
            } 
        } 
        
        if (aux == false) {  
            return false; 
        }
        
        for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
            if (p1 >= 2) {  
                v1 += cnpj[i] * p1;  
            } else {  
                v1 += cnpj[i] * p2;  
            } 
        } 
        
        v1 = (v1 % 11);
        
        if (v1 < 2) { 
            v1 = 0; 
        } else { 
            v1 = (11 - v1); 
        } 
        
        if (v1 != cnpj[12]) {  
            return false; 
        } 
        
        for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) { 
            if (p1 >= 2) {  
                v2 += cnpj[i] * p1;  
            } else {   
                v2 += cnpj[i] * p2; 
            } 
        }
        
        v2 = (v2 % 11); 
        
        if (v2 < 2) {  
            v2 = 0;
        } else { 
            v2 = (11 - v2); 
        } 
        
        if (v2 != cnpj[13]) {   
            return false; 
        } else {  
            return true; 
        }
    } else {
        return false;
    }
 }