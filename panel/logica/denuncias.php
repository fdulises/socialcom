<?php
	namespace wecor;
	
	abstract class denuncias{
		
		use paginaraux;
		
		public static function getList( $data ){
			$resultado = DB::select(t_denuncias.' d')
				->leftJoin(t_usuarios.' u', 'd.autor', '=', 'u.id');
			if( isset($data['columns']) ) $resultado->columns($data['columns']);
			if( isset($data['limit'], $data['offset']) )
				$resultado->limit($data['limit'], $data['offset']);
			else if( isset($data['limit']) ) $resultado->limit($data['limit']);
			if( isset($data['order']) ) $resultado->order($data['order']);
			
			return self::getSelect($resultado->getSQL());
		}
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_denuncias)
				->set($data)
				->where('id', '=', $id)
				->send();
				
			return $resultado;
		}
		
	}