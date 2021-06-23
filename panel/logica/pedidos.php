<?php
	namespace wecor;
	
	abstract class pedidos{
		
		use paginaraux;
		
		public static function getList( $data ){
			$resultado = DB::select(t_pedidos.' c');
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			if( isset($data['order']) ) $resultado->order($data['order']);
			
			return self::getSelect($resultado->getSQL());
		}
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_pedidos)
				->set($data)
				->where('id', '=', $id)
				->send();
				
			return $resultado;
		}
		
	}