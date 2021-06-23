<?php

	$toscrap = DB::select(t_listascrap)
		->columns(['nombre', 'url', 'updated'])
		->where('estado','=',1)
		->get();
	
	foreach( $toscrap as $v ){
		DB::update(t_listascrap)->set(['updated'=>date('Y-m-d H:i:s')])->send();
		
		$urltos = $v['url'];
		$lalsts = strtotime($v['updated']);
		
		$rss = new rssReader ($urltos);
		$total = 0;
		foreach($rss->get_items() as $item){
			if( strtotime($item->pupdate) > $lalsts ){
				$result = new webscrap($item->url);
				$datos = array();
				$datos['titulo'] = $item->title;
				$datos['contenido'] = $result->html;
				$datos['url'] = extras::urlClear($item->title);
				$datos['cover'] = $result->cover;				
				
				if( strlen(strip_tags($datos['contenido'])) > 100 ){
					if( $mt->createScrap($datos) ) $total++;
				}
			}
		}
		
		echo "<h2>Obtener Contenido - ", $v['nombre'],"</h2>";
		echo "<p>URL Fuente: ", $v['url'],"</p>";
		echo "<p>Ultima Actualizacion: ", date('H:i:s d/m/Y', $lalsts), "</p>";
		echo "<p>Total de entradas creadas: ",$total,"</p>";
		echo "<hr><br>";
	}