<?php
	/*
	* Facade para la seccion perfil
	*/

	$tplsec = 'tpl/error404';
	//Mostramos pagina de error si no se recibe el nickname
	if( !isset($_GET['usuario']) ) die($mt->plantilla->display($tplsec));

	//Procesamos eliminacion de publicacion
	if( isset($_GET['public_del'], $_GET['id']) && $user->logingCheck() ){
		$_GET['id'] = (INT) $_GET['id'];
		$result = 0;

		$publifordelete = $user->getPublication([
			'id' => $_GET['id'],
			'columns' => ['A.id as id', 'A.destino as destino', 'A.autor as autor', 'A.superior']
		]);

		if( $_SESSION[S_USERID] == $publifordelete['destino'] || $_SESSION[S_USERID] == $publifordelete['autor'] ){
			$result = $user->deletePublication(['id' => $_GET['id']]);
		}
		
		if($result && $publifordelete['superior']){
			$user->updatePubliComenCont(
				$publifordelete['superior'], -1
			);
		}

		$estado = $result ? 1 : 0;
		echo json_encode(['estado'=>$estado]);
		exit();
	}

	//Procesamos envio de publicaciones
	if( isset($_GET['public_set']) && $user->logingCheck() ){
		$dataresult = array(
			'estado' => 0,
			'error' => array(),
		);
		if( isset($_POST['superior'], $_POST['contenido']) ){
			$useractual = $user->get([
				'nickname' => $_GET['usuario'], 'columns' => ['u.id'],
			]);
			if($useractual){
				if( $_POST['superior'] ) $result = $user->setPublication([
					'superior' => (INT) $_POST['superior'],
					'contenido' => $_POST['contenido'],
					'autor' => (INT) $_SESSION[S_USERID],
					'destino' => (INT) $useractual['id'],
				]);
				else if( $useractual['id'] == $_SESSION[S_USERID] ) $result = $user->setPublication([
					'superior' => (INT) $_POST['superior'],
					'contenido' => $_POST['contenido'],
					'autor' => (INT) $_SESSION[S_USERID],
					'destino' => (INT) $useractual['id'],
				]);
				else $result = false;
				if($result){
					$dataresult['estado'] = 1;
					$recentpublidata = $user->getPublication([
						'id' => $result,
						'columns' => [
							'A.id as public_id',
							'A.destino as public_destino',
							'A.autor as public_autor_id',
							'A.contenido as public_contenido',
							'A.fecha as public_fecha',
							'B.nickname as public_autor',
							'B.email as public_email',
						]
					]);
					if( $recentpublidata ){
						$recentpublidata['public_avatar'] = $user->generateAvatar([
							'id' => $recentpublidata['public_autor_id'],
							'email' => $recentpublidata['public_email'],
							'size' => 200,
						]);
						$recentpublidata['public_fecha'] = extras::formatoDate($recentpublidata['public_fecha'], 'd/m/Y').
						' a las '.
						extras::formatoDate($recentpublidata['public_fecha'], 'H:i').
						' horas';
						$dataresult['data'] = $recentpublidata;
					}
				}
			}
		}
		echo json_encode($dataresult);
		exit();
	}

	//Procesamos follows
	if( isset($_GET['follow']) && $user->logingCheck() ){
		$_GET['follow'] = (INT) $_GET['follow'];
		$estado = 0;

		$resultado = $user->setFollow([
			'autor' => $_SESSION[S_USERID],
			'destino' => $_GET['follow'],
		]);

		if( $resultado ) $estado = 1;
		echo json_encode(['estado'=>$estado]);
		
		exit();
	}else if( isset($_GET['unfollow']) && $user->logingCheck() ){
		$_GET['unfollow'] = (INT) $_GET['unfollow'];
		$estado = 0;

		$resultado = $user->setUnfollow([
			'autor' => $_SESSION[S_USERID],
			'destino' => $_GET['unfollow'],
		]);

		if( $resultado ) $estado = 1;
		echo json_encode(['estado'=>$estado]);

		exit();
	}
	
	//Procesamos subida de fotos
	if( isset($_GET['subida'], $_FILES['foto']) && $user->logingCheck() ){
		$estado = 0;
		$error = array();
		$data = '';
		$resultado = 0;
		
		//Consultamos el grupo del usuario
		$grupo = $user->getGrupo();
		//Obtenemos el numero total de fotos
		$total = $user::getTotalFotos($_SESSION[S_USERID]);
		
		if( ($total < $permitted_pics[$grupo]) ){
			//Procesamos el archivo
			$upload = extras::imgUpload([
				'file' => $_FILES['foto'],
				'dir' => 'media/fotos',
			]);
			
			//En caso de exito guardamos los datos
			if( $upload['estado'] ){
				$data['url'] = $mt->getInfo('url').'/'.$upload['data']['filepath'];
				$resultado = $user::createFoto([
					'url' => $upload['data']['name'],
					'autor' => $_SESSION[S_USERID],
					'estado' => 1,
				]);
			}else $error = $upload['error'];
			
			//En caso de exito mostramos la salida
			if( $resultado ){
				$estado = 1;
				$data['id'] = DBConnector::insertId();
			}		
		}else $error[] = 'permitted_pics';
		
		echo json_encode([
			'estado'=>$estado,
			'data' => $data,
			'error' => $error,
		]);
		exit();
	}
	
	//Procesamos eliminacion de fotos
	if( isset($_GET['fotodel']) && $user->logingCheck() ){
		$busqueda = $user::readFoto([
			'id' => $_GET['fotodel'],
			'columns' => ['id', 'url', 'autor'],
		]);
		
		if( $busqueda ){
			if( $busqueda['autor'] == $_SESSION[S_USERID] ){
				$resultado = $user::deleteFoto($busqueda['id']);
				if( $resultado ) @unlink("media/fotos/{$busqueda['url']}");
			}
		}
		
		echo json_encode(['estado'=>1]);
		exit();
	}
	
	//Procesamos denuncia de usuario
	if( isset( $_GET['denunciar'], $_GET['tipo'] ) ){
		$result = -1;
		if( $user->logingCheck() ) $result = $mt->setDenuncia(array(
			'destino' => $_GET['denunciar'],
			'tipo' => $_GET['tipo'],
		));
		echo json_encode(['estado'=>$result]);
		exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_perfil.php";
