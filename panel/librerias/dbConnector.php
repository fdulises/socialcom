<?php
	/*
	* Clase Abstracta MySQLi
	* Provee de todos los metodos necesarios para hacer operaciones con la base de datos
	*/
	
	namespace wecor;
	
	abstract class DBConnector{

		public static $conexion;
		public static function connect($host, $user, $password, $database){
			self::$conexion = new \mysqli($host, $user, $password, $database);
			if(self::$conexion->connect_errno > 0){
				die('Imposible conectarse con la base de datos: [' . self::$conexion->connect_error . ']');
			}
			self::$conexion->set_charset("utf8");
		}
		public static function sendQuery($query){
			$resultado = self::$conexion->query($query) or die('Error '.self::errorNo().' - '.self::error());
			return $resultado;
		}
		public static function fetchAssoc($result){
			return $result->fetch_assoc();
		}
		public static function escape($datos){
			if( is_array($datos) ){
				$datos_f = array();
				foreach($datos as $k => $v) {
					$datos_f[$k] = self::$conexion->real_escape_string($v);
				}
				return $datos_f;
			}else return self::$conexion->real_escape_string($datos);
		}
		public static function unescape($datos){
			if( is_array($datos) ){
				foreach($datos as $k => $v) $datos[$k] = self::unescape($v);
			}else stripslashes($datos);
			return $datos;
		}
		public static function query($q){
			$result = self::sendQuery($q);
			$arr = array();
			if(is_object($result)){
				while( $row = self::fetchAssoc($result) )
					$arr[] = self::unescape($row);
			}
			return $arr;
		}
		public static function errorNo(){
			return self::$conexion->errno;
		}
		public static function error(){
			return self::$conexion->error;
		}
		public static function insertId(){
			return self::$conexion->insert_id;
		}
		public static function numRows($result){
			return self::$conexion->num_rows;
		}
		public static function affectedRows($result){
			return $result->affected_rows;
		}
		public static function free($result){
			return $result->free();
		}
		public static function close(){
			return self::$conexion->close();
		}
		public static function foundRows(){
			$datos = self::query("SELECT FOUND_ROWS() AS total");
			return $datos[0]['total'];
		}
	}
