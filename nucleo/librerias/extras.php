<?php
	abstract class extras{

		public static $error = array();

		public static function print_r($datos){
			print "<pre>";
			print_r($datos);
			print "</pre>";
		}

		/*
		* Metodo para implementar el modo mantenimiento
		*/
		public static function modoMantenimiento(){
			include 'mt_nucleo/presentacion/tpl/mantenimiento.tpl';
			exit();
		}

		/*
		* Metodos para escapar codigo html
		*
		* Si $multi se establece en true procesa $datos como arreglo multidimencional
		*/
		public static function htmlentities($datos, $algo = null){
			if( is_array($datos) ){
				foreach( $datos as $k => $v ) $datos[$k] = self::htmlentities($v);
			}else $datos = htmlentities($datos);
			return $datos;
		}

		/*
		* Metodo para dar el formato deseado a la fecha y el tiempo
		*/
		public static function formatoDate($cadena, $formato){
			return date( $formato, strtotime($cadena) );
		}

		/*
		* Metodo para generar gravarar
		*/
		public static function urlGravatar($email, $size){
			$hash = md5( strtolower( trim( $email ) ) );
			return "http://www.gravatar.com/avatar/{$hash}.jpg?s={$size}&d=mm";
		}

		/*
		* Metodo que itera sobre un arreglo bidimencional para modificar y agregar celdas
		*/
		public static function setCeldaArregloBi($arreglo, $contenido, $celda = ''){
			foreach( $arreglo as $k => $v )	$arreglo[$k][$celda] = $contenido;
			return $arreglo;
		}

		/*
		* Metodo para verificar campos vacios
		*/
		public static function checkCampoVacio($datos){
			foreach ($datos as $k => $v) if( empty($v) ) self::$error[]=$k.'_vacio';
			return self::$error;
		}

		/*
		* Metodos para sanitizar campos
		*/
		public static function limpiarCadenas($datos){
			foreach ($datos as $k => $v) $datos[$k] = filter_var($v, FILTER_SANITIZE_STRING);
			return $datos;
		}

		/*
		* Metodo para validar la configuracion de la clave (sha512)
		*/
		public static function sha512Validate($cadena){
			if( (strlen($cadena) == 128) && ctype_xdigit($cadena) ) return 1;
			return 0;
		}

		/*
		* Metodo para verificar campos vacios
		*/
		static public function verifCampoVacio($datos){
			$error = array();
			foreach ($datos as $k => $v)
				if( $v == '' ) $error[]=$k.'_vacio';
			return $error;
		}
		
		/*
		* Metodo para subir archivos
		*/
		public static function upload( $data ){
			$info = array(
				'estado' => 0,
				'data' => array(),
			);
			if( 0 == $data['file']['error'] ){
				//Definimos el directorio de subida
				$dir = '';
				if( isset($data['dir']) )
					$dir = "{$data['dir']}/";
				
				//Definimos el nombre del archivo
				if( isset( $data['name'] ) ) $name = $data['name'];
				else $name = basename($data['file']['name']);
				
				//Definimos la ruta del archivo
				$filepath = $dir.$name;
				
				//Movemos el archivo creado
				$result = move_uploaded_file($data['file']['tmp_name'], $filepath);
				
				if( $result ){
					$info['estado'] = 1;
					$info['data']['name'] = $name;
					$info['data']['filepath'] = $filepath;
				}
			}
			return $info;
		}
		
		public static function imgUpload( $data ){
			$info = array(
				'estado' => 0,
				'data' => '',
				'error' => array(),
			);
			if( 0 == $data['file']['error'] ){
				//Validamos que sea un archivo valido
				$type = strtolower(substr(strrchr($data['file']['name'],"."),1));
				if($type == 'jpeg') $type = 'jpg';
				switch($type){
					case 'bmp': $valid_type = 1; break;
					case 'gif': $valid_type = 1; break;
					case 'jpg': $valid_type = 1; break;
					case 'png': $valid_type = 1; break;
					default : 	$valid_type = 0;
				}
				if( $valid_type ){
					$result = self::upload( $data );
					if( $result['estado'] ){
						$info['estado'] = 1;
						$info['data'] = $result['data'];
					}else $info['error'][] = 'error_subida';
				}else $info['error'][] = 'error_type';
			}else{
				if( $data['file']['error'] == 1 || $data['file']['error'] == 2 )
					$info['error'][] = 'error_size';
				else $info['error'][] = 'error_subida';
			}
			
			return $info;
		}
		
		/*
		* Metodo para limpiar una cadena para su uso en urls
		*/
		public static function urlClear($url){
			$url = strtolower($url);
			$find = array(' ', '&', '\r\n', '\n', '+');
			$url = str_replace ($find, '-', $url);
			$find = array('/[^a-z0-9\-]/', '/[\-]+/', '/]*>/');
			$repl = array('', '-', '');
			$url = preg_replace ($find, $repl, $url);
			return $url;
		}

	}
