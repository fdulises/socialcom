<?php

	session_start();

	require 'inc/config.php';
	require 'inc/DBConnector.php';
	require 'inc/funciones.php';
	
	$step = isset($_GET['step']) ? $_GET['step'] : 0;
	$estado = 1;
	
	$_SESSION['db_host'] = isset($_SESSION['db_host']) ? $_SESSION['db_host'] : 'localhost';
	$_SESSION['db_user'] = isset($_SESSION['db_user']) ? $_SESSION['db_user'] : 'root';
	$_SESSION['db_pass'] = isset($_SESSION['db_pass']) ? $_SESSION['db_pass'] : '';
	$_SESSION['db_name'] = isset($_SESSION['db_name']) ? $_SESSION['db_name'] : CMS_DB_NAME;
	$_SESSION['db_pref'] = isset($_SESSION['db_pref']) ? $_SESSION['db_pref'] : CMS_DB_PREF;
	
	$_SESSION['site_title'] = isset($_SESSION['site_title']) ? $_SESSION['site_title'] : SITE_NAME;
	$_SESSION['site_url'] = isset($_SESSION['site_url']) ? $_SESSION['site_url'] : '';
	$_SESSION['site_email'] = isset($_SESSION['site_email']) ? $_SESSION['site_email'] : '';
	
	$_SESSION['user_name'] = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
	$_SESSION['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
	$_SESSION['user_pass'] = isset($_SESSION['user_pass']) ? $_SESSION['user_pass'] : '';
	
	if( $step == 1 ){
		//Configuracion de la base de datos
		if( isset( $_POST['db_host'], $_POST['db_user'], $_POST['db_pass'], $_POST['db_name'], $_POST['db_pref'] ) ){
			$_SESSION['db_host'] = $_POST['db_host'];
			$_SESSION['db_user'] = $_POST['db_user']; 
			$_SESSION['db_pass'] = $_POST['db_pass'];
			$_SESSION['db_name'] = $_POST['db_name'];
			$_SESSION['db_pref'] = $_POST['db_pref'];
			
			//Realizamos conexion con los datos indicados
			@DBConnector::connect(
				$_SESSION['db_host'], $_SESSION['db_user'],  $_SESSION['db_pass'], $_SESSION['db_name']
			);
			if( DBConnector::$conexion->connect_errno > 0 ) $estado = 0;
			else{
				//En caso de exito procedemos creando las tablas
				$consultas = file_get_contents('inc/consultas.sql');
				preg_match_all('/(.*?);/is', $consultas, $consultas);
				$consultas = $consultas[0];
				$consultas = str_replace(
					'pref_', $_SESSION['db_pref'], $consultas
				);
				foreach( $consultas as $v ){
					$result = DBConnector::sendQuery($v);
					if( !$result ){ $estado = 2; break; }
				}
				
				//Creamos el archivo de configuracion
				$configfile = file_get_contents('inc/'.CMS_CONFIG_FILE);
				$remplazos = array(
					'%DB_HOST%' => $_SESSION['db_host'],
					'%DB_USER%' => $_SESSION['db_user'],
					'%DB_PASS%' => $_SESSION['db_pass'],
					'%DB_NAME%' => $_SESSION['db_name'],
					'%DB_PREF%' => $_SESSION['db_pref'],
				);
				$configfile = str_replace(
					array_keys($remplazos),array_values($remplazos),$configfile
				);
				file_put_contents(CMS_CONFIG_DIR.CMS_CONFIG_FILE, $configfile);
				
				//En caso de exito pasamos al siguiente paso
				if( $estado == 1 ) header('location: index.php?step=2');
			}
		}
	}else if( $step == 2 ){
		//Configuracion de los datos del sitio		
		if( isset(
			$_POST['site_title'], $_POST['site_url'], $_POST['site_email']
			) ){
				
			$_SESSION['site_title'] = $_POST['site_title'];
			$_SESSION['site_url'] = $_POST['site_url']; 
			$_SESSION['site_email'] = $_POST['site_email'];
		
			//Realizamos conexion con los datos indicados
			@DBConnector::connect(
				$_SESSION['db_host'], $_SESSION['db_user'],  $_SESSION['db_pass'], $_SESSION['db_name']
			);
			if( DBConnector::$conexion->connect_errno > 0 ) $estado = 0;
			else{
				//Guardamos los datos de configuracion del sitio
				$tabla = $_SESSION['db_pref'].'sitio';
				$result = DBConnector::sendQuery("SELECT id FROM {$tabla}");
				if( DBConnector::numRows($result) ){	
					$sql = "UPDATE {$tabla} SET 
						titulo = '{$_SESSION['site_title']}',
						url = '{$_SESSION['site_url']}',
						email = '{$_SESSION['site_email']}',
						cms_info = '".CMS_NAME."',
						cms_v = '".CMS_VER."',
						cms_a = '".CMS_DATE."
					)";
				}else{
					$sql = "INSERT INTO {$tabla}(
						titulo, url, email, cms_info, cms_v, cms_a
					) VALUES(
						'{$_SESSION['site_title']}',
						'{$_SESSION['site_url']}',
						'{$_SESSION['site_email']}',
						'".CMS_NAME."', '".CMS_VER."', '".CMS_DATE."'
					)";
				}

				$result = DBConnector::sendQuery($sql);
				if( !$result ) $estado = 0;
			}
			
			//En caso de exito pasamos al siguiente paso
			if( $estado == 1 ) header('location: index.php?step=3');
		}
	}else if( $step == 3 ){
		//Configuracion de los datos del administrador	
		if( isset(
			$_POST['user_name'], $_POST['user_email'], $_POST['user_pass']
			) ){
				
			$_SESSION['user_name'] = $_POST['user_name'];
			$_SESSION['user_email'] = $_POST['user_email']; 
			$_SESSION['user_pass'] = $_POST['user_pass'];
		
			//Realizamos conexion con los datos indicados
			@DBConnector::connect(
				$_SESSION['db_host'], $_SESSION['db_user'],  $_SESSION['db_pass'], $_SESSION['db_name']
			);
			if( DBConnector::$conexion->connect_errno > 0 ) $estado = 0;
			else{
				//Preparamos los datos de configuracion del usuario
				$fregistro = date('Y-m-d');
				$salt = randomSalt();
				$clave = generateSPass($_SESSION['user_pass'], $salt);
				$ip = $_SERVER['REMOTE_ADDR'];
				
				//Guardamos los datos de configuracion del usuario
				$tabla = $_SESSION['db_pref'].'usuario';
				$tabla_perfil = $_SESSION['db_pref'].'perfil';
				$sql = "INSERT INTO {$tabla}(
					nickname, email, clave, salt, fregistro, ip, estado, grupo
				) VALUES(
					'{$_SESSION['user_name']}',
					'{$_SESSION['user_email']}',
					'{$clave}',
					'{$salt}',
					'{$fregistro}',
					'{$ip}',
					1,
					1
				)";
				$result = DBConnector::sendQuery($sql);
				if( $result ) DBConnector::sendQuery("
					INSERT INTO {$tabla_perfil}(nombre)
					VALUES('{$_SESSION['user_name']}')
				");
				else $estado = 0;
			}
			
			//En caso de exito pasamos al siguiente paso
			if( $estado == 1 ) header('location: index.php?step=4');
		}
	}else if( $step == 4 ){
		
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Instalación - <?php echo CMS_NAME; ?></title>
	<link rel="stylesheet" href="css/listefi-fuentes.css" />
	<link rel="stylesheet" href="css/listefi.css" />
	<link rel="stylesheet" href="css/estilos.css" />
	<script src="js/listefi.js"></script>
</head>
<body>
	<?php if( $step == 0 ): ?>
	<div class="container cont-700 mg-sec">
		<h1 class="header-t1">Asistente de instalación</h1>
		<div class="cont-white">
			<p>Bienvenido al asistente de instalación de <strong><?php echo CMS_NAME; ?> <?php echo CMS_VER; ?></strong></p>
			<p>La instalación es rapida y sencilla.</p>
			<p>Es necesario asegurarse que se cuenta con los requerimientos que aparecen a continuación, despues siga los pasos del asistente para poder configurar y comenzar a usar el sofware dentro de su sitio.</p>
			<h3>Requerimientos</h3>
			<ul>
				<li>PHP > 5.4</li>
				<li>Base de datos MySQL</li>
				<li>
					Permisos de escritura CHMOD 777
					<ul>
						<li>Carpeta cache</li>
						<li>Carpeta media y carpetas internas</li>
						<li>Archivo config/conf_db.php</li>
					</ul>
				</li>
			</ul>
			<a href="?step=1"><button class="btn size-l btn-primary">Comenzar</button></a>
		</div>
	</div>
	<?php elseif( $step == 1 ): ?>
	<div class="container cont-700">	
		<form id="form-db" class="mg-sec" method="post" action="?step=1">
			<h2 class="header-t1">Paso 1 - Base de datos</h2>
			<div class="cont-white">
				<?php if( $estado == 0 ): ?>
				<div class="alert alert-error">
					<strong>Error:</strong> No se puede establecer conexión con los datos ingresados.
				</div>
				<?php elseif( $estado == 2 ): ?>
				<div class="alert alert-error">
					<strong>Error:</strong> No se pudierón crear las tablas.
				</div>
				<?php endif; ?>
				<div class="form-sec">
					<label for="db_host">Servidor</label>
					<input placeholder="" type="text" name="db_host" id="db_host" class="form-in" value="<?php echo $_SESSION['db_host']; ?>" />
				</div>
				<div class="form-sec">
					<label for="db_user">Nombre de usuario</label>
					<input placeholder="" type="text" name="db_user" id="db_user" class="form-in" value="<?php echo $_SESSION['db_user']; ?>" />
				</div>
				<div class="form-sec">
					<label for="db_pass">Contraseña</label>
					<input placeholder="" type="text" name="db_pass" id="db_pass" class="form-in" value="<?php echo $_SESSION['db_pass']; ?>" />
				</div>
				<div class="form-sec">
					<label for="db_name">Nombre de la base de datos</label>
					<input placeholder="" type="text" name="db_name" id="db_name" class="form-in" value="<?php echo $_SESSION['db_name']; ?>" />
				</div>
				<div class="form-sec">
					<label for="db_pref">Prefijo de tabla</label>
					<input placeholder="" type="text" name="db_pref" id="db_pref" class="form-in" value="<?php echo $_SESSION['db_pref']; ?>" />
				</div>
				<button class="btn size-l btn-primary">Siguiente</button>
			</div>
		</form>
	</div>
	<?php elseif( $step == 2 ): ?>
	<div class="container cont-700">	
		<form id="form-sitio" class="mg-sec" method="post" action="?step=2">
			<h2 class="header-t1">Paso 2 - Datos del sitio</h2>
			<div class="cont-white">
				<div class="form-sec">
					<label for="site_title">Titulo del sitio</label>
					<input placeholder="" type="text" name="site_title" id="site_title" class="form-in" value="<?php echo $_SESSION['site_title']; ?>" />
				</div>
				<div class="form-sec">
					<label for="site_url">Dirección del sitio (Sin la última /)</label>
					<input placeholder="" type="text" name="site_url" id="site_url" class="form-in" value="<?php echo $_SESSION['site_url']; ?>" />
				</div>
				<div class="form-sec">
					<label for="site_email">E-mail del sitio o del administrador</label>
					<input placeholder="" type="text" name="site_email" id="site_email" class="form-in" value="<?php echo $_SESSION['site_email']; ?>" />
				</div>
				<button class="btn size-l btn-primary">Siguiente</button>
			</div>
		</form>
	</div>
	<?php elseif( $step == 3 ): ?>
	<div class="container cont-700">	
		<form id="form-admin" class="mg-sec" method="post" action="?step=3">
			<h2 class="header-t1">Paso 3 - Datos del administrador</h2>
			<div class="cont-white">
				<div class="form-sec">
					<label for="user_name">Nombre de usuario</label>
					<input placeholder="" type="text" name="user_name" id="user_name" class="form-in" value="<?php echo $_SESSION['user_name']; ?>" />
				</div>
				<div class="form-sec">
					<label for="user_email">E-mail</label>
					<input placeholder="" type="text" name="user_email" id="user_email" class="form-in" value="<?php echo $_SESSION['user_email']; ?>" />
				</div>
				<div class="form-sec">
					<label for="user_pass">Contraseña</label>
					<input placeholder="" type="password" name="user_pass" id="user_pass" class="form-in" value="<?php echo $_SESSION['user_pass']; ?>" />
				</div>
				<button class="btn size-l btn-primary">Siguiente</button>
			</div>
		</form>
	</div>
	<?php else: ?>
	<div class="container cont-700 mg-sec tx-center">
		<h1 class="header-t1">Instalación completada</h1>
		<div class="cont-white">
			<p>Se ha completado la instalación del software y el sitio ya esta listo.</p>
			<p>Ya puedes acceder al panel de administración con los datos que ingresaste.</p>
			<p>Por seguridad <strong>la carpeta de instalación se debe eliminar</strong>.</p>
			<p><a href="../panel"><button class="btn size-l btn-primary">Acceder al panel</button></a></p>
		</div>
	</div>
	<?php endif; ?>
</body>
</html>