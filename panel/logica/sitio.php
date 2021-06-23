<?php

	namespace wecor;
	
	abstract class sitio{
		
		private static $info = array();
		
		/*
		* Metodo para obtener datos de configuracion del sitio
		*/
		public static function getInfo( $data = array() ){
			if( !count(self::$info) ) self::$info = DB::select(t_sitio)->first();
			if( !$data ) return self::$info;
			else if( is_array($data) ){
				$result = array();
				foreach($data as $v){
					if( isset( self::$info[$v] ) ) $result[$v] = self::$info[$v];
				}
				return $result;
			}else if( is_string($data) )
				return isset(self::$info[$data]) ? self::$info[$data] : null;
		}
		
		/*
		* Metodo para editar datos de configuracion del sitio
		*/
		public static function updateInfo( $data, $value = null ){
			if( is_array($data) && is_null($value) )
				$result = DB::update(t_sitio)->set($data)->send();
			else if( is_string($data) && (is_string($value) || is_numeric($value)) )
				$result = DB::update(t_sitio)->set(array(
					$data => $value
				))->send();
			return $result;
		}
	}