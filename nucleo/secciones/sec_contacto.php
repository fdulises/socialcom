<?php
	
	if( isset($_GET['contactar']) ){
		$result = [
			'estado' => 0,
			'error' => [],
		];
		
		if( isset($_POST['nombre'], $_POST['email'], $_POST['asunto'], $_POST['mensaje']) ){
			if( empty($_POST['nombre']) ) $result['error'][] = 'nombre_vacio';
			if( empty($_POST['email']) ) $result['error'][] = 'email_vacio';
			if( empty($_POST['asunto']) ) $result['error'][] = 'asunto_vacio';
			if( empty($_POST['mensaje']) ) $result['error'][] = 'mensaje_vacio';
		}
		
		if( !$result['error'] ){
			$_POST = extras::htmlentities($_POST);
			
			$mensaje = "<p><b>Datos del mensaje:</b></p>";
			$mensaje .= "<p> <b>Nombre:</b> {$_POST['nombre']}</p>";
			$mensaje .= "<p> <b>E-mail:</b> {$_POST['email']}</p>";
			$mensaje .= "<p> <b>Asunto:</b> {$_POST['asunto']}</p>";
			$mensaje .= "<p> <b>Mensaje:</b> {$_POST['mensaje']}</p>";
			$envio = @mail::phpMail(array(
				'destino' => $mt->getInfo('email'),
				'asunto' => 'Contacto desde sitio web '.$mt->getInfo('titulo'),
				'mensaje' => $mensaje,
				'from' => $mt->getInfo('email'),
			));
			if( $envio ) $result['estado'] = 1;
		}
		
		echo json_encode($result);
		exit();
	}

	$mt->plantilla->display('tpl/contacto');