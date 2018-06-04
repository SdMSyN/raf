<?php
    include ('../config/conexion.php');
    include ('../config/variables.php');
    
    if($_GET['action'] == 'listar'){
        $sqlGetUsers = "SELECT id, photo, name, ap, am, street, town, city, facebook, referencia_id FROM $tUserData  ";
        //$datos=array();
        //
        // Ordenar por
		$name = $_POST['nombre'];
		$ap = $_POST['ap'];
		$street = $_POST['calle'];
		$town = $_POST['colonia'];
		$sqlGetUsers .= ($name != '' || $street != '' || $town != '' || $ap != '' ) ? " WHERE 1=1 " : "";
		$sqlGetUsers .= ($name != '') ? "AND name LIKE '%$name%' " : "";
		$sqlGetUsers .= ($ap != '') ? "AND ap LIKE '%$ap%' " : "";
		$sqlGetUsers .= ($street != '') ? " AND street LIKE '%$street%' " : "";
		$sqlGetUsers .= ($town != '') ? "AND  town LIKE '%$town%' " : "";
		//$sqlGetUsers .= " 1=1 ";
        //if($est >= 0) $sqlGetCateories .= " WHERE activo='$est' ";
        
        //Ordenar ASC y DESC
		$vorder = (isset($_POST['orderby'])) ? $_POST['orderby'] : "";
		if($vorder != ''){
			$sqlGetUsers .= " ORDER BY ".$vorder;
		}
		
		if($name == '' && $street == '' && $town == '' && $ap == '') $sqlGetUsers .= " LIMIT 50 ";
		else $sqlGetUsers .= " ";
		
        echo $sqlGetUsers;
        //Ejecutamos query
        $resGetUsers = $con->query($sqlGetUsers);
        $datos = '';
        //$datos .= '<tr><td colspan="7">'.$sqlGetCateories.'</td></tr>';
		$i=0;
        while($rowGetUser = $resGetUsers->fetch_assoc()){
			$i++;
            $datos .= '<tr>';
            $idUser = $rowGetUser['id'];
            $idUserRef = $rowGetUser['referencia_id'];
			$datos .= '<td>'.$i.'</td>';
            $datos .= '<td><img src="../'.$folderImgUploads."/".$rowGetUser['photo'].'" width="50%"></td>';
            $datos .= '<td>'.$rowGetUser['name'].'</td>';
            $datos .= '<td>'.$rowGetUser['ap'].'</td>';
            $datos .= '<td>'.$rowGetUser['am'].'</td>';
            $datos .= '<td>'.$rowGetUser['street'].'</td>';
            $datos .= '<td>'.$rowGetUser['town'].'</td>';
            $datos .= '<td>'.$rowGetUser['city'].'</td>';
			//obtenemos la referencia padre
			if($rowGetUser['referencia_id'] != NULL){
				$sqlGetRef = "SELECT CONCAT (name,' ',ap,' ',am) as ref FROM $tUserData WHERE id='$idUserRef' ";
				$resGetRef = $con->query($sqlGetRef);
				$rowGetRef = $resGetRef->fetch_assoc();
				$datos .= '<td>'.$rowGetRef['ref'].'</td>';
			}else{
				$datos.='<td></td>';
			}
            $datos .= '<td>'.$rowGetUser['facebook'].'</td>';
            $datos .= '<td></td>';
            $datos .= '<td><button type="button" class="edit btn" data-toggle="modal" data-target="#MyModalModReg" data-whatever="'.$rowGetUser['id'].'" data-id="'.$rowGetUser['id'].'" ><span class="glyphicon glyphicon-edit"></span></button></td>';
            $datos .= '<td></td>';
			
            $datos .= '</tr>';
        }
        echo $datos;
    }
    
?>
