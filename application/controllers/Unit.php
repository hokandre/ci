<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

include('ChromePhp.php');

class Unit extends CI_Controller 
{

public function __construct()
{
    parent::__construct();
    $this->load->model("user_model");
    $this->load->model('unit_model');
    $this->load->model('institusi_model');
    $this->load->model('ketua_unit_model');

}

public function index($institusi_id = null, $success = null){
   $institusi_id = isset($institusi_id) ? $institusi_id : $this->input->post('institusi_id');
   $institusi = $this->institusi_model->get();
   if(!isset($institusi_id)){
        if(sizeof($institusi) != 0){
            $institusi_id = $institusi[0]->id;
        }
   }

   $data["breadcrumb"]= [
        [
            "url" => site_url()."/unit",
            "name" => "Unit"
        ]
   ];
   $data["title"] = "Unit";
   $data["action_cari"] = site_url()."/unit";
   $data["action_detil"] = site_url()."/unit/detil/";
   $data["action_update_unit"] = site_url()."/unit/action_update_unit/";
   $data["action_form_unit"] = site_url()."/unit/form_unit";
   $data["institusi_selected"] = $institusi_id;
   $data["data_institusi"] = $institusi;
   $data["data_unit"] = $this->unit_model->get_unit_by_institusi_id($institusi_id);
   $data["error"] = null;
   $data["success"] = $success;
   $this->load->view("unit/list_unit.php", $data);
}

public function detil($unit_id){
    $data["title"] = "Detil Unit";
    $data["unit_selected"] = $this->unit_model->get_unit_by_id($unit_id);
    $data["data_anggota"] = $this->user_model->get_user_by_unit_id($unit_id);
    $data["data_user"] = $this->user_model->get_all();
    $data["action_update_ketua"] = site_url()."/unit/action_update_ketua_unit/".$unit_id;
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/unit",
            "name" => "Unit"
        ],
        [
            "url" => site_url()."/unit/detil/".$unit_id,
            "name" => "Detil"
        ]
   ];
   $this->load->view("unit/detil_unit.php", $data);
}

public function action_update_ketua_unit($unit_id){
    $ketua_unit = $this->input->post("ketua_unit");
    $affected_rows = $this->ketua_unit_model->update_ketua_unit($unit_id, $ketua_unit);
    redirect(site_url()."/unit/detil/".$unit_id);
}

public function action_update_unit($unit_id){
    $nama_unit = $this->input->post("nama_unit");
    $tenaga_pengajar = $this->input->post("tenaga_pengajar");
    $institusi_id = $this->input->post("institusi_id");

    $error = null;

    $data["title"] = "Unit";
    $data["action_cari"] = site_url()."/unit";
    $data["action_detil"] = site_url()."/unit/detil/";
    $data["action_update_unit"] = site_url()."/unit/action_update_unit/";
    $data["institusi_selected"] = $institusi_id;
    $data["data_institusi"] =  $this->institusi_model->get();
    $data["data_unit"] = $this->unit_model->get_unit_by_institusi_id($institusi_id);
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/unit",
            "name" => "Unit"
        ]
    ];

    if(empty($nama_unit)){
        $error = [
            "Nama Unit" => "tidak boleh kosong!"
        ];
    }

    if(isset($error)){
       
        $data["error"] = $error;
        $data["success"] = null;
        
    }else{
        $data_update = [
            "nama_unit" => $nama_unit,
            "tenaga_pengajar" => $tenaga_pengajar,
            "institusi_id" => $institusi_id
        ];
      
        $affected_rows = $this->unit_model->update_unit($unit_id, $data_update);
        $data["error"] = null;
        $data["success"] = site_url()."/unit";
    }
    $this->load->view("unit/list_unit.php", $data);
}


public function form_unit(){
    $data['title'] = "Form Unit";
    $data['action_add_unit'] = site_url()."/unit/add_unit";
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_user'] = $this->user_model->get_all();
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/unit",
            "name" => "Unit"
        ]
    ];

    $data['nama_unit'] = "";
    $data['ketua_unit'] = "";
    $data['institusi_id'] = "";
    $data['tenaga_pengajar'] = "";
    $data['error'] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];
    $data['success'] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];
    $this->load->view("unit/form_tambah.php", $data);
}

public function add_unit(){
    $data['title'] = "Form Unit";
    $data['action_add_unit'] = site_url()."/unit/add_unit";
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_user'] = $this->user_model->get_all();
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/unit",
            "name" => "Unit"
        ]
   ];

    $data['nama_unit'] = "";
    $data['ketua_unit'] = "";
    $data['institusi_id'] = "";
    $data['tenaga_pengajar'] = "";
    $data['error'] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];
    $data['success'] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];

    $nama_unit = $this->input->post("nama_unit");
    $institusi_id = $this->input->post("institusi_id");
    $ketua_unit = $this->input->post("ketua_unit");
    $tenaga_pengajar = $this->input->post("tenaga_pengajar");

    if(empty($nama_unit)){
       $data["error"]["key"] = "Nama Unit"; 
       $data["error"]["message"] = "Nama Unit Tidak Boleh Kosong";
       $data["error"]["status"] = true;
    }else if(empty($institusi_id)){
       $data["error"]["key"] = "Institusi"; 
       $data["error"]["message"] = "Institusi Harus Dipilih";
       $data["error"]["status"] = true;
    }else if(empty($ketua_unit)){
        $data["error"]["key"] = "Ketua Unit"; 
        $data["error"]["message"] = "Ketua Unit Harus Dipilih";
        $data["error"]["status"] = true;
    }else if($tenaga_pengajar == "" || $tenaga_pengajar == NULL){
        $data["error"]["key"] = "Tenaga Pengajar"; 
        $data["error"]["message"] = "Tenaga Penagajar Harus Dipilih";
        $data["error"]["status"] = true;
    }else{
        $data['nama_unit'] = $nama_unit;
        $data['ketua_unit'] = $ketua_unit;
        $data['institusi_id'] = $institusi_id;
        $data['tenaga_pengajar'] = $tenaga_pengajar;
    }

    if($data["error"]["status"] == false){
        $data_new_unit = [
            "nama_unit" => $nama_unit,
            "institusi_id" => $institusi_id,
            "tenaga_pengajar" => $tenaga_pengajar,
            "ketua_unit" => $ketua_unit
        ];
        $new_unit_id = $this->unit_model->add_unit($data_new_unit);
        $data["success"]["key"] = "Unit"; 
        $data["success"]["message"] = "Data Telah Ditambahkan!";
        $data["success"]["status"] = true;
        $this->load->view("unit/form_tambah.php", $data);
    }else{
        $data["nama_unit"] = $nama_unit;
        $data["institusi_id"] = $institusi_id;
        $data["ketua_unit"] = $ketua_unit;
        $data["tenaga_pengajar"] = $tenaga_pengajar;
        $this->load->view("unit/form_tambah.php", $data);
    }
}

}

?>