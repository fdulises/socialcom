{include=tpl/html/header}
	<article>
		{include=tpl/html/letras}
		<div id="cont">
			<div class="container pd-z mg-sec">
				<div class="gd-70 gd-b-100">
					{is_login}{is_owner}
					<div id="post_opt" class="cont-white mg-sec">
						<strong>Opciones de post: &nbsp;</strong>
						<a href="{SITIO_URL}/post?id={articulo_id}">
							<button class="btn btn-primary size-s">
								<span class="icon-pencil"></span> &nbsp; Editar
							</button>
						</a>
						<button id="btn-deleteEntrada" class="btn btn-default size-s" data-id="{articulo_id}">
							<span class="icon-cross"></span> &nbsp; Eliminar
						</button>
					</div>
					{/is_owner}{/is_login}
					<div id="post">
						<h2>{articulo_titulo}</h2>
						<div id="post_info">
							<a href="{SITIO_URL}/@{articulo_autor}"><span class="post_info_d">
								<span class="icon-user"></span> @{articulo_autor}
							</span></a>
							<span class="post_info_d">
								<span class="icon-clock"></span> {articulo_fecha}
							</span>
							<span class="post_info_d">
								<span class="icon-bubbles2"></span> {articulo_comentarios} Comentarios
							</span>
							<span class="post_info_d">
								<span class="icon-eye"></span> {articulo_hits} Lecturas
							</span>
							<span class="post_info_d">
								<span class="icon-droplet"></span> {articulo_puntos} Puntos
							</span>
							<span class="post_info_d">
								<span class="icon-thumb_up"></span> {articulo_likes} Likes
							</span>
						</div>
						<div id="post_cover"><img src="{articulo_portada}" /></div>
						<div id="post_contenido">{articulo_contenido}</div>
						{has_descargas}
						<div class="mg-sec container">
							<div class="gd-100">
								<h3><span class="icon-link"></span> Enlaces</h3>
								<div id="post_descargas">{articulo_descargas}</div>
							</div>
						</div>
						<br>
						{/has_descargas}
					</div>
					{is_login}
					<div id="post_usopt" class="container cont-white mg-sec">
						<div id="post_usopt_likes" class="gd-33 gd-s-100">
							<div id="likebox">
								<button class="likebtn btn btn-default" data-tipo="1" data-id="{articulo_id}">
									<span class="icon-thumb_up"></span>
								</button>
								<button class="likebtn btn btn-default" data-tipo="-1" data-id="{articulo_id}">
									<span class="icon-thumb_down"></span>
								</button>
								<span class="display-i-b">&nbsp; <span id="totallikes">{articulo_likes}</span>  Likes</span>
							</div>
						</div>
						<form id="post_usopt_puntos" class="gd-33 gd-s-100 tx-center" method="get" action="?punteo" data-id="{articulo_id}" data-maxp="{SITIO_MAXP}">
							<input id="puntos_in" type="number" class="form-in" value="{SITIO_MAXP}" name="cantidad">
							<button class="btn btn-primary size-s" type="submit">
								Dar puntos
							</button>
						</form>
						<div id="post_usopt_denun" class="gd-33 gd-s-100 tx-right">
							<button id="denun_in" class="btn"  data-id="{articulo_id}">
								<span class="icon-blocked"></span> Denunciar
							</button>
						</div>
					</div>
					{/is_login}
					<div id="articulo_autor_info" class="container cont-white mg-sec">
						<h4>Información del autor</h4>
						<div class="gd-20 gd-s-100">
							<p><img src="{articulo_autor_avatar}"></p>
						</div>
						<div class="gd-80 gd-s-100">
							<p><strong>Usuario: </strong><a href="{SITIO_URL}/@{articulo_autor}">@{articulo_autor}</a> - {articulo_autor_grupo}</p>
							<p><strong>Descripción: </strong>{articulo_autor_descrip}</p>
							<p id="articulo_autor_medallas">
							<strong>Medallas: </strong>
							{logros_list}
							<img src="{cover}" title="{nombre}" />
							{/logros_list}
							</p>
						</div>
					</div>
					<div class="container mg-sec">
					{relacionados}
					<div class="card-t1 gd-25 gd-m-50 gd-s-100">
						<a href="{articulo_enlace}">
							<img data-original="{SITIO_URL}/miniatura?w=600&h=600&src={articulo_portada}" />
							<h3>{articulo_titulo}</h3>
						</a>
					</div>
					{/relacionados}
					</div>
				</div>
				<div id="post_aside" class="gd-30 gd-b-100">
					<div id="ad_400" class="">{field_add2}</div>
					<div class="card-t2 pd-z">
						<div class="card-mark">
							<h3><span class="icon-bookmark"></span> Categorias</h3>
							<ul>
								{lista_categorias}
								<li><a href="{categoria_enlace}">{categoria_titulo}</a></li>
								{/lista_categorias}
							</ul>
						</div>
					</div>
					<div class="tx-right mg-sec">{add_t1}</div>
				</div>
			</div>
			
			<div class="container pd-z mg-sec">
				<div class="gd-70 gd-b-100">
					<h3>Comentarios</h3>
					<ol id="coment_list">
						<div class="container">
							<div class="gd-60">
								<h4>{articulo_comentarios} Comentarios</h4>
							</div>
							<div class="gd-40 tx-right">
								<button class="btn size-s" id="scrollto_coment">Publicar un comentario</button>
							</div>
						</div>
						{lista_comentarios}
						<li id="coment_{comentario_id}">
							<div class="container">
								<div class="gd-10">
									<img class="coment_avatar" src="{comentario_avatar}" />
								</div>
								<div class="gd-90">
									<div class="coment_autor">{comentario_autor}
										<div class="bx-right">
											<button class="btn coment_denuncia size-s" data-id="{comentario_id}">
												<span class="icon-blocked"></span>
											</button>
										</div>
									</div>
									<div class="coment_fecha">
										{TIEMPOFORMATO|d/m/Y|{comentario_fecha}}
									</div>
									<div class="coment_cont">{comentario_contenido}</div>
								</div>
							</div>
						</li>
						{/lista_comentarios}
					</ol>
				</div>
			</div>
			<div id="coment_form_box" class="container pd-z mg-sec">
				{no_login}
				<form id="coment_form" class="gd-70 gd-b-100" method="post">
					<h3>Escribir un comentario</h3>
					<div class="form-sec">
						<textarea id="contenido" name="contenido" rows="10" class="form-in" placeholder="Comentario"></textarea>
					</div>
					<div class="form-sec">
						<input type="text" id="autor" name="autor" class="form-in" placeholder="Nombre" />
					</div>
					<div class="form-sec">
						<input type="text" id="email" name="email" class="form-in" placeholder="E-mail" />
					</div>
					<div class="tx-right">
						<button type="submit" class="btn btn-default">Enviar comentario</button>
					</div>
				</form>
				{/no_login}
				{is_login}
				<h3 class="gd-70 gd-b-100">Escribir un comentario</h3>
				<form id="coment_member_form" class="gd-70 gd-b-100 pd-z mg-sec" action="comentar">
					<div class="gd-10 gd-b-20 gd-x-100">
						<img src="{user_avatar}">
					</div>
					<div class="gd-90 gd-b-80 gd-x-100 pd-z">
						<div class="form-sec">
							<textarea id="contenido" name="contenido" rows="10" class="form-in" placeholder="Comentario"></textarea>
						</div>
						<div class="tx-right">
							<button type="submit" class="btn btn-default">Enviar comentario</button>
						</div>
					</div>
				</form>
				{/is_login}
			</div>
		</div>
	</article>
	{donar_puntos}
	<style>
	.mw-screen{background-color: black}
	.mw-screen{background-color: rgba(0,0,0,0.95)}
	</style>
	<script>
	document.addEventListener('DOMContentLoaded', function(){
		var donmsg = "<p>Es necesario donar {puntosv} puntos para poder continuar</p><p>Realmente desea continuar?</p>";
		listefi.confirm(donmsg, function(resp){
			if(resp) listefi.ajax({ method: 'get',
				url: window.location+'?donar={articulo_id}',
				success: function(result){
					if( JSON.parse(result).estado != 1 )
						document.location.href = SITIO_URL+'?error=no_donacion';
				}
			});
			else document.location.href = SITIO_URL;
		});
	});
	</script>
	{/donar_puntos}
{include=tpl/html/footer}
