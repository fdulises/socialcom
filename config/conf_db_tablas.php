<?php

	define('t_colecciones', DB_PREF.'colecciones');
	define('t_comentarios', DB_PREF.'comentarios');
	define('t_entradas', DB_PREF.'entradas');
	define('t_perfiles', DB_PREF.'perfil');
	define('t_sesiones', DB_PREF.'sesiones');
	define('t_sesioneserr', DB_PREF.'intentosf');
	define('t_sitio', DB_PREF.'sitio');
	define('t_temas', DB_PREF.'temas');
	define('t_usuarios', DB_PREF.'usuario');
	define('t_publicaciones', DB_PREF.'publicaciones');
	define('t_follow', DB_PREF.'follow');
	define('t_likes', DB_PREF.'likes');
	define('t_denuncias', DB_PREF.'denuncias');
	define('t_puntos', DB_PREF.'puntos');
	define('t_productos', DB_PREF.'productos');
	define('t_compras', DB_PREF.'compras');
	define('t_fotos', DB_PREF.'fotos');
	define('t_logros', DB_PREF.'logros');
	define('t_logros_registro', DB_PREF.'logros_registro');
	define('t_listascrap', DB_PREF.'listascrap');
	define('t_pedidos', DB_PREF.'pedidos');
	define('t_referidos', DB_PREF.'referidos');
	define('t_adds', DB_PREF.'adds');

	class db_tablas{
		protected function __construct(){}

		public $t_colecciones = t_colecciones;
		public $t_comentarios = t_comentarios;
		public $t_entradas = t_entradas;
		public $t_perfiles = t_perfiles;
		public $t_sesiones = t_sesiones;
		public $t_sesioneserr = t_sesioneserr;
		public $t_sitio = t_sitio;
		public $t_temas = t_temas;
		public $t_usuarios = t_usuarios;
	}
