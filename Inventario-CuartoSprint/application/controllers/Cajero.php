<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cajero extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('login_model');
        //$this->load->helper('form');
        //$this->load->model('Local');
        $this->load->database('default');
        $this->load->library('email');
        date_default_timezone_set('America/Bogota');
    }

    public function index()
    {
        if($this->session->userdata('is_logued_in') != '1' || $this->session->userdata('perfil') != 'cajero'){
            redirect(base_url().'index.php/Login');
        }else {
            $this->load->view('inicio');
        }
    }

    public function inventario()
    {
        if($this->session->userdata('is_logued_in') != '1' || $this->session->userdata('perfil') != 'cajero'){
            redirect(base_url().'index.php/Login');
        }else {
            $data['inventario'] = $this->Local->get_register_join2('Inventario', 'Sedes', 'cod_sede_inv = id_sede');
            $data['sede'] = $this->Local->get_register('Sedes');
            $this->load->view('inventario', $data);
        }
    }

    public function clientes()
    {
        if($this->session->userdata('is_logued_in') != '1' || $this->session->userdata('perfil') != 'cajero'){
            redirect(base_url().'index.php/Login');
        }else {
            $data['clientes'] = $this->Local->get_register('Clientes');
            $this->load->view('clientes', $data);
        }
    }

    public function facturas(){
        if($this->session->userdata('is_logued_in') != '1' || $this->session->userdata('perfil') != 'cajero'){
            redirect(base_url().'index.php/Login');
        }else {
            $sede = $this->session->userdata('sede');
            $data['facturas'] = $this->Local->get_register_join3_where_group_by('Facturas', 'Facturas_Cliente', 'id_fact = cod_fact_fact_cli', 'Clientes',  'cod_cli_fact_cli = id_cli', 'cod_sede_fact', $sede, 'id_fact');
            $data['sede'] = $this->Local->get_register2('Sedes', 'id_sede', $this->session->userdata('sede'));
            $data['bool'] = 0;
            $this->session->set_userdata('id', 0);
            $this->load->view('facturas', $data);
        }
    }

    public function consultas(){
        if($this->session->userdata('is_logued_in') != '1' || $this->session->userdata('perfil') != 'cajero'){
            redirect(base_url().'index.php/Login');
        }else {
            $sede = $this->session->userdata('sede');
            $fecha = date("Y-m-d");
            $data['fecha']=$fecha;
            $data['consultas']=$this->Local->get_register_join3_where_and_order_by('Facturas', 'Facturas_Cliente', 'id_fact = cod_fact_fact_cli', 'Inventario',  'cod_inv_fact_cli = id_inv', 'fecha_fact', $fecha, 'cod_sede_fact', $sede, 'id_fact', 'desc');
            $this->load->view('consultas', $data);
        }
    }

}
?>