<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Instituciones
    </title>

    <script type="text/javascript" class="init">

        $(document).ready(function() {

            //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
            //una celda esta en 'selected'
            var table = $('#Inst').DataTable();
            $('#Inst tbody').on( 'click', 'tr', function () {
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
                $('#nombre1').attr('value',obj[0][1]);
                $('#ciudad1').attr('value',obj[0][2]);
            } );

            $('#button3').click( function () {
                var obj = table.rows('.selected').data();
                console.log(obj);
                document.getElementById('id2').value = obj[0][0];
                document.getElementById('nombre2').innerHTML = obj[0][1];
            } );

        } );

    </script>

</header>
<body style="background-color: #f0f0f0;">

<div class="container">
    <ul class="nav nav-tabs" >
        <li class="active"><a href='#invt'>Inventario</a></li>
        <li><a href='<?php echo base_url('index.php/Admin/inventario') ?>'>Movimiento Inventario</a></li>
        <h3><?php echo base_url('index.php/Admin/inventario') ?></h3>
    </ul>
</div>

<br>

<div class="cuerpo container panel-body" id="cuerpo1">

    <div  class="tab-pane active" id="invt">
        <div id="navegador">
            <ul>
                <li><a id="button" href="#myModal1" class="btn">Agregar</a></li>
                <li><a id="button2" href="#myModal2" class="btn" disabled="disable">Editar</a></li>
                <li><a id="button3" href="#myModal3" class="btn" disabled="disable">Eliminar</a></li>
            </ul>
        </div>

        <div style="text-align: center">
            <h1>Inventario</h1><?php
            //mostrar mensaje de almacenamiento satisfactorio o no de la bd
            if(key_exists('success', $this->session->all_userdata())){
                echo "<h4>".$this->session->userdata('success')."</h4>";
                $this->session->unset_userdata('success');
            } ?>
        </div>
        <?php
        if(validation_errors()== TRUE){?>
            <div class="alert alert-danger alert-error" style="padding:7px;  height: 30px">
                <a href="#" data-dismiss="alert" style="alignment-adjust: central; text-decoration: none; color: gray"><?php echo "Hay un error en el formulario, vuelva a internarlo"; ?> </a>
            </div>
        <?php } ?>

        <?php if (isset($inventario['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Inst" class="display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php foreach ($inventario as $key) { ?>
                    <tr>
                        <td><?php echo $key->id_prov; ?></td>
                        <td><?php echo $key->nombre_prov; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php } ?>

       <!-- <div id="myModal1" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Agregar Institución</h3>
                <br><br>
                <form class="form-horizontal" action="<?php /*echo base_url('index.php/crear/institucion')*/?>" method="post">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">


                            <input type="text" name="nombre" value="<?php /*echo set_value('nombre');*/?>" class="form-control">
                            <?php /*echo form_error('nombre'); */?>

                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ciudad</label>
                        <div class="col-sm-9">
                            <input type="text" name="ciudad" class="form-control">
                        </div>
                    </div>

                    <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">Cerrar</a></button>
                        <input type="submit" class="btn btn-info" value="Guardar">
                    </div>
                </form>
            </div>
        </div>

        <div id="myModal2" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Editar Institución</h3>
                <br><br>
                <form class="form-horizontal" action="<?php /*echo base_url('index.php/editar/institucion')*/?>" method="post">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Nombre</label>
                        <div class="col-sm-9">
                            <input type="text" id="nombre1" name="nombre" value="<?php /*echo set_value('nombre')*/?>" class="form-control">
                            <?php /*echo form_error('nombre')*/?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ciudad</label>
                        <div class="col-sm-9">
                            <input type="text" id="ciudad1" name="ciudad" class="form-control">
                        </div>
                    </div>
                    <input type="hidden" id="id1" name="id">
                    <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">Cerrar</a></button>
                        <input type="submit" class="btn btn-info" value="Guardar">
                    </div>
                </form>
            </div>
        </div>

        <div id="myModal3" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Eliminar Institución</h3>
                <br><br>
                <form class="form-horizontal" action="<?php /*echo base_url('index.php/eliminar/institucion')*/?>" method="post">
                    <input type="hidden" id="id2" name="id">
                    <p>Desea eliminar la institución: </p>
                    <p style="font-weight: bold" id="nombre2"></p>
                    <br><br>
                    <div class="modal-footer" style="text-align: right; position: relative;top:80px">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#close">No</a></button>
                        <input type="submit" class="btn btn-info" name="boton" value="Si">
                    </div>
                </form>
            </div>
        </div>-->

    </div>
</div>


</body>
</html>