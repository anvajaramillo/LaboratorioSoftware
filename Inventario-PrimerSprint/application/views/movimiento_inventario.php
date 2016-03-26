<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Movimiento de Inventario
    </title>

    <style>
        .modal-body1{
            height: 70%;
            width: 100%;
            overflow-y: auto;
            overflow-x: hidden;
        }
    </style>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
            //una celda esta en 'selected'
            var table = $('#Mov').DataTable({
                "scrollY": "230",
                "scrollCollapse" : true
            });
            $('#Mov tbody').on( 'click', 'tr', function () {
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
                $('#descripcion1').attr('value',obj[0][7]);
                $('#proveedor1').val(obj[0][2]);
            } );

        } );

    </script>

</header>
<body style="background-color: #f0f0f0;">
<br>

<div class="container">
    <ul class="nav nav-tabs" >
        <li><a href='<?php echo base_url('index.php/Admin/inventario') ?>'>Inventario</a></li>
        <li class="active"><a href='#mov'>Movimiento Inventario</a></li>
        <!--        <h6>--><?php //echo base_url('index.php/Admin/inventario') ?><!--</h6>-->
    </ul>
</div>

<br>

<div class="cuerpo container panel-body" id="cuerpo1">

    <div  class="tab-pane active" id="movz">
        <div id="navegador">
            <ul>
                <li><a id="button" href="#myModal1" class="btn">Agregar</a></li>
                <li><a id="button2" href="#myModal2" class="btn" disabled="disable">Editar</a></li>
            </ul>
        </div>

        <div style="text-align: center">
            <h1>Movimiento de Inventario</h1><?php
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

        <?php if (isset($movimiento['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Mov" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Fecha</th>
                    <th>Id proveedor</th>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Tipo de movimiento</th>
                    <th>Descripcion</th>
                </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php foreach ($movimiento as $key) { ?>
                    <tr>
                        <td><?php echo $key->id_mov; ?></td>
                        <td><?php echo $key->fecha_mov; ?></td>
                        <td><?php echo $key->id_prov; ?></td>
                        <td><?php echo $key->nombre_prov; ?></td>
                        <td><?php echo $key->nombre_inv; ?></td>
                        <td><?php echo $key->cantidad_prod_mov; ?></td>
                        <td><?php echo $key->tipo_movimiento_mov; ?></td>
                        <td><?php echo $key->descripcion_mov; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php } ?>

        <div id="myModal1" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Agregar Movimiento de Inventario</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/crear/movInventario')?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Proveedor</label>
                            <div class="col-sm-9">
                                <select  type="text" name="proveedor" class="form-control">
                                    <?php foreach ($proveedores as $key) { ?>
                                        <option value="<?php echo $key->id_prov ?>"><?php echo $key->nombre_prov; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Producto</label>
                            <div class="col-sm-9">
                                <select  type="text" name="producto" class="form-control">
                                    <?php foreach ($inventario as $key) { ?>
                                        <option value="<?php echo $key->id_inv ?>"><?php echo $key->nombre_inv; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Cantidad producto</label>
                            <div class="col-sm-9">
                                <input type="text" name="cantidad" value="<?php echo set_value('cantidad'); ?>" class="form-control">
                                <?php echo form_error('cantidad'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo movimiento</label>
                            <div class="col-sm-9">
                                <select  type="text" name="tipo" class="form-control">
                                    <option value="Ingreso">Ingreso</option>
                                    <option value="Egreso">Egreso</option>
                                </select>
                                <?php echo form_error('tipo'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Descripción movimiento</label>
                            <div class="col-sm-9">
                                <input type="text" name="descripcion" value="<?php echo set_value('descripcion'); ?>" class="form-control">
                                <?php echo form_error('descripcion'); ?>
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
                <h3 class="modal-title">Editar Movimiento</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/editar/movInventario')?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Proveedor</label>
                            <div class="col-sm-9">
                                <select  type="text" name="proveedor1" id="proveedor1" class="form-control" value="<?php echo set_value('proveedor1') ?>">
                                    <?php foreach ($proveedores as $key) { ?>
                                        <option value="<?php echo $key->id_prov ?>"><?php echo $key->nombre_prov; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Descripción movimiento</label>
                            <div class="col-sm-9">
                                <input type="text" name="descripcion1" id='descripcion1' value="<?php echo set_value('descripcion1'); ?>" class="form-control">
                                <?php echo form_error('descripcion1'); ?>
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

    </div>
</div>


</body>
</html>