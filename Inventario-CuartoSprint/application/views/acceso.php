<?php
$this->load->view('header');
?>

<html>
<header>
    <title>
        Login Inventario
    </title>
</header>
<body style='background-image:url("<?php echo base_url('media/imagenes/inventario.png') ?>");background-repeat: no-repeat; background-position: center top'>
<div class="cuerpo container panel-body" id="cuerpo">
    <form class="form-horizontal" action="<?php echo base_url('index.php/Login/login')?>" method="post">
        <div class="form-group">
            <label class="col-sm-3 control-label">Usuario</label>
            <div class="col-sm-9">
                <input type="text" name="usr" class="form-control">
            </div>
            <?php if($this->session->flashdata('usuario_incorrecto')){ ?>
            <label class="col-sm-9 control-label"><?php echo $this->session->flashdata('usuario_incorrecto')?></label>
            <?php } ?>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Contraseña</label>
            <div class="col-sm-9">
                <input type="password" name="pass"class="form-control">
            </div>
            <?php if ($error_clave == 1){ ?>
                <label class="col-sm-9 control-label">Contraseña errada intente otra vez</label>
            <?php } ?>
        </div>
        <br><br>
        <div class="form-group" style="text-align: right">
            <div class="col-sm-offset-3 col-sm-9">
                <input type="submit" value="entrar" class="btn btn-danger">
            </div>
        </div>
    </form>
</div>
</body>
</html>
