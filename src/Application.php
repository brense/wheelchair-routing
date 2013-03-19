<?php

class Application {
	
	private $_sources;
	private $_uri;
	private static $_loaded;
	
	public function __construct($uri){
     if(substr($uri, -1, 1) == '/'){
        $this->_uri = substr($uri, 0, -1);
     } else {
       $this->_uri = $uri;
     }
		$this->_sources[] = str_replace('Application.php', '', __FILE__);
		spl_autoload_register(array($this, 'autoload'));
		self::$_loaded[] = get_class($this);
	}
	
	private function autoload($class){
		if(!in_array($class, self::$_loaded)){
			$path = str_replace('\\', '/', $class);
			$path = str_replace('_', '/', $class);
			$found = false;
			foreach($this->_sources as $source){
				if(file_exists($source . $path . '.php')){
					include($source . $path . '.php');
					spl_autoload($class);
					self::$_loaded[] = $class;
					$found = true;
					break;
				}
			}
			if(!$found){
				throw new \Exception('class ' . $class . ' not found');
			}
		}
	}
	
	public function getPage(){
		ob_start();
		if($this->_uri == ''){
			include('home.php');
		} else if(file_exists($this->_uri . '.php')){
			include($this->_uri . '.php');
		} else {
			include('404.php');
		}
		$contents = ob_get_contents();
		ob_end_clean();
		if(!isset($head)){
			$head = '';
		}
		return array('contents' => $contents, 'head' => $head);
	}
	
}