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
		if( isset( $_POST['descrip'] ) ){
			$data['descrip'] = $_POST['descrip'];
		}
		
		$data['fecha'] = date('Y-m-d H:i:s');
			
		$result = entrada::catCreate( $data );
		
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-catcreate" method="post" action="?guardar">
		<h1>Agregar categoría</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" />
		</div>
		<div class="form-sec">
			<label for="url">Slug</label>
			<input type="text" name="url" id="url" class="form-in" />
		</div>
		<div class="form-sec">
			<label for="descrip">Descripción</label>
			<textarea name="descrip" id="descrip" class="form-in"></textarea>
		</div>
		<button type="submit" class="btn btn-primary size-l">Enviar</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>,