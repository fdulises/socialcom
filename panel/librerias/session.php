<?php

	namespace wecor;
	
	abstract class session{
		private static $config = array(
			'name' => '',
			'secure' => false,
			'httponly' => true,
		);
		
		/*
		* Metodo para establecer configuracion de sesion
		*/
		public static function setConfig($key, $value){
			self::$config[$key] = $value;
		}
		
		/*
		* Metodo para establecer sesion personalizada
		*/
		public static function start(){
			if(ini_set('session.use_only_cookies', 1) === FALSE)
				die('Error de sesion');
			$cookieParams = session_get_cookie_params();
			session_set_cookie_params(
				$cookieParams["lifetime"],
				$cookieParams["path"],
				$cookieParams["domain"],
				self::$config['secure'],
				self::$config['httponly']
			);
			session_name(self::$config['name']);
			session_start();
			session_regenerate_id();
		}
		
		/*
		* Metodo para limpiar la sesion actual
		*/
		public function destroy(){
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
		
		
	}