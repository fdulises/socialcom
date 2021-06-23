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
	
	//Obtenemos el id del producto a editar
	$actualid = (INT) routes::$params[1];
	
	//Obtenemos los datos a editar
	$actualudata = extras::htmlentities(tienda::get([
		'id' => $actualid,
		'columns' => [
			'id',
			'nombre',
			'precio',
			'cover',
			'estado',
		]
	]));
	
	//Si no se encuentra el producto mostramos la pagina de error
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
		if( isset( $_POST['precio'] ) ){
			$data['precio'] = $_POST['precio'];
		}
		if( isset( $_POST['estado'] ) ){
			$data['estado'] = $_POST['estado'];
		}
		
		if( isset( $_FILES['cover'] ) ){
			if( 0 == $_FILES['cover']['error'] ){
				//Procesamos imagen de producto
				$dir = MEDIA_TIENDA_DIR.'/'.basename($_FILES['cover']['name']);
				if(move_uploaded_file($_FILES['cover']['tmp_name'], "../{$dir}")){
					$data['cover'] = basename($_FILES['cover']['name']);
				}
			}
		}
			
		$result = tienda::update( $actualid, $data );
		
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-tiendaeditar" method="post" action="?guardar">
		<h1>Editar producto</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" value="<?php echo $actualudata['nombre'] ?>" />
		</div>
		<div class="form-sec">
			<label for="precio">Precio</label>
			<input type="text" name="precio" id="precio" class="form-in" value="<?php echo $actualudata['precio'] ?>" />
		</div>
		<div class="form-sec">
			<label for="cover">Cover</label>
			<input type="file" name="cover" id="cover" class="form-in" />
			<div id="imgprev">
				<img src="<?php echo sitio::getInfo('url').'/'.MEDIA_TIENDA_DIR.'/'.$actualudata['cover'] ?>" />
			</div>
		</div>
		<div class="form-sec">
			<label for="estado">Estado</label>
			<select name="estado" id="estado" class="form-in">
				<option value="1">Activo</option>
				<option value="0">Eliminado</option>
			</select>
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>,