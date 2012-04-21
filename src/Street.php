<?php

class Street {
	
	private $_sections;
	private $_name;
	
	public function __construct($name){
		$this->_name = $name;
	}
	
	public function addSection(Section $section){
		$this->_sections[] = $section;
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}