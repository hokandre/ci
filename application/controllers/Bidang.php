<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

include 'ChromePhp.php';

function sort_descending_by_score($form_unit_1, $form_unit_2){
    $form1Score  = ($form_unit_1->score / $form_unit_1->MAX_SCORE  ) * 100;
    $form2Score  = ($form_unit_2->score / $form_unit_2->MAX_SCORE  ) * 100;

    if($form1Score == $form2Score){
        return 0;
    }

    return ($form1Score > $form2Score ) ? -1 : 1;
}

class Bidang extends CI_Controller 
{

public function __construct()
{
    parent::__construct();
    $this->load->database();
    $this->load->model('bidang_model');
    $this->load->helper(array('form', 'url'));
    $this->load->model('institusi_model');
    $this->load->model('renstra_periode_model');
    $this->load->model('periode_model');
    $this->load->model('unit_model');
    $this->load->model('user_model');
}

public function index($error=null)
{
    $result = $this->bidang_model->get();
    $data["data_bidang"] = $result;
    $data["action_add"] = site_url()."/bidang/add";
    $data["action_update"] = site_url()."/bidang/update/";
    //bpm
    $data["action_lihat_pencapain"] = site_url()."/bidang/pencapaian_institusi";
    //user
    if($this->session->userdata("hak_akses") != 1){
        $data["action_lihat_pencapain"] = site_url()."/bidang/pencapaian_user";
    }
    $data["title"] = "Bidang KPI";
    $data["error"] = $error;
    $this->load->view('form_bidang/index.php', $data);
}

public function add()
{
    $nama_bidang = $this->input->post("nama_bidang");
    $error = null;

    if(empty($nama_bidang)){
        $error = "Nama Bidang tidak boleh kosong.";
        $this->index($error);    
    }

    if (!$error){
        $this->bidang_model->add($nama_bidang);
        redirect(site_url('bidang'));    
    }

}

public function update($bidang_id){
    $newName = $this->input->post("nama_bidang");
    if(empty($newName)){
        $error = "Nama Bidang tidak boleh kosong.";
        $this->index($error);    
    }else{
        $this->bidang_model->update($bidang_id, $newName);
        redirect(site_url('bidang'));
    }
  
}

public function pencapaian_institusi(){
    $selected_institusi = $this->input->post("institusi_id");
    $selected_bidang = $this->input->post("bidang_id");
    $selected_periode_tahun_semetser = $this->input->post("periode_id");
    $selected_renstra_periode = $this->input->post("renstra_periode");
    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");


    if($show_bread_crumb_institusi == "1"){
        $selected_bread_crumb_periode = $this->input->post("periode_id_institusi");
        $selected_bread_crumb_renstra_periode = $this->input->post("renstra_periode_institusi");
        
        $selected_periode_tahun_semetser = $selected_bread_crumb_periode;
        $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
        $selected_renstra_periode = $selected_bread_crumb_renstra_periode;
        $data['selected_renstra_periode'] = $selected_renstra_periode;
    }

    $data['title'] = "Pencapaian Bidang";
    $data['selected_institusi'] = $selected_institusi;
    $data['nama_institusi'] = "";
    $data['selected_bidang'] = $selected_bidang;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_bidang'] =  $this->bidang_model->get();
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_bidang_institusi'] = site_url()."/bidang/pencapaian_institusi";
    $data['action_lihat_bidang_unit'] = site_url()."/bidang/pencapaian_unit";
    $data['action_lihat_bidang_user'] = site_url()."/bidang/pencapaian_user";

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

    if($selected_bidang == null){
        $selected_bidang =  $data['data_bidang'][0]->id;
        $data['selected_bidang'] = $selected_bidang;
    }

    if($selected_institusi == null){
        $selected_institusi = $data['data_institusi'][0]->id;
        $data['selected_institusi'] =  $selected_institusi;
        $data['nama_institusi'] = $data['data_institusi'][0]->nama_institusi;
    }else {
        $objInstitusi = $this->institusi_model->get_by_id($selected_institusi);
        if(isset($objInstitusi)){
            $data['nama_institusi'] = $objInstitusi->nama_institusi;
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
   
    $array_formulir = $this->bidang_model->get_pencapaian_bidang_by_institusi_and_periode($selected_bidang,$selected_institusi, $selected_periode_id);
    if(sizeof($array_formulir) != 0){
       $maximum_score = sizeof($array_formulir) * 100;
       $actual_score = 0;
       foreach($array_formulir as $formulir) {
           $actual_score += ($formulir->score / $formulir->MAX_SCORE) * 100;
           $formulir->persen_ketercapaian = ($formulir->score / $formulir->MAX_SCORE) * 100;
       }

       $data["data_kinerja_saat_ini"]->tercapai = ($actual_score / $maximum_score) * 100;
       $data["data_kinerja_saat_ini"]->tidak_tercapai =  $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
       $data["data_kinerja_saat_ini"]->actual_score = $actual_score;
       $data["data_kinerja_saat_ini"]->max_score = $maximum_score;
       $data["data_kinerja_saat_ini"]->minus_score = $maximum_score - $actual_score;
       usort($array_formulir, "sort_descending_by_score");
       $data["data_kinerja_anggota"] = $array_formulir;
       $data["data_detil_kinerja_saat_ini"] = $array_formulir;
    }

     // rentang waktu 5 tahun        
     if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_institusi_and_periode($selected_bidang, $selected_institusi, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
    $this->load->view('form_bidang/dashboard_bidang_institusi.php', $data);
}

public function pencapaian_unit(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_bidang = $this->input->post("bidang_id");
    $selected_periode_tahun_semetser = $this->input->post("periode_id");
    $selected_renstra_periode = $this->input->post("renstra_periode");
    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");


    $data['title'] = "Pencapaian Bidang Unit";
    $data['versi'] = 'unit';
    $data['show_bread_crumb_institusi'] = "0";
    if($show_bread_crumb_institusi == "1"){
        $data['show_bread_crumb_institusi'] = "1";
        $selected_institusi = $this->input->post("institusi_id");
        $selected_periode_tahun_semetser_institusi= $this->input->post("periode_id_institusi");
        $selected_renstra_periode_institusi = $this->input->post("renstra_periode_institusi");
        $objSelectedInstitusi = $this->institusi_model->get_by_id($selected_institusi);
        $data['selected_institusi'] = $selected_institusi;
        $data['selected_periode_tahun_semester_institusi'] = $selected_periode_tahun_semetser_institusi;
        $data['selected_renstra_periode_institusi'] = $selected_renstra_periode_institusi;
        $data["breadcrumb"] = [
            [
                "url" => site_url()."/bidang/pencapaian_institusi",
                "name" => $objSelectedInstitusi->nama_institusi
            ]
        ];
    }

    $data['selected_unit'] = $selected_unit;
    $data["nama_unit"] = "";
    $data['ketua_unit'] = $is_ketua;
    $data['selected_bidang'] = $selected_bidang;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_unit'] = [];
    if(isset( $data['selected_institusi'])){
        $data['data_unit'] = $this->unit_model->get_unit_by_institusi_id($data['selected_institusi']);
    }
     
    if($this->session->userdata("hak_akses") != 1 || $data['mode_individu'] == true){
        $data['data_unit'] = [];
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

    }

    $data['data_bidang'] =  $this->bidang_model->get();
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_bidang_institusi'] = site_url()."/bidang/pencapaian_institusi";
    $data['action_lihat_bidang_unit'] = site_url()."/bidang/pencapaian_unit";
    $data['action_lihat_bidang_user'] = site_url()."/bidang/pencapaian_user";

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

    if($selected_bidang == null){
        $selected_bidang = $data['data_bidang'][0]->id;
        $data['selected_bidang'] = $selected_bidang;
    }

    if($selected_unit == null){
        $selected_unit = $data['data_unit'][0]->id;
        $data['selected_unit'] = $selected_unit;
        $data['ketua_unit'] = $is_ketua;
        //jika 0 maka unit ketua
        $is_ketua =  $data['data_unit'][0]->jumlah_anggota == 0 ? "1" : "0";
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
   
    $array_formulir_id = [];
    $array_formulir_anggota = $this->bidang_model->get_pencapaian_bidang_by_unit_and_periode($selected_bidang,$selected_unit,$is_ketua, $selected_periode_id);

    if(sizeof($array_formulir_anggota) != 0){
       $maximum_score = sizeof($array_formulir_anggota) * 100;
       $actual_score = 0;
       foreach($array_formulir_anggota as $formulir) {
           $actual_score += $formulir->nilai_pencapaian;
           array_push($array_formulir_id, $formulir->id);
       }

       $data["data_kinerja_saat_ini"]->tercapai = ($actual_score / $maximum_score) * 100;
       $data["data_kinerja_saat_ini"]->tidak_tercapai =  $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
       $data["data_kinerja_saat_ini"]->actual_score = $actual_score;
       $data["data_kinerja_saat_ini"]->max_score = $maximum_score;
       $data["data_kinerja_saat_ini"]->minus_score = $maximum_score - $actual_score;

       $array_detil_formulir = $this->bidang_model->get_detil_pencapaian_bidang_by_unit_and_formulir($selected_bidang, $array_formulir_id);
       //usort($array_formulir, "sort_descending_by_score");
       $data["data_kinerja_anggota"] = $array_formulir_anggota;
       $data["data_detil_kinerja_saat_ini"] = $array_detil_formulir;
    }

     //rentang waktu 5 tahun        
     if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_unit_and_periode($selected_bidang, $selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
    $this->load->view('form_bidang/dashboard_bidang_unit.php', $data);
}

public function pencapaian_user(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_user = $this->input->post("user_id");
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_bidang = $this->input->post("bidang_id");
    $selected_periode_tahun_semetser = $this->input->post("periode_id");
    $selected_renstra_periode = $this->input->post("renstra_periode");
    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
    $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");

    $data['title'] = "Pencapaian Bidang Unit";
    $data['versi'] = 'individu';
    $data['show_bread_crumb_institusi'] = "0";
    $data['show_bread_crumb_unit'] = "0";
    $data["breadcrumb"] = [];
    //bread crumb institusi
    if($show_bread_crumb_institusi == "1"){
        $data['show_bread_crumb_institusi'] = "1";
        $selected_institusi = $this->input->post("institusi_id");
        $selected_periode_tahun_semetser_institusi= $this->input->post("periode_id_institusi");
        $selected_renstra_periode_institusi = $this->input->post("renstra_periode_institusi");
        $objSelectedInstitusi = $this->institusi_model->get_by_id($selected_institusi);
        $data['selected_institusi'] = $selected_institusi;
        $data['data_unit'] = $this->unit_model->get_unit_by_institusi_id($selected_institusi);
        $data['selected_periode_tahun_semester_institusi'] = $selected_periode_tahun_semetser_institusi;
        $data['selected_renstra_periode_institusi'] = $selected_renstra_periode_institusi;
        $data["breadcrumb"]['institusi'] = [
                "url" => site_url()."/bidang/pencapaian_institusi",
                "name" => $objSelectedInstitusi->nama_institusi
        ];
    }

    //bread crumb unit
    if($show_bread_crumb_unit == "1"){
        $data['show_bread_crumb_unit'] = "1";
        $selected_ketua_unit = $this->input->post("ketua_unit");
        $selected_unit = $this->input->post("unit_id");
        $selected_periode_tahun_semetser_unit = $this->input->post("periode_id_unit");
        $selected_renstra_periode_unit = $this->input->post("renstra_periode_unit");

        $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $data['selected_unit'] = $selected_unit;
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $data["selected_institusi"] = $objUnit->institusi_id;
        $data['selected_ketua_unit'] = $selected_ketua_unit;
        $data['selected_periode_tahun_semester_unit'] = $selected_periode_tahun_semetser_unit;
        $data['selected_renstra_periode_unit'] = $selected_renstra_periode_unit;
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
                "url" => site_url()."/bidang/pencapaian_unit",
                "name" => $namaUnit
        ];
    }

    $data['data_bidang'] =  $this->bidang_model->get();
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
   

    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_bidang_institusi'] = site_url()."/bidang/pencapaian_institusi";
    $data['action_lihat_bidang_unit'] = site_url()."/bidang/pencapaian_unit";
    $data['action_lihat_bidang_user'] = site_url()."/bidang/pencapaian_user";


    $data['selected_user'] = $selected_user;
    $data['nama_user'] = "";
    $data['ketua_unit'] = $is_ketua;
    $data['selected_bidang'] = $selected_bidang;
    $data['selected_unit'] = $selected_unit;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['selected_renstra_periode'] = $selected_renstra_periode;

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
            $data['selected_obj_renstra_periode'] = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
        }

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

    if($selected_bidang == null){
        $selected_bidang = $data['data_bidang'][0]->id;
        $data['selected_bidang'] = $selected_bidang;
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
        $data['data_unit'] = [];
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

    if($selected_periode_id != null){
        $row_kinrja_user = $this->bidang_model->get_pencapaian_bidang_by_user_and_periode($selected_bidang,$selected_user, $selected_unit, $is_ketua, $selected_periode_id);
        if($row_kinrja_user != null){
            $data['data_kinerja_saat_ini']->tercapai = $row_kinrja_user->nilai_pencapaian;
            $data['data_kinerja_saat_ini']->tidak_tercapai =  $data['data_kinerja_saat_ini']->tidak_tercapai -  $data['data_kinerja_saat_ini']->tercapai;
            $data['data_kinerja_saat_ini']->max_score = 100;
            $data['data_kinerja_saat_ini']->actual_score =  $data['data_kinerja_saat_ini']->tercapai;
            $data['data_kinerja_saat_ini']->minus_score = 100 -  $data['data_kinerja_saat_ini']->tercapai;
            $data['data_detil_kinerja_saat_ini'] = $this->bidang_model->get_detil_pencapaian_bidang_by_user_formulir($selected_bidang, $row_kinrja_user->id);
            
        }
    }


    if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_user_and_periode($selected_bidang, $selected_user,$selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }

    $this->load->view('form_bidang/dashboard_bidang_user.php', $data);

}

}

?>