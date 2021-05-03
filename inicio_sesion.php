<?php 
$alert ='';
$alert1 ='';
$alert2 ='';
session_start();
if(!empty($_SESSION['active'])){

    header('location: sistema/');
}
else{
    if(!empty($_POST)){

        if (empty($_POST['usuario'])){
            echo '<script language="javascript">alert("El campo Usuario no puede estar vacio");</script>';

        }
        if (empty($_POST['clave'])){
            echo '<script language="javascript">alert("El campo Clave no puede estar vacio");</script>';

            }
        else{
            require_once 'conexion_db.php';
            $user = mysqli_real_escape_string($conexion, $_POST['usuario']);
            $pass = md5(mysqli_real_escape_string($conexion, $_POST['clave']));
            
            $query = mysqli_query($conexion, "SELECT * FROM usuario WHERE usuario='$user' AND clave='$pass' AND estado=1 ");
            mysqli_close($conexion);
            $result = mysqli_num_rows($query);
            

            if($result > 0){
                $data = mysqli_fetch_array($query);
                $_SESSION['active']=true;
                $_SESSION['idusuario']=$data[0];
                $_SESSION['nombre']=$data[1];
                $_SESSION['correo']=$data[2];
                $_SESSION['usuario']=$data[3];
                $_SESSION['rol']=$data[5];
                header('location: sistema/');
            }
        else {
                echo '<script language="javascript">alert("El usuario y/o la clave son incorrectos");</script>';
                session_destroy();
            }
        }
    }
}
?>