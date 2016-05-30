<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller
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

    public function crearCliente()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('identificacion', 'identificación del cliente', 'trim|required|is_natural|is_unique[Clientes.identificacion_cli]');
            $this->form_validation->set_rules('nombre', 'nombre del cliente', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('fecha', 'fecha', 'trim|required|callback_validate_date');
            $this->form_validation->set_rules('telefono', 'telefono', 'trim|required|is_natural');
            $this->form_validation->set_rules('direccion', 'direccion', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('ciudad', 'ciudad', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['clientes'] = $this->Local->get_register('Clientes');
                $this->load->view('clientes', $data);
                //si cumple con las validaciones
            } else {
                $identificacion = $this->input->post('identificacion');
                $nombre = $this->input->post('nombre');
                $fecha = $this->input->post('fecha');
                $telefono = $this->input->post('telefono');
                $direccion = $this->input->post('direccion');
                $ciudad = $this->input->post('ciudad');
                $data = array(
                    'identificacion_cli' => $identificacion,
                    'nombre_cli' => $nombre,
                    'fecha_nac_cli' => $fecha,
                    'telefono_cli' => $telefono,
                    'direccion_cli' => $direccion,
                    'ciudad_cli' => $ciudad
                );
                $sql = $this->Local->add('Clientes', $data);
                if ($sql) {
                    $this->session->set_userdata('success', '<span class="label label-success">El cliente ha sido guardado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El cliente no pudo ser guardado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/clientes');
                        break;
                    case 'cajero':
                        redirect(base_url().'index.php/Cajero/clientes');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function editarCliente()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $sql1 = $this->Local->getElementWhere('Clientes', "identificacion_cli", 'id_cli', $this->input->post('id1'));
            if ($sql1[0]->identificacion_cli != $this->input->post('identificacion1')) {
                $this->form_validation->set_rules('identificacion1', 'identificación del cliente', 'trim|required|is_natural|is_unique[Clientes.identificacion_cli]');
            } else {
                $this->form_validation->set_rules('identificacion1', 'identificación del cliente', 'trim|required|is_natural');
            }
            $this->form_validation->set_rules('nombre1', 'nombre del cliente', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('fecha1', 'fecha', 'trim|required|callback_validate_date');
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
                $data['clientes'] = $this->Local->get_register('Clientes');
                $this->load->view('clientes', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id1');
                $identificacion = $this->input->post('identificacion1');
                $nombre = $this->input->post('nombre1');
                $fecha = $this->input->post('fecha1');
                $telefono = $this->input->post('telefono1');
                $direccion = $this->input->post('direccion1');
                $ciudad = $this->input->post('ciudad1');
                $data = array(
                    'id_cli' => $id,
                    'identificacion_cli' => $identificacion,
                    'nombre_cli' => $nombre,
                    'fecha_nac_cli' => $fecha,
                    'telefono_cli' => $telefono,
                    'direccion_cli' => $direccion,
                    'ciudad_cli' => $ciudad
                );
                $sql2 = $this->Local->update('Clientes', $data, 'id_cli', $id);
                if ($sql2) {
                    $this->session->set_userdata('success', '<span class="label label-success">El cliente ha sido actualizado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El cliente no pudo ser actualizado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/clientes');
                        break;
                    case 'cajero':
                        redirect(base_url().'index.php/Cajero/clientes');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function eliminarCliente()
    {
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('id2', 'id', 'trim|required|numeric');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['clientes'] = $this->Local->get_register('Clientes');
                $this->load->view('clientes', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id2');
                $data = array('id_cli' => $id);
                $sql = $this->Local->delete('Clientes', $data);
                if ($sql) {
                    $this->session->set_userdata('success', '<span class="label label-success">El cliente ha sido borrado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-success">El cliente no pudo ser borrado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/clientes');
                        break;
                    case 'cajero':
                        redirect(base_url().'index.php/Cajero/clientes');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function historicoCliente(){
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('id3', 'id', 'trim|required|numeric');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $data['clientes'] = $this->Local->get_register('Clientes');
                $this->load->view('clientes', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id3');
                if($this->session->userdata('perfil') == 'admin') {
                    $sql = "SELECT * FROM Facturas
                      JOIN Facturas_Cliente
                      ON id_fact = cod_fact_fact_cli
                      JOIN Clientes
                      ON cod_cli_fact_cli = id_cli
                      WHERE id_cli = " . $id . "
                      GROUP BY id_fact";
                }else{
                    $sql = "SELECT * FROM Facturas
                      JOIN Facturas_Cliente
                      ON id_fact = cod_fact_fact_cli
                      JOIN Clientes
                      ON cod_cli_fact_cli = id_cli
                      WHERE id_cli = " . $id . "
                      AND cod_sede_fact = ".$this->session->userdata('sede')."
                      GROUP BY id_fact";
                }
                $data['facturas'] = $this->Local->get_register_sql($sql);
                $data['sede'] = $this->Local->get_register('Sedes');
                $data['bool'] = 0;
                $this->session->set_userdata('id', 0);
                $this->load->view('facturas', $data);
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