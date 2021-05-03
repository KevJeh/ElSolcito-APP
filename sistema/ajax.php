<?php 
include '../conexion_db.php';
session_start();
//buscar clientes (Ventas)
if (!empty($_POST['cliente'])) {
	$cliente=$_POST['cliente'];

	
	$sql_cliente= mysqli_query($conexion, "SELECT * from cliente where idcliente = $cliente and estado = 1");
	mysqli_close($conexion);
	$result_cliente = mysqli_num_rows($sql_cliente);
		if ($result_cliente > 0) {
			$data = mysqli_fetch_assoc($sql_cliente);
		}
		else {
			$data = 0;
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
	//buscar productos (Ventas)
if (!empty($_POST['producto'])) {
	$producto=$_POST['producto'];
	
		
	$sql_producto= mysqli_query($conexion, "SELECT * from producto where idproducto = $producto and estado = 1");
	mysqli_close($conexion);
	$result_producto = mysqli_num_rows($sql_producto);
		if ($result_producto > 0) {
				$data = mysqli_fetch_assoc($sql_producto);
		}
		else {
				$data = 0;
		}
		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}
		

	// agregar prodcuctos temporales
if (!empty($_POST['idproducto']) || !empty($_POST['cantidad'])) {
		
		$idproducto = $_POST['idproducto'];
		$cantidad = $_POST['cantidad'];
		$token=md5($_SESSION['idusuario']);
		
		$sql_iva=mysqli_query($conexion, "SELECT iva FROM configuracion");
		$resurlt_iva=mysqli_num_rows($sql_iva);

		$sql_detalle_temp = mysqli_query($conexion, "CALL add_detalle_temp($idproducto, $cantidad, '$token')");
		$result_detalle_temp = mysqli_num_rows($sql_detalle_temp);

		$detalle_tabla = "";
		$sub_total = 0;
		$iva = 0;
		$total = 0;
		$arrayData = array();

		if ($result_detalle_temp > 0){

			if ($resurlt_iva > 0){

				$info_iva = mysqli_fetch_assoc($sql_iva);
				$iva = $info_iva['iva'];	
			}
			
			while ($data = mysqli_fetch_assoc($sql_detalle_temp)) {
		
				$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
				$sub_total = round($sub_total + $precioTotal, 2);
				$total = round($total + $sub_total, 2);

				$detalle_tabla .= '            
					<tr>
						<td>'.$data['idproducto'].'</td>
						<td>'.$data['descripcion'].'</td>
						<td>'.$data['cantidad'].'</td>
						<td>'.$data['precio_venta'].'</td>
						<td>'.$precioTotal.'</td>
						<td><a class="btn btn-outline-danger" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].')"><i class="fas fa-trash-alt"></i> Eliminar</a></td>
						</tr>';
			}
			$impuesto = round($sub_total * ($iva/100), 2);
			$tl_sniva = round($sub_total - $impuesto, 2);
			$total = round($tl_sniva + $impuesto, 2);

			$detalle_totales ='
				<tr>
					<th colspan="4">SUBTOTAL</th>
					<th colspan="2">'.$tl_sniva.'</th>

				</tr>
				<tr>
					<th colspan="3">IVA</th>
					<th>'.$iva.'%</th>
					<th colspan="2">'.$impuesto.'</th>
				</tr>
				<tr>
					<th colspan="4">TOTAL</th>
					<th colspan="2">'.$total.'</th>
				</tr>
			';

			$arrayData['detalles']= $detalle_tabla;
			$arrayData['totales'] = $detalle_totales;

			echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
		}
		else {
			echo 'error';
		}
		mysqli_close($conexion);
		exit;
	}
	

		// recargar prodcuctos temporales
		
if (!empty($_POST['iduser'])) {

	$token=md5($_SESSION['idusuario']);

	$sql_temp=mysqli_query($conexion, "SELECT tmp.correlativo, tmp.token_user, tmp.cantidad, tmp.precio_venta, p.idproducto, p.descripcion FROM detalle_temp tmp INNER JOIN producto p ON tmp.idproducto = p.idproducto WHERE token_user = '$token' ORDER BY tmp.correlativo DESC");
	$result_detalle_temp = mysqli_num_rows($sql_temp);
	
	$sql_iva=mysqli_query($conexion, "SELECT iva FROM configuracion");
	$resurlt_iva=mysqli_num_rows($sql_iva);

	$detalle_tabla = "";
	$sub_total = 0;
	$iva = 0;
	$total = 0;
	$arrayData = array();

	if ($result_detalle_temp > 0){

		if ($resurlt_iva > 0){

			$info_iva = mysqli_fetch_assoc($sql_iva);
			$iva = $info_iva['iva'];	
		}
		
		while ($data = mysqli_fetch_assoc($sql_temp)) {
	
			$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
			$sub_total = round($sub_total + $precioTotal, 2);
			$total = round($total + $sub_total, 2);

			$detalle_tabla .= '            
				<tr>
					<td>'.$data['idproducto'].'</td>
					<td>'.$data['descripcion'].'</td>
					<td>'.$data['cantidad'].'</td>
					<td>'.$data['precio_venta'].'</td>
					<td>'.$precioTotal.'</td>
					<td><a class="btn btn-outline-danger" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].')"><i class="fas fa-trash-alt"></i> Eliminar</a></td>
				</tr>';
		}
		$impuesto = round($sub_total * ($iva/100), 2);
		$tl_sniva = round($sub_total - $impuesto, 2);
		$total = round($tl_sniva + $impuesto, 2);

		$detalle_totales ='
			<tr>
				<th colspan="4">SUBTOTAL</th>
				<th colspan="2">'.$tl_sniva.'</th>

			</tr>
			<tr>
				<th colspan="3">IVA</th>
				<th>'.$iva.'%</th>
				<th colspan="2">'.$impuesto.'</th>
			</tr>
			<tr>
				<th colspan="4">TOTAL</th>
				<th colspan="2">'.$total.'</th>
			</tr>
		';

		$arrayData['detalles']= $detalle_tabla;
		$arrayData['totales'] = $detalle_totales;

		echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
	}
	else {
		echo 'error';
	}
	mysqli_close($conexion);
	exit;
}
	//eliminar productos
if (!empty($_POST['id_detalle'])) {

	$id_detalle= $_POST['id_detalle'];
	$token=md5($_SESSION['idusuario']);

	
	$sql_iva=mysqli_query($conexion, "SELECT iva FROM configuracion");
	$resurlt_iva=mysqli_num_rows($sql_iva);

	$query_detalle_temp = mysqli_query($conexion, "CALL del_detalle_temp($id_detalle, '$token')");
	$result_detalle_temp= mysqli_num_rows($query_detalle_temp);

	$detalle_tabla = "";
	$sub_total = 0;
	$iva = 0;
	$total = 0;
	$arrayData = array();

	if ($result_detalle_temp > 0){

		if ($resurlt_iva > 0){

			$info_iva = mysqli_fetch_assoc($sql_iva);
			$iva = $info_iva['iva'];	
		}
		
		while ($data = mysqli_fetch_assoc($query_detalle_temp)) {
	
			$precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
			$sub_total = round($sub_total + $precioTotal, 2);
			$total = round($total + $sub_total, 2);

			$detalle_tabla .= '            
				<tr>
					<td>'.$data['idproducto'].'</td>
					<td>'.$data['descripcion'].'</td>
					<td>'.$data['cantidad'].'</td>
					<td>'.$data['precio_venta'].'</td>
					<td>'.$precioTotal.'</td>
					<td><a class="btn btn-outline-danger" href="#" onclick="event.preventDefault(); del_product_detalle('.$data['correlativo'].')"><i class="fas fa-trash-alt"></i> Eliminar</a></td>
				</tr>';
		}
		$impuesto = round($sub_total * ($iva/100), 2);
		$tl_sniva = round($sub_total - $impuesto, 2);
		$total = round($tl_sniva + $impuesto, 2);

		$detalle_totales ='
			<tr>
				<th colspan="4">SUBTOTAL</th>
				<th colspan="2">'.$tl_sniva.'</th>

			</tr>
			<tr>
				<th colspan="3">IVA</th>
				<th>'.$iva.'%</th>
				<th colspan="2">'.$impuesto.'</th>
			</tr>
			<tr>
				<th colspan="4">TOTAL</th>
				<th colspan="2">'.$total.'</th>
			</tr>
		';

		$arrayData['detalles']= $detalle_tabla;
		$arrayData['totales'] = $detalle_totales;

		echo json_encode($arrayData, JSON_UNESCAPED_UNICODE);
	}
	else {
		echo 'error';
	}
	mysqli_close($conexion);
	exit;
}

if ($_POST['accion']=="anular_venta") {
	$token = md5($_SESSION['idusuario']);
	$query_del = mysqli_query($conexion, "DELETE FROM detalle_temp WHERE token_user = '$token'");
	mysqli_close($conexion);
	if ($query_del) {
		echo "OK";
	}
	else {
		echo "error";
	}
	exit;
}

if ($_POST['accion']=="procesar_venta") {
	if (!empty($_POST['id_cliente'])) {
		
		$token = md5($_SESSION['idusuario']);
		$usuario = $_SESSION['idusuario'];
		$id_cliente = $_POST['id_cliente'];

		$query = mysqli_query($conexion, "SELECT * FROM detalle_temp WHERE token_user = '$token'");
		$result = mysqli_num_rows($query);

		if ($result> 0) {
			
			$query_procesar = mysqli_query($conexion,"CALL procesar_venta ($usuario,$id_cliente,'$token')" );
			$result_procesar = mysqli_num_rows($query_procesar);

			if ($result_procesar > 0) {
				$data = mysqli_fetch_assoc($query_procesar);
				echo json_encode($data, JSON_UNESCAPED_UNICODE);
			}
			else {
				echo "error";
			}
		}
		else {
			echo "error";
		}
		mysqli_close($conexion);
	}
	else {
		echo "error";
	};
	exit;
}

if ($_POST['accion'] == "anular_factura") {
	if (!empty($_POST['nofactura'])) {

		$nofactura = $_POST['nofactura'];
		print_r($nofactura);
		
		$query_anular= mysqli_query($conexion, "CALL anular_factura('$nofactura')");
		$result_anular = mysqli_num_rows($query_anular);
		mysqli_close($conexion);
		if ($result_anular > 0) {
			$data = mysqli_fetch_assoc($query_anular);
			echo json_encode($data, JSON_UNESCAPED_UNICODE);
		}
		else {
			echo "error";
		}

	}
	else {
		echo "error";
	}
	exit;
}



else {
	echo "error";
}

?>


