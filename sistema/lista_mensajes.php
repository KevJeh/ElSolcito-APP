<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estilolistas.css" rel="stylesheet">
    <title>Mensajes</title>

    <?php include 'includes/scripts.php'
    
    ?>

</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
	<section class="container">
    <h2><i class="fas fa-envelope"></i> Mensajes</h2>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Asunto</th>
                <th>Mensaje</th>
                <th>Acciones</th>

 
            </tr>
        </thead>
        <tbody>
        <?php
            include '../conexion_db.php';
            $query=mysqli_query($conexion,"SELECT * FROM mensajes WHERE estado = 1");
            mysqli_close($conexion);
            $result=mysqli_num_rows($query);
            
            if ($result>0) {
                while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo $data['idmensaje']?></td>
                <td><?php echo $data['nombre']?></td>
                <td><?php echo $data['mail']?></td>
                <td><?php echo $data['asunto']?></td>
                <td><?php echo $data['texto']?></td>
                <td><a href="eliminar_mensaje.php?id=<?php echo $data['idmensaje']?>"><button class="btn btn-danger" onclick='return confirmeliminar()'><i class="fas fa-trash-alt"></i> Eliminar</button></a></td>

            </tr>
        <?php
                }
            }
        ?>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <?php include 'includes/paginacion.php'?>
	</section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
</html>
<script type="text/javascript">
	function confirmeliminar()
    {
        var respuesta = confirm("¿Estas seguro que deseas eliminar el Mensaje?");
        if (respuesta == true){
            return true;
        }
        else{
            return false;
        }

    }
</script>