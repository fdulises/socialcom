<?php

	/*
	* Clase de la capa de datos para entradas
	*
	* Permite realizar transacciones con entradas y colecciones
	*/
	abstract class db_entrada extends db_tablas{

		private $campos = array(
			'e_id' => 'e.id',
			'e_titulo' => 'e.titulo',
			'e_url' => 'e.url',
			'e_fecha' => 'e.fecha',
			'e_fecha_u' => 'e.fecha_u',
			'e_descrip' => 'e.descrip',
			'e_contenido' => 'e.contenido',
			'e_superior' => 'e.superior',
			'e_tags' => 'e.tags',
			'e_portada' => 'e.portada',
			'e_estado' => 'e.estado',
			'e_tipo' => 'e.tipo',
			'e_plantilla' => 'e.plantilla',
			'e_coment' => 'e.total_coment',
			'col_id' => 'e.coleccion AS coleccion_id',
			'col_nombre' => 'col.nombre AS coleccion_nombre',
			'col_url' => 'col.url AS coleccion_url',
			'col_descrip' => 'col.descrip AS coleccion_descrip',
			'col_tipo' => 'col.tipo AS coleccion_tipo',
			'col_superior' => 'col.superior AS coleccion_superior',
			'col_estado' => 'col.estado AS coleccion_estado',
			'col_total_e' => 'col.total_e AS coleccion_total_e',
			'e_autor_id' => 'e.usuario AS autor_id',
			'u_nickname' => 'u.nickname autor_nickname',
			'u_email' => 'u.email autor_emai',
			'u_grupo' => 'u.grupo autor_grupo',
			'u_nombre' => 'p.nombre autor_nombre',
			'u_descrip' => 'p.descrip autor_descrip',
			'u_sitio' => 'p.sitio autor_sitio',
		);

		protected function __construct(){}

		protected function dbGetEntrada($datos = null){

			$condicion = "e.estado = 1";
			$orden = 'e.id';
			$disp = 'ASC';

			if( is_array($datos) ){

				if( isset($datos['filtro']) ) $condicion .= " AND {$datos['filtro']}";

				//Definimos si se busca la entrada por id
				if( isset( $datos['id'] ) ){
					$datos['id'] = (INT) $datos['id'];
					$condicion .= " AND e.id = '{$datos['id']}'";
					//Definimos si se busca la entrada por fecha
				}else if( isset( $datos['url'] ) ){
					$datos['url'] = dbConnector::escape($datos['url']);
					$condicion .= " AND e.url = '{$datos['url']}'";
					//Definimos si se busca la entrada por fecha exacta
				}else if( isset( $datos['fecha'] ) ){
					$datos['fecha'] = dbConnector::escape($datos['fecha']);
					$datos['fecha'] = extras::formatoDate(
						$datos['fecha'], 'Y-m-d H:i:s'
					);
					$condicion .= " AND e.fecha_u <= '{$datos['fecha']}'";
					//Definimos si se buscan entradas por intervalos de fecha
				}else if(
					isset( $datos['fecha_before'] ) &&
					isset( $datos['fecha_after'] )
				){
					$datos['fecha_before'] = extras::formatoDate(
						$datos['fecha_before'], 'Y-m-d H:i:s'
					);
					$datos['fecha_after'] = extras::formatoDate(
						$datos['fecha_after'], 'Y-m-d H:i:s'
					);
					$condicion .= " AND e.fecha_u < '{$datos['fecha_before']}'";
					$condicion .= " AND e.fecha_u > '{$datos['fecha_after']}'";
					//Definimos si se busca la entrada con condiciones de fecha
				}else if( isset( $datos['fecha_cond'] ) ){
					$datos['fecha_cond'] = dbConnector::escape(
						$datos['fecha_cond']
					);
					$condicion .= " AND {$datos['fecha_cond']}";
					//Definimos si se busca la entrada por url de coleccion
				}else if( isset( $datos['coleccion_url'] ) ){
					$datos['coleccion_url'] = dbConnector::escape(
						$datos['coleccion_url']
					);
					$condicion .= " AND col.url = '{$datos['coleccion_url']}'";
					//Definimos si se busca la entrada por id de coleccion
				}else if( isset( $datos['coleccion_id'] ) ){
					$datos['coleccion_id'] = dbConnector::escape(
						$datos['coleccion_id']
					);
					$condicion .= " AND e.coleccion = '{$datos['coleccion_id']}'";
				}

				if( isset( $datos['usuario'] ) ){
					$datos['usuario'] = dbConnector::escape($datos['usuario']);
					$condicion .= " AND u.nickname = '{$datos['usuario']}'";
				}else if( isset( $datos['usuario_id'] ) ){
					$datos['usuario_id'] = (INT) $datos['usuario_id'];
					$condicion .= " AND e.usuario = '{$datos['usuario_id']}'";
				}

				//Definimos si se busca entradas por su tipo
				if( isset($datos['tipo']) ){
					$datos['tipo'] = (INT) $datos['tipo'];
					$condicion .= " AND e.tipo = '{$datos['tipo']}'";
				}

				//Definimos las opciones para la clausula ORDER
				if( isset($datos['orden']) ){
					$orden = dbConnector::escape( $datos['orden'] );
					if( isset($datos['disp']) )
						$disp =  dbConnector::escape( $datos['disp'] );
				}
				
				$condicion .= " AND u.estado != 0";
			}

			//Definimos los campos solicitados
			if( !isset($datos['columnas']) ) $datos['columnas'] = $this->campos;

			$opciones = array(
				'tabla' => "{$this->t_entradas} e",
				'join' => array(
					array(
						'tipo' => 'LEFT JOIN',
						'tabla' => "{$this->t_colecciones} col",
						'condicion' => "e.coleccion = col.id",
					),
					array(
						'tipo' => 'LEFT JOIN',
						'tabla' => "{$this->t_usuarios} u",
						'condicion' => "e.usuario = u.id",
					),
					array(
						'tipo' => 'LEFT JOIN',
						'tabla' => "{$this->t_perfiles} p",
						'condicion' => "e.usuario = p.id",
					)
				),
				'columnas' => $datos['columnas'],
				'filtro' => $condicion,
				'orden' => $orden,
				'disp' => $disp,
			);

			//Establecemos la configuracion para limit
			if( isset($datos['limit']) ){
				$opciones['limit'] = (INT) $datos['limit'];
				if( isset($datos['offset']) )
					$opciones['offset'] = (INT) $datos['offset'];
			}

			//Establecemos la configuracion para la funcion SQL_CALC_FOUND_ROWS
			if( isset($datos['foundrows']) ){
				if( is_bool( $datos['foundrows'] ) )
					$opciones['foundrows'] = $datos['foundrows'];
			}

			//Definimos si se require de busqueda
			if( isset($datos['busqueda']) ) $opciones['busqueda'] = $datos['busqueda'];

			return dbConsultas::select($opciones);
		}

		protected function dbGetColeccion($datos = null){
			$condicion = "col.estado = 1";
			$orden = 'col.id';
			$disp = 'ASC';

			if( is_array($datos) ){
					//Definimos si se busca la entrada por id
				if( isset( $datos['id'] ) ){
					$datos['id'] = (INT) $datos['id'];
					$condicion .= " AND col.id = '{$datos['id']}'";
					//Definimos si se busca la entrada por fecha
				}else if( isset( $datos['url'] ) ){
					$datos['url'] = dbConnector::escape( $datos['url'] );
					$condicion .= " AND col.url = '{$datos['url']}'";
					//Definimos si se busca la entrada por fecha exacta
				}

				//Definimos las opciones para la clausula ORDER
				if( isset($datos['orden']) ){
					$orden = dbConnector::escape( $datos['orden'] );
					if( isset($datos['disp']) )
						$disp =  dbConnector::escape( $datos['disp'] );
				}
			}

			//Definimos los campos solicitados
			if( !isset($datos['columnas']) ) $datos['columnas'] = array();

			//Definimos si se busca la coleccion por su tipo
			if( isset($datos['tipo']) ){
				$datos['tipo'] = (INT) $datos['tipo'];
				$condicion .= " AND col.tipo = '{$datos['tipo']}'";
			}

			$opciones = array(
				'tabla' => "{$this->t_colecciones} col",
				'columnas' => $datos['columnas'],
				'filtro' => $condicion,
				'orden' => $orden,
				'disp' => $disp,
			);

			//Establecemos la configuracion para limit
			if( isset($datos['limit']) ){
				$opciones['limit'] = (INT) $datos['limit'];
				if( isset($datos['offset']) )
					$opciones['offset'] = (INT) $datos['offset'];
			}

			//Establecemos la configuracion para la funcion SQL_CALC_FOUND_ROWS
			if( isset($datos['calcular_filas']) ){
				if( is_bool( $datos['calcular_filas'] ) )
					$opciones['calcular_filas'] = $datos['calcular_filas'];
			}

			return dbConsultas::select($opciones);
		}

		/*
		* Metodo para obtener comentarios de la base de datos
		*/
		protected function dbGetComentario($datos = null){
			$condicion = "estado = 1";
			$orden = 'fecha';
			$disp = 'ASC';

			if( is_array($datos) ){
				if( isset($datos['filtro']) ){
					$condicion .= " AND {$datos['filtro']}";
				}
					//Definimos si se busca el articulo por id
				if( isset( $datos['id'] ) ){
					$datos['id'] = (INT) $datos['id'];
					$condicion .= " AND id = '{$datos['id']}'";
					//Definimos si se busca el articulo por entrada
				}else if( isset( $datos['destino'] ) ){
					$datos['destino'] = dbConnector::escape( $datos['destino'] );
					$condicion .= " AND destino = '{$datos['destino']}'";
				}

				//Definimos las opciones para la clausula ORDER
				if( isset($datos['orden']) ){
					$orden = dbConnector::escape( $datos['orden'] );
					if( isset($datos['disp']) )
						$disp =  dbConnector::escape( $datos['disp'] );
				}
			}

			//Definimos los campos solicitados
			if( !isset($datos['columnas']) ) $datos['columnas'] = array();

			$opciones = array(
				'tabla' => $this->t_comentarios,
				'columnas' => $datos['columnas'],
				'filtro' => $condicion,
				'orden' => $orden,
				'disp' => $disp,
			);

			//Establecemos la configuracion para limit
			if( isset($datos['limit']) ){
				$opciones['limit'] = (INT) $datos['limit'];
				if( isset($datos['offset']) )
					$opciones['offset'] = (INT) $datos['offset'];
			}

			//Establecemos la configuracion para la funcion SQL_CALC_FOUND_ROWS
			if( isset($datos['calcular_filas']) ){
				if( is_bool( $datos['calcular_filas'] ) )
					$opciones['calcular_filas'] = $datos['calcular_filas'];
			}

			return dbConsultas::select($opciones);
		}

		/*
		* Metodo para insertar comentarios en la base de datos
		*/
		protected function dbSetComentario($datos){
			$opciones = array(
				'tabla' => $this->t_comentarios,
				'columnas' => array('destino', 'usuario', 'autor', 'email', 'sitio', 'contenido', 'fecha', 'ip', 'agent', 'tipo', 'superior', 'estado'),
				'valores' => array(
					$datos['destino'],
					$datos['usuario'],
					$datos['autor'],
					$datos['email'],
					$datos['sitio'],
					$datos['contenido'],
					$datos['fecha'],
					$datos['ip'],
					$datos['agent'],
					$datos['tipo'],
					$datos['superior'],
					$datos['estado'],
				),
			);
			$opciones['columnas'] = dbConnector::escape($opciones['columnas']);
			return dbConsultas::insert($opciones);
		}
		/*
		* Metodo para actualizar contadores de comentarios en entrada
		*/
		protected function dbUpdateComentContEntrada($id){
			$id = (INT) $id;
			return dbConsultas::update(array(
				'tabla' => $this->t_entradas,
				'valores' => array(
					'total_coment' => 'total_coment+1'
				),
				'filtro' => "id = '{$id}'",
			));
		}
		/*
		* Metodo para actualizar contadores de comentarios en sitio
		*/
		protected function dbUpdateComentContSitio(){
			return dbConsultas::update(array(
				'tabla' => $this->t_sitio,
				'valores' => array(
					'total_c' => 'total_c+1'
				),
				'filtro' => "1",
			));
		}
		/*
		* Metodo para actualizar contadores de comentarios
		*/
		protected function dbUpdateComentCont($id){
			$entcom = $this->dbUpdateComentContEntrada($id);
			$sitcom = $this->dbUpdateComentContSitio();
			return $entcom && $sitcom;
		}


	}
