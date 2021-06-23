<?php

abstract class referidos{
	public static function create($usuario, $referido, $puntos){
		return DB::insert(t_referidos)
			->columns(['referido', 'usuario', 'puntos', 'fecha'])
			->values([
				$referido,
				$usuario,
				$puntos,
				date('Y-m-d'),
			])
			->send();
	}
	public static function getList($usuario){
		return DB::select(t_referidos.' r')
			->leftJoin(t_usuarios.' u', 'r.usuario', '=', 'u.id')
			->columns(['r.id','r.usuario', 'r.fecha', 'r.puntos','u.nickname'])
			->order('fecha DESC')
			->where('r.referido', '=', (INT) $usuario)
			->get();
	}
	public static function setCookieRef(){
		if( isset($_GET['refuser']) ){
			$_GET['refuser'] = (INT) $_GET['refuser'];
			setcookie( "referido", $_GET['refuser'], strtotime( '+30 days' ) );
		}
	}
	public static function refProccess( $referido ){
		if( isset($_COOKIE['referido']) ){
			$referer = (INT) $_COOKIE['referido'];
			$referido = (INT) $referido;
			$puntos = $GLOBALS['mt']->getInfo('conf_pp_referido');
			
			//Registramos al usuario referido
			self::create( $referido, $referer, $puntos );
			
			//Le asignamos puntos al referer
			$GLOBALS['mt']->uPuntosIncrement( $referer, $puntos );
			$GLOBALS['mt']->uExpIncrement( $referer, $puntos );
			
			//Eliminamos cookie para que no se vuelva a registrar como referido
			setcookie("referido", "", time()-3600);
		}
	}
}