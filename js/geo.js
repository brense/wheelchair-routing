var start_stop_btn, wpid=false, map, z, op, prev_lat, prev_long, min_speed=0, max_speed=0, min_altitude=0, max_altitude=0, distance_travelled=0, min_accuracy=150

function geoSuccess(position){
	if(position.coords.accuracy<=min_accuracy){
		if(prev_lat!=position.coords.latitude || prev_long!=position.coords.longitude){		
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