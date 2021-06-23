<?php

	namespace wecor;
	
	abstract class routes{
		
		private static $routesDir = '';
		private static $rules = array();
		public static $params = array();
		public static $actual = '';
		
		public static function setDir( $path ){
			self::$routesDir = $path;
		}
		
		public static function add( $route, $action, $regex = false ){
			if( $route !== '/' ) $regex = trim($route, '/');
			else $route = '\/';
			self::$rules["/^{$route}$/i"] = $action;
		}
		
		public static function get( ){
			$url = isset( $_GET['url'] ) ? $_GET['url'] : '';
			$url = trim($url, '/');
			if( $url == '' ) $url = '/';
			
			$result = '';
			foreach( self::$rules as $k => $v ){
				preg_match( $k, $url, $result );
				if( $result ) break;
			}
			if( $result ){
				self::$actual = $v;
				self::$params = $result;
			}else{
				self::$actual = 'error404';
				self::$params = array();
			}
			return self::$actual;
		}
		
	}