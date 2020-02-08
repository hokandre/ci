<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
include('ChromePhp.php');
class User extends CI_Controller 
{

public function __construct()
{
    parent::__construct();
    $this->load->model('user_model');
    $this->load->model('institusi_model');
    $this->load->model('unit_model');
}

public function index(){
  $data["title"] = "User";
  $data["data_user"] = $this->user_model->get_all();
  $data["data_institusi"] = $this->institusi_model->get();
  $data["data_unit"] = $this->unit_model->get();
  $data["action_update_user"] = site_url()."/user/update_user/";
  $data["action_form_add"] = site_url()."/user/form_add";

  $data["breadcrumb"]= [
    [
        "url" => site_url()."/user",
        "name" => "User"
    ]
  ];

  $data["error"] = null;
  $data["success"] = null;
  
  $this->load->view('user/list_user.php', $data);
}

public function form_add()
{
    $data["title"] = "Form Pengguna";
    $data["error"] = null;
    $data["success"] = null;
    $data["data_unit"] = $this->unit_model->get();
    $data["action_add_user"] = site_url()."/user/action_add";
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/user",
            "name" => "User"
        ],
        [
            "url" => site_url()."/user/form_add",
            "name" => "Form Pengguna"
        ]
      ];

    $this->load->view('user/form_user.php', $data);
   
}

public function action_add(){

    $id = $this->input->post("id");
    $nama_user = $this->input->post("nama_user");
    $unit_id = $this->input->post("unit_id");
    $password = $this->input->post("password");

    $data["id"] = $id;
    $data["nama_user"] = $nama_user;
    $data["unit_id"] = $unit_id;
    $data["password"] = $password;

    $data["error"] = null;
    $data["success"] = null;
    $data["title"] = "Form Pengguna";
    $data["data_unit"] = $this->unit_model->get();
    $data["action_add_user"] = site_url()."/user/action_add";
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/user",
            "name" => "User"
        ],
        [
            "url" => site_url()."/user/form_add",
            "name" => "Form Pengguna"
        ]
    ];

    $error = null;
    if(empty($id)){
        $error = [
            "Id" => "tidak boleh kosong"
        ];
    }else if(empty($nama_user)){
        $error = [
            "Nama User" => "tidak boleh kosong"
        ];
    }else if(empty($unit_id)){
        $error = [
            "Unit" => "tidak boleh kosong"
        ];
    }else if(empty($password)){
        $error = [
            "Password" => "tidak boleh kosong"
        ];
    }

    if(!empty($id)){
        $user = $this->user_model->get_user_by_id($id);
        if($user){
            $error = [
                "Id" => "telah digunakan"
            ];
        }else if(strlen($id) !== 6){
            $error = [
                "Id" => "harus terdiri dari 6 angka"
            ];
        }
    }


    if(isset($error)){
        $data["error"] = $error;
    }else{
        $data_new["id"] = $id;
        $data_new["nama_user"] = $nama_user;
        $data_new["unit_id"] = $unit_id;
        $data_new["password_user"] = $password;
        $insert_id = $this->user_model->create($data_new);
        $data["success"] = site_url()."/user/index/".true;
    }

    $this->load->view('user/form_user.php', $data);
}

public function update_user($id)
{
    $nama_user = $this->input->post("nama_user");
    $unit_id = $this->input->post("unit_id");
    $status = $this->input->post("status");

    $data["title"] = "User";
    $data["data_user"] = $this->user_model->get_all();
    $data["data_institusi"] = $this->institusi_model->get();
    $data["data_unit"] = $this->unit_model->get();
    $data["action_update_user"] = site_url()."/user/update_user/";
    $data["action_form_add"] = site_url()."/user/form_add";
  
    $data["breadcrumb"]= [
      [
          "url" => site_url()."/user",
          "name" => "User"
      ]
    ]; 

    if(empty($nama_user)){

       
        $data["success"] = null;
        
        $data["error"] = [
            "Nama User" => "tidak boleh kosong"
        ];
        $this->load->view('user/list_user.php', $data);
    }else{
        $data_updated["nama_user"]  = $nama_user;
        $data_updated["unit_id"] = $unit_id;
        $data_updated["status"] = $status;
        $affected_rows = $this->user_model->update_user($id, $data_updated);
        $data["error"] = null;
        $data["success"] = site_url()."/user";
        $this->load->view('user/list_user.php', $data);

    }


}

public function account(){
    $data["title"] = "Account Pengguna";
    $data['action_update_password'] = site_url()."/user/update_password";
    $data["error"] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];
    $data["success"] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];

    $user_logged_in_id = $this->session->userdata("id");
    $user = $this->user_model->get_user_by_id($user_logged_in_id);

    $data["user_id"] = $user->id;
    $data["nama_user"] = $user->nama_user;
    $data["nama_unit"] = $user->nama_unit;
    $data["nama_institusi"] = $user->nama_institusi;
    $data["password"] = "";

    $this->load->view("user/account_user.php", $data);
}

public function update_password(){
    $data["title"] = "Account Pengguna";
    $data['action_update_password'] = site_url()."/user/update_password";
    $data["error"] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];
    $data["success"] = [
        "key" => "",
        "message" => "",
        "status" => false
    ];

    $user_logged_in_id = $this->session->userdata("id");
    $user = $this->user_model->get_user_by_id($user_logged_in_id);

    $data["user_id"] = $user->id;
    $data["nama_user"] = $user->nama_user;
    $data["nama_unit"] = $user->nama_unit;
    $data["nama_institusi"] = $user->nama_institusi;
    $data["password"] = "";

    $password = $this->input->post("password");

    if(empty($password)){
        $data["error"]["key"] = "Password";
        $data["error"]["message"] = "Tidak Boleh Kosong";
        $data["error"]["status"] = true;

    }else{
        $data["password"] = $password;
        $affected_row = $this->user_model->update_password($user_logged_in_id, $password);
        $data["success"]["key"] = "Password";
        $data["success"]["message"] = "Berhasil Diubah";
        $data["success"]["status"] = true;
    }

    $this->load->view("user/account_user.php", $data);

}

public function search_user_by_name(){
    $nama_user = $this->input->get("nama_user");

    $users = $this->user_model->get_user_by_name_and_unit($nama_user);
    echo json_encode($users);
}


}

?>