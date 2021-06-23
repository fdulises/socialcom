<?php
	/*
	*	Motor de plantillas dinamicas 22.03.16
	*	Script Creado Por Ulises Rendón
	* 	debred.com
	*/
	
	namespace wecor;
	
	class templates{

		//Propiedades que contendran las etiquetas bloques y patrones a remplazar
		private $etiquetas = array();
		private $bloques = array();
		private $patrones = array();

		//Propiedades con datos de configuración
		private $directorio = '';
		private $extension = '';
		private $delimitadorabrir = '{';
		private $delimitadorcerrar = '}';
		private $config = array(
			'date' => true,
			'escape' => false,
		);

		public $erroes = array();

		public function __construct($dir='', $ext=''){
			//Establecemos la ruta del directorio con los archivos de plantilla y la extension de los arhivos
			$this->setDir($dir);
			$this->setExt($ext);
			//Patron para ocultar comentarios en codigo
			$this->setPatron('/\{\*(.*?)\*\}/is', '');
			$this->setPatron('/\{\*\}(.*?)\{\/\*\}/is', '');
		}

		//Metodo para establecer la ruta al directorio con los archivos de plantilla
		public function setDir($dir){
			if($dir == '') $this->directorio = '';
			else $this->directorio = "{$dir}/";
		}

		//Metodo para establecer la extension de los archivos de plantilla
		public function setExt($ext){
			if($ext == '') $this->extension = '';
			else $this->extension = ".{$ext}";
		}

		//Metodo para establecer datos de configuracion
		public function setConfig($datos, $valor = true){
			if( is_array( $datos ) ){
				foreach ($datos as $k => $v)
					$this->config[$k] = $v;
			}else $this->config[$datos] = $valor;
		}

		//Metodo para establecer los delimitadores de variables y bloques
		public function setDelimitadores($dela, $delc){
			$this->delimitadorabrir = $dela;
			$this->delimitadorcerrar = $delc;
		}

		//Metodo para establecer una etiqueta con su nombre y su valor
		public function setEtiqueta($clave, $valor = ''){
			if( !is_array($clave) ){
				$clave = $this->delimitadorabrir.$clave.$this->delimitadorcerrar;
				$this->etiquetas[$clave] = $valor;
			}else{
				foreach($clave as $k => $v){
					$clavetag = $this->delimitadorabrir.$k.$this->delimitadorcerrar;
					$this->etiquetas[$clavetag] = $v;
				}
			}
		}

		//Metodo para establecer un bloque
		public function setBloque($nombre, $datos, $tags = array()){
			$dabrir = "\\".$this->delimitadorabrir;
			$dcerrar = "\\".$this->delimitadorcerrar;
			$patron = "/{$dabrir}{$nombre}{$dcerrar}(.*?){$dabrir}\/{$nombre}{$dcerrar}/is";
			
			if( !$tags ){
				$tags = array();
				foreach ($datos as $k => $v)
					foreach ($v as $clave => $contenido) $tags[] = $clave;
			}
			foreach($tags as $k => $v) $tags[$k] = $this->delimitadorabrir.$v.$this->delimitadorcerrar;

			$this->bloques[$nombre] = array(
				'patron' => $patron,
				'datos' => $datos,
				'tags' => $tags
			);
		}

		//Metodo para asignar nuevo patron
		public function setPatron($patron, $valor){
			$this->patrones[$patron] = $valor;
		}

		//Metodo para asignar nuevo patron
		public function setCondicion($nombre, $condicion){
			$patron = "/\\{$this->delimitadorabrir}{$nombre}"
			."\\{$this->delimitadorcerrar}(.*?)"
			."\\{$this->delimitadorabrir}\/{$nombre}"
			."\\{$this->delimitadorcerrar}/is";
			$this->setPatron($patron, ( $condicion ) ? '$1': '');
		}

		//Metodo para obtener el contenido de un archivo de plantilla
		public function getHTML($nombre){
			$ruta = $this->directorio.$nombre.$this->extension;
			$html = $this->getFile($ruta);
			return $html;
		}

		//Metodo para obtener el contenido de un archivo
		public function getFile($ruta){
			$cadena = '';
			if( file_exists($ruta) ) $cadena = file_get_contents($ruta);
			else $this->errores[] = 'Error de plantilla: Archivo "'.htmlentities($ruta).'" no encontrado';
			return $cadena;
		}

		//Metodo para buscar una cadena en otra cadena apartir de un patron
		private function getCadenaPatron($cadena, $patron, $todos = false){
			if(!$todos){
				preg_match($patron, $cadena, $resultado);
				if($resultado) return $resultado[1];
			}else{
				preg_match_all($patron, $cadena, $resultado, PREG_SET_ORDER);
				if($resultado) return $resultado;
			}
			return false;
		}

		//Metodo para dar funcionamiento a la etiqueta {include}
		private function sustIncludes($cadena){
			$patron = '/\{include=(.*?)\}/is';
			$rutas = array();
			$inclusiones = array();
			while($matches = $this->getCadenaPatron($cadena, $patron, true)){
				foreach($matches as $v){
					$ruta = $this->directorio.$v[1].$this->extension;
					if(!array_key_exists($v[1], $rutas)){
						$rutas[$v[1]] = $ruta;
						$html = $this->getFile($ruta);
						$inclusiones[$v[0]] = $html;
					}
				}
				$cadena = $this->sustEtiquetas($cadena, $inclusiones);
			}
			return $cadena;
		}

		//Metodo para sustituir etiquetas en una cadena apartir de un array con los datos de las etiquetas
		private function sustEtiquetas($cadena, $tags = false, $valores = false){
			//Si se proporciona un array con las etiquetas las usamos para la sustitucion
			if( is_array($tags) ){
				if( is_array($valores) ) $cadena = str_replace(
					$tags, $valores, $cadena
				);
				else $cadena = str_replace(
					array_keys($tags), array_values($tags), $cadena
				);
			}else{
				//Si no se proporciona un array hacemos la sustitucion usando la propiedad de la clase etiquetas
				$cadena = str_replace(array_keys($this->etiquetas), array_values($this->etiquetas), $cadena);
			}
			return $cadena;
		}

		//Metodo para hacer la sustitucion de todos lo bloques
		private function sustBloques($cadena){
			$render = array();
			foreach($this->bloques as $k => $v){
				$fragmento = $this->getCadenaPatron($cadena, $v['patron']);
				$render[$v['patron']] = '';
				foreach($v['datos'] as $a){
					$render[$v['patron']] .= $this->sustEtiquetas($fragmento, $v['tags'], $a);
				}
			}
			$cadena = preg_replace(array_keys($render), array_values($render), $cadena);
			return $cadena;
		}

		//Metodo para realizar las sustituciones de patrones en la cadena indicada
		private function sustPatrones($cadena){
			$render = preg_replace(array_keys($this->patrones), array_values($this->patrones), $cadena);
			return $render;
		}

		public function sustDate($cadena){
			$remplazos = array();
			$coincidencias = array();
			$coincidencias = $this->getCadenaPatron(
				$cadena, '/\{TIEMPOFORMATO\|(.*?)\|(.*?)\}/i', true
			);
			if( $coincidencias ){
				foreach ($coincidencias as $k => $v) {
					$remplazos[$v[0]] = date( $v[1], strtotime($v[2]) );
				}
				$coincidencias = $this->getCadenaPatron(
					$cadena, '/\{TIEMPO\|(.*?)\}/i', true
				);
				foreach ($coincidencias as $k => $v) {
					$remplazos[$v[0]] = date($v[1]);
				}
				$render = str_replace(
					array_keys($remplazos),
					array_values($remplazos),
					$cadena
				);
				return $render;
			}
			return $cadena;
		}

		public function escape($cadena){
			return htmlentities($cadena);
		}

		//Metodo para realizar las sustituciones
		public function parse($cadena){
			$cadena = $this->sustIncludes($cadena);
			$cadena = $this->sustBloques($cadena);
			$cadena = $this->sustPatrones($cadena);
			$cadena = $this->sustEtiquetas($cadena);
			if( $this->config['date'] )
				$cadena = $this->sustDate($cadena);
			if( $this->config['escape'] )
				$cadena = $this->escape($cadena);
			return $cadena;
		}

		//Metodo para mostrar el codigo final
		public function displayCadena($cadena){
			print($this->parse($cadena));
		}

		//Metodo para mostrar sustitucion de una cadena extraida de un archivo por su ruta
		public function displayRuta($ruta){
			print($this->parse($this->getFile($ruta)));
		}

		//Metodo para mostrar un archivo de plantilla final
		public function display($nombre){
			print($this->parse($this->getHTML($nombre)));
		}
	}
