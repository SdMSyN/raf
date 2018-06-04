<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');

	$idUser = $_POST['idUser'];
	$idUserSess = $_POST['idUserSess'];
	//echo $idUser;
	$sqlGetUserData = "SELECT * FROM $tUserData WHERE id='$idUser' ";
	$resGetUserData = $con->query($sqlGetUserData);
	$rowGetUserData = $resGetUserData->fetch_assoc();
	
		$arrName=array("Nombre", "Apellido paterno", "Apellido Materno", "Sexo", "Fecha de nacimiento", "Calle", "Número", "Colonia", "Municipio", "Estado", "Código Postal", "Teléfono", "Celular", "Correo electrónico", "Facebook", "Twitter", "Fotografía", "IFE", "IFE (reverso)",  "Clave Electoral", "Referencia");
		$arrInputName=array("name", "ap", "am", "sex_id", "birthdate", "street", "num", "town", "city", "state", "cp", "tel", "cel", "mail", "facebook", "twitter", "photo", "ife", "ife_rev", "clave_electoral", "referencia_id");
		$arrTypeInput=array("text", "text", "text", "text", "date", "text", "text", "text", "text", "text", "number", "number", "number", "email", "text", "text", "file", "file", "file", "text", "text");
		
		// obtenemos los usuarios para añadirle referencias
		$sqlGetUsers = "SELECT id, CONCAT(name,' ',ap,' ',am) as name FROM $tUserData ";
		$resGetUsers = $con->query($sqlGetUsers);
		$optUsers = '<option></option>';
		while($rowGetUser = $resGetUsers->fetch_assoc()){
			if($rowGetUserData['referencia_id'] == $rowGetUser['id'])
				$optUsers .= '<option value="'.$rowGetUser['id'].'" selected>'.$rowGetUser['name'].'</option>';
			else $optUsers .= '<option value="'.$rowGetUser['id'].'">'.$rowGetUser['name'].'</option>';
		}
		
		// obtenemos los sexos
		$sqlGetSexs = "SELECT * FROM $tSex";
		$resGetSexs = $con->query($sqlGetSexs);
		$optSexs = '<option></option>';
		while($rowGetSex = $resGetSexs->fetch_assoc()){
			if($rowGetUserData['sex_id'] == $rowGetSex['id'])
				$optSexs .= '<option value="'.$rowGetSex['id'].'" selected>'.$rowGetSex['nombre'].'</option>';
			else $optSexs .= '<option value="'.$rowGetSex['id'].'">'.$rowGetSex['nombre'].'</option>';
		}
		
		// obtenemos las colonias
		$sqlGetTowns = "SELECT DISTINCT town FROM $tUserData ";
		$resGetTowns = $con->query($sqlGetTowns);
		$optTown = '<option></option>';
		if($resGetTowns -> num_rows > 0){
			while($rowGetTown = $resGetTowns -> fetch_assoc()){
				if($rowGetTown['town']=="") continue;
				if($rowGetTown['town'] == $rowGetUserData['town'])
					$optTown .= '<option value="'.$rowGetTown['town'].'" selected>'.$rowGetTown['town'].'</option>';
				else
					$optTown .= '<option value="'.$rowGetTown['town'].'">'.$rowGetTown['town'].'</option>';
			}
		}
		
		// Recorremos la información del usuario
		$data='';
		for($i=0; $i<count($arrName); $i++){
			$data.='<div class="form-group">';
			$data.='<label for="input'.$arrInputName[$i].'" >'.$arrName[$i].'</label>';
			if( $arrInputName[$i] == "sex_id" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optSexs.'</select>';
			else if( $arrInputName[$i] == "town" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optTown.'</select>';
			else if( $arrInputName[$i] == "referencia_id" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optUsers.'</select>';
			else $data.='<input type="'.$arrTypeInput[$i].'" class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'" value="'.$rowGetUserData[$arrInputName[$i]].'" >';
			$data.='</div>';
		}
		$data.='<input type="hidden" name="inputlat" id="inputlat" >';
		$data.='<input type="hidden" name="inputlon" id="inputlon" >';
		$data.='<div class="form-group"><a href="#" target="_blank" id="viewMap2">Ver Mapa</a></div>';
		$data.='<div id="map-canvas2"></div>';
		$data .= '<input type="text" name="idUser" value="'.$idUser.'">';
		$data .= '<input type="text" name="idUserSess" value="'.$idUserSess.'">';
	echo $data;
	
?>