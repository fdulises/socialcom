<?php
	function randomSalt(){
		//return uniqid(mt_rand(1, mt_getrandmax()), true);
		return hash( 'sha512', uniqid(openssl_random_pseudo_bytes(16), true) );
	}
	
	function sha512Validate($cadena){
		if( (strlen($cadena) == 128) && ctype_xdigit($cadena) ) return 1;
		return 0;
	}
	
	function generateSPass($pass, $salt){
		if( !sha512Validate($pass) ) $pass = hash('sha512', $pass);
		return hash( 'sha512', $pass.$salt);
	}