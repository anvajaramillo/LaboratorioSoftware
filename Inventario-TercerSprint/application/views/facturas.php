<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Facturas
    </title>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
            //una celda esta en 'selected'
            var table = $('#Fact').DataTable({
                "scrollY": "210",
                "scrollCollapse" : true
            });
            $('#Fact tbody').on( 'click', 'tr', function () {
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                    $('#button2').attr("disabled", true);
                    $('#button3').attr("disabled", true);
                }else {
                    table.$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                    $('#button2').attr("disabled", false);
                    $('#button3').attr("disabled", false);
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

            $('#identificacion').change(function () {
                //$('#nombre').empty();
                var valor=$('#identificacion').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactClient') ?>",
                    data:{
                        id:valor,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombreCli').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });
            $('#identificacion').ready(function () {
                //$('#nombre').empty();
                var valor=$('#identificacion').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactClient') ?>",
                    data:{
                        id:valor,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombreCli').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });

            $('#producto').change(function () {
                //$('#nombre').empty();
                var valor1=$('#producto').val();
                var valor2=$('#sede').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactPro') ?>",
                    data:{
                        id:valor1,
                        sede:valor2,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombrePro').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });

            $('#producto').ready(function () {
                //$('#nombre').empty();
                var valor1=$('#producto').val();
                var valor2=$('#sede').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactPro') ?>",
                    data:{
                        id:valor1,
                        sede:valor2,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombrePro').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });
            $('#sede').change(function () {
                //$('#nombre').empty();
                var valor1=$('#producto').val();
                var valor2=$('#sede').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactPro') ?>",
                    data:{
                        id:valor1,
                        sede:valor2,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombrePro').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });
            $('#producto1').change(function () {
                //$('#nombre').empty();
                var valor1=$('#producto1').val();
                var valor2=$('#sede1').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactPro') ?>",
                    data:{
                        id:valor1,
                        sede:valor2,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombrePro1').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });

            $('#producto1').ready(function () {
                //$('#nombre').empty();
                var valor1=$('#producto1').val();
                var valor2=$('#sede1').val();
                //alert(valor);
                $.ajax({
                    type: "POST",
                    url:"<?php echo base_url('index.php/Admin/FactPro') ?>",
                    data:{
                        id:valor1,
                        sede:valor2,
                    },
                    success:function(datos){
                        //$('#nombreCli').append(datos);
                        $('#nombrePro1').html(datos);
                    },
                    error:function(datos){
                        alert('error');
                    }
                });
            });

        } );

    </script>

    <script>
        $(document).ready(function (){
            //agregar
            $('#add').click(function (){
                //$('#addprod').css("display","block");
                document.getElementById('band').value = 1;
            });
            $('#fin').click(function (){
                //$('#addprod').css("display","block");
                document.getElementById('band').value = 0;
            });
        });
    </script>
    <script type="text/javascript">
        function ventanaadd(){
            //alert('hola');
            var submit=document.getElementById('button');
            submit.click();
        }

    </script>

</header>
<body style="background-color: #f0f0f0;">
<br>
<div class="container">
    <ul class="nav nav-tabs" >
        <li><a href='<?php echo base_url('index.php/Admin/inventario') ?>'>Inventario</a></li>
        <li><a href='<?php echo base_url('index.php/Admin/movInventario') ?>'>Movimiento Inventario</a></li>
        <li><a href='<?php echo base_url('index.php/Admin/clientes') ?>'>Clientes</a></li>
        <li class="active"><a href='#fac'>Facturas</a></li>
    </ul>
</div>

<br>

<div class="cuerpo container panel-body" id="cuerpo1">

    <div  class="tab-pane active" id="fac">
        <div id="navegador">
            <ul>
                <li><a id="button" href="#myModal1" class="btn">Agregar</a></li>
                <li><a id="button2" href="#myModal2" class="btn" disabled="disable">Editar</a></li>
                <li><a id="button3" href="#myModal3" class="btn" disabled="disable">Eliminar</a></li>
            </ul>
        </div>

        <div style="text-align: center">
            <h1>Facturas</h1><?php
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

        <?php if (isset($facturas['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Fact" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Número de Sede</th>
                </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php foreach ($facturas as $key) { ?>
                    <tr>
                        <td><?php echo $key->id_fact; ?></td>
                        <td><?php echo $key->cod_sede_fact; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php } ?>

        <div id="myModal1" class="modalmask">
            <?php if($bool == 1){ ?>
                <div class="modalbox normal">
                    <a href="#close" class="close">X</a>
                    <h3 class="modal-title">Agregar Factura</h3>
                    <br><br>
                    <form class="form-horizontal" action="<?php echo base_url('index.php/factura/crearFactura')?>" method="post">
                        <div class="modal-body1">

                            <input type="hidden" id="sede1" name="sede" value="<?php echo $this->session->userdata('id_sede'); ?>">

                            <div class="form-group">
                                <label class="col-sm-11 control-label">Sede: <?php echo $this->session->userdata('nombre_sede'); ?></label>
                            </div>

                            <input type="hidden" name="identificacion" value="<?php echo $this->session->userdata('ident_cliente'); ?>">

                            <div class="form-group">
                                <label class="col-sm-11 control-label">Nombre del cliente: <?php echo $this->session->userdata('nombre_cliente'); ?></label>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Código del producto</label>
                                <div class="col-sm-9">
                                    <input type="text" id="producto1" name="producto" value="<?php echo set_value('producto');?>" class="form-control">
                                    <?php echo form_error('producto'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-11 control-label">Nombre del producto: <span id="nombrePro1"></span></label>
                            </div>
            <?php } else { ?>
                <div class="modalbox rotate">
                    <a href="#close" class="close">X</a>
                    <h3 class="modal-title">Agregar Factura</h3>
                    <br><br>
                    <form class="form-horizontal" action="<?php echo base_url('index.php/factura/crearFactura')?>" method="post">
                        <div class="modal-body1">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Sede</label>
                                <div class="col-sm-9">
                                    <select  type="text" id="sede" name="sede" class="form-control">
                                        <?php foreach ($sede as $key) { ?>
                                            <option value="<?php echo $key->id_sede ?>"><?php echo $key->nombre_sede; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Código del cliente</label>
                                <div class="col-sm-9">
                                    <input type="text" id="identificacion" name="identificacion" value="<?php echo set_value('identificacion');?>" class="form-control">
                                    <?php echo form_error('identificacion'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-11 control-label">Nombre del cliente: <span id="nombreCli"></span></label>
                            </div>

<!--                            <div id="addprod" style="display: none;">-->
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Código del producto</label>
                                <div class="col-sm-9">
                                    <input type="text" id="producto" name="producto" value="<?php echo set_value('producto');?>" class="form-control">
                                    <?php echo form_error('producto'); ?>
                                </div>
                            </div>
<!--                            </div>-->

                            <div class="form-group">
                                <label class="col-sm-11 control-label">Nombre del producto: <span id="nombrePro"></span></label>
                            </div>
            <?php } ?>
                            <input type="hidden" id="band" name="band">

                            <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">Cerrar</a></button>
                                <input type="submit" class="btn btn-danger" id="add" value="Añadir Producto">
<!--                                <a id="add" class="btn btn-danger" >Añadir Producto</a>-->
                                <input type="submit" class="btn btn-danger" id="fin" value="Terminar Factura">
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

        <?php if($bool == 1){ ?>
            <script>
                ventanaadd();
            </script>
        <?php } ?>

    </div>
</div>


</body>
</html>