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

class Dashboard extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        if($this->session->userdata('logged') != 1){
            redirect(site_url().'/auth');
        }
        $this->load->model('institusi_model');
        $this->load->model('periode_model');
        $this->load->model('dashboard_model');
        $this->load->model('unit_model');
        $this->load->model('user_model');
        $this->load->model('renstra_periode_model');
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

    private function get_detil_kinerja_user($formulir_id){
        $data_detil_ketercapaian = $this->dashboard_model->get_detil_kinerja_user_by_formulir_id($formulir_id);
        if(sizeof($data_detil_ketercapaian) != 0){
            //pelaporan belum di acc
            foreach($data_detil_ketercapaian as $detil) {
                if($detil->status != '1'){
                    $detil->persen_ketercapaian = 0;
                }
            }

            return $data_detil_ketercapaian;
        }

        return null;
    }

    private function get_statistik_kinerja_user($tahun_awal, $tahun_akhir, $user_id, $isKetua, $selected_unit){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);

        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_user($user_id, $array_periode_id, $isKetua, $selected_unit);
            
            return $statistic_kinerja;
        }

        return null;
    }

    private function get_kinerja_user($user_id, $periode_id, $isKetua, $selected_unit){
        $formulir_kinerja_user = $this->dashboard_model->get_kinerja_user_by_periode_id($user_id, $periode_id, $isKetua, $selected_unit);

        if( isset($formulir_kinerja_user)){
            $max_score = $formulir_kinerja_user->MAX_SCORE;
            $persen_ketercapaian = ceil($formulir_kinerja_user->score);
            $persen_tidak_tercapai = 100 - $persen_ketercapaian;
            $score = $formulir_kinerja_user->score;
            
            $minus_score = $max_score - $score;

            $kinerja_user  = (object) [
                "tercapai" => $persen_ketercapaian,
                "tidak_tercapai" => $persen_tidak_tercapai,
                "actual_score" => $score,
                "max_score" => $max_score,
                "minus_score" => $minus_score,
                "formulir_id" => $formulir_kinerja_user->id 
            ];
            
            return $kinerja_user;           
        }

        return null;

    } 
    
    private function get_kinerja_unit($unit_id, $periode_id, $is_ketua){
        $kinerja_formulir = $this->dashboard_model->get_kinerja_unit_by_periode_id($unit_id, $periode_id, $is_ketua);
            
        if( sizeof($kinerja_formulir) != 0){
            $jum_formulir = sizeof($kinerja_formulir);
            $max_score = $jum_formulir * 100;
            
            $score_actual = 0;
            foreach($kinerja_formulir as $formulir){
                $score_actual += ceil($formulir->score);
            }

            $persen_ketercapaian =($score_actual / $max_score) * 100;

            $persen_tidak_tercapai = 100 - $persen_ketercapaian;
            $minus_score = $max_score - $score_actual;
            $data_id_formulir =[];
            foreach($kinerja_formulir as $formulir) {
                array_push($data_id_formulir, $formulir->id);
            }

            $kinerja_unit  = (object) [
                "tercapai" => $persen_ketercapaian,
                "tidak_tercapai" => $persen_tidak_tercapai,
                "actual_score" => $score_actual,
                "max_score" => $max_score,
                "minus_score" => $minus_score,
                "formulir_anggota_id" => $data_id_formulir,
                "formulir_anggota" => $kinerja_formulir 
            ];

            return $kinerja_unit;
        }

        return null;
    }

    private function get_detil_kinerja_unit($array_formulir_id){
        $data_detil_ketercapaian = $this->dashboard_model->get_detil_kinerja_unit_by_formulir_id($array_formulir_id);

        foreach($data_detil_ketercapaian as $detil ){
            $detil->persen_ketercapaian = ($detil->nilai_pencapaian / $detil->MAX_SCORE) * 100;
        }

        if(sizeof($data_detil_ketercapaian) != 0){
            
            return $data_detil_ketercapaian;
        }

        return null;
    }

    private function get_statistik_kinerja_unit($tahun_awal, $tahun_akhir, $unit_id,$is_ketua){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);

        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_unit($unit_id, $array_periode_id, $is_ketua);
            
            return $statistic_kinerja;
        }

        return null;
    }

    private function get_kinerja_institusi($institusi_id, $periode_id){
        $kinerja_formulir = $this->dashboard_model->get_kinerja_institusi($institusi_id, $periode_id);

        if( sizeof($kinerja_formulir) != 0){
            //sort descending by performance
            usort($kinerja_formulir, "sort_descending_by_score");

            $jum_formulir = sizeof($kinerja_formulir);
            $max_score = $jum_formulir * 100;
            
            $score_actual = 0;
            foreach($kinerja_formulir as $formulir){
                $score_actual += ($formulir->score/$formulir->MAX_SCORE) * 100;
            }

            $persen_ketercapaian = ( $score_actual / $max_score ) * 100;
            $persen_tidak_tercapai = 100 - $persen_ketercapaian;
            $minus_score = $max_score - $score_actual;
            $kinerja_institusi  = (object) [
                "tercapai" => $persen_ketercapaian,
                "tidak_tercapai" => $persen_tidak_tercapai,
                "actual_score" => $score_actual,
                "max_score" => $max_score,
                "minus_score" => $minus_score,
                "formulir_anggota" => $kinerja_formulir 
            ];

            return $kinerja_institusi;
        }

        return null;
    }

    private function get_statistik_kinerja_institusi($tahun_awal, $tahun_akhir, $institusi_id){
        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_institusi($institusi_id, $array_periode_id);

            return $statistic_kinerja;
        }

        return null;
    }

    public function index()
    {
        $data_renstra_periode = $this->renstra_periode_model->get_all();
        $data_unit = $this->get_unit_associated_with_user();

        $selected_unit = $this->input->post("unit_id");
        if($selected_unit == null){
            $selected_unit = $this->session->userdata("unit_id");
        }

        $isKetua = $this->input->post("ketua_unit");
        if($isKetua == null){
            $isKetua = "0";
        }

        $user_id = $this->session->userdata("id");
        $objUser = $this->user_model->get_user_by_id($user_id);

        $selected_renstra_periode = $this->input->post("renstra_periode");
        if($selected_renstra_periode == null){
            $selected_renstra_periode = $data_renstra_periode[0]->id;
        }
        
        $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

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
        }else {
            $split_tahun_semester = explode("-", $string_tahun_semester);

            $tahun = $split_tahun_semester[0];
            $semester = $split_tahun_semester[1];

            $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
            if($periode_obj){
                $periode_id = $periode_obj->id;
            }
        }
         

        $data["mode_individu"] = "1";
        $data["breadcrumb"] = [];
        $data["versi"] = "individu";
        $data["data_renstra_periode"] = $data_renstra_periode;
        $data["selected_unit"] = $selected_unit;
        $data["user_id"] = $user_id;
        $data["nama_user"] = $objUser->nama_user;
        $data['data_unit'] = $data_unit;
        $data["ketua_unit"] = $isKetua;
        $data["data_kinerja"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "max_score" => 0,
            "minus_score" => 0 
        ];
        $data["data_detil_kinerja"] = [];
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = $objCurrentRenstra;
        $data["tahun"] = $tahun;
        $data["semester"] = $semester;

        if($periode_id != null){

            //pie chart
            $kinerja_user = $this->get_kinerja_user($user_id, $periode_id, $isKetua, $selected_unit);
            if($kinerja_user) {
                $data["data_kinerja"] = $kinerja_user;
            }
    
            //bar char 
            if($kinerja_user){
                $detil_kinerja_user = $this->get_detil_kinerja_user($kinerja_user->formulir_id);
    
                if($detil_kinerja_user){
                    $data["data_detil_kinerja"] = $detil_kinerja_user;
                }
            }
    
            //line chart
            // rentang waktu 5 tahun
            $tahun_awal = $objCurrentRenstra->tahun_awal;
            $tahun_akhir = $objCurrentRenstra->tahun_akhir;
    
            $statistic_kinerja = $this->get_statistik_kinerja_user($tahun_awal, $tahun_akhir, $user_id, $isKetua, $selected_unit );
            if($statistic_kinerja){
                $data["data_kinerja_statistik"] = $statistic_kinerja;
            }
        }
                
        
        $hak_akses = $this->session->userdata("hak_akses");
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
        //kode hak akses bpm = 1
        if($hak_akses == 1){
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
        }else{
            $data["title"] = "Dashboard ".$this->session->userdata("nama_unit");
        }        
        $this->load->view('dashboard/dashboard_user.php', $data);
    }

    public function kinerja_user(){
        $data_renstra_periode = $this->renstra_periode_model->get_all();
        $data_unit = $this->unit_model->get();

        $selected_unit = $this->input->post("unit_id");
        if($selected_unit == null){
            $selected_unit = $this->session->userdata("unit_id");
        }
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);

        $isKetua = $this->input->post("ketua_unit");
        if($isKetua == null){
            $isKetua = "0";
        }

        $user_id = $this->input->post("user_id");
        if($user_id == null){
            $user_id = $this->session->userdata("id");
        }
        $objUser = $this->user_model->get_user_by_id($user_id);

        $selected_renstra_periode = $this->input->post("renstra_periode");
        if($selected_renstra_periode == null){
            $selected_renstra_periode = $data_renstra_periode[0]->id;
        }
        
        $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

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
        }else {
            $split_tahun_semester = explode("-", $string_tahun_semester);

            $tahun = $split_tahun_semester[0];
            $semester = $split_tahun_semester[1];

            $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
            $periode_id = $periode_obj->id;
        }

        $breadcrumb = [];

        $show_bread_crumb_unit = $this->input->post("show_bread_crumb_unit");
        if($show_bread_crumb_unit == null) {
            $show_bread_crumb_unit = "0";
        }else {
            $breadcrumb = [
                [
                    "url" => site_url()."/dashboard/kinerja_unit",
                    "name" => $objUnit->tenaga_pengajar == "1" && $isKetua == "0" ? "Dosen ".$objUnit->nama_unit : $objUnit->nama_unit,
                    "type" => "unit"
                ]
            ];
        }

        $show_bread_crumb_institusi = $this->input->post("show_bread_crumb_institusi");
        if($show_bread_crumb_institusi == null){
            $show_bread_crumb_institusi = "0";   
        }else {
            $institusi_id = $this->input->post("institusi_id");
            $selectedObjectInstitusi = $this->institusi_model->get_by_id($institusi_id);
            $data["selected_institusi_id"] = $institusi_id;
            $data["selected_institusi_periode_id"] = $this->input->post("periode_id_institusi");
            $data["selected_institusi_renstra_periode"] = $this->input->post("renstra_periode_institusi");
            array_push($breadcrumb,[
                "url" => site_url()."/dashboard/kinerja_institusi",
                "name" => $selectedObjectInstitusi->nama_institusi,
                "type" => "institusi"
            ]);
        }

        $mode_individu = $this->input->post("mode_individu");
        if($mode_individu == null || $mode_individu == "1"){
            $mode_individu = true;
        }else{
            $mode_individu = false;
        }

        //change data unit based on hak akses and mode individu
        $hak_akses_bpm = 1;
        if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true){
            $data_unit = $this->get_unit_associated_with_user();
        }

        $data["mode_individu"] = $mode_individu ? "1" : "0";
        $data["versi"] = "individu";
        $data["data_renstra_periode"] = $data_renstra_periode;
        $data["selected_unit"] = $selected_unit;
        $data["user_id"] = $user_id;
        $data["nama_user"] = $objUser->nama_user;
        $data['data_unit'] = $data_unit;
        $data["ketua_unit"] = $isKetua;
        $data["data_kinerja"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "max_score" => 0,
            "minus_score" => 0 
        ];
        $data["data_detil_kinerja"] = [];
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = $objCurrentRenstra;
        $data["tahun"] = $tahun;
        $data["semester"] = $semester;
        $data["show_bread_crumb_institusi"] = $show_bread_crumb_institusi;
        $data["show_bread_crumb_unit"] = $show_bread_crumb_unit;
        $data["breadcrumb"] = $breadcrumb;

        if($periode_id != null){

            //pie chart
            $kinerja_user = $this->get_kinerja_user($user_id, $periode_id, $isKetua, $selected_unit);
            if($kinerja_user) {
                $data["data_kinerja"] = $kinerja_user;
            }
    
            //bar char 
            if($kinerja_user){
                $detil_kinerja_user = $this->get_detil_kinerja_user($kinerja_user->formulir_id);
    
                if($detil_kinerja_user){
                    $data["data_detil_kinerja"] = $detil_kinerja_user;
                }
            }
    
            //line chart
            // rentang waktu 5 tahun
            $tahun_awal = $objCurrentRenstra->tahun_awal;
            $tahun_akhir = $objCurrentRenstra->tahun_akhir;
    
            $statistic_kinerja = $this->get_statistik_kinerja_user($tahun_awal, $tahun_akhir, $user_id, $isKetua, $selected_unit );
            if($statistic_kinerja){
                $data["data_kinerja_statistik"] = $statistic_kinerja;
            }
        }

        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";

        $hak_akses_bpm = 1;
        if($this->session->userdata("hak_akses") == $hak_akses_bpm && $mode_individu == false){
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
            $this->load->view('dashboard/dashboard_bpm.php', $data);
        }else {
            $data["title"] = "Dashboard ".$objUnit->nama_unit;
            $this->load->view('dashboard/dashboard_user.php', $data);
        }        
    }

    public function kinerja_unit(){
        $data_renstra_periode = $this->renstra_periode_model->get_all();
        $data_unit = $this->unit_model->get ();
        
        $selected_unit = $this->input->post("unit_id");
        if($selected_unit == null){
            $selected_unit = $this->session->userdata("unit_id");
        }

        $isKetua = $this->input->post("ketua_unit");
        if($isKetua == null){
            $isKetua = "0";
        }    

        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
        $nama_unit = "";
        if(isset($objUnit)){
            if($objUnit->jumlah_anggota == 0) {
                $nama_unit = "Ketua ".$objUnit->nama_unit;
            }else {
                if($isKetua == "1") {
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
        }else {
            $split_tahun_semester = explode("-", $string_tahun_semester);

            $tahun = $split_tahun_semester[0];
            $semester = $split_tahun_semester[1];

            $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
            $periode_id = $periode_obj->id;
        }

        $breadcrumb = [];
        $showBreadCrumb = $this->input->post("show_bread_crumb");
        if($showBreadCrumb == null){
            $showBreadCrumb = "0";
        }

        $selected_institusi_id = "";
        if($showBreadCrumb == "1"){
            $institusi_id = $this->input->post("institusi_id");
            $selectedObjectInstitusi = $this->institusi_model->get_by_id($institusi_id);
            $selected_institusi_id = $institusi_id;
            $breadcrumb = [
                [
                    "url" => site_url()."/dashboard/kinerja_institusi",
                    "name" => $selectedObjectInstitusi->nama_institusi
                ]
            ];
        }

        $mode_individu = $this->input->post("mode_individu");
        if($mode_individu == null || $mode_individu == "1"){
            $mode_individu = true;
        }else{
            $mode_individu = false;
        }

        //change data unit based on hak akses and mode individu
        $hak_akses_bpm = 1;
        if($this->session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true){
            $data_unit = $this->get_unit_associated_with_user();
        } 

        $versi = $this->input->post("versi");
        if($versi == null){
            $versi = "individu";
        }

        $data["mode_individu"] = $mode_individu ? "1" : "0";
        $data["versi"] = $versi;
        $data["data_renstra_periode"] = $data_renstra_periode;
        $data["selected_unit"] = $selected_unit;
        $data["nama_unit"] = $nama_unit;
        $data['data_unit'] = $data_unit;
        $data["ketua_unit"] = $isKetua;
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
        $data["renstra_periode"] = $objCurrentRenstra;
        $data["tahun"] = $tahun;
        $data["semester"] = $semester;
        $data["selected_institusi_id"] = $selected_institusi_id;
        $data["show_bread_crumb"] = $showBreadCrumb;
        $data["breadcrumb"] = $breadcrumb;
       
        if($periode_id != null){
            //pie chart
            $kinerja_unit = $this->get_kinerja_unit($selected_unit, $periode_id, $isKetua);
            if($kinerja_unit){
                $data["data_kinerja"] = $kinerja_unit;
                $data["data_kinerja_anggota"] = $kinerja_unit->formulir_anggota;  

            }
    
            //bar chart
            if($kinerja_unit){
                $formulir_anggota_id = $kinerja_unit->formulir_anggota_id;
    
                $detil_kinerja_unit = $this->get_detil_kinerja_unit($formulir_anggota_id);
                if($detil_kinerja_unit){
                    $data["data_detil_kinerja"] = $detil_kinerja_unit;
                }
    
            } 
    
            //line chart
            // rentang waktu 5 tahun
            $tahun_awal = $objCurrentRenstra->tahun_awal;
            $tahun_akhir = $objCurrentRenstra->tahun_akhir;
            $statistic_kinerja_unit = $this->get_statistik_kinerja_unit($tahun_awal, $tahun_akhir, $selected_unit, $isKetua);
            if($statistic_kinerja_unit){
                $data["data_kinerja_statistik"] = $statistic_kinerja_unit;
            }
        }
            
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";

        $hak_akses_bpm = 1;
        if($this->session->userdata("hak_akses") == $hak_akses_bpm && $data['mode_individu'] == false){
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
        }else {
            $data["title"] = "Dashboard Kinerja Unit";
            
        }

        $this->load->view('dashboard/dashboard_unit.php', $data);
    }

    public function kinerja_institusi(){
        $data_institusi = $this->institusi_model->get();
        $data_renstra_periode = $this->renstra_periode_model->get_all();

        $selected_institusi_id = $this->input->post("institusi_id");
        if($selected_institusi_id == null){
            $selected_institusi_id = $data_institusi[0]->id;
        }

        $selected_renstra_periode = $this->input->post("renstra_periode");
        if($selected_renstra_periode == null){
            $selected_renstra_periode = $data_renstra_periode[0]->id;
        }
        
        $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);

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
        }else {
            $split_tahun_semester = explode("-", $string_tahun_semester);

            $tahun = $split_tahun_semester[0];
            $semester = $split_tahun_semester[1];

            $periode_obj = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
            $periode_id = $periode_obj->id;
        }

        $data["title"] = "Dashboard Institusi";
        $data["selected_institusi_id"] = $selected_institusi_id;
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_pribadi"] = site_url()."/dashboard";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
        $data["data_institusi"] = $data_institusi;
        $data["data_renstra_periode"] = $data_renstra_periode;
        //for table
        $data["data_kinerja_anggota"] = [];
        //for pie chart
        $data["data_kinerja"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "minus_score" => 0,
            "max_score" => 0
        ];
        //for bar chart
        $data["data_detil_kinerja"] = [];
        //for line chart
        $data["data_kinerja_statistik"] = [];
        $data["selected_renstra_periode"] = $objCurrentRenstra;
        $data["semester"] = $semester;
        $data["tahun"] = $tahun;

        if($periode_id != null){
            //pie chart
            $kinerja_institusi = $this->get_kinerja_institusi($selected_institusi_id, $periode_id);
            if($kinerja_institusi) {
                $data["data_kinerja"] = $kinerja_institusi;
                $data["data_kinerja_anggota"] = $kinerja_institusi->formulir_anggota;
            }
    
            //bar chart
            if($kinerja_institusi){
                $data["data_detil_kinerja"] = $kinerja_institusi->formulir_anggota;
            }
    
            //line chart
            $tahun_awal = $objCurrentRenstra->tahun_awal;
            $tahun_akhir = $objCurrentRenstra->tahun_akhir;
            $statistik_kinerja_institusi = $this->get_statistik_kinerja_institusi($tahun_awal, $tahun_akhir, $selected_institusi_id);
            if($statistik_kinerja_institusi){
                $data["data_kinerja_statistik"] = $statistik_kinerja_institusi;
            }
        }

        $this->load->view('dashboard/dashboard_institusi.php', $data);
    }
    
}



?>