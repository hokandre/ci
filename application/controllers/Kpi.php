<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

function sort_descending_by_score($form_unit_1, $form_unit_2){
    $form1Score  = ($form_unit_1->score / $form_unit_1->MAX_SCORE  ) * 100;
    $form2Score  = ($form_unit_2->score / $form_unit_2->MAX_SCORE  ) * 100;

    if($form1Score == $form2Score){
        return 0;
    }

    return ($form1Score > $form2Score ) ? -1 : 1;
}

class Kpi extends CI_Controller {

public function __construct()
{
    parent::__construct();
    $this->load->database();
    $this->load->model('kpi_model');
    $this->load->model('indikator_model');
    $this->load->model('institusi_model');
    $this->load->model('renstra_periode_model');
    $this->load->model('periode_model');
    $this->load->model('bidang_model');
    $this->load->model('unit_model');
    $this->load->model('user_model');
} 

public function index(){
    $data["title"] = "Key Performance Indikator";

    $data["action_update_kpi"] = site_url()."/kpi/action_update_kpi/";
    $data["action_detil_kpi"] = site_url()."/kpi/detil/";
    $data["action_lihat_pencapaian_kpi"] = site_url()."/kpi/pencapaian_institusi";
    $data["breadcrumb"] = [
        [
            "url" => site_url()."/kpi",
            "name" => "List Kpi"
        ]
    ];

    $data["data_kpi"] = $this->kpi_model->get_all();
    $data["data_indikator"] = $this->indikator_model->get();

    $data["success"] = null;
    $data["error"] = null;
    $this->load->view('kpi/list_kpi.php', $data);
}

//ajax
public function get_by_name()
{
    $indikator_id = (int) $this->input->get('indikator');
    $name = $this->input->get('name');
    $result = $this->kpi_model->get_by_name($indikator_id, $name);
    echo json_encode($result);
}

public function action_update_kpi($kpi_id){
    $nama_kpi = $this->input->post("nama_kpi");
    $indikator_id = $this->input->post("indikator_id");

    $error = null;

    $data["title"] = "Key Performance Indikator";
    $data["action_update_kpi"] = site_url()."/kpi/action_update_kpi/";
    $data["action_detil_kpi"] = site_url()."/kpi/detil/";
    $data["breadcrumb"] = [
        [
            "url" => site_url()."/kpi",
            "name" => "List Kpi"
        ]
    ];

    $data["data_kpi"] = $this->kpi_model->get_all();
    $data["data_indikator"] = $this->indikator_model->get();
    

    if( empty($nama_kpi) ) {
        $error = [
            "Nama Kpi" => "tidak boleh kosong"
        ];
    }

    if(isset($error)){
        $data["error"] = $error;
        $data["success"] = null;
    }else{
        $data_updated["nama_kpi"] = $nama_kpi;
        $data_updated["indikator_id"] = $indikator_id;
        $affected_rows = $this->kpi_model->update($kpi_id, $data_updated);
        $data["error"] = null;
        $data["success"] = site_url()."/kpi";
    }

   $this->load->view('kpi/list_kpi.php', $data);
}

public function pencapaian_institusi(){
    $selected_institusi = $this->input->post("institusi_id");
    $selected_sumber = $this->input->post("sumber_id");
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
    $data['selected_sumber'] = $selected_sumber;
    $data['selected_periode_tahun_semetser'] = $selected_periode_tahun_semetser;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $this->institusi_model->get();
    $data['data_sumber'] =  [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";

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

    if($selected_sumber == null){
        $selected_sumber =  $data['data_sumber'][0]->id;
        $data['selected_sumber'] = $selected_sumber;
    }

    if($selected_institusi == null){
        $selected_institusi = $data['data_institusi'][0]->id;
        $data['selected_institusi'] =  $selected_institusi;
        $data['nama_institusi'] = $data['data_institusi'][0]->nama_institusi;
    }else{
        $objSelectedInstitusi = $this->institusi_model->get_by_id($selected_institusi);
        if(isset($objSelectedInstitusi)){
            $data['nama_institusi'] = $objSelectedInstitusi->nama_institusi;
        }
    }

    $data["data_kinerja_saat_ini"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100,
        "actual_score" => 0,
        "minus_score" => 0,
        "max_score" => 0
    ];
    $data["data_kinerja_anggota"] = [];
    $data["data_detil_kinerja_saat_ini"] = [];
    $data["data_kinerja_statistik"] = [];
   
    $array_formulir = $this->kpi_model->get_pencapaian_sumber_kpi_by_institusi_and_periode($selected_sumber,$selected_institusi, $selected_periode_id);
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

            $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_institusi_and_periode($selected_sumber, $selected_institusi, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
    $this->load->view('kpi/dashboard_kpi_institusi.php', $data);
}

public function pencapaian_unit(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_sumber = $this->input->post("sumber_id");
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
                "url" => site_url()."/kpi/pencapaian_institusi",
                "name" => $objSelectedInstitusi->nama_institusi
            ]
        ];
    }

    $data['selected_unit'] = $selected_unit;
    $data['nama_unit'] = "";
    $data['ketua_unit'] = $is_ketua;
    $data['selected_sumber'] = $selected_sumber;
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

    $data['data_sumber'] =  [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";

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

    /* default value subject*/
    if($selected_sumber == null){
        $selected_sumber = $data['data_sumber'][0]->id;
        $data['selected_sumber'] = $selected_sumber;
    }

    if($selected_unit == null){
        $selected_unit = $data['data_unit'][0]->id;
        $data['selected_unit'] = $selected_unit;
        $is_ketua =  $data['data_unit'][0]->ketua;
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
    $array_formulir_anggota = $this->kpi_model->get_pencapaian_sumber_kpi_by_unit_and_periode($selected_sumber,$selected_unit,$is_ketua, $selected_periode_id);

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
       $array_detil_formulir = $this->kpi_model->get_detil_pencapaian_sumber_kpi_by_unit_and_formulir($selected_sumber, $array_formulir_id);
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

            $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_unit_and_periode($selected_sumber, $selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
    $this->load->view('kpi/dashboard_kpi_unit.php', $data);
}

public function pencapaian_user(){
    $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
    $selected_user = $this->input->post("user_id");
    $selected_unit = $this->input->post("unit_id");
    $is_ketua = $this->input->post("ketua_unit");
    $selected_sumber = $this->input->post("sumber_id");
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
        $data['selected_periode_tahun_semester_institusi'] = $selected_periode_tahun_semetser_institusi;
        $data['selected_renstra_periode_institusi'] = $selected_renstra_periode_institusi;
        $data["breadcrumb"]['institusi'] = [
                "url" => site_url()."/kpi/pencapaian_institusi",
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
                "url" => site_url()."/kpi/pencapaian_unit",
                "name" => $namaUnit
        ];
    }

    $data['data_sumber'] =  [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];
    $data['data_renstra_periode'] = $this->renstra_periode_model->get_all();
    $data['data_periode'] = $this->periode_model->get ();
   

    $data['selected_obj_renstra_periode'] = $data['data_renstra_periode'][0];
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";


    $data['selected_user'] = $selected_user;
    $data['nama_user'] = "";
    $data['ketua_unit'] = $is_ketua;
    $data['selected_sumber'] = $selected_sumber;
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
    }else{
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $data["data_unit"] = $this->unit_model->get_unit_by_institusi_id($objUnit->institusi_id);
    }

    if($selected_sumber == null){
        $selected_sumber = $data['data_sumber'][0]->id;
        $data['selected_sumber'] = $selected_sumber;
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
        $row_kinrja_user = $this->kpi_model->get_pencapaian_sumber_kpi_by_user_and_periode($selected_sumber,$selected_user, $selected_unit, $is_ketua, $selected_periode_id);
        if($row_kinrja_user != null){
            $data['data_kinerja_saat_ini']->tercapai = $row_kinrja_user->nilai_pencapaian;
            $data['data_kinerja_saat_ini']->tidak_tercapai =  $data['data_kinerja_saat_ini']->tidak_tercapai -  $data['data_kinerja_saat_ini']->tercapai;
            $data['data_kinerja_saat_ini']->max_score = 100;
            $data['data_kinerja_saat_ini']->actual_score = $data['data_kinerja_saat_ini']->tercapai;
            $data['data_kinerja_saat_ini']->minus_score = $data['data_kinerja_saat_ini']->tidak_tercapai;
            $data['data_detil_kinerja_saat_ini'] = $this->kpi_model->get_detil_pencapaian_sumber_kpi_by_user_formulir($selected_sumber, $row_kinrja_user->id);
            
        }
    }


    if($selected_renstra_periode != null){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($data['selected_obj_renstra_periode']->tahun_awal, $data['selected_obj_renstra_periode']->tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_unit_and_periode($selected_sumber, $selected_unit,$is_ketua, $array_periode_id);
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }

    $this->load->view('kpi/dashboard_kpi_user.php', $data);

}


}

?>