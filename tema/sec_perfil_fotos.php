<?php

	$lista_fotos = $user::listFoto([
		'autor' => $usuario['usuario_id'],
		'columns' => ['id', 'url'],
	]);
	foreach( $lista_fotos as $k => $v ){
		$lista_fotos[$k]['url'] = $mt->getInfo('url')."/media/fotos/{$v['url']}";
	}
	
	$mt->plantilla->setBloque('lista_fotos', $lista_fotos);

	$tplsec = 'tpl/perfil_fotos';