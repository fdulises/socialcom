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
	
	//Obtenemos lista de webscraps
	$lista_scrap = extras::htmlentities(webscrap::getList(array(
		'columns' => array(
			'id',
			'nombre',
			'url',
			'estado',
			'updated',
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'nombre ASC',
	)));
	//Creamos la paginacion para lista de webscraps
	$pagination = webscrap::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Fuentes Webscrap</h1></div>
			<div class="gd-40 gd-m-100 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/webscrap/crear"><button type="button" class="btn btn-primary"><span class="icon-plus"></span></button></a>
			</div>
		</div>
		<div class="container">
			<div class="gd-20 gd-s-33"><h4>Nombre</h4></div>
			<div class="gd-40 hide-s"><h4>Fuente</h4></div>
			<div class="gd-20 gd-s-33"><h4>Actualizado</h4></div>
			<div class="gd-20 gd-s-33 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_scrap as $e ): ?>
		<div class="container userlist">
			<div class="gd-20">
				<?php echo $e['nombre']; ?>
			</div>
			<div class="gd-40">
				<?php echo $e['url']; ?>
			</div>
			<div class="gd-20">
				<?php echo extras::formatoDate( $e['updated'], 'H:i:s d/m/Y' ) ?>
			</div>
			<div class="gd-20 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/webscrap/editar/<?php echo $e['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
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