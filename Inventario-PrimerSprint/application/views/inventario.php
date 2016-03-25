<?php
$this->load->view('header');
?>
<!DOCTYPE html>
<html lang="es">
<header>
    <title>
        Inventario
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

    function logo(){
        var table = $('#Invt').DataTable();
        var obj = table.rows('.selected').data();
        console.log(obj);
        $.ajax({
            type:'POST',
            url : '<?php echo base_url('index.php/Admin/ObtenerRutaImg') ?>',
            data: {
                id_invt:obj[0][0],
            },
            success : function(body){
                //alert(body);
                $('#img').html(body);
            },
            error : function(body){
                alert(body);
            }
        });
        setTimeout(function (){result();},3000);
    }

    $(document).ready(function() {

        //marca o desmarca las celdas de la Datatable y tambien habilita los botones cuando
        //una celda esta en 'selected'
        var table = $('#Invt').DataTable({
            "scrollY": "230",
            "scrollCollapse" : true
        });
        $('#Invt tbody').on( 'click', 'tr', function () {
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
            $('#codigo1').attr('value',obj[0][1]);
            $('#nombre1').attr('value',obj[0][2]);
            $('#compra1').attr('value',obj[0][6]);
            $('#venta1').attr('value',obj[0][7]);
            $('#tipo1').val(obj[0][3]);
        } );

        $('#button3').click( function () {
            var obj = table.rows('.selected').data();
            console.log(obj);
            document.getElementById('id2').value = obj[0][0];
            document.getElementById('nombre2').innerHTML = obj[0][2];
        } );

    } );

</script>

</header>
<body style="background-color: #f0f0f0;">
<br>
<div class="container">
    <ul class="nav nav-tabs" >
        <li class="active"><a href='#invt'>Inventario</a></li>
        <li><a href='<?php echo base_url('index.php/Admin/movInventario') ?>'>Movimiento Inventario</a></li>
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
                <center><a href="#" data-dismiss="alert" style="alignment-adjust: central; text-decoration: none; color: gray"><?php echo "Hay un error en el formulario, vuelva a internarlo"; ?> </a></center>
            </div>
        <?php } ?>

        <?php if (isset($inventario['error'])) { ?>
            <h2>Ha ocurrido un error en la base de datos</h2>
        <?php } else {	?>
            <table id="Invt" class="display" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Código Producto</th>
                        <th>Nombre producto</th>
                        <th>Tipo de producto</th>
                        <th>IVA</th>
                        <th>Imagen</th>
                        <th>Valor compra + IVA</th>
                        <th>Valor venta + IVA</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                <?php //se recorre el arreglo por medio de un foreach y se accede a el como un objeto ?>
                <?php foreach ($inventario as $key) { ?>
                    <tr>
                        <td><?php echo $key->id_inv; ?></td>
                        <td><?php echo $key->cod_prod_inv; ?></td>
                        <td><?php echo $key->nombre_inv; ?></td>
                        <td><?php echo $key->tipo_producto_inv; ?></td>
                        <td><?php echo $key->iva_inv; ?></td>
                        <td><a id="ruta" onclick="logo()" href="#imagen"><img src="<?php echo RUTA_SUB.$key->ruta_imagen_inv; ?>" width="25px"; height="40px" title="logo"></a></td>
                        <td><?php echo $key->valor_compra_con_iva_inv; ?></td>
                        <td><?php echo $key->valor_venta_con_iva_inv; ?></td>
                        <td><?php echo $key->cantidad_prod_inv; ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        <?php } ?>

        <div id="myModal1" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <h3 class="modal-title">Agregar Producto</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/crear/inventario')?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Código Producto</label>
                            <div class="col-sm-9">
                                <input type="text" name="codigo" value="<?php echo set_value('codigo');?>" class="form-control">
                                <?php echo form_error('codigo'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre producto</label>
                            <div class="col-sm-9">
                                <input type="text" name="nombre" value="<?php echo set_value('nombre'); ?>" class="form-control">
                                <?php echo form_error('nombre'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo producto</label>
                            <div class="col-sm-9">
                                <select  type="text" name="tipo" class="form-control">
                                    <option value="Aseo personal">Aseo personal</option>
                                    <option value="Limpieza hogar">Limpieza hogar</option>
                                    <option value="Alimentos congelados">Alimentos congelados</option>
                                    <option value="Comida para animales">Comida para animales</option>
                                    <option value="Frutas y verduras">Frutas y verduras</option>
                                    <option value="Carnes">Carnes</option>
                                    <option value="Confitería">Confitería</option>
                                    <option value="Bebidas">Bebidas</option>
                                    <option value="Licores">Licores</option>
                                    <option value="Granos">Granos</option>
                                    <option value="Lacteos y huevos">Lacteos y huevos</option>
                                </select>
                                <?php echo form_error('tipo'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="iva" readonly="true" value="0.16" class="form-control">
                                <?php echo form_error('iva'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Imagen</label>
                            <div class="col-sm-9">
                                <input type="file" name="img" value="<?php echo set_value('img'); ?>">
                                <label style="font-size: 0.8em;">Solo imagenes PNG y JPG</label>
                                <?php echo form_error('img'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valor unitario compra con IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="compra" value="<?php echo set_value('compra'); ?>" class="form-control">
                                <?php echo form_error('compra'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valor unitario venta con IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="venta" value="<?php echo set_value('venta'); ?>" class="form-control">
                                <?php echo form_error('venta'); ?>
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
                <h3 class="modal-title">Editar Producto</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/editar/inventario')?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body1">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Código Producto</label>
                            <div class="col-sm-9">
                                <input type="text" name="codigo1" id="codigo1" value="<?php echo set_value('codigo1');?>" class="form-control">
                                <?php echo form_error('codigo1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Nombre producto</label>
                            <div class="col-sm-9">
                                <input type="text" name="nombre1" id="nombre1" value="<?php echo set_value('nombre1'); ?>" class="form-control">
                                <?php echo form_error('nombre1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo producto</label>
                            <div class="col-sm-9">
                                <select  type="text" name="tipo1" id="tipo1" class="form-control">
                                    <option value="Aseo personal">Aseo personal</option>
                                    <option value="Limpieza hogar">Limpieza hogar</option>
                                    <option value="Alimentos congelados">Alimentos congelados</option>
                                    <option value="Comida para animales">Comida para animales</option>
                                    <option value="Frutas y verduras">Frutas y verduras</option>
                                    <option value="Carnes">Carnes</option>
                                    <option value="Confitería">Confitería</option>
                                    <option value="Bebidas">Bebidas</option>
                                    <option value="Licores">Licores</option>
                                    <option value="Granos">Granos</option>
                                    <option value="Lacteos y huevos">Lacteos y huevos</option>
                                </select>
                                <?php echo form_error('tipo1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="iva1" readonly="true" value="0.16" class="form-control">
                                <?php echo form_error('iva1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Imagen</label>
                            <div class="col-sm-9">
                                <input type="file" name="img1" value="<?php echo set_value('img1'); ?>">
                                <label style="font-size: 0.8em;">Solo imagenes PNG y JPG</label>
                                <?php echo form_error('img1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valor unitario compra con IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="compra1" id="compra1" value="<?php echo set_value('compra1'); ?>" class="form-control">
                                <?php echo form_error('compra1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Valor unitario venta con IVA</label>
                            <div class="col-sm-9">
                                <input type="text" name="venta1" id="venta1" value="<?php echo set_value('venta1'); ?>" class="form-control">
                                <?php echo form_error('venta1'); ?>
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
                <h3 class="modal-title">Eliminar Producto</h3>
                <br><br>
                <form class="form-horizontal" action="<?php echo base_url('index.php/eliminar/inventario')?>" method="post">
                    <div class="modal-body1">
                        <input type="hidden" id="id2" name="id2">
                        <p>Desea eliminar el producto: </p>
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

        <div id="imagen" class="modalmask">
            <div class="modalbox rotate">
                <a href="#close" class="close">X</a>
                <div id="img"></div>
            </div>
        </div>

    </div>
</div>


</body>
</html>