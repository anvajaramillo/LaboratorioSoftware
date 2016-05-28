<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Factura extends CI_Controller
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
        require_once(RUTA_PDF.'class.ezpdf.php');
    }

    public function index()
    {
        null;
    }

    public function crearFactura(){
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('sede', 'sede', 'trim|required|numeric');
            $this->form_validation->set_rules('identificacion', 'identificacón del cliente', 'trim|required|is_natural');
            $this->form_validation->set_rules('producto', 'producto', 'trim|required|numeric');
            $this->form_validation->set_rules('cantidad', 'cantidad', 'trim|required|is_natural');
            $this->form_validation->set_rules('band', 'band', 'trim|required|numeric');
            $this->form_validation->set_rules('id', 'id', 'trim|required|numeric');
            $this->form_validation->set_error_delimiters('<div class="alert alert-danger alert-error" style="padding:7px; margin:7px 0 -8px 0">
                                                        <a href="#" class="close" data-dismiss="alert">&times;</a>', '
                                                    </div>
                                                    ');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $sql = "SELECT * FROM Facturas
              JOIN Facturas_Cliente
              ON id_fact = cod_fact_fact_cli
              JOIN Clientes
              ON cod_cli_fact_cli = id_cli
              GROUP BY id_fact";
                $data['facturas'] = $this->Local->get_register_sql($sql);
                $data['sede'] = $this->Local->get_register('Sedes');
                $data['bool'] = 0;
                $this->session->set_userdata('id', 0);
                $this->load->view('facturas', $data);
                //si cumple con las validaciones
            } else {
                $sede = $this->input->post('sede');
                $cliente = $this->input->post('identificacion');
                $producto = $this->input->post('producto');
                $cantidad = $this->input->post('cantidad');
                $band = $this->input->post('band');
                $id = $this->input->post('id');
                $existe = 0;

                $sql = $this->Local->getElementWhere('Clientes', 'nombre_cli', 'identificacion_cli', $cliente);
                if (count($sql) > 0) {
                    $sql = $this->Local->getElementWhere('Inventario', 'nombre_inv,cantidad_prod_inv,cod_sede_inv', 'cod_prod_inv', $producto);
                    if (count($sql) > 0) {
                        foreach ($sql as $key) {
                            if ($key->cod_sede_inv == $sede) {
                                if ($key->cantidad_prod_inv == 0) {
                                    $this->session->set_userdata('success', '<span class="label label-danger">El producto con código ' . $producto . ' se encuentra agotado en la sede seleccionada</span>');
                                    redirect(base_url() . 'index.php/Admin/facturas');
                                }
                                if ($cantidad > $key->cantidad_prod_inv) {
                                    $this->session->set_userdata('success', '<span class="label label-danger">El producto con código ' . $producto . ' tiene un cantidad menor a la solicitada en la sede seleccionada</span>');
                                    redirect(base_url() . 'index.php/Admin/facturas');
                                }
                                $existe = 1;
                                break;
                            }
                        }
                        if ($existe == 0) {
                            $this->session->set_userdata('success', '<span class="label label-danger">No existe producto con código ' . $producto . ' en el inventario de la sede seleccionada</span>');
                            redirect(base_url() . 'index.php/Admin/facturas');
                        }
                    } else {
                        $this->session->set_userdata('success', '<span class="label label-danger">No existe producto con código ' . $producto . ' en el inventario</span>');
                        redirect(base_url() . 'index.php/Admin/facturas');
                    }
                } else {
                    $this->session->set_userdata('success', '<span class="label label-danger">No existe cliente con el número de identificación ' . $cliente . ', debe realizar el ingreso del cliente.</span>');
                    redirect(base_url() . 'index.php/Admin/facturas');
                }

                if ($cantidad <= 0) {
                    $this->session->set_userdata('success', '<span class="label label-danger">La cantidad del producto debe ser mayor a cero</span>');
                    redirect(base_url() . 'index.php/Admin/facturas');
                }

                if ($id == 0) {
                    $sql3 = $this->Local->get_register3('Inventario', 'cod_prod_inv', $producto, 'cod_sede_inv', $sede);
                    $sql5 = $this->Local->get_register2('Clientes', 'identificacion_cli', $cliente);
                    $data = array(
                        'cod_sede_fact' => $sede,
                        'fecha_fact' => $fecha = date("Y-m-d")
                    );
                    $sql = $this->Local->add('Facturas', $data);
                    $id_fact = $this->db->insert_id();
                    $this->session->set_userdata('id', $id_fact);
                    $data = array(
                        'cod_fact_fact_cli' => $id_fact,
                        'cod_cli_fact_cli' => $sql5[0]->id_cli,
                        'cod_inv_fact_cli' => $sql3[0]->id_inv,
                        'cant_prod_fact_cli' => $cantidad
                    );
                    $sql = $this->Local->add('Facturas_Cliente', $data);
                    $data = array(
                        'cantidad_prod_inv' => $sql3[0]->cantidad_prod_inv - $cantidad,
                    );
                    $sql4 = $this->Local->update('Inventario', $data, 'id_inv', $sql3[0]->id_inv);
                } else {
                    $con = $this->Local->get_register_join2_where_and('Facturas_Cliente', 'Inventario', 'id_inv = cod_inv_fact_cli', 'cod_fact_fact_cli', $id, 'cod_prod_inv', $producto);
                    if (count($con) > 0) {
                        $data = array(
                            'cant_prod_fact_cli' => $con[0]->cant_prod_fact_cli + $cantidad,
                        );
                        $sql4 = $this->Local->update('Facturas_Cliente', $data, 'id_fact_cli', $con[0]->id_fact_cli);
                        $this->session->set_userdata('id', $id);
                        $sql3 = $this->Local->get_register3('Inventario', 'cod_prod_inv', $producto, 'cod_sede_inv', $sede);
                        $data = array(
                            'cantidad_prod_inv' => $sql3[0]->cantidad_prod_inv - $cantidad,
                        );
                        $sql4 = $this->Local->update('Inventario', $data, 'id_inv', $sql3[0]->id_inv);
                    } else {
                        $sql3 = $this->Local->get_register3('Inventario', 'cod_prod_inv', $producto, 'cod_sede_inv', $sede);
                        $sql5 = $this->Local->get_register2('Clientes', 'identificacion_cli', $cliente);
                        $this->session->set_userdata('id', $id);
                        $data = array(
                            'cod_fact_fact_cli' => $id,
                            'cod_cli_fact_cli' => $sql5[0]->id_cli,
                            'cod_inv_fact_cli' => $sql3[0]->id_inv,
                            'cant_prod_fact_cli' => $cantidad
                        );
                        $sql = $this->Local->add('Facturas_Cliente', $data);

                        $data = array(
                            'cantidad_prod_inv' => $sql3[0]->cantidad_prod_inv - $cantidad,
                        );
                        $sql4 = $this->Local->update('Inventario', $data, 'id_inv', $sql3[0]->id_inv);
                    }
                }

                if ($band == 1) {
                    $sql1 = $this->Local->getElementWhere('Sedes', 'id_sede,nombre_sede', 'id_sede', $sede);
                    $this->session->set_userdata('id_sede', $sql1[0]->id_sede);
                    $this->session->set_userdata('nombre_sede', $sql1[0]->nombre_sede);
                    $sql2 = $this->Local->getElementWhere('Clientes', 'nombre_cli,identificacion_cli', 'identificacion_cli', $cliente);
                    $this->session->set_userdata('ident_cliente', $sql2[0]->identificacion_cli);
                    $this->session->set_userdata('nombre_cliente', $sql2[0]->nombre_cli);

                    $sql = "SELECT * FROM Facturas
                      JOIN Facturas_Cliente
                      ON id_fact = cod_fact_fact_cli
                      JOIN Clientes
                      ON cod_cli_fact_cli = id_cli
                      GROUP BY id_fact";
                    $data['facturas'] = $this->Local->get_register_sql($sql);
                    $data['sede'] = $this->Local->get_register('Sedes');
                    $data['bool'] = $this->input->post('band');
                    $this->load->view('facturas', $data);
                } else {
                    if ($sql and $sql4) {
                        $this->session->set_userdata('success', '<span class="label label-success">La factura ha sido guardada con éxito</span>');
                    } else {
                        $this->session->set_userdata('success', '<span class="label label-danger">La factura no puedo ser guardada con éxito</span>');
                    }
                    redirect(base_url() . 'index.php/Admin/facturas');
                }

            }
        }
    }

    public function eliminarFactura(){
        if($this->session->userdata('perfil') == FALSE || $this->session->userdata('is_logued_in') != 1) {
            redirect(base_url().'index.php/Login');
        }else {
            $this->form_validation->set_rules('id2', 'id', 'trim|required|numeric');
            //si no cumple con las validaciones
            if (!$this->form_validation->run()) {
                $sql = "SELECT * FROM Facturas
              JOIN Facturas_Cliente
              ON id_fact = cod_fact_fact_cli
              JOIN Clientes
              ON cod_cli_fact_cli = id_cli
              GROUP BY id_fact";
                $data['facturas'] = $this->Local->get_register_sql($sql);
                $data['sede'] = $this->Local->get_register('Sedes');
                $data['bool'] = 0;
                $this->session->set_userdata('id', 0);
                $this->load->view('facturas', $data);
                //si cumple con las validaciones
            } else {
                $id = $this->input->post('id2');
                $sql1 = $this->Local->get_register_join2_where('Facturas_Cliente', 'Inventario', 'id_inv = cod_inv_fact_cli', 'cod_fact_fact_cli', $id);
                foreach ($sql1 as $key) {
                    $data = array('cantidad_prod_inv' => $key->cantidad_prod_inv + $key->cant_prod_fact_cli);
                    $sql2 = $this->Local->update('Inventario', $data, 'id_inv', $key->id_inv);
                    $data = array('id_fact_cli' => $key->id_fact_cli);
                    $sql3 = $this->Local->delete('Facturas_Cliente', $data);
                }
                $data = array('id_fact' => $id);
                $sql4 = $this->Local->delete('Facturas', $data);
                if ($sql2 and $sql3 and $sql4) {
                    $this->session->set_userdata('success', '<span class="label label-success">La factura ha sido anulada con éxito</span>');
                } else {
                    $this->session->set_userdata('success', '<span class="label label-success">La factura ha sido anulada con éxito</span>');
                }
                redirect(base_url() . 'index.php/Admin/facturas');
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

    public function ObtenerItems1(){
        $id_sede = $_POST['id_sede'];
        $sql2=$this->Local->get_register2('Sedes','id_sede',$id_sede);
        $datos='<center>'.$sql2[0]->nombre_empr_sede.'</center>
                <center>NIT '.$sql2[0]->nit_empr_sede.'</center>
                <center>regimen '.$sql2[0]->regimen_empr_sede.'</center>
                <center>sede '.$sql2[0]->nombre_sede.'</center>
                <center>'.$sql2[0]->direccion_sede.'</center>';
        echo $datos;
    }

    public function ObtenerItems2(){
        $id_fact = $_POST['id_fact'];
        $id_cli = $_POST['id_cli'];
        $sql1=$this->Local->get_register2('Facturas','id_fact',$id_fact);
        $sql2=$this->Local->get_register2('Clientes','identificacion_cli',$id_cli);
        $datos='<center>factura de venta No. '.$id_fact.'</center>
                <center>fecha: '.$sql1[0]->fecha_fact.'</center>
                <center>cliente: '.$sql2[0]->nombre_cli.'</center>
                <center>identificación: '.$id_cli.'</center>';
        echo $datos;

    }

    public function ObtenerItems3(){
        $id_fact = $_POST['id_fact'];
        $sql1=$this->Local->get_register_join2_where('Facturas_Cliente', 'Inventario', 'id_inv = cod_inv_fact_cli','cod_fact_fact_cli',$id_fact);
        $datos = '<tbody>
                 <tr>
                    <th>&nbsp&nbspCódigo Producto&nbsp&nbsp</th>
                    <th>&nbsp&nbspNombre Producto&nbsp&nbsp</th>
                    <th>&nbsp&nbspCantidad Producto&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor Base&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor IVA&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor Unitario&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor Compra&nbsp&nbsp</th>
                 </tr>';
        $total=0;
        foreach($sql1 as $key){
            $compra=$key->valor_venta_con_iva_inv;
            $iva=$compra*$key->iva_inv;
            $base=$compra-$iva;
            $datos=$datos.'<tr>
                        <td>&nbsp&nbsp'.$key->cod_prod_inv.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$key->nombre_inv.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$key->cant_prod_fact_cli.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$base.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$iva.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$compra.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$compra*$key->cant_prod_fact_cli.'&nbsp&nbsp</td>
                    </tr>';
            $total=$total+$compra*$key->cant_prod_fact_cli;
        }
        $datos=$datos.'<tr><td>&nbsp&nbsp</td><td>&nbsp&nbsp</td><td>&nbsp&nbsp</td><td>&nbsp&nbsp</td></tr>
                      <tr>
                        <td>&nbsp&nbspTotal Compra&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$total.'&nbsp&nbsp</td>
                      </tr>
                     </tbody>';
        echo $datos;
    }

    public function ObtenerPDF(){
        $id_fact = $_POST['id_fact'];
        $id_sede = $_POST['id_sede'];
        $id_cli = $_POST['ident_cli'];
        $sql1=$this->Local->get_register2('Sedes','id_sede',$id_sede);
        $sql2=$this->Local->get_register2('Facturas','id_fact',$id_fact);
        $sql3=$this->Local->get_register2('Clientes','identificacion_cli',$id_cli);
        $sql4=$this->Local->get_register_join2_where('Facturas_Cliente', 'Inventario', 'id_inv = cod_inv_fact_cli','cod_fact_fact_cli',$id_fact);

        $pdf = new Cezpdf('LETTER');
        $pdf->selectFont(RUTA_PDF.'fonts/Helvetica.afm');

        $pdf->ezText($sql1[0]->nombre_empr_sede,16,array('justification'=>'center'));
        $pdf->ezText("NIT ".$sql1[0]->nit_empr_sede,16,array('justification'=>'center'));
        $pdf->ezText('Regimen '.$sql1[0]->regimen_empr_sede,16,array('justification'=>'center'));
        $pdf->ezText('Sede '.$sql1[0]->nombre_sede,16,array('justification'=>'center'));
        $pdf->ezText($sql1[0]->direccion_sede."\n\n",16,array('justification'=>'center'));

        $pdf->ezText('Factura de venta No. '.$id_fact,14,array('justification'=>'center'));
        $pdf->ezText('Fecha: '.$sql2[0]->fecha_fact,14,array('justification'=>'center'));
        $pdf->ezText('Cliente: '.$sql3[0]->nombre_cli,14,array('justification'=>'center'));
        $pdf->ezText('Identificacion: '.$id_cli."\n\n",14,array('justification'=>'center'));

        $titles = array('codigo'=>'<b>Código Producto</b>', 'nombre'=>'<b>Nombre Producto</b>', 'cantidad'=>'<b>Cantidad Producto</b>','base'=>'<b>Valor Base</b>', 'iva'=>'<b>Valor IVA</b>', 'unitario'=>'<b>Valor Unitario</b>', 'compra'=>'<b>Valor Compra</b>');

        $total=0;
        foreach($sql4 as $key){
            $compra=$key->valor_venta_con_iva_inv;
            $iva=$compra*$key->iva_inv;
            $base=$compra-$iva;
            $data[] = array('codigo'=>$key->cod_prod_inv, 'nombre'=>$key->nombre_inv,'cantidad'=>$key->cant_prod_fact_cli, 'base'=>$base, 'iva'=>$iva, 'unitario'=>$compra, 'compra'=>$compra*$key->cant_prod_fact_cli);
            $total=$total+$compra*$key->cant_prod_fact_cli;
        }
        $data[] = array('codigo'=>' ', 'nombre'=>' ', 'base'=>' ', 'iva'=>' ', 'compra'=>' ');
        $data[] = array('codigo'=>'Total Compra', 'nombre'=>$total, 'base'=>' ', 'iva'=>' ', 'compra'=>' ');
        $pdf->ezTable($data,"5",$titles);
        $output=$pdf->ezOutput();
        file_put_contents(RUTA.'Archivos/inventario.pdf',$output);

        echo "exito";
    }

}
?>