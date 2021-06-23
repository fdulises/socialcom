<?php

	require 'nucleo/logica/pedidos.php';


	if( isset($_POST['nombre']) ){
		$result = pedidos::create($_POST['nombre']);
		
		if( $result ) echo 1;
		exit();
	}
	
	$lista_pedidos = pedidos::getList();
	
	foreach( $lista_pedidos as $c => $v ){
		$lista_pedidos[$c]['estado'] = $v['estado'] ? 'Si' : 'No';
	}
	
	$mt->plantilla->setBloque('lista_pedidos', $lista_pedidos);

	$mt->plantilla->display('tpl/pedidos');