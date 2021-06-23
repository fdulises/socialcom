<?php

	$lista_productos = tienda::listar([
		'columns' => ['id','nombre', 'precio', 'cover'],
		'tipo' => 1,
	]);
	
	foreach( $lista_productos as $k => $v ){
		$lista_productos[$k]['cover'] = $mt->getInfo('url').'/media/tienda/'.$v['cover'];
		if( strlen($v['nombre']) > 25 )
			$lista_productos[$k]['nombre'] = mb_strimwidth($v['nombre'], 0, 27).'...';
	}
	
	$mt->plantilla->setEtiqueta('USER_PUNTOS', tienda::getUserPuntos($_SESSION[S_USERID]));
	
	$mt->plantilla->setBloque('lista_productos', $lista_productos);

	$mt->plantilla->display('tpl/tienda');
