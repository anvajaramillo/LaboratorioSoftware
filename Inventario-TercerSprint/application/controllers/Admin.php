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
        require_once(RUTA_PDF.'class.ezpdf.php');
    }

    public function index()
    {
        $data['inventario']=$this->Local->get_register_join2('Inventario','Sedes','cod_sede_inv = id_sede');
        $data['sede']=$this->Local->get_register('Sedes');
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
        $data['sede']=$this->Local->get_register('Sedes');
        $this->load->view('movimiento_inventario',$data);
    }

    public function clientes()
    {
        $data['clientes']=$this->Local->get_register('Clientes');
        $this->load->view('clientes',$data);
    }

    public function facturas(){
        $sql="SELECT * FROM Facturas
              JOIN Facturas_Cliente
              ON id_fact = cod_fact_fact_cli
              JOIN Clientes
              ON cod_cli_fact_cli = id_cli
              GROUP BY id_fact";
        $data['facturas']=$this->Local->get_register_sql($sql);
        $data['sede']=$this->Local->get_register('Sedes');
        $data['bool']=0;
        $this->session->set_userdata('id', 0);
        $this->load->view('facturas',$data);
    }

    public function ObtenerRutaImg(){
        $id_invt=$_POST['id_invt'];
        $data=$this->Local->getElementWhere('Inventario','ruta_imagen_inv','id_inv', $id_invt);
        $img='<img src="'.RUTA_SUB.$data[0]->ruta_imagen_inv.'" width="100%" height="100%" title="producto">';
        echo $img;
    }

    public function MovSede(){
        $sede=$_POST['sede'];
        $data=$this->Local->get_register2('Inventario', 'cod_sede_inv', $sede);
        echo $_GET['callback']."(".json_encode($data).");";
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
        $existe = 0;
        $sql=$this->Local->getElementWhere('Inventario', 'nombre_inv,cantidad_prod_inv,cod_sede_inv', 'cod_prod_inv', $id);
        if(count($sql)>0){
            foreach ($sql as $key) {
                if($key->cod_sede_inv==$sede){
                    if($key->cantidad_prod_inv>0){
                        $datos=$key->nombre_inv.", Cantidad: ".$key->cantidad_prod_inv;
                    }else{
                        $datos="El producto con código ".$id." se encuentra agotado";
                    }
                    $existe = 1;
                    break;
                }
            }
            if($existe == 0){
                $datos="No existe producto con código ".$id." en el inventario de la sede seleccionada";
            }
        }else{
            $datos="No existe producto con código ".$id." en el inventario";
        }

        echo $datos;
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
        $datos='<span>factura de venta No. '.$id_fact.'</span>
                <br>
                <span>fecha: '.$sql1[0]->fecha_fact.'</span>
                <br>
                <span>cliente: '.$sql2[0]->nombre_cli.'</span>
                <br>
                <span>identificación: '.$id_cli.'</span>';
        echo $datos;

    }

    public function ObtenerItems3(){
        $id_fact = $_POST['id_fact'];
        $sql1=$this->Local->get_register_join2_where('Facturas_Cliente', 'Inventario', 'id_inv = cod_inv_fact_cli','cod_fact_fact_cli',$id_fact);
        $datos = '<tbody>
                 <tr>
                    <th>&nbsp&nbspCódigo Producto&nbsp&nbsp</th>
                    <th>&nbsp&nbspNombre Producto&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor Base&nbsp&nbsp</th>
                    <th>&nbsp&nbspValor IVA&nbsp&nbsp</th>
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
                        <td>&nbsp&nbsp'.$base.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$iva.'&nbsp&nbsp</td>
                        <td>&nbsp&nbsp'.$compra.'&nbsp&nbsp</td>
                    </tr>';
            $total=$total+$compra;
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

        $pdf->ezText('Factura de venta No. '.$id_fact,14);
        $pdf->ezText('Fecha: '.$sql2[0]->fecha_fact,14);
        $pdf->ezText('Cliente: '.$sql3[0]->nombre_cli,14);
        $pdf->ezText('Identificacion: '.$id_cli."\n\n",14);

        $titles = array('codigo'=>'<b>Código Producto</b>', 'nombre'=>'<b>Nombre Producto</b>', 'base'=>'<b>Valor Base</b>', 'iva'=>'<b>Valor IVA</b>', 'compra'=>'<b>Valor Compra</b>');

        $total=0;
        foreach($sql4 as $key){
            $compra=$key->valor_venta_con_iva_inv;
            $iva=$compra*$key->iva_inv;
            $base=$compra-$iva;
            $data[] = array('codigo'=>$key->cod_prod_inv, 'nombre'=>$key->nombre_inv, 'base'=>$base, 'iva'=>$iva, 'compra'=>$compra);
            $total=$total+$compra;
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