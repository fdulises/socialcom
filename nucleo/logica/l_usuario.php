<?php

	/*
	* Clase de la capa de logica para usuarios
	*
	* Permite realizar transacciones con usuarios
	*/
	
	require APP_PATH.'/nucleo/logica/l_fotos.php';
	
	class l_usuario extends db_tablas{
		
		use l_fotos;

		private static $instance;
		public $error = array();

		public static function getInstance(){
			if (null === self::$instance)
			self::$instance = new self();
			return self::$instance;
		}

		public function __construct(){
			$this->sessionStart();
		}

		/*
		* Metodo para establecer el inicio de sesion seguro
		*/
		public function sessionStart(){
			//Personalizamos los parametros del inicio de sesion
			$session_name = S_ID;
			$secure = false;
			$httponly = true;
			if(ini_set('session.use_only_cookies', 1) === FALSE)
				die('Error de sesion');
			//Establecemos los parametros de inicio de sesion
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params(
				$cookieParams["lifetime"],
				$cookieParams["path"],
				$cookieParams["domain"],
				$secure, $httponly
			);
			session_name($session_name);
			@session_start();
			@session_regenerate_id();
		}

		/*
		* Metodo para verificar que se haya iniciado sesion
		*/
		public function logingCheck(){
			if( isset($_SESSION[S_USERID], $_SESSION[S_USERNAME], $_SESSION[S_STRING]) ){
				$userId = dbConnector::escape($_SESSION[S_USERID]);
				$resultado = DB::select($this->t_usuarios)
					->columns(['clave'])
					->where('id', '=', $userId)
					->where('estado', '=', 1)
					->first();
				if($resultado){
					$clave = $resultado['clave'];
					$logingcheck = hash( 'sha512', $clave.$_SERVER['HTTP_USER_AGENT'] );
					if( $logingcheck === $_SESSION[S_STRING] ) return 1;
				}
			}//else if( isset($_COOKIE[S_LOGING]) ) return $this->rememberLogingCheck();
			return 0;
		}

		/*
		* Metodo para establecer sesion como iniciada
		*/
		public function login($datos){
			$_SESSION[S_USERID] = $datos['id'];
			$_SESSION[S_USERNAME] = $datos['nickname'];
			$_SESSION[S_USERMAIL] = $datos['email'];
			$_SESSION[S_STRING] = hash(
				'sha512', $datos['clave'].$_SERVER['HTTP_USER_AGENT']
			);
			return 1;
		}
		/*
		* Metodo para cerrar la sesion de forma segura
		*/
		public function logout(){
			//$this->salirSesionRecordar();
			$_SESSION = array();
			$sesionParams = session_get_cookie_params();
			setcookie(
				session_name(),
				'', time()-42000,
				$sesionParams['path'],
				$sesionParams['domain'],
				$sesionParams['secure'],
				$sesionParams['httponly']
			);
			session_destroy();
		}

		/*
		* Metodo para crear nuevo usuario
		*/
		public function create($datos){
			//Verificamos que los campos no esten vacios y los sanitizamos
			$this->verifCampoVacio(array(
				'nickname' => $datos['nickname'],
				'email' => $datos['email'],
				'clave' => $datos['clave'],
				'grupo' => $datos['grupo'],
			));
			$this->limpiarCadenas($datos);
			//Validamos que el usuario y la contraseña sean Validos
			$this->validaUsuario($datos['nickname']);
			$this->validaClave($datos['clave']);
			$this->validaEmail($datos['email']);
			$this->validaGrupo($datos['grupo']);

			//Validamos si ya existen correo o nickname
			if( !count($this->error) ){
				$this->existeUsuarioDatos($datos['email'], $datos['nickname']);
				if( $this->existeIP() ) $this->error[] = 'ip_registrada';
			}

			//Si no hay ningun error con la clave y la contraseña procedemos
			if( !count($this->error) ){
				$sal = $this->randomSalt();
				$usuario_datos = array(
					'nickname' => $datos['nickname'],
					'email' => $datos['email'],
					'clave' => hash( 'sha512', $datos['clave'].$sal),
					'salt' => $sal,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'fregistro' => date('Y-m-d'),
					'estado' => 1,
					'grupo' => $datos['grupo'],
				);
				$usuario_datos = dbConnector::escape($usuario_datos);
				$resultado = DB::insert($this->t_usuarios)
					->columns(['nickname', 'email', 'clave', 'salt', 'ip', 'fregistro', 'estado', 'grupo'])
					->values($usuario_datos)
					->send();
				if( $resultado ){
					$lastInsertedId = dbConnector::insertId();
					DB::insert($this->t_perfiles)
						->columns(['id'])
						->values([$lastInsertedId])
						->send();
					$puntosa = $GLOBALS['mt']->getInfo('conf_pp_registro');
					self::puntosIncrement( $lastInsertedId, $puntosa );
					DB::update(t_sitio)->set('total_u=total_u+1')->send();
					return [
						'id' => $lastInsertedId,
						'nickname' => $usuario_datos['nickname'],
						'email' => $usuario_datos['email'],
						'clave' => $usuario_datos['clave'],
					];
				}
			}
			return 0;
		}
		
		/*
		* Metodo para crear nuevo usuario via facebook
		*/
		public function createFB($datos){
			$datos['grupo'] = 6;
			//Verificamos que los campos no esten vacios y los sanitizamos
			$this->verifCampoVacio(array(
				'nickname' => $datos['nickname'],
				'email' => $datos['email'],
			));
			$this->limpiarCadenas($datos);
			//Validamos que los datos sean validos
			$this->validaUsuario($datos['nickname']);
			$this->validaEmail($datos['email']);

			//Validamos si ya existen correo o nickname
			if( !count($this->error) ){
				$this->existeUsuarioDatos($datos['email'], $datos['nickname']);
				if( $this->existeIP() ) $this->error[] = 'ip_registrada';
			}

			if( !count($this->error) ){
				
				$sal = $this->randomSalt();
				$datos['clave'] = $this->randomSalt();
				
				$usuario_datos = array(
					'nickname' => $datos['nickname'],
					'email' => $datos['email'],
					'clave' => hash( 'sha512', $datos['clave'].$sal),
					'salt' => $sal,
					'ip' => $_SERVER['REMOTE_ADDR'],
					'fregistro' => date('Y-m-d'),
					'estado' => 1,
					'grupo' => $datos['grupo'],
				);
				$usuario_datos = dbConnector::escape($usuario_datos);
				$resultado = DB::insert($this->t_usuarios)
					->columns(['nickname', 'email', 'clave', 'salt', 'ip', 'fregistro', 'estado', 'grupo'])
					->values($usuario_datos)
					->send();
				if( $resultado ){
					$lastInsertedId = dbConnector::insertId();
					DB::insert($this->t_perfiles)
						->columns(['id'])
						->values([$lastInsertedId])
						->send();
					$puntosa = $GLOBALS['mt']->getInfo('conf_pp_registro');
					self::puntosIncrement( $lastInsertedId, $puntosa );
					DB::update(t_sitio)->set('total_u=total_u+1')->send();
					return [
						'id' => $lastInsertedId,
						'nickname' => $usuario_datos['nickname'],
						'email' => $usuario_datos['email'],
						'clave' => $usuario_datos['clave'],
					];
				}
			}else if( in_array("usuario_repetido", $this->error) && in_array("email_repetido", $this->error) && isset($datos['facebookid']) ){
				$this->error[] = "Lorem";
				$getusemail = $resultado = DB::select($this->t_usuarios)
					->columns(['id', 'nickname', 'email', 'clave'])
					->where('email', '=', $datos['email'])
					->where('nickname', '=', $datos['nickname'])
					->first();
				if( $getusemail ){
					$this->error = [];
					return [
						'id' => $getusemail['id'],
						'nickname' => $getusemail['nickname'],
						'email' => $getusemail['email'],
						'clave' => $getusemail['clave'],
					];
				}
			}
			return 0;
		}

		/*
		* Metodo para generar una sal aleatoria para usarla en contraseñas
		*/
		private function randomSalt(){
			//return uniqid(mt_rand(1, mt_getrandmax()), true);
			return hash( 'sha512', uniqid(openssl_random_pseudo_bytes(16), true) );
		}

		/*
		* Metodo para verificar campos vacios
		*/
		private function verifCampoVacio($datos){
			$err = 0;
			foreach ($datos as $k => $v) {
				if($v === "") $this->error[]=$k.'_vacio';
				$err += 1;
			}
			if($err) return 0;
			return 1;
		}

		/*
		* Metodos para sanitizar campos de acceso
		*/
		private function limpiarCadenas($datos){
			if( is_array($datos) ) foreach ($datos as $k => $v)
					$datos[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			else $datos = filter_var($datos, FILTER_SANITIZE_STRING);
			return $datos;
		}

		/*
		* Metodo para verificar si es email o usuario y validarlo
		*/
		private function validaUsuario($dato){
			if( filter_var($dato, FILTER_VALIDATE_EMAIL) ) return 1;
			else if( preg_match('/^[a-zA-Z0-9\-_]{4,16}$/', $dato) ) return 2;
			else $this->error[] = "usuario_incorrecto";
			return 0;
		}

		/*
		* Metodo para validar la configuracion de la clave (sha512)
		*/
		private function validaClave($dato){
			if( (strlen($dato) !== 128) || !ctype_xdigit($dato) )
				$this->error[] = 'clave_configuracion';
		}

		/*
		* Metodo para validar emails
		*/
		private function validaEmail($dato){
			if( filter_var($dato, FILTER_VALIDATE_EMAIL) ) return 1;
			else $this->error[] = "email_incorrecto";
			return 0;
		}

		/*
		* Metodo para grupo de usuarios
		*/
		private function validaGrupo($dato){
			require 'config/conf_grupos.php';
			if( isset($grupos[$dato]) ) return 1;
			else $this->error[] = "grupo_incorrecto";
			return 0;
		}

		/*
		* Metodo para saber si existe correo o nickname
		*/
		public function existeUsuarioDatos($correo, $nickname){
			$correo = dbConnector::escape($correo);
			$nickname = dbConnector::escape($nickname);
			$resultado = DB::select($this->t_usuarios)->columns(['id', 'nickname', 'email'])
				->where('email', '=', $correo)
				->orWhere('nickname', '=', $nickname)
				->first();
			if( $resultado ){
				if( $resultado['nickname'] == $nickname ) $this->error[] = 'usuario_repetido';
				if( $resultado['email'] == $correo ) $this->error[] = 'email_repetido';
			}
		}
		
		/*
		* Metodo para saber si existe ip
		*/
		public function existeIP(){
			if( !MODO_DEBUG ){
				$resultado = DB::select($this->t_usuarios)
					->columns(['id', 'ip'])
					->where('ip', '=', $_SERVER['REMOTE_ADDR'])
					->get();
				if( count($resultado) > 2 ) return 1;
			}
			return 0;
		}

		/*
		* Metodo para procesar inicio de sesion de usuario
		*/
		public function acceso($datos){
			//Verificamos que los campos no esten vacios y los sanitizamos
			$this->verifCampoVacio($datos);
			$this->limpiarCadenas($datos);
			//Validamos el usuario y la contraseña
			$this->validaUsuario($datos['usuario']);
			$this->validaClave($datos['clave']);
			//Escapamos datos de usuario
			$datos['usuario'] = dbConnector::escape($datos['usuario']);

			//Si no hay ningun error con la clave y la contraseña procedemos
			if( !count($this->error) ){

				//Obtenemos los datos del usuario solicitado
				$usuarioDatos = DB::select($this->t_usuarios);
				$usuarioDatos->columns(['id', 'nickname', 'email', 'clave', 'salt'])->where('estado', '=', 1);
				if( filter_var($datos['usuario'], FILTER_VALIDATE_EMAIL) )
					$usuarioDatos->where('email', '=', $datos['usuario']);
				else $usuarioDatos->where('nickname', '=', $datos['usuario']);
				$usuarioDatos = $usuarioDatos->first();

				//Si encontro el usuario procedemos
				if($usuarioDatos){
					//Verificamos intentos de sesion fallidos para evitar ataques de fuerza bruta
					if( $this->checkbrute($usuarioDatos['id']) ) return 0;
					//Comparamos la contraseña ingresada con la guardada en la base de datos
					if( (hash('sha512', $datos['clave'].$usuarioDatos['salt']) === $usuarioDatos['clave']) && !count($this->error) ){
						//Iniciamos sesion si todo es correcto
						return $resultado = $this->login([
							'id' => $usuarioDatos['id'],
							'nickname' => $usuarioDatos['nickname'],
							'clave' => $usuarioDatos['clave'],
							'email' => $usuarioDatos['email'],
						]);
						/*if( isset($datos['recordars']) ) if( $datos['recordars'] )
							$this->sesionRecordar(array(
								'usuario' => $usuarioDatos['id'],
								'salt' => $usuarioDatos['salt'],
							));
						return $resultado;*/
					}else{
						//En caso de error Registramos intento de inicio de sesion fallido
						$this->error[] = 'clave_incorrecta';
						DB::insert($this->t_sesioneserr)
							->columns(['tiempo', 'usuario'])
							->values([
								'tiempo' => time(),
								'id' => $usuarioDatos['id'],
							])
							->send();
					}
				}else{
					$this->error[] = 'usuario_inexistente';
				}
			}
			return 0;
		}

		/*
		* Metodo para impedir ataques de fuerza bruta
		*/
		private function checkbrute($id){
			$tiempoLimite = time() - (2*60*60);
			$intentos = DB::select($this->t_sesioneserr)
				->columns(['id', 'tiempo'])
				->where('usuario', '=', $id)
				->where('tiempo', '>', $tiempoLimite)
				->get();
			if( count($intentos) >= $GLOBALS['mt']->getInfo('conf_intentos') ){
				$this->error[] = 'limite_intentos';
				return true;
			}
			return 0;
		}

		/*
		* Metodo para obtener datos de usario
		*/
		public function get( $datos ){
			$resultado = DB::select(t_usuarios.' u')
				->where(function($query){
					$query->orWhere('estado', '=', 1)
						->orWhere('estado', '=', 2)
						->orWhere('estado', '=', 3);
				})
				->leftJoin(t_perfiles.' p', 'u.id', '=', 'p.id')
				->columns($datos['columns']);
			if( isset($datos['id']) )
				$resultado->where('u.id', '=', (INT) $datos['id']);
			else if( isset($datos['nickname']) )
				$resultado->where('u.nickname', '=', $datos['nickname']);
			$resultado = $resultado->first();
			if( $resultado ) return $resultado;
			return array();
		}


		/*
		* Metodo para actualizar datos de usario
		*/
		public function update( $id, $datos ){
			$resultado = DB::update(t_usuarios.' u')
				->leftJoin(t_perfiles.' p', 'u.id', '=', 'p.id')
				->set($datos)
				->where('p.id', '=', (INT) $id)
				->send();
			return $resultado;
		}
		
		/*
		* Metodo para actualizar contraseña
		*/
		public function updateClave($clave){
			$usid = (INT) $_SESSION[S_USERID];
			$sal = $this->randomSalt();
			$data = array(
				'clave' => hash( 'sha512', $clave.$sal),
				'salt' => $sal,
			);
			return DB::update(t_usuarios)
				->set($data)
				->where('id', '=', $usid)
				->send();
		}

		/*
		* Metodo para generar avatar de usuario
		*/
		public function generateAvatar( $datos ){
			$avtrimg = array();
			$size = isset( $datos['size'] ) ? $datos['size'] : '';
			if( isset($datos['id']) ) $avtrimg = glob("media/avatar/{$datos['id']}.*");
			if( count($avtrimg) ){
				$avtrimg = "{$GLOBALS['mt']->getInfo('url')}/{$avtrimg[0]}";
			}else $avtrimg = extras::urlGravatar($datos['email'], $size);
			return $avtrimg;
		}

		/*
		* Metodo para generar portada de usuario
		*/
		public function generateBackground( $id, $default = null ){
			$avtrimg = array();
			if( isset($id) ) $avtrimg = glob("media/background/{$id}.*");
			if( count($avtrimg) ){
				$avtrimg = "{$GLOBALS['mt']->getInfo('url')}/{$avtrimg[0]}";
			}else{
				if( is_null($default) )
				$avtrimg = "{$GLOBALS['mt']->getInfo('url')}/media/background/default.jpg";
				else $avtrimg = $default;
			}
			return $avtrimg;
		}

		/*
		* Metodo para procesar avatar de usuario
		*/
		public function avatarProcess($id){
			//Eliminamos el archivo anterior
			$avtrimg = glob("media/avatar/{$id}.*");
			foreach ($avtrimg as $v) unlink($v);

			$imgresult = img::resize([
				'filename' => 'avatar',
				'picname' => $id,
				'uri' => 'media/avatar',
				'w' => 500,
				'h' => 500,
			]);
			if( $imgresult ) return $imgresult;
			return '';
		}
		/*
		* Metodo para procesar portada de usuario
		*/
		public function coverProcess($id){
			//Eliminamos el archivo anterior
			$avtrimg = glob("media/background/{$id}.*");
			foreach ($avtrimg as $v) unlink($v);

			$imgresult = img::resize([
				'filename' => 'cover',
				'picname' => $id,
				'uri' => 'media/background',
				'w' => 1100,
				'h' => 400,
			]);
			if( $imgresult ) return $imgresult;
			return '';
		}

		/*
			Tabla publicaciones

			id, destino, autor, contenido, fecha, tipo, superior, estado
		*/
		
		public function updatePubliComenCont($id, $cantidad){
			return DB::update(t_publicaciones)
				->set("contador=contador+{$cantidad}")
				->where('id', '=', $id)
				->send();
		}

		/*
		* Metodo para procesar creacion de publicaciones
		*/
		public function setPublication($data){

			if( !isset($data['destino']) ) $data['destino'] = $_SESSION[S_USERID];
			if( !isset($data['autor']) ) $data['autor'] = $_SESSION[S_USERID];
			if( !isset($data['contenido']) ) $data['contenido'] = '';
			if( !isset($data['fecha']) ) $data['fecha'] = date('Y-m-d H:i:s');
			if( !isset($data['tipo']) ) $data['tipo'] = 1;
			if( !isset($data['superior']) ) $data['superior'] = 0;
			if( !isset($data['estado']) ) $data['estado'] = 1;

			$data = dbConnector::escape($data);

			$resultado = DB::insert(t_publicaciones)->columns([
				'destino', 'autor', 'contenido', 'fecha', 'tipo', 'superior', 'estado'
			])->values([
				$data['destino'],
				$data['autor'],
				$data['contenido'],
				$data['fecha'],
				$data['tipo'],
				$data['superior'],
				$data['estado'],
			])->send();

			if( $resultado ){
				$insertid = dbConnector::insertId();
				if( $data['superior'] ) $this->updatePubliComenCont(
					$data['superior'], 1
				);
				return $insertid;
			}
			return $resultado;
		}

		/*
		* Metodo para procesar recuperacion de publicaciones
		*/
		public function getPublication($data){
			$resultado = DB::select(t_publicaciones.' A')->leftJoin(
				t_usuarios.' B', 'A.autor', '=', 'B.id'
			);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['superior']) ) $resultado->where(
				"A.superior = '{$data['superior']}'"
			);
			if( !isset($data['estado']) )
				$resultado->where('A.estado', '=', 1);
			else $resultado->where('A.estado', '=', $data['estado']);
			if( isset($data['autor']) ) $resultado->where('A.autor', '=', $data['autor']);
			if( isset($data['destino']) )
				$resultado->where('A.destino', '=', $data['destino']);

			if( isset($data['limit']) )
				$resultado->limit($data['limit']);
			$resultado->order('A.fecha desc');
			if( isset($data['id']) ){
				$resultado->where('A.id', '=', (INT) $data['id']);
				$resultado = $resultado->first();
			}else $resultado = $resultado->get();
			return $resultado;
		}

		/*
		* Metodo para procesar eliminacion de publicaciones
		*/
		public function deletePublication($data){
			//id, destino, autor, superior
			$resultado = DB::delete(t_publicaciones);
			if( isset($data['id']) ) $resultado->where('id', '=', $data['id']);
			if( isset($data['destino']) ) $resultado->where('destino', '=', $data['destino']);
			if( isset($data['autor']) ) $resultado->where('autor', '=', $data['autor']);
			if( isset($data['superior']) )
				$resultado->where('superior', '=', $data['superior']);
			$resultado->send();

			return $resultado;
		}

		/*
		* Metodo para procesar edicion de publicaciones
		*/
		public function updatePublication(){

		}

		/*
		* Metodo para procesar accion seguir
		*/
		public function setFollow( $data ){
			$resultado = $this->getFollow($data['autor'], $data['destino']);
			if( !$resultado ){
				$resultado = DB::insert(t_follow)
					->columns(['autor', 'destino'])
					->values([ $data['autor'], $data['destino'] ])
					->send();
				if( $resultado ){
					DB::update(t_perfiles)->set('seguidores=seguidores+1')
						->where('id', '=', $data['destino'])->send();
					DB::update(t_perfiles)->set('siguiendo=siguiendo+1')
						->where('id', '=', $data['autor'])->send();
				}
			}
			return $resultado;
		}

		/*
		* Metodo para saber si un usuario sigue a otro
		*/
		public function getFollow($autor, $destino){
			return DB::select(t_follow)->columns(['id'])
				->where('autor', '=', $autor)
				->where('destino', '=', $destino)
				->first();
		}

		/*
		* Metodo para procesar accion dejar de seguir
		*/
		public function setUnfollow( $data ){
			$resultado = DB::delete(t_follow)
				->where('autor', '=', $data['autor'])
				->where('destino', '=', $data['destino'])
				->send();
			if( $resultado ){
				DB::update(t_perfiles)->set('seguidores=seguidores-1')
					->where('id', '=', $data['destino'])->send();
				DB::update(t_perfiles)->set('siguiendo=siguiendo-1')
					->where('id', '=', $data['autor'])->send();
			}
			return $resultado;
		}

		/*
		* Metodo para obtener lista de seguidos
		*/
		public function getFollowing( $data ){
			$resultado = DB::select(t_follow.' f')
				->leftJoin(t_usuarios.' u', 'u.id', '=', 'f.destino')
				->leftJoin(t_perfiles.' p', 'p.id', '=', 'f.destino')
				->columns($data['columns'])
				->where('p.autor', '=', $data['id'])
				->get();
			return $resultado;
		}

		/*
		* Metodo para obtener lista de seguidores
		*/
		public function getFollowers( $data ){
			$resultado = DB::select(t_follow.' f')
				->leftJoin(t_usuarios.' u', 'u.id', '=', 'f.autor')
				->leftJoin(t_perfiles.' p', 'p.id', '=', 'f.autor')
				->columns($data['columns'])
				->where('p.destino', '=', $data['id'])
				->get();
			return $resultado;
		}
		
		/*
		* Metodo para consultar grupo de usuario
		*/
		public static function getGrupo( $id = null ){
			if( is_null($id) ) $id = $_SESSION[S_USERID];
			$result = DB::select(t_usuarios)
				->columns(['grupo'])
				->where('id', '=', $id)->where('estado', '=', 1)
				->first();
			if( $result ) return $result['grupo'];
			return 0;
		}
		
		/*
		* Metodo para incrementar los puntos de un usuario
		*/
		public static function puntosIncrement( $id, $cantidad ){
			return DB::update(t_perfiles)
				->set("puntos = puntos+{$cantidad}, experiencia = experiencia+{$cantidad}")
				->where('id', '=', (INT) $id)
				->send();
		}
		
		/*
		* Metodo para saber si el sistema ya otorgo los puntos
		*/
		public static function getPuntosRegistro( $data ){
			$result = DB::select(t_puntos)
				->columns(['id'])
				->where( 'autor = 0' )
				->where( 'tipo', '=', $data['tipo'] )
				->where( 'destino', '=', $data['usuario'] )
				->where( 'razon', '=', $data['razon'] )
				->first();
			if( $result ) return $result['id'];
			return 0;
		}
		
		/*
		* Metodo para registrar los puntos que el sistema otorga
		*/
		public static function setPuntosRegistro( $data ){
			return DB::insert(t_puntos)
				->columns(['autor', 'destino', 'tipo', 'razon'])
				->values([
					'0',
					$data['usuario'],
					$data['tipo'],
					$data['razon']
				])
				->send();
		}
		
		/*
		* Metodo del sistema para dar puntos a un usuario si no se han dado
		* Tipos:
			1-
			2-Recibidos en entrada
			3-Por entrada
			4-Por comentario
			5-Donacion
		*/
		//usuario::givePuntos( $usuario, $tipo, $razon );
		public static function givePuntos( $usuario, $tipo, $razon ){
			$data['usuario'] = (INT) $usuario;
			$data['tipo'] = (INT) $tipo;
			$data['razon'] = (INT) $razon;
			
			//Checamos si ya se dieron los puntos
			$hasRegistro = self::getPuntosRegistro($data);
			if( !$hasRegistro ){
				//Establecemos la cantidad de puntos a dar
				$cantidad = 0;
				if( $data['tipo'] == 3 )
					$cantidad = $GLOBALS['mt']->getInfo('conf_pp_entrada');
				else if( $data['tipo'] == 4 )
					$cantidad = $GLOBALS['mt']->getInfo('conf_pp_coment');
			
				//Damos puntos al usuario y hacemos el registro
				self::puntosIncrement( $data['usuario'], $cantidad );
				self::setPuntosRegistro($data);
				
				return 1;
			}
			
			return 0;
		}
		
		public function getList($data){
			$result = DB::select(t_usuarios.' u')
				->leftJoin(t_perfiles.' p', 'u.id', '=', 'p.id')
				->columns($data['columns']);
			if( isset($data['limit']) ) $result->limit($data['limit']);
			if( isset($data['order']) ) $result->order($data['order']);
			$result->where('u.estado', '=', '1');
			return $result->get();
		}

	}
