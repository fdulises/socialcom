<?php

	//Generamos cookie para ser utilizada al mostrar enlaces de descarga
	if( !isset($_COOKIE['publiTime']) ){
		setcookie( "publiTime", date('U'), strtotime( '+30 days' ) );
	}
	if( isset($_GET['publiTime']) ){
		setcookie( "publiTime", date('U'), strtotime( '+30 days' ) );
		die();
	}

	$articulo = $mt->getEntrada(array(
		'id' => $mt->seccion['id'],
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'e.descrip as articulo_descrip',
			'e.fecha_u as articulo_fecha',
			'e.total_coment as articulo_comentarios',
			'col.nombre as articulo_coleccion_nombre',
			'enlace as articulo_enlace',
			'e.contenido as articulo_contenido',
			'e.descargas as articulo_descargas',
			'e.usuario as articulo_autor_id',
			'u.email as articulo_autor_email',
			'u.grupo as articulo_autor_grupo',
			'u.nickname as articulo_autor',
			'p.nombre as articulo_autor_nombre',
			'p.descrip as articulo_autor_descrip',
			'e.portada as articulo_portada',
			'e.hits as articulo_hits',
			'e.likes as articulo_likes',
			'e.puntos as articulo_puntos',
			'e.puntosv',
			'e.tipo as articulo_tipo',
		),
	));
	if( $articulo['articulo_tipo'] != 3 )
		$articulo = extras::htmlentities($articulo);
	
	
	$relacionados = extras::htmlentities($mt->getEntrada(array(
		'columnas' => array(
			'e.id as articulo_id',
			'e.titulo as articulo_titulo',
			'enlace as articulo_enlace',
			'e.portada as articulo_portada',
		),
		'filtro' => 'e.id <> '.$articulo['articulo_id'],
		'limit' => 4,
		'order' => 'articulo_id',
	)));
	$mt->plantilla->setBloque('relacionados', $relacionados);
	
	//Comprobamos si se piden puntos
	$cond_donarp = false;
	if( $articulo['puntosv'] && $user->logingCheck() ){
		//Comprobamos que el usuario actual no sea el autor del post
		if( $_SESSION[S_USERID] != $articulo['articulo_autor_id'] ){
			//Vermificamos si ya se donaron puntos al articulo
			$regpunteo = $mt->getPunteo([
				'autor' => $_SESSION[S_USERID],
				'destino' => $articulo['articulo_id'],
				'tipo' => 5,
			]);
			$cond_donarp = !$regpunteo['id'];
		}
	}else if( $articulo['puntosv'] ){
		//Si se piden puntos y no se ha iniciado sesion redireccionamos
		header('location: '.$mt->getInfo('url').'/acceso');
	}
	$mt->plantilla->setCondicion('donar_puntos', $cond_donarp);
	
	//Campo con enlaces de descargas
	$mt->plantilla->setCondicion('has_descargas', $articulo['articulo_descargas']);
	
	//Contador de hits
	$mt->hitsIncrement($articulo['articulo_id']);
	
	//BBCode Parser
	require_once 'nucleo/inclusiones/inc_bbcodes.php';
	$articulo['articulo_contenido'] = preg_replace(
		$bbcode['code'], $bbcode['html'], $articulo['articulo_contenido']
	);
	$articulo['articulo_contenido'] = str_replace("\r\n", "<br>", $articulo['articulo_contenido']);
	
	$articulo['articulo_descargas'] = str_replace("\r\n", "<br>", $articulo['articulo_descargas']);
	
	function convertirUrls($cadena){
		return preg_replace('/([\w]+\:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/', '<a href="$1">$0</a>', $cadena);
	}
	$articulo['articulo_descargas'] = convertirUrls($articulo['articulo_descargas']);
	
	//Mostramos banner publicitario para mostrar descargas cada x tiempo
	if( strtotime("-60 minute") > @$_COOKIE['publiTime'] ){
		
		$publiscript = "<script>
			document.addEventListener('DOMContentLoaded', function(){
				var banner_magic = document.querySelector('#banner_magic');
				selectorMultiple('#banner_magic a', function(k, nodo){
					nodo[k].setAttribute('target', '_blank');
				});
				function bmpro(){
					var arcdesm = document.querySelector('#articulo_descm');
					this.innerHTML = arcdesm.innerHTML;
					listefi.ajax({url: '?publiTime', method: 'get',});
					this.removeEventListener('click', bmpro);
				}
				banner_magic.addEventListener('click', bmpro);
			});
		</script>";
		
		$articulo_descm = "<div id='articulo_descm' style='display:none'>{$articulo['articulo_descargas']}</div>";

		$articulo['articulo_descargas'] = "<h5>Click en la publicidad para mostrar enlaces</h5><div id='banner_magic'>{$articulo_descm}{$add_t2}{$publiscript}</div>";
	}

	//Damos formato a campos necesarios
	$articulo['articulo_fecha'] = extras::formatoDate(
		$articulo['articulo_fecha'], 'd/m/Y'
	);
	$articulo['articulo_autor_avatar'] = $user->generateAvatar([
		'id' => $articulo['articulo_autor_id'],
		'email' => $articulo['articulo_autor_email'],
		'size' => 150,
	]);

	require 'config/conf_grupos.php';
	$articulo['articulo_autor_grupo'] = $grupos[$articulo['articulo_autor_grupo']]['nombre'];
	
	$mt->plantilla->setEtiqueta($articulo);

	//BLoque con lista de categorias
	$mt->plantilla->setBloque('lista_categorias', $mt->getColeccion(array(
		'columnas' => array(
			'id as categoria_id',
			'nombre as categoria_titulo',
			'enlace as categoria_enlace',
		),
		'orden' => 'nombre',
		'disp' => 'ASC',
		'tipo' => 1,
	)));

	//Obtenemos y mostramos los comentarios para la entrada
	$lista_comentarios = $mt->getComentMember(array(
		'destino' => $articulo['articulo_id'],
		'columns' => array(
			'c.id as comentario_id',
			'c.autor as comentario_autor',
			'c.usuario',
			'u.nickname',
			'u.email',
			'c.email as comentario_email',
			'c.contenido as comentario_contenido',
			'c.fecha as comentario_fecha'
		 ),
	));
	foreach( $lista_comentarios as $k => $v){
		if( !empty($v['nickname']) ){
			$lista_comentarios[$k]['comentario_autor'] = "@{$v['nickname']}";
		}
		if( !empty($v['email']) ){
			$lista_comentarios[$k]['comentario_email'] = $v['email'];
		}
		
		$lista_comentarios[$k]['comentario_avatar'] = $user->generateAvatar([
			'id' => $v['usuario'],
			'size' => 50,
			'email' => $lista_comentarios[$k]['comentario_email'],
		]);
		
		$lista_comentarios[$k]['comentario_contenido'] = nl2br($v['comentario_contenido']);
		
		unset($lista_comentarios[$k]['nickname'], $lista_comentarios[$k]['email'], $lista_comentarios[$k]['usuario']);
	}
	$lista_comentarios = extras::htmlentities($lista_comentarios, true);
	
	$mt->plantilla->setCondicion('si_comentarios', count($lista_comentarios));
	$mt->plantilla->setBloque('lista_comentarios', $lista_comentarios);

	$mt->plantilla->setEtiqueta('pagina_descrip', $articulo['articulo_descrip']);
	
	$mt->plantilla->setEtiqueta('pagina_enlace', $articulo['articulo_enlace']);
	$mt->plantilla->setEtiqueta('pagina_cover', $articulo['articulo_portada']);

	$ownercond = false;
	if( $user->logingCheck() ){
		if( $_SESSION[S_USERID] == $articulo['articulo_autor_id'] )
			$ownercond = true;
		
		$user_avatar = $user->generateAvatar([
			'id' => $_SESSION[S_USERID],
			'size' => 50,
			'email' => $_SESSION[S_USERMAIL],
		]);
		$mt->plantilla->setEtiqueta('user_avatar', $user_avatar);
	}
	$mt->plantilla->setCondicion('is_owner', $ownercond);
	$mt->plantilla->setCondicion('no_owner', $ownercond);

	//Definimos bloque con medallas de usuario
	$logros_list = logros::get($articulo['articulo_autor_id'], [
		'r.id', 'nombre', 'cover'
	]);
	$logros_dir = $mt->getInfo('url').'/'.MEDIA_LOGROS_DIR;
	foreach( $logros_list as $k => $v )
		$logros_list[$k]['cover'] = $logros_dir.'/'.$v['cover'];
	$mt->plantilla->setBloque('logros_list', $logros_list);
	
	
	$mt->plantilla->display('tpl/articulo');
