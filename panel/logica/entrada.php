<?php

	namespace wecor;
	
	abstract class entrada{
		
		use paginaraux;
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_entradas)
				->set($data)
				->where('id', '=', $id)
				->send();
				
			return $resultado;
		}
		
		public static function getList( $data = array() ){
			$resultado = DB::select(t_entradas.' e')
				->leftJoin(t_colecciones.' c', 'e.coleccion', '=', 'c.id')
				->leftJoin(t_usuarios.' u', 'e.usuario', '=', 'u.id')
				->leftJoin(t_perfiles.' p', 'e.usuario', '=', 'p.id')
				->where('e.estado', '!=', 0)
				->where('u.estado', '=', 1);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['tipo']) )
				$resultado->where('e.tipo', '=', $data['tipo']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			
			if( isset($data['order']) )
				$resultado->order($data['order']);
			if( isset($data['denuncias']) )
				$resultado->where('e.denuncias', '>=', $data['denuncias']);
			return self::getSelect($resultado->getSQL());
		}
		
		public static function catGetList( $data = array() ){
			$resultado = DB::select(t_colecciones)
				->where('estado', '!=', 0);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['tipo']) )
				$resultado->where('tipo', '=', $data['tipo']);
			return $resultado->get();
		}
		
		public static function catGet( $data = array() ){
			$resultado = DB::select(t_colecciones)->where('id', '=', $data['id']);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['tipo']) )
				$resultado->where('tipo', '=', $data['tipo']);
			return $resultado->first();
		}
		
		public static function catUpdate( $id, $data ){
			return DB::update(t_colecciones)->set($data)
				->where( 'id', '=', $id )
				->send();
		}
		
		public static function catCreate( $data ){
			$resultado = DB::insert(t_colecciones)
				->columns(array_keys($data))
				->values(array_values($data))
				->send();
				
			return $resultado;
		}
	}