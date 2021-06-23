<?php

	/*
	* Clase de la capa de datos para sitio
	*
	* Permite realizar transacciones con los datos relacionados al sitio
	*/
	abstract class db_sitio extends db_tablas{

		protected function __construct(){}

		private $info = array();
		private $sitioCampos = array(
			'titulo', 'lema', 'descrip', 'url', 'email', 'conf_enlaces', 'conf_enlaces_anidar', 'conf_epp', 'conf_coment', 'tema_url', 'tema_ext'
		);

		/*
		* Metodo para obtener informacion del sitio
		*/
		public function getInfo( $datos = null ){
			if( is_array($datos) ){
				return $this->getInfoFaltante($datos);
			}else if( is_string($datos) ){
				if( !isset($this->info[$datos]) ){
					$datos = dbConnector::escape($datos);
					$resultado = dbConnector::query(
						"SELECT {$datos} FROM {$this->t_sitio}"
					);
					if( $resultado )
						$this->info[$datos] = $resultado[0][$datos];
				}
				return $this->info[$datos];
			}else{
				return $this->getInfoFaltante($this->sitioCampos);
			}
			return null;
		}

		/*
		* Metodo para extraer datos de la tabla sitio
		*
		* Busca si existen los campos solicitados en la propiedad info
		* Solo se extraen de la db los campos que aun no se han extraido
		*/
		private function getInfoFaltante($datos){
			$faltantes = array();
			foreach ($datos as $v) {
				if( !array_key_exists($v, $this->info) )
					$faltantes[] = $v;
			}
			if( count($faltantes) ){
				$faltantes = dbConnector::escape($faltantes);
				$campos = implode(', ', $faltantes);
				$resultado = dbConnector::query(
					"SELECT {$campos} FROM {$this->t_sitio}"
				);
				if( $resultado ) $this->info = array_merge(
					$this->info, $resultado[0]
				);
			}
			$resultado = array();
			foreach ($datos as $v) {
				if( isset($this->info[$v]) )
					$resultado[$v] = $this->info[$v];
			}
			return $resultado;
		}
	}
