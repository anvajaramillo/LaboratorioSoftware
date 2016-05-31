<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Clientes
    </title>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
            //una celda esta en 'selected'
            var table = $('#Cliente').DataTable({
                "scrollY": "180",
                "scrollCollapse" : true
            });
            $('#Cliente tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                    $('#button2').attr("disabled", true);
                    $('#button3').attr("disabled", true);
                    $('#button4').attr("disabled", true);
                }else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('#button2').attr("disabled", false);
                    $('#button3').attr("disabled", false);
                    $('#button4').attr("disabled", false);
                }
            } );

            $('#button2').click( function () {
                var obj = table.rows('.selected').data();
                console.log(obj);
                document.getElementById('id1').value = obj[0][0];
                $('#identificacion1').attr('value',obj[0][1]);
                $('#nombre1').attr('value',obj[0][2]);
                $('#fecha1').attr('value',obj[0][3]);
                $('#telefono1').attr('value',obj[0][4]);
                $('#direccion1').val(obj[0][5]);
                $('#ciudad1').val(obj[0][6]);
            } );

            $('#button3').click( function () {
                var obj = table.rows('.selected').data();
                console.log(obj);
                document.getElementById('id2').value = obj[0][0];
                document.getElementById('nombre2').innerHTML = obj[0][2];
            } );

            $('#button4').click( function () {
                var obj = table.rows('.selected').data();
                console.log(obj);
                document.getElementById('id3').value = obj[0][0];
                var submit=document.getElementById('button5');
                submit.click();
            } );

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
            <li class="active"><a href='#cli'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/facturas') ?>'>Facturas</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/proveedores') ?>'>Proveedores</a></li>
            <li><a href='<?php echo base_url('index.php/Admin/consultas') ?>'>Consultas</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } elseif($this->session->userdata('perfil') == 'cajero'){ ?>
            <li><a href='<?php echo base_url('index.php/Login/menu') ?>'>Inicio</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/inventario') ?>'>Inventario</a></li>
            <li class="active"><a href='#cli'>Clientes</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/facturas') ?>'>Facturas</a></li>
            <li><a href='<?php echo base_url('index.php/Cajero/consultas') ?>'>Consultas</a></li>
            <li><a href='<?php echo base_url('index.php/Login/logout_ci') ?>'>Salir</a></li>
        <?php } ?>
    </ul>
</div>

<br>

<div class="cuerpo container panel-body" id="cuerpo1">

    <div  class="tab-pane active" id="cli">
        <div id="navegador">
            <ul>
                <li><a id="button" href="#myModal1" class="btn">Agregar</a></li>
                <li><a id="button2" href="#myModal2" class="btn" disabled="disable">Editar</a></li>
                <?php if($this->session->userdata('perfil') == 'admin'){ ?>
                    <li><a id="button3" href="#myModal3" class="btn" disabled="disable">Eliminar</a></li>
                <?php } ?>
                <li><a id="button4" href="#" class="btn" disabled="disable">Histórico de Compras</a></li>
            </ul>
        </div>

        <div style="text-align: center">
            <h1>Clientes</h1><?php
            //mostrar mensaje de almacenamiento satisfactorio o no de la bd
            if(key_exists('success', $this->session->all_userdata())){
                echo "<h4>".$this->session->userdata('success')."</h4>";
                $this->session->unset_userdata('success');
            } ?>
        </div>
        <?php
        if(validation_errors()== TRUE){?>
            <div class="alert alert-danger alert-error" style="padding:7px;  height: 30px">
                <center><a href="#" data-dismiss="alert" style="alignment-adjust: central; text-decoration: none; color: gray"><?php echo "Hay un error en el formulario, vuelva a internarlo"; ?> </a></center>
            </div>
        <?php } ?>

        <?php if (isset($clientes['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Cliente" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Identificación</th>
                    <th>Nombre</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php foreach ($clientes as $key) { ?>
                    <tr>
                        <td><?php echo $key->id_cli; ?></td>
                        <td><?php echo $key->identificacion_cli; ?></td>
                        <td><?php echo $key->nombre_cli; ?></td>
                        <td><?php echo $key->fecha_nac_cli; ?></td>
                        <td><?php echo $key->telefono_cli; ?></td>
                        <td><?php echo $key->direccion_cli; ?></td>
                        <td><?php echo $key->ciudad_cli; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php } ?>

        <div id="myModal1" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Agregar Cliente</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/cliente/crearCliente')?>" method="post">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Identificación cliente</label>
                            <div class="col-sm-9">
                                <input type="text" name="identificacion" value="<?php echo set_value('identificacion');?>" class="form-control">
                                <?php echo form_error('identificacion'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre cliente</label>
                            <div class="col-sm-9">
                                <input type="text" name="nombre" value="<?php echo set_value('nombre');?>" class="form-control">
                                <?php echo form_error('nombre'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Fecha de nacimiento</label>
                            <div class="col-sm-9">
                                <input type="date" name="fecha" value="<?php echo set_value('fecha');?>" class="form-control">
                                <?php echo form_error('fecha'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-9">
                                <input type="text" name="telefono" value="<?php echo set_value('telefono');?>" class="form-control">
                                <?php echo form_error('telefono'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Dirección</label>
                            <div class="col-sm-9">
                                <input type="text" name="direccion" value="<?php echo set_value('direccion');?>" class="form-control">
                                <?php echo form_error('direccion'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ciudad</label>
                            <div class="col-sm-9">
                                <input type="text" name="ciudad" value="<?php echo set_value('ciudad');?>" class="form-control">
                                <?php echo form_error('ciudad'); ?>
                            </div>
                        </div>

                        <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">Cerrar</a></button>
                            <input type="submit" class="btn btn-danger" value="Guardar">
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div id="myModal2" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Editar Cliente</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/cliente/editarCliente')?>" method="post">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Identificación cliente</label>
                            <div class="col-sm-9">
                                <input type="text" id="identificacion1" name="identificacion1" value="<?php echo set_value('identificacion1');?>" class="form-control">
                                <?php echo form_error('identificacion1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre cliente</label>
                            <div class="col-sm-9">
                                <input type="text" id="nombre1" name="nombre1" value="<?php echo set_value('nombre1');?>" class="form-control">
                                <?php echo form_error('nombre1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Fecha de nacimiento</label>
                            <div class="col-sm-9">
                                <input type="date" id="fecha1" name="fecha1" value="<?php echo set_value('fecha1');?>" class="form-control">
                                <?php echo form_error('fecha1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Teléfono</label>
                            <div class="col-sm-9">
                                <input type="text" id="telefono1" name="telefono1" value="<?php echo set_value('telefono1');?>" class="form-control">
                                <?php echo form_error('telefono1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Dirección</label>
                            <div class="col-sm-9">
                                <input type="text" id="direccion1" name="direccion1" value="<?php echo set_value('direccion1');?>" class="form-control">
                                <?php echo form_error('direccion1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Ciudad</label>
                            <div class="col-sm-9">
                                <input type="text" id="ciudad1" name="ciudad1" name="ciudad1" value="<?php echo set_value('ciudad1');?>" class="form-control">
                                <?php echo form_error('ciudad1'); ?>
                            </div>
                        </div>

                        <input type="hidden" id="id1" name="id1">

                        <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">Cerrar</a></button>
                            <input type="submit" class="btn btn-danger" value="Guardar">
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <div id="myModal3" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Eliminar Cliente</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/cliente/eliminarCliente')?>" method="post">
                    <div class="modal-body1">
                        <input type="hidden" id="id2" name="id2">
                        <p>Desea eliminar el cliente: </p>
                        <p style="font-weight: bold" id="nombre2"></p>
                        <br><br>
                        <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">No</a></button>
                            <input type="submit" class="btn btn-danger" name="boton" value="Si">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <form class="form-horizontal" action="<?php echo base_url('index.php/cliente/historicoCliente')?>" method="post">
            <input type="hidden" id="id3" name="id3">
            <input type="submit" id="button5" style="display:none">
        </form>

    </div>
</div>


</body>
</html>