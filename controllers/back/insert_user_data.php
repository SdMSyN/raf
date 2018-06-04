<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
    $idUser = $_POST['inputIdUser'];
	
	$name=$_POST['inputname'];
	$ap=$_POST['inputap'];
	$am=$_POST['inputam'];
	$sex=$_POST['inputsex_id'];
	
	$birthday = (isset($_POST['inputbirthday'])) ? $_POST['inputbirthday'] : "";
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
	
	//echo $idUser.'--'.$lat.'--'.$lon.'--'.$photo.'--'.$name.' '.$ap.' '.$am.'--'.$ife.'--'.$ife_rev;

	
	//generamos clave unica
	$sqlGetCountUsers = "SELECT id FROM $tUserData ";
	$resGetCountUsers = $con->query($sqlGetCountUsers);
	$countUsers = $resGetCountUsers->num_rows;
	$key=$countUsers.$name{0}.$name{1}.$ap{0}.$ap{1}.$am{0}.$am{1};
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
		$sqlInserReg = "INSERT INTO $tUserData (name, ap, am, sex_id ";
		$sqlTmp = " '$name', '$ap', '$am', '$sex' ";
		if($birthday != ""){ $sqlInserReg .= ", birthdate"; $sqlTmp .=" ,'$birthday' "; }
		if($street != ""){ $sqlInserReg .= ", street"; $sqlTmp .=" ,'$street' "; }
		if($num != ""){ $sqlInserReg .= ", num"; $sqlTmp .=" ,'$num' "; }
		if($town != ""){ $sqlInserReg .= ", town"; $sqlTmp .=" ,'$town' "; }
		if($city != ""){ $sqlInserReg .= ", city"; $sqlTmp .=" ,'$city' "; }
		if($state != ""){ $sqlInserReg .= ", state"; $sqlTmp .=" ,'$state' "; }
		if($cp != ""){ $sqlInserReg .= ", cp"; $sqlTmp .=" ,'$cp' "; }
		if($lat != ""){ $sqlInserReg .= ", lat"; $sqlTmp .=" ,'$lat' "; }
		if($lon != ""){ $sqlInserReg .= ", lon"; $sqlTmp .=" ,'$lon' "; }
		if($tel != ""){ $sqlInserReg .= ", tel"; $sqlTmp .=" ,'$tel' "; }
		if($cel != ""){ $sqlInserReg .= ", cel"; $sqlTmp .=" ,'$cel' "; }
		if($mail != ""){ $sqlInserReg .= ", mail"; $sqlTmp .=" ,'$mail' "; }
		if($facebook != ""){ $sqlInserReg .= ", facebook"; $sqlTmp .=" ,'$facebook' "; }
		if($twitter != ""){ $sqlInserReg .= ", twitter"; $sqlTmp .=" ,'$twitter' "; }
		$sqlInserReg .= ", clave"; $sqlTmp.=" ,'$key' ";
		if($photo != ""){ $sqlInserReg .= ", photo"; $sqlTmp .=" ,'$namePhoto' "; }
		if($ife != ""){ $sqlInserReg .= ", ife"; $sqlTmp .=" ,'$nameIFE' "; }
		if($ife_rev != ""){ $sqlInserReg .= ", ife_rev"; $sqlTmp .=" ,'$nameIFER' "; }
		if($claveE != ""){ $sqlInserReg .= ", clave_electoral"; $sqlTmp .=" ,'$claveE' "; }
		if($ref != ""){ $sqlInserReg .= ", referencia_id"; $sqlTmp .=" ,'$ref' "; }
		$sqlInserReg .= ", created, updated, created_by, updated_by";
		$sqlTmp .= ", '$dateNow', '$dateNow', '$idUser', '$idUser' ";
		
		$sqlInsertRegFinal = $sqlInserReg.") VALUES (".$sqlTmp.")";
		if($con->query($sqlInsertRegFinal) === TRUE){
			echo "true";
		}else{
			echo "Error al añadir nuevo registro.<br>".$con->error.'<br>'.$sqlInsertRegFinal;
		}
	}else{
		echo $error;
	}
?>