<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('user_model');
        $this->load->model('unit_model');
        //semakin kecil semakin tinggi prioritas
        $this->level_hak_akses = [
            "admin" => 1,
            "manajemen" => 2,
            "ketua" => 3,
            "anggota" => 4
        ];
        $this->logged = false;
        $this->login_ketua = false;
        $this->hak_akses = $this->level_hak_akses["anggota"];
        $this->nama_hak_akses = "anggota";
        $this->user_logged = [
            "id" => null,
            "nama_user" => null
        ];
        $this->unit_user = (object)[
            "id" => null,
            "nama_unit" => "",
            "tenaga_pengajar" => "0",
            "formulir_ketua" => "0" // 0 unit tidak memiliki anggota 
        ];
        $this->unit_diketuai = [];
    }

    private function set_logged($logged){
        if($logged == true) $this->logged = true;
    }

    private function set_login_ketua($is_ketua) {
        if($is_ketua == true) $this->login_ketua = true;
    }

    private function set_hak_akses($level_hak_akses){
        $this->hak_akses = $level_hak_akses;
    }

    private function set_nama_hak_akses($nama_hak_akses){
        $this->nama_hak_akses = $nama_hak_akses;
    }

    private function set_user_logged($user_data){
        if($user_data != null && sizeof($user_data) != 0 ){
            $this->user_logged = $user_data;
        } 
    }

    private function set_unit_user($unit_user){
        if($unit_user != null && sizeof($unit_user) != 0 ){
            $this->unit_user = $unit_user;
        } 
    }

    private function set_unit_diketuai($unit_diketuai){
        if($unit_diketuai != null && sizeof($unit_diketuai) != 0 ){
            $this->unit_diketuai = $unit_diketuai;
        } 
    }

    private function set_session(){
        $all_unit = [$this->unit_user];
        foreach($this->unit_diketuai as $unit){
            array_push($all_unit, $unit);
        }

        $session_data = [
            "logged" => $this->logged,
            "login_ketua" => $this->login_ketua,
            "hak_akses" => $this->hak_akses,
            "nama_hak_akses" => $this->nama_hak_akses,
            "user_logged" => $this->user_logged,
            "unit_diketuai" => $this->unit_diketuai,
            "unit_user" => $this->unit_user,
            "all_unit_user" => $all_unit
        ];
        $this->session->set_userdata($session_data);
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
            'error' => $error
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

            $unit_diketuai = null;
            $unit_user = null;
            $user = $this->user_model->get_user_by_id($username);
            if( $user && $password == $user->password_user ){
                $unit_user = $this->unit_model->get_by_id($user->unit_id);
                $unit_diketuai = $this->unit_model->get_unit_by_ketua_id($user->id);
            } 

            if (!$user) {
                $this->index(' Username tidak terdaftar');
            }else if($user && $password != $user->password_user){
                $this->index('Password salah');
            }else {
                $this->set_logged(true);
                
                $user_logged = [
                    "id" => $user->id,
                    "nama_user" => $user->nama_user
                ];
                $this->set_user_logged($user_logged);

                $data_unit_user = (object)[
                    "id" => $unit_user->id,
                    "nama_unit" => $unit_user->nama_unit,
                    "tenaga_pengajar" => $unit_user->tenaga_pengajar,
                    "formulir_ketua" => $unit_user->jumlah_anggota == 0 ? "1" : "0"
                ];
                $this->set_unit_user($data_unit_user);
                
                $hak_akses = $this->hak_akses;
                $nama_hak_akses = $this->nama_hak_akses;
                $data_unit_diketuai = [];
                if( $unit_diketuai != null || sizeof($unit_diketuai) != 0) {
                    foreach($unit_diketuai as $unit) {
                       $data_unit = (object)[
                            "id" => $unit->id,
                            "nama_unit" => $unit->nama_unit,
                            "tenaga_pengajar" => $unit->tenaga_pengajar,
                            "formulir_ketua" => $unit->jumlah_anggota == 0 ? "1" : "0"
                        ];
                        array_push($data_unit_diketuai, $data_unit);

                        $id_unit_bpm = 12; //hak akses level 1
                        $id_unit_tingkat_management = 10; //ketua, pk1, pk2, pk3 (hak akses level 2)            
                        if ($unit->id == $id_unit_bpm) {
                            $hak_akses = $this->level_hak_akses["admin"];
                            $nama_hak_akses = "admin";
                        }else if($unit->id <= $id_unit_tingkat_management){
                            $manajemen_level = $this->level_hak_akses["manajemen"];
                            if($manajemen_level < $hak_akses){
                                $hak_akses = $manajemen_level;
                                $nama_hak_akses = "manajemen";
                            }
                        }else {
                            $unit_biasa_level = $this->level_hak_akses["ketua"];
                            if($unit_biasa_level < $hak_akses){
                                $hak_akses = $unit_biasa_level;
                                $nama_hak_akses = "ketua";
                            }
                        }
                    }
                }

                if(sizeof($data_unit_diketuai) != 0) {
                    $this->set_login_ketua(true);
                    $this->set_unit_diketuai($data_unit_diketuai);
                } 
                $this->set_hak_akses($hak_akses);
                $this->set_nama_hak_akses($nama_hak_akses);

                $this->set_session();
                redirect("/bidang/pencapaian_institusi");
            }
        }

    }

    public function logout(){
      $this->session->sess_destroy();
	  redirect(site_url().'/auth');
    }
}
?>