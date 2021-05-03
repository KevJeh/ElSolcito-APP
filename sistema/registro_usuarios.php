<?php
session_start();
if($_SESSION['rol'] != 1) {
    header('location: ../');
}
    
    include '../conexion_db.php';

if(!empty($_POST)){
    if(empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['usuario']) || empty($_POST['password']) || empty($_POST['rol'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
   
    else {
            $nombre = $_POST['nombre'];
            $correo = $_POST['email'];
            $usuario = $_POST['usuario'];
            $clave = md5($_POST['password']);
            $rol = $_POST['rol'];

            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$usuario'");
           
            $result = mysqli_fetch_array($query);

            if ($result > 0) {
                    echo '<script language="javascript">alert("Usuario creado correctamente");</script>';
            }
        
            else {
                $query_insert = mysqli_query($conexion, "INSERT INTO usuario(nombre,correo,usuario,clave,rol) VALUES ('$nombre','$correo','$usuario','$clave','$rol')");
                if ($query_insert) {
                    echo '<script language="javascript">alert("Usuario creado correctamente");</script>';
                } 
                else {
                    echo '<script language="javascript">alert("Error al crear el usuario");</script>';
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

    <title>Registro de Usuarios</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 700px; height:525px">		<h2><i class="fas fa-users"></i> Registro de Usuarios</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="nombre">Apellido y Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Apellido y Nombre" required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email"  placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="usuario">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombre de Usuario" required>
            </div>
           
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password"  placeholder="Contraseña" required>
            </div>
           
           <?php                        
           include '../conexion_db.php';
           $query_rol = mysqli_query($conexion, "SELECT * FROM rol");
           mysqli_close($conexion);
                    
           $result_rol = mysqli_num_rows($query_rol);
           
           
           ?>
            <div class="form-group">
                <label for="rol">Tipo de Cuenta</label>
                <select class="form-control" id="rol" name="rol">
                <option value="" disabled="disabled" selected>Seleccionar Rol</option>
                <?php   
                if($result_rol > 0){
                    while ($rol = mysqli_fetch_array($query_rol)){
                ?>  
                <option value=<?php echo $rol['idrol'] ?>> <?php echo $rol['rol'] ?> </option>
                <?php
                    }
                }
                ?>
                </select>
            </div>

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
