<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{SITIO_TITULO} - {pagina_titulo}</title>
	
	<meta property="og:url"                content="{pagina_enlace}" />
	<meta property="og:type"               content="article" />
	<meta property="og:title"              content="{pagina_titulo}" />
	<meta property="og:description"        content="{pagina_descrip}" />
	<meta property="og:image"              content="{pagina_cover}" />
	
	<link rel="stylesheet" href="{TEMA_URL}/css/listefi-fuentes.css" />
	<link rel="stylesheet" href="{TEMA_URL}/css/listefi.min.css" />
	<link rel="stylesheet" href="{TEMA_URL}/css/baguetteBox.min.css" />
	<link rel="stylesheet" href="{TEMA_URL}/estilos.min.css" />
	<link rel="icon" href="{SITIO_URL}/favicon.ico" />
	<script>
	var SITIO_URL = '{SITIO_URL}';
	var SITIO_SEC = '{SITIO_SEC}';
	var SITIO_LOGIN = {SITIO_LOGIN};
	var PAGINA_TIPO = {PAGINA_TIPO};
	var IS_ENTRADA = {IS_ENTRADA};
	var USERID = {S_USERID};
	var USERNAME = '{S_USERNAME}';
	</script>
</head>
<body>
	<header id="superior">
		<div class="nav-bar nav-primary">
			<div class="container">
				<div class="bx-left" id="socialbtns">{conf_code_t}{conf_code_f}</div>
				<button type="button" class="btn-sadw hide-min-m" id="actionNav">
					<span class="line"></span>
					<span class="line"></span>
					<span class="line"></span>
				</button>
				<ul id="sup-nav" class="bx-right">
					<li>
						<form id="nav-search" method="get" action="{SITIO_URL}/busqueda">
							<button id="nav-search-btn" type="button"><span class="icon-search"></span></button>
							<input type="text" name="b" id="nav-search-in" placeholder="Buscar" />
						</form>
					</li>
					{NO_LOGIN}
					<li><a href="{SITIO_URL}/acceso"><span class="icon-key"></span> Iniciar Sesión</a></li>
					<li><a href="{SITIO_URL}/registro"><span class="icon-user"></span> Crear cuenta</a></li>
					{/NO_LOGIN}
					{IS_LOGIN}
					{MIN_GROUP_5}<li><a href="{SITIO_URL}/post"><span class="icon-plus"></span> Crear Post</a></li>
					{/MIN_GROUP_5}
					{MIN_GROUP_4}
					<li><a href="{SITIO_URL}/panel"><span class="icon-home3"></span> Panel</a></li>
					{/MIN_GROUP_4}
					<li><a href="{SITIO_URL}/@{S_USERNAME}"><span class="icon-user"></span> Perfil</a></li>
					<li><a href="{SITIO_URL}/salir"><span class="icon-exit"></span> Salir</a></li>
					{/IS_LOGIN}
				</ul>
			</div>
		</div>
		<div id="cabecera" class="container">
			<div class="gd-30 gd-b-100">
				<a id="logotipo" href="{SITIO_URL}">{SITIO_TITULO}</a>
			</div>
			<div id="baner-sup" class="gd-70 gd-b-100 hide-m">{field_add1}</div>
		</div>
	</header>
