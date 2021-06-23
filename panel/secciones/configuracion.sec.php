<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Verificamos que el usuario tenga permiso para esta seccion
	if( !($user->getGrupo() == 1) ) event::fire('e404');
	
	//Procesamos formularios de actualizacion
	if( isset($_GET['action']) ){
		
		$result = array(
			'estado' => 0,
			'error' => array(),
		);
		$update = false;
		
		if( $_GET['action'] == 'sitio' ){
			$data = array();
			$data['titulo'] = isset($_POST['titulo']) ? $_POST['titulo'] : '';
			$data['descrip'] = isset($_POST['descrip']) ? $_POST['descrip'] : '';
			$data['url'] = isset($_POST['url']) ? $_POST['url'] : '';
			$data['email'] = isset($_POST['email']) ? $_POST['email'] : '';
			$data['conf_fbappid'] = isset($_POST['conf_fbappid']) ? $_POST['conf_fbappid'] : '';
			$update = sitio::updateInfo($data);
		}else if( $_GET['action'] == 'entrada' ){
			$data = array();
		
			$data['conf_coment'] = isset($_POST['conf_coment']) ? $_POST['conf_coment'] : '';
			$data['conf_pp_max'] = isset($_POST['conf_pp_max']) ? $_POST['conf_pp_max'] : '';
			$data['conf_pp_entrada'] = isset($_POST['conf_pp_entrada']) ? $_POST['conf_pp_entrada'] : '';
			$data['conf_pp_coment'] = isset($_POST['conf_pp_coment']) ? $_POST['conf_pp_coment'] : '';
			$update = sitio::updateInfo($data);
			
		}else if( $_GET['action'] == 'usuario' ){
			$data = array();
			
			$data['conf_intentos'] = isset($_POST['conf_intentos']) ? $_POST['conf_intentos'] : '';
			$data['conf_pp_registro'] = isset($_POST['conf_pp_registro']) ? $_POST['conf_pp_registro'] : '';
			$data['conf_pp_referido'] = isset($_POST['conf_pp_referido']) ? $_POST['conf_pp_referido'] : '';
			$update = sitio::updateInfo($data);
		
		}else if( $_GET['action'] == 'apariencia' ){
			$data = array();
			
			$data['tema_nombre'] = isset($_POST['tema_nombre']) ? $_POST['tema_nombre'] : '';
			$data['tema_url'] = isset($_POST['tema_url']) ? $_POST['tema_url'] : '';
			$data['tema_ext'] = isset($_POST['tema_ext']) ? $_POST['tema_ext'] : '';
			$update = sitio::updateInfo($data);
			
		}else if( $_GET['action'] == 'publicidad' ){
			$data = array();
			
			$data['field_add1'] = isset($_POST['field_add1']) ? $_POST['field_add1'] : '';
			$data['field_add2'] = isset($_POST['field_add2']) ? $_POST['field_add2'] : '';
			$update = sitio::updateInfo($data);
			
		}else if( $_GET['action'] == 'social' ){
			$data = array();
			
			$data['conf_code_f'] = isset($_POST['conf_code_f']) ? $_POST['conf_code_f'] : '';
			$data['conf_code_t'] = isset($_POST['conf_code_t']) ? $_POST['conf_code_t'] : '';
			$data['conf_link_f'] = isset($_POST['conf_link_f']) ? $_POST['conf_link_f'] : '';
			$data['conf_link_t'] = isset($_POST['conf_link_t']) ? $_POST['conf_link_t'] : '';
			$update = sitio::updateInfo($data);
		}else{
			event::fire('e404');
		}
		
		
		if( $update ) $result['estado'] = 1;
		print( json_encode( $result ) );
		exit();
	}
	
	
	$config = extras::htmlentities(sitio::getInfo());
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confsitio" method="post" action="?action=sitio">
		<h1>Configuración del sitio</h1>
		<div class="form-sec">
			<label for="titulo">Titulo</label>
			<input type="text" name="titulo" id="titulo" placeholder="El nombre del sitio" class="form-in" value="<?php echo $config['titulo']; ?>" />
		</div>
		<div class="form-sec">
			<label for="descrip">Descripción</label>
			<textarea name="descrip" id="descrip" placeholder="Una breve descripción" class="form-in"><?php echo $config['descrip']; ?></textarea>
		</div>
		<div class="form-sec">
			<label for="url">Dirección del sitio</label>
			<input type="text" name="url" id="url" placeholder="Una URL valida" class="form-in" value="<?php echo $config['url']; ?>" />
		</div>
		<div class="form-sec">
			<label for="email">E-mail del sitio</label>
			<input type="text" name="email" id="email" placeholder="Un correo valido" class="form-in" value="<?php echo $config['email']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_fbappid">Facebook App ID</label>
			<input type="text" name="conf_fbappid" id="conf_fbappid" class="form-in" value="<?php echo $config['conf_fbappid']; ?>" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confentrada" method="post" action="?action=entrada">
		<h2>Configuración de entradas</h2>
		<div class="form-sec">
			<label for="conf_pp_max">Limite de puntos por entrada</label>
			<input type="text" name="conf_pp_max" id="conf_pp_max" placeholder="Puntos que un usuario puede dar por entrada" class="form-in" value="<?php echo $config['conf_pp_max']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_pp_entrada">Puntos por entrada publicada</label>
			<input type="text" name="conf_pp_entrada" id="conf_pp_entrada" placeholder="Puntos que el autor gana por entrada publicada" class="form-in" value="<?php echo $config['conf_pp_entrada']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_coment">Ajustes de comentarios</label>
			<select name="conf_coment" id="conf_coment" class="form-in">
				<option value="0"<?php echo $config['conf_coment'] == 0 ? 'selected': ''; ?>>Desactivar comentarios</option>
				<option value="1"<?php echo $config['conf_coment'] == 1 ? 'selected': ''; ?>>Publicar sin revisar</option>
				<option value="2"<?php echo $config['conf_coment'] == 0 ? 'selected': ''; ?>>Pasar por revisón antes de publicar</option>
			</select>
		</div>
		<div class="form-sec">
			<label for="conf_pp_coment">Puntos por comentario publicado</label>
			<input type="text" name="conf_pp_coment" id="conf_pp_coment" placeholder="Puntos que el autor del comentario gana" class="form-in" value="<?php echo $config['conf_pp_coment']; ?>" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confusuario" method="post" action="?action=usuario">
		<h2>Configuración de usuarios</h2>
		<div class="form-sec">
			<label for="conf_intentos">Intentos de inicio de sesión permitidos</label>
			<input type="text" name="conf_intentos" id="conf_intentos" placeholder="" class="form-in" value="<?php echo $config['conf_intentos']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_pp_registro">Puntos por registrarse</label>
			<input type="text" name="conf_pp_registro" id="conf_pp_registro" placeholder="Puntos con los que empieza un nuevo usuario" class="form-in" value="<?php echo $config['conf_pp_registro']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_pp_referido">Puntos por usuario referido</label>
			<input type="text" name="conf_pp_referido" id="conf_pp_referido" placeholder="Puntos por lograr que alguien se registre" class="form-in" value="<?php echo $config['conf_pp_referido']; ?>" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confapariencia" method="post" action="?action=apariencia">
		<h2>Configuración de apariencia</h2>
		<div class="form-sec">
			<label for="tema_nombre">Nombre del tema</label>
			<input type="text" name="tema_nombre" id="tema_nombre" placeholder="default" class="form-in" value="<?php echo $config['tema_nombre']; ?>" />
		</div>
		<div class="form-sec">
			<label for="tema_url">URL del tema</label>
			<input type="text" name="tema_url" id="tema_url" placeholder="tema" class="form-in" value="<?php echo $config['tema_url']; ?>" />
		</div>
		<div class="form-sec">
			<label for="tema_ext">Extensión de archivos de tema</label>
			<input type="text" name="tema_ext" id="tema_ext" placeholder="tpl" class="form-in" value="<?php echo $config['tema_ext']; ?>" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confpublicidad" method="post" action="?action=publicidad">
		<h2>Configuración de publicidad</h2>
		<div class="form-sec">
			<label for="field_add1">Código para campo publicitario 720px/90px</label>
			<textarea name="field_add1" id="field_add1" class="form-in"><?php echo $config['field_add1']; ?></textarea>
		</div>
		<div class="form-sec">
			<label for="field_add2">Código para campo publicitario 400px/400px</label>
			<textarea name="field_add2" id="field_add2" class="form-in"><?php echo $config['field_add2']; ?></textarea>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
	<form class="container cont-700 cont-white mg-sec confform" id="form-confsocial" method="post" action="?action=social">
		<h2>Configuración de elementos sociales</h2>
		<div class="form-sec">
			<label for="conf_code_f">Código para boton Facebook Like de la página</label>
			<textarea name="conf_code_f" id="conf_code_f" class="form-in"><?php echo $config['conf_code_f']; ?></textarea>
		</div>
		<div class="form-sec">
			<label for="conf_code_t">Código para boton Twittear de la página</label>
			<textarea name="conf_code_t" id="conf_code_t" class="form-in"><?php echo $config['conf_code_t']; ?></textarea>
		</div>
		<div class="form-sec">
			<label for="conf_link_f">Enlace página de Facebook</label>
			<input type="text" name="conf_link_f" id="conf_link_f" class="form-in" value="<?php echo $config['conf_link_f']; ?>" />
		</div>
		<div class="form-sec">
			<label for="conf_link_t">Enlace perfil de Twitter</label>
			<input type="text" name="conf_link_t" id="conf_link_t" class="form-in" value="<?php echo $config['conf_link_t']; ?>" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>