
$(document).on('submit','form[id="recoverform"]',function(){

	var param = $(this).serialize();

	$.ajax({
		type: 'POST',
		url: this.action,
		data: param,
		dataType: "json",
		// Antes de enviar
		beforeSend: function(){
						
		},
		success: function(data){
		
			if(data[0].tipo == 1){
				
				$('form[id="recoverform"]').html("<div align='center'><img src='../images/sucess.png' /><br/> "+data[0].mensagem+" <div class='form-group m-b-0'><div class='col-sm-12 text-center'><p>VOLTAR PARA O <a href='login.php' class='text-info m-l-5'><b>LOGIN</b></a></p></div></div></div></div>");	

			}else if(data[0].tipo == 2){

				alert(data[0].mensagem);
			}								
		},
		error: function(data){
			alert("Ops!, Algo não ocorreu como esperado mais informações ligar para o agregar para que possamos entender o que quer fazer!");			
		}
	});
	return false;
});


$(document).on('submit','form[id="refinaloginform"]',function(){

	var param = $(this).serialize();

	$.ajax({
		type: 'POST',
		url: this.action,
		data: param,
		dataType: "json",
		// Antes de enviar
		beforeSend: function(){
						
		},
		success: function(data){
		
			if(data[0].tipo == 1){
				
				$('form[id="refinaloginform"]').html("<div align='center'><img src='../images/sucess.png' /><br/> "+data[0].msg+" <div class='form-group m-b-0'><div class='col-sm-12 text-center'><p>VOLTAR PARA O <a href='login.php' class='text-info m-l-5'><b>LOGIN</b></a></p></div></div></div>");	

			}else if(data[0].tipo == 2){

				alert(data[0].msg);
			}								
		},
		error: function(data){
			alert("Ops!, Algo não ocorreu como esperado mais informações ligar para o agregar para que possamos entender o que quer fazer!");			
		}
	});
	return false;

});