<?php
	namespace wecor;
	abstract class event{
		private static $events = array();
		public static function add($e, $action, $rank = 10, $args = 0){
			if( !isset(self::$events[$e]) ) self::$events[$e] = array();
			$handler = array(
				'action' => $action,
				'rank' => $rank,
				'args' => $args,
			);
			if( is_string($action) ){
				self::$events[$e][$action] = $handler;
			}else self::$events[$e][] = $handler;
		}
		public static function has($e, $action){
			if( isset( self::$events[$e] ) ){
				if( isset(self::$events[$e][$action]) ) return 1;
			}
			return 0;
		}
		public static function remove($e, $action){
			if( isset(self::$events[$e]) ){
				if( isset(self::$events[$e][$action]) ){
					unset(self::$events[$e][$action]);
				}
			}
		}
		public static function fire($e){
			$args = func_get_args();
			array_shift($args);
			if( isset(self::$events[$e]) ){
				self::$events[$e] = array_reverse(self::$events[$e]);
				uasort(self::$events[$e], array(__NAMESPACE__.'\event', 'compare'));
				foreach( self::$events[$e] as $v ){
					if( is_string($v['action']) )
						$v['action'] = __NAMESPACE__."\\".$v['action'];
					call_user_func_array(
						$v['action'], array_slice($args, 0, $v['args'])
					);
				}
			}
		}
		public static function apply($e, $cont){
			$args = func_get_args();
			array_shift($args);
			if( isset(self::$events[$e]) ){
				self::$events[$e] = array_reverse(self::$events[$e]);
				uasort(self::$events[$e], array(__NAMESPACE__.'\event', 'compare'));
				foreach( self::$events[$e] as $v ){
					if( is_string($v['action']) )
						$v['action'] = __NAMESPACE__."\\".$v['action'];
					$args[0] = call_user_func_array(
						$v['action'], array_slice($args, 0, $v['args']+1)
					);
				}
			}
			return $args[0];
		}
		private static function compare($a, $b){
			if($a['rank'] == $b['rank']) return 0;
			return ($a['rank'] < $b['rank']) ? -1 : 1;
		}
	}
