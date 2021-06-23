<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Verificamos que el usuario tenga permiso para esta seccion
	if( !($user->getGrupo() <= 4) ) event::fire('e404');
	
	//Procesamos aprobacion de comentario
	if( isset($_GET['aprobar'], $_GET['valor']) ){
		$_GET['aprobar'] = (INT) $_GET['aprobar'];
		$_GET['valor'] = (INT) $_GET['valor'];
		
		$result = coment::update($_GET['aprobar'], [
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
	
	//Obtenemos lista de comentarios
	$lista_coment = extras::htmlentities(coment::getList(array(
		'columns' => array(
			'c.id as comentario_id',
			'c.autor as comentario_autor',
			'c.usuario',
			'c.estado as comentario_estado',
			'u.nickname',
			'u.email',
			'c.email as comentario_email',
			'c.contenido as comentario_contenido',
			'c.fecha as comentario_fecha',
			'e.titulo as comentario_titulo',
			'e.url',
			'cat.nombre as categoria',
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'comentario_fecha DESC',
	)));
	foreach( $lista_coment as $k => $v){
		if( !empty($v['nickname']) ){
			$lista_coment[$k]['comentario_autor'] = "@{$v['nickname']}";
		}
		if( !empty($v['email']) ){
			$lista_coment[$k]['comentario_email'] = $v['email'];
		}
		
		$lista_coment[$k]['comentario_avatar'] = $user->generateAvatar([
			'id' => $v['usuario'],
			'size' => 35,
			'email' => $lista_coment[$k]['comentario_email'],
		]);
		
		$lista_coment[$k]['comentario_contenido'] = nl2br($v['comentario_contenido']);
		
		$lista_coment[$k]['comentario_fecha'] = extras::formatoDate(
			$lista_coment[$k]['comentario_fecha'], 'd/m/Y'
		);
		//Definimos el enlace al comentario
		$lista_coment[$k]['comentario_enlace'] = sitio::getInfo('url').'/'.$v['url'];
		if( !empty($v['categoria']) )
			$lista_coment[$k]['comentario_enlace'] .= "/{$v['categoria']}";
		
		$lista_coment[$k]['comentario_enlace'] .= "#coment_{$v['comentario_id']}";
		//Eliminamos los campos que ya no se ocupan
		unset($lista_coment[$k]['url'], $lista_coment[$k]['categoria'], $lista_coment[$k]['usuario']);
	}
	//Creamos la paginacion para lista de comentarios
	$pagination = coment::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
	]);
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div><h1>Listado de comentarios</h1></div>
		</div>
		<div class="container">
			<div class="gd-30 gd-m-33 gd-s-100"><h4>Autor</h4></div>
			<div class="gd-50 gd-m-33 gd-s-100"><h4>Contenido</h4></div>
			<div class="gd-20 gd-m-33 gd-s-100 tx-right"><h4>Aprobado</h4></div>
		</div>
		<?php foreach( $lista_coment as $e ): ?>
		<div class="container userlist">
			<div class="gd-30 gd-m-33 gd-s-100">
				<img class="coment_avatr bx-left" src="<?php echo $e['comentario_avatar']; ?>">
				<div>
					<?php echo $e['comentario_autor']; ?>
					<br>
					<span class="icon-calendar"></span> <?php echo $e['comentario_fecha']; ?>					
				</div>
			</div>
			<div class="gd-60 gd-m-33 gd-s-100">
				<a href="<?php echo $e['comentario_enlace']; ?>" target="_blank"><h4><?php echo $e['comentario_titulo']; ?> - #<?php echo $e['comentario_id']; ?></h4></a>
				<div><?php echo $e['comentario_contenido']; ?></div>
				<div class="listable_switch">
					<div class="toggle-group">
						<input name="sw_<?php echo $e['comentario_id'] ?>" type="checkbox" id="sw_<?php echo $e['comentario_id'] ?>" data-estado="<?php echo $e['comentario_estado'] ?>" data-id="<?php echo $e['comentario_id'] ?>">
						<label for="sw_<?php echo $e['comentario_id'] ?>">
							<span class="aural">Show:</span> Aprobar
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
			<div class="gd-10 gd-m-33 gd-s-100 tx-right">
				<?php if( $user->getGrupo() <= 3 ): ?>
				<a href="<?php echo PANEL_PATH ?>/comentarios/editar/<?php echo $e['comentario_id'] ?>">
					<button class="btn btn-primary">
					<span class="icon-pencil"></span>
					</button>
				</a>
				<?php endif; ?>
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