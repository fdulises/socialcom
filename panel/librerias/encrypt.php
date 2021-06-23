<?php

	namespace wecor;
	
	abstract class encrypt{
		
		/*
		* Metodo para generar hash aleatorio
		*
		* Parametros: $hash - Metodo de cifrado para el hash
		* Retorna: cadena aleatoria
		*/
		public static function randomHash( $hash = 'md5' ){
			if(is_callable('openssl_random_pseudo_bytes'))
				$randomString = openssl_random_pseudo_bytes(16);
			else $randomString = mt_rand(1, mt_getrandmax());
			
			return hash( $hash, $randomString );
		}
		
		/*
		* Metodo para generar hash aleatorio
		*
		* Parametros: $hash - Metodo de cifrado para el hash
		* Retorna: cadena aleatoria
		*/
		public static function hash( $string, $hash = 'md5' ){
			return hash( $hash, $string );
		}
		
		/*
		* Metodo para validar cadena sha512
		*
		* Parametros: $cadena - Variable a validar
		* Retorna: boleano
		*/
		public static function sha512Validate($cadena){
			if( (strlen($cadena) == 128) && ctype_xdigit($cadena) ) return 1;
			return 0;
		}
	}