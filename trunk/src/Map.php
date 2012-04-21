<?php

class Map {
	
	private $_junctions;
	private $_streets;
	private $_options = '';
	private $_position;
	private $_div;
	
	public function __construct(LatLong $pos, $div){
		$this->_position = $pos;
		$this->_div = $div;
	}
	
	public function draw(){
		$html = '<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	var map;
	var sections;
	var markersArray;
	function initialize(){
		var mapOptions = {
			zoom: 17,
			center: new google.maps.LatLng(' . $this->_position->long . ', ' . $this->_position->lat . '),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		map = new google.maps.Map(document.getElementById(\'' . $this->_div . '\'), mapOptions);
		' . $this->_options . "\n" . '
	}
	
	google.maps.event.addDomListener(window, \'load\', initialize);
</script>' . "\n";
		return $html;
	}
	
	public function loadStreets($file){
		$junctions = array();
		$streets = array();
		include($file);
		$this->_junctions = $junctions;
		$this->_streets = $streets;
	}
	
	public function drawJunctions(){
		foreach($this->_junctions as $junction){
			$this->_options .= '
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(' . $junction->position->lat . ', ' . $junction->position->long . '), 
			map: map,
			title:"Junction"
		});';
		}
	}
	
	public function drawPoint(LatLong $point){
		$this->_options .= '
		var marker = new google.maps.Marker({
			position: new google.maps.LatLng(' . $point->lat . ', ' . $point->long . '), 
			map: map,
			title:"Point"
		});';
	}
	
	public function drawSections(){
		foreach($this->_streets as $street){
			foreach($street->sections as $section){
				$this->_options .= '
			var streets = [new google.maps.LatLng(' . $section->start->position->lat . ', ' . $section->start->position->long . '), new google.maps.LatLng(' . $section->end->position->lat . ', ' . $section->end->position->long . ')];
				var streetMap = new google.maps.Polyline({path: streets, strokeColor: "#ff0000", strokeOpacity: 0.2, strokeWeight: 4});
				streetMap.setMap(map);';
			}
		}
	}
	
	public function drawRoute(Route $route){
		foreach($route->sections as $section){
			if($section->factor > 1){
				$color = '#ff0000';
			} else {
				$color = '#0000ff';
			}
			$this->_options .= '
		var streets = [new google.maps.LatLng(' . $section->start->position->lat . ', ' . $section->start->position->long . '), new google.maps.LatLng(' . $section->end->position->lat . ', ' . $section->end->position->long . ')];
			var streetMap = new google.maps.Polyline({path: streets, strokeColor: "' . $color . '", strokeOpacity: 0.7, strokeWeight: 4});
			streetMap.setMap(map);';
		}
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}