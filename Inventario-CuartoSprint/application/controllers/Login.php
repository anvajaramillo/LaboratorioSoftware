<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct(){
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
        $data['error_clave']=0;
        $this->load->view('acceso',$data);
    }

    public function login()
    {
        $usr  = $this->input->post('usr');
        $pass = $this->input->post('pass');
        $sql=$this->Local->getElementWhere('Usuarios','contrasena_usu','usuario_usu',$usr);
        if($sql){
            if($sql[0]->contrasena_usu == $pass){
                $check=$this->Local->select_login($usr,$pass);
            }else{
                $data['error_clave']=1;                      //activa el mensaje diciendo que la contraseña es incorrecta
                $this->load->view('acceso',$data);
                $check=false;
            }
        }else{
            $check=$this->Local->select_login($usr,$pass);          //activa mansaje diciendo que usuario inválido
        }
        if($check == true){
            $this->session->set_userdata($check);
            $this->menu();
        }

    }

    public function menu(){
        switch ($this->session->userdata('perfil')) {
            case 'admin':
                //se llama al controlador admin index
                redirect(base_url().'index.php/Admin');
                break;
            case 'cajero':
                //se llama al controlador empresa index
                //redirect(base_url().'index.php/Cajero');
                $this->logout_ci();
                break;
            default:
                $data['titulo'] = 'Login con roles de usuario en codeigniter1';
                $this->load->view('login_view',$data);
                break;
        }
    }

    public function logout_ci() {
        $this->session->sess_destroy();
        redirect('Login');
    }


}
?>