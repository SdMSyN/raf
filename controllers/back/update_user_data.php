<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
    $idUser = $_POST['idUser'];
	$idUserMod = $_POST['idUserSess'];
	//echo $idUser.'--'.$idUserMod;
	
	$name=$_POST['inputname'];
	$ap=$_POST['inputap'];
	$am=$_POST['inputam'];
	$sex=$_POST['inputsex_id'];
	
	$birthday = (isset($_POST['inputbirthdate'])) ? $_POST['inputbirthdate'] : "";
	$street = (isset($_POST['inputstreet'])) ? $_POST['inputstreet'] : "";
	$num = (isset($_POST['inputnum'])) ? $_POST['inputnum'] : "";
	$town = (isset($_POST['inputtown'])) ? $_POST['inputtown'] : "";
	$city = (isset($_POST['inputcity'])) ? $_POST['inputcity'] : "";
	$state = (isset($_POST['inputstate'])) ? $_POST['inputstate'] : "";
	$cp = (isset($_POST['inputcp'])) ? $_POST['inputcp'] : "";
	$tel = (isset($_POST['inputtel'])) ? $_POST['inputtel'] : "";
	$cel = (isset($_POST['inputcel'])) ? $_POST['inputcel'] : "";
	$mail = (isset($_POST['inputmail'])) ? $_POST['inputmail'] : "";
	$facebook = (isset($_POST['inputfacebook'])) ? $_POST['inputfacebook'] : "";
	$twitter = (isset($_POST['inputtwitter'])) ? $_POST['inputtwitter'] : "";
	$ref = (isset($_POST['inputreferencia_id'])) ? $_POST['inputreferencia_id'] : "";
	
    $photo=$_FILES['inputphoto']['name'];
    $ife=$_FILES['inputife']['name'];
    $ife_rev=$_FILES['inputife_rev']['name'];
	
	$claveE = (isset($_POST['inputclave_electoral'])) ? $_POST['inputclave_electoral'] : "";
	
    $lat=(isset($_POST['inputlat'])) ? $_POST['inputlat'] : "";
    $lon=(isset($_POST['inputlon'])) ? $_POST['inputlon'] : "";

	//obtenemos clave unica
	$sqlGetKey = "SELECT clave FROM $tUserData WHERE id='$idUser' ";
	$resGetKey = $con->query($sqlGetKey);
	$rowGetKey = $resGetKey->fetch_assoc();
	$key=$rowGetKey['clave'];
	//echo $key;
	
	//validamos imagenes
	$ban1=true;$ban2=true;$ban3=true; $error='';
	if($photo != ""){
		$ext1=explode(".", $_FILES['inputphoto']['name']);
		$namePhoto = "foto_".$key.".".$ext1[1];
		//echo "--".$namePhoto;
		if ($_FILES["inputphoto"]["error"] > 0){
            $error.= "Ha ocurrido un error al subir fotografía.";
		} else {
			$limite_kb = 1000;
			if ($_FILES['inputphoto']['size'] <= $limite_kb * 1024){
				$ruta = "../".$folderImgUploads."/".$namePhoto;
				$resultado = @move_uploaded_file($_FILES["inputphoto"]["tmp_name"], $ruta);
				if ($resultado){
					$ban1=true;
				} else {
					$error .= "ocurrio un error al mover el archivo fotografía.";
					$ban1=false;
				}
			} else {
				$error .= "Excede el tamaño de $limite_kb Kilobytes (1Mb).";
				$ban1=false;
			}
		}
	}
	if($ife != ""){
		$ext1=explode(".", $_FILES['inputife']['name']);
		$nameIFE = "ife_".$key.".".$ext1[1];
		//echo "--".$nameIFE;
		if ($_FILES["inputife"]["error"] > 0){
            $error.= "Ha ocurrido un error al subir IFE.";
		} else {
			$limite_kb = 1000;
			if ($_FILES['inputife']['size'] <= $limite_kb * 1024){
				$ruta = "../".$folderImgUploads."/".$nameIFE;
				$resultado = @move_uploaded_file($_FILES["inputife"]["tmp_name"], $ruta);
				if ($resultado){
					$ban2=true;
				} else {
					$error .= "ocurrio un error al mover el archivo IFE.";
					$ban2=false;
				}
			} else {
				$error .= "Excede el tamaño de $limite_kb Kilobytes (1Mb).";
				$ban2=false;
			}
		}
	}
	if($ife_rev != ""){
		$ext1=explode(".", $_FILES['inputife_rev']['name']);
		$nameIFER = "ife_".$key.".".$ext1[1];
		//echo "--".$nameIFER;
		if ($_FILES["inputife_rev"]["error"] > 0){
            $error.= "Ha ocurrido un error al subir IFE reverso.";
		} else {
			$limite_kb = 1000;
			if ($_FILES['inputife_rev']['size'] <= $limite_kb * 1024){
				$ruta = "../".$folderImgUploads."/".$nameIFER;
				$resultado = @move_uploaded_file($_FILES["inputife_rev"]["tmp_name"], $ruta);
				if ($resultado){
					$ban3=true;
				} else {
					$error .= "ocurrio un error al mover el archivo IFE reverso.";
					$ban3=false;
				}
			} else {
				$error .= "Excede el tamaño de $limite_kb Kilobytes (1Mb).";
				$ban3=false;
			}
		}
	}
	
	if($ban1 && $ban2 && $ban3){
		//echo "he llegado a la consulta";
		$sqlInserReg = "UPDATE $tUserData SET name='$name', ap='$ap', am='$am', sex_id='$sex' ";
		if($birthday != ""){ $sqlInserReg .= ", birthdate='$birthday' "; }
		if($street != ""){ $sqlInserReg .= ", street='$street' "; }
		if($num != ""){ $sqlInserReg .= ", num='$num' "; }
		if($town != ""){ $sqlInserReg .= ", town='$town' "; }
		if($city != ""){ $sqlInserReg .= ", city='$city' "; }
		if($state != ""){ $sqlInserReg .= ", state='$state' "; }
		if($cp != ""){ $sqlInserReg .= ", cp='$cp' "; }
		if($lat != ""){ $sqlInserReg .= ", lat='$lat' "; }
		if($lon != ""){ $sqlInserReg .= ", lon='$lon' "; }
		if($tel != ""){ $sqlInserReg .= ", tel='$tel' "; }
		if($cel != ""){ $sqlInserReg .= ", cel='$cel' "; }
		if($mail != ""){ $sqlInserReg .= ", mail='$mail' "; }
		if($facebook != ""){ $sqlInserReg .= ", facebook='$facebook' "; }
		if($twitter != ""){ $sqlInserReg .= ", twitter='$twitter' "; }
		//$sqlInserReg .= ", clave"; $sqlTmp.=" ,'$key' ";
		if($photo != ""){ $sqlInserReg .= ", photo='$namePhoto' "; }
		if($ife != ""){ $sqlInserReg .= ", ife='$nameIFE' "; }
		if($ife_rev != ""){ $sqlInserReg .= ", ife_rev='$nameIFER' "; }
		if($claveE != ""){ $sqlInserReg .= ", clave_electoral='$claveE' "; }
		if($ref != ""){ $sqlInserReg .= ", referencia_id='$ref' "; }
		$sqlInserReg .= ", updated='$dateNow', updated_by='$idUserMod' ";
		$sqlInserReg .= " WHERE id='$idUser' ";
		//echo $sqlInserReg;
		if($con->query($sqlInserReg) === TRUE){
			echo "true";
		}else{
			echo "Error al añadir nuevo registro.<br>".$con->error.'<br>'.$sqlInserReg;
		}
	}else{
		echo $error;
	}
	
?>