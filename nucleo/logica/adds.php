<?php
	abstract class adds{
		
		public static function getRotate( $data ){
			$resultado = DB::select(t_adds);
			$resultado->where('estado>0');
			if( isset($data['columns']) ) $resultado->columns(
				$data['columns']
			);
			else $resultado->columns(['id', 'codigo']);
			if( isset($data['tipo']) ) $resultado->where(
				'tipo', '=', $data['tipo']
			);
			$resultado->order('updated ASC');
			$resultado = $resultado->first();
			
			if($resultado) self::update($resultado['id'], [
				'updated' => date('Y-m-d H:i:s')
			]);
			return $resultado;
		}
		
		public static function update( $id, $data ){
			$resultado = DB::update(t_adds)
				->set($data)
				->where('id', '=', $id)
				->send();
			return $resultado;
		}
		
	}