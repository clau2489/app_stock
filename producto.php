<?php
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }

	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	include("funciones.php");
	
	$active_productos="active";
	$active_clientes="";
	$active_usuarios="";	
	$title="Productos";
	
	if (isset($_POST['reference']) and isset($_POST['quantity'])){
		$quantity=intval($_POST['quantity']);
		$reference=mysqli_real_escape_string($con,(strip_tags($_POST["reference"],ENT_QUOTES)));
		$id_producto=intval($_GET['id']);
		$user_id=$_SESSION['user_id'];
		$firstname=$_SESSION['firstname'];
		$nota="$firstname agregó $quantity producto(s) al inventario";
		$fecha=date("Y-m-d H:i:s");
		guardar_historial($id_producto,$user_id,$fecha,$nota,$reference,$quantity);
		$update=agregar_stock($id_producto,$quantity);
		if ($update==1){
			$message=1;
		} else {
			$error=1;
		}
	}
	
	if (isset($_POST['reference_remove']) and isset($_POST['quantity_remove'])){
		$quantity=intval($_POST['quantity_remove']);
		$reference=mysqli_real_escape_string($con,(strip_tags($_POST["reference_remove"],ENT_QUOTES)));
		$id_producto=intval($_GET['id']);
		$user_id=$_SESSION['user_id'];
		$firstname=$_SESSION['firstname'];
		$nota="$firstname eliminó $quantity producto(s) del inventario";
		$fecha=date("Y-m-d H:i:s");
		guardar_historial($id_producto,$user_id,$fecha,$nota,$reference,$quantity);
		$update=eliminar_stock($id_producto,$quantity);
		if ($update==1){
			$message=1;
		} else {
			$error=1;
		}
	}
	
	if (isset($_GET['id'])){
		$id_producto=intval($_GET['id']);
		$query=mysqli_query($con,"select * from products where id_producto='$id_producto'");
		$row=mysqli_fetch_array($query);
		
	} else {
		die("Producto no existe");
	}
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php include("head.php");?>
  </head>
  <body>
	<?php
	include("navbar.php");
	include("modal/agregar_stock.php");
	include("modal/eliminar_stock.php");
	include("modal/editar_productos.php");	
	?>	
	<div class="container">
		<div class="row">
		    <div class="col-md-12">
		        <div class="panel panel-default">
		          	<div class="panel-body text-center">
		            	<div class="row">
		            		<div class="col-md-12">
		            			<div class="col-md-6">
		            				<h5>Codigo del producto: <?php echo $row['codigo_producto'];?> </h5>
		            			</div>	
		            			<div class="col-md-6 pull-rigth">
			            			<h5>Stock Disponible: <?php echo number_format($row['stock'],2);?></h5>	            				
		            			</div>
		            		</div>
		            	</div>
		            	<div class="row">
		            		<div class="col-md-12">
			            		<div class="col-md-6">
			            			<h2><?php echo $row['nombre_producto'];?></h2>
			            		</div>
			            		<div class="col-md-6">
			            			<h2> $ <?php echo number_format($row['precio_producto'],2);?></h2>
			            		</div>
		            		</div>
	            		</div>
	            		<hr>		            		
	            		<div class="row">
	            			<div class="col-md-3">
	            				<button type="button" class="btn btn-success btn-block btn-colors" href="" data-toggle="modal" data-target="#add-stock"> Agregar Stock </button>
	            			</div>
	            			<div class="col-md-3">
	            				<button type="button" class="btn btn-info btn-block btn-colors" href="#myModal2" data-toggle="modal" data-codigo='<?php echo $row['codigo_producto'];?>' data-nombre='<?php echo $row['nombre_producto'];?>' data-categoria='<?php echo $row['id_categoria']?>' data-precio='<?php echo $row['precio_producto']?>' data-stock='<?php echo $row['stock'];?>' data-id='<?php echo $row['id_producto'];?>'>Editar Producto</button>
	            			</div>
	            			<div class="col-md-3">
	            				<button type="button" class="btn btn-warning btn-block btn-colors" href="" data-toggle="modal" data-target="#remove-stock"> Quitar Stock</button>
	            			</div>
	            			<div class="col-md-3">
	            				<button type="button" class="btn btn-danger btn-block btn-colors" href="#" onclick="eliminar('<?php echo $row['id_producto'];?>')">Eliminar Producto</button>
	            			</div>
	            		</div>
	            		<hr>
	           			<div class="row">
	           				<div class="col-md-12">
		                    	<?php
								if (isset($message)){
									?>
								<div class="alert alert-success alert-dismissible" role="alert">
								  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								  	<strong>Aviso!</strong> Datos procesados exitosamente.
								</div>	
									<?php
								}
								if (isset($error)){
									?>
								<div class="alert alert-danger alert-dismissible" role="alert">
								  	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;	</span></button>
								  	<strong>Error!</strong> No se pudo procesar los datos.
								</div>	
									<?php
								}
							?>
									<div class="row">
										<div class="col-md-12">
											<h4>Movimientos del Producto</h4>
										</div>
									</div>
									<hr>
					            	<div class="row">
					            		<?php
										$query=mysqli_query($con,"select * from historial where id_producto='$id_producto'");
										while ($row=mysqli_fetch_array($query)){
										?>
					            		<div class="col-md-2">
					            			<h6>Fecha: <?php echo date('d/m/Y', strtotime($row['fecha']));?></h6>
					            		</div>
					            		<div class="col-md-2">
					            			<h6>Hora: <?php echo date('H:i:s', strtotime($row['fecha']));?></h6>
					            		</div>
					            		<div class="col-md-4">
					            			<h6><?php echo $row['nota'];?></h6>
					            		</div>
					            		<div class="col-md-2">
					            			<h6>Cod. Referencia: <?php echo $row['referencia'];?></h6>
					            		</div>
					            		<div class="col-md-2">
					            			<h6>Total: <?php echo number_format($row['cantidad'],2);?></h6>
					            		</div>
				            		</div>
				            		<hr>	
								<?php
									}
								?>					                         		
	           				</div>
           				</div>
            		</div>
          		</div>
        	</div>
    	</div>
	</div>
	
	<?php
	include("footer.php");
	?>
	<script type="text/javascript" src="js/productos.js"></script>
  </body>
</html>
<script>
$( "#editar_producto" ).submit(function( event ) {
  $('#actualizar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/editar_producto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax2").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax2").html(datos);
			$('#actualizar_datos').attr("disabled", false);
			window.setTimeout(function() {
				$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();});
				location.replace('stock.php');
			}, 4000);
		  }
	});
  event.preventDefault();
})

	$('#myModal2').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var codigo = button.data('codigo') // Extract info from data-* attributes
		var nombre = button.data('nombre')
		var categoria = button.data('categoria')
		var precio = button.data('precio')
		var stock = button.data('stock')
		var id = button.data('id')
		var modal = $(this)
		modal.find('.modal-body #mod_codigo').val(codigo)
		modal.find('.modal-body #mod_nombre').val(nombre)
		modal.find('.modal-body #mod_categoria').val(categoria)
		modal.find('.modal-body #mod_precio').val(precio)
		modal.find('.modal-body #mod_stock').val(stock)
		modal.find('.modal-body #mod_id').val(id)
	})
	
	function eliminar (id){
		var q= $("#q").val();
		if (confirm("Realmente deseas eliminar el producto")){	
			location.replace('stock.php?delete='+id);
		}
	}
</script>