<?php
if(empty($_SESSION['active'])){

    header('location: ../');
}
?>

		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  				<a class="navbar-brand" href="index.php">Sistemas de Ventas</a>
				  <img src="" alt="">
  				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
    				<span class="navbar-toggler-icon"></span>
  				</button>
			
		 	<div class="collapse navbar-collapse" id="navbarSupportedContent">
    			<ul class="navbar-nav mr-auto">
					<?php if($_SESSION['rol'] == 1) { ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-users"></i> Usuarios
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="../sistema/registro_usuarios.php">Nuevo Usuario</a>
							<a class="dropdown-item" href="../sistema/lista_usuarios.php">Lista de Usuarios</a>
						</div>
					</li>
					<?php } ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-id-badge"></i> Clientes
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="../sistema/registro_clientes.php">Nuevo Cliente/a</a>
							<a class="dropdown-item" href="../sistema/lista_clientes.php">Lista de Clientes</a>
						</div>
					</li>
					<?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-building"></i> Proveedores
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="../sistema/registro_proveedores.php">Nuevo Proveedor</a>
							<a class="dropdown-item" href="../sistema/lista_proveedores.php">Lista de Proveedores</a>
						</div>
					</li>
					<?php } ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-archive"></i> Productos
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) { ?>
							<a class="dropdown-item" href="../sistema/registro_productos.php">Nuevo Producto</a>
						<?php } ?>
							<a class="dropdown-item" href="../sistema/lista_productos.php">Lista de Productos</a>
						</div>
					</li>

					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-file-invoice-dollar"></i> Ventas
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="../sistema/generar_ventas.php">Nueva Venta</a>
							<a class="dropdown-item" href="../sistema/lista_ventas.php">Ventas</a>
						</div>
					</li>

					<div class="col-md-auto">
    				</div>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-envelope"></i>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="../sistema/lista_mensajes.php">Mensajes</a>
						</div>
					</li>
						<span class="navbar-text">
							<?php echo fechaC() ?> || <i class="fas fa-user-circle"></i> <?php echo $_SESSION['nombre']?> 
						</span>
					</li>
					
					<div class="col-md-auto">
    				</div>
					<li class="nav-item justify">
						<a class="nav-link" href="../cierre_sesion.php"><i class="fas fa-power-off"></i> Cerrar Sesión <span class="sr-only"></span></a>
					</li>
					</div>
				</ul>
			</div>
		</nav>

