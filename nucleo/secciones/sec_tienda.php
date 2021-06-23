<?php

	if( isset($_GET['comprar']) && $user->logingCheck() ){
		
		$id = (int) $_GET['comprar'];
		$autor = (int) $_SESSION[S_USERID];
		
		$info = tienda::procesaCompra( $id, $autor );
		
		echo json_encode($info);
		exit();
	}

	require "{$mt->getInfo('tema_url')}/sec_tienda.php";