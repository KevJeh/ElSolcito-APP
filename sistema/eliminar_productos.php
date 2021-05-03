<?php
	session_start();
	if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 1) {
		header('location: ../'); 
	}
	$id= $_GET['id'];

	include '../conexion_db.php';
	$sentencia="UPDATE producto SET estado=0 WHERE idproducto= $id";
	$conexion->query($sentencia) or die ("Error al eliminar el Proveerdor<br>".mysqli_error($conexion));
	mysqli_close($conexion);
?>

<script type="text/javascript">
	alert("Producto Eliminado Exitosamante!!");
	window.location.href='lista_productos.php';
</script>