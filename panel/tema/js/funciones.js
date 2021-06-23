//Auxiliar para iterar sobre resultado querySelectorAll
function selectorMultiple(selector, callback){
	var elementos = document.querySelectorAll(selector);
	Object.keys(elementos).map(function(k){
		callback(k, elementos);
	});
}
//Manejadores de eventos que itera sobre resultado querySelectorAll
function addEvent(selector, evento, callback){
	selectorMultiple(selector, function(k, elementos){
		elementos[k].addEventListener(evento, callback);
	});
}
function removeEvent(selector, evento, callback){
	selectorMultiple(selector, function(k, elementos){
		elementos[k].removeEventListener(evento, callback);
	});
}
//Previsualizacion de subida de imagenes
var imgupload = function(conf){
	conf.filein = document.querySelector(conf.filein);
	conf.container = document.querySelector(conf.container);
	conf.filein.addEventListener('change', function(){
		conf.container.innerHTML = '';
		var total = this.files.length;
		for(i=0; i<total; i++ ){
			var file = this.files[i];
			if(file.type.match(/image.*/)){
				var reader = new FileReader();
				reader.onloadend = function(e){
					var	img  = document.createElement('img');
					img.src = e.target.result;
					conf.container.appendChild(img);
				};
				reader.readAsDataURL(file);
			}
		}
	});
};

function ToSeoUrl(url) {
	var encodedUrl = url.toString().toLowerCase();        
	encodedUrl = encodedUrl.split(/\&+/).join("-and-")
	encodedUrl = encodedUrl.split(/[^a-z0-9]/).join("-");      
	encodedUrl = encodedUrl.split(/-+/).join("-");
	encodedUrl = encodedUrl.trim('-'); 
	return encodedUrl;
}

document.addEventListener('DOMContentLoaded',function(){
	//Definimos funcionamiento de barra de navegacion
	var btn = document.querySelector('#actionNav');
	var nav = document.querySelector('#leftNav');
	var cont = document.querySelector('#cont');
	function showNav(){
		btn.setAttribute('data-estado','open');
		nav.setAttribute('data-estado','open');
		cont.setAttribute('data-estado','open');
	}
	function hideNav(){
		btn.setAttribute('data-estado','close');
		nav.setAttribute('data-estado','close');
		cont.setAttribute('data-estado','close');
	}
	document.querySelector('#actionNav').addEventListener('click',function(){
		if( btn.getAttribute('data-estado') == "open" ){
			hideNav();
			listefi.setCookie('navshow', 0, 30);
		}else{
			showNav();
			listefi.setCookie('navshow', 1, 30);
		}
	});
	if( listefi.getCookie('navshow') == 1 ) showNav();
	else hideNav();
	
	//Tildamos checkeds por defecto
	selectorMultiple("input[type='checkbox']", function(k,e){
		var estado = e[k].getAttribute("data-estado");
		if(estado == 1) e[k].setAttribute('checked', 'checked');
	});
	
	//Agregamos la propiedad selected a los option con el value deseado
	selectorMultiple('select[data-selected]', function(k,e){
		var value = e[k].getAttribute('data-selected');
		var option = e[k].querySelector('option[value="'+value+'"]');
		if( option ) option.setAttribute('selected', 'selected');
	});
});

if( SITIO_SEC == 'configuracion' ){
	document.addEventListener('DOMContentLoaded', function(){
		addEvent('.confform', 'submit', function(e){
			e.preventDefault();
			
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) listefi.alert(
						'Los datos se han guardado correctamente',
						'Éxito'
					);
					else listefi.alert(
						'Los datos no se pudieron guardar',
						'Error'
					);
				}
			});
		});
	});	
}else if( SITIO_SEC == 'usuarios.editar' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-useredit").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) listefi.alert(
						'Los datos se han guardado correctamente',
						'Éxito'
					);
					else{
						var mensaje = '';
						if( result.error.indexOf('nickname_incorrecto') != -1 )
							mensaje += '<li>Nickname incorrecto (Solo puede contener letras, numeros y guiones)</li>';
						if( result.error.indexOf('email_incorrecto') != -1 )
							mensaje += '<li>E-mail incorrecto.</li>';
						if( result.error.indexOf('email_vacio') != -1 || result.error.indexOf('facebook_incorrecto') != -1 )
							mensaje += '<li>Facebook incorrecto</li>';
						if( result.error.indexOf('NO_PERMITIDO') != -1 )
							mensaje += '<li>No tienes permiso para editar este perfil</li>';
						
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>'+'<ul>'+mensaje+'</ul>',
							'Error'
						);
					}
				}
			});
		});
	});	
}else if( SITIO_SEC == 'categorias.editar' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-catedit").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) listefi.alert(
						'Los datos se han guardado correctamente',
						'Éxito'
					);
					else{
						var mensaje = '';
						if( result.error.indexOf('nickname_incorrecto') != -1 )
							mensaje += '<li>Nickname incorrecto (Solo puede contener letras, numeros y guiones)</li>';
						if( result.error.indexOf('email_incorrecto') != -1 )
							mensaje += '<li>E-mail incorrecto.</li>';
						if( result.error.indexOf('email_vacio') != -1 || result.error.indexOf('facebook_incorrecto') != -1 )
							mensaje += '<li>Facebook incorrecto</li>';
						
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>'+'<ul>'+mensaje+'</ul>',
							'Error'
						);
					}
				}
			});
		});
	});	
}else if( SITIO_SEC == 'categorias.crear' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-catcreate").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/categorias';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});	
}else if( SITIO_SEC == 'tienda.crear' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-tiendacrear").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/tienda';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'tienda.editar' ){
	document.addEventListener('DOMContentLoaded', function(){
		
		//Mostramos previsualizacion de imagen de producto
		imgupload({filein: '#cover', container: '#imgprev'});
		
		document.querySelector("#form-tiendaeditar").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/tienda';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'logros.crear' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-logroscrear").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/logros';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'logros.editar' ){
	document.addEventListener('DOMContentLoaded', function(){
		
		//Mostramos previsualizacion de imagen de logro
		imgupload({filein: '#cover', container: '#imgprev'});
		
		document.querySelector("#form-logroseditar").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/logros';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'comentarios' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Definimos funcionamiento de switch aprobar comentario
		addEvent("input[name*='sw_']", "click", function(){
			var valor = this.checked ? 1 : 2;
			var id = this.getAttribute("data-id");
			listefi.ajax({
				method: 'get',
				url: "?aprobar="+id+"&valor="+valor,
			});
		});
	});
}else if( SITIO_SEC == 'comentarios.editar' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Procesamos formulario de edicion de comentarios
		addEvent("#form-comentarioseditar", "submit", function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function(result){
					listefi.alert("Los cambios se han guardado correctamente","Éxito");
				}
			});
		});
	});
}else if( SITIO_SEC == 'entradas' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Definimos funcionamiento de switch aprobar comentario
		addEvent("input[name*='sw_']", "click", function(){
			var valor = this.checked ? 1 : 2;
			var id = this.getAttribute("data-id");
			listefi.ajax({
				method: 'get',
				url: "?aprobar="+id+"&valor="+valor,
			});
		});
	});
}else if( SITIO_SEC == 'webscrap.crear' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-webscrapcrear").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/webscrap';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'webscrap.editar' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Procesamos formulario de edicion de comentarios
		addEvent("#form-webscrapeditar", "submit", function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function(result){
					listefi.alert("Los cambios se han guardado correctamente","Éxito");
				}
			});
		});
	});
}else if( SITIO_SEC == 'pedidos' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Definimos funcionamiento de switch aprobar comentario
		addEvent("input[name*='sw_']", "click", function(){
			var valor = this.checked ? 1 : 2;
			var id = this.getAttribute("data-id");
			listefi.ajax({
				method: 'get',
				url: "?aprobar="+id+"&valor="+valor,
			});
		});
	});
}else if( SITIO_SEC == 'adds.crear' ){
	document.addEventListener('DOMContentLoaded', function(){
		document.querySelector("#form-addscrear").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function( result ){
					result = JSON.parse(result);
					
					if( result.estado == 1 ) document.location = PANEL_PATH+'/adds';
					else{
						listefi.alert(
							'<p>Los datos no se pudieron guardar:</p>',
							'Error'
						);
					}
				}
			});
		});
	});
}else if( SITIO_SEC == 'adds.editar' ){
	document.addEventListener("DOMContentLoaded", function(){
		//Procesamos formulario de edicion de comentarios
		addEvent("#form-addseditar", "submit", function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function(result){
					listefi.alert("Los cambios se han guardado correctamente","Éxito");
				}
			});
		});
	});
}