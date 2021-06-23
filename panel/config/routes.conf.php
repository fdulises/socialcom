<?php

	/*
	* Archivo de configuracion de rutas
	* Lista con las rutas y sus controladores
	*/

	namespace wecor;
	
	routes::add( '/', 							'inicio');
	routes::add( 'inicio', 						'inicio');
	routes::add( 'acceso', 						'acceso');
	routes::add( 'salir', 						'salir');
	routes::add( 'cuenta', 						'cuenta');
	routes::add( 'configuracion', 				'configuracion');
	routes::add( 'usuarios\/editar\/(\d+)', 	'usuarios.editar');
	routes::add( 'usuarios\/eliminar\/(\d+)', 	'usuarios.eliminar');
	routes::add( 'usuarios', 					'usuarios');
	routes::add( 'entradas', 					'entradas');
	routes::add( 'categorias', 					'categorias');
	routes::add( 'categorias\/editar\/(\d+)', 	'categorias.editar');
	routes::add( 'categorias\/crear', 			'categorias.crear');
	routes::add( 'comentarios',					'comentarios');
	routes::add( 'comentarios\/editar\/(\d+)',	'comentarios.editar');
	routes::add( 'denuncias', 					'denuncias');
	routes::add( 'tienda', 						'tienda');
	routes::add( 'tienda\/crear', 				'tienda.crear');
	routes::add( 'tienda\/editar\/(\d+)', 		'tienda.editar');
	routes::add( 'tienda\/eliminar\/(\d+)', 	'tienda.eliminar');
	routes::add( 'logros', 						'logros');
	routes::add( 'logros\/crear', 				'logros.crear');
	routes::add( 'logros\/editar\/(\d+)', 		'logros.editar');
	routes::add( 'logros\/eliminar\/(\d+)', 	'logros.eliminar');
	routes::add( 'webscrap', 					'webscrap');
	routes::add( 'webscrap\/crear', 			'webscrap.crear');
	routes::add( 'webscrap\/editar\/(\d+)', 	'webscrap.editar');
	routes::add( 'pedidos', 					'pedidos');
	routes::add( 'denuncias', 					'denuncias');
	routes::add( 'denuncias\/usuarios', 		'denuncias.usuarios');
	routes::add( 'denuncias\/entradas', 		'denuncias.entradas');
	routes::add( 'denuncias\/comentarios', 		'denuncias.comentarios');
	routes::add( 'adds', 						'adds');
	routes::add( 'adds\/crear', 				'adds.crear');
	routes::add( 'adds\/editar\/(\d+)',			'adds.editar');