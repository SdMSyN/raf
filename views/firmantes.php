<?php
	include ('header.php');
	include('../config/variables.php');
	include('../config/conexion.php');
?>

<title><?=$tit;?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />

<?php
	include ('navbar.php');
?>


<?php 
	
	$sqlGetFirm = "SELECT id, name, ap, am, clave, "
		."(SELECT name FROM $tCol WHERE id=$tFirm.colony_id) as colony, "
		."(SELECT CONCAT(name,' ',ap,' ',am) FROM $tReco WHERE id=$tFirm.recolector_id) as recolector, "
		."(SELECT name FROM $tApo WHERE id=$tFirm.apoyo_id) as apoyo "
		."FROM $tFirm ";
	$resGetFirm = $con->query($sqlGetFirm);
	$cadFirm = '';
	$cadData = '';
		$interval = $dateNow->diff($dateFinish);
		$cadData .= '<br>Hoy es '.$dateNow->format("Y-m-d").' y faltan '.$interval->format('%a%').' días para la fecha límite.';
	if($resGetFirm -> num_rows > 0){
		$countFirm = $resGetFirm ->num_rows;
		$restFirm = 1500 - $countFirm;
		$cadData .= '<br>Llevamos ' .$countFirm.' y nos faltan'.$restFirm.' para llegar a la meta.' ;
		while($rowGetFirm = $resGetFirm -> fetch_assoc()){
			$cadFirm .= '<tr><td>'.$rowGetFirm['id'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['name'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['ap'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['am'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['clave'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['colony'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['recolector'].'</td>';
			$cadFirm .= '<td>'.$rowGetFirm['apoyo'].'</td>';
			$cadFirm .= '<td><a href="update_firm.php?id='.$rowGetFirm['id'].'"><span class="glyphicon glyphicon-wrench"></span></td>';
			$cadFirm .= '</tr>';
		}
	}else $cadFirm = '<tr><td colspan="5">No hay firmas aún</td></tr>';
	
	$sqlGetCol = "SELECT id, name FROM $tCol ";
	$resGetCol = $con->query($sqlGetCol);
	$optCol = '<option></option>';
	if($resGetCol->num_rows > 0){
		while($rowGetCol = $resGetCol->fetch_assoc()){
			$optCol .= '<option value="'.$rowGetCol['id'].'">'.$rowGetCol['name'].'</option>';
		}
	}
	
	$sqlGetReco = "SELECT id, CONCAT(name,' ',ap,' ',am) as name FROM $tReco ";
	$resGetReco = $con->query($sqlGetReco);
	$optReco = '<option></option>';
	if($resGetReco->num_rows > 0){
		while($rowGetReco = $resGetReco->fetch_assoc()){
			$optReco .= '<option value="'.$rowGetReco['id'].'">'.$rowGetReco['name'].'</option>';
		}
	}
	
	$sqlGetApoyo = "SELECT id, name FROM $tApo ";
	$resGetApoyo = $con->query($sqlGetApoyo);
	$optApoyo = '<option></option>';
	if($resGetApoyo->num_rows > 0){
		while($rowGetApoyo = $resGetApoyo->fetch_assoc()){
			$optApoyo .= '<option value="'.$rowGetApoyo['id'].'">'.$rowGetApoyo['name'].'</option>';
		}
	}

?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Firmas</h1>

          <div class="row placeholders">
            <div class="col-xs-18 col-sm-12 placeholder">
              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAddReg">Añadir nueva firma <span class="glyphicon glyphicon-plus"></span>
							</button>
							<br>
							<span class="text-muted"><?= $cadData; ?></span>
            </div>
          </div>

          <h2 class="sub-header">Lista de firmas recolectadas</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Apellido paterno</th>
                  <th>Apellido materno</th>
                  <th>Clave de elector</th>
									<th>Colonia</th>
                  <th>Recolector</th>
                  <th>Compromiso</th>
                  <th>Modificar</th>
                </tr>
              </thead>
              <tbody>
                <?= $cadFirm; ?>
              </tbody>
            </table>
          </div>
        </div>

<!-- ventana modal para añadir usuarios -->
<div class="modal fade" id="modalAddReg" tabindex="-1" role="dialog" aria-labellebdy="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Añadir registro</h4>
				<p class="msgModal"></p>
			</div>
			<form id="formAddReg" name="formAddReg" >
				<div class="modal-body">
				<!-- contenido documento -->
					<div class="form-group">
						<label for="inputName">Nombre</label>
						<input type="text" class="form-control" id="inputName" name="inputName" >
					</div>
					<div class="form-group">
						<label for="inputAp">Apellido paterno</label>
						<input type="text" class="form-control" id="inputAp" name="inputAp" >
					</div>
					<div class="form-group">
						<label for="inputName">Apellido materno</label>
						<input type="text" class="form-control" id="inputAm" name="inputAm" >
					</div>
					<div class="form-group">
						<label for="inputClave">Clave de elector</label>
						<input type="text" class="form-control" id="inputClave" name="inputClave" >
					</div>
					<div class="form-group">
						<label for="inputCol">Colonia</label>
						<select class="form-control" id="inputCol" name="inputCol" >
							<?= $optCol; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="inputReco">Recolector</label>
						<select class="form-control" id="inputReco" name="inputReco" >
							<?= $optReco; ?>
						</select>
					</div>
					<div class="form-group">
						<label for="inputApoyo">Compromiso con el candidato</label>
						<select class="form-control" id="inputApoyo" name="inputApoyo" >
							<?= $optApoyo; ?>
						</select>
					</div>
				</div><!-- end modal body -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Añadir</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
    var ordenar = '';
    $(document).ready(function () {
			// Añadir nuevo registro y validación de envío 
			$('#formAddReg').validate({
        rules: {
					inputName: {required: true},
					inputAp: {required: true},
					inputAm: {required: true},
					inputClave: {required: true, maxlength: 18},
					inputCol: {required: true},
					inputReco: {required: true},
					inputApoyo: {required: true}
        },
        messages: {
					inputName: "Nombre obligatorio",
					inputAp: "Apellido paterno obligatorio",
					inputAm: "Apellido materno obligatorio",
					inputClave: { required: "Clave de elector obligatoria", maxlength: "Máximo 18 caracteres"},
					inputCol: "Colonia obligatoria",
					inputReco: "Recolector obligatorio",
					inputApoyo: "Compromiso obligatorio",
        },
        tooltip_options: {
					inputName: {trigger: "focus", placement: 'bottom'},
					inputAp: {trigger: "focus", placement: 'bottom'},
					inputAm: {trigger: "focus", placement: 'bottom'},
					inputClave: {trigger: "focus", placement: 'bottom'},
					inputCol: {trigger: "focus", placement: 'bottom'},
					inputReco: {trigger: "focus", placement: 'bottom'},
					inputApoyo: {trigger: "focus", placement: 'bottom'}
        },
        submitHandler: function (form) {
          $.ajax({
            type: "POST",
            url: "../controllers/insert_user_firm.php",
            data: $('form#formAddReg').serialize(),
            success: function (msg) {
              //alert(msg);
              if (msg == "true") {
                $('.msgModal').css({color: "#77DD77"});
                $('.msgModal').html("Se añadio la firma con éxito.");
                setTimeout(function () {
                  location.href = 'firmantes.php';
                }, 3000);
              } else {
                $('.msgModal').css({color: "#FF0000"});
                $('.msgModal').html(msg);
              }
            },
            error: function () {
              alert("Error al crear firma.");
            }
          });
        }
      });
		});
</script>

<?php
	include ('footer.php');
?>