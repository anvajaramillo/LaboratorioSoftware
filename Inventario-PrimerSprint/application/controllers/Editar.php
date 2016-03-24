<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Editar extends CI_Controller
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
        $sql = $this->Local->getElementWhere('Inventario', "cod_prod_inv", 'id_inv', $this->input->post('id1'));
        if($sql[0]->cod_prod_inv != $this->input->post('codigo1')){
            $this->form_validation->set_rules('codigo1', 'código producto', 'trim|required|is_natural|is_unique[Inventario.cod_prod_inv]');
        }
        else{
            $this->form_validation->set_rules('codigo1', 'código producto', 'trim|required|is_natural');
        }
        $this->form_validation->set_rules('nombre1', 'nombre producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('tipo1', 'tipo producto', 'trim|required|max_length[44]|callback_validate_string');
        $this->form_validation->set_rules('iva1', 'IVA', 'trim|required|decimal');
        $img = $_FILES["img11"]["name"];
        if($img != ""){
            $this->form_validation->set_rules('img1', 'imagen', 'max_length[20]|callback_img_jpgpng');
        }
        $this->form_validation->set_rules('compra1', 'valor unitario compra con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('venta1', 'valor unitario venta con IVA', 'trim|required|numeric');
        $this->form_validation->set_rules('id1', 'id', 'trim|required|numeric');
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
            $data = array(
                'cod_prod_inv' => $codigo,
                'nombre_inv' => $nombre,
                'tipo_producto_inv' => $tipo,
                'ruta_imagen_inv' => $ruta_img,
                'valor_compra_con_iva_inv' => $compra,
                'valor_venta_con_iva_inv' => $venta,
            );
            $sql2 = $this->Local->update('Inventario', $data, 'id_inv',$id);
            if ($sql2) {
                $this->session->set_userdata('success', '<span class="label label-success">El producto ha sido actualizado con éxito</span>');
            } else {
                $this->session->set_userdata('success', '<span class="label label-success">El producto no pudo ser actualizado con éxito</span>');
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
//        $str=  mysqli_real_escape_string($str);
//        return $str;
    }

    public function img_jpgpng($img){
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