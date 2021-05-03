<?php
session_start();
    include '../conexion_db.php';
    
if(!empty($_POST)){
    if(empty($_POST['cuit']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion'])) {
       echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
    else {
            $cuit = $_POST['cuit'];
            $nombre = $_POST['nombre'];
            $telefono = $_POST['telefono'];
            $direccion = $_POST['direccion'];
            $id_usuario = $_SESSION['idusuario'];
            $result = 0;

            if (is_numeric($cuit)) {
                $query = mysqli_query($conexion, "SELECT * FROM cliente WHERE cuit = '$cuit'");
                $result = mysqli_fetch_array($query);
            }

            if ($result > 0) {
                echo '<script language="javascript">alert("El número de CUIT / CUIL ya existe");</script>';

            }
        
            else {
                $query_insert = mysqli_query($conexion, "INSERT INTO cliente(cuit,nombre,telefono,direccion,usuario_id) VALUES ('$cuit', '$nombre', '$telefono', '$direccion', '$id_usuario')");
                if ($query_insert) {
                    
             
                    echo '<script language="javascript">alert("Cliente creado correctamente");</script>';

                } 
                else {
                    echo '<script language="javascript">alert("Error al crear el cliente");</script>';
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

    <title>Registro de Clientes</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:475px">		

<h2><i class="fas fa-id-badge"></i> Registro de Clientes</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="cuit">CUIT / CUIL</label>
                <input type="number" class="form-control" id="cuit" name="cuit" placeholder="Número de CUIT/CUIL" required>
            </div>
            
            <div class="form-group">
                <label for="nombre">Nombre / Razón Social</label>
                <input type="text" class="form-control" id="nombre" name="nombre"  placeholder="Nombre / Razón Social" required>
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Número de teléfono" required>
            </div>
           
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <input type="text" class="form-control" id="direccion" name="direccion"  placeholder="Dirección" required>
            </div>

            <div class="form-group">
            <div class="d-grid gap-2 col-6 mx-auto">
            <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            </div>
        </form>
    
    </section>
</body>
<footer style="color:white" ><?php include 'includes/footer.php'?></footer>
</html>
