<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eliminar extends CI_Controller
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
        $this->form_validation->set_rules('id2', 'id', 'trim|required|numeric');
        //si no cumple con las validaciones
        if (!$this->form_validation->run()){
            $data['inventario']=$this->Local->get_register('Inventario');
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

}
?>