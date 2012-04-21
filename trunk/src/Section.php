<?php

class Section {
	
	private $_start;
	private $_end;
	private $_pavement;
	private $_factor;
	
	public function __construct(Junction $start, Junction $end, $pavement = null){
		$this->_start = $start;
		$this->_end = $end;
		$this->_start->addSection($this);
		$this->_end->addSection($this);
		$this->_pavement = $pavement;
		switch($this->_pavement){
			case 'Tegels':
				$this->_factor = 0.9;
				break;
			case 'Keien':
				$this->_factor = 1.4;
				break;
			case 'Slechte kruising':
				$this->_factor = 13;
				break;
			default:
				$this->_factor = 1;
				break;
		}
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}