<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
include 'ChromePhp.php';

class Indikator extends CI_Controller 
{

public function __construct()
{
    parent::__construct();
    $this->load->model('indikator_model');
    $this->load->model('kamus_indikator_model');
    $this->load->model('unit_model');
    $this->load->model('institusi_model');
    $this->load->model('renstra_periode_model');
    $this->load->model('periode_model');
    $this->load->model('user_model');
}

public function index($error=null){
    $versi = $this->input->get("versi", true);
    $data["versi"] = ($versi == NULL) ? "indikator" : $versi;
    $data["error"] = $error;
    $result = [];
    if($versi == "unit"){
        $data["data_unit"] = $this->unit_model->get_with_institusi();
    }else{
        $result = $this->indikator_model->get();   
    }

    $data["data_indikator"] = $result;
    $data["title"] = "Indikator";
    $data["action_lihat"] = site_url()."/indikator";
    $data["action_lihat_indikator_unit"] = site_url()."/indikator/indikator_unit";
    $data["action_add"] = site_url()."/indikator/add";
    $data["action_detil"] = site_url()."/indikator/detil/";
    $data["action_lihat_pencapaian"] = site_url()."/indikator/pencapaian_unit";
    $data["action_update"] = site_url()."/indikator/update/";
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/indikator",
            "name" => "Indikator / Sasaran Strategis"
        ]
    ];

    if($versi == "unit"){
        $this->load->view("indikator/list_unit_indikator.php", $data);
    }else{
        $this->load->view("indikator/list_indikator.php", $data);
    }
}

public function add()
{
    $nama_indikator = $this->input->post("nama_indikator");
    if(empty($nama_indikator)){
        $this->index('Nama indikator harus diisi.');
    }else{
        $data["nama_indikator"] = $nama_indikator;
        $this->indikator_model->add($data);
        redirect(site_url('indikator'));
    }
}

public function update($id)
{
    $nama_indikator = $this->input->post("nama_indikator");
    if(empty($nama_indikator)){
        $this->index('Nama indikator harus diisi.');
    }else{
        $data["nama_indikator"] = $nama_indikator;
        $affected_rows = $this->indikator_model->update($id,$data);
        redirect(site_url('indikator'));
    }
}

public function detil($id)
{
    
    $indikator = $this->indikator_model->get_by_id($id);
    $kamus_indikator = $this->kamus_indikator_model->get_by_indikator_id($id);
    $indikator->detil = $kamus_indikator;
    $data["error"] = null;
    $data["title"] = "Detil Indikator";
    $data["data_unit"] = $this->unit_model->get();
    $data["indikator"] = $indikator;
    $data["action_add_detil"] = site_url()."/indikator/add_detil";
    $data["action_delete_detil"] = site_url()."/indikator/delete_detil/";
    $data["breadcrumb"]= [
        [
            "url" => site_url()."/indikator",
            "name" => "Indikator / Sasaran Strategis"
        ],
        [
            "url" => site_url()."/indikator/detil/".$id,
            "name" => "Detil"
        ]
    ];
    $this->load->view("indikator/detil_indikator.php", $data);
}

public function add_detil()
{

    $indikator_id = $this->input->post("indikator");
    $unit_id = $this->input->post("unit");

    if($this->kamus_indikator_model->get_by_unit_and_indikator_id($unit_id, $indikator_id) != null ){
        $indikator = $this->indikator_model->get_by_id($indikator_id);
        $kamus_indikator = $this->kamus_indikator_model->get_by_indikator_id($indikator_id);
        $indikator->detil = $kamus_indikator;
        $data["error"] = [
            "Duplikat!" => "Unit! sudah pernah didaftarkan"
        ];
        $data["title"] = "Detil Indikator";
        $data["data_unit"] = $this->unit_model->get();
        $data["indikator"] = $indikator;
        $data["action_add_detil"] = site_url()."/indikator/add_detil";
        $data["action_delete_detil"] = site_url()."/indikator/delete_detil/";
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/indikator",
                "name" => "Indikator / Sasaran Strategis"
            ],
            [
                "url" => site_url()."/indikator/detil/".$indikator_id,
                "name" => "Detil"
            ]
        ];
        $this->load->view("indikator/detil_indikator.php", $data);
    }else{
        $data["indikator_id"] = $indikator_id;
        $data["unit_id"] = $unit_id;
        $this->kamus_indikator_model->add_detil($data);
        redirect('indikator/detil/'.$indikator_id);
    }
}

public function delete_detil($id, $unit_id)
{
    $this->kamus_indikator_model->delete_detil($id,$unit_id);
    redirect('indikator/detil/'.$id);
}

public function pencapaian_unit(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_institusi = $this->input->post("institusi_id");
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_periode_tahun_semetser = $this->input->post("periode_id");
    $selected_renstra_periode = $this->input->post("renstra_periode");


    $data['title'] = "Sasaran Strategis Institusi";
    $data['versi'] = "unit";
    $data['selected_institusi'] = $selected_institusi;
    $data['selected_unit'] = $selected_unit;
    $data['nama_unit'] = "";
    $data['ketua_unit'] = $is_ketua;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_unit'] = $this->unit_model->get_unit_by_institusi_id($data['data_institusi'][0]->id);
    if($selected_institusi == null){
        if(isset($selected_unit)){
          $institusi_id = $this->unit_model->get_unit_by_id($selected_unit)->institusi_id; 
          $selected_institusi =  $institusi_id;
          $data['selected_institusi'] = $selected_institusi;
        }else{
            $selected_institusi =  $data['data_institusi'][0]->id;
            $data['selected_institusi'] = $selected_institusi;
        }
    }else{
        $data['data_unit'] = $this->unit_model->get_unit_by_institusi_id($selected_institusi);
    }

    if($this->session->userdata("hak_akses") != 1 || $data['mode_individu'] == true) {
        $data["data_unit"] = [
            (object) [
                "nama_unit" => $this->session->userdata("nama_unit"),
                "unit_id" => $this->session->userdata("unit_id"),
                "tenaga_pengajar" => $this->session->userdata("tenaga_pengajar"),
                "ketua" => "0",
                "view" => "user"
            ]  
        ];

        if($this->session->userdata("ketua_unit") != null){
            array_push($data["data_unit"], (object)[
                "nama_unit" => "Ketua ".$this->session->userdata("nama_unit_diketuai"),
                "unit_id" => $this->session->userdata("ketua_unit"),
                "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
                "ketua" => "1",
                "view" => "unit"
            ]);
        }

        if($this->session->userdata("ketua_unit") != $this->session->userdata("unit_id")){
            array_push($data["data_unit"], (object)[
                "nama_unit" => $this->session->userdata("jenus_unit_diketuai") == "0" ? "Anggota ".$this->session->userdata("nama_unit_diketuai") : "Dosen ".$this->session->userdata("nama_unit_diketuai"),
                "unit_id" => $this->session->userdata("ketua_unit"),
                "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
                "ketua" => "0",
                "view" => "unit"
            ]);
        }
    }

    if($selected_unit == null){
        $selected_unit = $data['data_unit'][0]->id;
        $data['selected_unit'] = $selected_unit;
        $is_ketua = ($data['data_unit'][0]->jumlah_anggota > 0 ) ? "1" : "0";
        $data['ketua_unit'] = $is_ketua;
        if($data['data_unit'][0]->jumlah_anggota == 0) {
            $data["nama_unit"] = $data['data_unit'][0]->nama_unit;
        }else {
            if($is_ketua == "1") {
                $data["nama_unit"] = "Ketua ".$data['data_unit'][0]->nama_unit;
            }else{
                $data["nama_unit"] = ($data['data_unit'][0]->tenaga_pengajar == "1") ? "Dosen ".$data['data_unit'][0]->nama_unit : "Anggota ".$data['data_unit'][0]->nama_unit;
            }
        }
    }else{
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
        if(isset($objUnit)){
            if($objUnit->jumlah_anggota == 0) {
                $data["nama_unit"] = $objUnit->nama_unit;
            }else {
                if($is_ketua == "1") {
                    $data["nama_unit"] = "Ketua ".$objUnit->nama_unit;
                }else{
                    $data["nama_unit"] = ($objUnit->tenaga_pengajar == "1") ? "Dosen ".$objUnit->nama_unit : "Anggota ".$objUnit->nama_unit;
                }
            }
        }
    }

    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_indikator_institusi'] = site_url()."/indikator/pencapaian_institusi";
    $data['action_lihat_indikator_unit'] = site_url()."/indikator/pencapaian_unit";
    $data['action_lihat_indikator_user'] = site_url()."/indikator/pencapaian_user";

    $selected_periode_id =  $data['data_periode'][0]->id;

    if($selected_periode_tahun_semetser == null && $selected_renstra_periode == null){
       
        $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
        $data['selected_renstra_periode'] = $selected_renstra_periode;
        $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($array_periode_id) != 0){
            $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
            $selected_periode_id = $array_periode_id[0]->id;
        }else{
            //default value periode belum ada
            $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
        }
    }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser != null){
        $year = explode("-", $selected_periode_tahun_semetser)[0];
        $semester = explode("-", $selected_periode_tahun_semetser)[1];

        $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
        $selected_periode_id = $cur_periode[0]->id;
        $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
    }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser == null){
        $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
        $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($array_periode_id) != 0){
            $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
            $selected_periode_id = $array_periode_id[0]->id;
        }else{
            //default value periode belum ada
            $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
        }
    }

   

   

    $data["data_kinerja_saat_ini"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100,
        "actual_score" => 0,
        "max_score" => 0,
        "minus_score" => 0 
    ];
    $data["data_kinerja_anggota"] = [];
    $data["data_detil_kinerja_saat_ini"] = [];
    $data["data_kinerja_statistik"] = [];
    //formulir user 

    $array_formulir_id = [];
    $array_formulir = $this->indikator_model->get_pencapaian_indikator_by_institusi_and_unit($selected_institusi,$selected_unit,$is_ketua, $selected_periode_id);
    if(sizeof($array_formulir) != 0){
       $maximum_score = sizeof($array_formulir) * 100;
       $actual_score = 0;
       foreach($array_formulir as $formulir) {
         $formulir->score = $formulir->nilai_pencapaian_formulir;
         $actual_score += $formulir->nilai_pencapaian_formulir;
         array_push($array_formulir_id, $formulir->formulir_id);
       }


       $data["data_kinerja_saat_ini"]->tercapai = ($actual_score / $maximum_score) * 100;
       $data["data_kinerja_saat_ini"]->tidak_tercapai =  $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
       $data["data_kinerja_saat_ini"]->actual_score = $actual_score;
       $data["data_kinerja_saat_ini"]->max_score = $maximum_score;
       $data["data_kinerja_saat_ini"]->minus_score = $maximum_score - $actual_score;
       
       //usort($array_formulir, "sort_descending_by_score");
       $data["data_kinerja_anggota"] = $array_formulir;
       $data["data_detil_kinerja_saat_ini"] = $this->indikator_model->get_detil_pencapaian_indikator_unit_by_formulir($array_formulir_id);

    }
     // rentang waktu 5 tahun        
     if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->indikator_model->get_statistic_pencapaian_indikator_by_institusi_and_unit($selected_institusi, $selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
   $this->load->view('indikator/dashboard_indikator_unit.php', $data);
}

public function pencapaian_user(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_periode_tahun_semetser = $this->input->post("periode_id");
    $selected_renstra_periode = $this->input->post("renstra_periode");
    $selected_user = $this->input->post("user_id");
    $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");

    $data['title'] = "Sasaran Strategis Institusi";
    $data["versi"] = "individu";
    $data['selected_unit'] = $selected_unit;
    $data['ketua_unit'] = $is_ketua;
    $data['selected_user'] = $selected_user;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['show_bread_crumb_unit'] = '0';
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_unit'] = [];

     //bread crumb unit
     if($show_bread_crumb_unit == "1"){
        $data['show_bread_crumb_unit'] = "1";
        $selected_ketua_unit = $this->input->post("ketua_unit_crumb");
        $selected_unit_breadcrumb = $this->input->post("unit_id_crumb");
        $selected_institusi_breadcrumb = $this->input->post("institusi_id_crumb");
        $selected_periode_tahun_semetser_unit = $this->input->post("periode_id_crumb");
        $selected_renstra_periode_unit = $this->input->post("renstra_periode_crumb");

        $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $data['selected_unit_crumb'] = $selected_unit_breadcrumb;
        $data['selected_ketua_unit_crumb'] = $selected_ketua_unit;
        $data['selected_periode_tahun_semester_crumb'] = $selected_periode_tahun_semetser_unit;
        $data['selected_renstra_periode_crumb'] = $selected_renstra_periode_unit;
        $data['selected_institusi_crumb'] = $selected_institusi_breadcrumb;
        $data['selected_institusi'] = $selected_institusi_breadcrumb;
        $namaUnit = $objSelectedUnit->nama_unit;
        if($objSelectedUnit->tenaga_pengajar == "1"){
            if($selected_ketua_unit == "0"){
                $namaUnit = "Dosen ".$namaUnit;
            }else{
                $namaUnit = "Ketua ".$namaUnit;
            }
        }else{
            if($selected_ketua_unit == "0"){
                $namaUnit = "Anggota ".$namaUnit;
            }else{
                $namaUnit = "Ketua ".$namaUnit;
            }
        }
        $data["breadcrumb"]['unit'] = [
                "url" => site_url()."/indikator/pencapaian_unit",
                "name" => $namaUnit
        ];
    }


    if($selected_user == null){
        $selected_user = $this->session->userdata("id");
        $data['selected_user'] = $selected_user;
        $data['nama_user'] = $this->session->userdata("nama_user");
    }else{
        $objSelectedUser =  $this->user_model->get_user_by_id($selected_user);
        $data['nama_user'] = $objSelectedUser->nama_user;
    }

    if($selected_unit == null){
        $selected_unit = $this->session->userdata("unit_id");
        $data['selected_unit'] = $selected_unit;
        $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $is_ketua = ($objSelectedUnit->jumlah_anggota == 0) ? "1" : "0"; 
        $data["ketua_unit"] = $is_ketua;
    }    

    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_indikator_institusi'] = site_url()."/indikator/pencapaian_institusi";
    $data['action_lihat_indikator_unit'] = site_url()."/indikator/pencapaian_unit";
    $data['action_lihat_indikator_user'] = site_url()."/indikator/pencapaian_user";

    $selected_periode_id =  $data['data_periode'][0]->id;

    if($selected_periode_tahun_semetser == null && $selected_renstra_periode == null){
       
        $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
        $data['selected_renstra_periode'] = $selected_renstra_periode;
        $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($array_periode_id) != 0){
            $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
            $selected_periode_id = $array_periode_id[0]->id;
        }else{
            //default value periode belum ada
            $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
        }
    }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser != null){
        $year = explode("-", $selected_periode_tahun_semetser)[0];
        $semester = explode("-", $selected_periode_tahun_semetser)[1];

        $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
        if($cur_periode != null){
            $selected_periode_id = $cur_periode[0]->id;
        }else{
            $selected_periode_id = null;
        }
        $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
    }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser == null){
        $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
        $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($array_periode_id) != 0){
            $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
            $selected_periode_id = $array_periode_id[0]->id;
        }else{
            //default value periode belum ada
            $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
            $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
        }
    }

    $selected_obj_unit = $this->unit_model->get_unit_by_id($selected_unit);
    $namaUnit = $selected_obj_unit->nama_unit;
    if($selected_obj_unit->tenaga_pengajar == "1"){
        if($is_ketua == "0"){
            $namaUnit = "Dosen ".$namaUnit;
        }else{
            $namaUnit = "Ketua ".$namaUnit;
        }
    }else{
        if($is_ketua == "0"){
            $namaUnit = "Anggota ".$namaUnit;
        }else{
            $namaUnit = "Ketua ".$namaUnit;
        }
    }
    $selected_obj_unit->nama_unit = $namaUnit;
    $data['selected_obj_unit'] = $selected_obj_unit;
    if($this->session->userdata("hak_akses") != 1 || $data['mode_individu'] == true){
        $data["data_unit"] = [
            (object) [
                "nama_unit" => $this->session->userdata("nama_unit"),
                "unit_id" => $this->session->userdata("unit_id"),
                "tenaga_pengajar" => $this->session->userdata("tenaga_pengajar"),
                "ketua" => "0",
                "view" => "user"
            ]  
        ];

        if($this->session->userdata("ketua_unit") != null){
            array_push($data["data_unit"], (object)[
                "nama_unit" => "Ketua ".$this->session->userdata("nama_unit_diketuai"),
                "unit_id" => $this->session->userdata("ketua_unit"),
                "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
                "ketua" => "1",
                "view" => "unit"
            ]);
        }

        if($this->session->userdata("ketua_unit") != $this->session->userdata("unit_id")){
            array_push($data["data_unit"], (object)[
                "nama_unit" => $this->session->userdata("jenus_unit_diketuai") == "0" ? "Anggota ".$this->session->userdata("nama_unit_diketuai") : "Dosen ".$this->session->userdata("nama_unit_diketuai"),
                "unit_id" => $this->session->userdata("ketua_unit"),
                "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
                "ketua" => "0",
                "view" => "unit"
            ]);
        }
    }else{
        $data["data_unit"] = $this->unit_model->get ();
    }

   

   

    $data["data_kinerja_saat_ini"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100, 
        "actual_score" => 0,
        "max_score" => 0,
        "minus_score" => 0 
    ];
    $data["data_detil_kinerja_saat_ini"] = [];
    $data["data_kinerja_statistik"] = [];
    //formulir user 
    if($selected_periode_id !== null){
        $array_formulir = $this->indikator_model->get_pencapaian_indikator_by_unit_and_user($selected_user,$selected_unit,$is_ketua, $selected_periode_id);
        if($array_formulir != null){
           $array_formulir->score = $array_formulir->nilai_pencapaian_formulir;
           $data["data_kinerja_saat_ini"]->tercapai = $array_formulir->score;
           $data["data_kinerja_saat_ini"]->max_score = 100;
           $data["data_kinerja_saat_ini"]->minus_score = 100 - $array_formulir->score;
           $data["data_kinerja_saat_ini"]->actual_score = $array_formulir->score; 
           $data["data_kinerja_saat_ini"]->tidak_tercapai =  $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
           //usort($array_formulir, "sort_descending_by_score");
           $data["data_detil_kinerja_saat_ini"] = $this->indikator_model->get_detil_pencapaian_indikator_by_unit_and_user($array_formulir->formulir_id);
    
        }
    }
     // rentang waktu 5 tahun        
     if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->indikator_model->get_statistic_pencapaian_indikator_by_unit_and_user($selected_user, $selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
    $this->load->view('indikator/dashboard_indikator_user.php', $data);
}


public function indikator_unit(){
    $unit_id = $this->input->get("unit",true);
    if($unit_id == NULL){
        redirect(base_url()."/indikator");
    }

    $data["action_back"] = site_url()."/indikator?versi=unit";
    $data["selected_unit"] = $this->unit_model->get_unit_by_id($unit_id);
    $data["data_indikator"] = $this->kamus_indikator_model->get_by_unit_id($unit_id);
    $this->load->view('indikator/list_indikator_unit.php', $data);

}


// public function pencapaian_unit(){
//     $selected_unit = $this->input->post("unit_id");
//     $is_ketua = $this->input->post("ketua_unit");
//     $selected_bidang = $this->input->post("bidang_id");
//     $selected_periode_tahun_semetser = $this->input->post("periode_id");
//     $selected_renstra_periode = $this->input->post("renstra_periode");
//     $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");


//     $data['title'] = "Pencapaian Bidang Unit";
//     $data['versi'] = 'unit';
//     $data['show_bread_crumb_institusi'] = "0";
//     if($show_bread_crumb_institusi == "1"){
//         $data['show_bread_crumb_institusi'] = "1";
//         $selected_institusi = $this->input->post("institusi_id");
//         $selected_periode_tahun_semetser_institusi= $this->input->post("periode_id_institusi");
//         $selected_renstra_periode_institusi = $this->input->post("renstra_periode_institusi");
//         $objSelectedInstitusi = $this->institusi_model->get_by_id($selected_institusi);
//         $data['selected_institusi'] = $selected_institusi;
//         $data['selected_periode_tahun_semester_institusi'] = $selected_periode_tahun_semetser_institusi;
//         $data['selected_renstra_periode_institusi'] = $selected_renstra_periode_institusi;
//         $data["breadcrumb"] = [
//             [
//                 "url" => site_url()."/bidang/pencapaian_institusi",
//                 "name" => $objSelectedInstitusi->nama_institusi
//             ]
//         ];
//     }

//     $data['selected_unit'] = $selected_unit;
//     $data['ketua_unit'] = $is_ketua;
//     $data['selected_bidang'] = $selected_bidang;
//     $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//     $data['selected_renstra_periode'] = $selected_renstra_periode;
//     $data['data_unit'] = [];
//     //bpm
//     if($this->session->userdata("hak_akses") == 1){
//         $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
//         $objSelectedUnit->ketua = $is_ketua;
//         array_push($data['data_unit'], $objSelectedUnit);
//     }else{
//     //ketua unit
//         $data["data_unit"] = [
//             (object) [
//                 "nama_unit" => $this->session->userdata("nama_unit"),
//                 "id" => $this->session->userdata("unit_id"),
//                 "tenaga_pengajar" => $this->session->userdata("tenaga_pengajar"),
//                 "ketua" => "0"
//             ]  
//         ];

//         if($this->session->userdata("ketua_unit") != null){
//             array_push($data["data_unit"], (object)[
//                 "nama_unit" => "Ketua ".$this->session->userdata("nama_unit_diketuai"),
//                 "id" => $this->session->userdata("ketua_unit"),
//                 "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
//                 "ketua" => "1"
//             ]);
//         }
//     }

//     $data['data_bidang'] =  $this->bidang_model->get();
//     $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
//     $data['data_periode'] = $this->periode_model->get ();
//     $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
//     $data['action_lihat_bidang_institusi'] = site_url()."/bidang/pencapaian_institusi";
//     $data['action_lihat_bidang_unit'] = site_url()."/bidang/pencapaian_unit";
//     $data['action_lihat_bidang_user'] = site_url()."/bidang/pencapaian_user";

//     $selected_periode_id =  $data['data_periode'][0]->id;

//     if($selected_periode_tahun_semetser == null && $selected_renstra_periode == null){
       
//         $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
//         $data['selected_renstra_periode'] = $selected_renstra_periode;
//         $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($array_periode_id) != 0){
//             $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//             $selected_periode_id = $array_periode_id[0]->id;
//         }else{
//             //default value periode belum ada
//             $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//         }
               
//     }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser != null){
//         $year = explode("-", $selected_periode_tahun_semetser)[0];
//         $semester = explode("-", $selected_periode_tahun_semetser)[1];

//         $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
//         $selected_periode_id = $cur_periode[0]->id;
//         $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

//     }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser == null){
//         $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
//         $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($array_periode_id) != 0){
//             $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//             $selected_periode_id = $array_periode_id[0]->id;
//         }else{
//             //default value periode belum ada
//             $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//         }
//     }

//     if($selected_bidang == null){
//         $selected_bidang = $data['data_bidang'][0]->id;
//         $data['selected_bidang'] = $selected_bidang;
//     }

//     if($selected_unit == null){
//         $selected_unit = $data['data_unit'][0]->id;
//         $data['selected_unit'] = $selected_unit;
//         $is_ketua =  $data['data_unit'][0]->ketua;
//         $data['ketua_unit'] = $is_ketua;
//     }

//     $data["data_kinerja_saat_ini"] = (object) [
//         "tercapai" => 0,
//         "tidak_tercapai" => 100
//     ];
//     $data["data_kinerja_anggota"] = [];
//     $data["data_detil_kinerja_saat_ini"] = [];
//     $data["data_kinerja_statistik"] = [];
   
//     $array_formulir_id = [];
//     $array_formulir_anggota = $this->bidang_model->get_pencapaian_bidang_by_unit_and_periode($selected_bidang,$selected_unit,$is_ketua, $selected_periode_id);

//     if(sizeof($array_formulir_anggota) != 0){
//        $maximum_score = sizeof($array_formulir_anggota) * 100;
//        $actual_score = 0;
//        foreach($array_formulir_anggota as $formulir) {
//            $actual_score += ($formulir->nilai_pencapaian * $formulir->bobot);
//            array_push($array_formulir_id, $formulir->id);
//        }

//        $data["data_kinerja_saat_ini"]->tercapai = ($actual_score / $maximum_score) * 100;
//        $data["data_kinerja_saat_ini"]->tidak_tercapai =  $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
       
//        $array_detil_formulir = $this->bidang_model->get_detil_pencapaian_bidang_by_unit_and_formulir($selected_bidang, $array_formulir_id);
//        //usort($array_formulir, "sort_descending_by_score");
//        $data["data_kinerja_anggota"] = $array_formulir_anggota;
//        $data["data_detil_kinerja_saat_ini"] = $array_detil_formulir;
//     }

//      //rentang waktu 5 tahun        
//      if($selected_renstra_periode != null){
//         $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($rentang_periode_id) != 0){
//             $array_periode_id = [];
//             foreach($rentang_periode_id as $periode){
//                 array_push($array_periode_id, $periode->id);
//             }

//             $statistic_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_unit_and_periode($selected_bidang, $selected_unit,$is_ketua, $array_periode_id);
//             $data["data_kinerja_statistik"] = $statistic_kinerja;
//         }
//     }
//     $this->load->view('form_bidang/dashboard_bidang_unit.php', $data);
// }

// public function pencapaian_user(){
//     $selected_user = $this->input->post("user_id");
//     $selected_unit = $this->input->post("unit_id");
//     $is_ketua = $this->input->post("ketua_unit");
//     $selected_bidang = $this->input->post("bidang_id");
//     $selected_periode_tahun_semetser = $this->input->post("periode_id");
//     $selected_renstra_periode = $this->input->post("renstra_periode");
//     $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
//     $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");

//     $data['title'] = "Pencapaian Bidang Unit";
//     $data['versi'] = 'individu';
//     $data['show_bread_crumb_institusi'] = "0";
//     $data['show_bread_crumb_unit'] = "0";
//     $data["breadcrumb"] = [];
//     //bread crumb institusi
//     if($show_bread_crumb_institusi == "1"){
//         $data['show_bread_crumb_institusi'] = "1";
//         $selected_institusi = $this->input->post("institusi_id");
//         $selected_periode_tahun_semetser_institusi= $this->input->post("periode_id_institusi");
//         $selected_renstra_periode_institusi = $this->input->post("renstra_periode_institusi");
//         $objSelectedInstitusi = $this->institusi_model->get_by_id($selected_institusi);
//         $data['selected_institusi'] = $selected_institusi;
//         $data['selected_periode_tahun_semester_institusi'] = $selected_periode_tahun_semetser_institusi;
//         $data['selected_renstra_periode_institusi'] = $selected_renstra_periode_institusi;
//         $data["breadcrumb"]['institusi'] = [
//                 "url" => site_url()."/bidang/pencapaian_institusi",
//                 "name" => $objSelectedInstitusi->nama_institusi
//         ];
//     }

//     //bread crumb unit
//     if($show_bread_crumb_unit == "1"){
//         $data['show_bread_crumb_unit'] = "1";
//         $selected_ketua_unit = $this->input->post("ketua_unit");
//         $selected_unit = $this->input->post("unit_id");
//         $selected_periode_tahun_semetser_unit = $this->input->post("periode_id_unit");
//         $selected_renstra_periode_unit = $this->input->post("renstra_periode_unit");

//         $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
//         $data['selected_unit'] = $selected_unit;
//         $data['selected_ketua_unit'] = $selected_ketua_unit;
//         $data['selected_periode_tahun_semester_unit'] = $selected_periode_tahun_semetser_unit;
//         $data['selected_renstra_periode_unit'] = $selected_renstra_periode_unit;
//         $data["breadcrumb"]['unit'] = [
//                 "url" => site_url()."/bidang/pencapaian_unit",
//                 "name" => $objSelectedUnit->tenaga_pengajar == "1" ? 
//                        $selected_ketua_unit == "1" ? "Ketua ". $objSelectedUnit->nama_unit : "Dosen ". $objSelectedUnit->nama_unit 
//                     :
//                        $selected_ketua_unit == "1" ? "Ketua ". $objSelectedUnit->nama_unit : "Anggota ". $objSelectedUnit->nama_unit 
//         ];
//     }

//     ChromePhp::log($data["breadcrumb"]);

//     $data['data_bidang'] =  $this->bidang_model->get();
//     $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
//     $data['data_periode'] = $this->periode_model->get ();
   

//     $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
//     $data['action_lihat_bidang_institusi'] = site_url()."/bidang/pencapaian_institusi";
//     $data['action_lihat_bidang_unit'] = site_url()."/bidang/pencapaian_unit";
//     $data['action_lihat_bidang_user'] = site_url()."/bidang/pencapaian_user";


//     $data['selected_user'] = $selected_user;
//     $data['ketua_unit'] = $is_ketua;
//     $data['selected_bidang'] = $selected_bidang;
//     $data['selected_unit'] = $selected_unit;
//     $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//     $data['selected_renstra_periode'] = $selected_renstra_periode;

//     $selected_periode_id =  $data['data_periode'][0]->id;

//     if($selected_periode_tahun_semetser == null && $selected_renstra_periode == null){
       
//         $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
//         $data['selected_renstra_periode'] = $selected_renstra_periode;
//         $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($array_periode_id) != 0){
//             $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//             $selected_periode_id = $array_periode_id[0]->id;
//         }else{
//             //default value periode belum ada
//             $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//         }
               
//     }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser != null){
//         $year = explode("-", $selected_periode_tahun_semetser)[0];
//         $semester = explode("-", $selected_periode_tahun_semetser)[1];
//         $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
//         if($cur_periode != null){
//             $selected_periode_id = $cur_periode[0]->id;
//             $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
//         }

//     }elseif($selected_renstra_periode != null && $selected_periode_tahun_semetser == null){
//         $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
//         $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($array_periode_id) != 0){
//             $selected_periode_tahun_semetser = $array_periode_id[0]->tahun."-".$array_periode_id[0]->semester;
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//             $selected_periode_id = $array_periode_id[0]->id;
//         }else{
//             //default value periode belum ada
//             $selected_periode_tahun_semetser = $data['selected_obj_renstra_periode']->tahun_awal."-"."1";
//             $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
//         }
//     }
    
//     if($selected_user == null){
//         $selected_user = $this->session->userdata("id");
//         $data['selected_user'] = $selected_user;
//         $is_ketua = "0";
//         $data["ketua_unit"] = $is_ketua;
//         $data['selected_nama_user'] = $this->session->userdata("nama_user");
//     }else{
//         $objSelectedUser =  $this->user_model->get_user_by_id($selected_user);
//         $data['selected_nama_user'] = $objSelectedUser->nama_user;
//     }

//     if($selected_unit == null){
//         $selected_unit = $this->session->userdata("unit_id");
//         $data['selected_unit'] = $selected_unit;
//     }

//     if($selected_bidang == null){
//         $selected_bidang = $data['data_bidang'][0]->id;
//         $data['selected_bidang'] = $selected_bidang;
//     }

//     $data['data_unit'] = [];
//     if($this->session->userdata("ketua_unit") == $selected_unit){
//         $data["data_unit"] = [
//             (object) [
//                 "nama_unit" => $this->session->userdata("nama_unit"),
//                 "unit_id" => $this->session->userdata("unit_id"),
//                 "tenaga_pengajar" => $this->session->userdata("tenaga_pengajar"),
//                 "ketua" => "0"
//             ]  
//         ];

//         if($this->session->userdata("ketua_unit") != null){
//             array_push($data["data_unit"], (object)[
//                 "nama_unit" => "Ketua ".$this->session->userdata("nama_unit_diketuai"),
//                 "unit_id" => $this->session->userdata("ketua_unit"),
//                 "tenaga_pengajar" => $this->session->userdata("jenus_unit_diketuai"),
//                 "ketua" => "1"
//             ]);
//         }
//     }
//     $data["data_kinerja_saat_ini"] = (object) [
//         "tercapai" => 0,
//         "tidak_tercapai" => 100
//     ];
//     $data["data_kinerja_anggota"] = [];
//     $data["data_detil_kinerja_saat_ini"] = [];
//     $data["data_kinerja_statistik"] = [];

//     if($selected_periode_id != null){
//         $row_kinrja_user = $this->bidang_model->get_pencapaian_bidang_by_user_and_periode($selected_bidang,$selected_user, $selected_unit, $is_ketua, $selected_periode_id);
//         if($row_kinrja_user != null){
//             $data['data_kinerja_saat_ini']->tercapai = $row_kinrja_user->nilai_pencapaian * $row_kinrja_user->bobot;
//             $data['data_kinerja_saat_ini']->tidak_tercapai =  $data['data_kinerja_saat_ini']->tidak_tercapai -  $data['data_kinerja_saat_ini']->tercapai;
//             $data['data_detil_kinerja_saat_ini'] = $this->bidang_model->get_detil_pencapaian_bidang_by_user_formulir($selected_bidang, $row_kinrja_user->id);
            
//         }
//     }


//     if($selected_renstra_periode != null){
//         $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
//         if(sizeof($rentang_periode_id) != 0){
//             $array_periode_id = [];
//             foreach($rentang_periode_id as $periode){
//                 array_push($array_periode_id, $periode->id);
//             }

//             $statistic_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_unit_and_periode($selected_bidang, $selected_unit,$is_ketua, $array_periode_id);
//             $data["data_kinerja_statistik"] = $statistic_kinerja;
//         }
//     }

//     $this->load->view('form_bidang/dashboard_bidang_user.php', $data);

// }

}

?>