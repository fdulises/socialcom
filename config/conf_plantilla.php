<?php

	$add_t1 = adds::getRotate(['tipo'=>1]);
	$add_t1 = $add_t1 ? $add_t1['codigo'] : '';
	$add_t2 = adds::getRotate(['tipo'=>2]);
	$add_t2 = $add_t2 ? $add_t2['codigo'] : '';

	$mt->plantilla->setEtiqueta(array(
		'SITIO_TITULO' => $mt->getInfo('titulo'),
		'SITIO_DESCRIP' => $mt->getInfo('descrip'),
		'SITIO_URL' => $mt->getInfo('url'),
		'SITIO_SEC' => $mt->seccion['url'],
		'SITIO_EMAIL' => $mt->getInfo('email'),
		'pagina_titulo' => $mt->seccion['titulo'],
		'pagina_enlace' => $mt->seccion['enlace'],
		'pagina_descrip' => $mt->getInfo('descrip'),
		'TEMA_URL' => "{$mt->getInfo('url')}/{$mt->getInfo('tema_url')}",
		'PAGINA_TIPO' => $mt->seccion['tipo'],
		'SITIO_LOGIN' => $user->logingCheck(),
		'IS_ENTRADA' => ('nucleo/secciones/sec_entrada.php' == $mt->seccion['filesec']) ? 1 : 0,
		'S_USERNAME' => isset($_SESSION[S_USERNAME]) ? $_SESSION[S_USERNAME] : 'visitante',
		'S_USERID' => isset($_SESSION[S_USERID]) ? $_SESSION[S_USERID] : 0,
		'conf_pp_max' => $mt->getInfo('conf_pp_max'),
		'conf_pp_entrada' => $mt->getInfo('conf_pp_entrada'),
		'conf_pp_coment' => $mt->getInfo('conf_pp_coment'),
		'conf_pp_registro' => $mt->getInfo('conf_pp_registro'),
		'conf_pp_referido' => $mt->getInfo('conf_pp_referido'),
		'conf_code_f' => $mt->getInfo('conf_code_f'),
		'conf_code_t' => $mt->getInfo('conf_code_t'),
		'conf_link_f' => $mt->getInfo('conf_link_f'),
		'conf_link_t' => $mt->getInfo('conf_link_t'),
		'field_add1' => $mt->getInfo('field_add1'),
		'field_add2' => $mt->getInfo('field_add2'),
		'field_add3' => $mt->getInfo('field_add3'),
		'conf_fbappid' => $mt->getInfo('conf_fbappid'),
		'add_t1' => $add_t1,
		'add_t2' => $add_t2,
	));

	$mt->plantilla->setCondicion('IS_LOGIN', $user->logingCheck());
	$mt->plantilla->setCondicion('NO_LOGIN', !$user->logingCheck());
	
	if( $user->logingCheck() )
		$userGetGrupo = $user->getGrupo();
	else $userGetGrupo = 7;
	$mt->plantilla->setCondicion('MIN_GROUP_1', ($userGetGrupo == 1));
	$mt->plantilla->setCondicion('MIN_GROUP_2', ($userGetGrupo <= 2));
	$mt->plantilla->setCondicion('MIN_GROUP_3', ($userGetGrupo <= 3));
	$mt->plantilla->setCondicion('MIN_GROUP_4', ($userGetGrupo <= 4));
	$mt->plantilla->setCondicion('MIN_GROUP_5', ($userGetGrupo <= 5));
	$mt->plantilla->setCondicion('MIN_GROUP_6', ($userGetGrupo <= 6));
	
	$mt->plantilla->setEtiqueta('pagina_cover', '');