<?php
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)  {
    header('location: ../'); 
}
    include '../conexion_db.php';

if(!empty($_POST)){
    $alert="";
    if(empty($_POST['id']) || empty($_POST['precio']) || empty($_POST['cantidad']) || empty($_POST['proveedor']) || empty($_POST['observacion']))  {
       echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';

    }
   
    else {

            $idproducto = $_POST['id'];

            $query=mysqli_query($conexion,"SELECT * FROM producto WHERE estado = 1");
            $data = mysqli_fetch_array($query);
           
            $descripcion = $_POST['descripcion'];
            $proveedor_id = $_POST['proveedor'];
            $codigo = $_POST['codigo'];
            $precio = $_POST['precio'];
            $existencia = $_POST['existencia'];
            $cantidad = $_POST['cantidad'];
            $id_usuario = $_SESSION['idusuario'];
            $observacion = $_POST['observacion'];


            if (empty($idproducto)) {
                $header('location: lista_productos.php');
            }
            else{
            $query = mysqli_query($conexion, "SELECT * FROM producto WHERE descripcion = '$descripcion' AND idproducto != '$idproducto'");
           
            $result = mysqli_fetch_array($query);
            $result = count((array)$result);


            if ($result > 0) {
                $header('location: lista_productos.php');
            }
            else { 

                    $aumento =  $existencia + $cantidad;

                    $sql_update = mysqli_query($conexion, "UPDATE producto SET existencia = $aumento, precio='$precio' WHERE idproducto='$idproducto'" );
                    if ($sql_update) {
                        $query_insert = mysqli_query($conexion, "INSERT INTO entradas(idproducto, cantidad, precio, proveedor_id, usuario_id, observacion) 
                        VALUES ('$idproducto', '$cantidad', '$precio', '$proveedor_id', '$id_usuario', '$observacion')");   
                                            
                                            if ($query_insert) {
                                                $existencia = 0;
                                                $cantidad = 0;
                                                echo '<script language="javascript">alert("Stock actulizado correctamente");</script>';
                                            } 
                                            else {
                                                echo '<script language="javascript">alert("Error al generar el ingreso");</script>';
                                            }   
                    }
                    else {
                        echo '<script language="javascript">alert("Error al actualizar el stock");</script>';
                    }   
             }
                    
            }
                    
                  
        }
    }

    if (empty($_REQUEST['id'])) {
        header('location: lista_productos.php');
        mysqli_close($conexion);
    }
   
    $idproduc = $_REQUEST['id'];
    $sql=mysqli_query($conexion,"SELECT * FROM producto WHERE idproducto = $idproduc AND estado = 1");
    mysqli_close($conexion);
    $result_sql=mysqli_num_rows($sql);
   
    if ($result_sql==0) {
        header('location: lista_productos.php');
    }
    else {
        while ($data=mysqli_fetch_array($sql)) {
            $idproduc = $data['idproducto'];
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

    <title>Registro de Compras</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:700px">		


		<h2><i class="fas fa-edit"></i> Cargar Compra</h2>
        <form action="" method="POST">
            <input type="hidden" id="idproducto" name="id" value="<?php echo $idproduc?>">
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion"  value="<?php echo $descripcion?> "readonly>
            </div>
            
            <div class="form-group">
                <label for="codigo">Código</label>
                <input type="number" class="form-control" id="codigo" name="codigo" value="<?php echo $codigo?>" readonly>
            </div>
           
            <div class="form-group">
                <label for="precio">Precio</label>
                <input type="number" step=0.01 class="form-control" id="precio" name="precio" value="<?php echo $precio?>">
            </div>
           
            <div class="form-group">
                <label for="existencia">Existencia Actual</label>
                <input type="number" class="form-control" id="existencia" name="existencia"  value="<?php echo $existencia?>" readonly>
            </div>

            <div class="form-group">
                <label for="cantidad">Cantidad a agregar</label>
                <input type="number" class="form-control" id="cantidad" placeholder="Cantidad a agregar" name="cantidad">
            </div>
            <?php                        
           include '../conexion_db.php';
           $query_prov = mysqli_query($conexion, "SELECT * FROM proveedor WHERE estado = 1 ORDER BY proveedor ASC");
           mysqli_close($conexion);     
           $result_prov = mysqli_num_rows($query_prov);
           
           ?>
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <select class="form-control" id="proveedor" name="proveedor">
                <?php   
                if($result_prov > 0){
                    while ($proveedor = mysqli_fetch_array($query_prov)){
                ?>  
                <option value=<?php echo $proveedor['idproveedor'] ?>> <?php echo $proveedor['proveedor'] ?> </option>
                <?php
                    }
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="observacion">Observación</label>
                <input type="text" class="form-control" id="observacion" placeholder="Ejemplo: Nº de Remito" name="observacion">
            </div>



            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">

            <small class="text-uppercase text-danger"><?php echo isset($alert) ? $alert:""?></small>
            <a href="lista_productos.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
<br>
</html>