<?php
	require 'config/conf_grupos.php';

	$usuario['usuario_grupo'] = $grupos[$usuario['usuario_grupo']]['nombre'];
	
	$registrod = extras::formatoDate($usuario['usuario_fregistro'],'d');
	$registrom = extras::formatoDate($usuario['usuario_fregistro'],'n');
	$registroy = extras::formatoDate($usuario['usuario_fregistro'],'Y');
	$registrom = $lista_meses[$registrom-1];
	$usuario['usuario_fregistro'] = "{$registrod} de {$registrom} del {$registroy}";
	
	$s_list = ['--', 'Hombre', 'Mujer'];
	
	$usuario['usuario_sexo'] = $s_list[$usuario['usuario_sexo']];
	
	$nacimientod = extras::formatoDate($usuario['usuario_nacimiento'],'d');
	$nacimientom = extras::formatoDate($usuario['usuario_nacimiento'],'n');
	$nacimientom = $lista_meses[$nacimientom-1];
	$usuario['usuario_nacimiento'] = "{$nacimientod} de {$nacimientom}";
	
	$mt->plantilla->setEtiqueta([
		'usuario_grupo' => $usuario['usuario_grupo'],
		'usuario_fregistro' => $usuario['usuario_fregistro'],
		'usuario_sexo' => $usuario['usuario_sexo'],
		'usuario_nacimiento' => $usuario['usuario_nacimiento'],
	]);

	$tplsec = 'tpl/perfil_info';