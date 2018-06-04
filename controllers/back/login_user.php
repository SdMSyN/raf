<?php
    session_start();
    include ('../config/conexion.php');
    $user = $_POST['inputUser'];
    $pass = $_POST['inputPass'];
    
    $sqlGetUser="SELECT $tUser.id as id, $tUser.perfil_id as perfil_id, $tUser.user_data_id as user_data_id, $tUserData.sex_id as sex_id, $tUserData.name as name, $tUserData.ap as ap, $tUserData.am as am  FROM $tUser  INNER JOIN $tUserData ON $tUser.user_data_id=$tUserData.id WHERE $tUser.user='$user' AND $tUser.pass='$pass' ";
    $resGetUser=$con->query($sqlGetUser);
    if($resGetUser->num_rows > 0){
        $rowGetUser=$resGetUser->fetch_assoc();
       
        $_SESSION['sessU'] = true;
		$_SESSION['userId'] = $rowGetUser['id'];
		$_SESSION['userName'] = $rowGetUser['ap']." ".$rowGetUser['am']." ".$rowGetUser['name'];
        $_SESSION['perfil'] = $rowGetUser['perfil_id'];
        $_SESSION['sex'] = $rowGetUser['sex_id'];
        
        echo $_SESSION['perfil'];
        
    }
    else{
        $_SESSION['sessU']=false;
        //echo "Error en la consulta<br>".$con->error;
        echo "Usuario incorrecto";
    }
    
?>