<?php
	/*
	* Basicache - Sistema de cache básico basado en archivos
	* V 17.02.07
	*/
	
	namespace wecor;
	
	abstract class basicache{
		private static $cachedir = '';
		private static $lifetime = 300;
		/*
		* Metodo para eliminar archivos de cache
		*/
		public static function delete($id, $ruta = ''){
			if( empty($ruta) && !empty(self::$cachedir) ) $ruta = self::$cachedir;
			$archivo = glob("{$ruta}/{$id}_*");
			if( $archivo ) foreach($archivo as $v) unlink($v);
		}
		
		/*
		* Metodo para obtener el contenido guardado en cache
		*/
		public static function get($id, $ruta = ''){
			if( empty($ruta) && !empty(self::$cachedir) ) $ruta = self::$cachedir;
			$archivo = glob("{$ruta}/{$id}_*");
			if( $archivo ){
				$archivo = explode('/', $archivo[0]);
				$archivo = $archivo[count($archivo)-1];
				$caducidad = explode('_', $archivo)[1];
				if( $caducidad > date('U') )
					return file_get_contents("{$ruta}/{$id}_{$caducidad}");
				else self::delete($id, $ruta, $caducidad);
			}
			return 0;
		}
		/*
		* Metodo para generar archivos de cache
		*/
		public static function set($id, $contenido, $lifetime = 0, $ruta = ''){
			if( empty($lifetime) && !empty(self::$lifetime) )
				$lifetime = self::$lifetime;
			if( empty($ruta) && !empty(self::$cachedir) ) $ruta = self::$cachedir;
			$lifetime = date('U')+$lifetime;
			file_put_contents("{$ruta}/{$id}_{$lifetime}.cache", $contenido);
		}
		
		/*
		* Metodo para generar archivos de cache actualizando el existente
		*/
		public static function update($id, $contenido, $lifetime = 0, $ruta = ''){
			if( empty($lifetime) && !empty(self::$lifetime) )
				$lifetime = self::$lifetime;
			self::delete($id, $ruta);
			self::set($id, $contenido, $lifetime, $ruta);
		}
	
		/*
		* Metodo para establecer directorio por defecto par el cache
		*/
		public static function setDir($ruta){
			self::$cachedir = $ruta;
		}
		/*
		* Metodo para establecer timepo de vida por defecto par el cache
		*/
		public static function setLifetime($lifetime){
			self::$lifetime = $lifetime;
		}
	}