</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Menú</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="select_users.php">Inicio</a>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<!-- añadimos el menú variable de acuerdo al perfil -->
					<ul class="nav navbar-nav">
						<?php include('../controllers/menu.php'); ?>
					</ul>
					<!-- Mensaje de bienvenida -->
					<p class="nav navbar-nav navbar-right">
						<?php
							$cadWelcome="";
							if(isset($_SESSION['sessU'])  AND $_SESSION['sessU'] == "true"){
								$cadWelcome.= ($_SESSION['sex'] == "1") ? "Bienvenida " : "Bienvenido ";
								$cadWelcome.= $_SESSION['userName'];
								$cadWelcome.='  <a href="../controllers/proc_destroy_login.php">Salir</a>   ';
							}else{
								$cadWelcome.='&nbsp;&nbsp;<a href="index.php">Iniciar Sesión</a>';
							}
							echo '   '.$cadWelcome;
						?>
					</p>
				</div><!-- /.navbar-collapse -->
			</div><!-- /.container-fluid -->
		</nav>