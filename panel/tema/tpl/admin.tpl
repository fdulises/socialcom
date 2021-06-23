<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Listefi - Dashboard</title>
	<link rel="stylesheet" href="css/listefi-fuentes.css" />
	<link rel="stylesheet" href="css/listefi.css" />
	<link rel="stylesheet" href="css/listefi-tema.css" />
	<script src="js/listefi.js"></script>
	<script src="js/funciones.js"></script>
</head>
<body>
	<header class="nav-bar nav-primary">
		<div class="nav-brand"><a href="admin.html">ADMIN PANEL</a></div>
		<button data-estado="open" type="button" class="btn-sadw" id="actionNav">
			<span class="line"></span>
			<span class="line"></span>
			<span class="line"></span>
		</button>
		<ul class="bx-right hide-m">
			<li><a href="#"><span class="icon-cogs"></span> Opciones</a></li>
			<li><a href="accesso.html"><span class="icon-exit"></span> Salir</a></li>
		</ul>
	</header>
	<article>
		<aside class="nav-abs" id="leftNav">
			<ul>
				<li class="hide-min-m"><a href="#"><span class="icon-cogs"></span> Opciones</a></li>
				<li class="hide-min-m"><a href="accesso.html"><span class="icon-exit"></span> Salir</a></li>
				<li><a href="#"><span class="icon-meter"></span> Elemento #1</a></li>
				<li><a href="#"><span class="icon-book"></span> Elemento #2</a></li>
				<li><a href="#"><span class="icon-file-text"></span> Elemento #3</a></li>
				<li><a href="#"><span class="icon-droplet"></span> Elemento #4</a></li>
				<li><a href="#"><span class="icon-info"></span> Elemento #5</a></li>
				<li>
					<button type="button"><span class="icon-circle-left"></span> Cerrar Menu</button>
				</li>
			</ul>
		</aside>
		<div class="container-r" id="cont">
			<div class="container mg-sec">
				<div class="alert">
					<span class="icon-info"></span> <strong>¡Bien hecho!</strong> Lorem ipsum dolor sit amet consectetur adipiscing elit.
					<button type="button" class="btn-close">&times;</button>
				</div>
				<ul class="breadcrumb">
					<li><a href="#">Seccion #1</a></li>
					<li><a href="#">Subseccion #1</a></li>
					<li><a href="#">Subseccion #2</a></li>
				</ul>
				<div class="container">
					<div class="gd-33 gd-b-100">
						<div class="cont-info">
							<div class="text">
								<h3>Entradas</h3>
								<p>200</p>
							</div>
							<div class="cover">
								<span class="icon-file-text"></span>
							</div>
						</div>
					</div>
					<div class="gd-33 gd-b-100">
						<div class="cont-info">
							<div class="text">
								<h3>Colecciones</h3>
								<p>106</p>
							</div>
							<div class="cover">
								<span class="icon-folder-open"></span>
							</div>
						</div>
					</div>
					<div class="gd-33 gd-b-100">
						<div class="cont-info">
							<div class="text">
								<h3>Usuarios</h3>
								<p>88</p>
							</div>
							<div class="cover">
								<span class="icon-users"></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="container mg-sec">
				<h1>Seccion #1</h1>
				<p>Lorem ipsum dolor sit amet consectetur adipiscing elit</p>
			</div>
			<footer class="container mg-sec">
				<h6>Listefi &copy 2016</h6>
			</footer>
		</div>
	</article>
</body>
</html>