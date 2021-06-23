<?php
	/*
	* Facade para la seccion entrada
	*/
	
	$mt->plantilla->setEtiqueta('SITIO_MAXP', $mt->getInfo('conf_pp_max'));

	if( isset( $_GET['comentar'] ) ){
		if( $user->logingCheck() && isset($_POST['contenido']) ){
			$datos = array(
				'destino' =>  $mt->seccion['id'],
				'contenido' => $_POST['contenido'],
			);
			echo $mt->setComentMember($datos);
		}else{
			$datos = array(
				'destino' =>  $mt->seccion['id'],
				'autor' => '',
				'email' => '',
				'sitio' => '',
				'contenido' => '',
			);

			if( isset($_POST['autor']) ) $datos['autor'] = $_POST['autor'];
			if( isset($_POST['email']) ) $datos['email'] = $_POST['email'];
			if( isset($_POST['sitio']) ) $datos['sitio'] = $_POST['sitio'];
			if( isset($_POST['contenido']) ) $datos['contenido'] = $_POST['contenido'];

			echo($mt->setComentario($datos));
		}
		exit();
	}else if( isset( $_GET['like'], $_GET['tipo'] ) ){
		$result = -1;
		$tipo = ($_GET['tipo'] >= 1) ? 1 : -1;
		if( $user->logingCheck() )
			$result = $mt->likeEntrada($_GET['like'], $tipo);
		echo json_encode(['estado'=>$result]);
		exit();
	}else if( isset( $_GET['puntear'], $_GET['cantidad'] ) ){
		$result = -1;
		if( $user->logingCheck() && $_GET['cantidad'] > 0 && $_GET['cantidad'] <= $mt->getInfo('conf_pp_max') ) $result = $mt->ePuntosProcess(array(
			'id' => $_GET['puntear'],
			'cantidad' => $_GET['cantidad'],
			'tipo' => 2,
		));
		echo json_encode(['estado'=>$result]);
		exit();
	}else if( isset( $_GET['denunciar'], $_GET['tipo'] ) ){
		$result = -1;
		if( $user->logingCheck() ) $result = $mt->setDenuncia(array(
			'destino' => $_GET['denunciar'],
			'tipo' => $_GET['tipo'],
		));
		echo json_encode(['estado'=>$result]);
		exit();
	}else if( isset( $_GET['eliminar'] ) ){
		if( $user->logingCheck() ) $result = $mt->deleteEntrada($_GET['eliminar']);
		echo json_encode(['estado'=>$result]);
		exit();
	}else if( isset( $_GET['donar'] ) ){
		$result = -1;
	
		if( $user->logingCheck() ){
			//Obtenemos datos de la entrada requerida
			$edadata = $mt->getDataEntrada([
				'id' => $_GET['donar'],
				'columns' => ['id','usuario','puntosv'],
			]);
			
			//Obtenemos datos del usuario actual
			$udata = $user->get([
				'id' => $_SESSION[S_USERID],
				'columns' => ['u.id','puntos'],
			]);
			
			//Verificamos que el usuario no haya punteado
			$punteo = $mt->getPunteo([
				'autor' => $udata['id'],
				'destino' => $edadata['id'],
				'tipo' => 5,
			]);
			
			if( $udata['puntos'] >= $edadata['puntosv'] && !$punteo && $edadata['usuario'] != $udata['id'] ){
				//Registramos punteo
				$mt->setPunteo([
					'autor' => $udata['id'],
					'destino' => $edadata['id'],
					'tipo' => 5,
				]);
				//Asignamos punteo
				$mt->uPuntosIncrement($edadata['usuario'], $edadata['puntosv']);	
				$mt->uExpIncrement($edadata['usuario'], $edadata['puntosv']);
				$mt->uPuntosIncrement($udata['id'], -$edadata['puntosv']);
				$result = 1;
			}else if( $punteo ) $result = 1;
			else if( !($udata['puntos'] >= $edadata['puntosv']) ) $result = 2;
		}
		echo json_encode(['estado'=>$result]);
		exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_entrada.php";
