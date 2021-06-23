<?php

	$lista_referidos = referidos::getList($_SESSION[S_USERID]);
	
	foreach( $lista_referidos as $k => $v ){
		$lista_referidos[$k]['fecha'] = extras::formatoDate($v['fecha'], 'd/m/Y');
	}

	$mt->plantilla->setBloque('lista_referidos', $lista_referidos);
	
	$mt->plantilla->setEtiqueta('S_USERID', $_SESSION[S_USERID]);

	$mt->plantilla->display('tpl/referidos');