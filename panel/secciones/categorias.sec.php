<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: '.PANEL_PATH.'/acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	//Obtenemos lista de entradas
	$lista_c = extras::htmlentities(entrada::catGetList(array(
		'columns' => array(
			'id',
			'nombre',
			'url',
			'descrip',
			'total_e as entradas',
		),
		'tipo' => 1,
	)));
	foreach($lista_c as $k => $v){
		//Definimos el link de cada entrada
		$lista_c[$k]['link'] = sitio::getInfo('url').'/'.$v['url'];
	}
	
	require 'secciones/header.tpl';
?>
	<div class="container listable">
		<div class="gd-100">
			<div class="gd-60 gd-m-100"><h1>Listado de categorias</h1></div>
			<?php if( ($user->getGrupo() == 1) ): ?>
			<div class="gd-40 gd-m-100 tx-right">
				<a href="categorias/crear"><button type="button" class="btn btn-primary"><span class="icon-plus"></span></button></a>
			</div>
			<?php endif; ?>
		</div>
		<div class="container">
			<div class="gd-30 gd-m-50"><h4>Nombre</h4></div>
			<div class="gd-30 hide-m"><h4>Descripción</h4></div>
			<div class="gd-20 hide-m"><h4>Entradas</h4></div>
			<div class="gd-20 gd-m-50 tx-right"><h4>Acciones</h4></div>
		</div>
		<?php foreach( $lista_c as $c ): ?>
		<div class="container userlist">
			<div class="gd-30 gd-m-50">
				<a href="<?php echo $c['link']; ?>"><?php echo $c['nombre']; ?></a>
			</div>
			<div class="gd-30 hide-m">
				<?php echo $c['descrip']; ?>
			</div>
			<div class="gd-20 hide-m">
				<span>
					<span class="icon-file-text"></span> <?php echo $c['entradas']; ?>
				</span>
			</div>
			<div class="gd-20 gd-m-50 tx-right">
				<?php if( ($user->getGrupo() == 1) ): ?>
				<a href="categorias/editar/<?php echo $c['id']; ?>"><button class="btn btn-primary"><span class="icon-pencil"></span></button></a>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
<?php require 'secciones/footer.tpl'; ?>