<?php
	namespace wecor;
	
	trait paginaraux{
		/*
		* Metodo auxiliar para obtener el numero de filas de una consulta
		* Parametros: $sql - (String) Peticion sql tipo select
		* Retorno: (array) arreglo bidimencional con el resultado de la consulta
		*/
		private static function getSelect($sql){
			$sql = preg_replace('/SELECT (.*?)/is', 'SELECT sql_calc_found_rows $1', $sql);
			$resultado = DBConnector::query($sql); 
			return $resultado;
		}
		
		public static function pagination( $data = array() ){
			$config = [
				'total' => 0,
				'per_page' => 10,
				'current_page' => 1,
				'url_base' => '',
				'friendly' => false,
				'page_string' => 'pagina',
				'num_links' => 2,
			];
			
			if( isset($data['total']) )
				$config['total'] = $data['total'];
			else $config['total'] = self::totalRows();
			
			if( isset($data['per_page']) )
				$config['per_page'] = $data['per_page'];
			
			if( isset($data['url_base']) )
				$config['url_base'] = $data['url_base'];
			
			if( isset($data['friendly']) )
				$config['friendly'] = $data['friendly'];
			
			if( isset($data['page_string']) )
				$config['page_string'] = $data['page_string'];
			
			if( isset($data['num_links']) )
				$config['num_links'] = $data['num_links'];
			
			//Capturamos el numero de pagina actual desde get
			if( isset($data['current_page']) )
				$config['current_page'] = $data['current_page'];
			else{
				if( isset($_GET[$config['page_string']]) )
					$config['current_page'] = $_GET[$config['page_string']];
			}
		
			//Creamos nuestro objeto de paginacion con los datos personalizados
			return new pagination($config);
		}
		
		public static function totalRows(){
			return DBConnector::foundRows();
		}
	}