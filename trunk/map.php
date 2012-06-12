<?php

$head = '<script type="text/javascript">
var map;
var markersArray = [];
var route;
var currentSection = 0;
var lastDistance = 0;
</script>';

// create the map and load the streetsfile
$map = new Map(new LatLong(4.4758699, 51.9067910), 'map_canvas');
$map->loadStreets('streetsfile.php');

// uncomment these to show junctions and sections on the map
//$map->drawJunctions();
//$map->drawSections();

// add javascript to the head of the page
$head .= $map->draw();

if(!isset($_GET['type'])){
	$type = 'best';
} else {
	$type = $_GET['type'];
}

// make the post request to route.php and draw the route sections on the map
if(isset($_GET['from']) && isset($_GET['to'])){
	$via = '';
	if(isset($_GET['via'])){
		$via = ' \'via[]\': ["' . implode('", "', $_GET['via']) . '"],';
	}
$head .= '<script type="text/javascript">
$.post(\'route.php\', {start: "' . $_GET['from'] . '",' . $via . ' end: "' . $_GET['to'] . '", type: "' . $type . '"}, function(data){
	route = data;
	sections = route.sections;
	/*
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(route.start.lat, route.start.long),
		map: map,
		title:"Point"
	});
	*/
	var n = 0;
	$.each(route.sections, function(i, section){
		var streets = [new google.maps.LatLng(section.start.lat, section.start.long), new google.maps.LatLng(section.end.lat, section.end.long)];
		var streetMap = new google.maps.Polyline({path: streets, strokeColor: section.color, strokeOpacity: 0.7, strokeWeight: 4});
		streetMap.setMap(map);
		if(route.sections[(n+1)] !== undefined && n == 0){
			var next = route.sections[(n+1)];
			//$(\'#directions\').append(\'<p>Na \'+section.length+\' meter \'+next.direction+\', \'+next.streetname+\', ondergrond: \'+next.pavement+\'</p>\');
		}
		
		n++;
	});
	var d = directions(route.sections, route.sections[0], 0, 0, 0);
	/*
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(route.end.lat, route.end.long), 
		map: map,
		title:"Point"
	});
	*/
	geoShow(51.907369, 4.477798);
	$(\'#calculating\').hide();
}, \'json\');

function geoShow(y, x){
	
	if(route !== undefined){
		/*
		console.log(currentSection);
		$.each(route.sections, function(i, section){
			if((section.start.long > x && section.end.long < x) || (section.start.long < x && section.end.long > x)){
				if((section.start.lat > y && section.end.lat < y) || (section.start.lat < y && section.end.lat > y)){
					if(route.sections[(i+1)] !== undefined){*/
						var d = directions(route.sections, route.sections[currentSection], x, y, currentSection);
					/*}
				}
			}
		});*/
		if(lastDistance > 0 && lastDistance < 8 && lastDistance < d){
			currentSection++;
			lastDistance = 0;
		} else {
			lastDistance = d;
		}
	}
	
	if(markersArray){
		for(i in markersArray){
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}
	markersArray.length = 0;
	var marker = new google.maps.Marker({
        position: new google.maps.LatLng(y, x),
		icon: \'img/marker.png\',
        map: map,
        title:"Uw locatie"
    });
	markersArray.push(marker);
	map.setCenter(new google.maps.LatLng(y, x));
}

function distance(posA, posB){
	if(posA.long > posB.long){
		var longdiff = posA.long - posB.long;
	} else {
		var longdiff = posB.long - posA.long;
	}
	if(posA.lat > posB.lat){
		var latdiff = posA.lat - posB.lat;
	} else {
		var latdiff = posB.lat - posA.lat;
	}
	var diff = { long: longdiff, lat: latdiff };
	return Math.sqrt((diff.long * diff.long) + (diff.lat * diff.lat)) * 63710;
}

function directions(sections, section, x, y, i){
	$(\'#directions\').html(\'\');
	switch(sections[(i+1)].direction){
		case \'Rechtdoor\':
			$(\'#directions\').append(\'<img src="img/rechtdoor.png" alt="rechtdoor" />\');
			break;
		case \'Linksaf\':
			$(\'#directions\').append(\'<img src="img/links.png" alt="links af" />\');
			break;
		case \'Rechtsaf\':
			$(\'#directions\').append(\'<img src="img/rechts.png" alt="rechts af" />\');
			break;
	}
	switch(sections[(i+1)].type){
		case \'Tegels\':
			color = \'green\';
			break;
		case \'Keien\':
			color = \'yellow\';
			break;
		default:
			color = \'green\';
			break;
	}
	var dist = sections[i].length;
	if(x > 0 && y > 0){
		if((sections[(i+1)].start.long == section.start.long && sections[(i+1)].start.lat == section.start.lat) || (sections[(i+1)].end.long == section.start.long && sections[(i+1)].end.lat == section.start.lat)){
				dist = Math.round(distance({ long: x, lat: y}, section.start));
		} else if((sections[(i+1)].start.long == section.end.long && sections[(i+1)].start.lat == section.end.lat) || (sections[(i+1)].end.long == section.end.long && sections[(i+1)].end.lat == section.end.lat)){
			dist = Math.round(distance({ long: x, lat: y}, section.end));
		}
	}
	$(\'#directions\').append(\'<span class="when">Na \'+dist+\' meter :</span><br />\');
	$(\'#directions\').append(\'<h2 class="green">\'+sections[(i+1)].streetname+\'</h2>\');
	$(\'#directions\').append(\'<span class="direction">\'+sections[(i+1)].direction+\'</span><br />\');
	$(\'#directions\').append(\'<span class="ondergrond">ondergrond : </span><span class="ondergrond \'+color+\'">\'+sections[(i+1)].pavement+\'</span>\');
	return dist;
}

</script>
<script type="text/javascript" src="js/geo.js"></script>
';
}
?>
<div id="map_canvas"></div>
<div id="directions"></div>
<div id="calculating">Route aan het berekenen...</div>