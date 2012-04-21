<?php
// create the map and load the streetsfile
$map = new Map(new LatLong(4.4758699, 51.9067910), 'map_canvas');
$map->loadStreets('streetsfile.php');

// uncomment these to show junctions and sections on the map
//$map->drawJunctions();
//$map->drawSections();

// add javascript to the head of the page
$head = $map->draw();

if(!isset($_GET['type'])){
	$type = 'best';
} else {
	$type = $_GET['type'];
}

// make the post request to route.php and draw the route sections on the map
if(isset($_GET['from']) && isset($_GET['to'])){
$head .= '<script type="text/javascript">
$.post(\'route.php\', {start: "' . $_GET['from'] . '", end: "' . $_GET['to'] . '", type: "' . $type . '"}, function(route){
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(route.start.lat, route.start.long), 
		map: map,
		title:"Point"
	});
	$.each(route.sections, function(i, section){
		var streets = [new google.maps.LatLng(section.start.lat, section.start.long), new google.maps.LatLng(section.end.lat, section.end.long)];
		var streetMap = new google.maps.Polyline({path: streets, strokeColor: section.color, strokeOpacity: 0.7, strokeWeight: 4});
		streetMap.setMap(map);
	});
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(route.end.lat, route.end.long), 
		map: map,
		title:"Point"
	});
	$(\'#calculating\').hide();
}, \'json\');
</script>
<script type="text/javascript" src="js/geo.js"></script>
';
}
?>
<div id="map_canvas"></div>
<div id="calculating">Route aan het berekenen...</div>