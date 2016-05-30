<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Consultas
    </title>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
            //una celda esta en 'selected'
            var table = $('#Consultas').DataTable({
                "scrollY": "180",
                "scrollCollapse" : true
            });

        } );

    </script>

</header>
<body style="background-color: #f0f0f0;">
<br>
<div class="container">
    <ul class="nav nav-tabs" >
        <?php if($this->session->userdata('perfil') == 'admin'){ ?>
            <li><a href='<?php echo base_url('index.php/Login/menu') ?>'>Inicio</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/inventario') ?>'>Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/movInventario') ?>'>Movimiento Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/clientes') ?>'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/facturas') ?>'>Facturas</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/proveedores') ?>'>Proveedores</a></li>
            <li class="active"><a href='#cons'>Consultas</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } elseif($this->session->userdata('perfil') == 'cajero'){ ?>
            <li><a href='<?php echo base_url('index.php/Login/menu') ?>'>Inicio</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/inventario') ?>'>Inventario</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/clientes') ?>'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/facturas') ?>'>Facturas</a></li>
            <li class="active"><a href='#cons'>Consultas</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } ?>
    </ul>
</div>

<br>

<div class="cuerpo container panel-body" id="cuerpo1">

    <div  class="tab-pane active" id="cons">

        <div style="text-align: center">
            <h1>Consultas</h1><?php
            //mostrar mensaje de almacenamiento satisfactorio o no de la bd
            if(key_exists('success', $this->session->all_userdata())){
                echo "<h4>".$this->session->userdata('success')."</h4>";
                $this->session->unset_userdata('success');
            } ?>
        </div>

        <?php if (isset($consultas['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Consultas" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Tipo</th>
                    <th>Valor unitario</th>
                    <th>IVA unitario</th>
                    <th>Valor compra</th>
                    <th>Id factura</th>
                    <th>Id Sede</th>
                </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php
                $ivad=0;
                $tventa = 0;
                if(count($consultas) > 0){
                    foreach ($consultas as $key) {
                        $iva = $key->valor_venta_con_iva_inv * $key->iva_inv;
                        $ivad = $ivad + $key->valor_venta_con_iva_inv * $key->iva_inv * $key->cant_prod_fact_cli;
                        $tventa = $tventa + $key->cant_prod_fact_cli * $key->valor_venta_con_iva_inv; ?>
                        <tr>
                            <td><?php echo $key->cod_prod_inv; ?></td>
                            <td><?php echo $key->nombre_inv; ?></td>
                            <td><?php echo $key->cant_prod_fact_cli; ?></td>
                            <td><?php echo $key->tipo_producto_inv; ?></td>
                            <td><?php echo $key->valor_venta_con_iva_inv; ?></td>
                            <td><?php echo $iva ?></td>
                            <td><?php echo $key->cant_prod_fact_cli * $key->valor_venta_con_iva_inv; ?></td>
                            <td><?php echo $key->id_fact; ?></td>
                            <td><?php echo $key->cod_sede_fact; ?></td>
                        </tr>
                    <?php }
                }?>
                </tbody>
            </table>
        <?php } ?>

        <div style="text-align: center">
            <h4>Fecha: <?php echo $fecha ?>; Ingresos del día: <?php echo $tventa ?>; IVA discrimidado: <?php echo $ivad ?></h4>
        </div>

    </div>
</div>


</body>
</html>