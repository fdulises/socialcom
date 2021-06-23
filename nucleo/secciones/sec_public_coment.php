<?php
	$superior = isset($_GET['id']) ? $_GET['id'] : 0;
	$pagina = isset($_GET['p']) ? $_GET['p'] : 0;
	$total = 10;
	$offset = ceil( ($pagina)*$total );
	
	$lista_publicaciones = $user->getPublication([
		'superior' => $superior,
		'columns' => [
			'A.id as public_id',
			'A.destino as public_destino',
			'A.autor as public_autor_id',
			'A.contenido as public_contenido',
			'A.fecha as public_fecha',
			'A.contador as public_total_coment',
			'B.nickname as public_autor',
			'B.email as public_email',
		],
		'limit' => "{$offset}, {$total}"
	]);
	if( $lista_publicaciones ){
		foreach ($lista_publicaciones as $k => $v) {
			$lista_publicaciones[$k]['public_avatar'] = $user->generateAvatar([
				'id' => $v['public_autor_id'],
				'email' => $v['public_email'],
				'size' => 100,
			]);
			$lista_publicaciones[$k]['public_fecha'] = extras::formatoDate($v['public_fecha'], 'd/m/Y');
		}
	}
	foreach( $lista_publicaciones as $v ){
		$delbtncond = false;
		if( $user->logingCheck() ){
			$delbtncond1 = ($_SESSION[S_USERID] == $v['public_autor_id']);
			$delbtncond2 = ($_SESSION[S_USERID] == $v['public_destino']);
			$delbtncond = $delbtncond1 || $delbtncond2;
		}
		require "{$mt->getInfo('tema_url')}/tpl/html/public_coment.tpl";
	}