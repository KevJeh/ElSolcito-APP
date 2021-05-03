<?php
	session_start();
	$id= $_GET['id'];

	include '../conexion_db.php';
	$sentencia="UPDATE mensajes SET estado=0 WHERE idmensaje= $id";
	$conexion->query($sentencia) or die ("Error al eliminar el Mensaje<br>".mysqli_error($conexion));
	mysqli_close($conexion);
?>

<script type="text/javascript">
	alert("Mensaje Eliminado Exitosamante!!");
	window.location.href='lista_mensajes.php';
</script>