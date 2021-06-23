<?php
	/*
	* Facade para la seccion sitemap
	*/

	header('Content-Type: text/xml');
	$mt->plantilla->setDir('nucleo/presentacion');
	$subsec = ( isset($_GET['subsec']) ) ? $_GET['subsec'] : 'index';

	if( $subsec == 'entradas' ){
		$lista_entradas = $mt->getEntrada(array(
			'columnas' => array(
				'enlace as entrada_enlace',
				'e.fecha_u as entrada_fecha',
			),
			'tipo' => 2,
			'orden' => 'e.fecha_u',
			'disp' => 'DESC',
		));
		$mt->plantilla->setBloque('lista_entradas', $lista_entradas);
		$mt->plantilla->display('tpl/sitemap');
	}else if( $subsec == 'colecciones' ){
		$lista_entradas = $mt->getColeccion(array(
			'columnas' => array(
				'fecha as entrada_fecha',
				'enlace as entrada_enlace',
			),
			'tipo' => 1,
			'orden' => 'fecha',
			'disp' => 'DESC',
		));
		$mt->plantilla->setBloque('lista_entradas', $lista_entradas);
		$mt->plantilla->display('tpl/sitemap');
	}else{
		$mt->plantilla->display('tpl/sitemapindex');
	}
