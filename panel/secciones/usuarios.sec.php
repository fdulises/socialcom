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
	
	//Definimos datos para la paginacion
	$per_page = 20;
	$current_page = isset($_GET['pagina']) ? (INT) $_GET['pagina'] : 1;
	if( $current_page < 1 ) $current_page = 1; 
	$offset = $per_page*($current_page-1);
	$url_base = '';
	
	//Obtenemos lista de usuarios
	$data = array(
		'columns' => array(
			'u.id', 'u.email', 'u.nickname', 'u.estado', 'u.grupo', 'p.nombre'
		),
		'limit' => $per_page,
		'offset' => $offset,
		'order' => 'id DESC',
	);
	//Definimos parametros de busqueda
	if( isset($_GET['s']) ){
		if( !empty($_GET['s']) ){
			$data['nickname'] = $_GET['s'];
			$_GET['s'] = htmlentities($_GET['s']);
			$url_base = "?s={$_GET['s']}";
			$data['order'] = 'nickname ASC';
		}
	}
	$lista_u = extras::htmlentities($user->getList($data));
	//Generamos campos faltantes
	foreach( $lista_u as $k => $v ){
		$lista_u[$k]['avatar'] = $user->generateAvatar(array(
			'id' => $v['id'],
			'size' => 20,
			'email' => $v['email'],
		));
		$lista_u[$k]['perfil'] = sitio::getInfo('url').'/@'.$v['nickname'];
	}
	
	//Creamos la paginacion
	$pagination = $user::pagination([
		'per_page' => $per_page,
		'current_page' => $current_page,
		'url_base' => $url_base,
	]);
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Listado de usuarios</h1></div>
			<form class="gd-40 gd-m-100 form-line" method="get" action="">
				<input type="text" placeholder="Buscar usuario" name="s" id="" class="form-in" />
				<button type="submit" class="btn btn-default"><span class="icon-search"></span></button>
			</form>
		</div>
		<div class="container">
			<div class="gd-30 gd-s-33 gd-x-50"><h4>Usuario</h4></div>
			<div class="gd-20 gd-s-33 hide-x"><h4>Grupo</h4></div>
			<div class="gd-30 hide-s"><h4>E-mail</h4></div>
			<div class="gd-20 gd-s-33 gd-x-50 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_u as $u ): ?>
		<div class="container userlist">
			<div class="gd-30 gd-s-33 gd-x-50">
				<img src="<?php echo $u['avatar']; ?>" class="bx-left avatar" />
				<div><a href="<?php echo $u['perfil']; ?>">@<?php echo $u['nickname']; ?></a></div>
				<div><?php echo $u['nombre']; ?></div>
			</div>
			<div class="gd-20 gd-s-33 hide-x">
				<?php echo $usergroup[$u['grupo']]; ?>
			</div>
			<div class="gd-30 hide-s">
				<?php echo $u['email']; ?>
			</div>
			<div class="gd-20 gd-s-33 gd-x-50 tx-right">
				<a href="usuarios/editar/<?php echo $u['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
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