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
	
	//Procesamos aprobacion de pedidosario
	if( isset($_GET['aprobar'], $_GET['valor']) ){
		$_GET['aprobar'] = (INT) $_GET['aprobar'];
		$_GET['valor'] = (INT) $_GET['valor'];
		
		$result = pedidos::update($_GET['aprobar'], [
			'estado' => $_GET['valor'],
		]);
		echo json_encode(['estado'=>$result]);
		exit();
	}
	
	//Definimos los datos necesarios para la paginacion
	$per_page = 20;
	$current_page = isset($_GET['pagina']) ? (INT) $_GET['pagina'] : 1;
	if( $current_page < 1 ) $current_page = 1; 
	$offset = $per_page*($current_page-1);
	
	//Obtenemos lista de pedidos
	$lista_pedidos = extras::htmlentities(pedidos::getList(array(
		'columns' => array(
			'id', 'nombre', 'estado'
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'id DESC',
	)));
	
	//Creamos la paginacion para lista de pedidosarios
	$pagination = pedidos::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div><h1>Listado de pedidos</h1></div>
		</div>
		<div class="container">
			<div class="gd-80 gd-s-100"><h4>Nombre</h4></div>
			<div class="gd-20 gd-s-100"><h4>Cumplido</h4></div>
		</div>
		<?php foreach( $lista_pedidos as $e ): ?>
		<div class="container userlist">
			<div class="gd-80 gd-s-100">
				<?php echo $e['nombre']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<div class="listable_switch">
					<div class="toggle-group">
						<input name="sw_<?php echo $e['id'] ?>" type="checkbox" id="sw_<?php echo $e['id'] ?>" data-estado="<?php echo $e['estado'] ?>" data-id="<?php echo $e['id'] ?>">
						<label for="sw_<?php echo $e['id'] ?>">
							<span class="aural">Show:</span>
						</label>
						<div class="onoffswitch pull-right" aria-hidden="true">
							<div class="onoffswitch-label">
								<div class="onoffswitch-inner"></div>
								<div class="onoffswitch-switch"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
	<div class="pagination_s1">
	Pagina: <?php echo $pagination->result('current_page'); ?> de <?php echo $pagination->result('last_page'); ?>:
	<?php if( $pagination->hasPrev() ): ?>
		<a href='<?php echo $pagination->result('prev_page_url'); ?>'>&laquo; Anterior</a>
	<?php endif; ?>
	<?php echo $pagination->getLinks(); ?>
	<?php if( $pagination->hasNext() ): ?>
		<a href='<?php echo $pagination->result('next_page_url'); ?>'>Siguiente &raquo;</a>
	<?php endif; ?>
	</div>
	
<?php require 'secciones/footer.tpl'; ?>