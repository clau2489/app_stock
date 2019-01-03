<?php
	session_start(); /* Primer archivo que se ejecuta - INICIO DE SESION*/
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$active_productos="active"; //
	$title="Productos"; //Titulo del Proyecto
?>

<!DOCTYPE html>
<html lang="en">
  	<head>
    <?php include("head.php");?> <!-- Llamamos al archivo head.php -->
  	</head>
  	<body>
	<?php
	include("navbar.php"); /* llamamos al archivo navbar.php */
	?>
    <div class="container"> <!-- Contenedor que mostrara la pagina principal con la busqueda de un producto -->
		<div class="panel panel-default">
			<div class="panel-heading">
		    	<div class="btn-group pull-right">
					<button type='button' class="btn btn-success" data-toggle="modal" data-target="#nuevoProducto"><span class="glyphicon glyphicon-plus" ></span> Agregar</button>
				</div>
				<h5><i class='glyphicon glyphicon-search'></i> Buscar</h5>
			</div>
			<div class="panel-body">
				<?php
				/* Incluimos los archivos que contienen los formularios de registro y edicion de un nuevo producto*/
				include("modal/registro_productos.php");
				include("modal/editar_productos.php");
				?>
				<form class="form-horizontal" role="form" id="datos"> <!-- Creamos el formulario para realizar la busqueda de un producto -->	
					<div class="form-group row">
						<div class='col-md-2'>
							<h6>Filtrar por código o nombre:</h6>
						</div>
						<div class="col-md-4">
							<input type="text" class="form-control" id="q" placeholder="Código o nombre del producto" onkeyup='load(1);'>
						</div>
						<div class="col-md-2">
							<h6>Filtrar por categoría:</h6>
						</div>
						<div class='col-md-4'>
							<select class='form-control' name='id_categoria' id='id_categoria' onchange="load(1);">
							<option value="">Selecciona una categoría</option>
							<?php 
							$query_categoria=mysqli_query($con,"select * from categorias order by nombre_categoria"); /* query que filtra los productos por categorias*/
							while($rw=mysqli_fetch_array($query_categoria))	{
								?>
							<option value="<?php echo $rw['id_categoria'];?>"><?php echo $rw['nombre_categoria'];?></option>			
								<?php
							}
							?>
							</select>
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
	<script type="text/javascript" src="js/productos.js"></script>
  </body>
</html>
<script>
function eliminar (id){
		var q= $("#q").val();
		var id_categoria= $("#id_categoria").val();
		$.ajax({
			type: "GET",
			url: "./ajax/buscar_productos.php",
			data: "id="+id,"q":q+"id_categoria="+id_categoria,
			 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados").html(datos);
			load(1);
			}
		});
	}
		
	$(document).ready(function(){
			
		<?php 
			if (isset($_GET['delete'])){
		?>
			eliminar(<?php echo intval($_GET['delete'])?>);	
		<?php
			}
		
		?>	
	});
		
$( "#guardar_producto" ).submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/nuevo_producto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax_productos").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax_productos").html(datos);
			$('#guardar_datos').attr("disabled", false);
			load(1);
		  }
	});
  event.preventDefault();
})

</script>