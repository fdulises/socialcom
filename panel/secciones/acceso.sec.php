<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( $user->logingCheck() ){
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
		else header('location: '.PANEL_PATH);
	}
	
	if( isset($_GET['iniciar']) ){
		$datos = array();
		$datos['usuario'] = ( isset($_POST['usuario']) ) ? $_POST['usuario'] : '';
		$datos['clave'] = ( isset($_POST['clave']) ) ? $_POST['clave'] : '';

		/*
		* La clave tiene que venir ya cifrada desde el frontend, en caso de que no lo este la ciframos ya que el sistema asi lo require
		*/
		if( !encrypt::sha512Validate($datos['clave']) )
			$datos['clave'] = hash('sha512', $datos['clave']);

		if( $user->acceso($datos) )
			echo json_encode(array('estado' => 1, 'error' => 0));
        else echo json_encode(array('estado' => 0, 'error' => $user->error));
		
        exit();
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta charset="utf-8" />
	<title>Listefi - Página de accceso</title>
	<link rel="stylesheet" href="tema/css/listefi-fuentes.css" />
	<link rel="stylesheet" href="tema/css/listefi.css" />
	<link rel="stylesheet" href="tema/css/listefi-tema.css" />
	<script src="tema/js/listefi.js"></script>
	<script src="tema/js/sha512.js"></script>
	<script src="tema/js/acceso.js"></script>
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
				<button class="btn size-l btn-primary d-block">Acceder</button>
			</div>
		</form>
		<div class="fpass-link">
			<a href="<?php echo sitio::getInfo('url'); ?>">Volver a <?php echo sitio::getInfo('titulo'); ?></a>
		</div>
	</div>
</body>
</html>