<?php

	if(isset($_SESSION['sessU'])  AND $_SESSION['sessU'] == "true"){
		$cadMenuNavbar='';
		if($_SESSION['perfil'] == "1"){//administrador
			//$cadMenuNavbar .= '<li><a href="../views/transportista_form_sel_ruta.php">Ver rutas</a></li>';
			$cadMenuNavbar .= '<li><a href="report_localization.php">Reportes de localización</a></li>';
		} else{
			$cadMenuNavbar .= '¿Cómo llegaste hasta acá?';
		}
		echo $cadMenuNavbar;
	}
	
?>