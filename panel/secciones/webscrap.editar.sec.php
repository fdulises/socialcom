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
	
	//Obtenemos el id dla fuente a editar
	$actualid = (INT) routes::$params[1];
	
	//Obtenemos los datos a editar
	$actualudata = extras::htmlentities(webscrap::get([
		'id' => $actualid,
		'columns' => [
			'id',
			'nombre',
			'url',
			'estado',
		]
	]));
	
	//Si no se encuentra la fuente mostramos la pagina de error
	if( !count($actualudata) ) event::fire('e404');
	
	//Procesamos formulario de guardado
	if( isset($_GET['guardar']) ){
		$estado = 0;
		$error = array();
		$resultado = false;
		$data = array();
		
		if( isset( $_POST['nombre'] ) ){
			$data['nombre'] = $_POST['nombre'];
		}
		if( isset( $_POST['url'] ) ){
			$data['url'] = $_POST['url'];
		}
		if( isset( $_POST['estado'] ) ){
			$data['estado'] = $_POST['estado'];
		}
					
		$result = webscrap::update( $actualid, $data );
		
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-webscrapeditar" method="post" action="?guardar">
		<h1>Editar fuente webscrap</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" value="<?php echo $actualudata['nombre'] ?>" />
		</div>
		<div class="form-sec">
			<label for="url">URL de la fuente</label>
			<input type="text" name="url" id="url" class="form-in" value="<?php echo $actualudata['url'] ?>" />
		</div>
		<div class="form-sec">
			<label for="estado">Estado</label>
			<select name="estado" id="estado" class="form-in">
				<option value="1"<?php if($actualudata['estado'] == 1) echo " selected" ?>>Activo</option>
				<option value="2"<?php if($actualudata['estado'] == 2) echo " selected" ?>>Pausado</option>
				<option value="0"<?php if($actualudata['estado'] == 0) echo " selected" ?>>Eliminado</option>
			</select>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>,