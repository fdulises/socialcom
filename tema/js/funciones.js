//jcslideshow Slideshow
var jcslideshow=function(e){var t=e.ulslideshow||!1,n=e.banterior,o=e.bsiguiente,r=e.intervalo||5,c=document.querySelector(n),l=document.querySelector(o),s=document.querySelector(t),u={},a=0,d=0,v=function(){u=s.querySelectorAll("li"),a=u.length},f=function(){if(d==a-1)var e=0;else var e=d+1;for(i=0;i<a;i++)u[i].style.opacity=e==i?1:0;d=e},y=function(){if(0==d)var e=a-1;else var e=d-1;for(i=0;i<a;i++)u[i].style.opacity=e==i?1:0;d=e},m=function(){jcstiempo=setInterval(f,1e3*r)},p=function(){window.clearInterval(jcstiempo)};v(),m(),c.addEventListener("click",f,!1),l.addEventListener("click",y,!1),s.addEventListener("mouseover",p,!1),s.addEventListener("mouseout",m,!1)};
function activarSlideshow(){var mislideshow = new jcslideshow({ulslideshow: "#JCSlideshow", bsiguiente: "#JCSbtna", banterior: "#JCSbtnb"});}

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

//dataToogle({listen: '#toogle',});
function dataToogle(conf){
	var escuchar = document.querySelectorAll(conf);
	var length = escuchar.length;
	function cambioEstado(elemento){
		var destino = document.querySelector(elemento.getAttribute('data-destino'));
		if( elemento.checked  ) destino.setAttribute('data-estado', 'show');
		else destino.setAttribute('data-estado', 'hide');
	}
	for(i=0; i<length; i++){
		cambioEstado(escuchar[i]);
		escuchar[i].addEventListener('change', function(){
			cambioEstado(this);
		});
	}
}

//Actualizar value de textarea
function ckeditorUpdate(){for (instance in CKEDITOR.instances) {
	CKEDITOR.instances[instance].updateElement();
}}

//Copiar texto
function txtCopy(tocopy) {
	var aux = document.createElement("input");
	aux.setAttribute("value", document.querySelector(tocopy).innerHTML);
	document.body.appendChild(aux);
	aux.select();
	document.execCommand("copy");
	document.body.removeChild(aux);
}

document.addEventListener('DOMContentLoaded',function(){
	
	//Carga peresoza de imagenes
	var myLazyLoad = new LazyLoad();
	
	//Galeria de imagenes Lightbox
	baguetteBox.run('.imglbg', {
		animation: 'fadeIn',
		noScrollbars: true
	});

	//Agregamos la propiedad selected a los option con el value deseado
	var selectelems = document.querySelectorAll('select[data-selected]');
	Object.keys(selectelems).map(function(k){
		var value = selectelems[k].getAttribute('data-selected');
		var option = selectelems[k].querySelector('option[value="'+value+'"]');
		if( option ) option.setAttribute('selected', '');
	});

	document.querySelector('#actionNav').addEventListener('click',function(){
		var btn = this;
		var nav = document.querySelector('#sup-nav');
		if( btn.getAttribute('data-estado') == "open" ){
			btn.setAttribute('data-estado','close');
			nav.setAttribute('data-estado','close');
		}else{
			btn.setAttribute('data-estado','open');
			nav.setAttribute('data-estado','open');
		}
	});
	document.querySelector('#nav-search-btn').addEventListener('click',function(){
		var btn = this;
		var nav = document.querySelector('#nav-search-in');
		if( btn.getAttribute('data-estado') == "open" ){
			btn.setAttribute('data-estado','close');
			nav.setAttribute('data-estado','close');
		}else{
			btn.setAttribute('data-estado','open');
			nav.setAttribute('data-estado','open');
		}

	});
	if( (/post=1/).test(window.location) ) listefi.alert(
		"Tu entrada ha sido enviada y pronto sera visible", "Éxito"
	);
	else if( (/error=no_donacion/).test(window.location) ) listefi.alert(
		"No tienes suficientes puntos para donar", "Error"
	);

	if( SITIO_SEC == 'post' ){
		var post_editor = document.querySelector('#post_editor');
		var cover = document.querySelector('#cover');
		var cover_btn = document.querySelector("#form-img-btn");
		var cover_box = document.querySelector('#form-img-box');
		var coverurl_box = document.querySelector('#form-imgurl-box');
		var coverurl = document.querySelector('#cover_url');
		var imgurl_s = document.querySelector('#imgurl_s');
		var cover_cont = document.querySelector('#cover_cont');
		var cover_cont_url = document.querySelector('#cover_cont_url');
		var puntosv = document.querySelector('#puntosv');
		var puntos_s = document.querySelector('#puntos_s');
		var post_titulo = document.querySelector("#titulo");
		var post_slug = document.querySelector("#url");
		
		//Generamos recomendacion de SLUG
		post_titulo.addEventListener("blur", function(){
			if( this.value && !post_slug.value )
				post_slug.value = ToSeoUrl(this.value);
		});

		//Ocultar/Mostrar campos usando ckecboxes
		if( coverurl.value != '' ) imgurl_s.setAttribute('checked', 'checked');
		if( puntosv.value != 0 ) puntos_s.setAttribute('checked', 'checked');
		function coverMethod(){
			if( imgurl_s.checked  ){
				cover_cont.setAttribute('data-estado', 'hide');
				cover_cont_url.setAttribute('data-estado', 'show');
			}else{
				cover_cont.setAttribute('data-estado', 'show');
				cover_cont_url.setAttribute('data-estado', 'hide');
			}
		}
		coverMethod();
		imgurl_s.addEventListener('change', coverMethod);
		dataToogle('.dataToogle');

		//Mostrar previsualizacion de imagen de portada por url
		function showCoverURL(){
			var cover = document.querySelector('#cover');
			coverurl_box.innerHTML = '';
			cover_box.innerHTML = '';
			cover.value = '';
			if(coverurl.value != ''){
				var cover = document.createElement("img");
				cover.src = coverurl.value;
				coverurl_box.appendChild(cover);
			}
		}
		showCoverURL();
		coverurl.addEventListener('blur', showCoverURL);

		//Mostrar previsualizacion de imagen de portada al subir
		cover.addEventListener('change', function(){
			cover_box.innerHTML = '';
			coverurl_box.innerHTML = '';
			coverurl.value = '';
			var total = this.files.length;
			for(i=0; i<total; i++ ){
				var file = this.files[i];
				if(file.type.match(/image.*/)){
					var reader = new FileReader();
					reader.onloadend = function(e){
						var	img  = document.createElement('img');
						img.src = e.target.result;
						cover_box.appendChild(img);
					};
					reader.readAsDataURL(file);
				}
			}
		});
		cover_btn.addEventListener('click', function(){cover.click();});

		//Procesamos el formulario de crear entrada
		post_editor.addEventListener('submit', function(e){
			e.preventDefault();
			ckeditorUpdate();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						document.location.href = SITIO_URL+'?post=1';
					}else{
						var mensaje = '';
						if( result.error.indexOf('titulo_vacio') != -1 )
							mensaje += '<li>El titulo no puede estar vacio</li>';
						if( result.error.indexOf('url_vacio') != -1 )
							mensaje += '<li>El slug no puede estar vacio</li>';
						if( result.error.indexOf('cover_error') != -1 )
							mensaje += '<li>Error al agregar la imagen de portada</li>';
						listefi.alert('<ul>'+mensaje+'</ul>','Tienes algunos errores');
					}
				}
			});
		});
	}else if( IS_ENTRADA ){
		document.querySelector("#scrollto_coment").addEventListener("click", function(){
			listefi.scrollTo(document.querySelector("#coment_form_box").offsetTop, 1000, false);
		});
		//Procesar envio de formulario de comentarios
		var coment_form = document.querySelector('#coment_form');
		if( !coment_form ) coment_form = document.createElement('div');
		coment_form.addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: 'post',
				url: '?comentar',
				data: {
					autor: this.autor.value,
					email: this.email.value,
					contenido: this.contenido.value,
				},
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						coment_form.reset();
						listefi.alert('Tu comentario ha sido enviado y pasara por revisión antes de ser publicado.', 'Comentario enviado');
					}else{
						var mensaje = '';
						if( result.error.indexOf('autor_vacio') != -1 )
							mensaje += '<li>Tienes que escribir tu nombre.</li>';
						if( result.error.indexOf('demasiados_comentarios') != -1 )
							mensaje += '<li>No puedes enviar tantos comentarios de forma seguida, intentalo unos minutos más tarde.</li>';
						if( result.error.indexOf('email_vacio') != -1 || result.error.indexOf('email_incorrecto') != -1 )
							mensaje += '<li>El E-mail ingresado es incorrecto.</li>';
						if( result.error.indexOf('contenido_vacio') != -1 )
							mensaje += '<li>No puedes enviar un comentario vacio.</li>';
						listefi.alert('<ul>'+mensaje+'</ul>','Comentario Rechazado');
					}
				}
			});
		});
		var coment_member_form = document.querySelector('#coment_member_form');
		if( !coment_member_form )
			coment_member_form = document.createElement('div');
		coment_member_form.addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: 'post', url: '?comentar',
				data: {contenido: this.contenido.value,},
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						coment_member_form.reset();
						listefi.alert('Tu comentario ha sido enviado y pasara por revisión antes de ser publicado.', 'Comentario enviado');
					}else{
						var mensaje = '';
						if( result.error.indexOf('demasiados_comentarios') != -1 )
							mensaje += '<li>No puedes enviar tantos comentarios de forma seguida, intentalo unos minutos más tarde.</li>';
						if( result.error.indexOf('contenido_vacio') != -1 )
							mensaje += '<li>No puedes enviar un comentario vacio.</li>';
						listefi.alert('<ul>'+mensaje+'</ul>','Comentario Rechazado');
					}
				}
			});
		});
		
		//Definimos funcionamiento de botones like
		var totallikescont = document.querySelector("#totallikes");
		function procesaLIke(){
			var likebtn = this;
			var tipo = likebtn.getAttribute('data-tipo');
			var id = likebtn.getAttribute('data-id');
			listefi.ajax({
				url: '?like='+id+'&tipo='+tipo,
				method: 'get',
				success: function(resultado){
					resultado = JSON.parse(resultado);
					totallikescont.innerHTML = parseInt(totallikescont.innerHTML) + parseInt(tipo);
					removeEvent('.likebtn', 'click', procesaLIke);
					if( tipo == 1 )
						likebtn.setAttribute('data-estado', 'success');
					else
						likebtn.setAttribute('data-estado', 'error');
				}
			});
		}
		addEvent('.likebtn', 'click', procesaLIke);
		
		//Definimos funcionamiento de campo donar puntos
		var donapuntosform = document.querySelector("#post_usopt_puntos");
		var puntos_in = document.querySelector("#puntos_in");
		var max_p = parseInt(donapuntosform.getAttribute("data-maxp"));
		function validaPuntos(){
			var valor = parseInt(this.value);
			if( valor < 0 ) this.value = 1;
			if( valor > max_p ) this.value = max_p;
		}
		puntos_in.addEventListener('change',  validaPuntos);
		puntos_in.addEventListener('blur',  validaPuntos);
		donapuntosform.addEventListener('submit', function(e){
			e.preventDefault();
			var id = this.getAttribute('data-id');
			var cantidad = this.cantidad.value;
			listefi.ajax({
				url: '?puntear='+id+'&cantidad='+cantidad,
				method: 'get',
				success: function(resultado){}
			});
			this.innerHTML = '<div class="alert alert-info"><span class="icon-info"></span> Puntos Agregados</div>';
		});
		
		//Metodo para procesar denuncias de contenido
		function procesaDenuncia(conf){
			listefi.confirm(conf.msg, function(r){if(r){
				listefi.ajax({
					url: '?denunciar='+conf.destino+'&tipo='+conf.tipo,
					method: 'get', success: conf.success
				});
			}});
		}
		
		//Definimos funcionamiento de campo denunciar entrada
		var denun_in = document.querySelector("#denun_in");
		denun_in.addEventListener('click', function(){
			procesaDenuncia({
				msg: "Realmente desea denunciar esta entrada?",
				destino: this.getAttribute('data-id'),
				tipo: 1, success: function(result){
					listefi.alert("Se ha envidado tu denuncia!", 'Éxito');
					denun_in.removeEventListener('click',procesaDenuncia);
				}
			});
		});
		
		//Boton denunciar comentario
		function coment_denuncia(){
			var self = this;
			procesaDenuncia({
				msg: "Realmente desea denunciar este comentario?",
				destino: this.getAttribute('data-id'),
				tipo: 2, success: function(result){
					listefi.alert("Se ha envidado tu denuncia!", 'Éxito');
					self.removeEventListener('click', coment_denuncia);
				}
			});
		}
		addEvent(".coment_denuncia", 'click', coment_denuncia);
		
		var btn_deleteEntrada = document.querySelector("#btn-deleteEntrada");
		if( btn_deleteEntrada ){
			btn_deleteEntrada.addEventListener('click', function(){
				var id = this.getAttribute('data-id');
				listefi.confirm("Realmente desea eliminar esta entrada?", function(r){
				if(r){
					listefi.ajax({
						url: '?eliminar='+id,
						method: 'get',
						success: function(resultado){
							resultado = JSON.parse(resultado);
							if(resultado.estado == 1)
								listefi.alert("La entrada ha sido eliminada!", "Éxito");
							else listefi.alert("No se pudo eliminar la entrada", "Error");
						}
					});
					
				}
			});
			});
		}
	}else if( SITIO_SEC == 'busqueda' ){
		//Funcionamiento del formulario de busqueda avanzada
		var contfbusq = document.querySelector("#cont-fbusq");
		var contfbusq_order = document.querySelector('#filtro_orden');
		//Funcionamiento del input order
		var paginaurl = document.location, orderselected = '';
		if( (/order=likes/).test(paginaurl) ) orderselected = 'likes';
		else if( (/order=recientes/).test(paginaurl) ) orderselected = 'recientes';
		else if( (/order=hits/).test(paginaurl) ) orderselected = 'hits';
		else if( (/order=puntosv/).test(paginaurl) ) orderselected = 'puntosv';
		if( orderselected != '' ) document.querySelector('option[value="'+orderselected+'"]')
			.setAttribute('selected', '');
		contfbusq_order.addEventListener('change', function(){contfbusq.submit();});
	}else if( SITIO_SEC == 'cuenta' ){
		document.querySelector("#cuenta_editor").addEventListener('submit', function(e){
			e.preventDefault();
			listefi.ajax({
				method: this.method,
				url: this.action,
				data: new FormData(this),
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						listefi.alert('Tus datos se han actualizado correctamente', 'Éxito');
					}else{
						var mensaje = '';
						if( result.error.indexOf('email_incorrecto') != -1 )
							mensaje += '<li>El E-mail ingresado es incorrecto</li>';
						if( result.error.indexOf('avatar_incorrecto') != -1 )
							mensaje += '<li>Error al subir el avatar seleccionado (Puede que el formato sea incorrecto o la imagen muy pequeña)</li>';
						if( result.error.indexOf('cover_incorrecto') != -1 )
							mensaje += '<li>Error al subir la imagen de portada (Puede que el formato sea incorrecto o la imagen muy pequeña)</li>';
						if( result.error.indexOf('facebook_incorrecto') != -1 )
							mensaje += '<li>El campo Facebook es incorrecto</li>';
						listefi.alert('<ul>'+mensaje+'</ul>','Tienes algunos errores');
					}
				}
			});
		});

		//Mostramos la imagen existente antes de la subida
		selectorMultiple('.form-upload[data-imgsource]', function(k, elementos){
			var img = document.createElement('img');
			img.src = elementos[k].getAttribute('data-imgsource');
			elementos[k].appendChild(img);
		});
		//Definimos el comportamineto de los botones upload
		addEvent('button[data-upload-source]', 'click', function(){
			var destino = document.querySelector(
				this.getAttribute('data-upload-source')
			);
			if( destino ) destino.click();
		});
		//Generamos las miniaturas de los uploaders
		imgupload({filein: '#avatar', container: '#form-avatar-box'});
		imgupload({filein: '#cover', container: '#form-cover-box'});
		
		//Procesamos cambio de contraseña
		var usuario_clave = document.querySelector("#usuario_clave");
		var usuario_reclave = document.querySelector("#usuario_reclave");
		var usuario_btnclave = document.querySelector("#usuario_btnclave");
		
		function procesaCambioClave(){
			var uclave = usuario_clave.value;
			var ureclave = usuario_reclave.value;
			if( uclave == ureclave ){
				listefi.ajax({
					url: '?cuenta_clave',
					method: 'post',
					data: {
						clave: hex_sha512(uclave)
					},
					success: function(resultado){
						resultado = JSON.parse(resultado);
						if( resultado.estado == 1) listefi.alert(
							"<p>Se ha guardado tu nueva contraseña</p>", "Éxito",
							function(){
							document.location.href = SITIO_URL+'/acceso';
						});
						else listefi.alert(
							"<p>Ocurrio un error al procesar la solicitud.</p><p>Intentalo de nuevo.</p>", "Error"
						);
					}
				});
			}else listefi.alert("<p>Los campos de contraseña no coinciden.</p><p>Intentalo de nuevo.</p>", "Error");
		}
		
		usuario_btnclave.addEventListener("click", function(){
			var msg_clave = "<p>Para cambiar la contraseña es necesario cerrar la sesión actual.</p>";
			msg_clave += "<p>Realmente desea continuar?</p>";
			listefi.confirm(msg_clave, function(resp){
				if(resp) procesaCambioClave();
			});
		});

	}else if( SITIO_SEC == 'perfil' ){
		document.querySelector("#superior").setAttribute('data-seccion', 'perfil');

		//Algoritmo para procesar eliminacion de publicaciones
		function publicdel(){
			var publicid = this.getAttribute('data-publicdel');
			listefi.confirm('Desea eliminar esta publicación de forma permanente?', function(respuesta){
				if(respuesta){
					listefi.ajax({
						url: '?public_del&id='+publicid, method: 'get',
						success: function(result){
							result = JSON.parse(result);
							if(result.estado == 1){
								document.querySelector('#publicel_'+publicid).style.display = 'none';
								listefi.alert('Publicación eliminada correctamente', 'Exito');
							}else{
								listefi.alert('No se ha podido eliminar la publicación', 'Error');
							}
						}
					});
				}
			});
		}
		addEvent('button[data-publicdel]', 'click', publicdel);

		//Algoritmo para procesar envio de publicaciones
		var public_set = document.querySelector("#public_set") || document.createElement('div');
		public_set.addEventListener('submit', function(e){
			e.preventDefault();
			function createPublicHTML(data){
				return '<div id="publicel_'+data.public_id+'" class="mg-sec cont-white"><div class="container"><div class="gd-20"><img class="coment_avatar" src="'+data.public_avatar+'"></div><div class="gd-80">	<div class="coment_autor"><a href="'+SITIO_URL+'/@'+data.public_autor+'">@'+data.public_autor+'</a></div><div class="coment_fecha">'+data.public_fecha+'</div><div class="coment_cont">'+data.public_contenido+'</div></div><div class="gd-100 tx-right"><button class="btn btn-default size-s" data-publicdel="'+data.public_id+'"><span class="icon-blocked"></span>Eliminar</button></div></div></div>';
			}

			var superior = this.getAttribute('data-superior');
			var contenido = this.publi_content.value;
			if( contenido == '' ) return 0;
			listefi.ajax({
				url: this.action, method: this.method,
				data: {
					superior: superior,
					contenido: contenido
				},
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						public_set.reset();
						var newpublic = createPublicHTML(result.data);
						var publiccont = document.querySelector("#recentPubli");
						publiccont.innerHTML = newpublic+publiccont.innerHTML;
						addEvent(
							'button[data-publicdel="'+result.data.public_id+'"]', 'click', publicdel
						);
					}
				}
			});
		});
		
		//Algoritmo para procesar envio de comentarios de publicacion
		addEvent('.form_public_set', 'submit', function(e){
			e.preventDefault();
			var public_set = this;
			var superior = this.getAttribute('data-superior');
			var contenido = this.publi_content.value;
			if( contenido == '' ) return 0;
			listefi.ajax({
				url: this.action, method: this.method,
				data: {
					superior: superior,
					contenido: contenido
				},
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						public_set.reset();
						listefi.alert('Comentario enviado', 'Éxito');
					}else{
						listefi.alert('No sabemos que paso', 'Oops!');
					}
				}
			});
		});

		//Algoritmos para botones seguir/dejar de seguir
		function ajaxFollow(){
			var destino = this.getAttribute('data-id');
			var elemento = this;
			listefi.ajax({ url: '?follow='+destino, method: 'get',
				success: function(resp){followSuccess(elemento);}
			});
		}
		function ajaxUnfollow(){
			var destino = this.getAttribute('data-id');
			var elemento = this;
			listefi.ajax({ url: '?unfollow='+destino, method: 'get',
				success: function(resp){unfollowSuccess(elemento);}
			});
		}
		function followSuccess(elemento){
			elemento.removeEventListener('click', ajaxFollow);
			elemento.setAttribute('data-follow', 1);
			unfollow(elemento);
		}
		function unfollowSuccess(elemento){
			elemento.removeEventListener('click', ajaxUnfollow);
			elemento.removeEventListener('mouseover', followover);
			elemento.removeEventListener('mouseout', followout);
			elemento.setAttribute('data-follow', 0);
			follow(elemento);
		}
		function followover(){this.innerHTML = 'Dejar de seguir';}
		function followout(){this.innerHTML = 'Siguiendo';}
		function follow(elem){
			elem.innerHTML = 'Seguir';
			elem.addEventListener('click', ajaxFollow);
		}
		function unfollow(elem){
			elem.innerHTML = 'Siguiendo';
			elem.addEventListener('mouseover', followover);
			elem.addEventListener('mouseout', followout);
			elem.addEventListener('click', ajaxUnfollow);
		}
		selectorMultiple('button[data-follow]', function(k, elementos){
			var isfollow = elementos[k].getAttribute('data-follow');
			if( isfollow == 0 ) follow(elementos[k]);
			else unfollow(elementos[k]);
		});

		//Definimos el comportamiento de los 2 botones seguir del perfil
		var followbtn = document.querySelectorAll('button[data-followbtn]');
		if( followbtn.length == 2 ){
			followbtn[0].addEventListener('click', function(){
				var isfollow = this.getAttribute('data-follow');
				if( isfollow == 0 ) followSuccess(followbtn[1]);
				else unfollowSuccess(followbtn[1]);
			});
			followbtn[1].addEventListener('click', function(){
				var isfollow = this.getAttribute('data-follow');
				if( isfollow == 0 ) followSuccess(followbtn[0]);
				else unfollowSuccess(followbtn[0]);
			});
		}
		
		//Procesamos formulario de subida de fotos
		var form_imgupload = document.querySelector("#form-fotoupload");
		if( form_imgupload ){
			
			imgupload({filein: '#foto', container: '#prevFoto'});
			var infoto = document.querySelector("#foto");
			document.querySelector("#perfilFotoSubidaBtn").addEventListener('click', function(){
				infoto.click();
			});
			form_imgupload.addEventListener('submit', function(e){
				e.preventDefault();
				listefi.ajax({
					method: this.method,
					url: this.action,
					data: new FormData(this),
					success: function(result){
						result = JSON.parse(result);
						if(result.estado == 1){
							var fotoelem = document.createElement("div");
							fotoelem.setAttribute('class', 'gd-25 fotos-element');
							fotoelem.setAttribute('data-id', result.data.id);
							var fotoresult = document.createElement("img");
							fotoresult.src = result.data.url;
							fotoelem.appendChild(fotoresult);
							document.querySelector("#fotos-cont").appendChild(fotoelem);
							
							document.querySelector("#prevFoto").innerHTML = '';
							form_imgupload.reset();
						}else{
							var mensaje = '';
							if( result.error.indexOf('permitted_pics') != -1 )
								mensaje += '<li>Has alcanzado el limite de fotos para tu nivel</li>';
							else if( result.error.indexOf('error_subida') != -1 )
								mensaje += '<li>Ocurrio un error al subir el archivo</li>';
							else if( result.error.indexOf('error_type') != -1 )
								mensaje += '<li>El formato de la imágen no es valido</li>';
							else if( result.error.indexOf('error_size') != -1 )
								mensaje += '<li>La imágen es damasiado grande</li>';
							else mensaje += '<li>Ocurrio un error al procesar la petición, por favor intenta de nuevo más tarde</li>';
							listefi.alert('<ul>'+mensaje+'</ul>','Tienes algunos errores');
						}
					}
				});
			});
			
			//Procesamos eliminacion de fotos
			function deletePic(btnpic){
				var id = btnpic.getAttribute('data-id');
				listefi.ajax({
					url: '?fotodel='+id,
					method: 'get',
					success: function(resultado){
						resultado = JSON.parse(resultado);
						if( resultado.estado == 1 )
							document.querySelector("[data-id='"+id+"'].fotos-element").style.display = 'none';
						else
							listefi.alert("La imágen no se pudo eliminar", "Error");
					}
				});
			}
			
			addEvent('.btndelete-cont button', 'click', function(){
				var btnpic = this;
				listefi.confirm('Realmente quieres eliminar esta imágen?', function(resp){
					if(resp) deletePic(btnpic);
				});
			});
		}
		
		//Algoritmo para obtener comentarios de publicacion
		function getPubliComent(data){
			var pag = data.pagina || 0;
			var sup = data.superior || 0;
			listefi.ajax({
				url: SITIO_URL+'/public_coment?id='+sup+'&p='+pag,
				method: 'get',
				success: data.callback
			});
		}
		
		//Algoritmo para mostrar/ocultar caja de comentarios de publicacion
		addEvent('.coment_btn', 'click', function(){
			var contenedor = this;
			var superior = this.getAttribute("data-superior");
			var destino = this.getAttribute("data-destino");
			destino = document.querySelector(destino);
			var estado = destino.getAttribute("data-state");
			var pagina = parseInt(destino.getAttribute("data-pagina"));
			if( !pagina ){
				getPubliComent({
					pagina: pagina,
					superior: superior,
					callback: function(resp){
						var contencoment = document.querySelector(".public_coment_list[data-superior='"+superior+"']");
						contencoment.innerHTML = resp;
						//alert(.innerHTML);
						// = resp;
					}
				});
				destino.setAttribute("data-pagina", 1);
			}
			
			if( "closed" == estado )
				destino.setAttribute("data-state", "open");
			else destino.setAttribute("data-state", "closed");
		});
		
		//Metodo para procesar usuario
		function procesaDenuncia1(){
			listefi.confirm("Realmente desea denunciar este usuario?", function(r){if(r){listefi.ajax({
				url: '?denunciar='+perfil_denuncia.getAttribute('data-id')+'&tipo='+3,
				method: 'get', success: function(result){
					listefi.alert("Se ha envidado tu denuncia!", 'Éxito');
					perfil_denuncia.removeEventListener('click',procesaDenuncia1);
				}
			});}});
		}		
		//Definimos funcionamiento de boton denunciar usuario
		var perfil_denuncia = document.querySelector("#perfil_denuncia");
		perfil_denuncia.addEventListener('click', procesaDenuncia1);

	}else if( SITIO_SEC == 'tienda' ){
		addEvent('.btncomprar', 'click', function(){
			
			var btncomprar = this;
			var id = btncomprar.getAttribute('data-id');
			var precio = parseInt(btncomprar.getAttribute('data-precio'));
			var user_puntos = document.querySelector("#user-puntos");
			var puntos = parseInt(user_puntos.innerHTML);
			
			//Procesamos compra
			function comprar(id){
				listefi.ajax({
					url: '?comprar='+id,
					method: 'get',
					success: function(result){
						result = JSON.parse(result);
						if( result.estado == 1 ){
							listefi.alert("El item se ha añadido a tu perfil", "Éxito");
							user_puntos.innerHTML = puntos - precio;
						}else{
							var msg = '';
							if( result.error.indexOf('puntos_insuficientes') != -1 )
								msg = 'No cuentas con suficientes puntos';
							else if( result.error.indexOf('producto_comprado') != -1 )
								msg = 'Ya has comprado este producto';
							else if( result.error.indexOf('producto_inexistente') != -1 )
								msg = 'Error al intar comprar el producto';
							listefi.alert(msg, "Error");
						}		
					}
				});
			}
			var mensaje = "<p>Desea adquirir este producto?</p>";
			mensaje += "<p><strong>Precio:</strong> "+precio+"</p>";
			mensaje += "<p><strong>Puntos:</strong> "+puntos+"</p>";
			listefi.confirm( mensaje, function(resp){
				if(resp) comprar(id);
			});
		});
	}else if( SITIO_SEC == 'contacto' ){
		var contactform = document.querySelector("#contactform")
		contactform.addEventListener("submit", function(e){
			e.preventDefault();
			listefi.ajax({
				url: this.action,
				method: this.method,
				data: new FormData(this),
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						listefi.alert("Tu mensaje ha sido enviado!", 'Éxito');
						contactform.reset();
					}else{
						var mensaje = '';
						if( result.error.indexOf('nombre_vacio') != -1 )
							mensaje += '<li>Tienes que llenar el campo nombre</li>';
						else if( result.error.indexOf('email_vacio') != -1 )
							mensaje += '<li>Tienes que llenar el campo E-mail</li>';
						else if( result.error.indexOf('asunto_vacio') != -1 )
							mensaje += '<li>Tienes que llenar el campo asunto</li>';
						else if( result.error.indexOf('mensaje_vacio') != -1 )
							mensaje += '<li>Tienes que escribir algo en el mensaje</li>';
						else mensaje += '<li>Ocurrio un error al procesar la petición, por favor intenta de nuevo más tarde</li>';
						listefi.alert('<ul>'+mensaje+'</ul>','Tienes algunos errores');
					}
				}
			});
		});
	}else if( SITIO_SEC == 'pedidos' ){
		var pedidosform = document.querySelector("#pedidosform");
		pedidosform.addEventListener('submit', function(e){
			e.preventDefault();
			if( !this.nombre.value ) return;
			listefi.ajax({
				url: this.action,
				method: this.method,
				data: {
					nombre: this.nombre.value
				},
				success: function(result){
					if(result == 1)
						listefi.alert('Tu pedido ha sido enviado','Éxito');
					else listefi.alert('No se pudo enviar tu pedido, intentar de nuevo más tarde','Error');
					pedidosform.reset();
				}
			});
		});
	}else if( SITIO_SEC == 'referidos' ){
		var btn_copy = document.querySelector("#btn_copy");
		btn_copy.addEventListener("click", function(){
			txtCopy("#copiar_enlace");
			listefi.alert("Enlace copiado al portapapeles", "Éxito");
		});
	}
});

//Algoritmo para procesar eliminacion de comentarios en publicaciones
function publicComentDel(el){
	var publicid = el.getAttribute('data-publicdel');
	listefi.confirm('Desea eliminar esta publicación de forma permanente?', function(respuesta){
		if(respuesta){
			listefi.ajax({
				url: '?public_del&id='+publicid, method: 'get',
				success: function(result){
					result = JSON.parse(result);
					if(result.estado == 1){
						document.querySelector('[data-id="'+publicid+'"].comentario_li').style.display = 'none';
						listefi.alert('Publicación eliminada correctamente', 'Exito');
					}else{
						listefi.alert('No se ha podido eliminar la publicación', 'Error');
					}
				}
			});
		}
	});
}