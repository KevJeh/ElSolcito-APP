<?php
	session_start();
	if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 1) {
		header('location: ../'); 
	}
	$id= $_GET['id'];

	include '../conexion_db.php';
	$sentencia="UPDATE proveedor SET estado=0 WHERE idproveedor= $id";
	$conexion->query($sentencia) or die ("Error al eliminar el Proveerdor<br>".mysqli_error($conexion));
	mysqli_close($conexion);
?>

<script type="text/javascript">
	alert("Proveerdor Eliminado Exitosamante!!");
	window.location.href='lista_proveedores.php';
</script>