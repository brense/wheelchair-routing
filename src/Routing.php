<?php

class Routing {
	
	private $_start;
	private $_end;
	private $_streets;
	private $_routes = array();
	private $_maxDepth;
	private $_refLength;
	
	public function __construct($streets){
		$this->_streets = $streets;
	}
	
	public function calculate($startAddress, $endAddress){
		// get the nearest junctions (can only calculate routes from junctions)
		$start = self::geocode($startAddress);
		$end = self::geocode($endAddress);
		$this->_start = $this->getNearestJunction($start);
		$this->_end = $this->getNearestJunction($end);
		
		// determine the max depth for the routing algorithm (higher depth is slower and can crash the application)
		$this->_refLength = self::distance($this->_start->position, $this->_end->position);
		$this->_maxDepth = round(70 / (10000 / $this->_refLength)) + 10;
		
		// create a route object and start calculating at the first junction
		$this->_routes[0] = new Route();
		$this->calculateJunction($this->_start, $this->_end, $this->_routes[0]);
		
		// remove routes that did not reach the end point
		$this->removeIncompleteRoutes();
		
		// return the nearest start and end junction
		return array('start' => $this->_start->position, 'end' => $this->_end->position);
	}
	
	public function getShortest(){
		if(count($this->_routes) > 0){
			$lengths = array();
			foreach($this->_routes as $k => $route){
				$lengths[$k] = $route->getLength();
			}
			asort($lengths);
			foreach($lengths as $k => $v){
				return $this->_routes[$k];
			}
		} else {
			// no routes found
		}
	}
	
	public function getBest(){
		if(count($this->_routes) > 0){
			$lengths = array();
			foreach($this->_routes as $k => $route){
				$lengths[$k] = $route->getLength(true);
			}
			asort($lengths);
			foreach($lengths as $k => $v){
				return $this->_routes[$k];
			}
		} else {
			// no routes found
		}
	}
	
	private function calculateJunction($junction, $endPoint, &$route, $prevJunction = null, $adjust = 0){
		$route->depth++;
		// determine the bearing from the junction to the end point
		$bearing = self::bearing($junction->position, $endPoint->position);
		if(isset($prevJunction)){
			$back = self::bearing($junction->position, $prevJunction->position);
		}
		// loop through possible options (sections connected to the junction)
		$options = array();
		foreach($junction->sections as $section){
			// get section bearing
			if($junction->position == $section->start->position){
				$sectionBearing = self::bearing($section->start->position, $section->end->position);
				$nextJunction = $section->end;
			} else {
				$sectionBearing = self::bearing($section->end->position, $section->start->position);
				$nextJunction = $section->start;
			}
			// break the loop if the end point is reached
			if($nextJunction == $endPoint){
				$route->addSection($section);
				$route->complete = true;
				break;
			} else {
				// calculate bearing difference to make sure we dont start moving in the wrong direction
				if($sectionBearing > $bearing){
					$diff = $sectionBearing - $bearing;
				} else if($sectionBearing < $bearing){
					$diff = $bearing - $sectionBearing;
				} else {
					$diff = 0;
				}
				// adjust the numbers below to narrow the "arc" for allowed connections, you should keep the arc atleast 180 degrees
				if($diff > (220 + $adjust) || $diff < (140 - $adjust)){
					if(($diff > (220 + $adjust) && $diff < (270 + $adjust)) || ($diff < (140 - $adjust) && $diff > (90 - $adjust))){
						$adjust += 60;
					} else {
						$adjust = 0;
					}
					if(!isset($back) || $sectionBearing != $back){
						$options[] = array('section' => $section, 'junction' => $nextJunction, $adjust);
					}
				}
			}
		}
		// create new routes for all the options
		foreach($options as $option){
			$newRoute = clone $route;
			$this->_routes[] = $newRoute;
			$newRoute->addSection($option['section']);
			if($newRoute->depth < $this->_maxDepth){
				$this->calculateJunction($option['junction'], $endPoint, $newRoute, $junction);
			}
		}
	}
	
	private function removeIncompleteRoutes(){
		$routes = array();
		foreach($this->_routes as $route){
			if($route->complete){
				$routes[] = $route;
			}
		}
		$this->_routes = $routes;
	}
	
	private function getNearestJunction($geocode){
		// get location and streetname from google geocode api
		$location = new LatLong($geocode->results[0]->geometry->location->lat, $geocode->results[0]->geometry->location->lng);
		foreach($geocode->results[0]->address_components as $comp){
			if($comp->types[0] == 'route'){
				$streetName = $comp->long_name;
			}
		}
		$found = array();
		// lookup junctions based on streetname if streetname is found in streetsfile
		foreach($this->_streets as $street){
			if($street->name == $streetName){
				foreach($street->sections as $section){
					$startDistance = self::distance($section->start->position, $location);
					$endDistance = self::distance($section->end->position, $location);
					if($startDistance < $endDistance){
						$found[] = $section->start;
					} else {
						$found[] = $section->end;
					}
				}
			}
		}
		// lookup all junctions if streetname is not found in streetsfile (disable this when using large streetfiles)
		if(count($found) == 0){
			foreach($this->_streets as $street){
				foreach($street->sections as $section){
					$startDistance = self::distance($section->start->position, $location);
					$endDistance = self::distance($section->end->position, $location);
					if($startDistance < $endDistance){
						$found[] = $section->start;
					} else {
						$found[] = $section->end;
					}
				}
			}
		}
		// determine the closest junction
		$nearest = $found[0];
		foreach($found as $junction){
			if(self::distance($junction->position, $location) < self::distance($nearest->position, $location)){
				$nearest = $junction;
			}
		}
		return $nearest;
	}
	
	public static function geocode($address){
		$address = str_replace(' ', '+', $address);
		return json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&sensor=false'));
	}
	
	public static function distance(LatLong $posA, LatLong $posB){
		$difference = LatLong::difference($posA, $posB);
		return sqrt(($difference['long'] * $difference['long']) + ($difference['lat'] * $difference['lat'])) * 63710;
	}
	
	public static function bearing(LatLong $posA, LatLong $posB){
		$bearingDeg = (rad2deg(atan2(sin(deg2rad($posB->lat) - deg2rad($posA->lat)) * 
		cos(deg2rad($posB->long)), cos(deg2rad($posA->long)) * sin(deg2rad($posB->long)) - 
		sin(deg2rad($posA->long)) * cos(deg2rad($posB->long)) * cos(deg2rad($posB->lat) - deg2rad($posA->lat)))) + 360) % 360;
		return $bearingDeg;
	}
	
	public function __get($property){
		return $this->{'_' . $property};
	}
	
	public function __set($property, $value){
		$this->{'_' . $property} = $value;
	}
	
}