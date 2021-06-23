<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Obtenemos el id del usuario a editar
	$actualid = (INT) routes::$params[1];
	
	//Verificamos que el usuario tenga permiso para esta seccion
	$userGrupo = $user->getGrupo();
	if( !($userGrupo == 1 || $userGrupo == 2) ) event::fire('e404');
	
	//Restringimos la edicion de perfiles admin y super admin a solo admin
	$hasPermiss = 0;
	$grupoActualid = $user->getGrupo($actualid);
	$condGrupoactual = ( $grupoActualid == 1 || $grupoActualid == 2 );
	if( ( $condGrupoactual && $userGrupo == 1 ) || !$condGrupoactual ){
		$hasPermiss = 1;
	}else if( $condGrupoactual && ($userGrupo != 1) ){
		if( $_SESSION[S_USERID] == $actualid ) $hasPermiss = 1;
	}
	
	//Obtenemos los datos del usuario a editar
	$actualudata = extras::htmlentities($user->get([
		'id' => $actualid,
		'columns' => [
			'u.id',
			'u.nickname',
			'u.email',
			'u.grupo',
			'u.estado',
			'p.nombre',
			'p.sexo',
			'p.nacimiento',
			'p.descrip',
			'p.puntos',
			'p.s_facebook',
		]
	]));
	
	//Si no se encuentra el usuario mostramos la pagina de error
	if( !count($actualudata) ) event::fire('e404');
	
	//Procesamos formulario de guardado
	if( isset($_GET['guardar']) ){
		$estado = 0;
		$error = array();
		$resultado = false;
		$data = array();
		$result = false;
		
		if( isset( $_POST['nickname'] ) ){
			$validacion = $user->validaUsuario($_POST['nickname']);
			if( $validacion == 2  ) $data['nickname'] = $_POST['nickname'];
			else $error[] = 'nickname_incorrecto';
		}
		if( isset( $_POST['nombre'] ) ){
			$data['nombre'] = $_POST['nombre'];
		}
		if( isset( $_POST['email'] ) ){
			$validacion = $user->validaUsuario($_POST['email']);
			if( $validacion == 1  ) $data['email'] = $_POST['email'];
			else $error[] = 'email_incorrecto';
		}
		if( isset( $_POST['descrip'] ) ){
			$data['descrip'] = $_POST['descrip'];
		}
		if( isset( $_POST['sexo'] ) ){
			$data['sexo'] = (INT) $_POST['sexo'];
			if( $data['sexo'] < 0 || $data['sexo'] > 2 )
				$data['sexo'] = 0;
		}
		if( isset( $_POST['s_facebook'] ) ){
			$data['s_facebook'] = $_POST['s_facebook'];
			if( !filter_var($_POST['s_facebook'], FILTER_VALIDATE_URL) && !empty($_POST['s_facebook']) ){
				$error[] = 'facebook_incorrecto';
				unset($data['s_facebook']);
			}
		}
		if( isset( $_POST['puntos'] ) ){
			$data['puntos'] = (INT) $_POST['puntos'];
		}
		
		if($userGrupo == 1):
			if( isset( $_POST['grupo'] ) ){
				$_POST['grupo'] = (INT) $_POST['grupo'];
				if( isset($usergroup[$_POST['grupo']]) )
					$data['grupo'] = (INT) $_POST['grupo'];
			}
		endif;
		
		if( isset( $_POST['estado'] ) ){
			$_POST['estado'] = (INT) $_POST['estado'];
			if( isset($userstate[$_POST['estado']]) )
				$data['estado'] = (INT) $_POST['estado'];
		}
			
		if( $hasPermiss ) $result = $user->update( $actualid, $data );
		
		if( !count($error) ){
			if( $result ) $estado = 1;
			else $error[] = 'NO_PERMITIDO';
		}
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-useredit" method="post" action="?guardar">
		<h1>Editar datos de usuario</h1>
		<div class="form-sec">
			<label for="nickname">Nickname</label>
			<input type="text" name="nickname" id="nickname" class="form-in" value="<?php echo $actualudata['nickname']; ?>" />
		</div>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" value="<?php echo $actualudata['nombre']; ?>" />
		</div>
		<div class="form-sec">
			<label for="email">E-mail</label>
			<input type="text" name="email" id="email" class="form-in" value="<?php echo $actualudata['email']; ?>" />
		</div>
		<div class="form-sec">
			<label for="descrip">Descripción</label>
			<textarea name="descrip" id="descrip" class="form-in"><?php echo $actualudata['descrip']; ?></textarea>
		</div>
		<div class="form-sec">
			<label for="sexo">Sexo</label>
			<select name="sexo" id="sexo" class="form-in">
				<option value="0"<?php echo $actualudata['sexo'] == 0 ? ' selected' : ''?>>Sin definir</option>
				<option value="1"<?php echo $actualudata['sexo'] == 1 ? ' selected' : ''?>>Hombre</option>
				<option value="2"<?php echo $actualudata['sexo'] == 2 ? ' selected' : ''?>>Mujer</option>
			</select>
		</div>
		<div class="form-sec">
			<label for="s_facebook">Facebook</label>
			<input type="text" name="s_facebook" id="s_facebook" class="form-in" value="<?php echo $actualudata['s_facebook']; ?>" />
		</div>
		<div class="form-sec">
			<label for="puntos">Puntos</label>
			<input type="text" name="puntos" id="puntos" class="form-in" value="<?php echo $actualudata['puntos']; ?>" />
		</div>
		<?php if($userGrupo == 1): ?>
		<div class="form-sec">
			<label for="grupo">Grupo</label>
			<select name="grupo" id="grupo" class="form-in">
				<?php foreach($usergroup as $k => $v):
					$selected = $actualudata['grupo'] == $k ? 'selected' : '';
				?>
				<option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php endif; ?>
		<div class="form-sec">
			<label for="estado">Estado</label>
			<select name="estado" id="estado" class="form-in">
				<?php foreach($userstate as $k => $v):
					$selected = $actualudata['estado'] == $k ? 'selected' : '';
				?>
				<option value="<?php echo $k; ?>" <?php echo $selected; ?>><?php echo $v; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>,