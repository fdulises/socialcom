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
		$data = array();
		$result = false;

		if( isset( $_POST['nombre'], $_POST['tipo'], $_POST['codigo'] ) ){
			if( !empty($_POST['nombre']) && !empty($_POST['codigo']) && !empty($_POST['tipo']) ){
				$_POST['tipo'] = (INT) $_POST['tipo'];
				$result = adds::create([
					'nombre' => $_POST['nombre'],
					'codigo' => $_POST['codigo'],
					'tipo' => $_POST['tipo'],
					'estado' => 1,
				]);
			}else $error[] = 'faltan_campos';
		}else $error[] = 'faltan_campos';
		
		if( $result ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-addscrear" method="post" action="?guardar">
		<h1>Agregar banner publicitario</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" />
		</div>
		<div class="form-sec">
			<label for="codigo">Codigo</label>
			<textarea name="codigo" id="codigo" class="form-in"></textarea>
		</div>
		<div class="form-sec">
			<label for="tipo">Tamaño</label>
			<select name="tipo" id="tipo" class="form-in">
				<?php foreach($tipos_adds as $k => $v): ?>
				<option value="<?php echo $k ?>"><?php echo $v ?></option>
				<?php endforeach ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>