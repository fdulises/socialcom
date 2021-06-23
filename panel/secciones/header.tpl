<?php
	namespace wecor;
	$usergrupo = $user->getGrupo();
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta charset="utf-8" />
	<title><?php echo sitio::getInfo('titulo'); ?> - Dashboard</title>
	<link rel="stylesheet" href="<?php echo PANEL_PATH; ?>/tema/css/listefi-fuentes.css" />
	<link rel="stylesheet" href="<?php echo PANEL_PATH; ?>/tema/css/listefi.css" />
	<link rel="stylesheet" href="<?php echo PANEL_PATH; ?>/tema/css/listefi-tema.css" />
	<script>
	var SITIO_SEC = '<?php echo $actualroute; ?>';
	var PANEL_PATH = '<?php echo PANEL_PATH; ?>';
	</script>
	<script src="<?php echo PANEL_PATH; ?>/tema/js/listefi.js"></script>
	<script src="<?php echo PANEL_PATH; ?>/tema/js/funciones.js"></script>
</head>
<body>
	<header class="nav-bar nav-primary">
		<div class="nav-brand"><a href="<?php echo PANEL_PATH; ?>/inicio">SOCIALCOM</a></div>
		<button data-estado="open" type="button" class="btn-sadw" id="actionNav">
			<span class="line"></span>
			<span class="line"></span>
			<span class="line"></span>
		</button>
		<ul class="bx-right hide-m">
			<li><a href="<?php echo sitio::getInfo('url'); ?>"><span class="icon-link"></span> Ver Sitio</a></li>
			<li><a href="<?php echo PANEL_PATH; ?>/salir"><span class="icon-exit"></span> Salir</a></li>
		</ul>
	</header>
	<article>
		<aside class="nav-abs" id="leftNav">
			<ul>
				<li class="hide-min-m">
					<a href="<?php echo sitio::getInfo('url'); ?>"><span class="icon-link"></span> Ver Sitio</a>
				</li>
				<li class="hide-min-m">
					<a href="<?php echo PANEL_PATH; ?>/salir"><span class="icon-exit"></span> Salir</a>
				</li>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/configuracion"><span class="icon-cog"></span> Configuración</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/usuarios"><span class="icon-user"></span> Usuarios</a>
				</li>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/entradas"><span class="icon-file-text"></span> Entradas</a>
				</li>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/categorias"><span class="icon-folder"></span> Categorias</a>
				</li>
				<?php endif; ?>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/tienda"><span class="icon-gift"></span> Tienda</a>
				</li>
				<?php endif; ?>
				<?php if( ($usergrupo == 1) || ($usergrupo == 2) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/logros"><span class="icon-trophy"></span> Logros</a>
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/comentarios"><span class="icon-bubble2"></span> Comentarios</a>
				</li>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/webscrap"><span class="icon-newspaper"></span> Webscrap</a>
				</li>
				<?php endif; ?>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/pedidos"><span class="icon-target"></span> Pedidos</a>
				</li>
				<?php endif; ?>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/denuncias"><span class="icon-blocked"></span> Denuncias</a>
				</li>
				<?php endif; ?>
				<?php if( ($usergrupo == 1) ): ?>
				<li>
					<a href="<?php echo PANEL_PATH; ?>/adds"><span class="icon-coin-dollar"></span> Publicidad</a>
				</li>
				<?php endif; ?>
			</ul>
		</aside>
		<div class="container-r clearfix" id="cont">