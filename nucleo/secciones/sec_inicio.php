<?php
	/*
	* Facade para la seccion inicio
	*/

	$ruta = APP_PATH."/{$mt->getInfo('tema_url')}/sec_inicio.php";

	if( file_exists( "{$mt->getInfo('tema_url')}/sec_inicio.php" ) )
		$ruta = "{$mt->getInfo('tema_url')}/sec_inicio.php";
	else if ( file_exists( "{$mt->getInfo('tema_url')}/sec_blog.php" ) )
		$ruta = "{$mt->getInfo('tema_url')}/sec_blog.php";
	require $ruta;