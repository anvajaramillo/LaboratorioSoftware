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
        $data['inventario']=$this->Local->get_register_join2('Inventario','Sedes','cod_sede_inv = id_sede');
        $data['sede']=$this->Local->get_register('Sedes');
        $this->load->view('inventario',$data);
    }

    public function movInventario()
    {
        $select="id_mov,fecha_mov,nombre_prov,nombre_inv,cantidad_prod_mov,tipo_movimiento_mov,descripcion_mov,nombre_sede";
        $condicion1='id_inv = cod_inv_mov';
        $condicion2='id_prov = cod_prov_mov';
        $condicion3='id_sede = cod_sede_inv';
        $data['movimiento']=$this->Local->get_register_join4_select($select,'Inventario','Movimiento',$condicion1,'Proveedores',$condicion2,'Sedes',$condicion3);
        $data['proveedores']=$this->Local->get_register('Proveedores');
        $data['inventario']=$this->Local->get_register('Inventario');
        $data['sede']=$this->Local->get_register('Sedes');
        $this->load->view('movimiento_inventario',$data);
    }

    public function clientes()
    {
        $data['clientes']=$this->Local->get_register('Clientes');
        $this->load->view('clientes',$data);
    }

    public function facturas(){
        $data['facturas']=$this->Local->get_register('Facturas');
        $data['sede']=$this->Local->get_register('Sedes');
        $data['bool']=0;
        $this->load->view('facturas',$data);
    }

    public function ObtenerRutaImg(){
        $id_invt=$_POST['id_invt'];
        $data=$this->Local->getElementWhere('Inventario','ruta_imagen_inv','id_inv', $id_invt);
        $img='<img src="'.RUTA_SUB.$data[0]->ruta_imagen_inv.'" width="100%" height="100%" title="producto">';
        echo $img;
    }

    public function FactClient(){
        $id=$_POST['id'];
        $sql=$this->Local->getElementWhere('Clientes', 'nombre_cli', 'identificacion_cli', $id);
        if(count($sql)>0){
            $datos=$sql[0]->nombre_cli;
        }else{
            $datos="No existe cliente con el número de identificación ".$id.", debe realizar el ingreso del cliente.";
        }

        echo $datos;
    }

    public function FactPro(){
        $id=$_POST['id'];
        $sede=$_POST['sede'];
        $sql=$this->Local->getElementWhere('Inventario', 'nombre_inv,cantidad_prod_inv,cod_sede_inv', 'cod_prod_inv', $id);
        if(count($sql)>0){
            foreach ($sql as $key) {
                if($key->cod_sede_inv==$sede){
                    if($key->cantidad_prod_inv>0){
                        $datos=$key->nombre_inv.", Cantidad: ".$key->cantidad_prod_inv;
                    }else{
                        $datos="El producto con código ".$id." se encuentra agotado";
                    }
                    break;
                }else{
                    $datos="No existe producto con código ".$id." en el inventario de la sede seleccionada";
                }
            }
        }else{
            $datos="No existe producto con código ".$id." en el inventario de la sede seleccionada";
        }

        echo $datos;
    }

}
?>