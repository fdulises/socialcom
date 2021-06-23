<?php

	$is_loging = $user->logingCheck();
	$nickname = htmlentities($_GET['usuario']);

	//Obtenemos los datos del usuario solicitado
	$usuario = extras::htmlentities($user->get([
		'nickname' => $_GET['usuario'],
		'columns' => [
			'u.id as usuario_id',
			'u.nickname as usuario_nickname',
			'u.email as usuario_email',
			'u.ip as usuario_ip',
			'u.fregistro as usuario_fregistro',
			'u.grupo as usuario_grupo',
			'u.total_e as usuario_entradas',
			'p.nombre as usuario_nombre',
			'p.sexo as usuario_sexo',
			'p.nacimiento as usuario_nacimiento',
			'p.descrip as usuario_descrip',
			'p.s_facebook as usuario_facebook',
			'p.s_twitter as usuario_twitter',
			'p.s_google as usuario_whatsapp',
			'p.puntos as usuario_puntos',
			'p.experiencia as usuario_experiencia',
			'p.seguidores as usuario_seguidores',
		]
	]));
	if( $usuario ){
		$tplsec = 'tpl/perfil';
		//Definimos avatar y portada de usuario
		$usuario['usuario_avatar'] = $user->generateAvatar([
			'id' => $usuario['usuario_id'],
			'email' => $usuario['usuario_email'],
			'size' => 600,
		]);
		$usuario['usuario_cover'] = $user->generateBackground($usuario['usuario_id'], '');
		$mt->plantilla->setEtiqueta($usuario);

		//Obtememos lista Ultimos temas agregados
		$lista_ultimos = extras::htmlentities($mt->getEntrada(array(
			'columnas' => array(
				'e.id as articulo_id',
				'e.titulo as articulo_titulo',
				'e.portada as articulo_portada',
				'e.fecha_u as articulo_fecha',
				'enlace as articulo_enlace',
			),
			'tipo' => 2,
			'orden' => 'e.fecha_u',
			'disp' => 'DESC',
			'limit' => 6,
			'usuario' => $_GET['usuario'],
		)), true);
		$lista_ultimos = array_map(function($k){
			$k['articulo_titulo'] = mb_strimwidth($k['articulo_titulo'], 0, 25);
			return $k;
		}, $lista_ultimos);
		$mt->plantilla->setCondicion('si_ultimos', count($lista_ultimos));
		$mt->plantilla->setBloque('lista_ultimos', $lista_ultimos);

		//Definimos condicionales is_owner/no_owner
		$ownercond = false;
		if( $is_loging ){
			if( $_SESSION[S_USERID] == $usuario['usuario_id'] ) $ownercond = true;
		}
		$mt->plantilla->setCondicion('is_owner', $ownercond);
		$mt->plantilla->setCondicion('no_owner', !$ownercond);

		$follow_tag = 0;
		if( $is_loging ){
			$is_follow = $user->getFollow($_SESSION[S_USERID], $usuario['usuario_id']);
			if( $is_follow ) $follow_tag = 1;
		}
		$mt->plantilla->setEtiqueta('follow_tag', $follow_tag);
		
		//Obtenemos lista de usuarios seguidos
		$lista_follows = DB::select(t_follow.' f')
			->leftJoin(t_usuarios.' u', 'f.destino', '=', 'u.id')
			->columns([
				'f.id',
				'u.nickname',
				'u.email',
			])
			->where('autor', '=', $usuario['usuario_id'])
			->limit(30)->order('f.id DESC')
			->get();
		foreach($lista_follows as $k => $v){
			$lista_follows[$k]['avatar'] = $user->generateAvatar([
				'id' => $v['id'],
				'email' => $v['email'],
				'size' => 25,
			]);
			$lista_follows[$k]['enlace'] = "{$mt->getInfo('url')}/@{$v['nickname']}";
		}
		$mt->plantilla->setBloque('lista_follows', $lista_follows);
		
		//Obtenemos lista de entradas de usuarios seguidos
		$result_follow = DB::select(t_follow)
			->columns(['destino'])
			->where('autor', '=', $usuario['usuario_id'])
			->limit(5)->order('id DESC')
			->get();
		$cond_follow = [];
		foreach( $lista_follows as $v )
			$cond_follow[] = "usuario = {$v['id']}";
		if( count($cond_follow) && $ownercond ){
			$cond_follow = '('.implode(" OR ", $cond_follow).')';
	
			$lista_eseguidos = DB::select(t_entradas.' e')
				->leftJoin(t_colecciones.' c', 'e.coleccion', '=', 'c.id')
				->columns([
					'e.id',
					'e.titulo',
					'e.url',
					'e.coleccion',
					'e.portada',
					'c.url as coleccion_url',
				])
				->where('e.estado', '=', 1)
				->where('e.tipo', '=', 2)
				->where($cond_follow)
				->limit(10)
				->order('e.fecha_u DESC')
				->get();
				
			foreach( $lista_eseguidos as $k => $v ){
				$lista_eseguidos[$k]['enlace'] = "{$mt->getInfo('url')}/{$v['coleccion_url']}/{$v['url']}";
			}
			
			$mt->plantilla->setBloque('lista_eseguidos', $lista_eseguidos);
		}else $mt->plantilla->setCondicion('lista_eseguidos', '');
	}

	if( $usuario && !isset($_GET['subsec']) ){
		//Obtememos lista de publicaciones de este perfil
		$lista_publicaciones = $user->getPublication([
			'destino' => $usuario['usuario_id'],
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
			'limit' => 10,
			'superior' => "0",
		]);
		if( $lista_publicaciones ){
			foreach ($lista_publicaciones as $k => $v) {
				$lista_publicaciones[$k]['public_avatar'] = $user->generateAvatar([
					'id' => $v['public_autor_id'],
					'email' => $v['public_email'],
					'size' => 200,
				]);
				$lista_publicaciones[$k]['public_fecha'] = extras::formatoDate($v['public_fecha'], 'd/m/Y').' a las '.extras::formatoDate($v['public_fecha'], 'H:i').' horas';
			}
		}
		$mt->plantilla->setCondicion('no_publiclist', !count($lista_publicaciones));
		$mt->plantilla->setBloque('lista_publicaciones', $lista_publicaciones);
	}else if( $usuario && isset($_GET['subsec']) ){	
		if( $_GET['subsec'] == 'entradas' )
			require "{$mt->getInfo('tema_url')}/sec_perfil_entradas.php";
		else if( $_GET['subsec'] == 'fotos' )
			require "{$mt->getInfo('tema_url')}/sec_perfil_fotos.php";
		else if( $_GET['subsec'] == 'premios' )
			require "{$mt->getInfo('tema_url')}/sec_perfil_premios.php";
		else if( $_GET['subsec'] == 'medallas' )
			require "{$mt->getInfo('tema_url')}/sec_perfil_medallas.php";
		else if( $_GET['subsec'] == 'info' )
			require "{$mt->getInfo('tema_url')}/sec_perfil_info.php";
	}

	//Finalmente mostramos el archivo de plantilla correspondiente
	$mt->plantilla->display($tplsec);
