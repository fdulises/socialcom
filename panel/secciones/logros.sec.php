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
	
	//Definimos los datos necesarios para la paginacion
	$per_page = 20;
	$current_page = isset($_GET['pagina']) ? (INT) $_GET['pagina'] : 1;
	if( $current_page < 1 ) $current_page = 1; 
	$offset = $per_page*($current_page-1);
	
	//Obtenemos lista de logros
	$lista_logros = extras::htmlentities(logros::getList(array(
		'columns' => array(
			'id',
			'nombre',
			'cover',
			'modo',
			'accion',
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'nombre ASC',
	)));
	//Creamos la paginacion para lista de logros
	$pagination = logros::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	//Agregamos datos faltantes a la lista de logros
	foreach($lista_logros as $k => $v){
		$lista_logros[$k]['cover'] = sitio::getInfo('url').'/'.MEDIA_LOGROS_DIR.'/'.$v['cover'];
		$lista_logros[$k]['modo'] = $logromodo[$v['modo']];
	}
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Listado de logros</h1></div>
			<div class="gd-40 gd-m-100 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/logros/crear"><button type="button" class="btn btn-primary"><span class="icon-plus"></span></button></a>
			</div>
		</div>
		<div class="container">
			<div class="gd-40 gd-s-50"><h4>Nombre</h4></div>
			<div class="gd-20 hide-s"><h4>Asignación</h4></div>
			<div class="gd-20 hide-s"><h4>Puntos requeridos</h4></div>
			<div class="gd-20 gd-s-50 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_logros as $e ): ?>
		<div class="container userlist">
			<div class="gd-40 gd-s-50">
				<div class="gd-20 gd-m-40 gd-s-100">
					<img src="<?php echo $e['cover']; ?>">
				</div>
				<div class="gd-80 gd-m-60 gd-s-100">
					<?php echo $e['nombre']; ?>
				</div>
			</div>
			<div class="gd-20 hide-s">
				<?php echo $e['modo']; ?>
			</div>
			<div class="gd-20 hide-s">
				<?php echo $e['accion']; ?>
			</div>
			<div class="gd-20 gd-s-50 tx-right">
				<a href="<?php echo PANEL_PATH; ?>/logros/editar/<?php echo $e['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
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