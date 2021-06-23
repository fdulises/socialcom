<?php
	/*
	* Facade para la seccion post
	*/
	
	//Restringimos esta seccion a usuarios con permiso
	if( $user->getGrupo() == 6 ) event::fire('e404');

	require "{$mt->getInfo('tema_url')}/sec_post.php";
