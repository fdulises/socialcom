<?php
	/*
	* Archivo con constantes de datos de configuracion del sistema
	*/

	define('MODO_DEBUG', true);
	define('ZONA_HORARIA', 'America/Mexico_City');
	define('MODO_MANTENIMIENTO', false);

	//Constante con datos para las sesiones
	define('S_PRE',			'sc_');
	define('S_ID',			S_PRE.'session_id');
	define('S_USERID',		S_PRE.'userid');
	define('S_USERNAME',	S_PRE.'username');
	define('S_USERMAIL',	S_PRE.'usermail');
	define('S_STRING',		S_PRE.'string');
	define('S_LOGING',		S_PRE.'loging');
	
	//Carpetas para contenido media
	define('MEDIA_DIR', 			'media');
	define('MEDIA_APARIENCIA_DIR', 	MEDIA_DIR.'/apariencia');
	define('MEDIA_AVATAR_DIR', 		MEDIA_DIR.'/avatar');
	define('MEDIA_BG_DIR', 			MEDIA_DIR.'/background');
	define('MEDIA_COVER_DIR', 		MEDIA_DIR.'/covers');
	define('MEDIA_FOTOS_DIR', 		MEDIA_DIR.'/fotos');
	define('MEDIA_LOGROS_DIR', 		MEDIA_DIR.'/logros');
	define('MEDIA_TIENDA_DIR', 		MEDIA_DIR.'/tienda');
	
	//Cantidad de fotos que se pueden subir por grupo
	$permitted_pics = array(
		0 => 0,
		1 => 30,
		2 => 30,
		3 => 30,
		4 => 20,
		5 => 10,
		6 => 10,
	);

	//Activamos registro de errores
	ini_set("log_errors" , "1");
	ini_set("error_log" , "error.log.txt");
	if( !MODO_DEBUG ) ini_set("display_errors" , "0");
