{include=tpl/html/perfil_header}
<div class="gd-80 gd-b-60 gd-s-100">
	<div class="gd-60 gd-b-100" id="perfil_publi">
		<h4>Publicaciones</h4>
		{is_login}
		{is_owner}
		<form method="post" data-superior="0" action="?public_set" id="public_set">
			<textarea class="form-in" rows="5" placeholder="¿Que deseas compartir?" name="publi_content"></textarea>
			<div class="tx-right">
				<button class="btn btn-default">Publicar</button>
			</div>
		</form>
		{/is_owner}
		{/is_login}
		{no_publiclist}
		<div id="msg_nopubli" class="mg-sec">
			<div class="alert">
				<span class="icon-info"></span> &nbsp; Aún no hay publicaciones que mostrar
			</div>
		</div>
		{/no_publiclist}
		<div id="recentPubli"></div>
		{lista_publicaciones}
		<div class="mg-sec cont-white">
			<div id="publicel_{public_id}" class="container">
				<div class="gd-20 gd-m-100">
					<img class="coment_avatar" src="{public_avatar}">
				</div>
				<div class="gd-80 gd-m-100">
					<div class="coment_autor">
						<a href="{SITIO_URL}/@{public_autor}">@{public_autor}</a>
					</div>
					<div class="coment_fecha">{public_fecha}</div>
					<div class="coment_cont">{public_contenido}</div>
				</div>
				<div class="gd-100 tx-right">
					<button class="btn btn-default size-s coment_btn" data-destino="[data-id='{public_id}'].public_cont" data-superior="{public_id}">
						<span class="icon-bubble"></span> {public_total_coment} Comentarios
					</button>
					{is_owner}
					<button class="btn btn-default size-s" data-publicdel="{public_id}">
						<span class="icon-blocked"></span> Eliminar
					</button>
					{/is_owner}
				</div>
			</div>
			<div class="public_cont clearfix" data-id="{public_id}" data-state="closed" data-pagina="0">
				<form class="gd-100 form_public_set" method="post" data-superior="{public_id}" action="?public_set">
					<textarea class="form-in" rows="3" placeholder="Escribe tu comentario" name="publi_content"></textarea>
					<div class="tx-right">
						<button class="btn btn-default size-s">Comentar</button>
					</div>
				</form>
				<div data-superior="{public_id}" class="public_coment_list" class="clearfix"></div>
			</div>
		</div>
		{/lista_publicaciones}
	</div>
	<div class="gd-40 hide-b card-t2" id="pefil_post">
		<div class="card-mark">
			<h3><span class="icon-clock"></span> Ultimos temas agregados</h3>
			<ul>
				{lista_ultimos}
				<li><a href="{articulo_enlace}"><img src="{SITIO_URL}/miniatura?w=40&h=30&src={articulo_portada}"> {articulo_titulo}</a></li>
				{/lista_ultimos}
			</ul>
		</div>
		<div class="card-mark mg-sec">
			<h3><span class="icon-user-check"></span> Siguiendo</h3>
			<div class="flexcont">
				{lista_follows}
				<div class="">
				<a class="tx-center" href="{enlace}" title="Ir a perfil de @{nickname}"><img src="{avatar}"></a>
				</div>
				{/lista_follows}
			</div>
		</div>
		{is_owner}
		<div class="card-mark mg-sec">
			<h3><span class="icon-file-text"></span> Temas de usuarios que sigo</h3>
			<ul>
				{lista_eseguidos}
				<li><a href="{enlace}"><img src="{SITIO_URL}/miniatura?w=40&h=30&src={portada}"> {titulo}</a></li>
				{/lista_eseguidos}
			</ul>
		</div>
		{/is_owner}
	</div>
</div>
{include=tpl/html/perfil_footer}
