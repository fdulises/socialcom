<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Verificamos que el usuario tenga permiso para esta seccion
	if( !($user->getGrupo() <= 3) ) event::fire('e404');
	
	//Obtenemos el id del comentario a editar
	$actualid = (INT) routes::$params[1];
	
	//Obtenemos los datos a editar
	$actualudata = extras::htmlentities(coment::get([
		'id' => $actualid,
		'columns' => [
			'id',
			'contenido',
			'estado',
		]
	]));
	
	//Si no se encuentra el comentario mostramos la pagina de error
	if( !count($actualudata) ) event::fire('e404');
	
	//Procesamos formulario de guardado
	if( isset($_GET['guardar']) ){
		$estado = 0;
		$error = array();
		$resultado = false;
		$data = array();
		
		if( isset( $_POST['contenido'] ) ){
			$data['contenido'] = $_POST['contenido'];
		}
		if( isset( $_POST['estado'] ) ){
			$data['estado'] = $_POST['estado'];
		}
		
		$result = coment::update( $actualid, $data );
		
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-comentarioseditar" method="post" action="?guardar">
		<h1>Editar comentario #<?php echo $actualudata['id'] ?></h1>
		<div class="form-sec">
			<label for="contenido">Contenido</label>
			<textarea name="contenido" id="contenido" class="form-in"><?php echo $actualudata['contenido'] ?></textarea>
		</div>
		<div class="form-sec">
			<label for="estado">Estado</label>
			<select name="estado" id="estado" class="form-in" data-selected="<?php echo $actualudata['estado'] ?>">
				<option value="2">Sin aprobar</option>
				<option value="1">Aprobado</option>
				<option value="0">Eliminado</option>
			</select>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>,