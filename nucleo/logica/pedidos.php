<?php

abstract class pedidos{
	public static function create($nombre){
		return DB::insert(t_pedidos)->columns(['nombre'])->values([$nombre])->send();
	}
	public static function getList(){
		return DB::select(t_pedidos)
			->columns(['id','nombre', 'estado'])
			->order('id DESC')
			->get();
	}
}