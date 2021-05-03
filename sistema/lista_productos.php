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
    <title>Lista de Productos</title>

    <?php include 'includes/scripts.php'
    ?>

</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
	<section class="container">
    <h2><i class="fas fa-archive"></i> Lista de Productos</h2>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Código</th>
                <th>Precio</th>
                <th>Existencia</th>
                <th>Foto</th>
                <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                <th>Acciones</th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../conexion_db.php';
            $query=mysqli_query($conexion,"SELECT * FROM producto WHERE estado = 1 ORDER BY descripcion ASC");

            mysqli_close($conexion);
            $result=mysqli_num_rows($query);
            
            if ($result>0) {
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['foto'] != 'img_producto.png') {
                        $foto = '../img/uploads/'.$data['foto'];
                    }
                    else {
                        $foto = '../img/'.$data['foto'];
                    }
        ?>
            <tr>
                <td><?php echo $data['descripcion']?></td>
                <td><?php echo $data['codigo']?></td>
                <td><?php echo "$".$data['precio']?></td>
                <td><?php echo $data['existencia']?></td>
                <td><img src="<?php echo $foto?>" width="100" height="100" alt="<?php echo $data['descripcion']?>"></td>
                <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                <td> <a href="editar_productos.php?id=<?php echo $data['idproducto']?>"><button class="btn btn-warning"><i class="fas fa-edit"></i></i> Editar</button></a> 
                <a href="eliminar_productos.php?id=<?php echo $data['idproducto']?>"><button class="btn btn-danger" onclick='return confirmeliminar()'><i class="fas fa-trash-alt"></i> Eliminar</button></a>
                <a href="registro_compras.php?id=<?php echo $data['idproducto']?>"><button class="btn btn-success"><i class="fas fa-plus-square"></i> Agregar</button></a></td>
                <?php } ?>
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
                <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
                <th></th>
                <?php } ?>
            </tr>
        </tfoot>
    </table>
    <?php include 'includes/paginacion.php'?>
	</section>

    <img src="" alt="" sizes="" srcset="">
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
</html>
<script type="text/javascript">
	function confirmeliminar()
    {
        var respuesta = confirm("¿Estas seguro que deseas eliminar el Producto?");
        if (respuesta == true){
            return true;
        }
        else{
            return false;
        }

    }
</script>