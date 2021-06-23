<?php

	$cadenabusqueda = ( isset( $_GET['b'] ) ) ? trim($_GET['b']) : '';
	$userbusqueda = ( isset( $_GET['u'] ) ) ? trim($_GET['u']) : '';
	$ordenbusqueda = ( isset( $_GET['order'] ) ) ? trim($_GET['order']) : '';
	$letrabusqueda = ( isset( $_GET['filtro'] ) ) ? trim($_GET['filtro']) : '';
	$tipo= ( isset( $_GET['tipo'] ) ) ? trim($_GET['tipo']) : '';
	$tipo = ($tipo == 3) ? 3 : 2;

	$filtro_nickname = dbConnector::escape($userbusqueda);
	$filtro_orden = dbConnector::escape($ordenbusqueda);
	$filtro_texto = dbConnector::escape($cadenabusqueda);
	$filtro_letra = dbConnector::escape($cadenabusqueda);
	$filtro_tipo = dbConnector::escape($tipo);

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
		'tipo' => $tipo,
		'orden' => 'e.fecha',
		'disp' => 'DESC',
		'paginacion' => true,
		'limit' => 20,
	);
	if( $opciones['tipo'] == 3 ) $opciones['disp'] = 'ASC';
	$filtros = array();

	//Definimos el filtro busqueda por nombre de usuario
	if( !empty( $userbusqueda ) ) $filtros[] = "nickname='{$filtro_nickname}'";

	//Definimos el filtro busqueda por texto
	if( str_word_count($filtro_texto) < 2 && !empty($filtro_texto) ){
		//Filtro busqueda exacta
		$filtros[] = "(
			e.titulo LIKE '%{$filtro_texto}%' OR
			e.descrip LIKE '%{$filtro_texto}%' OR
			e.tags LIKE '%{$filtro_texto}%'
		)";
		//Filtro busqueda por letra
		if( !empty($filtro_letra) ){
			if( isset( $_GET['fn'] ) ) $filtros[] = "e.titulo regexp '^[0-9\W].'";
			else $filtros[] = "e.titulo LIKE '{$filtro_texto}%'";
		}
	}else if( $filtro_texto ){
		//Filtro busqueda coincidencias
		$opciones['busqueda'] = array(
			'campos' => array('e.titulo', 'e.descrip'),
			'cadena' => $filtro_texto,
		);
		$opciones['orden'] = 'score';
	}

	//Agregamos los filtros para la busqueda
	$filtros = implode(' AND ', $filtros);
	if( !empty($filtros) ) $opciones['filtro'] = $filtros;

	//Definimos el orden de busqueda
	if( !empty($ordenbusqueda) ){
		$opciones['orden'] = dbConnector::escape($ordenbusqueda);
	}

	//Arreglar error de ordenar por likes
	if( isset($opciones['orden']) ){if( $opciones['orden'] == 'likes' ) $opciones['orden'] = 'e.likes';}
	
	//Hacemos la peticion de las entradas
	$lista_articulos = $mt->getEntrada($opciones);

	//Algunas etiquetas informativas
	$cadenabusqueda = htmlentities($cadenabusqueda);
	$ordenbusqueda = htmlentities($ordenbusqueda);
	$userbusqueda = htmlentities($userbusqueda);
	$mt->plantilla->setEtiqueta(array(
		'pagina_titulo' => "Resultados de busqueda para: {$cadenabusqueda}",
		'cadenabusqueda' => $cadenabusqueda,
		'userbusqueda' => $userbusqueda,
	));
	
	$lista_articulos['entradas'] = extras::htmlentities($lista_articulos['entradas']);

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

	$bparametros = array();
	if( isset( $_GET['order'] ) ) $bparametros[] = "order={$_GET['order']}";
	if( isset( $_GET['filtro'] ) ) $bparametros[] = "filtro={$_GET['filtro']}";
	if( isset( $_GET['u'] ) ) $bparametros[] = "u={$_GET['u']}";
	if( isset( $_GET['b'] ) ) $bparametros[] = "b={$_GET['b']}";
	if( isset( $_GET['tipo'] ) ) $bparametros[] = "tipo={$_GET['tipo']}";
	$bparametros = implode('&', $bparametros);

	$enlace_anterior = "{$enlace_anterior}?{$bparametros}";
	$enlace_siguiente = "{$enlace_siguiente}?{$bparametros}";

	$mt->plantilla->setEtiqueta(array(
		'paginacion_enlace_a' => $enlace_anterior,
		'paginacion_enlace_s' => $enlace_siguiente,
	));

	$mt->plantilla->display('tpl/blog');
