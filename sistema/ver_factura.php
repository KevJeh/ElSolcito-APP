<?php session_start();
  include '../conexion_db.php';

if (empty($_GET['nofactura'])) {
    header('location: lista_clientes.php');
}

$nofactura = $_GET['nofactura'];
$sql=mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.totalfactura, f.codcliente, f.estado, u.nombre as vendedor, cl.nombre as cliente 
                                FROM factura f 
                                INNER JOIN usuario u ON f.usuario = u.idusuario 
                                INNER JOIN cliente cl ON f.codcliente = cl.idcliente 
                                WHERE f.nofactura=$nofactura and f.estado != 10 ORDER BY f.fecha DESC");
mysqli_close($conexion);
$result_sql=mysqli_num_rows($sql);

if ($result_sql==0) {
    header('location: lista_ventas.php');
}

else {
    while ($data=mysqli_fetch_array($sql)) {
        $nofactura = $data['nofactura'];
        $fecha = $data['fecha'];
        $cliente = $data['cliente'];
        $vendedor = $data['vendedor'];
        $totalfactura = $data['totalfactura'];
        $estado = $data ['estado'];
        $idcliente = $data['codcliente'];
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Facturas</title>
<?php 
include 'includes/scripts.php'
?>
</head>
<header><?php include 'includes/header.php'?></header>
<body>
<section class="container">
    <h2><i class="fas fa-edit"></i>Comprobante</h2>
    <form action="" method="POST">
        
        <div class="form-group">
            <label for="nofactura">Número de comprobante</label>
            <input type="text" class="form-control" id="nofactura" name="nofactura"  value="<?php echo $nofactura?> "readonly>
        </div>
        
        
        <div class="form-group">
            <label for="fecha">Fecha de facturación</label>
            <input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha?> "readonly>
        </div>
        
        <div class="form-group">
            <label for="cliente">Cliente</label>
            <input type="text" class="form-control" id="cliente" name="cliente"  value="<?php echo $cliente?>" readonly>
        </div>

        <div class="form-group">
            <label for="vendedor">Teléfono</label>
            <input type="text" class="form-control" id="vendedor" name="vendedor" value="<?php echo $vendedor?>" readonly>
        </div>
       
        <div class="form-group">
            <label for="totalfactura">Dirección</label>
            <input type="text" class="form-control" id="totalfactura" name="totalfactura"  value="$<?php echo $totalfactura?>" readonly>
        </div>
       
        <div class="form-group">
        
        <small class="text-uppercase text-danger"><?php echo isset($alert) ? $alert:""?></small>
        <a href="lista_ventas.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
        <a id="btn_procesar" onclick="verFactura()" class="btn btn-success"><i class="fas fa-print"></i> Imprimir</a>
        <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2)  {
                    if ($estado == 1) {?>
         <a id="btn_anular_factura" onclick="confirmeAnular()" class="btn btn-danger"><i class="far fa-times-circle"></i> Anular</a> 
            <?php }}?>


        </div>
    </form>

</section>

</body>
<footer><?php include 'includes/footer.php'?></footer>
<br>
</html>

<script type="text/javascript">
    function confirmeAnular(){   
        var respuesta = confirm("¿Estas seguro que deseas anular la factura Nº <?php echo $nofactura?>");
        if (respuesta == true){
            anularFactura();
        }
        else{
            return false;
        }

    }

    function anularFactura(){
        var accion = "anular_factura";
        var nofactura = "<?php echo $nofactura?>";
        $.ajax({
                type:"POST",
                url:"ajax.php",
                data: {nofactura:nofactura, accion:accion},
                asiync:true,
                success:function(r){
                
                    if (r!='error') {

                        alert("Factura anulada Exitosamante!!");
	                    window.location.href='lista_ventas.php';
                        
                    }
                    else{
                        alert("Error al anular la factura")
                    }
                }
            });

    };
    function verFactura(){
        var nofactura = "<?php echo $nofactura?>";
        var idcliente = "<?php echo $idcliente?>";
      
        var ancho = 1000;
        var alto = 800;

        var x = parseInt((window.screen.width/2) - (ancho/2));
        var y = parseInt((window.screen.height/2) - (alto/2));

        $url = 'factura/generaFactura.php?cl='+idcliente+'&f='+nofactura;
        window.open($url, "Factura", "lefth="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");

    }
</script>