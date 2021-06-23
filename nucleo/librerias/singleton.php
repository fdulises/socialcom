<?php
class singleton{

	private static $instance;

	public static function getInstance(){
		if (null === self::$instance)
			self::$instance = new singleton();
		return self::$instance;
	}

	protected function __construct(){}
	private function __clone(){}
	private function __wakeup(){}
}
