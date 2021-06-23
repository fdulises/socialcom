<?php
	namespace wecor;
	
	abstract class coment{
		
		use paginaraux;
		
		public static function get( $data ){
			$resultado = DB::select(t_comentarios)
				->where('id', '=', $data['id'])
				->where('estado', '>', 0);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			return $resultado->first();
		}
		
		public static function getList( $data ){
			$resultado = DB::select(t_comentarios.' c')
				->leftJoin(t_usuarios.' u', 'c.usuario', '=', 'u.id')
				->leftJoin(t_entradas.' e', 'c.destino', '=', 'e.id')
				->leftJoin(t_colecciones.' cat', 'e.coleccion', '=', 'cat.id')
				->where('c.estado', '>', 0);
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			if( isset($data['order']) ) $resultado->order($data['order']);
			
			if( isset($data['denuncias']) ){
				$resultado->where('c.denuncias', '>=', $data['denuncias']);
			}
			
			return self::getSelect($resultado->getSQL());
		}
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_comentarios)
				->set($data)
				->where('id', '=', $id)
				->send();
				
			return $resultado;
		}
		
		public static function delete( int $data ){
			$resultado = DB::delete(t_comentarios)
				->where('id', '=', $data)
				->send();
				
			return $resultado;
		}
		
	}