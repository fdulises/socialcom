<?php

	/*
	* Clase auxiliar para construir peticiones a la BD
	*/
	abstract class dbConsultas{

		/*
		* Metodo para insertar filas en una tabla
		*/
		public static function insert($datos){
			$tabla = '';
			$columnas = '';

			if( isset($datos['tabla']) )
				$tabla = dbConnector::escape($datos['tabla']);

			//Definimos los campos a insertar
			if( isset($datos['columnas']) ){
				$columnas = dbConnector::escape($datos['columnas']);
				$columnas = implode(', ', $datos['columnas']);
				$columnas = "($columnas)";
			}

			//definimos los valores que se insertaran
			if( isset($datos['valores']) ){
				$values = array();
				$multidi = false;
				foreach ($datos['valores'] as $v) {
					if( is_array($v) ){
						$multidi = true;
						$valuesB = array();
						foreach ($v as $b) {
							$b = dbConnector::escape($b);
							$valuesB[] = "'{$b}'";
						}
						$valuesB = implode(', ', $valuesB);
						$values[] = "({$valuesB})";
					}else{
						$v = dbConnector::escape($v);
						$values[] = "'{$v}'";
					}
				}
				$values = implode(', ', $values);
				if( $multidi ) $valores = $values;
				else $valores = "({$values})";
			}

			$consulta = "INSERT INTO {$tabla}{$columnas}
				VALUES{$valores}
			";

			return dbConnector::sendQuery($consulta);
		}

		/*
		* Metodo para recuperar filas de una tabla
		*/
		public static function select($datos){
			$tabla = '';
			$join = '';
			$columnas = '';
			$orden = '';
			$disp = 'ASC';
			$filtro = '';
			$busqueda = false;

			if( isset($datos['tabla']) )
				$tabla = dbConnector::escape($datos['tabla']);

			if( isset( $datos['busqueda'] ) ){
				$camposBusqueda = dbConnector::escape($datos['busqueda']['campos']);
				if( is_array($camposBusqueda) ) $camposBusqueda = implode(', ', $camposBusqueda);
				$cadenaBusqueda = dbConnector::escape($datos['busqueda']['cadena']);
				if( isset($datos['busqueda']['alias']) )
					$aliasBusqueda = dbConnector::escape($datos['busqueda']['alias']);
				else $aliasBusqueda = 'score';
				$filtroBusqueda = "MATCH ({$camposBusqueda}) AGAINST ('{$cadenaBusqueda}')";
				$columnaBusqueda = "{$filtroBusqueda} AS {$aliasBusqueda}";
				$busqueda = array(
					'campo' => $columnaBusqueda,
					'filtro' => $filtroBusqueda,
				);
			}

			//Definimos los campos solicitados
			if( isset($datos['columnas']) ){
				if( count($datos['columnas']) && $busqueda ){
					$columnas = dbConnector::escape($datos['columnas']);
					$columnas = array_merge($columnas, array($busqueda['campo']));
					$columnas = implode(', ', $columnas);
				}else if( count($datos['columnas']) && !$busqueda ){
					$columnas = dbConnector::escape($datos['columnas']);
					$columnas = implode(', ', $columnas);
				}else if( !count($datos['columnas']) && $busqueda ){
					$columnas = $busqueda['campo'];
				}else $columnas = '*';
			}else $columnas = '*';

			//Definimos la clausula JOIN
			if( isset( $datos['join']) ){
				$uniones = array();
				foreach ($datos['join'] as $v) {
					$v = dbConnector::escape($v);
					$uniones[] = "{$v['tipo']} {$v['tabla']} ON {$v['condicion']}";
				}
				$uniones = implode(' ', $uniones);
				$join = " {$uniones}";
			}

			//Definimos los filtros
			if( isset($datos['filtro']) ) $filtro = $datos['filtro'];
			if( $filtro && $busqueda ) $filtro .= " AND {$filtroBusqueda}";
			else if( !$filtro && $busqueda ) $filtro = $filtroBusqueda;

			//Definimos la clausula ORDER
			if( isset( $datos['orden'] ) ){
				$datos['orden'] = dbConnector::escape($datos['orden']);
				if( isset( $datos['disp'] ) ){
					$datos['disp'] = dbConnector::escape($datos['disp']);
					$orden = " ORDER BY {$datos['orden']} {$datos['disp']}";
				}else $orden = " ORDER BY {$datos['orden']} {$disp}";
			}
			//Definimos las clausulas limit y offset para order
			if( isset($datos['limit']) ){
				$datos['limit'] = (int) $datos['limit'];
				$orden .= " LIMIT {$datos['limit']}";

				if( isset( $datos['offset'] ) ){
					$datos['offset'] = (int) $datos['offset'];
					$orden .= " OFFSET {$datos['offset']}";
				}
			}

			//Usamos SQL_CALC_FOUND_ROWS si se desea conocer el total de filas
			if( isset($datos['foundrows']) ){
				if( $datos['foundrows'] )
					$columnas = "SQL_CALC_FOUND_ROWS {$columnas}";
			}

			//Generamos la consulta SQL y retornamos el resultado
			$consulta = "SELECT {$columnas}";

			if( $tabla && $join ) $consulta .= " FROM {$tabla}{$join}";
			else if ( $tabla && !$join ) $consulta .= " FROM {$tabla}";
			if( $filtro && $orden ) $consulta .= " WHERE {$filtro}{$orden}";
			else if ( $filtro && !$orden )
				$consulta .= " WHERE {$filtro}";
			else if ( !$filtro && $orden )
				$consulta .= " WHERE {$orden}";
			return dbConnector::query($consulta);
		}

		/*
		* Metodo para actualizar filas en una tabla
		*/
		public static function update($datos){
			$tabla = '';
			$join = '';
			$valores = '';
			$filtro = '';

			if( isset($datos['tabla']) )
				$tabla = dbConnector::escape($datos['tabla']);

			//Definimos la clausula JOIN
			if( isset( $datos['join']) ){
				$uniones = array();
				foreach ($datos['join'] as $v) {
					$v = dbConnector::escape($v);
					$uniones[] = "{$v['tipo']} {$v['tabla']} ON {$v['condicion']}";
				}
				$uniones = implode(' AND ', $uniones);
				$join = " {$uniones}";
			}

			//Definimos los valores a actualizar
			if( isset( $datos['valores'] ) ){
				$actualizaciones = array();
				foreach ($datos['valores'] as $k => $v) {
					$k = dbConnector::escape($k);
					$v = dbConnector::escape($v);
					$actualizaciones[] = "{$k}={$v}";
				}
				$valores = implode(', ', $actualizaciones);
			}

			//Definimos los filtros
			if( isset($datos['filtro']) ) $filtro = $datos['filtro'];

			$consulta = "UPDATE {$tabla}{$join}
				SET {$valores}
				WHERE {$filtro}
			";
			return dbConnector::query($consulta);
		}

		/*
		* Metodo para eliminar filas en una tabla
		*/
		public static function delete($datos){
			$tabla = '';
			$join = '';
			$filtro = '';

			if( isset($datos['tabla']) )
				$tabla = dbConnector::escape($datos['tabla']);

			//Definimos la clausula JOIN
			if( isset( $datos['join']) ){
				$uniones = array();
				foreach ($datos['join'] as $v) {
					$v = dbConnector::escape($v);
					$uniones[] = "{$v['tipo']} {$v['tabla']} ON {$v['condicion']}";
				}
				$uniones = implode(' AND ', $uniones);
				$join = " {$uniones}";
			}

			//Definimos los filtros
			if( isset($datos['filtro']) ) $filtro = $datos['filtro'];

			$consulta = "DELETE FROM {$tabla}{$join}
				WHERE {$filtro}
			";

			return dbConnector::query($consulta);
		}

		/*
		* Metodo para obtener el total de filas de la ultima consulta
		*/
		public static function getTotalFilas(){
			$resultado = self::select(array(
				'columnas' => array('FOUND_ROWS() AS total_filas')
			));

			if( $resultado ) return $resultado[0]['total_filas'];

			return null;
		}

	}
