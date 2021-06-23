<?php
	$opciones = array(
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
		'orden' => 'e.fecha',
		'disp' => 'DESC',
		'paginacion' => true,
		'limit' => 20,
		'coleccion_url' => $mt->seccion['url'],
	);
	$filtros = array();

	//Hacemos la peticion de las entradas
	$lista_articulos = $mt->getEntrada($opciones);

	//Mostramos/ocultamos contendor de articulos
	$mt->plantilla->setCondicion('si_articulos', count($lista_articulos['entradas']));
	$mt->plantilla->setCondicion('no_articulos', !count($lista_articulos['entradas']));
	$mt->plantilla->setBloque('lista_articulos', $lista_articulos['entradas']);

	//Generamos etiquetas con la ruta de las paginas anterior/siguiente
	$paginacion_cond = ( $lista_articulos['paginacion']['enlace_a'] || $lista_articulos['paginacion']['enlace_s']);
	$mt->plantilla->setCondicion('si_paginacion', $paginacion_cond);
	$mt->plantilla->setCondicion('is_paginacion_a', $lista_articulos['paginacion']['enlace_a']);
	$mt->plantilla->setCondicion('is_paginacion_s', $lista_articulos['paginacion']['enlace_s']);
	$enlace_anterior = "{$mt->seccion['enlace']}/pagina/{$lista_articulos['paginacion']['enlace_a']}";
	$enlace_siguiente = "{$mt->seccion['enlace']}/pagina/{$lista_articulos['paginacion']['enlace_s']}";
	$mt->plantilla->setEtiqueta(array(
		'paginacion_enlace_a' => $enlace_anterior,
		'paginacion_enlace_s' => $enlace_siguiente,
	));

	$mt->plantilla->display('tpl/archivo');
