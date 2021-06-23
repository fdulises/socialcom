<?php

	/*
	* webscrap - Ulises Rendon - Todos los derechos reservados
	* v 2017.03.14
	* Clase para obtener contenido de paginas y articulos
	*/
	
	/*
		require 'HttpConnection.php';
		
		$result = new webscrap("http://sitio.algo");
		echo "<h1>{$result->title}</h1>";
		echo "<p><img src='{$result->cover}'></p>";
		echo $result->html;
	*/
	class webscrap{
		
		public $html = "";
		public $title = "";
		public $cover = "";
		
		public function __construct($url){
			$this->getHTML($url);
			$this->getTitle();
			$this->getPrincipal();
			$this->deleteTags();
			$this->deleteAttr();
			$this->trim();
			$this->getCover();
		}
		
		//Obtenemos el codigo fuente de la pagina
		private function getHTML($url){
			$http = new HttpConnection();
			$http->setCookiePath("/cookies");
			$http->init();
			$this->html = $http->get($url);
			$http->close();
		}
		
		//Obtenemos el titulo de la pagina
		private function getTitle(){
			preg_match("/<title>(.*)<\/title>/i", $this->html, $title);
			if(isset($title[1])) $this->title = $title[1];
		}
		
		//Buscamos el contenido principal del documento
		private function getPrincipal(){
			$html = $this->html;
			
			//Obtenemos el contenido del body
			$html = preg_replace("/<body(.*?)>/is", "<body>", $html);
			$html = preg_replace("/(.*)<body>(.*)<\/body>(.*)/is", "$2", $html);
			
			//Obtenemos el contenido principal
			$html = preg_replace("/<main(.*?)>/is", "<main>", $html);
			$html = preg_replace("/(.*)<main>(.*)<\/main>(.*)/is", "$2", $html);
			$html = preg_replace("/<article(.*?)>/is", "<article>", $html);
			$html = preg_replace("/(.*)<article>(.*)<\/article>(.*)/is", "$2", $html);
			
			//Eliminamos todo antes del h1
			if( preg_match("/<\/h1>(.*?)/i", $html) ){
				$html = preg_split("/<\/h1>(.*?)/i", $html);
				if( isset($html[1]) ) $html = $html[1];
			}
			
			$this->html = $html;
		}
		
		//Eliminamos todas las etiquetas a ecepcion de <p><ul><ol><li><img>
		private function deleteTags(){
			$html = $this->html;
			
			//Eliminamos contenido inecesario
			$html = preg_replace("/<script(.*?)<\/script>/is", "", $html);
			$html = preg_replace("/<style(.*?)<\/style>/is", "", $html);
			$html = preg_replace("/<header(.*?)<\/header>/is", "", $html);
			$html = preg_replace("/(<footer>|<footer(.*?)>)(.*)<\/footer>/is", "", $html);
			$html = preg_replace("/<nav(.*?)<\/nav>/is", "", $html);
			$html = preg_replace("/<form(.*?)<\/form>/is", "", $html);
			$html = preg_replace("/<aside(.*?)<\/aside>/is", "", $html);
			$html = preg_replace("/<cite(.*?)<\/cite>/is", "", $html);
			$html = preg_replace("/<quote(.*?)<\/quote>/is", "", $html);
			$html = preg_replace("/<q(.*?)<\/q>/is", "", $html);
			
			//Eliminamos todas las tags html excepto las principales
			$html = strip_tags($html, '<p><ul><ol><li><img>');
			
			$this->html = $html;
		}
		
		//Eliminamos los atributos de las etiquetas restantes
		private function deleteAttr(){
			$html = $this->html;
		
			$html = preg_replace("/<p(.*?)>/is", "<p>", $html);
			$html = preg_replace("/<ul(.*?)>/is", "<ul>", $html);
			$html = preg_replace("/<ol(.*?)>/is", "<ol>", $html);
			$html = preg_replace("/<li(.*?)>/is", "<li>", $html);
			//Eliminamos todos los atributos inecesarios de las imagenes
			$html = preg_replace("/src=(.*?)(\s.)/is", ">[img=$1]<img ", $html);
			$html = preg_replace("/<img(.*?)>/is", "", $html);
			$html = preg_replace("/\[img=(.*?)\]/i", "<img src=$1>", $html);
			
			$this->html = $html;
		}
		
		//Limpiamos los espacios
		private function trim(){
			$this->html = preg_replace("/\s+/i", " ", trim($this->html));
		}
		
		//Obtenemos la primera imagen del contenido
		private function getCover(){
			$cover = '';
			preg_match_all("/<img src=(.*?)>/is", $this->html, $cover);
			if( isset($cover[1]) ){
				if( isset($cover[1][0]) ) $cover = $cover[1][0];
				else $cover = '';
			}
			if( !is_array($cover) ) $this->cover = trim($cover, '\'"');
		}
		
	}
	