<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estilolistas.css" rel="stylesheet">
    <title>Lista de Ventas</title>

    <?php include 'includes/scripts.php'
    
    ?>


</head>
<header><?php include 'includes/header.php'?></header>
<body class="body">
	<section class="container">
    <h2><i class="fas fa-file-invoice-dollar"></i> Lista de Ventas</h2>
    <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Fecha y Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th>Total Factura</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php
            include '../conexion_db.php';
            $query=mysqli_query($conexion,"SELECT f.nofactura, f.fecha, f.totalfactura, f.codcliente, f.estado, u.nombre as vendedor, cl.nombre as cliente 
                                        FROM factura f 
                                        INNER JOIN usuario u ON f.usuario = u.idusuario 
                                        INNER JOIN cliente cl ON f.codcliente = cl.idcliente 
                                        WHERE f.estado != 10 ORDER BY f.fecha DESC");
            mysqli_close($conexion);
            $result=mysqli_num_rows($query);
            
            if ($result>0) {
                while ($data = mysqli_fetch_array($query)) {
                    if ($data['estado'] == 1) {
                        $estado = "<span>Pagada</span>";
                    } else {
                        $estado = "<span>Anulada</span>";
                    }
                    
        ?>
            <tr id = "row_<?php echo $data['nofactura']?>">
                <td><?php echo $data['nofactura']?></td>
                <td><?php echo $data['fecha']?></td>
                <td><?php echo $data['cliente']?></td>
                <td><?php echo $data['vendedor']?></td>
                <td><?php echo $estado?></td>
                <td><?php echo'$'.$data['totalfactura']?></td>
                <td> 
                <a href="ver_factura.php?nofactura=<?php echo $data['nofactura']?>" class="btn btn-primary" ><i class="far fa-eye"></i> Ver</a>
                </td>

            </tr>
        <?php 
                }
            }
        ?>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <?php include 'includes/paginacion.php'?>
	</section>


    
    
</body>
<footer style="color: white;"><?php include 'includes/footer.php'?></footer>
</html>