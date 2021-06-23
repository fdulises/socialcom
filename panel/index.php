<?php

	/*
	* Wecor - Dashboard
	* V 17.02.09
	* Desarrollado por Ulises Rendon - fdulises@outlook.com
	* Todos los derechos reservados
	*/
	
	/*
	* Archivo principal de la aplicacion
	* Procesa todas las peticiones
	*/
	
	namespace wecor;
	
	//Activamos registro de errores
	ini_set("log_errors" , "1");
	ini_set("error_log" , "error.log.txt");
	//ini_set("display_errors" , "0");
	
	//Autocarga de librerias del sistema
	spl_autoload_register(function($classname){
		$classname = explode("\\" , $classname);
		if ($classname[0] == 'wecor') {
			$filename = __DIR__ . "/librerias/{$classname[1]}.php";
			if( file_exists($filename) ) require_once($filename);
		}
	});
	
	//Incluimos archivo de configuracion db
	require '../config/conf_db.php';
	
	//Cargamos los archivos de configuracion
	$conffiles = glob("config/*.conf.php");
	if( $conffiles ) foreach($conffiles as $v) require $v;
	
	//Cargamos los includes
	$incfiles = glob("inclusiones/*.inc.php");
	if( $incfiles ) foreach($incfiles as $v) require $v;
	
	//Establecemos la sesion
	session::setConfig('name', S_ID);
	session::start();
	
	//Establecemos configuracion de cache
	basicache::setDir('../cache');
	basicache::setLifetime(60*60);
	
	//Realizamos la coneccion con la base de datos
	dbConnector::connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	//Autocarga de clases del usuario
	spl_autoload_register(function($classname){
		$classname = explode("\\" , $classname);
		if ($classname[0] == 'wecor') {
			$filename = __DIR__ . "/logica/{$classname[1]}.php";
			if( file_exists($filename) ) require_once($filename);
		}
	});
	
	$user = new usuario;
	
	//Definimos constante con la ruta absoluta del dashboard
	define('PANEL_PATH', sitio::getInfo('url').'/'.basename(__DIR__));
	define('PANEL_ABSOLUTE_DIR', __DIR__);

	//Cargamos el archivo de la seccion actual
	$actualroute = routes::get();
	$secdir = 'secciones';
	$secfile = "{$secdir}/{$actualroute}.sec.php";
	if( file_exists($secfile) ) require $secfile;
	else require "{$secdir}/error404.sec.php";
	