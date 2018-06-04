<?php
	include ('header.php');
	include('../config/variables.php');
	include('../config/conexion.php');
?>

<title><?= $tit; ?></title>
<meta name="author" content="Luigi Pérez Calzada (GianBros)" />
<meta name="description" content="Descripción de la página" />
<meta name="keywords" content="etiqueta1, etiqueta2, etiqueta3" />
<!-- script para google maps -->
	<script src="https://maps.googleapis.com/maps/api/js"></script>
	<style>
		#map-canvas {
			width: 100%;
			height: 400px;
		}
		
		#map-canvas2 {
			width: 100%;
			height: 400px;
		}
	</style>
<?php
	include ('navbar.php');
	if (!isset($_SESSION['sessU'])){
		echo '<div class="row><div class="col-sm-12 text-center"><h2>No tienes permiso para entrar a esta sección</h2></div></div>';
	}else {
		
		$userId = $_SESSION['userId'];
		// obtenemos todos las calles
		$sqlGetStreets = "SELECT DISTINCT street FROM $tUserData ";
		$resGetStreets = $con->query($sqlGetStreets);
		$optStreets = '<option></option>';
		if($resGetStreets->num_rows > 0){
			while($rowGetStreet = $resGetStreets->fetch_assoc()){
				$optStreets .= '<option value="'.$rowGetStreet['street'].'">'.$rowGetStreet['street'].'</option>';
			}
		}
		
		// obtenemos todas las colonias
		$sqlGetTowns = "SELECT DISTINCT town FROM $tUserData ";
		$resGetTowns = $con->query($sqlGetTowns);
		$optTown = '<option></option>';
		if($resGetTowns -> num_rows > 0){
			while($rowGetTown = $resGetTowns -> fetch_assoc()){
				if($rowGetTown['town']=="") continue;
				$optTown .= '<option value="'.$rowGetTown['town'].'">'.$rowGetTown['town'].'</option>';
			}
		}
		
		// obtenemos los usuarios para añadirle referencias
		$sqlGetUsers = "SELECT id, CONCAT(name,' ',ap,' ',am) as name FROM $tUserData ";
		$resGetUsers = $con->query($sqlGetUsers);
		$optUsers = '<option></option>';
		while($rowGetUser = $resGetUsers->fetch_assoc()){
			$optUsers .= '<option value="'.$rowGetUser['id'].'">'.$rowGetUser['name'].'</option>';
		}
		
		// obtenemos los sexos
		$sqlGetSexs = "SELECT * FROM $tSex";
		$resGetSexs = $con->query($sqlGetSexs);
		$optSexs = '<option></option>';
		while($rowGetSex = $resGetSexs->fetch_assoc()){
			$optSexs .= '<option value="'.$rowGetSex['id'].'">'.$rowGetSex['nombre'].'</option>';
		}
		
?>

	<div class="container">
		<div class="row">
			<div class="col-sm-12 text-center">
				<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modalAddReg">Añadir nuevo registro <span class="glyphicon glyphicon-plus"></span>
				</button>
			</div>
		</div>
		<br>
		<div class="row">
			   <form id="frm_filtro" method="post" action="" class="form-horizontal">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="nombre">Nombre</label>
								<div class="col-sm-8"><input type="text" class="form-control" name="nombre" id="nombre"></div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="ap">Apelido paterno</label>
								<div class="col-sm-8"><input type="text" class="form-control" name="ap" id="ap"></div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="calle">Calle</label>
								<div class="col-sm-8">
									<select id="calle" name="calle" class="form-control">
										<?= $optStreets; ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="colonia">Colonia</label>
								<div class="col-sm-8">
									<select id="colonia" name="colonia" class="form-control">
										<?= $optTown; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-sm-offset-5 col-sm-7">
						 <button type="button" id="btnfiltrar" class="btn btn-success">Filtrar <span class="glyphicon glyphicon-filter"></span></button>
						 <a href="javascript:;" id="btncancel" class="btn btn-default">Todos</a>
						</div>
				</form>
				
		</div>
		<div class="table-responsive">
			<table class="table table-striped" id="data">
				<thead>
					<th><b>#</b></th>
					<th>Imagen</th>
					<th><span title="name">Nombre</span></th>
					<th><span title="ap">Apellido paterno</span></th>
					<th><span title="am">Apellido materno</span></th>
					<th><span title="street">Calle</span></th>
					<th><span title="town">Colonia</span></th>
					<th><span title="city">Municipio</span></th>
					<th><span title="referencia_id">Referencia</span></th>
					<th><span title="facebook">Facebook</span></th>
					<th>Ver datos completos</th>
					<th>Modificar</th>
					<th>Eliminar</th>
				</thead>
				<tbody></tbody>
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
			<form id="formAddReg" name="formAddReg" method="POST" enctype="multipart/form-data">
				<div class="modal-body">
				<!-- contenido documento -->
				<?php
					$arrName=array("Nombre", "Apellido paterno", "Apellido Materno", "Sexo", "Fecha de nacimiento", "Calle", "Número", "Colonia", "Municipio", "Estado", "Código Postal", "Teléfono", "Celular", "Correo electrónico", "Facebook", "Twitter", "Fotografía", "IFE", "IFE (reverso)",  "Clave Electoral", "Referencia");
					$arrInputName=array("name", "ap", "am", "sex_id", "birthday", "street", "num", "town", "city", "state", "cp", "tel", "cel", "mail", "facebook", "twitter", "photo", "ife", "ife_rev", "clave_electoral", "referencia_id");
					$arrTypeInput=array("text", "text", "text", "text", "date", "text", "text", "text", "text", "text", "number", "number", "number", "email", "text", "text", "file", "file", "file", "text", "text");
					
					// Recorremos la información del usuario
					$data='';
					for($i=0; $i<count($arrName); $i++){
						$data.='<div class="form-group">';
						$data.='<label for="input'.$arrInputName[$i].'" >'.$arrName[$i].'</label>';
						if( $arrInputName[$i] == "sex_id" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optSexs.'</select>';
						else if( $arrInputName[$i] == "town" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optTown.'</select>';
						else if( $arrInputName[$i] == "referencia_id" ) $data.='<select class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'">'.$optUsers.'</select>';
						else $data.='<input type="'.$arrTypeInput[$i].'" class="form-control" id="input'.$arrInputName[$i].'" name="input'.$arrInputName[$i].'" >';
						$data.='</div>';
					}
					$data.='<input type="hidden" name="inputlat" id="inputlat" >';
					$data.='<input type="hidden" name="inputlon" id="inputlon" >';
					$data.='<input type="hidden" name="inputIdUser" value="'.$userId.'">';
					$data.='<div class="form-group"><a href="#" target="_blank" id="viewMap">Ver Mapa</a></div>';
					$data.='<div id="map-canvas"></div>';
					echo $data;
				?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Añadir</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- ventana modal para editar usuarios -->
<div class="modal fade" id="MyModalModReg" tabindex="-1" role="dialog" aria-labellebdy="MySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Editar registro</h4>
				<p class="msgModal"></p>
			</div>
			<form id="formEditReg">
				<div class="modal-body">
				<!-- contenido documento -->
				</div>
					<!--<input type="text" name="inputIdUserSes" value="<?= $userId; ?>" >
					<input type="hidden" name="inputlat" id="inputlat" >
					<input type="hidden" name="inputlon" id="inputlon" >
					<div class="form-group"><a href="#" target="_blank" id="viewMap2">Ver Mapa</a></div>
					<div id="map-canvas2"></div>-->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="submit" class="btn btn-primary">Actualizar</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">
    var ordenar = '';
    $(document).ready(function () {
        //Ordenamiento
        filtrar();
        function filtrar(){
            $.ajax({
                type: "POST",
                data: $("#frm_filtro").serialize()+ordenar,
                url: "../controllers/select_users.php?action=listar",
                success: function(msg){
                    //$("#data tbody").empty();
                    $("#data tbody").html(msg);
                }
            });
        }
        
        //Ordenar ASC y DESC header tabla
        $("#data th span").click(function(){
            if($(this).hasClass("desc")){
                $("#data th span").removeClass("desc").removeClass("asc");
                $(this).addClass("asc");
                ordenar = "&orderby="+$(this).attr("title")+" asc";
            }else{
                $("#data th span").removeClass("desc").removeClass("asc");
                $(this).addClass("desc");
                ordenar = "&orderby="+$(this).attr("title")+" desc";
            }
            filtrar();
        });
        
        //Ordenar por formulario
        $("#btnfiltrar").click(function(){ 
            filtrar();
            //alert("y ahora?");
        });
        
        // boton cancelar
		$("#btncancel").click(function(){ 
				//$("#frm_filtro #calle").find("option[value='0']").attr("selected",true);
				$("#frm_filtro #nombre").val('');
				$("#frm_filtro #ap").val('');
				$("#frm_filtro #calle").val('');
				$("#frm_filtro #colonia").val('');
				filtrar() 
		});
		
		//Obtener coordenadas
		$("#formAddReg").on("change", function(){
			var valStreet=$("#inputstreet").val();
			var valNum=$("#inputnum").val();
			var valTown=$("#inputtown").val();
			var valCity=$("#inputcity").val();
			var valState=$("#inputstate").val();
			$.ajax({
				type: 'POST',
				url: '../controllers/get_cord_lat_lon.php',
				data: {street: valStreet, num: valNum,  town: valTown, city: valCity, state: valState},
				success: function(response){
					//alert(response);
					var data=jQuery.parseJSON(response);
					if(data.error==0){
						//alert(data.Lat+'**'+data.Lon);
						$("#inputlat").val(data.Lat);
						$("#inputlon").val(data.Lon);
						$('#viewMap').attr({  'href': 'https://www.google.com.mx/maps/place//@'+data.Lat+','+data.Lon+',18z' });
						initMap(data.Lat, data.Lon);
					}
					//$('#MyModalModUser .modal-body').html(response);
					//$("#verModal .modal-body").html(response);
				}
			});
		});
		//Obtener coordenadas modificación de registro
		$("#formEditReg .modal-body").on("change", function(){
			var valStreet=$(this).parent().parent().find("#inputstreet").val();
			var valNum=$(this).parent().parent().find("#inputnum").val();
			var valTown=$(this).parent().parent().find("#inputtown").val();
			var valCity=$(this).parent().parent().find("#inputcity").val();
			var valState=$(this).parent().parent().find("#inputstate").val();
			//alert(valStreet+"--"+valNum+"--"+valTown+"--"+valCity+"--"+valState);
			$.ajax({
				type: 'POST',
				url: '../controllers/get_cord_lat_lon.php',
				data: {street: valStreet, num: valNum,  town: valTown, city: valCity, state: valState},
				success: function(response){
					//alert(response);
					var data=jQuery.parseJSON(response);
					if(data.error==0){
						//alert(data.Lat+'**'+data.Lon);
						$("#inputlat").val(data.Lat);
						$("#inputlon").val(data.Lon);
						$('#viewMap2').attr({  'href': 'https://www.google.com.mx/maps/place//@'+data.Lat+','+data.Lon+',18z' });
						initMap2(data.Lat, data.Lon);
					}
					//$('#MyModalModUser .modal-body').html(response);
					//$("#verModal .modal-body").html(response);
				}
			});
		});
		
		//Modificar registro
			$("#data").on("click", ".edit", function () {
				var idUser=$(this).data('id');
				//alert(idUser);
				$.ajax({
					type: 'POST',
					url: '../controllers/select_user_data.php',
					data: {idUser: idUser, idUserSess: <?= $userId; ?>},
					success: function(response){
						//alert(response);
						$('#MyModalModReg .modal-body').html(response);
						//$("#verModal .modal-body").html(response);
					}
				});
			});
        
		
       //eliminar usuario
        $("#data tbody").on("click", ".delete", function(){
            //alert("Hope");
            var idCatDel = $(this).data('id');
            if(confirm("¿Está seguro(a) que desea dar de baja este registro?") == true){
                $.ajax({
                    type: 'POST',
                    url: 'controllers/delete_category.php',
                    data: {categoryDel: idCatDel, est: 1},
                    success: function(msg){
                        //alert(msg);
                        if (msg == "true") {
                            $('.msg').css({color: "#77DD77"});
                            $('.msg').html("Se dio de Baja la categoría con éxito.");
                                setTimeout(function () {
                                  location.href = 'form_select_category.php';
                                }, 1500);
                        } else {
                            $('.msg').css({color: "#FF0000"});
                            $('.msg').html(msg);
                        }
                    }
		});
            }//end if confirm
        });
        $("#data tbody").on("click", ".activate", function(){
            //alert("Hope");
            var idCatAct = $(this).data('id');
            //alert("Eliminando..." + idCatAct);
            if(confirm("¿Está seguro(a) que desea dar de Alta el registro?") == true){
                $.ajax({
                    type: 'POST',
                    url: 'controllers/delete_category.php',
                    data: {categoryDel: idCatAct, est: 0},
                    success: function(msg){
                        //alert(msg);
                        if (msg == "true") {
                            $('.msg').css({color: "#77DD77"});
                            $('.msg').html("Se activo la Categoría con éxito.");
                                setTimeout(function () {
                                  location.href = 'form_select_category.php';
                                }, 1500);
                        } else {
                            $('.msg').css({color: "#FF0000"});
                            $('.msg').html(msg);
                        }
                    }
		});
            }//end if confirm
        });
        
        
      $('#formAddReg').validate({
        rules: {
			  inputname: {required: true},
			  inputap: {required: true},
			  inputam: {required: true},
			  inputsex_id: {required: true},
			  inputcp: {digits: true, minlength: 5, maxlength: 5},
			  inputtel: {minlength: 10, maxlength: 10, digits: true},
			  inputcel: {minlength: 10, maxlength: 10, digits: true},
			  inputmail: {email: true},
			  inputphoto: {extension: "jpg|png|bmp|jpeg|gif"},
			  inputife: {extension: "jpg|png|bmp|jpeg|gif"},
			  inputife_rev: {extension: "jpg|png|bmp|jpeg|gif"}
        },
        messages: {
			  inputname: "Nombre obligatorio",
			  inputap: "Apellido paterno obligatorio",
			  inputam: "Apellido materno obligatorio",
			  inputsex_id: "Sexo obligatorio",
			  inputcp:{ 
				minlength: "Se requieren mínimo 5 dígitos",
				maxlength: "Se admiten máximo 5 dígitos ",
				digits: "Solo se admiten números."
			  },
			  inputtel:{ 
				minlength: "Se requieren mínimo 10 dígitos",
				maxlength: "Se admiten máximo 10 dígitos ",
				digits: "Solo se admiten números."
			  },
			  inputcel:{  
				minlength: "Se requieren mínimo 10 dígitos",
				maxlength: "Se admiten máximo 10 dígitos ",
				digits: "Solo se admiten números."
			  },
			  inputmail:{ 
				email: "Formato de correo invalido"
			  },
			  inputphoto: "Formato de imagen no valido",
			  inputife: "Formato de imagen no valido",
			  inputife_rev: "Formato de imagen no valido"
        },
        tooltip_options: {
			  inputname: {trigger: "focus", placement: 'bottom'},
			  inputap: {trigger: "focus", placement: 'bottom'},
			  inputam: {trigger: "focus", placement: 'bottom'},
			  inputsex_id: {trigger: "focus", placement: 'bottom'},
			  inputcp: {trigger: "focus", placement: 'bottom'},
			  inputtel: {trigger: "focus", placement: 'bottom'},
			  inputcel: {trigger: "focus", placement: 'bottom'},
			  inputmail: {trigger: "focus", placement: 'bottom'},
			  inputphoto: {trigger: "focus", placement: 'bottom'},
			  inputife: {trigger: "focus", placement: 'bottom'},
			  inputife_rev: {trigger: "focus", placement: 'bottom'},
        },
        submitHandler: function (form) {
          //var data = new FormData();
          //data.append('file', $('#inputImg')[0].files[0]);
          //form.preventDefault();
          $.ajax({
            type: "POST",
            url: "../controllers/insert_user_data.php",
            //data: $('form#formAddCategory').serialize(),
            //data: data,
            data: new FormData($("form#formAddReg")[0]),
            contentType: false,
            processData: false,
            //contentType: "multipart/form-data",
            //cache: false,
            //mimeType: "multipart/form-data",
            success: function (msg) {
              //alert(msg);
              if (msg == "true") {
                $('.msgModal').css({color: "#77DD77"});
                $('.msgModal').html("Se añadio el registro con éxito.");
                setTimeout(function () {
                  location.href = 'select_users.php';
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
	  
		//validación modificaciones
		 $('#formEditReg').validate({
			rules: {
				  inputname: {required: true},
				  inputap: {required: true},
				  inputam: {required: true},
				  inputsex_id: {required: true},
				  inputcp: {digits: true, minlength: 5, maxlength: 5},
				  inputtel: {minlength: 10, maxlength: 10, digits: true},
				  inputcel: {minlength: 10, maxlength: 10, digits: true},
				  inputmail: {email: true},
				  inputphoto: {extension: "jpg|png|bmp|jpeg|gif"},
				  inputife: {extension: "jpg|png|bmp|jpeg|gif"},
				  inputife_rev: {extension: "jpg|png|bmp|jpeg|gif"}
			},
			messages: {
				  inputname: "Nombre obligatorio",
				  inputap: "Apellido paterno obligatorio",
				  inputam: "Apellido materno obligatorio",
				  inputsex_id: "Sexo obligatorio",
				  inputcp:{ 
					minlength: "Se requieren mínimo 5 dígitos",
					maxlength: "Se admiten máximo 5 dígitos ",
					digits: "Solo se admiten números."
				  },
				  inputtel:{ 
					minlength: "Se requieren mínimo 10 dígitos",
					maxlength: "Se admiten máximo 10 dígitos ",
					digits: "Solo se admiten números."
				  },
				  inputcel:{  
					minlength: "Se requieren mínimo 10 dígitos",
					maxlength: "Se admiten máximo 10 dígitos ",
					digits: "Solo se admiten números."
				  },
				  inputmail:{ 
					email: "Formato de correo invalido"
				  },
				  inputphoto: "Formato de imagen no valido",
				  inputife: "Formato de imagen no valido",
				  inputife_rev: "Formato de imagen no valido"
			},
			tooltip_options: {
				  inputname: {trigger: "focus", placement: 'bottom'},
				  inputap: {trigger: "focus", placement: 'bottom'},
				  inputam: {trigger: "focus", placement: 'bottom'},
				  inputsex_id: {trigger: "focus", placement: 'bottom'},
				  inputcp: {trigger: "focus", placement: 'bottom'},
				  inputtel: {trigger: "focus", placement: 'bottom'},
				  inputcel: {trigger: "focus", placement: 'bottom'},
				  inputmail: {trigger: "focus", placement: 'bottom'},
				  inputphoto: {trigger: "focus", placement: 'bottom'},
				  inputife: {trigger: "focus", placement: 'bottom'},
				  inputife_rev: {trigger: "focus", placement: 'bottom'},
			},
			submitHandler: function (form) {
				  $.ajax({
						type: "POST",
						url: "../controllers/update_user_data.php",
						data: new FormData($("form#formEditReg")[0]),
						contentType: false,
						processData: false,
						success: function (msg) {
							  //alert(msg);
							  if (msg == "true") {
								$('.msgModal').css({color: "#77DD77"});
								$('.msgModal').html("Se modifico el registro con éxito.");
								setTimeout(function () {
								  location.href = 'select_users.php';
								}, 3000);
						  } else {
								$('.msgModal').css({color: "#FF0000"});
								$('.msgModal').html(msg);
						  }
						},
						error: function () {
							alert("Error al modificar registro.");
						}
				  });
			}
		});
      
    });
  </script>
  
  <script>
		function initMap(lat2, lon2) {
			var lat2=lat2;
			var lon2=lon2;
			var myLatLng = {lat: lat2, lng: lon2};

			var map = new google.maps.Map(document.getElementById('map-canvas'), {
				zoom: 16,
				center: myLatLng
			});

			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Hello World!'
			});
		}
		
		function initMap2(lat2, lon2) {
			var lat2=lat2;
			var lon2=lon2;
			var myLatLng = {lat: lat2, lng: lon2};

			var map = new google.maps.Map(document.getElementById('map-canvas2'), {
				zoom: 16,
				center: myLatLng
			});

			var marker = new google.maps.Marker({
				position: myLatLng,
				map: map,
				title: 'Hello World!'
			});
		}
    </script>
<?php
	}//fin else sesión
	include ('footer.php');
?>