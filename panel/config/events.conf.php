<?php

	namespace wecor;
	
	//Evento que fuerza error 404
	function e404(){
		header('HTTP/1.0 404 Not Found');
		require 'secciones/error404.sec.php';
		die();
	}
	event::add('e404', 'e404');