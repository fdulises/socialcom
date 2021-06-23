<?php
	
	//Activacion de modo mantenimiento
	if( MODO_MANTENIMIENTO ) event::add('beforeload', function(){
		echo "El sitio se encuentra en mantenimiento";
		exit();
	});
	
	//Evento que fuerza error 404
	function e404(){
		header('HTTP/1.0 404 Not Found');
		$GLOBALS['mt']->plantilla->display('tpl/error404');
		die();
	}
	event::add('e404', 'e404');

	//Asignacion de puntos a un usuario por determinada accion
	event::add('givepoints', function($data){
		l_usuario::givePuntos(
			$data['usuario'], $data['tipo'], $data['razon']
		);
	}, 5, 1);
	
	//Asignacion de logros por puntos conseguidos
	event::add('givepoints', function($data){
		$cantidad = DB::select(t_perfiles)
			->columns(['experiencia'])
			->where( 'id', '=', $data['usuario'] )
			->first();
		if( $cantidad )
			logros::autoAssign( $data['usuario'], $cantidad['experiencia'] );
	}, 10, 1);
	