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

		if( isset( $_POST['nombre'], $_POST['precio'], $_FILES['cover'] ) ){
			if( 0 == $_FILES['cover']['error'] ){
				//Procesamos imagen de producto
				$dir = MEDIA_TIENDA_DIR.'/'.basename($_FILES['cover']['name']);
				if(move_uploaded_file($_FILES['cover']['tmp_name'], "../{$dir}")){
					//Procesamos datos del producto
					$result = tienda::create([
						'nombre' => $_POST['nombre'],
						'precio' => $_POST['precio'],
						'tipo' => 1,
						'cover' => basename($_FILES['cover']['name']),
					]);
				}else $error[] = 'subida_cover';
			}else $error[] = 'subida_cover';
		}else $error[] = 'faltan_campos';
		
		if( $result ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-tiendacrear" method="post" action="?guardar" enctype="multipart/form-data">
		<h1>Agregar producto</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" />
		</div>
		<div class="form-sec">
			<label for="precio">Precio</label>
			<input type="text" name="precio" id="precio" class="form-in" />
		</div>
		<div class="form-sec">
			<label for="cover">Cover</label>
			<input type="file" name="cover" id="cover" class="form-in" />
		</div>
		<button type="submit" class="btn btn-primary size-l">Guardar Cambios</button>
	</form>
<?php require 'secciones/footer.tpl'; ?>