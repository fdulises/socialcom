{include=tpl/html/header}
	<style>
	#superior{
		background-image: url({usuario_cover});
	}
	</style>
	<div class="perfil_bar">
		<div class="flex-t2">
			<div class="gd- imglbg" id="avatar_op">
				<a href="{usuario_avatar}"><img src="{usuario_avatar}" id="perfil_avatar"></a>
				{IS_LOGIN}{no_owner}
				<button class="btn btn_follow hide-b" data-id="{usuario_id}" data-follow="{follow_tag}" data-followbtn="">Seguir</button>
				{/no_owner}{/IS_LOGIN}
			</div>
			<div class="gd- perfil_stats hide-m">
				<h5>@{usuario_nickname}</h5>
				<p>{usuario_nombre}</p>
			</div>
			<div class="gd- perfil_stats hide-m">
				<h5>Entradas</h5>
				<p>{usuario_entradas}</p>
			</div>
			<div class="gd- perfil_stats hide-m">
				<h5>Puntos</h5>
				<p>{usuario_experiencia}</p>
			</div>
			<div class="gd- perfil_stats hide-m">
				<h5>Seguidores</h5>
				<p>{usuario_seguidores}</p>
			</div>
		</div>
	</div>
	<article id="perfil" class="container">
		<div class="container">
			<div class="gd-20 gd-b-40 gd-s-100">
				{IS_LOGIN}{no_owner}
				<div class="hide-min-b mg-sec">
					<button class="btn btn-block" data-id="{usuario_id}" data-follow="{follow_tag}" data-followbtn="">Seguir</button>
				</div>
				{/no_owner}{/IS_LOGIN}
				{NO_LOGIN}
				<style>#perfil_nav{margin-top: 30px}</style>
				{/NO_LOGIN}
				{is_owner}
				<style>#perfil_nav{margin-top: 30px}</style>
				{/is_owner}
				<ul id="perfil_nav">
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}"><span class="icon-calendar"></span> Actividad</a>
					</li>
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}/info"><span class="icon-info"></span> Información</a>
					</li>
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}/entradas"><span class="icon-files-empty"></span> Entradas</a>
					</li>
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}/fotos"><span class="icon-image"></span> Fotos</a>
					</li>
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}/premios"><span class="icon-gift"></span> Premios</a>
					</li>
					<li>
						<a href="{SITIO_URL}/@{usuario_nickname}/medallas"><span class="icon-trophy"></span> Medallas</a>
					</li>
					{is_owner}<li>
						<a href="{SITIO_URL}/cuenta"><span class="icon-cog"></span> Ajustes</a>
					</li>{/is_owner}
					{no_owner}
					<button id="perfil_denuncia" class="btn size-s" data-id="{usuario_id}">
						<span class="icon-blocked"></span> Denunciar
					</button>
					{/no_owner}
				</ul>
			</div>