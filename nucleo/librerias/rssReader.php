<?php
class rssReader {
	private $xml;
	private $data;
	public function __construct($url){
		$http = new HttpConnection();
		$http->setCookiePath("/cookies");
		$http->init();
		$this->xml = $http->get($url);
		$http->close();
	}
	public function get_items(){
		preg_match_all("/<item.*>.*<\/item>|<entry.*>.*<\/entry>/xsmUi", $this->xml, $matches);
		$items = array();
		foreach($matches[0] as $match){
			$items[] = new RssItem ($match);
		}
		return $items;
	}
}
class RssItem {
	private $data;
	public function __construct($xml){
		$this->populate ($xml);
	}
	public function populate ($xml){
		//Obtenemos el titulo
		preg_match ("/<title.*>(.*)<\/title>/xsmUi", $xml, $matches);
		$this->data['title'] = $matches[1];
		
		//Obtenemos el elnlace
		preg_match("/<link>(.*)<\/link>/xsmUi", $xml, $matches);
		if( count($matches) ) $this->data['url'] = $matches[1];
		else{
			preg_match_all("/<link.*>/xsmUi", $xml, $matches);
			if( count($matches) ){
				foreach($matches[0] as $actual){
					preg_match("/rel='alternate'/xsmUi", $actual, $match);
					if( count($match) ){
						preg_match("/href=['\"](.*)['\"]/xsmUi", $actual, $match);
						$this->data['url'] = $match[1];
						break;
					}
				}
			}
		}
		
		//Obtenemos la descripcion
		preg_match ("/<description.*>(.*)<\/description>/xsmUi", $xml, $matches);
		if( count($matches) ) $this->data['description'] = $matches[1];
		else{
			preg_match ("/<content.*>(.*)<\/content>/xsmUi", $xml, $matches);
			if( count($matches) ) $this->data['description'] = $matches[1];
		}
		
		//Obtenemos la fecha de publicacion
		preg_match ("/<(updated|pubdate|lastBuildDate)>(.*?)<\/(updated|pubdate|lastBuildDate)>/is", $xml, $matches);
		$this->data['pupdate'] = $matches[2];
	}
	public function __get($name){
		if( isset($this->data[$name]) ) return $this->data[$name];
		return null;
	}
}