<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MovInventario extends CI_Controller
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

    public function crearMovInventario(){
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('proveedor', 'proveedor', 'trim|required|numeric');
            $this->form_validation->set_rules('producto', 'producto', 'trim|required|numeric');
            $this->form_validation->set_rules('cantidad', 'cantidad', 'trim|required|numeric');
            $this->form_validation->set_rules('tipo', 'tipo de movimiento', 'trim|required|max_length[44]|callback_validate_string');
            $this->form_validation->set_rules('descripcion', 'descripcion movimiento', 'trim|required|max_length[99]|callback_validate_string');
            $this->form_validation->set_rules('sede', 'sede', 'trim|required|numeric');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $select = "id_mov,fecha_mov,nombre_prov,nombre_inv,cantidad_prod_mov,tipo_movimiento_mov,descripcion_mov,nombre_sede";
                $condicion1 = 'id_inv = cod_inv_mov';
                $condicion2 = 'id_prov = cod_prov_mov';
                $condicion3 = 'id_sede = cod_sede_inv';
                $data['movimiento'] = $this->Local->get_register_join4_select($select, 'Inventario', 'Movimiento', $condicion1, 'Proveedores', $condicion2, 'Sedes', $condicion3);
                $data['proveedores'] = $this->Local->get_register('Proveedores');
                $data['sede'] = $this->Local->get_register('Sedes');
                $this->load->view('movimiento_inventario', $data);
                //si cumple con las validaciones
            } else {
                $fecha = date("Y-m-d H:i:s");
                $proveedor = $this->input->post('proveedor');
                $producto = $this->input->post('producto');
                $cantidad = $this->input->post('cantidad');
                $tipo = $this->input->post('tipo');
                $descripcion = $this->input->post('descripcion');
                $sede = $this->input->post('sede');
                $dataMov = array(
                    'fecha_mov' => $fecha,
                    'cod_prov_mov' => $proveedor,
                    'cod_inv_mov' => $producto,
                    'cantidad_prod_mov' => $cantidad,
                    'tipo_movimiento_mov' => $tipo,
                    'descripcion_mov' => $descripcion
                );

                $sql = $this->Local->get_register3('Inventario', 'id_inv', $producto, 'cod_sede_inv', $sede);
                if (count($sql) == 0) {
                    $sql1 = $this->Local->get_register2('Inventario', 'id_inv', $producto);
                    $sql2 = $this->Local->get_register2('Sedes', 'id_sede', $sede);
                    $this->session->set_userdata('success', '<span class="label label-danger">Error: No existe el producto ' . $sql1[0]->nombre_inv . ' en la sede ' . $sql2[0]->nombre_sede . ' </span>');
                    redirect(base_url() . 'index.php/Admin/movInventario');
                }

                $con = $this->Local->get_register2('Inventario', 'id_inv', $producto);
                if ($tipo == 'Ingreso') {
                    $cantidad = $con[0]->cantidad_prod_inv + $cantidad;
                    $dataInv = array(
                        'cantidad_prod_inv' => $cantidad
                    );
                } else {
                    $cantidad_dan = $con[0]->cantidad_dan_inv + $cantidad;
                    $cantidad = $con[0]->cantidad_prod_inv - $cantidad;
                    if ($cantidad < 0) {
                        $this->session->set_userdata('success', '<span class="label label-danger">Error: Existen menos productos de los que se quieren dar de baja</span>');
                        redirect(base_url() . 'index.php/Admin/movInventario');
                    } else {
                        $dataInv = array(
                            'cantidad_prod_inv' => $cantidad,
                            'cantidad_dan_inv' => $cantidad_dan
                        );
                    }
                }

                $sql3 = $this->Local->add('Movimiento', $dataMov);
                $sql4 = $this->Local->update2('Inventario', $dataInv, 'id_inv', $producto, 'cod_sede_inv', $sede);
                if ($sql3 and $sql4) {
                    $this->session->set_userdata('success', '<span class="label label-success">El movimiento de inventario ha sido realizado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El movimiento de inventario no pudo ser realizado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/movInventario');
                        break;
                    default:
                        redirect(base_url().'index.php/Login');
                        break;
                }
            }
        }
    }

    public function editarMovInventario(){
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('descripcion1', 'descripcion movimiento', 'trim|required|max_length[99]|callback_validate_string');
            $this->form_validation->set_rules('id1', 'id', 'trim|required|numeric');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $select = "id_mov,fecha_mov,nombre_prov,nombre_inv,cantidad_prod_mov,tipo_movimiento_mov,descripcion_mov,nombre_sede";
                $condicion1 = 'id_inv = cod_inv_mov';
                $condicion2 = 'id_prov = cod_prov_mov';
                $condicion3 = 'id_sede = cod_sede_inv';
                $data['movimiento'] = $this->Local->get_register_join4_select($select, 'Inventario', 'Movimiento', $condicion1, 'Proveedores', $condicion2, 'Sedes', $condicion3);
                $data['proveedores'] = $this->Local->get_register('Proveedores');
                $data['inventario'] = $this->Local->get_register('Inventario');
                $data['sede'] = $this->Local->get_register('Sedes');
                $this->load->view('movimiento_inventario', $data);
                //si cumple con las validaciones
            } else {
                $descripcion = $this->input->post('descripcion1');
                $id = $this->input->post('id1');
                $dataMov = array(
                    'descripcion_mov' => $descripcion
                );
                $sql1 = $this->Local->update('Movimiento', $dataMov, 'id_mov', $id);
                if ($sql1) {
                    $this->session->set_userdata('success', '<span class="label label-success">El movimiento de inventario ha sido actualizado con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">El movimiento de inventario o no pudo ser actualizado con éxito</span>');
                }

                switch ($this->session->userdata('perfil')) {
                    case 'admin':
                        redirect(base_url().'index.php/Admin/movInventario');
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

}
?>