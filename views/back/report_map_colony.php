<?php
	include ('header.php');
	include('../config/variables.php');
	include('../config/conexion.php');
?>

<title><?= $tit; ?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<!-- script para google maps -->
	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<style>
	  #map-canvas {
		width: 100%;
		height: 500px;
	  }
	</style>
	
<?php
	include ('navbar.php');
	if (!isset($_SESSION['sessU'])){
		echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección</h2></div></div>';
	}else {
		$colony = $_GET['colony'];
		$userId = $_SESSION['userId'];
		// Obtenemos los puntos de la comunidad
		$points=array(); $totalLat=0; $totalLon=0; $count=0; $dataTable='';
		$sqlGetPoints = "SELECT CONCAT (name,' ',ap,' ',am) as name"
			.", CONCAT (street, ' #' ,num, ', ' ,town, ', ' ,city, ', del Estado de ' ,state, ', CP: ' ,cp) as address"
			.", lat, lon "
			." FROM $tUserData "
			." WHERE town='$colony' ";
			//echo $sqlGetPoints;
		$resGetPoints = $con->query($sqlGetPoints);
		if($resGetPoints->num_rows > 0){
			while($rowGetPoint = $resGetPoints->fetch_assoc()){
				array_push($points, array('name'=>$rowGetPoint['name'], 'address'=>$rowGetPoint['address'], 'lat'=>$rowGetPoint['lat'], 'lng'=>$rowGetPoint['lon']));
				$dataTable.='<tr><td>'.$rowGetPoint['name'].'</td><td>'.$rowGetPoint['address'].'</td><td>'.$rowGetPoint['lat'].'--'.$rowGetPoint['lon'].'</td></tr>';
				if($rowGetPoint['lat'] != "0.0000000") $totalLat+=$rowGetPoint['lat'];
				if($rowGetPoint['lon'] != "0.0000000") $totalLon+=$rowGetPoint['lon'];
				if($rowGetPoint['lon'] != "0.0000000" && $rowGetPoint['lat'] != "0.0000000") $count++;
			}
		}
		$pointsMsg = json_encode(array("Locations"=>$points));
		$totalLat = ($totalLat/$count);
		$totalLon = ($totalLon/$count);
?>

	<div class="container">
		<div class="row">
			<div id="map-canvas"></div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<table>
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Dirección</th>
							<th>Lat/Lng</th>
						</tr>
					</thead>
					<tbody><?= $dataTable; ?></tbody>
				</table>
			</div>
		</div>
	</div>

<script type="text/javascript">
    $(document).ready(function () {
		var msg=<?php echo $pointsMsg; ?>;
        var totolac = new google.maps.LatLng(<?= $totalLat; ?>, <?= $totalLon; ?>);  
		var opciones = {  
		  zoom: 14,
		  center: totolac,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var div = document.getElementById('map-canvas');  
		var map = new google.maps.Map(div, opciones); // Creamos un marcador y lo posicionamos en el mapa  
		var infoWindow = new google.maps.InfoWindow;
		/*var marcador = new google.maps.Marker({  
		  position: new google.maps.LatLng(<?= $totalLat; ?>, <?= $totalLon; ?>),
		  map: map
		});*/
		if(msg.Locations.length > 0){
			for(i=0; i<msg.Locations.length; i++){
				var location = msg.Locations[i];
				var html = "<b>"+location.name+"</b><br>"+location.address;
				var point = new google.maps.LatLng(location.lat, location.lng);
				var marker = new google.maps.Marker({
					map: map,
					position: point
				})
				bindInfoWindow(marker, map, infoWindow, html);
			}
		}
		
		function bindInfoWindow(marker, map, infoWindow, html) {
			google.maps.event.addListener(marker, 'click', function() {
				infoWindow.setContent(html);
				infoWindow.open(map, marker);
			});
		}
    });
  </script>
  
<?php
	}//fin else sesión
	include ('footer.php');
?>