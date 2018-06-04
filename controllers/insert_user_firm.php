<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
	
	$name=$_POST['inputName'];
	$ap=$_POST['inputAp'];
	$am=$_POST['inputAm'];
	$clave=$_POST['inputClave'];
	$col=$_POST['inputCol'];
	$reco=$_POST['inputReco'];
	$apo=$_POST['inputApoyo'];
	
	$sqlGetClave = "SELECT id FROM $tFirm WHERE clave='$clave' OR (name='$name' AND ap='$ap' AND am='$am' ) ";
	$resGetClave = $con->query($sqlGetClave);
	if($resGetClave->num_rows > 0){
		echo "Error; el registro ya se encuentra dado de alta";
	}else{
		$sqlInsertReg = "INSERT INTO $tFirm (name, ap, am, clave, colony_id, recolector_id, apoyo_id) VALUES ('$name', '$ap', '$am', '$clave', '$col', '$reco', '$apo') ";
		if($con->query($sqlInsertReg) === TRUE){
			echo "true";
		}else{
			echo "Error al añadir nuevo registro de firma.<br>".$con->error.'<br>'.$sqlInsertReg;
		}
	}

?>