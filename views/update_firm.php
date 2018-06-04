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
	$idUser=$_GET['id'];
	
	$sqlGetFirm = "SELECT id, name, ap, am, clave, "
		."colony_id, "
		."recolector_id, "
		."apoyo_id "
		."FROM $tFirm "
		."WHERE id='$idUser' ";
	$resGetFirm = $con->query($sqlGetFirm);
	$cadFirm = '';
	if($resGetFirm -> num_rows > 0){
		$rowGetFirm = $resGetFirm->fetch_assoc();
		$cadFirm .= '<input type="hidden" name="idUser" value="'.$idUser.'" >';
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputName">Nombre</label>'
			.'<input type="text" class="form-control" id="inputName" name="inputName" value="'.$rowGetFirm['name'].'">'
			.'</div>';
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputAp">Apellido paterno</label>'
			.'<input type="text" class="form-control" id="inputAp" name="inputAp" value="'.$rowGetFirm['ap'].'">'
			.'</div>';
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputName">Apellido materno</label>'
			.'<input type="text" class="form-control" id="inputAm" name="inputAm" value="'.$rowGetFirm['am'].'">'
			.'</div>';
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputClave">Clave de elector</label>'
			.'<input type="text" class="form-control" id="inputClave" name="inputClave" value="'.$rowGetFirm['clave'].'">'
			.'</div>';
			
		// Obtenemos la colonia
		$sqlGetCol = "SELECT id, name FROM $tCol ";
		$resGetCol = $con->query($sqlGetCol);
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputCol">Colonia</label>'
			.'<select class="form-control" id="inputCol" name="inputCol" >'
			.'<option></option>';
		if($resGetCol->num_rows > 0){
			while($rowGetCol = $resGetCol->fetch_assoc()){
				$cadFirm .= ($rowGetCol['id'] == $rowGetFirm['colony_id']) ? '<option value="'.$rowGetCol['id'].'" selected>'.$rowGetCol['name'].'</option>' : '<option value="'.$rowGetCol['id'].'">'.$rowGetCol['name'].'</option>';
			}
		}
		$cadFirm .= '</select></div>';
		// Obtenemos el recolector
		$sqlGetReco = "SELECT id, CONCAT(name,' ',ap,' ',am) as name FROM $tReco ";
		$resGetReco = $con->query($sqlGetReco);
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputReco">Recolector</label>'
			.'<select class="form-control" id="inputReco" name="inputReco" >'
			.'<option></option>';
		if($resGetReco->num_rows > 0){
			while($rowGetReco = $resGetReco->fetch_assoc()){
				$cadFirm .= ($rowGetReco['id'] == $rowGetFirm['recolector_id']) ? '<option value="'.$rowGetReco['id'].'" selected>'.$rowGetReco['name'].'</option>' :'<option value="'.$rowGetReco['id'].'">'.$rowGetReco['name'].'</option>';
			}
		}
		$cadFirm .= '</select></div>';
		// Obtenemos el apoyo
		$sqlGetApoyo = "SELECT id, name FROM $tApo ";
		$resGetApoyo = $con->query($sqlGetApoyo);
		$cadFirm .= '<div class="form-group">'
			.'<label for="inputApoyo">Compromiso</label>'
			.'<select class="form-control" id="inputApoyo" name="inputApoyo" >'
			.'<option></option>';
		if($resGetApoyo->num_rows > 0){
			while($rowGetApoyo = $resGetApoyo->fetch_assoc()){
				$cadFirm .= ($rowGetApoyo['id'] == $rowGetFirm['apoyo_id']) ? '<option value="'.$rowGetApoyo['id'].'" selected>'.$rowGetApoyo['name'].'</option>' :'<option value="'.$rowGetApoyo['id'].'">'.$rowGetApoyo['name'].'</option>';
			}
		}
		$cadFirm .= '</select></div>';
		
	}else $cadFirm .= '<tr><td colspan="5">No hay firmas aún</td></tr>';

?>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Modificar registro</h1>
					<p class="msgModal"></p>
					<form id="formUpdReg" name="formUpdReg" >
						<?= $cadFirm; ?>
						<a href="firmantes.php" class="btn btn-default">Cancelar</a>
						<button type="submit" class="btn btn-primary">Actualizar</button>
					</form>
        </div>

<script type="text/javascript">
    var ordenar = '';
    $(document).ready(function () {
			// Añadir nuevo registro y validación de envío 
			$('#formUpdReg').validate({
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
            url: "../controllers/update_user_firm.php",
            data: $('form#formUpdReg').serialize(),
            success: function (msg) {
              //alert(msg);
              if (msg == "true") {
                $('.msgModal').css({color: "#77DD77"});
                $('.msgModal').html("Se actualizo con éxito.");
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