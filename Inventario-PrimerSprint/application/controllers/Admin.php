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
        $select="id_mov,fecha_mov,nombre_prov,nombre_inv,cantidad_prod_mov,tipo_movimiento_mov,descripcion_mov";
        $condicion1='id_inv = cod_inv_mov';
        $condicion2='id_prov = cod_prov_mov';
        $data['movimiento']=$this->Local->get_register_join3_select($select,'Inventario','Movimiento',$condicion1,'Proveedores',$condicion2);
        $data['proveedores']=$this->Local->get_register('Proveedores');
        $data['inventario']=$this->Local->get_register('Inventario');
        $this->load->view('movimiento_inventario',$data);
    }

    public function ObtenerRutaImg(){
        $id_invt=$_POST['id_invt'];
        $data=$this->Local->getElementWhere('Inventario','ruta_imagen_inv','id_inv', $id_invt);
        $img='<img src="'.RUTA_SUB.$data[0]->ruta_imagen_inv.'" width="100%" height="100%" title="producto">';
        echo $img;
    }

}
?>