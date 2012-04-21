var start_stop_btn, wpid=false, map, z, op, prev_lat, prev_long, min_speed=0, max_speed=0, min_altitude=0, max_altitude=0, distance_travelled=0, min_accuracy=150

function geoSuccess(position){
	if(position.coords.accuracy<=min_accuracy){
		if(prev_lat!=position.coords.latitude || prev_long!=position.coords.longitude){
			if(position.coords.speed>max_speed){
				max_speed=position.coords.speed;
			} else if(position.coords.speed<min_speed){
				min_speed=position.coords.speed;
			}
			if(position.coords.altitude>max_altitude){
				max_altitude=position.coords.altitude;
			} else if(position.coords.altitude<min_altitude){
				min_altitude=position.coords.altitude;
			}			
			prev_lat=position.coords.latitude;
			prev_long=position.coords.longitude;
			
			geoShow(position.coords.latitude, position.coords.longitude);
		}
	}
}

function geoError(error){}

function getPos(){
	if(!!navigator.geolocation){
		wpid=navigator.geolocation.watchPosition(geoSuccess, geoError, {enableHighAccuracy:true, maximumAge:30000, timeout:27000});
	}
}

function geoShow(y, x){	
	if(markersArray){
		for(i in markersArray){
			markersArray[i].setMap(null);
		}
		markersArray.length = 0;
	}
	markersArray.length = 0;
	var marker = new google.maps.Marker({
        position: new google.maps.LatLng(y, x), 
        map: map,
        title:"Uw locatie"
    });
	markersArray.push(marker);
	map.setCenter(new google.maps.LatLng(y, x));
}

function initGeo(){
	if(wpid){
		navigator.geolocation.clearWatch(wpid);
		wpid=false;
	}
	else {
		getPos();
	}
}

window.onload=initGeo;