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

private function get_unit_associated_with_user(){
    $unitUser = [
        //data unit diri sendiri
        (object) [
            "nama_unit" => $this->session->userdata("nama_unit"),
            "unit_id" => $this->session->userdata("unit_id"),
            "tenaga_pengajar" => $this->session->userdata("tenaga_pengajar"),
            "ketua" => "0",
            "view" => "user"
        ]  
    ];
    
    //menjadi ketua unit dari unit diri nya sendiri
    //contoh : unit program studi sistem informasi dan merupakan kaprodi sistem informasi
    if($this->session->userdata("isKetua")){
        array_push($unitUser, (object)[
            "nama_unit" => "Ketua ".$this->session->userdata("nama_unit_diketuai"),
            "unit_id" => $this->session->userdata("ketua_unit"),
            "tenaga_pengajar" => $this->session->userdata("jenis_unit_diketuai"),
            "ketua" => "1",
            "view" => "unit"
        ]);
    }

    //menjadi ketua unit dari unit bukan diri nya sendiri
    //contoh : dosen program studi sistem informasi dan merupakan ketua badan penjaminan mutu
    if($this->session->userdata("ketua_unit") != null && $this->session->userdata("ketua_unit") != $this->session->userdata("unit_id")){
        array_push($unitUser, (object)[
            "nama_unit" => $this->session->userdata("jenis_unit_diketuai") == "0" ? "Anggota ".$this->session->userdata("nama_unit_diketuai") : "Dosen ".$this->session->userdata("nama_unit_diketuai"),
            "unit_id" => $this->session->userdata("ketua_unit"),
            "tenaga_pengajar" => $this->session->userdata("jenis_unit_diketuai"),
            "ketua" => "0",
            "view" => "unit"
        ]);
    }

    return $unitUser;
}

private function get_periode_by_renstra($renstra_obj){
    $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($renstra_obj->tahun_awal, $renstra_obj->tahun_akhir);
    return $array_periode_id[0];
}

private function get_pencapaian_institusi($sumber_id, $institusi_id, $periode_id){
    $array_formulir = $this->kpi_model->get_pencapaian_sumber_kpi_by_institusi_and_periode($sumber_id, $institusi_id, $periode_id);

    if(sizeof($array_formulir) != 0){
       $max_score = sizeof($array_formulir) * 100;
       $score_actual = 0;
       foreach($array_formulir as $formulir) {
           $score_actual += ($formulir->score / $formulir->MAX_SCORE) * 100;
           $formulir->persen_ketercapaian = ($formulir->score / $formulir->MAX_SCORE) * 100;
       }

       $persen_ketercapaian = ($score_actual / $max_score) * 100;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
       $minus_score = $max_score - $score_actual;

       $pencapaian_institusi  = (object) [
            "tercapai" => $persen_ketercapaian,
            "tidak_tercapai" => $persen_tidak_tercapai,
            "actual_score" => $score_actual,
            "max_score" => $max_score,
            "minus_score" => $minus_score,
            "formulir_anggota" => $array_formulir 
       ];
       usort($array_formulir, "sort_descending_by_score");

       return $pencapaian_institusi;
       
    }

    return null;
}

private function get_statistik_pencapaian_institusi($tahun_awal, $tahun_akhir, $sumber_id, $institusi_id){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

        $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_institusi_and_periode($sumber_id, $institusi_id, $array_periode_id);
        
        return $statistic_kinerja;
    }

    return null;
}

private function get_pencapaian_unit($sumber_id, $unit_id, $is_ketua, $periode_id){
    $array_formulir_anggota = $this->kpi_model->get_pencapaian_sumber_kpi_by_unit_and_periode($sumber_id,$unit_id,$is_ketua, $periode_id);

    if(sizeof($array_formulir_anggota) != 0){
       $max_score = sizeof($array_formulir_anggota) * 100;
       $score_actual = 0;

       $persen_ketercapaian = ($score_actual / $max_score) * 100;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
       $minus_score = $max_score - $score_actual;

       $pencapaian_unit  = (object) [
            "tercapai" => $persen_ketercapaian,
            "tidak_tercapai" => $persen_tidak_tercapai,
            "actual_score" => $score_actual,
            "max_score" => $max_score,
            "minus_score" => $minus_score,
            "formulir_anggota" => $array_formulir_anggota 
       ];

    return $pencapaian_unit;
    }

    return null;
}

private function get_detil_pencapaian_unit($sumber_id, $array_formulir_id){
    $array_detil_formulir = $this->kpi_model->get_detil_pencapaian_sumber_kpi_by_unit_and_formulir($sumber_id, $array_formulir_id);
       
    if(sizeof($array_detil_formulir) != 0){
        return $array_detil_formulir;
    }

    return null;
}

private function get_statistik_pencapaian_unit($tahun_awal, $tahun_akhir, $sumber_id, $unit_id, $is_ketua){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

        $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_unit_and_periode($sumber_id, $unit_id, $is_ketua, $array_periode_id);
       
        return $statistic_kinerja;
    }

    return null;
}

private function get_pencapaian_user($sumber_id, $user_id, $unit_id, $is_ketua, $periode_id){
    $row_kinrja_user = $this->kpi_model->get_pencapaian_sumber_kpi_by_user_and_periode($sumber_id,$user_id, $unit_id, $is_ketua, $periode_id);
    if($row_kinrja_user != null){
        $max_score = 100;
        $score_actual = $row_kinrja_user->nilai_pencapaian;
        $persen_ketercapaian = ($score_actual / $max_score) * 100;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
        $minus_score = $max_score - $score_actual;
    
        $pencapaian_user  = (object) [
                "tercapai" => $persen_ketercapaian,
                "tidak_tercapai" => $persen_tidak_tercapai,
                "actual_score" => $score_actual,
                "max_score" => $max_score,
                "minus_score" => $minus_score,
                "formulir_id" => $row_kinrja_user 
        ];
        
    return $pencapaian_user;
    }
    
    return null;
}

private function get_detil_pencapaian_user($sumber_id, $formulir_id){
   $detil_pencapaian_user = $this->kpi_model->get_detil_pencapaian_sumber_kpi_by_user_formulir($sumber_id, $formulir_id);

   if(sizeof($detil_pencapaian_user) != 0){
       return $detil_pencapaian_user;
   }

   return null;
}

private function get_statistik_pencapaian_user($tahun_awal, $tahun_akhir, $sumber_id, $unit_id, $is_ketua){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

        $statistic_kinerja = $this->kpi_model->get_statistic_pencapaian_sumber_kpi_by_unit_and_periode($sumber_id, $unit_id,$is_ketua, $array_periode_id);
        
        return $statistic_kinerja;
    }

    return null;
}

public function index($error = null){
    $data["title"] = "Key Performance Indikator";

    $data["action_update_kpi"] = site_url()."/kpi/action_update_kpi/";
    $data["action_detil_kpi"] = site_url()."/kpi/detil/";
    $data["action_lihat_pencapaian_kpi"] = site_url()."/kpi/pencapaian_institusi";
    $data["action_add"] = site_url()."/kpi/add";
    $data["breadcrumb"] = [
        [
            "url" => site_url()."/kpi",
            "name" => "List Kpi"
        ]
    ];

    $data["data_kpi"] = $this->kpi_model->get_all();
    $data["data_indikator"] = $this->indikator_model->get();

    $data["success"] = null;
    $data["error"] = $error;
    $this->load->view('kpi/list_kpi.php', $data);
}

public function add(){
    $error = [];

    $indikator_id = $this->input->post("indikator");
    $nama_kpi = $this->input->post("kpi");

    if(!$indikator_id || empty($indikator_id)){
        $error["indikator"] = "Data Indikator Harus Diisi";
        $this->index($error);
        return;
    }

    if(!$nama_kpi || empty($nama_kpi)){
        $error["KPI"] = "Data KPI Harus Diisi";
        $this->index($error);
        return;
    }


    $is_kpi_has_registered = false;

    $kpi_obj = $this->kpi_model->get_by_name($indikator_id, $nama_kpi);
    if($kpi_obj){
        $is_kpi_has_registered = true;
    }

    if($is_kpi_has_registered){
        $error["KPI"] = "Data KPI Sudah Pernah Didaftarkan!";
        $this->index($error);
        return;
    }

    $data_new_kpi["nama_kpi"] = $nama_kpi;
    $data_new_kpi["indikator_id"] = $indikator_id;

    $new_kpi_id = $this->kpi_model->create($data_new_kpi);

    redirect(site_url()."/kpi");
}
//ajax
public function get_by_name()
{
    $indikator_id = (int) $this->input->get('indikator');
    $name = $this->input->get('name');
    $result = $this->kpi_model->get_like_by_name($indikator_id, $name);
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
    $data_renstra_periode = $this->renstra_periode_model->get_all();
    $data_periode = $this->periode_model->get ();
    $data_institusi = $this->institusi_model->get();
    $data_sumber = [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];

    $objInstitusi = null;
    $nama_institusi = "";
    $selected_institusi = $this->input->post("institusi_id");
    if($selected_institusi == null){
        $selected_institusi = $data_institusi[0]->id;
        $objInstitusi = $data_institusi[0];
    }

    if($objInstitusi == null){
        $objInstitusi = $this->institusi_model->get_by_id($selected_institusi);
    }

    if($objInstitusi != null){
        $nama_institusi = $objInstitusi->nama_institusi;
    }

    $selected_sumber = $this->input->post("sumber_id");
    if($selected_sumber == null){
        $selected_sumber = $data_sumber[0]->id;
    }

    $selected_renstra_periode = $this->input->post("renstra_periode");
    if($selected_renstra_periode == null){
        $selected_renstra_periode = $data_renstra_periode[0]->id;
    }

    $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

    $string_tahun_semester = $this->input->post("periode_id");

    $periode_id = null;
    $tahun = null;
    $semester = null;
    $string_tahun_semester = $this->input->post("periode_id");
    //berisi tahun dan semester ex='2019-1';
    if($string_tahun_semester == null){
        $periode_obj = $this->get_periode_by_renstra($objCurrentRenstra);

        $tahun = $periode_obj->tahun;
        $semester = $periode_obj->semester;
        $periode_id = $periode_obj->id;
        $string_tahun_semester = $tahun."-".$semester;
    }else {
        $split_tahun_semester = explode("-", $string_tahun_semester);

        $tahun = $split_tahun_semester[0];
        $semester = $split_tahun_semester[1];

        $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
        $periode_id = $periode_obj->id;
    }  

    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
    if($show_bread_crumb_institusi == null){
        $show_bread_crumb_institusi = "0";
    }

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
    $data['nama_institusi'] = $nama_institusi;
    $data['selected_sumber'] = $selected_sumber;
    $data['selected_periode_tahun_semetser'] = $string_tahun_semester;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $data_institusi;
    $data['data_sumber'] =  $data_sumber;
    $data['data_renstra_periode'] = $data_renstra_periode;
    $data['data_periode'] = $data_periode;
    $data['selected_obj_renstra_periode'] = $objCurrentRenstra;
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";
    $data["data_kinerja"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100,
        "actual_score" => 0,
        "minus_score" => 0,
        "max_score" => 0
    ];
    $data["data_kinerja_anggota"] = [];
    $data["data_detil_kinerja"] = [];
    $data["data_kinerja_statistik"] = [];
    $data["mode_individu"] = $mode_individu ? "1" : "0";
    
    if($periode_id != null){
        //pie chart
        $pencapaian_institusi = $this->get_pencapaian_institusi($selected_sumber,$selected_institusi, $periode_id);
        if($pencapaian_institusi){
            $data["data_kinerja"] = $pencapaian_institusi;
            $data["data_detil_kinerja"] = $pencapaian_institusi->formulir_anggota;
            $data["data_kinerja_anggota"] = $pencapaian_institusi->formulir_anggota;
    
        }
    
        //line chart 
        $tahun_awal = $objCurrentRenstra->tahun_awal;
        $tahun_akhir = $objCurrentRenstra->tahun_akhir;
        $statistik_institusi = $this->get_statistik_pencapaian_institusi($tahun_awal, $tahun_akhir, $selected_sumber, $selected_institusi);     
    
        if($statistik_institusi){
            $data["data_kinerja_statistik"] = $statistik_institusi;
        }
    }

    $this->load->view('kpi/dashboard_kpi_institusi.php', $data);
}

public function pencapaian_unit(){
    $data_renstra_periode = $this->renstra_periode_model->get_all();
    $data_periode = $this->periode_model->get ();
    $data_institusi = $this->institusi_model->get();
    $data_sumber = [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];
    $data_unit = [];

    $mode_individu = $this->input->post("mode_individu");
    if( $mode_individu == null) {
        $mode_individu = true;
    }else{
        $mode_individu = false;
    }

    $hak_akses_bpm = 1;
    if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true) { 
        $data_unit = $this->get_unit_associated_with_user();
    }

    $selected_institusi = $this->input->post("institusi_id");
    if($selected_institusi != null){
       $data_unit = $this->unit_model->get_unit_by_institusi_id($selected_institusi);
    }

    $objUnit = null;
    $nama_unit = "";
    $selected_unit = $this->input->post("unit_id");
    if( $selected_unit == null ) {
        $selected_unit = $data_unit[0]->id;
    }
    
    if($objUnit == null){
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
    }

    $is_ketua = $this->input->post("ketua_unit");
    if($is_ketua == null) {
        $is_ketua = "0";
    }
    
    if(isset($objUnit)){
        if($objUnit->jumlah_anggota == 0) {
            $nama_unit = $objUnit->nama_unit;
        }else {
            if($is_ketua == "1") {
                $nama_unit = "Ketua ".$objUnit->nama_unit;
            }else{
                $nama_unit = ($objUnit->tenaga_pengajar == "1") ? "Dosen ".$objUnit->nama_unit : "Anggota ".$objUnit->nama_unit;
            }
        }
    }

   

    $selected_sumber = $this->input->post("sumber_id");
    if($selected_sumber == null) {
        $selected_sumber = $data_sumber[0]->id;
    }

    $selected_renstra_periode = $this->input->post("renstra_periode");
    if($selected_renstra_periode == null){
        $selected_renstra_periode = $data_renstra_periode[0]->id;
    }

    $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

    $string_tahun_semester = $this->input->post("periode_id");

    $periode_id = null;
    $tahun = null;
    $semester = null;
    $string_tahun_semester = $this->input->post("periode_id");
    //berisi tahun dan semester ex='2019-1';
    if($string_tahun_semester == null){
        $periode_obj = $this->get_periode_by_renstra($objCurrentRenstra);

        $tahun = $periode_obj->tahun;
        $semester = $periode_obj->semester;
        $periode_id = $periode_obj->id;
        $string_tahun_semester = $tahun."-".$semester;
    }else {
        $split_tahun_semester = explode("-", $string_tahun_semester);

        $tahun = $split_tahun_semester[0];
        $semester = $split_tahun_semester[1];

        $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
        if($periode_obj){
            $periode_id = $periode_obj->id;
        }
    }  
    
    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
    if($show_bread_crumb_institusi == null) {
        $show_bread_crumb_institusi = "0";
    }

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

    $data['title'] = "Pencapaian Bidang Unit";
    $data['versi'] = 'unit';
    $data['show_bread_crumb_institusi'] = $show_bread_crumb_institusi;
    $data['selected_unit'] = $selected_unit;
    $data['nama_unit'] = $nama_unit;
    $data['ketua_unit'] = $is_ketua;
    $data['selected_sumber'] = $selected_sumber;
    $data['selected_periode_tahun_semetser'] = $string_tahun_semester;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_unit'] = $data_unit;
    $data["data_sumber"] = $data_sumber;
    $data['data_renstra_periode'] = $data_renstra_periode;
    $data['data_periode'] = $data_periode;
    $data['selected_obj_renstra_periode'] = $objCurrentRenstra;
    $data["mode_individu"] = $mode_individu ? "1" : "0";
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";
    $data["data_kinerja"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100,
        "actual_score" => 0,
        "max_score" => 0,
        "minus_score" => 0
    ];
    $data["data_kinerja_anggota"] = [];
    $data["data_detil_kinerja"] = [];
    $data["data_kinerja_statistik"] = [];
    
    if($periode_id != null){
        //pie chart
        $pencapaian_unit = $this->get_pencapaian_unit($selected_sumber,$selected_unit,$is_ketua, $periode_id);
        if($pencapaian_unit){
            $data["data_kinerja"] = $pencapaian_unit;
        }
    
        //bar chart
        if($pencapaian_unit){
            $array_id_anggota = [];
            foreach($pencapaian_unit->formulir_anggota as $formulir){
                array_push($array_id_anggota, $formulir->id);
            }

            $detil_pencapaian_unit = $this->get_detil_pencapaian_unit($selected_sumber, $array_id_anggota);
    
            if($detil_pencapaian_unit){
                $data["data_detil_kinerja"] = $detil_pencapaian_unit;
                $data["data_kinerja_anggota"] = $pencapaian_unit->formulir_anggota;
            }
        }
    
        //line chart
        $tahun_awal = $objCurrentRenstra->tahun_awal;
        $tahun_akhir = $objCurrentRenstra->tahun_akhir;
        $statistik_pencapaian_unit = $this->get_statistik_pencapaian_unit($tahun_awal, $tahun_akhir, $selected_sumber, $selected_unit,$is_ketua);
        if($statistik_pencapaian_unit){
            $data["data_kinerja_statistik"] = $statistik_pencapaian_unit;
        }
    }

    $this->load->view('kpi/dashboard_kpi_unit.php', $data);
}

public function pencapaian_user(){
    $data_renstra_periode = $this->renstra_periode_model->get_all();
    $data_periode = $this->periode_model->get ();
    $data_sumber = [
        (object)["id"=>"renstra", "nama_sumber" => "Rencana Strategis"],
        (object)["id" => "mutu","nama_sumber" =>"Sasaran Mutu"],
        (object)["id"=>"renop","nama_sumber"=>"Rencana Operasional"]
    ];

    $mode_individu = $this->input->post("mode_individu");
    if($mode_individu == null) {
        $mode_individu = true;
    }else {
        $mode_individu = false;
    }

    $hak_akses_bpm = 1;
    if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true){ 
        $data_unit = $this->get_unit_associated_with_user();
    }else {
        $data_unit = $this->unit_model->get ();
    }

    $objUser = null;
    $nama_user = "";
    $selected_user = $this->input->post("user_id");
    if($selected_user == null){
        $selected_user = $this->session->userdata("id");
    }

    $objUser =  $this->user_model->get_user_by_id($selected_user);

    if($objUser){
        $nama_user = $objUser->nama_user;
    }

    $is_ketua = $this->input->post("ketua_unit");
    if($is_ketua == null){
        $is_ketua = "0";
    }

    $objUnit = null;
    $nama_unit = "";
    $selected_unit = $this->input->post("unit_id");
    if($selected_unit == null) {
        $selected_unit = $data_unit[0]->unit_id;
        $objUnit = $data_unit[0];
    }

    if($objUnit == null){
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
    }

    if($objUnit->tenaga_pengajar == "1"){
        if($is_ketua == "0"){
            $nama_unit = "Dosen ".$objUnit->nama_unit;;
        }else{
            $nama_unit = "Ketua ".$objUnit->nama_unit;;
        }
    }else{
        if($is_ketua == "0"){
            $nama_unit = "Anggota ".$objUnit->nama_unit;;
        }else{
            $nama_unit = "Ketua ".$objUnit->nama_unit;;
        }
    }
    $objUnit->nama_unit = $nama_unit;

    $selected_sumber = $this->input->post("sumber_id");
    if($selected_sumber == null){
        $selected_sumber = $data_sumber[0]->id;
    }
    
    $selected_renstra_periode = $this->input->post("renstra_periode");
    if($selected_renstra_periode == null){
        $selected_renstra_periode = $data_renstra_periode[0]->id;
    }

    $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

    $string_tahun_semester = $this->input->post("periode_id");

    $periode_id = null;
    $tahun = null;
    $semester = null;
    $string_tahun_semester = $this->input->post("periode_id");
    //berisi tahun dan semester ex='2019-1';
    if($string_tahun_semester == null){
        $periode_obj = $this->get_periode_by_renstra($objCurrentRenstra);

        $tahun = $periode_obj->tahun;
        $semester = $periode_obj->semester;
        $periode_id = $periode_obj->id;
        $string_tahun_semester = $tahun."-".$semester;
    }else {
        $split_tahun_semester = explode("-", $string_tahun_semester);

        $tahun = $split_tahun_semester[0];
        $semester = $split_tahun_semester[1];

        $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
        if($periode_obj){
            $periode_id = $periode_obj->id;
        }
       
    }  

    $breadcrumb = [];
    $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
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
        $breadcrumb['institusi'] = [
                "url" => site_url()."/kpi/pencapaian_institusi",
                "name" => $objSelectedInstitusi->nama_institusi
        ];
    }

    $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");

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
        $breadcrumb['unit'] = [
                "url" => site_url()."/kpi/pencapaian_unit",
                "name" => $namaUnit
        ];
    }

    $data['title'] = "Pencapaian Bidang Unit";
    $data['versi'] = 'individu';
    $data['show_bread_crumb_institusi'] = $show_bread_crumb_institusi;
    $data['show_bread_crumb_unit'] = $show_bread_crumb_unit;
    $data["breadcrumb"] = $breadcrumb;
    $data['data_renstra_periode'] = $data_renstra_periode;
    $data['data_periode'] = $data_periode;
    $data["data_sumber"] = $data_sumber;
    $data["data_unit"] = $data_unit;
    $data['selected_obj_renstra_periode'] = $objCurrentRenstra;
    $data['action_lihat_kpi_institusi'] = site_url()."/kpi/pencapaian_institusi";
    $data['action_lihat_kpi_unit'] = site_url()."/kpi/pencapaian_unit";
    $data['action_lihat_kpi_user'] = site_url()."/kpi/pencapaian_user";
    $data['selected_user'] = $selected_user;
    $data['nama_user'] = $nama_user;
    $data['ketua_unit'] = $is_ketua;
    $data['selected_sumber'] = $selected_sumber;
    $data['selected_unit'] = $selected_unit;
    $data['selected_periode_tahun_semetser'] = $string_tahun_semester;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['selected_obj_unit'] = $objUnit;
    $data["mode_individu"] = $mode_individu ? "1" : "0";
    $data["data_kinerja"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100,
        "actual_score" => 0,
        "max_score" => 0,
        "minus_score" => 0
    ];
    $data["data_kinerja_anggota"] = [];
    $data["data_detil_kinerja"] = [];
    $data["data_kinerja_statistik"] = [];

    if($periode_id != null){
        //pie chart
        $pencapaian_user = $this->get_pencapaian_user($selected_sumber, $selected_user, $selected_unit, 
        $is_ketua,
        $periode_id);
        if($pencapaian_user){
            $data["data_kinerja"] = $pencapaian_user;
        }
        
        //bar chart
        if($pencapaian_user){
            $detil_pencapaian_user = $this->get_detil_pencapaian_user
            (
                $selected_sumber,
                $pencapaian_user->formulir_id->id);
            if($detil_pencapaian_user){
                $data["data_detil_kinerja"] = $detil_pencapaian_user;
            }
         }
    
        //line chart
        $tahun_awal = $objCurrentRenstra->tahun_awal;
        $tahun_akhir = $objCurrentRenstra->tahun_akhir;
        $statistik_pencapaian_user = $this->get_statistik_pencapaian_user($tahun_awal, $tahun_akhir, $selected_sumber, $selected_unit,$is_ketua);
        if($statistik_pencapaian_user){
            $data["data_kinerja_statistik"] = $statistik_pencapaian_user;
        }
    }
    

    $this->load->view('kpi/dashboard_kpi_user.php', $data);

}


}

?>