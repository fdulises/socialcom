<?php
	namespace wecor;

	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Procesamos aprobacion de entrada
	if( isset($_GET['aprobar'], $_GET['valor']) && ($user->getGrupo() <= 3) ){
		$_GET['aprobar'] = (INT) $_GET['aprobar'];
		$_GET['valor'] = (INT) $_GET['valor'];
		
		$result = entrada::update($_GET['aprobar'], [
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
	
	//Obtenemos lista de entradas
	$lista_e = extras::htmlentities(entrada::getList(array(
		'columns' => array(
			'e.id as id',
			'e.titulo as titulo',
			'e.url as url',
			'e.fecha_u as fecha',
			'e.descrip as descrip',
			'e.total_coment as comentarios',
			'e.puntosv as puntos',
			'e.likes as likes',
			'e.hits as hits',
			'e.estado as estado',
			'e.coleccion as categoria_id',
			'c.nombre as categoria',
			'c.url as categoria_url',
			'e.usuario as autor_id',
			'u.nickname as autor',
		),
		'tipo' => 2,
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'e.fecha_u DESC',
	)));
	//Creamos la paginacion para lista de entradas
	$pagination = entrada::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	//Agregamos datos faltantes a la lista de entradas
	foreach($lista_e as $k => $v){
		//Definimos el link de cada entrada
		if( !empty($v['categoria_url']) )
			$link = "{$v['categoria_url']}/{$v['url']}";
		else $link = $v['url'];
		$lista_e[$k]['link'] = sitio::getInfo('url').'/'.$link;
		
		//Definimos el formato de entrada
		$lista_e[$k]['fecha'] = extras::formatoDate($v['fecha'], 'd/m/Y');
		
		//Truncamos el titulo
		$lista_e[$k]['titulo'] = mb_strimwidth($v['titulo'], 0, 50);
	}
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Listado de entradas</h1></div>
			<div class="gd-40 gd-m-100 tx-right">
				<a href="<?php echo sitio::getInfo('url') ?>/post"><button type="button" class="btn btn-primary"><span class="icon-plus"></span></button></a>
			</div>
		</div>
		<div class="container">
			<div class="gd-50 gd-s-60"><h4>Titulo</h4></div>
			<div class="gd-30 hide-s"><h4>Datos</h4></div>
			<div class="gd-20 gd-s-40 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_e as $e ): ?>
		<div class="container userlist">
			<div class="gd-50 gd-s-60">
				<?php if( $user->getGrupo() <= 3 ): ?>
				<div class="gd-80 gd-m-60 gd-s-100 bx-right">
				<?php else: ?>
				<div>
				<?php endif; ?>
					<div><a href="<?php echo $e['link']; ?>"><?php echo $e['titulo']; ?></a></div>
					<div class="">
						<span><span class="icon-user"></span> @<?php echo $e['autor']; ?></span>
						<span class="hide-m"> - 
							<span class="icon-clock"></span> <?php echo $e['fecha']; ?>
						</span>
					</div>
				</div>
				<?php if( $user->getGrupo() <= 3 ): ?>
				<div class="gd-20 gd-m-40 gd-s-100">
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
				<?php endif; ?>
			</div>
			<div class="gd-30 hide-s display-flex">
				<span>
					<span class="icon-folder"></span> <?php echo $e['categoria']; ?>
				</span>
				<span>
					<span class="icon-thumb_up"></span> <?php echo $e['likes']; ?>
				</span>
				<span>
					<span class="icon-droplet"></span> <?php echo $e['puntos']; ?>
				</span>
				<span>
					<span class="icon-eye"></span> <?php echo $e['hits']; ?>
				</span>
				<span>
					<span class="icon-bubble2"></span> <?php echo $e['comentarios']; ?>
				</span>
			</div>
			<div class="gd-20 gd-s-40 tx-right">
				<a href="<?php echo sitio::getInfo('url'); ?>/post?id=<?php echo $e['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
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