<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_categoria="active";
	$title="Categorías";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	?>
	
    <div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
			    <div class="btn-group pull-right">
					<button type='button' class="btn btn-success" data-toggle="modal" data-target="#nuevoCliente"><span class="glyphicon glyphicon-plus" ></span> Nueva Categoría</button>
				</div>
				<h5><i class='glyphicon glyphicon-search'></i> Buscar</h5>
			</div>
			<div class="panel-body">		
				<?php
					include("modal/registro_categorias.php");
					include("modal/editar_categorias.php");
				?>
				<form class="form-horizontal" role="form" id="datos_cotizacion">				
					<div class="form-group row">
						<div class="col-md-2">
							<h6>Filtrar por Nombre:</h6>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="q" placeholder="Nombre de la categoría" onkeyup='load(1);'>
						</div>
					</div>				
				</form>
					<div id="resultados"></div><!-- Carga los datos ajax -->
					<div class='outer_div'></div><!-- Carga los datos ajax -->		
	  		</div>
		</div>		 
	</div>
	<hr>
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/categorias.js"></script>
  </body>
</html>
