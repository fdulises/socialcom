<?php

	if( !($userGetGrupo <= 5) ) event::fire('e404');  

	if( isset($_GET['crear']) ){
		$datos = array();
		$datos['titulo'] = ( isset($_POST['titulo']) ) ? $_POST['titulo'] : '';
		$datos['url'] = ( isset($_POST['url']) ) ? $_POST['url'] : '';
		$datos['contenido'] = ( isset($_POST['contenido']) ) ? $_POST['contenido'] : '';
		$datos['descrip'] = ( isset($_POST['descrip']) ) ? $_POST['descrip'] : '';
		$datos['categoria'] = ( isset($_POST['categoria']) ) ? $_POST['categoria'] : '';
		$datos['puntosv'] = ( isset($_POST['puntosv']) ) ? $_POST['puntosv'] : 0;
		$datos['cover_url'] = ( isset($_POST['cover_url']) ) ? $_POST['cover_url'] : '';
		$datos['descargas'] = ( isset($_POST['descargas']) ) ? $_POST['descargas'] : '';

		if( $mt->createEntrada($datos) ){
			echo json_encode(array('estado' => 1, 'error' => 0));
        }else echo json_encode(array('estado' => 0, 'error' => $mt->error));
        exit();
	}

	if( isset($_GET['editar'],$_GET['id']) ){
		$datos = array();
		$datos['titulo'] = ( isset($_POST['titulo']) ) ? $_POST['titulo'] : '';
		$datos['url'] = ( isset($_POST['url']) ) ? $_POST['url'] : '';
		$datos['contenido'] = ( isset($_POST['contenido']) ) ? $_POST['contenido'] : '';
		$datos['descrip'] = ( isset($_POST['descrip']) ) ? $_POST['descrip'] : '';
		$datos['categoria'] = ( isset($_POST['categoria']) ) ? $_POST['categoria'] : '';
		$datos['puntosv'] = ( isset($_POST['puntosv']) ) ? $_POST['puntosv'] : 0;
		$datos['cover_url'] = ( isset($_POST['cover_url']) ) ? $_POST['cover_url'] : '';
		$datos['descargas'] = ( isset($_POST['descargas']) ) ? $_POST['descargas'] : '';
		if( $mt->updateEntrada($datos) ) echo json_encode(array('estado' => 1, 'error' => 0));
        else echo json_encode(array('estado' => 0, 'error' => $mt->error));
        exit();
	}

	//Definimos etiquetas con datos de la entrada vacios por defecto
	$mt->plantilla->setEtiqueta([
		'entrada_titulo' => '',
    	'entrada_url' => '',
    	'entrada_contenido' => '',
    	'entrada_descrip' => '',
    	'entrada_categoria' => '',
    	'entrada_puntosv' => '',
    	'entrada_cover' => '',
    	'entrada_descargas' => '',
		'entrada_action' => '?crear',
	]);

	//Obtenemos lista de categorias
	$lista_categorias = $mt->getColeccion(null, ['id', 'nombre']);
	foreach ($lista_categorias as $k => $v) $lista_categorias[$k]['selected'] = '';

	if( isset($_GET['id']) ){
		//Obtenemos datos de entrada a editar
		$_GET['id'] = (int) $_GET['id'];
		$editard = DB::select(t_entradas)
			->columns(['titulo as entrada_titulo', 'url as entrada_url', 'contenido as entrada_contenido', 'descrip as entrada_descrip', 'coleccion as entrada_categoria', 'puntosv as entrada_puntosv', 'portada as entrada_cover', 'descargas as entrada_descargas'])
			->where('id', '=', $_GET['id'])
			->first();
		
		//Llenamos las etiquetas con los datos del post
		if($editard){
			
			$mt->plantilla->setEtiqueta('entrada_action', "?editar&id={$_GET['id']}");
			$mt->plantilla->setEtiqueta($editard);
			foreach ($lista_categorias as $k => $v) {
				$selected = '';
				if( $v['id'] == $editard['entrada_categoria'] ) $selected = 'selected';
				$lista_categorias[$k]['selected'] = $selected;
			}
		}
		
		//echo l_entrada::getAutor($_GET['id']);
	}

	//Creamos bloque dinamico con la lista de las categorias
	$mt->plantilla->setBloque('lista_categorias', $lista_categorias);

	$mt->plantilla->display('tpl/post');
