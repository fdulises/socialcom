<?php

	namespace wecor;
	
	/*
		Generador de datos para paginacion
		
		$pag = new pagination([
			'total' => 100, //Total de elementos
			'per_page' => 10, //Elementos por pagina
			'current_page' => $_GET['p'], //Pagina actual
			'url_base' => 'pagination.php', //Url base para links
			'friendly' => false, //False - url_base?pagina=n || True - url_base/n
			'page_string' => 'p', //Si friendly == true : url_base?page_string=n
			'num_links' => 2, //Numero de links a mostrar antes y despues de current
		]);
	*/
	class pagination{
		private $url_base = '';
		private $next = 1;
		private $prev = 1;
		private $friendly = true;
		private $page_string = 'page';
		private $hasNext = 0;
		private $hasPrev = 0;
		private $num_links = 2;
		
		private $info = array(
			"total" => 0,
			"per_page" => 1,
			"current_page" => 1,
			"last_page" => 1,
			"next_page_url" => null,
			"prev_page_url" => null,
		);
		
		public function __construct($data){
			if( isset($data['total']) ){
				$this->info['total'] = (INT) $data['total'];
				if( $this->info['total'] < 0 ) $this->info['total'] = 0;
			}
			if( isset($data['per_page']) ){
				$this->info['per_page'] = (INT) $data['per_page'];
				if( $this->info['per_page'] < 1 ) $this->info['per_page'] = 1;
			}
			if( isset($data['current_page']) ){
				$this->info['current_page'] = (INT) $data['current_page'];
				if( $this->info['current_page'] < 1 )
					$this->info['current_page'] = 1;
			}
			if( isset($data['url_base']) ) $this->url_base = $data['url_base'];
			
			if( isset($data['friendly']) ) $this->friendly = $data['friendly'];
			if( isset($data['page_string']) )
					$this->page_string = $data['page_string'];
				
			if( isset($data['num_links']) )
				$this->num_links = (INT) $data['num_links'];
			
			$this->process();
		}
		
		/*
		* Metodo que genera los datos de la paginacion
		*/
		private function process(){
			//Calculamos el numero total de paginas
			$this->info['last_page'] = ceil( $this->info['total'] / $this->info['per_page'] );
			
			//Definimos la pagina siguiente
			if( ($this->info['last_page'] > 1) && ($this->info['current_page'] < $this->info['last_page']) ){
				$this->next = $this->info['current_page']+1;
				$this->hasNext = 1;
			}else if( $this->info['current_page'] > $this->info['last_page'] )
				$this->next = $this->info['last_page'];
			else $this->next = $this->info['current_page'];
			
			
			//Definimos la pagina anterior
			if( $this->info['current_page'] > $this->info['last_page'] )
				$this->prev = $this->info['last_page'];
			else if( $this->info['current_page'] > 1 ){
				$this->prev = $this->info['current_page']-1;
			}
			if( $this->info['current_page'] > 1 ) $this->hasPrev = 1;
			
			//Definimos los enlaces de la paginacion
			$url_base = '';
			if( $this->friendly ) $url_base = $this->url_base;
			else{
				$glue = preg_match('/\?/', $this->url_base) ? "&" : "?";
				$this->url_base = "{$this->url_base}{$glue}{$this->page_string}=";
			}
			$this->info['prev_page_url'] = "{$this->url_base}{$this->prev}";
			$this->info['next_page_url'] = "{$this->url_base}{$this->next}";
		}
		
		/*
		* Metodo para generar automaticamente lista de enlaces
		* Retorna: string - html
		*/
		private function generateLinks($number, $link, $current){
			if( $number == $current )
				return "<a href='{$link}' class='active'>{$number}</a>";
			return "<a href='{$link}'>{$number}</a>";
		}
		
		/*
		* Metodo para conocer si existe pagina siguiente
		* Retorna: boleano
		*/
		public function hasNext(){
			return $this->hasNext;
		}
		/*
		* Metodo para conocer si existe pagina anterior
		* Retorna: boleano
		*/
		public function hasPrev(){
			return $this->hasPrev;
		}
		
		/*
		* Metodo para conocer si existe pagina siguiente
		* Parametros: $callback - Funcion con la que se generara lista de enlaces
		* Retorna: string - html
		*/
		public function getLinks( $callback = false ){
			$html = '';
			
			$cond_inicio = $this->info['current_page'] - $this->num_links;
			if( $cond_inicio < 1 ) $cond_inicio = 1;
			$cond_fin = $this->info['current_page'] + $this->num_links;
			if( $cond_fin > $this->info['last_page'] )
				$cond_fin = $this->info['last_page'];
			
			for( $i=$cond_inicio; $i<=$cond_fin; $i++ ){
				if( is_callable($callback) ) $html .= $callback(
					$i, "{$this->url_base}{$i}", $this->info['current_page']
				);
				else $html .= $this->generateLinks(
					$i, "{$this->url_base}{$i}", $this->info['current_page']
				);
			}
			return $html;
		}
		
		/*
		* Metodo para obtener datos de la paginacion
		* Parametros: $data - Clave de la informacion a obtener
		* Retorna: array - Contiene toda la informacion || string - info deseada
		*/
		public function result( $data = null ){
			if( is_string($data) )
				return ( isset($this->info[$data]) ) 
					? $this->info[$data] 
					: null;
			return $this->info;
		}
	}