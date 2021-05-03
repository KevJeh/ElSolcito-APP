<?php
	session_start();
	$id= $_GET['id'];

	include '../conexion_db.php';
	$sentencia="UPDATE cliente SET estado=0 WHERE idcliente= $id";
	$conexion->query($sentencia) or die ("Error al eliminar el contacto<br>".mysqli_error($conexion));
	mysqli_close($conexion);
?>

<script type="text/javascript">
	alert("Usuario Eliminado Exitosamante!!");
	window.location.href='lista_clientes.php';
</script>