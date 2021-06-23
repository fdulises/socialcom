<?php

	abstract class tienda{
		
		/*
		* Metodo para obtener listado de productos
		*/
		public static function listar( $data ){
			$result = DB::select(t_productos)
				->columns($data['columns'])
				->where('estado', '=', 1);
			if( isset( $data['tipo'] ) )
				$result->where('tipo', '=', $data['tipo']);
			return $result->get();
		}
		
		/*
		* Metodo para obtener datos de un producto
		*/
		public static function getProducto( $data ){
			$result = DB::select(t_productos)
				->columns($data['columns'])
				->where('estado', '=', 1)
				->where('id', '=', $data['id']);
			if( isset( $data['tipo'] ) )
				$result->where('tipo', '=', $data['tipo']);
			return $result->first();
		}
		
		/*
		* Metodo para saber si el usuario cuenta con los puntos necesarios
		*/
		public static function getUserPuntos( $id ){
			$result = DB::select(t_perfiles)
				->columns(['puntos'])
				->where('id', '=', $id)
				->first();
			if( $result ) return $result['puntos'];
			return 0;
		}
		
		/*
		* Metodo para saber si el usuario cuenta con los puntos necesarios
		*/
		public static function updateUserPuntos( $id, $cantidad ){
			return DB::update(t_perfiles)
				->set("puntos = puntos+({$cantidad})")
				->where('id', '=', $id)
				->send();
		}
		
		/*
		* Metodo para registrar compra
		*/
		public static function registrarCompra( $data ){
			return DB::insert(t_compras)
				->columns(array_keys($data))
				->values(array_values($data))
				->send();
		}
		
		/*
		* Metodo para obtener registros de compra
		*/
		public static function getRegistro( $data ){
			return DB::select(t_compras)
				->columns($data['columns'])
				->where('estado', '=', 1)
				->where('producto', '=', $data['producto'])
				->where('autor', '=', $data['autor'])
				->get();
		}
		
		/*
		* Metodo para conocer si el producto ya se ha comprado
		*/
		public static function getTotalCompras( $id, $autor ){
			$result = DB::select(t_compras)
				->columns(['count(*) as total'])
				->where('estado', '=', 1)
				->where('producto', '=', $id)
				->where('autor', '=', $autor)
				->first();
			if( $result ) return $result['total'];
			return 0;
		}
		
		/*
		* Metodo para procesar compra
		*/
		public static function procesaCompra( $id, $autor ){
			$info = [
				'estado' => 0,
				'error' => [],
			];
			//Obtenemos los datos del producto y los puntos del comprador
			$puntos = self::getUserPuntos($autor);
			$producto = self::getProducto([
				'id' => $id,
				'columns' => ['id', 'precio'],
			]);
			//Obtenemos el numero de compras hechas del producto
			$comprados = self::getTotalCompras( $id, $autor );
			
			if( $producto ){
				if( !$comprados ){
					if( $puntos >= $producto['precio'] ){
						//Le restamos el consto del producto al usuario
						$consulta = self::updateUserPuntos( $autor, -$producto['precio'] );
						
						//Registramos la compra
						$consulta = self::registrarCompra([
							'producto' => $id,
							'autor' => $autor,
							'estado' => 1,
						]);
						$info['estado'] = 1;
					}else $info['error'][] = 'puntos_insuficientes';
				}else $info['error'][] = 'producto_comprado';
			}else $info['error'][] = 'producto_inexistente';
			
			return $info;
		}
		
		/*
		* Metodo para obtener los productos de un usuario
		*/
		public static function getItems( $data ){
			$result = DB::select(t_productos)
				->leftJoin(t_compras, 'producto', '=', 'id')
				->columns($data['columns'])
				->where('autor', '=', $data['id'])
				->where(t_productos.'.estado', '=', 1);
			if( isset( $data['tipo'] ) )
				$result->where('tipo', '=', $data['tipo']);
			return $result->get();
		}
		
	}