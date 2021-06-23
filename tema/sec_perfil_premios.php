<?php

	$lista_items = tienda::getItems([
		'id' => $usuario['usuario_id'],
		'columns' => [
			'id_compras as id',
			'nombre',
			'cover',
			'producto as producto_id',
			'autor'
		],
		'tipo' => 1,
	]);
	
	foreach( $lista_items as $k => $v ){
		$lista_items[$k]['cover'] = $mt->getInfo('url').'/media/tienda/'.$v['cover'];
		if( strlen($v['nombre']) > 25 )
			$lista_items[$k]['nombre'] = mb_strimwidth($v['nombre'], 0, 20).'...';
	}	
	
	$mt->plantilla->setBloque('lista_items', $lista_items);

	$tplsec = 'tpl/perfil_premios';