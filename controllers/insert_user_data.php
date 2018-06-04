<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
	
	$name=$_POST['inputName'];
	$ap=$_POST['inputAp'];
	$am=$_POST['inputAm'];
	$col=$_POST['inputCol'];
	

		$sqlInsertReg = "INSERT INTO $tReco (name, ap, am, colony) VALUES ('$name', '$ap', '$am', '$col') ";
		if($con->query($sqlInsertReg) === TRUE){
			echo "true";
		}else{
			echo "Error al añadir nuevo registro.<br>".$con->error.'<br>'.$sqlInsertReg;
		}

?>