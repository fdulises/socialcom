<?php

	//Creamos token en caso de ser referid
	referidos::setCookieRef();

	//Lista articulos slide
	$lista_slide = extras::htmlentities($mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
		),
		'tipo' => 2,
		'orden' => 'e.fecha_u',
		'disp' => 'DESC',
		'limit' => 5,
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_slide as $k => $v) {
		$lista_slide[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	$mt->plantilla->setCondicion('si_slide', count($lista_slide));
	$mt->plantilla->setBloque('lista_slide', $lista_slide);

	//LIsta articulos categoria en emision
	$lista_emision = extras::htmlentities($mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
		),
		'tipo' => 2,
		'orden' => 'e.fecha_u',
		'disp' => 'DESC',
		'limit' => 15,
		'coleccion_url' => 'emision',
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_emision as $k => $v) {
		$lista_emision[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	$mt->plantilla->setCondicion('si_emision', count($lista_emision));
	$mt->plantilla->setBloque('lista_emision', $lista_emision);

	//LIsta articulos likes
	$lista_likes = extras::htmlentities($mt->getEntrada(array(
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
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_likes as $k => $v) {
		$lista_likes[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	$mt->plantilla->setCondicion('si_likes', count($lista_likes));
	$mt->plantilla->setBloque('lista_likes', $lista_likes);

	//Lista articulo hits
	$lista_hits = extras::htmlentities($mt->getEntrada(array(
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
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_hits as $k => $v) {
		$lista_hits[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	$mt->plantilla->setCondicion('si_hits', count($lista_hits));
	$mt->plantilla->setBloque('lista_hits', $lista_hits);

	//Lista articulos ultimos
	$lista_ultimos = extras::htmlentities($mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
		),
		'tipo' => 2,
		'orden' => 'e.fecha_u',
		'disp' => 'DESC',
		'limit' => 20,
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_ultimos as $k => $v) {
		$lista_ultimos[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo']), 0, 25);
	}
	$mt->plantilla->setCondicion('si_ultimos', count($lista_ultimos));
	$mt->plantilla->setBloque('lista_ultimos', $lista_ultimos);

	//Lista articulos ultimos
	$lista_ultimos2 = array_slice($lista_ultimos, 0, 12);
	$mt->plantilla->setCondicion('si_ultimos2', count($lista_ultimos2));
	$mt->plantilla->setBloque('lista_ultimos2', $lista_ultimos2);
	
	//Lista webscrap
	$lista_webscrap = extras::htmlentities($mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.portada as articulo_portada',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
		),
		'tipo' => 3,
		'orden' => 'e.fecha_u',
		'disp' => 'DESC',
		'limit' => 8,
	)));
	//Recortamos el ancho del titulo y escapamos caracteres html
	foreach ($lista_webscrap as $k => $v) {
		$lista_webscrap[$k]['articulo_titulo'] = mb_strimwidth(htmlentities($v['articulo_titulo'],ENT_COMPAT,"ISO-8859-1"), 0, 25);
	}
	$mt->plantilla->setCondicion('si_webscrap', count($lista_webscrap));
	$mt->plantilla->setCondicion('has_webscrap_pag', count($lista_webscrap) >=8);
	$mt->plantilla->setBloque('lista_webscrap', $lista_webscrap);

	$mt->plantilla->display('tpl/inicio');
