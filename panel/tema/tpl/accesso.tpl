<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Listefi - Página de accceso</title>
	<link rel="stylesheet" href="css/listefi-fuentes.css" />
	<link rel="stylesheet" href="css/listefi.css" />
	<link rel="stylesheet" href="css/listefi-tema.css" />
	<script src="js/listefi.js"></script>
	<script src="js/funciones.js"></script>
</head>
<body>
	<div class="container cont-400">
		<form id="login-form" method="post" action="admin.html">
			<h1 class="header-t1">Iniciar sesión</h1>
			<div class="cont-white">
				<input placeholder="Usuario" type="text" name="usuario" id="usuario" class="form-in" />
				<span class="icon icon-user form-decoration"></span>
				<input placeholder="Contraseña" type="password" name="clave" id="clave" class="form-in" />
				<span class="icon icon-key form-decoration"></span>
				<div class="form-check">
					<input type="checkbox" id="recuerdame" />
					<label for="recuerdame">Mantener sesión iniciada</label>
				</div>
				<button class="btn size-l btn-primary d-block">Acceder</button>
			</div>
		</form>
		<div class="fpass-link"><a href="#">Recuperar mi contraseña</a></div>
	</div>
</body>
</html>