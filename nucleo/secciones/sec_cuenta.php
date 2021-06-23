<?php
	/*
	* Facade para la seccion cuenta
	*/
	
	if( isset($_GET['cuenta_clave']) ){
		$datos = array();
		$clave = ( isset($_POST['clave']) ) ? $_POST['clave'] : '';

		/*
		* Por seguridad la clave tiene que venir ya cifrada desde el frontend (con javascript por ejemplo), en caso de que no lo este la ciframos ya que el sistema asi lo require
		*/
		if( !extras::sha512Validate($clave) ) $clave = hash('sha512', $clave);

		if( $user->updateClave($clave) )
			echo json_encode(array('estado' => 1, 'error' => 0));
        else echo json_encode(array('estado' => 0, 'error' => $user->error));
        exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_cuenta.php";
