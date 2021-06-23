<?php

	$cond = isset( $_POST['usuario_email'], $_POST['usuario_nombre'], $_POST['usuario_sexo'], $_POST['fd'], $_POST['fm'], $_POST['fa'], $_POST['usuario_descrip'], $_POST['usuario_facebook'] );
	if( $cond ){
		$nacimiento = "{$_POST['fa']}-{$_POST['fm']}-{$_POST['fd']}";
		$estado = 0;
		$error = array();

		if( !filter_var($_POST['usuario_email'], FILTER_VALIDATE_EMAIL) )
			$error[] = 'email_incorrecto';
		if( !filter_var($_POST['usuario_facebook'], FILTER_VALIDATE_URL) && !empty($_POST['usuario_facebook']) )
			$error[] = 'facebook_incorrecto';
		if( !filter_var($_POST['usuario_twitter'], FILTER_VALIDATE_URL) && !empty($_POST['usuario_twitter']) )
			$error[] = 'twitter_incorrecto';

		$resultado = false;
		if( !$error ) $resultado = $user->update( $_SESSION[S_USERID], array(
			'u.email' => $_POST['usuario_email'],
			'u.ip' => $_SERVER['REMOTE_ADDR'],
			'p.nombre' => $_POST['usuario_nombre'],
			'p.sexo' => (INT) $_POST['usuario_sexo'],
			'p.nacimiento' => $nacimiento,
			'p.descrip' => $_POST['usuario_descrip'],
			'p.s_facebook' => $_POST['usuario_facebook'],
			'p.s_twitter' => $_POST['usuario_twitter'],
			'p.s_google' => $_POST['usuario_whatsapp'],
		));
		if( $resultado ){
			//Procesamos avatar de usuario
			if( isset($_FILES['avatar']) ){
				if( 0 == $_FILES['avatar']['error'] ){
					$avatarresult = $user->avatarProcess($_SESSION[S_USERID]);
					if( !$avatarresult ) $error[] = 'avatar_incorrecto';
				}
			}
			//Procesamos portada de usuario
			if( isset($_FILES['cover']) ){
				if( 0 == $_FILES['cover']['error'] ){
					$coverresult = $user->coverProcess($_SESSION[S_USERID]);
					if( !$coverresult )$error[] = 'cover_incorrecto';
				}
			}
		}
		if( !count($error) ) $estado = 1;
		echo json_encode(['estado' => $estado, 'error' => $error]);
		exit();
	}

	$usuario = extras::htmlentities($user->get([
		'id' => $_SESSION[S_USERID],
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
		]
	]));
	//Definimos datos de fecha de nacimiento
	$usuario['usuario_fd'] = extras::formatoDate($usuario['usuario_nacimiento'], 'd');
	$usuario['usuario_fm'] = extras::formatoDate($usuario['usuario_nacimiento'], 'n');
	$usuario['usuario_fa'] = extras::formatoDate($usuario['usuario_nacimiento'], 'Y');

	//Definimos avatar y portada de usuario
	$usuario['usuario_avatar'] = $user->generateAvatar([
		'id' => $usuario['usuario_id'],
		'email' => $usuario['usuario_email'],
		'size' => 600,
	]);
	$usuario['usuario_cover'] = $user->generateBackground($usuario['usuario_id']);

	$mt->plantilla->setEtiqueta($usuario);

	//Generamos lista con los dias del mes
	$lista_fd = array();
	for( $i = 1; $i <= 31; $i++ )
		$lista_fd[] = array( 'valor' => $i, 'nombre' => $i );
	$mt->plantilla->setBloque('lista_fd', $lista_fd);

	//Generamos lista con los meses
	$lista_fm = array();
	foreach($lista_meses as $k => $v)
		$lista_fm[] = array( 'valor' => $k+1, 'nombre' => $v );
	$mt->plantilla->setBloque('lista_fm', $lista_fm);

	//Generamos lista con los años
	$lista_fa = array();
	for( $i = (date('Y')-12); $i>(date('Y')-100); $i-- )
		$lista_fa[] = array( 'valor' => $i, 'nombre' => $i );
	$mt->plantilla->setBloque('lista_fa', $lista_fa);

	$mt->plantilla->display('tpl/cuenta');
