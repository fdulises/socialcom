<?php

	/*
	* Clase de la capa de logica para fotos
	*
	* Permite realizar transacciones con fotos
	*/
	trait l_fotos{
		public static function createFoto( $data ){
			return DB::insert(t_fotos)
				->columns(array_keys($data))
				->values(array_values($data))
				->send();
		}
		public static function deleteFoto( $id ){
			$id = (int) $id;
			return DB::delete(t_fotos)
				->where( 'id', '=', $id )
				->send();
		}
		public static function readFoto( $data ){
			$result = DB::select(t_fotos)
				->where( 'id', '=', $data['id'])
				->where( 'estado', '=', 1);
			if( isset( $data['columns'] ) )
				$result->columns($data['columns']);
			return $result->first();
		}
		public static function listFoto( $data ){
			$result = DB::select(t_fotos)
				->where( 'estado', '=', 1);
			if( isset( $data['autor'] ) )
				$result->where('autor', '=', $data['autor']);
			if( isset( $data['columns'] ) )
				$result->columns($data['columns']);
			return $result->get();
		}
		public static function getTotalFotos($id){
			$result = self::listFoto([
				'autor' => $_SESSION[S_USERID],
				'columns' => ['count(*) as total'],
			]);
			if( $result ) return $result[0]['total'];
			return 0;
		}
	}