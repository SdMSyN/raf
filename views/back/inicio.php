<?php
	include ('header.php');
	include('../config/variables.php');
	include('../config/conexion.php');
?>

<title><?= $tit; ?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />

<?php
	include ('navbar.php');
	if (!isset($_SESSION['sessU'])){
		echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección</h2></div></div>';
	}else {
?>

	<div class="container">
		<!-- vista de inicio, carga de formulario -->
		<div class="row text-center">
			<div class="col-sm-12">
				<img src="../assets/img/ecologia.png" class="img-round"> 
			</div>
			<div class="col-md-12 titulo">
				BIENVENIDO AL SISTEMA ADMINISTRATIVO DE <br>
				MECA TECNOLOGÍAS S. DE R.L. DE C.V. 
			</div>
		</div>
	</div>

<?php
	}//fin else sesión
	include ('footer.php');
?>