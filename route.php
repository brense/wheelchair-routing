<?php

ob_start();
$file = str_replace('/mounted-storage', '' ,str_replace('\\', '/', str_replace('route.php', '', __FILE__)));
$uri = str_replace($file, '', array_shift(explode('?', str_replace('\\', '/', substr($_SERVER['DOCUMENT_ROOT'], 0, -1) . $_SERVER['REQUEST_URI']))));

include('src/Application.php');
$app = new Application($uri);

?>
<?php
// create the map and load the streetsfile
$map = new Map(new LatLong(4.4758699, 51.9067910), 'map_canvas');
$map->loadStreets('streetsfile.php');

// calculate possible routes from start to end point
$routing = new Routing($map->streets);
$c = $routing->calculate($_POST['start'], $_POST['end']);

// get the shortest of best route
switch($_POST['type']){
	case 'shortest':$route = $routing->getShortest();break;
	case 'best':default:$route = $routing->getBest();break;
}
ob_end_clean();

// create the route array
$arr = array();
$memory = $c['start'];
$mb = 0;
$arr['start']['lat'] = $c['start']->lat;
$arr['start']['long'] = $c['start']->long;
$arr['end']['lat'] = $c['end']->lat;
$arr['end']['long'] = $c['end']->long;
foreach($route->sections as $section){
	// section parameters
	$s['start']['long'] = $section->start->position->long;
	$s['start']['lat'] = $section->start->position->lat;
	$s['end']['long'] = $section->end->position->long;
	$s['end']['lat'] = $section->end->position->lat;
	$s['length'] = Routing::distance($section->start->position, $section->end->position);;
	$s['pavement'] = $section->pavement;
	
	// section color
	if($section->factor > 1){
		$s['color'] = '#ff0000';
	} else {
		$s['color'] = '#0000ff';
	}
	
	// bearing and direction
	if($memory == $section->start->position){
		$a = $section->start->position;
		$b = $section->end->position;
	} else if($memory == $section->end->position) {
		$a = $section->end->position;
		$b = $section->start->position;
	}
	$memory = $b;
	$bearing = round(Routing::bearing($a, $b));
	if($mb == 0){
		$mb = $bearing;
		$s['direction'] = 'Rechtdoor';
	} else {
		if($mb > $bearing + 5){
			if($mb - $bearing > 180){
				$s['direction'] = 'Linksaf';
			} else {
				$s['direction'] = 'Rechtsaf';
			}
		} else if($bearing > $mb + 5) {
			if($bearing - $mb > 180){
				$s['direction'] = 'Rechtsaf';
			} else {
				$s['direction'] = 'Linksaf';
			}
		} else {
			$s['direction'] = 'Rechtdoor';
		}
		$mb = $bearing;
	}
	
	$arr['sections'][] = $s;
}
$arr['length'] = $route->getLength();

echo json_encode($arr);
exit;