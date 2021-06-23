<?php

	namespace wecor;
	
	abstract class mail{
		/*
		* Metodo para envio de emails php
		*
			mail::phpMail(array(
				'destino' => alguien@sitio.algo, //Para quien va
				'asunto' => 'Aqui va el asunto del mensaje',
				'mensaje' => '<p>Lorem ipsum dolor sit amet</p>',
				'from' => contacto@sitio.algo, //Quien envia
			));
		*/
		public static function phpMail($datos){
			$cabeceras  = 'MIME-Version: 1.0'."\r\n";
			$cabeceras .= 'Content-type: text/html; charset=utf-8'."\r\n";
			if( isset( $datos['from'] ) )
				$cabeceras .= 'From: '.$datos['from']."\r\n";
			if( isset( $datos['cc'] ) )
				$cabeceras .= 'CC: '.$datos['cc']."\r\n";
			if( isset( $datos['bcc'] ) )
				$cabeceras .= 'Bcc: '.$datos['bcc']."\r\n";

			$resultado = mail(
				$datos['destino'],
				$datos['asunto'],
				$datos['mensaje'],
				$cabeceras
			);
			return $resultado;
		}
	}