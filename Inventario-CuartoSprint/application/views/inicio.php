<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Administración Inventario
    </title>
</header>
<body style="background-color: #f0f0f0;">
<br>
<div class="container">
    <ul class="nav nav-tabs" >
        <?php if($this->session->userdata('perfil') == 'admin'){ ?>
            <li class="active"><a href='#inicio'>Inicio</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/inventario') ?>'>Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/movInventario') ?>'>Movimiento Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/clientes') ?>'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/facturas') ?>'>Facturas</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/proveedores') ?>'>Proveedores</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } elseif($this->session->userdata('perfil') == 'cajero'){ ?>
            <li class="active"><a href='#inicio'>Inicio</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/inventario') ?>'>Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/clientes') ?>'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/facturas') ?>'>Facturas</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } ?>
    </ul>
</div>

<br>
<div class="cuerpo container panel-body" id="cuerpo1" align="center">
    <div  class="tab-pane active" id="inicio">
        <img src="<?php echo base_url('media/imagenes/inventario.png') ?>" width="720" height="300" alt="Tamaño original" border="3">
    </div>
</div>
</body>
</html>