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

    <title>Lista de Clientes</title>
    <?php include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
	<section class="container">
    <h2><i class="fas fa-id-badge"></i> Lista de Clientes</h2>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Nombre</th>
                <th>CUIT / CUIL</th>
                <th>Telefono</th>
                <th>Dirección</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../conexion_db.php';
            $query=mysqli_query($conexion,"SELECT * FROM cliente WHERE estado = 1");
            mysqli_close($conexion);
            $result=mysqli_num_rows($query);
            
            if ($result>0) {
                while ($data = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?php echo $data['idcliente']?></td>
                <td><?php echo $data['nombre']?></td>
                <td><?php echo $data['cuit']?></td>
                <td><?php echo $data['telefono']?></td>
                <td><?php echo $data['direccion']?></td>
                <td> <a href="editar_clientes.php?id=<?php echo $data['idcliente']?>"><button class="btn btn-warning"><i class="fas fa-edit"></i></i> Editar</button></a> 
                <a href="eliminar_clientes.php?id=<?php echo $data['idcliente']?>"><button class="btn btn-danger" onclick='return confirmeliminar()'><i class="fas fa-trash-alt"></i> Eliminar</button></a>

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
<footer style="color:white;"><?php include 'includes/footer.php'?></footer>
</html>
<script type="text/javascript">
	function confirmeliminar()
    {
        var respuesta = confirm("¿Estas seguro que deseas eliminar el Cliente?");
        if (respuesta == true){
            return true;
        }
        else{
            return false;
        }

    }
</script>