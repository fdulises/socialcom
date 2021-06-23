<?php
	/*
	* Facade para la seccion acceso
	*/

	if( isset($_GET['iniciar']) ){
		$datos = array();
		$datos['usuario'] = ( isset($_POST['usuario']) ) ? $_POST['usuario'] : '';
		$datos['clave'] = ( isset($_POST['clave']) ) ? $_POST['clave'] : '';

		/*
		* Por seguridad la clave tiene que venir ya cifrada desde el frontend (con javascript por ejemplo), en caso de que no lo este la ciframos ya que el sistema asi lo require
		*/
		if( !extras::sha512Validate($datos['clave']) )
			$datos['clave'] = hash('sha512', $datos['clave']);

		if( $user->acceso($datos) ) echo json_encode(array('estado' => 1, 'error' => 0));
        else echo json_encode(array('estado' => 0, 'error' => $user->error));
        exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_acceso.php";
