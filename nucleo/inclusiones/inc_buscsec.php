<?php

	function buscaSecInterna($url){
		//Separamos la url en partes
		$partesURL = explode('/', $url);

		global $mt;
		$importantURL = $partesURL[0];
		$resultado = $mt->getSec($importantURL);
		return $resultado;
	}

	function buscaEntrada($url){
		//Separamos la url en partes
		$partesURL = explode('/', $url);

		global $mt;
		$importantURL = $partesURL[count($partesURL)-1];
		$resultado = $mt->getEntrada(array(
			'url' => $importantURL,
			'columnas' => array(
				'e.id', 'e.titulo', 'e.url', 'e.tipo', 'e.plantilla', 'enlace'
			),
		));
		if( $resultado ) foreach ($resultado as $k => $v) {
			if( "{$mt->getInfo('url')}/{$url}" == $v['enlace'] ){
				$v['filesec'] = "nucleo/secciones/sec_entrada.php";
				return $v;
			}
		}
		return array();
	}

	function buscaColeccion($url){
		//Separamos la url en partes
		$partesURL = explode('/', $url);

		global $mt;
		$importantURL = $partesURL[count($partesURL)-1];
		$resultado = $mt->getColeccion(array(
			'url' => $importantURL,
			'columnas' => array(
				'id', 'nombre as titulo', 'url', 'tipo', 'enlace'
			),
		));

		if( $resultado ) foreach ($resultado as $k => $v) {
			if( "{$mt->getInfo('url')}/{$url}" == $v['enlace'] ){
				$v['filesec'] = "nucleo/secciones/sec_archivo.php";
				return $v;
			}
		}
		return array();
	}
