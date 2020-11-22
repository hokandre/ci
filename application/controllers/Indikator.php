<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

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

private function get_pencapaian_unit($unit_id, $is_ketua, $periode_id){
    $array_formulir = $this->indikator_model->get_pencapaian_indikator_by_unit($unit_id,$is_ketua, $periode_id);
    if(sizeof($array_formulir) != 0){
       $max_score = sizeof($array_formulir) * 100;
       $score_actual = 0;
       foreach($array_formulir as $formulir) {
         $formulir->score = $formulir->nilai_pencapaian_formulir;
         $score_actual += $formulir->nilai_pencapaian_formulir;
       }

       $persen_ketercapaian = ($score_actual / $max_score) * 100;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
       $minus_score = $max_score - $score_actual;

       $pencapaian_unit  = (object) [
            "tercapai" => $persen_ketercapaian,
            "tidak_tercapai" => $persen_tidak_tercapai,
            "actual_score" => $score_actual,
            "max_score" => $max_score,
            "minus_score" => $minus_score,
            "formulir_anggota" => $array_formulir 
       ];

       return $pencapaian_unit;
    }

    return null;
}

private function get_detil_pencapaian_unit($array_formulir_id){
    $detil_pencapaian_unit = $this->indikator_model->get_detil_pencapaian_indikator_unit_by_formulir($array_formulir_id);
    if(sizeof($array_formulir_id)){
        return $detil_pencapaian_unit;
    }

    return null;
}

private function get_statistik_pencapaian_unit($tahun_awal, $tahun_akhir, $unit_id, $is_ketua){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

        $statistic_kinerja = $this->indikator_model->get_statistic_pencapaian_indikator_by_unit($unit_id,$is_ketua, $array_periode_id);
        
        return $statistic_kinerja;
    }

    return null;
}

private function get_pencapaian_user($user_id, $unit_id, $is_ketua, $periode_id){
    $array_formulir = $this->indikator_model->get_pencapaian_indikator_by_unit_and_user($user_id,$unit_id,$is_ketua, $periode_id);
    if($array_formulir != null){
        $max_score = 100;
        $score_actual = $array_formulir->nilai_pencapaian_formulir;
        
        $persen_ketercapaian = $score_actual;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
        $minus_score = $max_score - $score_actual;

       $pencapaian_user  = (object) [
            "tercapai" => $persen_ketercapaian,
            "tidak_tercapai" => $persen_tidak_tercapai,
            "actual_score" => $score_actual,
            "max_score" => $max_score,
            "minus_score" => $minus_score,
            "formulir_id" => $array_formulir->formulir_id 
       ];
       
       return $pencapaian_user;
    }

    return null;
}

private function get_detil_pencapaian_user($formulir_id){
    $data_detil_kinerja = $this->indikator_model->get_detil_pencapaian_indikator_by_unit_and_user($formulir_id);

    if(sizeof($data_detil_kinerja) != 0) {
        return $data_detil_kinerja;
    }

    return null;
}

private function get_statistik_pencapaian_user($tahun_awal, $tahun_akhir, $user_id, $unit_id, $is_ketua){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

        $statistic_kinerja = $this->indikator_model->get_statistic_pencapaian_indikator_by_unit_and_user($user_id, $unit_id,$is_ketua, $array_periode_id);
        
        return $statistic_kinerja;
    }

    return null;
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
        $this->load->view("indikator/list_unit_berdasar_indikator.php", $data);
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
    $data_renstra_periode = $this->renstra_periode_model->get_all();
    $data_periode = $this->periode_model->get ();
    $data_institusi = $this->institusi_model->get();
    $data_unit = [];

    $mode_individu = $this->input->post("mode_individu");
    if($mode_individu == null || $mode_individu == "1") {
        $mode_individu = true;
    }else{
        $mode_individu = false;
    }

    $hak_akses_bpm = 1;
    if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true) { 
        $data_unit = $this->get_unit_associated_with_user();
    }

    $selected_institusi = $this->input->post("institusi_id");
    if($selected_institusi == null){
        $selected_institusi = $data_institusi[0]->id;
    }

    if($this->session->userdata("hak_akses") == $hak_akses_bpm && $mode_individu == false) { 
        $data_unit = $this->unit_model->get_unit_by_institusi_id($selected_institusi);
    }

    $nama_unit = "";
    $objUnit = null;
    $selected_unit = $this->input->post("unit_id");
    if($selected_unit == null){
        if($mode_individu == true) {
            $selected_unit = $data_unit[0]->unit_id;
        }else {
            $selected_unit = $data_unit[0]->id;
        }

        $objUnit = $data_unit[0];
    }

    $is_ketua = $this->input->post("ketua_unit");
    if($is_ketua == null) {
        $is_ketua = "0";
    }

    if($objUnit == null){
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
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


    $data['title'] = "Sasaran Strategis Institusi";
    $data['versi'] = "unit";
    $data['mode_individu'] = $mode_individu ? "1" : "0";
    $data['selected_institusi'] = $selected_institusi;
    $data['selected_unit'] = $selected_unit;
    $data['nama_unit'] = $nama_unit;
    $data['ketua_unit'] = $is_ketua;
    $data['selected_periode_tahun_semetser'] = $string_tahun_semester;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $data_institusi;
    $data['data_unit'] = $data_unit;
    $data['data_renstra_periode'] = $data_renstra_periode;
    $data['data_periode'] = $data_periode;
    $data['selected_obj_renstra_periode'] = $objCurrentRenstra;
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
        $pencapaian_unit = $this->get_pencapaian_unit($selected_unit, $is_ketua, $periode_id);
        if($pencapaian_unit){
            $data["data_kinerja"] = $pencapaian_unit;
        }

        //bar chart
        if($pencapaian_unit){
            $array_formulir_id = [];
            foreach($pencapaian_unit->formulir_anggota as $formulir){
                array_push($array_formulir_id, $formulir->id);
            }

            $detil_pencapaian_unit = $this->get_detil_pencapaian_unit($array_formulir_id);
            if(sizeof($detil_pencapaian_unit) != 0){
                $data["data_detil_kinerja"] = $detil_pencapaian_unit;
                $data["data_kinerja_anggota"] = $pencapaian_unit->formulir_anggota;
            }
        }
        
        //line chart
        $tahun_awal = $objCurrentRenstra->tahun_awal;
        $tahun_akhir = $objCurrentRenstra->tahun_akhir;
        $statistik_pencapaian_unit = $this->get_statistik_pencapaian_unit($tahun_awal, $tahun_akhir,$selected_unit, $is_ketua);
        if($statistik_pencapaian_unit){
            $data["data_kinerja_statistik"] = $statistik_pencapaian_unit;
        }
    }

    $data['action_lihat_indikator_institusi'] = site_url()."/indikator/pencapaian_institusi";
    $data['action_lihat_indikator_unit'] = site_url()."/indikator/pencapaian_unit";
    $data['action_lihat_indikator_user'] = site_url()."/indikator/pencapaian_user";

   $this->load->view('indikator/dashboard_indikator_unit.php', $data);
}

public function pencapaian_user(){
    $data_renstra_periode = $this->renstra_periode_model->get_all();
    $data_periode = $this->periode_model->get ();
    $data_institusi = $this->institusi_model->get();
    $data_unit = [];

    $mode_individu = $this->input->post("mode_individu"); 
    if($mode_individu == null || $mode_individu == "1") {
        $mode_individu = true;
    }else {
        $mode_individu = false;
    }

    $selected_institusi = $this->input->post("institusi_id");
    $hak_akses_bpm = 1;
    if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true){ 
        $data_unit = $this->get_unit_associated_with_user();
    }else {
        if($selected_institusi == null){
            $selected_institusi = $data_institusi[0]->id;
        }
        
        $data_unit = $this->unit_model->get_unit_by_institusi_id($selected_institusi);
    }

    $is_ketua = $this->input->post("ketua_unit");
    if($is_ketua == null){
        $is_ketua = "0";
    }

    $objUnit = null;
    $nama_unit = "";
    $selected_unit = $this->input->post("unit_id");
    if($selected_unit == null) {
        if($mode_individu == true){
            $selected_unit = $data_unit[0]->unit_id;
        }else{
            $selected_unit = $data_unit[0]->id;
        }
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

    $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");
    if($show_bread_crumb_unit == null){
        $show_bread_crumb_unit = "0";
    }
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

    $data['title'] = "Sasaran Strategis Institusi";
    $data["versi"] = "individu";
    $data["mode_individu"] = $mode_individu ? "1" : "0";
    $data['selected_unit'] = $selected_unit;
    $data['ketua_unit'] = $is_ketua;
    $data['selected_user'] = $selected_user;
    $data["nama_user"] = $nama_user;
    $data["selected_institusi"] = $selected_institusi;
    $data['selected_periode_tahun_semetser'] = $string_tahun_semester;
    $data['show_bread_crumb_unit'] = $show_bread_crumb_unit;
    $data['selected_renstra_periode'] = $selected_renstra_periode;
    $data['data_institusi'] = $data_institusi;
    $data['data_unit'] = $data_unit;
    $data['data_renstra_periode'] = $data_renstra_periode;
    $data['data_periode'] = $data_periode;
    $data['selected_obj_renstra_periode'] = $objCurrentRenstra;
    $data['selected_obj_unit'] = $objUnit;
    $data["data_kinerja"] = (object) [
        "tercapai" => 0,
        "tidak_tercapai" => 100, 
        "actual_score" => 0,
        "max_score" => 0,
        "minus_score" => 0 
    ];
    $data["data_detil_kinerja"] = [];
    $data["data_kinerja_statistik"] = [];
    $data['action_lihat_indikator_institusi'] = site_url()."/indikator/pencapaian_institusi";
    $data['action_lihat_indikator_unit'] = site_url()."/indikator/pencapaian_unit";
    $data['action_lihat_indikator_user'] = site_url()."/indikator/pencapaian_user";
    
    if($periode_id != null){
        //pie chart
        $pencapaian_user = $this->get_pencapaian_user($selected_user, $selected_unit, $is_ketua, $periode_id);
        if($pencapaian_user){
            $data["data_kinerja"] = $pencapaian_user;
        }
    
        //bar chart 
        if($pencapaian_user){
           $detil_pencapaian_user = $this->get_detil_pencapaian_user($pencapaian_user->formulir_id);
           if($detil_pencapaian_user){
               $data["data_detil_kinerja"] = $detil_pencapaian_user;
           }
        }
    
        //line chart
        $tahun_awal = $objCurrentRenstra->tahun_awal;
        $tahun_akhir = $objCurrentRenstra->tahun_akhir;
        $statistik_pencapaian_user = $this->get_statistik_pencapaian_user($tahun_awal, $tahun_akhir, $selected_user, $selected_unit, $is_ketua);
        if($statistik_pencapaian_user){
            $data["data_kinerja_statistik"] = $statistik_pencapaian_user;
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
    $this->load->view('indikator/list_indikator_pada_unit.php', $data);

}

}

?>