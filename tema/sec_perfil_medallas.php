<?php

	$logros_list = logros::get($usuario['usuario_id'], [
		'r.id', 'nombre', 'cover', 'fecha', 'logro_id'
	]);
	$logros_dir = $mt->getInfo('url').'/'.MEDIA_LOGROS_DIR;
	foreach( $logros_list as $k => $v ){
		$logros_list[$k]['cover'] = $logros_dir.'/'.$v['cover'];
	}
	
	$mt->plantilla->setBloque('logros_list', $logros_list);

	$tplsec = 'tpl/perfil_medallas';