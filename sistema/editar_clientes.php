<?php
session_start();

    include '../conexion_db.php';

if(!empty($_POST)){
    $alert="";
    if(empty($_POST['nombre']) || empty($_POST['cuit']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
   
    else {
            $idcliente = $_POST['id'];
            $nombre = $_POST['nombre'];
            $cuit = $_POST['cuit'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];

            $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE cuit = '$cuit' AND idcliente != '$idcliente'");
           
            $result = mysqli_fetch_array($query);
            $result = count((array)$result);

            if ($result > 0) {
                echo '<script language="javascript">alert("El número de CUIT / CUIL ya existe");</script>';
            }

            else {
                    $sql_update=mysqli_query($conexion, "UPDATE cliente SET nombre='$nombre', cuit='$cuit', telefono='$telefono', direccion='$direccion'WHERE idcliente='$idcliente'" );                }
                    
                    if ($sql_update) {
                        echo '<script language="javascript">alert("Cliente actualizado correctamente");</script>';
   
                    } 
                    else {
                        echo '<script language="javascript">alert("Error al actualizar el cliente");</script>';
                    }         
            }
    }

    if (empty($_REQUEST['id'])) {
        header('location: lista_clientes.php');
        mysqli_close($conexion);
    }
   
    $idclient = $_REQUEST['id'];
    $sql=mysqli_query($conexion,"SELECT * FROM cliente WHERE idcliente = $idclient AND estado = 1");
    mysqli_close($conexion);
    $result_sql=mysqli_num_rows($sql);
   
    if ($result_sql==0) {
        header('location: lista_clientes.php');
    }
    else {
        while ($data=mysqli_fetch_array($sql)) {
            $idclient = $data['idcliente'];
            $nombre = $data['nombre'];
            $cuit = $data['cuit'];
            $telefono = $data['telefono'];
            $domicilo = $data['direccion'];
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

    <title>Editar Clientes</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:450px">	
		<h2><i class="fas fa-edit"></i> Editar Clientes</h2>
        <form action="" method="POST">
            <input type="hidden" id="idusuario" name="id" value="<?php echo $idclient?>">
            <div class="form-group">
                <label for="nombre">Nombre / Razón Social</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder=">Nombre / Razón Social" value="<?php echo $nombre?> "required>
            </div>
            
            <div class="form-group">
                <label for="cuit">CUIT / CUIL</label>
                <input type="number" class="form-control" id="cuit" name="cuit"  placeholder="CUIT / CUIL" value="<?php echo $cuit?>" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $telefono?>" required>
            </div>
           
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Dirección" value="<?php echo $domicilo?>" required>
            </div>
           
            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">

            <small class="text-uppercase text-danger"><?php echo isset($alert) ? $alert:""?></small>
            <a href="lista_clientes.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
<br>
</html>