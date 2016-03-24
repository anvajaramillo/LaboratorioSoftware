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
        $data['inventario']=$this->Local->get_register('Inventario');
        $this->load->view('inventario',$data);
    }

    public function inventario()
    {
        $data['inventario']=$this->Local->get_register('Inventario');
        $this->load->view('inventario',$data);
    }

    public function movInventario()
    {
        $data['movInventario']=$this->Local->get_register('Movimiento');
        $this->load->view('movimiento_inventario',$data);
    }

    public function ObtenerRutaImg(){
        $id_invt=$_POST['id_invt'];
        $data=$this->Local->getElementWhere('Inventario','ruta_imagen_inv','id_inv', $id_invt);
        $img='<img src="'.RUTA_SUB.$data[0]->ruta_imagen_inv.'" width="100%" height="100%" title="logo">';
        echo $img;
    }

}
?>