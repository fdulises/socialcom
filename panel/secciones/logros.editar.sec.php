<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Verificamos que el usuario tenga permiso para esta seccion
	if( !($user->getGrupo() == 1 || $user->getGrupo() == 2) ) event::fire('e404');
	
	//Obtenemos el id del logro a editar
	$actualid = (INT) routes::$params[1];
	
	//Obtenemos los datos a editar
	$actualudata = extras::htmlentities(logros::get([
		'id' => $actualid,
		'columns' => [
			'id',
			'nombre',
			'modo',
			'cover',
			'accion',
			'estado',
		]
	]));
	
	//Si no se encuentra el logro mostramos la pagina de error
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
		if( isset( $_POST['modo'] ) ){
			$data['modo'] = $_POST['modo'];
		}
		if( isset( $_POST['accion'] ) ){
			$data['accion'] = $_POST['accion'];
		}
		if( isset( $_POST['estado'] ) ){
			$data['estado'] = $_POST['estado'];
		}
		
		if( isset( $_FILES['cover'] ) ){
			if( 0 == $_FILES['cover']['error'] ){
				//Procesamos imagen de logro
				$dir = MEDIA_LOGROS_DIR.'/'.basename($_FILES['cover']['name']);
				if(move_uploaded_file($_FILES['cover']['tmp_name'], "../{$dir}")){
					$data['cover'] = basename($_FILES['cover']['name']);
				}
			}
		}
			
		$result = logros::update( $actualid, $data );
		
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		
		exit();
	}
	
	require 'secciones/header.tpl';
?>
	<form class="container cont-700 cont-white mg-sec" id="form-logroseditar" method="post" action="?guardar">
		<h1>Editar logro</h1>
		<div class="form-sec">
			<label for="nombre">Nombre</label>
			<input type="text" name="nombre" id="nombre" class="form-in" value="<?php echo $actualudata['nombre'] ?>" />
		</div>
		<div class="form-sec">
			<label for="modo">Modo de asignación</label>
			<select name="modo" id="modo" class="form-in">
				<option value="1"<?php if($actualudata['modo']==1)echo 'selected'; ?>>Automática</option>
				<option value="2"<?php if($actualudata['modo']==2)echo 'selected'; ?>>Manual</option>
			</select>
		</div>
		<div class="form-sec">
			<div><label for="cover">Cover</label></div>
			<div class="gd-80 gd-s-100">
				<input type="file" name="cover" id="cover" class="form-in" />
			</div>
			<div id="imgprev" class="gd-20 gd-s-100">
				<img src="<?php echo sitio::getInfo('url').'/'.MEDIA_LOGROS_DIR.'/'.$actualudata['cover'] ?>" />
			</div>
		</div>
		<div class="form-sec clearfix">
			<label for="accion">Puntos para activación</label>
			<input type="text" name="accion" id="accion" class="form-in" value="<?php echo $actualudata['accion'] ?>" />
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