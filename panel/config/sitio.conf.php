<?php
	/*
	* Archivo con constantes de datos de configuracion del sistema
	*/

	define('ZONA_HORARIA', 'America/Mexico_City');

	//Constante con datos para las sesiones
	define('S_PRE',			'sc_');
	define('S_ID',			S_PRE.'session_id');
	define('S_USERID',		S_PRE.'userid');
	define('S_USERNAME',	S_PRE.'username');
	define('S_USERMAIL',	S_PRE.'usermail');
	define('S_STRING',		S_PRE.'string');
	define('S_LOGING',		S_PRE.'loging');
	
	//Constantes con datos del sistema
	define('PANEL_DIR', 			'panel');
	define('MEDIA_DIR', 			'media');
	define('MEDIA_APARIENCIA_DIR', 	MEDIA_DIR.'/apariencia');
	define('MEDIA_AVATAR_DIR', 		MEDIA_DIR.'/avatar');
	define('MEDIA_BG_DIR', 			MEDIA_DIR.'/background');
	define('MEDIA_COVER_DIR', 		MEDIA_DIR.'/covers');
	define('MEDIA_FOTOS_DIR', 		MEDIA_DIR.'/fotos');
	define('MEDIA_LOGROS_DIR', 		MEDIA_DIR.'/logros');
	define('MEDIA_TIENDA_DIR', 		MEDIA_DIR.'/tienda');
