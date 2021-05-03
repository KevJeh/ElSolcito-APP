<?php
	session_start();
	if($_SESSION['rol'] != 1) {
		header('location: ../'); 
	}
	$id= $_GET['id'];

	include '../conexion_db.php';
	$sentencia="UPDATE usuario SET estado=0 WHERE idusuario= $id";
	$conexion->query($sentencia) or die ("Error al eliminar el contacto<br>".mysqli_error($conexion));
	mysqli_close($conexion);
?>

<script type="text/javascript">
	alert("Usuario Eliminado Exitosamante!!");
	window.location.href='lista_usuarios.php';
</script>