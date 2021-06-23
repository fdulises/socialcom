<?php
	/*
	* Socialcom CMS - Todos los derechos reservados
	* V Alpha 1.0
	* Ulises Rendon - http://debred.com - ulises@debred.com
	*/

	/*
	* Archivo principal del sitema - Frontcontroller
	*
	* Recibe y procesa todas las peticiones
	*/
	
	
	//Definimos el directorio raiz de la aplicacion
	define("APP_PATH", __DIR__);

	//Incluimos archivos de configuracion
	require 'config/conf_db.php';
	require 'config/conf_sitio.php';
	require 'config/conf_db_tablas.php';
	require 'config/conf_secciones.php';
	if( !defined('DB_INSTALLED') ) header('location: instalador');
	
	//Definimos $_GET['url'] en caso de acceso desde linea de comandos
	if( isset($argv) ){if( isset($argv[1]) ){
		foreach( explode('&', $argv[1]) as $el ){
			$el = explode('=', $el);
			if( count($el)==2 && $el[0]=='url' ) $_GET['url'] = $el[1];
		}
	}}

	//Incluimos dependencias
	require 'nucleo/librerias/'.DB_CONECTOR.'.php';
	spl_autoload_register(function ($nombre_clase) {
		require "nucleo/librerias/{$nombre_clase}.php";
	});
	
	//Incluimos archivos del sistema
	require 'nucleo/inclusiones/inc_listas.php';
	require 'config/conf_eventos.php';
	require 'nucleo/datos/db_sitio.php';
	require 'nucleo/datos/db_entrada.php';
	require 'nucleo/logica/l_usuario.php';
	require 'nucleo/logica/l_mt.php';
	require 'nucleo/logica/l_entrada.php';
	require 'nucleo/logica/l_tienda.php';
	require 'nucleo/logica/logros.php';
	require 'nucleo/logica/referidos.php';
	require 'nucleo/logica/adds.php';

	//Instanciamos obtejos esenciales para el sistema
	$mt = mt::getInstance();
	$user = l_usuario::getInstance();
	
	//Incluimos el archivo de configuracion de la plantilla
	if( file_exists( "{$mt->getInfo('tema_url')}/config.php" ) )
	require "{$mt->getInfo('tema_url')}/config.php";

	//Definimos la seccion solicitada
	$mt->getSeccion();
	if( !$user->logingCheck() && ( 1 == $mt->seccion['loging'] ) )
		header('location: acceso');
	else if ( $user->logingCheck() && ( -1 == $mt->seccion['loging'] ) )
		header('location: inicio');
	
	//Insertamos el facade de la seccion solicitada
	if( file_exists(APP_PATH.'/'.$mt->seccion['filesec']) ){
		require APP_PATH.'/config/conf_plantilla.php';
		require $mt->seccion['filesec'];
	}else die('Error: No se encontro la sección solicitada');
