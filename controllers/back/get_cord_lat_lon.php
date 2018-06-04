<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
	
	$street=$_POST['street'];
	$num=$_POST['num'];
	$town=$_POST['town'];
	$city=$_POST['city'];
	$state=$_POST['state'];

	//echo $street.'--'.$num.'--'.$town.'--'.$city.'--'.$state;
	//$direccion_google = ‘Calle, Población, Provincia / Estado, País’;
	$direccion_google = $street.', '.$town.', '.$city.', '.$state;
	$resultado = file_get_contents(sprintf('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyDJW6W8V9BFz5LmA4e5m4M-Tv33dbr3S6U&sensor=false&address=%s', urlencode($direccion_google)));
	$resultado = json_decode($resultado, TRUE);

	$lat = $resultado['results'][0]['geometry']['location']['lat'];
	$lng = $resultado['results'][0]['geometry']['location']['lng'];
	
	//print_r($resultado);
	echo json_encode(array("error"=>0, "Lat"=>$lat, "Lon" =>$lng));
?>