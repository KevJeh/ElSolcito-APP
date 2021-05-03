<?php
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header('location: ../');
}
    include '../conexion_db.php';
    
if(!empty($_POST)){
    if(empty($_POST['descripcion']) || empty($_POST['codigo'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }

    else { 
            $idproducto = $_POST['id'];
            $descripcion = $_POST['descripcion'];
            $codigo = $_POST['codigo'];

            if (is_numeric($codigo)) {
                $query = mysqli_query($conexion, "SELECT * FROM producto WHERE codigo = '$codigo' AND idproducto != '$idproducto'");
                $result = mysqli_fetch_array($query);
                $result = count((array)$result);
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
                
                $sql_update= mysqli_query($conexion, "UPDATE producto SET descripcion='$descripcion', codigo = '$codigo', foto ='$img_producto' WHERE idproducto = '$idproducto'");

                if ($sql_update) {
                    if ($nombre_foto != '') {
                    move_uploaded_file($url_temp, $src);
                    }
                    echo '<script language="javascript">alert("Producto modificado correctamente");</script>';

                } 
                else {
                    echo '<script language="javascript">alert("Error al modificar el Producto");</script>';

                    }   
            }    
        }
 
}
    if (empty($_REQUEST['id'])) {
        header('location: lista_productos.php');
        mysqli_close($conexion);
    }
   
    $idproducto = $_REQUEST['id'];
    $sql=mysqli_query($conexion,"SELECT * FROM producto WHERE idproducto = $idproducto AND estado = 1");
    mysqli_close($conexion);
    $result_sql=mysqli_num_rows($sql);
   
    if ($result_sql==0) {
        header('location: lista_productos.php');
    }
    else {
        while ($data=mysqli_fetch_array($sql)) {
            $idproducto = $data['idproducto'];
            $descripcion = $data['descripcion'];
            $codigo = $data['codigo'];
            $precio = $data['precio'];
            $existencia = $data['existencia'];
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estiloregistros.css" rel="stylesheet">

    <title>Editar Productos</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:550px">		
		<h2><i class="fas fa-archive"></i> Editar Productos</h2>
        <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" id="idproducto" name="id" value="<?php echo $idproducto?>">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción del producto" value="<?php echo $descripcion?>" required>
            </div>
            <div class="form-group">
                <label for="codigo">Código de producto</label>
                <input type="text" class="form-control" id="codigo" name="codigo"  placeholder="Código del producto" value="<?php echo $codigo?>" required>
            </div>

            <div class="form-group">
                <label for="precio">Precio $</label>
                <input type="number" step=0.01 class="form-control" id="precio" name="precio" value="<?php echo $precio?>" readonly>
            </div>

            <div class="form-group">
                <label for="existencia">Existencia</label>
                <input type="number" class="form-control" id="existencia" name="existencia" value="<?php echo $existencia?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="foto">Seleccionar Imagen</label><br>
                <input type="file"  id="foto" name="foto">  
            </div>
            <br>
            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">

            <a href="lista_productos.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
</html>

