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
	
	//Obtenemos lista de entradas
	$lista_e = extras::htmlentities(entrada::getList(array(
		'columns' => array(
			'e.id as id',
			'e.titulo as titulo',
			'e.url as url',
			'e.estado as estado',
			'e.coleccion as categoria_id',
			'e.denuncias as denuncias',
			'c.nombre as categoria',
			'c.url as categoria_url',
			'e.usuario as autor_id',
			'u.nickname as autor',
		),
		'tipo' => 2,
		'limit' => 20,
		'order' => 'e.denuncias DESC',
		'denuncias' => 1,
	)));
	//Agregamos datos faltantes a la lista de entradas
	foreach($lista_e as $k => $v){
		//Definimos el link de cada entrada
		if( !empty($v['categoria_url']) )
			$link = "{$v['categoria_url']}/{$v['url']}";
		else $link = $v['url'];
		$lista_e[$k]['link'] = sitio::getInfo('url').'/'.$link;

		//Truncamos el titulo
		$lista_e[$k]['titulo'] = mb_strimwidth($v['titulo'], 0, 50);
	}
	
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
			'c.denuncias',
		),
		'limit' => 20,
		'order' => 'denuncias DESC',
		'denuncias' => 1,
	)));
	foreach( $lista_coment as $k => $v){
		if( !empty($v['nickname']) ){
			$lista_coment[$k]['comentario_autor'] = "@{$v['nickname']}";
		}
		if( !empty($v['email']) ){
			$lista_coment[$k]['comentario_email'] = $v['email'];
		}
		$lista_coment[$k]['comentario_contenido'] = nl2br($v['comentario_contenido']);
		
		//Definimos el enlace al comentario
		$lista_coment[$k]['comentario_enlace'] = sitio::getInfo('url').'/'.$v['url'];
		if( !empty($v['categoria']) )
			$lista_coment[$k]['comentario_enlace'] .= "/{$v['categoria']}";
		
		$lista_coment[$k]['comentario_enlace'] .= "#coment_{$v['comentario_id']}";
		//Eliminamos los campos que ya no se ocupan
		unset($lista_coment[$k]['url'], $lista_coment[$k]['categoria'], $lista_coment[$k]['usuario']);
	}
	
	$lista_u = extras::htmlentities($user->getList(array(
		'columns' => array(
			'u.id', 'u.email', 'u.nickname', 'u.estado', 'u.grupo', 'p.nombre', 'u.denuncias'
		),
		'limit' => 20,
		'order' => 'denuncias DESC',
		'denuncias' => 1,
	)));
	//Generamos campos faltantes
	foreach( $lista_u as $k => $v ){
		$lista_u[$k]['perfil'] = sitio::getInfo('url').'/@'.$v['nickname'];
	}
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div><h1>Listado de denuncias</h1></div>
		</div>
		<p><h4>Entradas denunciadas</h4></p>
		<div class="container">
			<div class="gd-40 gd-s-100"><h4>Titulo</h4></div>
			<div class="gd-20 gd-s-100"><h4>Autor</h4></div>
			<div class="gd-20 gd-s-100"><h4>Denuncias</h4></div>
			<div class="gd-20 gd-s-100 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_e as $e ): ?>
		<div class="container userlist">
			<div class="gd-40 gd-s-100">
				<?php echo $e['titulo']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<?php echo $e['autor']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<?php echo $e['denuncias']; ?>
			</div>
			<div class="gd-20 gd-s-100 tx-right">
				<a href="<?php echo sitio::getInfo('url').'/'.$e['url']; ?>" title="Ver entrada"><button class="icon-eye btn size-s"></button></a>
				<a href="<?php echo sitio::getInfo('url').'/post?id='.$e['id']; ?>" title="Editar entrada"><button class="icon-pencil btn size-s"></button></a>
				<a href="<?php echo sitio::getInfo('url').'/'.$e['url'].'?eliminar='.$e['id']; ?>" title="Eliminar entrada"><button class="icon-cross btn size-s"></button></a>
			</div>
		</div>
		<?php endforeach; ?>
		<!--<p>
			<a href="denuncias/entradas"><button class="btn btn-default">Ver todos</button></a>
		</p>-->
		
		
		<p><h4>Comentarios denunciados</h4></p>
		<div class="container">
			<div class="gd-40 gd-s-100"><h4>Contenido</h4></div>
			<div class="gd-20 gd-s-100"><h4>Autor</h4></div>
			<div class="gd-20 gd-s-100"><h4>Denuncias</h4></div>
			<div class="gd-20 gd-s-100 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_coment as $e ): ?>
		<div class="container userlist">
			<div class="gd-40 gd-s-100">
				<?php echo $e['comentario_contenido']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<?php echo $e['comentario_autor']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<?php echo $e['denuncias']; ?>
			</div>
			<div class="gd-20 gd-s-100 tx-right">
				<a href="<?php echo $e['comentario_enlace']; ?>" title="Ver comentario"><button class="icon-eye btn size-s"></button></a>
				<a href="<?php echo PANEL_PATH.'/comentarios/editar/'.$e['comentario_id']; ?>" title="Editar comentario"><button class="icon-pencil btn size-s"></button></a>
			</div>
		</div>
		<?php endforeach; ?>
		<!--<p><a href="denuncias/comentarios"><button class="btn btn-default">Ver todos</button></a></p>-->
		
		
		<p><h4>Usuarios denunciados</h4></p>
		<div class="container">
			<div class="gd-30 gd-s-100"><h4>Nickname</h4></div>
			<div class="gd-30 gd-s-100"><h4>Email</h4></div>
			<div class="gd-20 gd-s-100"><h4>Denuncias</h4></div>
			<div class="gd-20 gd-s-100 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_u as $e ): ?>
		<div class="container userlist">
			<div class="gd-30 gd-s-100">
				@<?php echo $e['nickname']; ?>
			</div>
			<div class="gd-30 gd-s-100">
				<?php echo $e['email']; ?>
			</div>
			<div class="gd-20 gd-s-100">
				<?php echo $e['denuncias']; ?>
			</div>
			<div class="gd-20 gd-s-100 tx-right">
				<a href="<?php echo $e['perfil']; ?>" title="Ver usuario"><button class="icon-eye btn size-s"></button></a>
				<a href="<?php echo PANEL_PATH.'/usuarios/editar/'.$e['id']; ?>" title="Editar Usuario"><button class="icon-pencil btn size-s"></button></a>
			</div>
		</div>
		<?php endforeach; ?>
		<!--<p><a href="denuncias/usuarios"><button class="btn btn-default">Ver todos</button></a></p>-->
	</div>
	
<?php require 'secciones/footer.tpl'; ?>