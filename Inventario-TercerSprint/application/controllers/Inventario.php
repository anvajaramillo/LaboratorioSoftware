<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventario extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('login_model');
        $this->load->helper('form');
        $this->load->model('Local');
        $this->load->database('default');
        $this->load->library('email');
        date_default_timezone_set('America/Bogota');
    }

    public function index()
    {
        null;
    }

    public function crearInventario()
    {
        $this->form_validation->set_rules('codigo', 'código producto', 'trim|required|is_natural');
        $this->form_validation->set_rules('nombre', 'nombre producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('tipo', 'tipo producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('iva', 'IVA', 'trim|required|decimal');
        if($this->input->post('id')==0){
            $this->form_validation->set_rules('img', 'imagen', 'max_length[20]|callback_img_jpgpng');
        }
        $this->form_validation->set_rules('compra', 'valor unitario compra con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('venta', 'valor unitario venta con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('sede', 'sede', 'trim|required|numeric');
        $this->form_validation->set_rules('id', 'sede', 'trim|required|numeric');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
        //si no cumple con las validaciones
        if (!$this->form_validation->run()){
            $data['inventario']=$this->Local->get_register_join2('Inventario','Sedes','cod_sede_inv = id_sede');
            $data['sede']=$this->Local->get_register('Sedes');
            $this->load->view('inventario',$data);
            //si cumple con las validaciones
        } else{
            $codigo = $this->input->post('codigo');
            $nombre = $this->input->post('nombre');
            $tipo = $this->input->post('tipo');
            $iva = $this->input->post('iva');
            $id=$this->input->post('id');
            if($id != 0){
                $sql=$this->Local->get_register2('Inventario','id_inv',$id);
                $ruta_img = $sql[0]->ruta_imagen_inv;
            }else{
                $img=$_FILES["img"]["name"];
                $ruta_img=RUTA.$codigo."_".$nombre."_".$img;
                move_uploaded_file($_FILES['img']['tmp_name'],$ruta_img);
                $ruta_img=$codigo."_".$nombre."_".$img;
            }
            $compra = $this->input->post('compra');
            $venta = $this->input->post('venta');
            $sede = $this->input->post('sede');

            $sql = $this->Local->get_register3('Inventario', 'cod_prod_inv', $codigo, 'cod_sede_inv', $sede);
            if(count($sql) > 0){
                $sql1 = $this->Local->get_register2('Sedes', 'id_sede', $sede);
                $this->session->set_userdata('success', '<span class="label label-danger">Error: el código de producto '.$codigo. ' ya se encuentra registrado en la sede ' .$sql1[0]->nombre_sede.'</span>');
                redirect(base_url() . 'index.php/Admin/inventario');
            }
            $data = array(
                'cod_prod_inv' => $codigo,
                'nombre_inv' => $nombre,
                'tipo_producto_inv' => $tipo,
                'iva_inv' => $iva,
                'ruta_imagen_inv' => $ruta_img,
                'valor_compra_con_iva_inv' => $compra,
                'valor_venta_con_iva_inv' => $venta,
                'cantidad_prod_inv' => 0,
                'cantidad_dan_inv' => 0,
                'cod_sede_inv' => $sede
            );
            $sql = $this->Local->add('Inventario', $data);
            if ($sql) {
                $this->session->set_userdata('success', '<span class="label label-success">El producto ha sido guardado con éxito</span>');
            } else {
                $this->session->set_userdata('success', '<span class="label label-danger">El producto no pudo ser guardado con éxito</span>');
            }
            redirect(base_url() . 'index.php/Admin/inventario');
        }
    }

    public function editarInventario()
    {
        $this->form_validation->set_rules('codigo1', 'código producto', 'trim|required|is_natural');
        $this->form_validation->set_rules('nombre1', 'nombre producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('tipo1', 'tipo producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('iva1', 'IVA', 'trim|required|decimal');
        $img = $_FILES["img1"]["name"];
        if($img != ""){
            $this->form_validation->set_rules('img1', 'imagen', 'max_length[20]|callback_img_jpgpng1');
        }
        $this->form_validation->set_rules('compra1', 'valor unitario compra con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('venta1', 'valor unitario venta con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('sede1', 'sede', 'trim|required|numeric');
        $this->form_validation->set_rules('id1', 'id', 'trim|required|numeric');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
        //si no cumple con las validaciones
        if (!$this->form_validation->run()){
            $data['inventario']=$this->Local->get_register_join2('Inventario','Sedes','cod_sede_inv = id_sede');
            $data['sede']=$this->Local->get_register('Sedes');
            $this->load->view('inventario',$data);
            //si cumple con las validaciones
        } else{
            $id = $this->input->post('id1');
            $codigo = $this->input->post('codigo1');
            $nombre = $this->input->post('nombre1');
            $tipo = $this->input->post('tipo1');
            $img=$_FILES["img1"]["name"];
            if ($img == "") {
                $sql1 = $this->Local->getElementWhere('Inventario', 'ruta_imagen_inv', 'id_inv', $id);
                $ruta_img = $sql1[0]->ruta_imagen_inv;
            } else {
                $ruta_img=RUTA.$codigo."_".$nombre."_".$img;
                move_uploaded_file($_FILES['img1']['tmp_name'],$ruta_img);
                $ruta_img=$codigo."_".$nombre."_".$img;
            }
            $compra = $this->input->post('compra1');
            $venta = $this->input->post('venta1');
            $sede = $this->input->post('sede1');

            $sql = $this->Local->get_register2('Inventario', 'id_inv',$id);
            if($codigo != $sql[0]->cod_prod_inv){
                $sql1=$this->Local->get_register3('Inventario', 'cod_prod_inv', $codigo, 'cod_sede_inv', $sede);
                foreach ($sql1 as $key) {
                    if($key->id_inv == $id){
                        if($codigo == $key->cod_prod_inv){
                            $sql2 = $this->Local->get_register2('Sedes', 'id_sede', $sede);
                            $this->session->set_userdata('success', '<span class="label label-danger">Error: el código de producto '.$codigo. ' ya se encuentra registrado en la sede ' .$sql2[0]->nombre_sede.'</span>');
                            redirect(base_url() . 'index.php/Admin/inventario');
                        }
                    }
                }
            }
            $data = array(
                'cod_prod_inv' => $codigo,
                'nombre_inv' => $nombre,
                'tipo_producto_inv' => $tipo,
                'ruta_imagen_inv' => $ruta_img,
                'valor_compra_con_iva_inv' => $compra,
                'valor_venta_con_iva_inv' => $venta,
                'cod_sede_inv' => $sede
            );
            $sql2 = $this->Local->update('Inventario', $data, 'id_inv',$id);
            if ($sql2) {
                $this->session->set_userdata('success', '<span class="label label-success">El producto ha sido actualizado con éxito</span>');
            } else {
                $this->session->set_userdata('success', '<span class="label label-danger">El producto no pudo ser actualizado con éxito</span>');
            }
            redirect(base_url() . 'index.php/Admin/inventario');
        }
    }

    public function eliminarInventario()
    {
        $this->form_validation->set_rules('id2', 'id', 'trim|required|numeric');
        //si no cumple con las validaciones
        if (!$this->form_validation->run()){
            $data['inventario']=$this->Local->get_register_join2('Inventario','Sedes','cod_sede_inv = id_sede');
            $data['sede']=$this->Local->get_register('Sedes');
            $this->load->view('inventario',$data);
            //si cumple con las validaciones
        } else{
            $id = $this->input->post('id2');
            $data = array('id_inv' => $id);
            $sql = $this->Local->delete('Inventario', $data);
            if ($sql) {
                $this->session->set_userdata('success', '<span class="label label-success">El producto ha sido borrado con éxito</span>');
            } else {
                $this->session->set_userdata('success', '<span class="label label-success">El producto no pudo ser borrado con éxito</span>');
            }
            redirect(base_url() . 'index.php/Admin/inventario');
        }
    }

    public function validate_string($str){
        if(preg_match('/^[\w\s_\-áéíóúñ\/\(\)]*$/',$str)){
            return TRUE;
        }else{
            $this->form_validation->set_message('validate_string', 'El campo %s no puede contener símbolos especiales, sólo letras y números');
            return FALSE;
        }
        // $str =  mysqli_escape_string($str);
        return $str;
    }

    public function img_jpgpng($img){
        $valor= $_FILES["img"]["type"];
        if(!$_FILES["img"]["tmp_name"]){
            $this->form_validation->set_message('img_jpgpng', 'Se requiere subir una imagen.');
            return false;
        }else{
            if($valor=="image/jpeg" || $valor=="image/png"){
                return true;
            }else{
                $this->form_validation->set_message('img_jpgpng', 'Solo se permite imagenes jpg o png');
                return false;
            }
        }
    }

    public function img_jpgpng1($img){
        $valor= $_FILES["img1"]["type"];
        if(!$_FILES["img1"]["tmp_name"]){
            $this->form_validation->set_message('img_jpgpng', 'Se requiere subir una imagen.');
            return false;
        }else{
            if($valor=="image/jpeg" || $valor=="image/png"){
                return true;
            }else{
                $this->form_validation->set_message('img_jpgpng', 'Solo se permite imagenes jpg o png');
                return false;
            }
        }
    }

    public function validate_datetime($dt){
        $datetime_opt="/^(([\d]{4})-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[\d]{1}|2[\d]{1}|3[0-1]{1})) ((0[0-9]{1}|1[0-9]{1}|2[0-3]{1}):([0-5]{1}[0-9]{1}):([0-5]{1}[0-9]{1}))$/";
        $datetime="/^(([\d]{4})-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[\d]{1}|2[\d]{1}|3[0-1]{1})) ((0[0-9]{1}|1[0-9]{1}|2[0-3]{1}):([0-5]{1}[0-9]{1}))$/";
        if(preg_match($datetime, $dt) || preg_match($datetime_opt, $dt)){
            return true;
        }else{
            $this->form_validation->set_message('validate_datetime', 'El campo %s debe tener el formato yyyy-mm-dd HH:MM (Hora Militar)');
            return false;
        }
    }

}
?>