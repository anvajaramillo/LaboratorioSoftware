<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends CI_Controller
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

    public function crearProveedor()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('nit', 'nit proveedor', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('nombre', 'nombre proveedor', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('telefono', 'telefono', 'trim|required|is_natural');
            $this->form_validation->set_rules('direccion', 'direccion', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('ciudad', 'ciudad', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['proveedores'] = $this->Local->get_register('Proveedores');
                $this->load->view('proveedores', $data);
                //si cumple con las validaciones
            } else {
                $nit = $this->input->post('nit');
                $nombre = $this->input->post('nombre');
                $telefono = $this->input->post('telefono');
                $direccion = $this->input->post('direccion');
                $ciudad = $this->input->post('ciudad');
                $data = array(
                    'nit_prov' => $nit,
                    'nombre_prov' => $nombre,
                    'telefono_prov' => $telefono,
                    'direccion_prov' => $direccion,
                    'ciudad_prov' => $ciudad
                );
                $sql = $this->Local->add('Proveedores', $data);
                if ($sql) {
                    $this->session->set_userdata('success', '<span class="label label-success">El proveedor ha sido guardado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El proveedor no pudo ser guardado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/proveedores');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function editarProveedor()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('nit1', 'nit proveedor', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('nombre1', 'nombre del cliente', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('telefono1', 'telefono', 'trim|required|is_natural');
            $this->form_validation->set_rules('direccion1', 'direccion', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('ciudad1', 'ciudad', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('id1', 'id', 'trim|required|numeric');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['proveedores'] = $this->Local->get_register('Proveedores');
                $this->load->view('proveedores', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id1');
                $nit = $this->input->post('nit1');
                $nombre = $this->input->post('nombre1');
                $telefono = $this->input->post('telefono1');
                $direccion = $this->input->post('direccion1');
                $ciudad = $this->input->post('ciudad1');
                $data = array(
                    'id_prov' => $id,
                    'nit_prov' => $nit,
                    'nombre_prov' => $nombre,
                    'telefono_prov' => $telefono,
                    'direccion_prov' => $direccion,
                    'ciudad_prov' => $ciudad
                );
                $sql2 = $this->Local->update('Proveedores', $data, 'id_prov', $id);
                if ($sql2) {
                    $this->session->set_userdata('success', '<span class="label label-success">El proveedor ha sido actualizado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El proveedor no pudo ser actualizado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/proveedores');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function eliminarProveedor()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('id2', 'id', 'trim|required|numeric');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['proveedores'] = $this->Local->get_register('Proveedores');
                $this->load->view('proveedores', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id2');
                $data = array('id_prov' => $id);
                $sql = $this->Local->delete('Proveedores', $data);
                if ($sql) {
                    $this->session->set_userdata('success', '<span class="label label-success">El proveedor ha sido borrado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-success">El proveedor no pudo ser borrado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/proveedores');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
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

    public function validate_date($dt){
        $date="/^(([\d]{4})-(0[1-9]{1}|1[0-2]{1})-(0[1-9]{1}|1[\d]{1}|2[\d]{1}|3[0-1]{1}))$/";
        //$date="/^hola$/";
        if(preg_match($date, $dt)){
            return true;
        }else{
            $this->form_validation->set_message('validate_date', 'El campo %s debe tener el formato dd/mm/aaaa');
            return false;
        }
    }

}
?>