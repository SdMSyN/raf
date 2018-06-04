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
	
	$sqlGetReco = "SELECT *, (SELECT name FROM $tCol WHERE id=$tReco.colony) as colony FROM $tReco ";
	$resGetReco = $con->query($sqlGetReco);
	$cadReco = '';
	if($resGetReco->num_rows > 0){
		while($rowGetReco = $resGetReco->fetch_assoc()){
			$cadReco .= '<tr><td>'.$rowGetReco['id'].'</td>';
			$cadReco .= '<td>'.$rowGetReco['name'].'</td>';
			$cadReco .= '<td>'.$rowGetReco['ap'].'</td>';
			$cadReco .= '<td>'.$rowGetReco['am'].'</td>';
			$cadReco .= '<td>'.$rowGetReco['colony'].'</td>';
			$cadReco .= '</tr>';
		}
	}else $cadReco = '<tr><td colspan="5">No hay recolectores aún</td></tr>';
	
	$sqlGetCol = "SELECT id, name FROM $tCol ";
	$resGetCol = $con->query($sqlGetCol);
	$optCol = '<option></option>';
	if($resGetCol->num_rows > 0){
		while($rowGetCol = $resGetCol->fetch_assoc()){
			$optCol .= '<option value="'.$rowGetCol['id'].'">'.$rowGetCol['name'].'</option>';
		}
	}

?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Recolectores</h1>

          <div class="row placeholders">
            <div class="col-xs-18 col-sm-12 placeholder">
              <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAddReg">Añadir nuevo recolector <span class="glyphicon glyphicon-plus"></span>
							</button>
            </div>
          </div>

          <h2 class="sub-header">Lista de recolectores</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Apellido paterno</th>
                  <th>Apellido materno</th>
                  <th>Municipio</th>
                </tr>
              </thead>
              <tbody>
                <?= $cadReco; ?>
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
						<label for="inputCol">Colonia</label>
						<select class="form-control" id="inputCol" name="inputCol" >
							<?= $optCol; ?>
						</select>
					</div>
				</div>
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
					inputCol: {required: true}
        },
        messages: {
					inputName: "Nombre obligatorio",
					inputAp: "Apellido paterno obligatorio",
					inputAm: "Apellido materno obligatorio",
					inputCol: "Colonia obligatoria"
        },
        tooltip_options: {
					inputName: {trigger: "focus", placement: 'bottom'},
					inputAp: {trigger: "focus", placement: 'bottom'},
					inputAm: {trigger: "focus", placement: 'bottom'},
					inputCol: {trigger: "focus", placement: 'bottom'}
        },
        submitHandler: function (form) {
          $.ajax({
            type: "POST",
            url: "../controllers/insert_user_data.php",
            data: $('form#formAddReg').serialize(),
            success: function (msg) {
              //alert(msg);
              if (msg == "true") {
                $('.msgModal').css({color: "#77DD77"});
                $('.msgModal').html("Se añadio el registro con éxito.");
                setTimeout(function () {
                  location.href = 'index.php';
                }, 3000);
              } else {
                $('.msgModal').css({color: "#FF0000"});
                $('.msgModal').html(msg);
              }
            },
            error: function () {
              alert("Error al crear registro.");
            }
          });
        }
      });
		});
</script>

<?php
	include ('footer.php');
?>