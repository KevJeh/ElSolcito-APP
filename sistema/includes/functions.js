//Buscar Clientes en el módulo de ventas
$(document).ready(function() {
 
    $('#cliente').select2();
    recargarLista();
    $('#cliente').change(function(e){
        e.preventDefault();
        recargarLista();
    });

//Buscar Productos en el módulo de ventas
  
    $('#txt_descripcion').select2();
    recargarProducto();
    $('#txt_descripcion').change(function(e){
        e.preventDefault();
        recargarProducto();
    });
    
//Validar y calcular productos
    $("#agregar_producto").hide();
    $("#txt_cant_producto").keyup(function(e){
        e.preventDefault();
    var precio_total = $(this).val() * $("#txt_precio").html();
    $("#txt_precio_total").html(precio_total);
    
    if($(this).val() < 1 || isNaN($(this).val()) || $(this).val()> parseInt($("#txt_existencia").html())){
        $("#agregar_producto").hide();
    }
    else{
        $("#agregar_producto").show();
    }

})
//Agregar producto
$("#agregar_producto").click(function(e){
    e.preventDefault()
    var idproducto = $("#idproducto").val();
    var cantidad = $("#txt_cant_producto").val();
    
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data: {idproducto:idproducto, cantidad:cantidad},
        asiync:true,
        success:function(r){
 
        var info = JSON.parse(r);
  
        $("#detalle_venta").html(info.detalles);
        $("#total_venta").html(info.totales);
        
        $('#txt_descripcion').val("0"); 
        
        }

    });

});

$("#btn_anular").click(function(e){
    e.preventDefault()
    var rows = $('#detalle_venta').length;
    if (rows > 0 ) {
     var accion = 'anular_venta';
        $.ajax({
            type:"POST",
            url:"ajax.php",
            data: {accion:accion},
            asiync:true,
            success:function(r){
               
            if (r!='error') {
               location.reload();
            }
        
            }
        });
    } 
    });


$("#btn_procesar").click(function(e){
    e.preventDefault()
        var rows = $('#detalle_venta').length;
        if (rows > 0 ) {
         var accion = 'procesar_venta';
         var id_cliente = $('#idcliente').val();
                
         $.ajax({
                type:"POST",
                url:"ajax.php",
                data: {accion:accion, id_cliente:id_cliente},
                asiync:true,
                success:function(r){       
                if (r!='error') {
                    var info = JSON.parse(r);    
                    generarPDF(info.codcliente, info.nofactura);
                    location.reload();
                    console.log(info);
                }
            
                }
            });
        } 
        });
});

function generarPDF(cliente,factura) {
    var ancho = 1000;
    var alto = 800;

    var x = parseInt((window.screen.width/2) - (ancho/2));
    var y = parseInt((window.screen.height/2) - (alto/2));

    $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
    window.open($url, "Factura", "lefth="+x+",top="+y+",height="+alto+",width="+ancho+",scrollbar=si,location=no,resizable=si,menubar=no");
            
}

function recargarLista(){
    var valorSelector = $('#cliente').val();
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data:"cliente=" + valorSelector,
        asiync:true,
        success:function(r){
            var data =$.parseJSON(r);
            $("#idcliente").val(data.idcliente);
            $("#cuit").val(data.cuit);
            $("#telefono").val(data.telefono);
            $("#direccion").val(data.direccion);
        }
    });
}

function recargarProducto(){
    var valorProducto = $('#txt_descripcion').val();
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data:"producto=" + valorProducto,
        asiync:true,
        success:function(r){
            if (r!=0) {
            var info =$.parseJSON(r);
            $("#idproducto").val(info.idproducto);
            $("#txt_cod_producto").html(info.codigo);
            $("#txt_existencia").html(info.existencia);
            $("#cuit").html(info.cuit);
            $("#txt_precio").html(info.precio);
            }
        }
    });
}

function buscarDetalle(id){
    var iduser = id;
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data: {iduser:iduser},
        asiync:true,
        success:function(r){
            if (r!='error') {
                var info = JSON.parse(r);
                $("#detalle_venta").html(info.detalles);
                $("#total_venta").html(info.totales);
            } else {
                $("#detalle_venta").html("");
                $("#total_venta").html(""); 
            }


        }

    });

}


function del_product_detalle(correlativo) {
    var id_detalle = correlativo;
    $.ajax({
        type:"POST",
        url:"ajax.php",
        data: {id_detalle:id_detalle},
        asiync:true,
        success:function(r){

            if (r!='error') {
                var info = JSON.parse(r);
                $("#detalle_venta").html(info.detalles);
                $("#total_venta").html(info.totales);
            } else {
                $("#detalle_venta").html("");
                $("#total_venta").html(""); 
            }
        }

    });
   
}