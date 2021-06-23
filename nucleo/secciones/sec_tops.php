<?php

	//Lista usuarios top entradas
	$lista_ue = $user->getList([
		'columns' => [
			'u.id',
			'u.nickname',
			'u.email',
			'u.total_e',
			'p.experiencia',
			'p.seguidores',
		],
		'order' => 'total_e DESC',
		'limit' => 10,
	]);
	foreach( $lista_ue as $k => $v ){
		$lista_ue[$k]['avatar'] = $user->generateAvatar([
			'id' => $v['id'],
			'email' => $v['email'],
			'size' => 20,
		]);
	}
	
	//Lista usuarios top seguidores
	$lista_us = $user->getList([
		'columns' => [
			'u.id',
			'u.nickname',
			'u.email',
			'u.total_e',
			'p.experiencia',
			'p.seguidores',
		],
		'order' => 'seguidores DESC',
		'limit' => 10,
	]);
	foreach( $lista_us as $k => $v ){
		$lista_us[$k]['avatar'] = $user->generateAvatar([
			'id' => $v['id'],
			'email' => $v['email'],
			'size' => 20,
		]);
	}
	
	//Lista usuarios top puntos
	$lista_ux = $user->getList([
		'columns' => [
			'u.id',
			'u.nickname',
			'u.email',
			'u.total_e',
			'p.experiencia',
			'p.seguidores',
		],
		'order' => 'experiencia DESC',
		'limit' => 10,
	]);
	foreach( $lista_ux as $k => $v ){
		$lista_ux[$k]['avatar'] = $user->generateAvatar([
			'id' => $v['id'],
			'email' => $v['email'],
			'size' => 20,
		]);
	}
	
	
	//LIsta articulos likes
	$lista_likes = $mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
			'e.likes as articulo_likes',
		),
		'tipo' => 2,
		'orden' => 'e.likes',
		'disp' => 'DESC',
		'limit' => 10,
	));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_likes as $k => $v) {
		$lista_likes[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	
	//Lista articulo hits
	$lista_hits = $mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
			'e.hits as articulo_hits',
		),
		'tipo' => 2,
		'orden' => 'e.hits',
		'disp' => 'DESC',
		'limit' => 10,
	));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_hits as $k => $v) {
		$lista_hits[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	
	//LIsta articulos puntos
	$lista_puntos = $mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
			'e.likes as articulo_likes',
			'e.puntos as articulo_puntos',
		),
		'tipo' => 2,
		'orden' => 'e.puntos',
		'disp' => 'DESC',
		'limit' => 10,
	));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_puntos as $k => $v) {
		$lista_puntos[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	
	
	$mt->plantilla->setBloque('lista_ue', extras::htmlentities($lista_ue));
	$mt->plantilla->setBloque('lista_ux', extras::htmlentities($lista_ux));
	$mt->plantilla->setBloque('lista_us', extras::htmlentities($lista_us));
	$mt->plantilla->setBloque('lista_likes', extras::htmlentities($lista_likes));
	$mt->plantilla->setBloque('lista_puntos', extras::htmlentities($lista_puntos));
	$mt->plantilla->setBloque('lista_hits', extras::htmlentities($lista_hits));

	$mt->plantilla->display('tpl/tops');