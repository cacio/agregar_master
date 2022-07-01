
function printDiv() {
				
		$('.dt-buttons').hide();
		/*$("#relatoriofatu").print({
			globalStyles: true,
            mediaPrint: false,
            stylesheet: null,
            noPrintSelector: ".icon-print",
            iframe: true,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred()
		});*/
    
    $(".print-link").hide();
	var contents = $(".relatorio").html();
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html><head><title>DIV Contents</title>');
        frameDoc.document.write('</head><body>');
        //Append the external CSS file.
        frameDoc.document.write(' <link href="../plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">');
	    /*frameDoc.document.write('<link href="../css/bootplus-responsive.css" rel="stylesheet">');
		frameDoc.document.write('<link href="../css/jquery.dataTables.css" rel="stylesheet">');
		frameDoc.document.write('<link href="../css/dataTables.tableTools.css" rel="stylesheet">');*/
		
        //Append the DIV contents.
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    /*$("#relatoriofatu").printThis({
        debug: false,               //* show the iframe for debugging
        importCSS: true,           // * import page CSS
        importStyle: false,        // * import style tags
        printContainer: true,      // * grab outer container as well as the contents of the selector
        loadCSS: "../css/jquery.dataTables.css", // * path to additional css file - us an array [] for multiple
        pageTitle: "",           //   * add title to print page
        removeInline: false,       // * remove all inline styles from print elements
        printDelay: 333,           // * variable print delay; depending on complexity a higher value may be necessary
        header: null,              // * prefix to html
        base: false,                // * preserve the BASE tag, or accept a string for the URL
        formValues: true            //* preserve input/form values
    });*/
    
    $(".print-link").show();
    
    
		$('.dt-buttons').show();
	}

$(document).ready(function(){
	$("form[id='frmrelacaoabates']").submit(function(){
		
		var parm = $(this).serialize();
		var dlog;
		var printCounter = 0;
		$.ajax({
			url: '../php/relatoriorelacaoabates.php',			
			method: 'post',
			data:parm,
			beforeSend: function(){
				dlog = $.dialog({
					title: 'Aguarde!',
					animation: 'zoom',
					content: '<div align="center"><img src="../images/ajax_loading.gif" /></div>',
				});
			},
		}).done(function (response) {
			//alert($("select[name='caminhao'] option:selected").text());

			var empresa = $("#psqempresa").val()!= "" ? "Empresa :"+$("#psqempresa").val() : "";

			var info = "<h5>"+$('#empresa').val()+"</h5><h5><strong>Relação de Abates de "+$("#dataini").val()+" a "+$("#datafin").val()+" "+empresa+"</strong></h5>";	
				
			$(".relatorio").html('<div class="info" align="center">'+info+'</div>'+response+'');
			 
			$('.table').dataTable({
				 "bSort" : false,
				 "paging":   false,
				 "ordering": true,
				 "info":     false,
				 "bDestroy": true,
				 "bFilter": false,
				"language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
		        },								
				"dom": 'Bfrtip',
				"buttons": [					
					 {
						extend: 'pdfHtml5',
						title: 'Abates',
						orientation: 'portrait',
						pageSize: 'A4',
						messageTop: ''+$('#empresa').val()+'\n Relação de Abates de '+$("#dataini").val()+' a '+$("#datafin").val()+' '+empresa+' ',
						  customize : function(doc) {
                			doc.content[2].table.widths = [ '20%', '25%', '12%', '18%', '28%'];
							  //doc.content[2].table.body[0][2].style = 'text-align:right;';
							console.log(doc.content);
            			}
					},
					{
						extend: 'excel',
						messageTop: ''+$('#empresa').val()+'\n Relação de Abates de '+$("#dataini").val()+' a '+$("#datafin").val()+' '+empresa+' ',
						title: 'Relação de abates',
					},
					{
						extend: 'print',
						title: 'Abates',
						messageTop: function () {
							printCounter++;

							if ( printCounter === 1 ) {
								return ''+info+'';
							}
							else {
								return ''+info+'';
							}
						},
						messageBottom: null
					}
				],						
			   "order": []
			});	
	
			
			$(".xrel").show();
			$(".xrel").removeClass('hide');
			dlog.close();
			
		}).fail(function(){

		});
				
		return false;
	});

});


$(document).ready(function(){
	$("form[id='frmrelacaobeneficiosarrecadacao']").submit(function(){
		
		var parm = $(this).serialize();
		var dlog;
		var printCounter = 0;
		$.ajax({
			url: '../php/relatoriorelacaobeneficioarecadacao.php',			
			method: 'post',
			data:parm,
			beforeSend: function(){
				dlog = $.dialog({
					title: 'Aguarde!',
					animation: 'zoom',
					content: '<div align="center"><img src="../images/ajax_loading.gif" /></div>',
				});
			},
		}).done(function (response) {
			//alert($("select[name='caminhao'] option:selected").text());

			var empresa = $("#psqempresa").val()!= "" ? "Empresa :"+$("#psqempresa").val() : "";

			var info = "<h5>"+$('#empresa').val()+"</h5><h5><strong>Relação de Benefícios e Arrecadação de "+$("#dataini").val()+" a "+$("#datafin").val()+" "+empresa+"</strong></h5>";	
				
			$(".relatorio").html('<div class="info" align="center">'+info+'</div>'+response+'');
			 
			$('.table').dataTable({
				 "bSort" : false,
				 "paging":   false,
				 "ordering": true,
				 "info":     false,
				 "bDestroy": true,
				 "bFilter": false,
				"language": {
		            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Portuguese-Brasil.json"
		        },								
				"dom": 'Bfrtip',
				"buttons": [					
					 {
						extend: 'pdfHtml5',
						title: 'Relação de Benefícios e Arrecadação',
						orientation: 'portrait',
						pageSize: 'A4',
						messageTop: ''+$('#empresa').val()+'\n Relação de Benefícios e Arrecadação de '+$("#dataini").val()+' a '+$("#datafin").val()+' '+empresa+' ',
						  customize : function(doc) {
                			doc.content[2].table.widths = [ '20%', '25%', '12%', '18%', '28%'];
							  //doc.content[2].table.body[0][2].style = 'text-align:right;';
							console.log(doc.content);
            			}
					},
					{
						extend: 'excel',
						messageTop: ''+$('#empresa').val()+'\n Relação de Benefícios e Arrecadação de '+$("#dataini").val()+' a '+$("#datafin").val()+' '+empresa+' ',
						title: 'Relação de Benefícios e Arrecadação',
					},
					{
						extend: 'print',
						title: 'Abates',
						messageTop: function () {
							printCounter++;

							if ( printCounter === 1 ) {
								return ''+info+'';
							}
							else {
								return ''+info+'';
							}
						},
						messageBottom: null
					}
				],						
			   "order": []
			});	
	
			
			$(".xrel").show();
			$(".xrel").removeClass('hide');
			dlog.close();
			
		}).fail(function(){

		});
				
		return false;
	});

});

$(document).ready(function(e) {
	$formsemi = $('form[id="frmnotasdeentradas"]');
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
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriolistagemnotasdeentrada.php',
			 data: params,	
			 success: function(data){
							 
			String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 

			 var info = "<h4><strong> Listagem de notas de entradas de animais de "+$("#dataini").val()+" a "+$("#datafin").val()+" </strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_entradas_de_animais",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_entradas_de_animais",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_entradas_de_animais", footer: true },
						{ 
							extend: 'pdfHtml5', 
							messageTop: '',
							title:''+info.stripHTML()+'', 
							filename:"Listagem_de_notas_de_entradas_de_animais",
							footer: true,		
							orientation: 'landscape',
                			pageSize: 'LEGAL',							
						}
					],						
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});


$(document).ready(function(e) {
	$formsemi = $('form[id="frmnotasdesaida"]');
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
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatorionotasdesaida.php',
			 data: params,	
			 success: function(data){
							 
			 String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 
			 var info = "<h4><strong> Listagem de notas de saida de "+$("#dataini").val()+" a "+$("#datafin").val()+" </strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",footer: true,						
						}
					],						
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});


$(document).ready(function(e) {
	$formsemi = $('form[id="frmnotasdesaidasimplificada"]');
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
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatorionotasdesaidassimplificada.php',
			 data: params,	
			 success: function(data){
							 
			 String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 
			 var info = "<h4><strong> Listagem de notas de saída de "+$("#dataini").val()+" a "+$("#datafin").val()+" </strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_de_saida",footer: true,						
						}
					],					
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});

$(document).ready(function(e) {
	$formsemi = $('form[id="frmdocumentoforaapuracaoentrada"]');
 $formsemi.validate({
 
	 rules: {
		comp:{           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },		
		 },
	 messages:{
		comp: {
				   required:"Informe uma competência.",                      
				   },	  
							   
		 },
	 submitHandler: function(form) {
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriodocumentoforaapuracaoentrada.php',
			 data: params,	
			 success: function(data){
							 
			String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}				 
			 var info = "<h4><strong> Documentos fora da apuração da competência "+$("#comp").val()+"</strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Documentos_fora_da_apuração_da_competência",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Documentos_fora_da_apuração_da_competência",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Documentos_fora_da_apuração_da_competência", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Documentos_fora_da_apuração_da_competência",footer: true,						
						}
					],					
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});

$(document).ready(function(e) {
	$formsemi = $('form[id="frmnotasdesaidaporroduto"]');
 $formsemi.validate({
 
	 rules: {
		comp:{           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },		
		 },
	 messages:{
		comp: {
				   required:"Informe uma competência.",                      
				   },	  
							   
		 },
	 submitHandler: function(form) {
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriorelacaodesaidaporproduto.php',
			 data: params,	
			 success: function(data){
							 
			 String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 
			 var info = "<h4><strong>Relação De Saídas Por Produto da competência "+$("#comp").val()+"</strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Relação_De_Saidas_Por_Produto_da_competência",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Relação_De_Saidas_Por_Produto_da_competência",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Relação_De_Saidas_Por_Produto_da_competência", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Relação_De_Saidas_Por_Produto_da_competência",footer: true,						
						}
					],						
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});

$(document).ready(function(e) {
	$formsemi = $('form[id="frmtotabatepordata"]');
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
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriototaldeabatepordata.php',
			 data: params,	
			 success: function(data){
							 
			String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 

			 var info = "<h4><strong> Total de abate de "+$("#dataini").val()+" a "+$("#datafin").val()+" </strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Total_de_abate",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Total_de_abate",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Total_de_abate", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Total_de_abate",footer: true,						
						}
					],						
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});


$(document).ready(function(e) {
	$formsemi = $('form[id="frmnotasdeentradasporcfop"]');
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
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriolistagemporcfop.php',
			 data: params,	
			 success: function(data){
							 
			String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}			 

			 var info = "<h4><strong> Listagem de notas por cfop de "+$("#dataini").val()+" a "+$("#datafin").val()+" </strong></h4>";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_por_cfop",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_por_cfop",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_por_cfop", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Listagem_de_notas_por_cfop",footer: true,						
						}
					],						
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});


$(document).ready(function(e) {
	$formsemi = $('form[id="frmfechamentoporcfop"]');
 $formsemi.validate({
 
	 rules: {
		comp:{           //input name: fullName
				 required: true,   //required boolean: true/false                 
			 },		
		 },
	 messages:{
		comp: {
				   required:"Informe uma competência.",                      
				   },	  
							   
		 },
	 submitHandler: function(form) {
		 
	 dlog = $.dialog({		
			title: '<img src="../images/icon.ico" style="width:25px;"/> mensagem do sistema',
			content: '<h2><img src="../images/ajax_loading.gif" style="width:58px;" /> Aguarde carregando...</h2>',
		});	
	   
		var $form = $(form);
		var params = $form.serialize();	
		 
		 $.ajax({
			 type: 'POST',			
			 url: '../php/relatoriofechamentoporcfop.php',
			 data: params,	
			 success: function(data){
							 
			String.prototype.stripHTML = function() {return this.replace(/<.*?>/g, '');}				 
			 var info = "<h4><strong> Fechamento por CFOP (ENTRADAS e SAÍDAS)</strong></h4>";
			 	 info += ""+$("#comp").val()+"";
			 
														 
														 
			 $(".relatorio").html('<div class="info" align="center">'+info+'</div>'+data+'');
				 //$(".info").html(info);
				 $(".xrel").show();
				 $(".xrel").removeClass('hide');
				 dlog.close();
				 
									 
				 $('.table').dataTable({
					 "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
					 "iDisplayLength": -1,
					 "dom": 'Bfrtip',
					 "buttons": [
						{ 
							extend: 'copyHtml5', 
							messageTop: '',title:''+info.stripHTML()+'', filename:"Fechamento_por_cfop",
							footer: true,							
						},
						{ 
						 extend: 'excelHtml5',
						 messageTop: '',title:''+info.stripHTML()+'', filename:"Fechamento_por_cfop",
						 footer: true,						  
						},
						{ extend: 'csvHtml5',messageTop: '',title:''+info.stripHTML()+'', filename:"Fechamento_por_cfop", footer: true },
						{ 
							extend: 'pdfHtml5', messageTop: '',title:''+info.stripHTML()+'', filename:"Fechamento_por_cfop",footer: true,						
						}
					],					
					"order": [],
					"paging":   false,
					"bFilter": false,
					"info":     false, 													
				 });		
				 			 
			 },
			 error: function(data){
				 alert(data);	
			 }
		 })
		 return false;
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
 });	 				
});