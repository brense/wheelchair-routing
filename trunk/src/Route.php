<?php

class Route {
	
	private $_sections;
	private $_complete = false;
	private $_depth = 0;
	
	public function __construct(){
		
	}
	
	public function addSection(Section $section){
		$this->_sections[] = $section;
	}
	
	public function getLength($pavement = false){
		$length = 0;
		foreach($this->_sections as $section){
			if($pavement){
				$length += Routing::distance($section->start->position, $section->end->position) * $section->factor;
			} else {
				$length += Routing::distance($section->start->position, $section->end->position);
			}
		}
		return $length;
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}