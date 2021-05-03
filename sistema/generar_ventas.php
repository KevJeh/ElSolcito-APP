    <?php session_start();?>
    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/estiloregistros.css" rel="stylesheet">

    <title>Ventas</title>
	<?php 
    include 'includes/scripts.php'
    ?>
</head>
<header><?php include 'includes/header.php'?></header>
<body style="background-color:#dbedf3" >

    <h2 class="text-center"><i class="fas fa-file-invoice-dollar"></i> Ventas</h2>
        <hr>
        <section class="container">
        <h4>Datos del Cliente</h4>
            <form name="form_nuevo_cliente_venta" id="form_nuevo_cliente_venta">
                <input type="hidden" name="idcliente" id="idcliente" >
            
                <div class="row">
                    <?php                        
                        include '../conexion_db.php';
                    $query_rol = mysqli_query($conexion, "SELECT * FROM cliente WHERE estado=1");
                    mysqli_close($conexion);
                                
                    $result_rol = mysqli_num_rows($query_rol);
                    
                    ?>
                        <div class="col">
                            <label for="cliente">Nombre</label><br>
                            <select class="custom-select" id="cliente" name="cliente">
                            <option value="0" disabled="disabled" selected>Seleccionar Cliente</option>
                            <?php   
                            if($result_rol > 0){
                                while ($cliente = mysqli_fetch_array($query_rol)){
                            ?> 
                            <option  value=<?php echo $cliente['idcliente'] ?>><?php echo $cliente['nombre']?></option>
                            <?php
                                }
                            }
                            ?>
                            </select>
                        </div>

                    <div class="col" id="vistaCuit">
                        <label for="cuit">CUIT / CUIL</label>
                        <input type="number" class="form-control" id="cuit" name="cuit" placeholder="Número de CUIT/CUIL" readonly>
                    </div>


                    <div class="col">
                        <label for="telefono">Teléfono</label>
                        <input type="number" class="form-control" id="telefono" name="telefono" placeholder="Número de teléfono"  readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion"  placeholder="Dirección" readonly>
                </div>
            </form>
        </section>
        <hr>
        <section class="container">
            <h4>Datos de Venta</h4>
                <div class="row">
                    <div class="col">
                        <label>Vendedor:</label>
                        <input type="text" class="form-control"value=<?php echo $_SESSION['nombre']?> readonly>
                    </div>
                    <div class="col">
                        <label>Acciones:</label>
                        <div class="d-flex justify-content-around">
                        <a id="btn_anular" class="btn btn-danger"><i class="far fa-times-circle"></i> Anular</a> 
                        <a id="btn_procesar" class="btn btn-success"><i class="fas fa-check"></i> Procesar</a>
                        </div>
                    </div>
                </div>
            </div>
            </section>
            <hr>
            <section class="container">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Código</th>
                <th>Descripción</th>
                <th>Existencia</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <TBody> 
            <tr>
                <input type="hidden" name="idproducto" id="idproducto" >
                <td id="txt_cod_producto"></td>

                <?php                        
                        include '../conexion_db.php';
                    $query_rol = mysqli_query($conexion, "SELECT * FROM producto WHERE estado = 1");
                    mysqli_close($conexion);
                                
                    $result_rol = mysqli_num_rows($query_rol);
                    
                    ?>
                        <div class="col">
                            <td> <select class="custom-select" name="txt_descripcion" id="txt_descripcion">
                            <option value="0" disabled="disabled" selected>Seleccionar Producto</option>
                            <?php   
                            if($result_rol > 0){
                                while ($cliente = mysqli_fetch_array($query_rol)){
                            ?> 
                            <option  value=<?php echo $cliente['idproducto'] ?>><?php echo $cliente['descripcion']?></option>
                            <?php
                                }
                            }
                            ?>
                            </select></td>
                        </div>
                <td id="txt_existencia"></td>
                <td><input type="text" name="txt_cant_producto" id="txt_cant_producto"></td>
                <td id="txt_precio"></td>
                <td id="txt_precio_total"></td>
                <td><a href="#"><button id="agregar_producto" class="btn btn-outline-success"><i class="fas fa-plus-square"></i> Agregar</button></a></td>
            </tr>
        </TBody>
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
    </section>
    <HR>
    <section class="container">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
                <th>Acción</th>
            </tr>
        </thead>
        <TBody id="detalle_venta">
            <!-- CONTENIDO AJAX -->
        </TBody>
        <tfoot id="total_venta">
            <!-- CONTENIDO AJAX -->
        </tfoot>
    </table>
    </section>
    <script type="text/javascript">
    $(document).ready(function() {
        var iduser = '<?php echo $_SESSION['idusuario'];?>';
        buscarDetalle(iduser);
    });
    </script>
    <script src="../sistema/includes/functions.js"></script>

</body>
<footer><?php include 'includes/footer.php'?></footer>
</html>
