<?php

	abstract class logros{
		
		/*
		*	Metodo para obtener lista de logros a asignar
		*/
		public static function getList( $data ){
			$resultado = DB::select(t_logros)->where('estado', '=', 1);
			if( isset($data['accion']) )
				$resultado->where('accion', '<=', $data['accion']);
			if( isset($data['modo']) )
				$resultado->where('modo', '=', $data['modo']);
			else
				$resultado->where('modo', '=', 1);
			if( isset($data['tipo']) )
				$resultado->where('tipo', '=', $data['tipo']);
			else $resultado->where('tipo', '=', 1);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			if( isset($data['order']) ) $resultado->order($data['order']);
			return $resultado->get();
		}
		
		/*
		*	Metodo para obtener lista de logros asignados
		*/
		public static function getRegistro( $usuario, $id ){
			$resultado = DB::select(t_logros_registro)
				->columns('logro_id')
				->where('estado', '=', 1)
				->where('destino', '=', $usuario);
			
			if( is_array($id) ){
				$conds = array();
				foreach( $id as $v ) $conds[] = "logro_id = {$v}";
				if( count( $conds ) ) $resultado = $resultado->where(
					'('.implode( ' OR ', $conds ).')'
				);
			}else $resultado = $resultado->where('logro_id', '=', $id);
			
			$resultado = $resultado->get();
			
			if( $resultado ){
				$ids = array();
				foreach( $resultado as $v ) $ids[] = $v['logro_id'];
				return $ids;
			}
			return array();
		}
		
		/*
		*	Metodo para asignar logros de una lista
		*/
		public static function setRegistroMultiple( $usuario, $logros ){
			$fecha = date( 'Y-m-d' );
			$resultado = DB::insert(t_logros_registro)
				->columns(['logro_id', 'destino', 'fecha', 'estado']);
			foreach( $logros as $v ){
				$resultado->values([
					$v, $usuario, $fecha, 1
				]);
			}
			return $resultado->send();
		}
		
		/*
		*	Metodo que asigna logros dependiendo de la cantidad de puntos
		*/
		public static function autoAssign( $usuario, $accion ){
			//Obtenemos lista de posibles logros a dar
			$list = self::getList([
				'accion' => $accion,
				'order' => 'accion ASC',
				'columns' => ['id']
			]);
			$logrosids = array();
			foreach( $list as $v ) $logrosids[] = $v['id'];

			if( count($logrosids) ){
				//Verficiamos que no se hayan dado ya
				$registros = self::getRegistro( $usuario, $logrosids );

				if( $registros ){
					//Descartamos los logros que ya se registraron
					foreach( $logrosids as $k => $v ){
						if( in_array($v, $registros) )
							unset($logrosids[$k]);
					}
				}
				
				//Registramos los logros que aun no se registran
				if( $logrosids )
					self::setRegistroMultiple( $usuario, $logrosids );
				
			}
			return $logrosids;
		}
		
		public static function get( $id, $columns ){
			$result = DB::select(t_logros_registro.' r')
				->leftJoin(t_logros.' l', 'r.logro_id', '=', 'l.id')
				->columns($columns)
				->where('l.estado', '=', 1)
				->where('r.destino', '=', $id)
				->order('fecha ASC')
				->get();
			return $result;
		}
	}