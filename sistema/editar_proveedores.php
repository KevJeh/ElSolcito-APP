<?php
session_start();
if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header('location: ../');
}

    include '../conexion_db.php';

if(!empty($_POST)){
    if(empty($_POST['proveedor']) || empty($_POST['cuit']) || empty($_POST['contacto']) || empty($_POST['telefono']) ||  empty($_POST['direccion'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
   
    else {
            $idproveedor = $_POST['id'];
            $proveedor = $_POST['proveedor'];
            $cuit = $_POST['cuit'];
            $contacto = $_POST['contacto'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            $query = mysqli_query($conexion, "SELECT * FROM proveedor WHERE cuit = '$cuit' AND idproveedor != '$idproveedor'");
           
            $result = mysqli_fetch_array($query);
            $result = count((array)$result);

            if ($result > 0) {
                echo '<script language="javascript">alert("El Número de CUIT ya existe");</script>';
            }

            else {
                    $sql_update=mysqli_query($conexion, "UPDATE proveedor SET proveedor='$proveedor', cuit='$cuit', contacto='$contacto', telefono='$telefono', direccion='$direccion'WHERE idproveedor='$idproveedor'" );                }
                    
                    if ($sql_update) {
                        echo '<script language="javascript">alert("Proveedor creado correctamente");</script>';
   
                    } 
                    else {
                        echo '<script language="javascript">alert("Error al crear el Proveedor");</script>';
                    }         
            }
    }

    if (empty($_REQUEST['id'])) {
        header('location: lista_proveedores.php');
        mysqli_close($conexion);
    }
   
    $idproveedor = $_REQUEST['id'];
    $sql=mysqli_query($conexion,"SELECT * FROM proveedor WHERE idproveedor = $idproveedor AND estado = 1");
    mysqli_close($conexion);
    $result_sql=mysqli_num_rows($sql);
   
    if ($result_sql==0) {
        header('location: lista_proveedores.php');
    }
    else {
        while ($data=mysqli_fetch_array($sql)) {
            $idproveedor = $data['idproveedor'];
            $proveedor = $data['proveedor'];
            $cuit = $data['cuit'];
            $contacto = $data['contacto'];
            $telefono = $data['telefono'];
            $direccion = $data['direccion'];
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

    <title>Editar Proveedores</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:550px">	
		<h2><i class="fas fa-building"></i> Editar Proveedor</h2>
        <form action="" method="POST">
            <input type="hidden" id="idproveedor" name="id" value="<?php echo $idproveedor?>">
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <input type="text" class="form-control" id="proveedor" name="proveedor" placeholder="Nombre Proveedor" value="<?php echo $proveedor?>" required>
            </div>
            <div class="form-group">
                <label for="cuit">CUIT</label>
                <input type="number" class="form-control" id="cuit" name="cuit" placeholder="Nombre Proveedor" value="<?php echo $cuit?>" required>
            </div>
            <div class="form-group">
                <label for="contacto">Contacto</label>
                <input type="text" class="form-control" id="contacto" name="contacto"  placeholder="Nombre completo contacto" value="<?php echo $contacto?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Número de teléfono" value="<?php echo $telefono?>" required>
            </div>
           
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion"  placeholder="Dirección" value="<?php echo $direccion?>" required>
            </div>

            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">

            <small class="form-text text-muted"><?php echo isset($alert) ? $alert:""?></small>
            <a href="lista_proveedores.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
<br>
</html>