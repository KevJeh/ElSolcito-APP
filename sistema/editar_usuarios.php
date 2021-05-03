<?php
session_start();
if($_SESSION['rol'] != 1 ) {
    header('location: ../'); 
}
    include '../conexion_db.php';

if(!empty($_POST)){
    $alert="";
    if(empty($_POST['nombre']) || empty($_POST['email']) || empty($_POST['usuario']) || empty($_POST['rol'])) {
        echo '<script language="javascript">alert("Todos los campos son obligatorios");</script>';
    }
   
    else {
            $idusuario = $_POST['id'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['email'];
            $usuario = $_POST['usuario'];
            $clave = md5($_POST['password']);
            $rol = $_POST['rol'];

            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario = '$usuario' AND idusuario != '$idusuario'");
           
            $result = mysqli_fetch_array($query);
            $result = count((array)$result);

            if ($result > 0) {
                echo '<script language="javascript">alert("El nombre de usuario ya existe");</script>';

            }
            else {
                if (empty($_POST['password'])) {
                    $sql_update=mysqli_query($conexion, "UPDATE usuario SET nombre='$nombre', correo='$correo', usuario='$usuario', rol='$rol' WHERE idusuario='$idusuario'" );
                }

                else {
                    $sql_update=mysqli_query($conexion, "UPDATE usuario SET nombre='$nombre', correo='$correo', usuario='$usuario', clave='$clave', rol='$rol' WHERE idusuario='$idusuario'" );                }
                    
                    if ($sql_update) {
                        echo '<script language="javascript">alert("Usuario actualizado correctamente");</script>';
   
                    } 
                    else {
                        echo '<script language="javascript">alert("Error al actualizar el usuario");</script>';
                    }       
                    
                }
        }
    }

    if (empty($_REQUEST['id'])) {
        header('location: lista_usuarios.php');
        mysqli_close($conexion);
    }
   
    $iduser = $_REQUEST['id'];
    $sql=mysqli_query($conexion,"SELECT u.idusuario, u.nombre, u.correo, u.usuario, (u.rol) as idrol, (r.rol) as rol FROM usuario u INNER JOIN rol r ON u.rol=r.idrol WHERE idusuario = $iduser AND estado = 1");
    mysqli_close($conexion);
    $result_sql=mysqli_num_rows($sql);
   
    if ($result_sql==0) {
        header('location: lista_usuarios.php');
    }
    else {
        while ($data=mysqli_fetch_array($sql)) {
            $iduser = $data['idusuario'];
            $nombre = $data['nombre'];
            $correo = $data['correo'];
            $usuario = $data['usuario'];
            $idrol = $data['idrol'];
            $rol = $data['rol'];
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

    <title>Editar Usuarios</title>
	<?php 
    include 'includes/scripts.php'?>

</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
<section class="container" style="width: 750px; height:550px">	

		<h2><i class="fas fa-edit"></i> Editar Usuarios</h2>
        <form action="" method="POST">
            <input type="hidden" id="idusuario" name="id" value="<?php echo $iduser?>">
            <div class="form-group">
                <label for="nombre">Apellido y Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Apellido y Nombre" value="<?php echo $nombre?> "required>
            </div>
            
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" class="form-control" id="email" name="email"  placeholder="Email" value="<?php echo $correo?>"  required>
            </div>

            <div class="form-group">
                <label for="usuario">Nombre de Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Nombre de Usuario" value="<?php echo $usuario?>">
            </div>
           
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password"  placeholder="Contraseña">
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
            <a href="lista_usuarios.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Guardar</button>
            
            </div>
        </form>
    
    </section>
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
<br>
</html>