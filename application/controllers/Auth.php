<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
include 'ChromePhp.php';
class Auth extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->model('unit_model');
    }

    public function __rules(){
		$this->form_validation->set_error_delimiters('<div class="text-danger">','</div>');
		$this->form_validation->set_rules('username',"Username","trim|required");
		$this->form_validation->set_rules('password',"Password","trim|required");
	}

    public function index($error = NULL){
        if($this->session->userdata('logged') == 1){
			redirect('dashboard');
        }
        
        $data = array(
			'action' => site_url().'/auth/login',
			'error' => $error,
        );
		$this->load->view('login',$data);
    }

    public function login(){
        $this->__rules();
        
        if($this->form_validation->run() == FALSE){
            $this->index();
        }else{
            $username =  $this->input->post("username");
            $password = $this->input->post("password"); 
    
            $user = $this->user_model->get_user_by_id($username);
            if($user){
                if($password == $user->password_user){
                    //hak akses 
                    $data_unit = $this->unit_model->get_unit_by_ketua_id($user->id);
                   if($data_unit){
                        //level 1 : bpm 
                        if($data_unit->id == 12) {
                            $user->hak_akses = 1;
                        }
                        //level 2 : Manajemen (PK1, ...,PK3 )
                        else if ($data_unit->id <= 10){
                            $user->hak_akses = 2;
                        }
                        //level 3 : Lppm
                        else if($data_unit->id == 25){
                            $user->hak_akses = 3;
                        }
                        //ketua unit biasa
                        else {
                            $user->hak_akses = 4;
                        } 
                        $user->ketua_unit = $data_unit->id;    
                        $user->nama_unit_diketuai = $data_unit->nama_unit;
                        $user->jenis_unit_diketuai = $data_unit->tenaga_pengajar;
                   }
                   //anggota biasa
                   else{
                        $user->hak_akses = 5;
                   }
                   $user->logged = true;
                   $this->session->set_userdata(json_decode(json_encode($user), true));
                   redirect('dashboard');
                }else{
                    $this->index('Password salah');
                }
            }else{
            //user tidak ada
                $this->index('Username tidak terdaftar!');
            }
        }

    }

    public function logout()
	{
      $this->session->sess_destroy();
	  redirect(site_url().'/auth');
	}


}


?>