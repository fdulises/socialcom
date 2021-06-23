<?php
	/*
	* Facade para la seccion rss
	*/

	header('Content-Type: text/xml');

	$lista_entradas = $mt->getEntrada(array(
		'columnas' => array(
			'e.id as entrada_id',
			'e.titulo as entrada_titulo',
			'enlace as entrada_enlace',
			'e.descrip as entrada_descrip',
			'e.fecha as entrada_fecha',
			'e.fecha_u as entrada_fecha_u',
			'e.portada as entrada_portada',
			'col.nombre as entrada_coleccion_nombre',
		),
		'orden' => 'e.fecha_u',
		'disp' => 'DESC',
	));

	//Mostramos/ocultamos contendor de entradas
	$mt->plantilla->setBloque('lista_entradas', $lista_entradas);

	$mt->plantilla->setDir('nucleo/presentacion');
	$mt->plantilla->display('tpl/rss');
