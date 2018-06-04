<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
	
	$id=$_POST['idUser'];
	$name=$_POST['inputName'];
	$ap=$_POST['inputAp'];
	$am=$_POST['inputAm'];
	$clave=$_POST['inputClave'];
	$col=$_POST['inputCol'];
	$reco=$_POST['inputReco'];
	$apo=$_POST['inputApoyo'];
	
	$sqlUpdData = "UPDATE $tFirm SET name='$name', ap='$ap', am='$am', clave='$clave', colony_id='$col', recolector_id='$reco', apoyo_id='$apo' WHERE id='$id'  ";
	if($con->query($sqlUpdData) === TRUE){
		echo "true";
	}else{
		echo "Error al actualizar registro.<br>".$con->error;
	}


?>