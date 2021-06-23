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
	
	//Definimos los datos necesarios para la paginacion
	$per_page = 20;
	$current_page = isset($_GET['pagina']) ? (INT) $_GET['pagina'] : 1;
	if( $current_page < 1 ) $current_page = 1; 
	$offset = $per_page*($current_page-1);
	
	//Obtenemos lista de productos
	$lista_prod = extras::htmlentities(tienda::getList(array(
		'columns' => array(
			'id',
			'nombre',
			'precio',
			'cover',
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'nombre ASC',
	)));
	
	//Creamos la paginacion para lista de productos
	$pagination = tienda::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	//Agregamos datos faltantes a la lista de productos
	foreach($lista_prod as $k => $v){
		$lista_prod[$k]['cover'] = sitio::getInfo('url').'/'.MEDIA_TIENDA_DIR.'/'.$v['cover'];
	}
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Listado de productos</h1></div>
			<div class="gd-40 gd-m-100 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/tienda/crear"><button type="button" class="btn btn-primary"><span class="icon-plus"></span></button></a>
			</div>
		</div>
		<div class="container">
			<div class="gd-60 gd-s-33"><h4>Nombre</h4></div>
			<div class="gd-20 gd-s-33"><h4>Precio</h4></div>
			<div class="gd-20 gd-s-33 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_prod as $e ): ?>
		<div class="container userlist">
			<div class="gd-10 gd-m-20 gd-s-20">
				<img src="<?php echo $e['cover']; ?>">
			</div>
			<div class="gd-50 gd-m-40 gd-s-30">
				<?php echo $e['nombre']; ?>
			</div>
			<div class="gd-20 gd-s-30">
				<span class="icon-coin-dollar"></span> <?php echo $e['precio']; ?>
			</div>
			<div class="gd-20 gd-s-20 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/tienda/editar/<?php echo $e['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
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