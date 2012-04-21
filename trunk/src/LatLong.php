<?php

class LatLong {
	
	private $_long;
	private $_lat;
	
	public function __construct($lat, $long){
		$this->_long = $long;
		$this->_lat = $lat;
	}
	
	public static function difference(LatLong $posA, LatLong $posB){
		if($posA->long > $posB->long){
			$longdiff = $posA->long - $posB->long;
		} else {
			$longdiff = $posB->long - $posA->long;
		}
		if($posA->lat > $posB->lat){
			$latdiff = $posA->lat - $posB->lat;
		} else {
			$latdiff = $posB->lat - $posA->lat;
		}
		return array('long' => $longdiff, 'lat' => $latdiff);
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}