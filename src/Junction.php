<?php

class Junction {
	
	private $_position;
	private $_sections = array();
	
	public function __construct(LatLong $pos){
		$this->_position = $pos;
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