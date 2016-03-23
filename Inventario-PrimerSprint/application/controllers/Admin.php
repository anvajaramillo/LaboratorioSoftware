<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
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
        $data['inventario']=$this->Local->get_register('Proveedores');
        $this->load->view('inventario',$data);
    }

    public function inventario()
    {
        $data['inventario']=$this->Local->get_register('Proveedores');
        $this->load->view('inventario',$data);
    }

}
?>