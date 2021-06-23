<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>{SITIO_TITULO} - {pagina_titulo}</title>
	<link rel="stylesheet" href="{TEMA_URL}/css/listefi-fuentes.css" />
	<link rel="stylesheet" href="{TEMA_URL}/css/listefi.css" />
	<link rel="stylesheet" href="{TEMA_URL}/estilos.css" />
	<script>
	var SITIO_URL = '{SITIO_URL}';
	var SITIO_SEC = '{SITIO_SEC}';
	</script>
	<script src="{TEMA_URL}/js/listefi.js"></script>
	<script src="{TEMA_URL}/js/sha512.js"></script>
	<script src="{TEMA_URL}/js/acceso.js"></script>
</head>
<body>
	<div class="container cont-400">
		<form id="login-form" method="post" action="?iniciar">
			<h1 class="header-t1">Iniciar sesión</h1>
			<div class="cont-white">
				<input placeholder="Usuario" type="text" name="usuario" id="usuario" class="form-in" />
				<span class="icon icon-user form-decoration"></span>
				<input placeholder="Contraseña" type="password" name="clave" id="clave" class="form-in" />
				<span class="icon icon-key form-decoration"></span>
				<div class="form-sec">
					<button class="btn size-l btn-primary d-block">Acceder</button>
				</div>
				<div class="form-sec">
					<button type="button" id="facebook-login-button" appId="{conf_fbappid}" class="btn size-l d-block"><span class="icon-facebook2"></span> Acceder vía Facebook</button>
				</div>
			</div>
		</form>
		<div class="fpass-link"><a href="registro">Aún no tengo una cuenta, crear una.</a></div>
		<div class="fpass-link"><a href="{SITIO_URL}">Volver a {SITIO_TITULO}</a></div>
	</div>
	<script src="{TEMA_URL}/js/facebookLogin.js"></script>
</body>
</html>
