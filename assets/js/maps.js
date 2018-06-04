function drawMap(msg){
	var directionsDisplay; //declaramos variable a pintar
	var directionsService = new google.maps.DirectionsService();
	var meca = new google.maps.LatLng(19.311316, -98.198715); //Punto A
	var home = new google.maps.LatLng(19.327671, -98.241822); //Punto B
	var waypts=[]; //way points
	directionsDisplay = new google.maps.DirectionsRenderer({suppressMarkers: true}); // declaramos trazo de ruta
	var map = new google.maps.Map(document.getElementById("map-canvas"), {
		center: new google.maps.LatLng(19.3144519, -98.1781525),
		zoom: 10,
		mapTypeId: 'roadmap'
	});
	directionsDisplay.setMap(map); //Seteamos el trazo de ruta
	var infoWindow = new google.maps.InfoWindow; //Cuadro de información
	if (msg.Locations.length > 0) {
		for (i=0; i<msg.Locations.length; i++) {
			var location = msg.Locations[i];
			var html = "<b>" + location.name + "</b> <br/>" + location.address; //html que contiene la info
			var point = new google.maps.LatLng(location.lat, location.lng); //coordenada [x,y]
			var marker = new google.maps.Marker({
				map: map,
				position: point
			});
			waypts.push({location: point, stopover: true});
			bindInfoWindow(marker, map, infoWindow, html);
		}
	}
	var directionsService = new google.maps.DirectionsService(); //Gestionamos ruta
	var directionsRequest = {
		origin: meca,
		destination: meca,
		waypoints: waypts,
		optimizeWaypoints: true,
		travelMode: google.maps.DirectionsTravelMode.DRIVING, //Tipo de traslado
		unitSystem: google.maps.UnitSystem.METRIC
	};
	directionsService.route(directionsRequest, function(response, status) {
		if (status == google.maps.DirectionsStatus.OK) {
			directionsDisplay.setDirections(response);
			var route = response.routes[0];
		}
	});
}

function bindInfoWindow(marker, map, infoWindow, html) {
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
}