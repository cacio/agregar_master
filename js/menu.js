/* menu.js */

/* 1   */ /**
/* 2   *|  * @author Bruno
/* 3   *|  */
/* 4   */ //removendo possiveis conflitos entre mootools e jquery
/* 5   */ 
/* 6   */ 
/* 7   */ (function($){
/* 8   */ 	
/* 9   */ 	/**
/* 10  *| 	 * TOOLTIPS no Cabeçalho
/* 11  *| 	 ********************************/
/* 12  */ 	function menuToolTips(){
/* 13  */ 		if ($.fn.bt) {
/* 14  */ 			jQuery.bt.defaults.closeWhenOthersOpen = true;
/* 15  */ 			
/* 16  */ 			//Tooltips da Barra de Cabecalho
/* 17  */ 			$("#topNav .tooltip[title]").mouseover(function(evt){
/* 18  */ 				$this = $(evt.target);
/* 19  */ 				if(!$this.data("bt")){
/* 20  */ 					$this.data("bt", "true");
/* 21  */ 					$this.bt({trigger: ['mouseover', 'mouseout'], positions: ['bottom','left', 'right'], padding: 6, spikeLength: 10, spikeGirth: 10, centerPointY: 1.5, cornerRadius: 1, width: 100, shadow: true, shadowBlur: 0.8, shadowOffsetX: 1, shadowOffsetY: 1, shadowColor: "#AAA", fill: '#FFFFFF', strokeStyle: "#CCC", cssStyles: {color: "#666", 'font-size':'12px', 'text-align':'center' }});
/* 22  */ 					$this.btOn();
/* 23  */ 				}
/* 24  */ 			});
/* 25  */ 		}
/* 26  */ 	}
/* 27  */ 	
/* 28  */ 	/**
/* 29  *| 	 * Menu no topo 
/* 30  *| 	 ****************************/
/* 31  */ 	function menuTopo(){
/* 32  */ 		var trigger = $("#top_menu_trigger");
/* 33  */ 		var menu 	= $("#top_menu_modal");
/* 34  */ 		
/* 35  */ 		// Eventos
/* 36  */ 		var toggle = function(stats){
/* 37  */ 			//esconde outros menus
/* 38  */ 			$(".top_menu").hide();
/* 39  */ 			
/* 40  */ 			menu.toggle(stats);
/* 41  */ 			trigger.toggleClass('ativo', stats);
/* 42  */ 			
/* 43  */ 			//Organiza os elementos internos, apenas 1 vez
/* 44  */ 			if(stats && $.fn.masonry && !menu.find("ul").data("masonry")){
/* 45  */ 				menu.find("ul").masonry({
/* 46  */ 					itemSelector: 'li.categoria'
/* 47  */ 				});
/* 48  */ 			}
/* 49  */ 		};
/* 50  */ 		var show = function(evt){ toggle(true); };

/* menu.js */

/* 51  */ 		var hide = function(evt){ toggle(false); };
/* 52  */ 		
/* 53  */ 		//Show
/* 54  */ 		trigger.mouseover(show);
/* 55  */ 		menu.mouseover(show);
/* 56  */ 		
/* 57  */ 		//Hide
/* 58  */ 		trigger.mouseout(hide);
/* 59  */ 		menu.mouseout(hide);
/* 60  */ 		menu.find("a").click(hide);
/* 61  */ 		
/* 62  */ 		//Click Toggle
/* 63  */ 		trigger.click(show);
/* 64  */ 		
/* 65  */ 	}
/* 66  */ 	
/* 67  */ 	/**
/* 68  *| 	 * Menu Suporte 
/* 69  *| 	 ****************************/
/* 70  */ 	function menuSuporte(){
/* 71  */ 		var trigger = $("#top_menu_suporte_trigger");
/* 72  */ 		var menu 	= $("#top_menu_suporte");
/* 73  */ 		
/* 74  */ 		trigger.attr("title", trigger.attr("title").replace(/_/g, "").replace(/[\\\n]/g, "<br/>"));
/* 75  */ 		trigger.bt({positions: ['bottom'], padding: 6, spikeLength: 10, spikeGirth: 10, centerPointY: 1.5, cornerRadius: 1, width: 250, shadow: true, shadowBlur: 0.8, shadowOffsetX: 1, shadowOffsetY: 1, shadowColor: "#AAA", fill: '#FFFFFF', strokeStyle: "#CCC", cssStyles: {color: "#666", 'font-size':'12px', 'text-align':'center' }});
/* 76  */ 		trigger.btOn();
/* 77  */ 		
/* 78  */ 		//Evts
/* 79  */ 		var toggle = function(stats){
/* 80  */ 			//esconde outros menus
/* 81  */ 			$(".top_menu").hide();
/* 82  */ 			trigger.btOff();
/* 83  */ 			
/* 84  */ 			trigger.toggleClass('ativo', stats);
/* 85  */ 			menu.toggle(stats);
/* 86  */ 		};
/* 87  */ 		var show = function(evt){ toggle(true); };
/* 88  */ 		var hide = function(evt){ toggle(false); };
/* 89  */ 		
/* 90  */ 		//Show
/* 91  */ 		trigger.mouseover(show);
/* 92  */ 		trigger.click(show);
/* 93  */ 		menu.mouseover(show);
/* 94  */ 		
/* 95  */ 		//Hide
/* 96  */ 		trigger.mouseout(hide);
/* 97  */ 		menu.mouseout(hide);
/* 98  */ 		menu.find("a").click(hide);
/* 99  */ 		
/* 100 */ 		//Sumir com tooltip

/* menu.js */

/* 101 */ 		$(document).click(function(){ trigger.btOff(); });
/* 102 */ 		$("#menu *").click(function(){ trigger.btOff(); });
/* 103 */ 
/* 104 */ 	}
/* 105 */ 	
/* 106 */ 	
/* 107 */ 	/** 
/* 108 *| 	 * Funcoes para o Menu modal 
/* 109 *| 	 * ***************************/
/* 110 */ 	function menuModal(){
/* 111 */ 		var bg 			= $("#menu_modal_bg"); 
/* 112 */ 		var container 	= $("#menu_modal_wrapper"); 
/* 113 */ 		var trigger		= $("#menu_modal_trigger");
/* 114 */ 		var scroll		= $("#menu_modal_scroll");
/* 115 */ 		var seta		= $("#menu_modal_triangle");
/* 116 */ 		var filtro 		= $("#filtro input"); 
/* 117 */ 		
/* 118 */ 		//Metodo para exibir
/* 119 */ 		var showModal = function(){
/* 120 */ 			if(!container.data("visivel")){
/* 121 */ 				filtro.trigger("reset");
/* 122 */ 				container.animate({width:"show"}, 300, function(){
/* 123 */ 					container.data("visivel", true);
/* 124 */ 					bg.show(); 
/* 125 */ 					seta.show();
/* 126 */ 					filtro.focus();
/* 127 */ 					menu_update_selected(true);
/* 128 */ 				});
/* 129 */ 			}
/* 130 */ 		};
/* 131 */ 		
/* 132 */ 		//Metodo para ocultar 
/* 133 */ 		var hideModal = function(){
/* 134 */ 			container.hide();
/* 135 */ 			container.data("visivel", false);
/* 136 */ 			bg.hide();
/* 137 */ 			seta.hide();
/* 138 */ 			$("#menu_wrapper").css("z-index", "15").data("overall", false);
/* 139 */ 			$("#menu_wrapper_closer").css("z-index", "14");
/* 140 */ 		};
/* 141 */ 		
/* 142 */ 		//Esconde
/* 143 */ 		$("#menu_wrapper, #menu *, #menu_modal_bg").click(function(evt){
/* 144 */ 			if(!$(evt.target).is("#menu_modal_trigger *")){
/* 145 */ 				hideModal();
/* 146 */ 			}
/* 147 */ 		});
/* 148 */ 		$("#menu_modal a[target]").click(hideModal);
/* 149 */ 		
/* 150 */ 		//Exibe

/* menu.js */

/* 151 */ 		trigger.click(showModal);
/* 152 */ 		
/* 153 */ 		//gerando evento para abrir o menu
/* 154 */ 		//jQuery
/* 155 */ 		$("#menu").bind("show", showModal);
/* 156 */ 		$("#menu").bind("hide", hideModal);
/* 157 */ 		//Mootools
/* 158 $$("#menu").addEvent("show", showModal);*/ 		
/* 159  		$$("#menu").addEvent("hide", hideModal);*/
/* 160 */ 	}
/* 161 */ 	
/* 162 */ 	/** 
/* 163 *| 	 * Expansão e contração do Menu 
/* 164 *| 	 * ********************************/
/* 165 */ 	function expandeMenu(){
/* 166 */ 		//VARIAVEIS ================
/* 167 */ 		var menu 				= $("#menu_wrapper");
/* 168 */ 		var menu_links 			= $("#menu_wrapper *");
/* 169 */ 		var modal_trigger 		= $("#menu_modal_trigger");
/* 170 */ 		var closer 				= $("#menu_wrapper_closer");
/* 171 */ 		var closer_els			= $("iframe, .mochaOverlay, #menu_wrapper_closer, #menu_modal *");
/* 172 */ 		var timeout 			= null;
/* 173 */ 		
/* 174 */ 		//MÉTODOS ================
/* 175 */ 		
/* 176 */ 		//reduz a camada do menu apenas se não foi fechado pelo trigger do modal
/* 177 */ 		function layerDown(){
/* 178 */ 			if(!menu.data("overall")){ 
/* 179 */ 				menu.css("z-index", '15');
/* 180 */ 				closer.css("z-index", '14');
/* 181 */ 			}
/* 182 */ 		}
/* 183 */ 		
/* 184 */ 		//aumenta a camada do menu para ser exibido sobre janelas
/* 185 */ 		function layerUp(){
/* 186 */ 			closer.css("z-index", '10004');
/* 187 */ 			menu.css("z-index", "10005");
/* 188 */ 		}
/* 189 */ 		
/* 190 */ 		
/* 191 */ 		//expande o menu
/* 192 */ 		var _expand = function(evt){
/* 193 */ 			var m = $("#menu_wrapper"); 
/* 194 */ 			var abrindo = m.is(":animated");
/* 195 */ 			if(!m.data("expandido") && !abrindo && !$("#menu_modal").is(":visible")){
/* 196 */ 				layerUp();
/* 197 */ 				menu.animate({width:"255px"}, 100, function(){
/* 198 */ 					if(!m.data("expandido")){
/* 199 */ 						closer.show();
/* 200 */ 						menu.addClass('expandido');

/* menu.js */

/* 201 */ 						menu.data("expandido", true);
/* 202 */ 					}
/* 203 */ 				});
/* 204 */ 			}
/* 205 */ 		};
/* 206 */ 		
/* 207 */ 		
/* 208 */ 		//Recolhe Menu
/* 209 */ 		var _collapse = function(evt){
/* 210 */ 			
/* 211 */ 			//fecha div de controle
/* 212 */ 			closer.hide();
/* 213 */ 			
/* 214 */ 			//fecha apenas se estiver expandido
/* 215 */ 			if(!menu.data("expandido")) return;

/* 216 */ 			
/* 217 */ 			//retirando a classe
/* 218 */ 			menu.removeClass('expandido');
/* 219 */ 			menu.animate({width:"60px"}, 100, function(){
/* 220 */ 				if(menu.data("expandido", true)){
/* 221 */ 					closer.hide();
/* 222 */ 					menu.data("expandido", false);
/* 223 */ 				}
/* 224 */ 			});
/* 225 */ 			
/* 226 */ 			//reduz a camada do menu apenas se não foi fechado pelo trigger do modal
/* 227 */ 			layerDown();
/* 228 */ 		};
/* 229 */ 		
/* 230 */ 		
/* 231 */ 		
/* 232 */ 		//EVENTOS ================
/* 233 */ 		//Exibindo menu completo com mouse over
/* 234 */ 		menu.mouseover(function(evt){
/* 235 */ 			if(!timeout){
/* 236 */ 				layerUp();
/* 237 */ 				closer.show();
/* 238 */ 				timeout = setTimeout(_expand, 300);
/* 239 */ 			}
/* 240 */ 		});
/* 241 */ 		
/* 242 */ 		//Exibindo menu completo com clique (dispositivos moveis)
/* 243 */ 		menu.click(function(evt){
/* 244 */ 			clearTimeout(timeout);
/* 245 */ 			layerUp();
/* 246 */ 			_expand();
/* 247 */ 		});
/* 248 */ 		
/* 249 */ 		//interrompendo abertura do menu quando algum link for clicado
/* 250 */ 		menu_links.click(function(evt){

/* menu.js */

/* 251 */ 			clearTimeout(timeout);
/* 252 */ 			timeout = null;
/* 253 */ 			$("#menu_modal a.selected").removeClass("selected"); //retira item selecionado no menu
/* 254 */ 			_collapse();
/* 255 */ 		});
/* 256 */ 		
/* 257 */ 		//recolhe o menu se o alvo for algum elemento fora do menu_wrapper
/* 258 */ 		closer_els.mouseover(function(){
/* 259 */ 			clearTimeout(timeout);
/* 260 */ 			timeout = null;
/* 261 */ 			layerDown();
/* 262 */ 			_collapse();
/* 263 */ 		});
/* 264 */ 		
/* 265 */ 		//trigger do modal
/* 266 */ 		modal_trigger.click(function(evt){
/* 267 */ 			menu.data("overall", true); //forçando o menu a ficar por cima de qualquer janela
/* 268 */ 			clearInterval(timeout);
/* 269 */ 			timeout = null;
/* 270 */ 			_collapse(evt);
/* 271 */ 		});
/* 272 */ 	}
/* 273 */ 	
/* 274 */ 	
/* 275 */ 	/** 
/* 276 *| 	 * Navegação por teclado no menu
/* 277 *| 	 * ***************************/
/* 278 */ 	var menu_link_itens = $("#menu_modal a.item:visible");
/* 279 */ 	var selected_index	= 0;
/* 280 */ 	
/* 281 */ 	
/* 282 */ 	/* Realiza o Scroll para mostrar o elemento */
/* 283 */ 	function menu_update_scroll(ontop){
/* 284 */ 		ontop 				= (ontop===true);
/* 285 */ 		var el				= menu_link_itens.eq(selected_index);
/* 286 */ 		var position 		= el.position().top;
/* 287 */ 		var elHeight		= el.height();
/* 288 */ 		
/* 289 */ 		var scroll			= $("#menu_modal_scroll");
/* 290 */ 		var divHeight 		= scroll.height();
/* 291 */ 		var divScroll 		= scroll.scrollTop();
/* 292 */ 		
/* 293 */ 		//coloca item no topo caso ontop seja true
/* 294 */ 		if(ontop){
/* 295 */ 			scroll.scrollTop(Math.floor(position));
/* 296 */ 			return;
/* 297 */ 		}
/* 298 */ 		
/* 299 */ 		//se a posicao do elemento for maior que a altura do Div, aumenta o scroll em posicao + altura do elemento - altura da div
/* 300 */ 		if(Math.ceil(position + elHeight) > Math.floor(divScroll + divHeight)){

/* menu.js */

/* 301 */ 			scroll.scrollTop(Math.ceil(position + elHeight - divHeight));
/* 302 */ 		}
/* 303 */ 		//se a posicao for menor, coloca o elemento no topo
/* 304 */ 		else if(Math.floor(position) < Math.ceil(divScroll)){
/* 305 */ 			scroll.scrollTop(Math.floor(position));
/* 306 */ 		}
/* 307 */ 	}
/* 308 */ 	
/* 309 */ 	
/* 310 */ 	/* Atualiza o elemento selecionado para o primeiro da lista */
/* 311 */ 	function menu_update_selected(keep_last){
/* 312 */ 		//verificando parametro
/* 313 */ 		keep_last = (keep_last===true);
/* 314 */ 		
/* 315 */ 		//atualizando lista de elementos
/* 316 */ 		menu_link_itens 	= $("#menu_modal a.item:visible");
/* 317 */ 		selected_index 		= menu_find_current_selected();
/* 318 */ 		
/* 319 */ 		if(!keep_last || selected_index==0){ //mantém ultima selecao
/* 320 */ 			$("#menu_modal a.selected").removeClass("selected");
/* 321 */ 			$("#menu_modal a.item:visible:first").addClass("selected");
/* 322 */ 			$("#menu_modal_scroll").scrollTop(0);
/* 323 */ 		}else{
/* 324 */ 			//Da um scroll com gap de 150px pra cima para ficar mais visivel quando menu for aberto sem filtro com a ultima opcao selecionada
/* 325 */ 			menu_update_scroll(true); 
/* 326 */ 		}
/* 327 */ 	}
/* 328 */ 	
/* 329 */ 	
/* 330 */ 	/* Encontra o indice do item selecionado atualmente*/
/* 331 */ 	function menu_find_current_selected(){
/* 332 */ 		for(i=0; i<menu_link_itens.length; i++){
/* 333 */ 			if(menu_link_itens.eq(i).is(".selected")){
/* 334 */ 				return i;
/* 335 */ 			}
/* 336 */ 		}
/* 337 */ 		return 0;
/* 338 */ 	}
/* 339 */ 	
/* 340 */ 	
/* 341 */ 	/* Seleciona Item clicado */
/* 342 */ 	function menu_select_item(item){
/* 343 */ 		$("#menu_modal a.selected").removeClass("selected");
/* 344 */ 		item.addClass("selected");
/* 345 */ 	}
/* 346 */ 	
/* 347 */ 	
/* 348 */ 	/* Trata eventos de teclado */
/* 349 */ 	function menu_key_action(evt){
/* 350 */ 		

/* menu.js */

/* 351 */ 		//encontra o elemento atualmente selecionado
/* 352 */ 		selected_index = menu_find_current_selected();
/* 353 */ 		
/* 354 */ 		var key 			= evt.which;
/* 355 */ 		var current			= menu_link_itens.eq(selected_index);
/* 356 */ 		var all_selected	= $("#menu_modal a.selected");
/* 357 */ 		
/* 358 */ 		switch (key) {
/* 359 */ 		
/* 360 */ 		case 38:	//SETA CIMA
/* 361 */ 			evt.preventDefault();
/* 362 */ 			evt.stopPropagation();
/* 363 */ 			
/* 364 */ 			//seleciona o item anterior se existir na lista
/* 365 */ 			if(selected_index > 0){
/* 366 */ 				current.removeClass("selected"); 									//deseleciona todos os selected
/* 367 */ 				menu_link_itens.eq(selected_index-1).addClass("selected");			//seleciona o anterior
/* 368 */ 				selected_index--;
/* 369 */ 				
/* 370 */ 				//rola a tela caso o item esteja fora dela
/* 371 */ 				menu_update_scroll();
/* 372 */ 				
/* 373 */ 			}
/* 374 */ 			break;
/* 375 */ 			
/* 376 */ 		case 40:	//SETA BAIXO
/* 377 */ 			evt.preventDefault();
/* 378 */ 			evt.stopPropagation();
/* 379 */ 			
/* 380 */ 			//seleciona o proximo item se existir na lista
/* 381 */ 			if(selected_index < menu_link_itens.length-1){
/* 382 */ 				current.removeClass("selected");									//deseleciona todos os selected
/* 383 */ 				menu_link_itens.eq(selected_index+1).addClass("selected");			//seleciona o proximo item
/* 384 */ 				selected_index++;
/* 385 */ 				
/* 386 */ 				//rola a tela caso o item esteja fora dela
/* 387 */ 				menu_update_scroll();
/* 388 */ 			}
/* 389 */ 			break;
/* 390 */ 		
/* 391 */ 		case 9:		//TAB 
/* 392 */ 			evt.preventDefault();
/* 393 */ 			evt.stopPropagation();
/* 394 */ 			break;
/* 395 */ 			
/* 396 */ 		case 13: 	//ENTER
/* 397 */ 			evt.preventDefault();
/* 398 */ 			evt.stopPropagation();
/* 399 */ 			//se há algum item selecionado, clica nele
/* 400 */ 			if(current.length == 1){

/* menu.js */

/* 401 */ 				//mootools
/* 402 */ 				$$(current.get())[0].fireEvent("click", new Event(evt));
/* 403 */ 				//jquery
/* 404 */ 				current.click();
/* 405 */ 			}
/* 406 */ 			break;
/* 407 */ 			
/* 408 */ 		case 27: 	//ESC
/* 409 */ 			evt.preventDefault();
/* 410 */ 			evt.stopPropagation();
/* 411 */ 			$("#menu").trigger("hide");
/* 412 */ 			break;
/* 413 */ 		
/* 414 */ 		//Retorna dizendo que nao houve alterações na seleção
/* 415 */ 		default:
/* 416 */ 			if(key==8 || key==46 || (key >= 48 && key <= 90) || (key >= 96 && key <= 111) || (key >= 186 && key <= 222)){
/* 417 */ 				return false;
/* 418 */ 			} 
/* 419 */ 			break;
/* 420 */ 		}
/* 421 */ 		
/* 422 */ 		//houve alguma alteração na seleção
/* 423 */ 		return true;
/* 424 */ 	}
/* 425 */ 	
/* 426 */ 	
/* 427 */ 	/** 
/* 428 *| 	 * Pesquisa no menu 
/* 429 *| 	 * ***************************/
/* 430 */ 	function menu_filtro(){
/* 431 */ 		var lista		= $("#menu_modal li.item");
/* 432 */ 		var lista_nomes	= $("#menu_modal li.item .label");
/* 433 */ 		var lista_cats	= $("#menu_modal li.categoria a.returnFalse");
/* 434 */ 		var num_cats 	= lista_cats.length;
/* 435 */ 		var filtro 		= $("#filtro input");
/* 436 */ 		var placeholder	= filtro.attr('placeholder');
/* 437 */ 		var bg 			= $("#menu_wrapper_bg"); 
/* 438 */ 		
/* 439 */ 		//Tratando Teclas
/* 440 */ 		filtro.keydown(function(evt){
/* 441 */ 			//executa rotina de navegação e tratamento de teclas
/* 442 */ 			filtro.data('sel_changed', menu_key_action(evt));
/* 443 */ 			
/* 444 */ 			//se classe vazia, limpa o campo
/* 445 */ 			if(!filtro.data('sel_changed') && filtro.is(".vazio")){
/* 446 */ 				filtro.removeClass("vazio");
/* 447 */ 				filtro.val("");
/* 448 */ 			}
/* 449 */ 		});
/* 450 */ 		

/* menu.js */

/* 451 */ 		//executando filtro ao teclar
/* 452 */ 		filtro.keyup(function(evt){
/* 453 */ 			var $this 	= $(this);
/* 454 */ 			var val 	= $this.val().toLowerCase();
/* 455 */ 			
/* 456 */ 			//verifica se foi digitado algo
/* 457 */ 			if(filtro.is(".vazio")) return;
/* 458 */ 			
/* 459 */ 			//itens
/* 460 */ 			lista.each(function(i, el){
/* 461 */ 				var $el 	= $(el); 
/* 462 */ 				var nome 	= lista_nomes.eq(i).text().toLowerCase();
/* 463 */ 				var keys 	= $el.attr("meta").toLowerCase();
/* 464 */ 				var regexp	= new RegExp("(?="+val+")", "g");
/* 465 */ 				
/* 466 */ 				//Se está fora da seleção, esconde
/* 467 */ 				if(val!="" && !regexp.test(nome) && !regexp.test(keys)){
/* 468 */ 					$el.hide();
/* 469 */ 				}else{
/* 470 */ 					$el.show();
/* 471 */ 				}
/* 472 */ 			});
/* 473 */ 			
/* 474 */ 			//categorias
/* 475 */ 			lista_cats.each(function(i, el){
/* 476 */ 				var $el = lista_cats.eq(num_cats-i-1);
/* 477 */ 				
/* 478 */ 				if($el.parent().find("ul:first > li > a.item:visible").length == 0){
/* 479 */ 					$el.hide();
/* 480 */ 				}else{
/* 481 */ 					$el.show();
/* 482 */ 				}
/* 483 */ 			});
/* 484 */ 			
/* 485 */ 			//atualiza o selecionado, caso nao tenha havido apenas uma troca de seleção
/* 486 */ 			if(!filtro.data('sel_changed')){
/* 487 */ 				menu_update_selected();
/* 488 */ 			}
/* 489 */ 			
/* 490 */ 			//colocando classe inativa
/* 491 */ 			if(filtro.val()==""){
/* 492 */ 				filtro.addClass("vazio");
/* 493 */ 				filtro.val(placeholder);
/* 494 */ 			}
/* 495 */ 		});
/* 496 */ 		
/* 497 */ 		//reseta o filtro
/* 498 */ 		filtro.bind("reset", function(){
/* 499 */ 			lista.show();
/* 500 */ 			lista_cats.show();

/* menu.js */

/* 501 */ 			filtro.val(filtro.attr('placeholder'));
/* 502 */ 			filtro.addClass("vazio");
/* 503 */ 			//$("#menu_modal a.selected").removeClass("selected");
/* 504 */ 		});
/* 505 */ 		
/* 506 */ 		//retirando classe inativa
/* 507 */ 		filtro.blur(function(){
/* 508 */ 			if(filtro.val()==""){
/* 509 */ 				filtro.addClass("vazio");
/* 510 */ 				filtro.val(placeholder);
/* 511 */ 			}
/* 512 */ 		});
/* 513 */ 		
/* 514 */ 		//inserindo evento de clique para atualizar elemento selecionado
/* 515 */ 		$("#menu_modal a.item").click(function(evt){
/* 516 */ 			menu_select_item($(this));
/* 517 */ 		});
/* 518 */ 	}
/* 519 */ 
/* 520 */ 	
/* 521 */ 	/** 
/* 522 *| 	 * Inicio
/* 523 *| 	 * ***************************/
/* 524 */ 	$(document).ready(function(){
/* 525 */ 		//modais
/* 526 */ 		menuModal();
/* 527 */ 		
/* 528 */ 		//menu topo
/* 529 */ 		menuTopo();
/* 530 */ 		
/* 531 */ 		//menu topo suporte
/* 532 		menuSuporte();*/ 
/* 533 */ 		
/* 534 */ 		//expandindo o menu com os nomes
/* 535 */ 		expandeMenu();
/* 536 */ 		
/* 537 */ 		//Filtragem do menu
/* 538 */ 		menu_filtro();
/* 539 */ 		
/* 540 */ 		//tooltips
/* 541 */ 		menuToolTips();
/* 542 */ 	});
/* 543 */ 	
/* 544 */ 	
/* 545 */ 	
/* 546 */ })(jQuery);