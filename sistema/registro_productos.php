<?php

session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header('location: ../');
}
    include '../conexion_db.php';
    
if(!empty($_POST)){
    if(empty($_POST['descripcion']) || empty($_POST['codigo']) ||  empty($_POST['precio'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
    else { 
            $descripcion = $_POST['descripcion'];
            $codigo = $_POST['codigo'];
            $precio = $_POST['precio'];
            $id_usuario = $_SESSION['idusuario'];

            if (is_numeric($codigo)) {
                $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo'");
                $result = mysqli_fetch_array($query);
            }
            if ($result > 0) {
                echo '<script language="javascript">alert("Ya existe un producto con este código");</script>';

            }
            else {
         
                $foto = $_FILES['foto'];

                $nombre_foto = $foto['name'];
                $type = $foto['type'];
                $url_temp = $foto['tmp_name'];

                $img_producto = 'img_producto.png';

                if ($nombre_foto != '') {
                    $destino = $_SERVER['DOCUMENT_ROOT'].'/facturacion/img/uploads/';
                    $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
                    $img_producto = $img_nombre.'.jpg';
                    $src = $destino.$img_producto;
                }
                $query_insert = mysqli_query($conexion, "INSERT INTO producto (descripcion, codigo, precio, usuario_id, foto) 
                                                            VALUES ('$descripcion', '$codigo', '$precio', '$id_usuario', '$img_producto')");
                
                if ($query_insert) {
                    if ($nombre_foto != '') {
                    move_uploaded_file($url_temp, $src);
                    }
                    echo '<script language="javascript">alert("Producto creado correctamente");</script>';
                } 
                else {
                    echo '<script language="javascript">alert("Error al modificar el Producto");</script>';
                    }   
            }
        }
        mysqli_close($conexion);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estiloregistros.css" rel="stylesheet">

    <title>Registro de Productos</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:475px">		<h2><i class="fas fa-archive"></i> Registro de Productos</h2>
        <form action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del producto" required>
            </div>
            <div class="form-group">
                <label for="codigo">Código de producto</label>
                <input type="text" class="form-control" id="codigo" name="codigo"  placeholder="Código del producto" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio $</label>
                <input type="number" step=0.01 class="form-control" id="precio" name="precio" placeholder="Precio de venta del producto" required>
            </div>
           
            <div class="form-group">
                <label for="foto">Seleccionar Imagen</label><br>
                <input type="file"  id="foto" name="foto">  
            </div>
            <br>
            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">
            <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
</html>
