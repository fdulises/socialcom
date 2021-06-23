<?php
	namespace wecor;
	
	//Verificamos que el usuario haya iniciado sesion
	if( !$user->logingCheck() ) header('location: acceso');
	else{
		if( $user->getGrupo() == 6 )
			header('location: '.sitio::getInfo('url'));
	}
	
	require 'secciones/header.tpl';
?>
<div class="container mg-sec">
		<h1><?php echo sitio::getInfo('titulo'); ?> - Dashboard</h1>
		<div class="container">
			<div class="gd-33 gd-b-100">
				<div class="cont-info">
					<div class="text">
						<h3>Entradas</h3>
						<p><?php echo sitio::getInfo('total_a'); ?></p>
					</div>
					<div class="cover">
						<span class="icon-file-text"></span>
					</div>
				</div>
			</div>
			<div class="gd-33 gd-b-100">
				<div class="cont-info">
					<div class="text">
						<h3>Comentarios</h3>
						<p><?php echo sitio::getInfo('total_c'); ?></p>
					</div>
					<div class="cover">
						<span class="icon-bubble2"></span>
					</div>
				</div>
			</div>
			<div class="gd-33 gd-b-100">
				<div class="cont-info">
					<div class="text">
						<h3>Usuarios</h3>
						<p><?php echo sitio::getInfo('total_u'); ?></p>
					</div>
					<div class="cover">
						<span class="icon-users"></span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container mg-sec">
	</div>
<?php require 'secciones/footer.tpl'; ?>