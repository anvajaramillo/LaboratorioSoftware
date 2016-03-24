<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crear extends CI_Controller
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

    public function inventario()
    {
        $this->form_validation->set_rules('codigo', 'código producto', 'trim|required|is_natural|is_unique[Inventario.cod_prod_inv]');
        $this->form_validation->set_rules('nombre', 'nombre producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('tipo', 'tipo producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('iva', 'IVA', 'trim|required|decimal');
        $this->form_validation->set_rules('img', 'imagen', 'max_length[20]|callback_img_jpgpng');
        $this->form_validation->set_rules('compra', 'valor unitario compra con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('venta', 'valor unitario venta con IVA', 'trim|required|numeric');
        $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
        //si no cumple con las validaciones
        if (!$this->form_validation->run()){
            $data['inventario']=$this->Local->get_register('Inventario');
            $this->load->view('inventario',$data);
        //si cumple con las validaciones
        } else{
            $codigo = $this->input->post('codigo');
            $nombre = $this->input->post('nombre');
            $tipo = $this->input->post('tipo');
            $iva = $this->input->post('iva');
            $img=$_FILES["img"]["name"];
            $ruta_img=RUTA.$codigo."_".$nombre."_".$img;
            move_uploaded_file($_FILES['img']['tmp_name'],$ruta_img);
            $ruta_img=$codigo."_".$nombre."_".$img;
            $compra = $this->input->post('compra');
            $venta = $this->input->post('venta');
            $data = array(
                'cod_prod_inv' => $codigo,
                'nombre_inv' => $nombre,
                'tipo_producto_inv' => $tipo,
                'iva_inv' => $iva,
                'ruta_imagen_inv' => $ruta_img,
                'valor_compra_con_iva_inv' => $compra,
                'valor_venta_con_iva_inv' => $venta,
                'cantidad_prod_inv' => 0
            );
            $sql = $this->Local->add('Inventario', $data);
            if ($sql) {
                $this->session->set_userdata('success', '<span class="label label-success">El producto ha sido guardado con éxito</span>');
            } else {
                $this->session->set_userdata('success', '<span class="label label-success">El producto no pudo ser guardado con éxito</span>');
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