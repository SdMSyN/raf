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
		
		$userId = $_SESSION['userId'];
		// Obtenemos comunidades
		$sqlGetColony = "SELECT DISTINCT town, town as colony FROM $tUserData WHERE city='Totolac' " ;
		$resGetColony = $con->query($sqlGetColony);
		$dataColony='';
		//variables totales
		$total=0; $totalF=0; $totalM=0;
		while($rowGetColony = $resGetColony->fetch_assoc()){
			//obtenemos el número de registrados en la colonia
			$town=$rowGetColony['colony'];
			$sqlGetCountTown = "SELECT COUNT(town) as countTown FROM $tUserData WHERE town='$town'  ";
			$resGetCountTown = $con->query($sqlGetCountTown);
			$rowGetCountTown = $resGetCountTown->fetch_assoc();
			$total+=$rowGetCountTown['countTown'];
				//obtenemos las mujeres de la colonia
				$sqlGetF="SELECT COUNT(sex_id) as sexF FROM $tUserData WHERE town='$town' AND sex_id='1' ";
				$resGetF = $con->query($sqlGetF);
				$rowGetF = $resGetF->fetch_assoc();
				$totalF+=$rowGetF['sexF'];
				//obtenemos los hombres de la colonia
				$sqlGetM="SELECT COUNT(sex_id) as sexM FROM $tUserData WHERE town='$town' AND sex_id='2' ";
				$resGetM = $con->query($sqlGetM);
				$rowGetM = $resGetM->fetch_assoc();
				$totalM+=$rowGetM['sexM'];
			$dataColony.='<tr><td>'.$rowGetColony['town'].'</td>'
				.'<td>'.$rowGetCountTown['countTown'].'</td>'
				.'<td>'.$rowGetF['sexF'].'</td>'
				.'<td>'.$rowGetM['sexM'].'</td>'
				.'<td><a href="report_map_colony.php?colony='.$rowGetColony['town'].' ">Ver mapa<span class=""></span></a></td>'
				.'</tr>';
		}
		$dataColony.='<tr><td><b>Totales:</b></td><td>'.$total.'</td><td><span class="glyphicon glyphicons_004_girl" ></span>'.$totalF.'</td><td><span class="glyphicons_003_user"></span>'.$totalM.'</td></tr>';
		//echo $dataColony;
		
?>

	<div class="container">
		<div class="row">
			<!--<div class="col-sm-12">
			   <form id="frm_filtro" method="post" action="" class="form-inline">
					<div class="form-group">
						<label>Nombre</label>
						<input type="text" class="form-control" name="nombre" id="nombre">
					</div>
					<div class="form-group">
						<label>Calle</label>
						<select id="calle" name="calle" class="form-control">
							<?= $optStreets; ?>
						</select>
					</div>
					<div class="form-group">
						<label>Colonia</label>
						<select id="calle" name="calle" class="form-control">
							<?= $optTown; ?>
						</select>
					</div>
				   <button type="button" id="btnfiltrar" class="btn btn-success">Filtrar</button>
				   <a href="javascript:;" id="btncancel" class="btn btn-default">Todos</a>
				</form>
			</div>
			-->
		</div>
		<div class="table-responsive">
			<table class="table table-striped" id="data">
				<thead>
					<th><span title="name">Colonia</span></th>
					<th><span title="ap">Número</span></th>
					<th><span title="">Mujeres</span></th>
					<th><span title="ap">Hombres</span></th>
					<th><span title="ap">Ver en mapa</span></th>
				</thead>
				<tbody><?= $dataColony; ?></tbody>
			</table>
		</div>
	</div>

<script type="text/javascript">
    $(document).ready(function () {

    });
  </script>
  
<?php
	}//fin else sesión
	include ('footer.php');
?>