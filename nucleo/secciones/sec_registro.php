<?php
	/*
	* Facade para la seccion registro
	*/

	if( isset($_GET['guardar']) ){
		$datos = array();
		$datos['nickname'] = ( isset($_POST['nickname']) ) ? $_POST['nickname'] : '';
		$datos['clave'] = ( isset($_POST['clave']) ) ? $_POST['clave'] : '';
		$datos['email'] = ( isset($_POST['email']) ) ? $_POST['email'] : '';
		$datos['grupo'] = 6;

		/*
		* Por seguridad la clave tiene que venir ya cifrada desde el frontend (con javascript por ejemplo), en caso de que no lo este la ciframos ya que el sistema asi lo require
		*/
		if( !extras::sha512Validate($datos['clave']) )
			$datos['clave'] = hash('sha512', $datos['clave']);

		$resultado =  $user->create($datos);
		if( $resultado ){
			
			//Si el usuario es referido lo procesamos
			referidos::refProccess( $resultado['id'] );
			
			//En caso de exito iniciamos sesion
			$user->login($resultado);
			echo json_encode(array('estado' => 1, 'error' => 0));
		}else echo json_encode(array('estado' => 0, 'error' => $user->error));
		exit();
	}else if( isset($_GET['fbreg']) ){
		$datos = array();
		$estado = 0;
		$error = [];
		
		if( isset($_POST['fb_id'],$_POST['fb_name'],$_POST['fb_email']) ){
			$datos = [
				'nickname' => $_POST['fb_id'],
				'facebookid' => $_POST['fb_id'],
				'fb_name' => $_POST['fb_name'],
				'email' => $_POST['fb_email'],
			];
			$resultado = $user->createFB($datos);
			if( $resultado ){
				$user->login($resultado);
				$estado = 1;
			}else $error = $user->error;
		}
		
		echo json_encode(array(
			'estado' => $estado,
			'error' => $error
		));
		
		exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_registro.php";
