<?php
	namespace wecor;
	
	abstract class adds{
		
		use paginaraux;
		
		public static function create( $data ){
			$resultado = DB::insert(t_adds)
				->columns(array_keys($data))
				->values(array_values($data))
				->send();
				
			return $resultado;
		}
		
		public static function get( $data ){
			$resultado = DB::select(t_adds);
			$resultado->where('id', '=', $data['id']);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			return $resultado->first();
		}
		
		public static function getList( $data ){
			$resultado = DB::select(t_adds);
			$resultado->where('estado>0');
			if( isset($data['columns']) )
				$resultado->columns($data['columns']);
			if( isset($data['tipo']) )
				$resultado->where('tipo', '=', $data['tipo']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			if( isset($data['order']) ) $resultado->order($data['order']);
			
			return self::getSelect($resultado->getSQL());
		}
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_adds)
				->set($data)
				->where('id', '=', $id)
				->send();
				
			return $resultado;
		}
		
		public static function delete( int $data ){
			$resultado = DB::delete(t_adds)
				->where('id', '=', $data)
				->send();
				
			return $resultado;
		}
		
	}