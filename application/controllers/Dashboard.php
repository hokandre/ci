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


    public function index()
    {
        $data["mode_individu"] = true;
        $selected_unit = $this->input->post("unit_id");
        if($selected_unit == NULL){
            $selected_unit = $this->session->userdata("unit_id");
        }
        $isKetua = $this->input->post("ketua_unit") == null ?  "0" : $this->input->post("ketua_unit");
        $user_id = $this->session->userdata("id");
         //berisi tahun dan semester ex='2019-1';
        $selected_periode_id = $this->input->post("periode_id");

        $selected_renstra_periode = $this->input->post("renstra_periode");
        $objCurrentRenstra = null;

        if($selected_unit == null){
            $selected_unit = $this->session->userdata("unit_id");
        }

        $data["versi"] = "individu";
        $data["data_renstra_periode"] = $this->renstra_periode_model->get_all();
        $data["selected_unit"] = $selected_unit;
        $data["user_id"] = $user_id;
        $data["nama_user"] = "";
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

        $objUser = $this->user_model->get_user_by_id($user_id);
        if(isset($objUser)){
            $data["nama_user"] = $objUser->nama_user;
        }
        $data["ketua_unit"] = $isKetua;
        $data["data_kinerja_saat_ini"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "max_score" => 0,
            "minus_score" => 0 
        ];
        $data["detil_kinerja_saat_ini"] = [];
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = null;

        if($selected_periode_id == null){
            if($selected_renstra_periode == null){
                $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
            }

            $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            //change rensta periode
            if($objCurrentRenstra != null){
                $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
                if(sizeof($array_periode_id) != 0){
                    $data["tahun"] = $array_periode_id[0]->tahun;
                    $data["semester"] = $array_periode_id[0]->semester;
                    $selected_periode_id = $array_periode_id[0]->id;
                }else{
                    //default value periode belum ada
                    $data["tahun"] = $objCurrentRenstra->tahun_awal;
                    $data["semester"] = "1";
                }
            }
        }else{
            $year = explode("-", $selected_periode_id)[0];
            $semester = explode("-", $selected_periode_id)[1];

            $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
            if(sizeof($cur_periode) !=0){
                $data["tahun"] = $cur_periode[0]->tahun;
                $data["semester"] = $cur_periode[0]->semester;
                $selected_periode_id = $cur_periode[0]->id;
            }else{
                //periode belum dibuat
                $data["tahun"] = $year;
                $data["semester"] = $semester;
            }
          
        }

        
        
        if($selected_periode_id != null ){
            $kinerja_formulir = $this->dashboard_model->get_kinerja_user_by_periode_id($user_id, $selected_periode_id, $isKetua, $selected_unit);

            if( isset($kinerja_formulir)){
                $data["data_kinerja_saat_ini"]->tercapai = ceil($kinerja_formulir->score);
                $data["data_kinerja_saat_ini"]->tidak_tercapai = $data["data_kinerja_saat_ini"]->tidak_tercapai - ceil($kinerja_formulir->score);
                $data["data_kinerja_saat_ini"]->actual_score = $kinerja_formulir->score;
                $data["data_kinerja_saat_ini"]->minus_score = $kinerja_formulir->MAX_SCORE - $kinerja_formulir->score;
                $data["data_kinerja_saat_ini"]->max_score = $kinerja_formulir->MAX_SCORE;
                $data["data_kinerja_saat_ini"]->form = $kinerja_formulir;

                $data_detil_ketercapaian = $this->dashboard_model->get_detil_kinerja_user_by_formulir_id($kinerja_formulir->id);
                if(sizeof($data_detil_ketercapaian) != 0){
                    $data["detil_kinerja_saat_ini"] = $data_detil_ketercapaian;
                }
            }
        }

           //line chart
        // rentang waktu 5 tahun        
        if($selected_renstra_periode != null){
            if($objCurrentRenstra == null){
                $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            }

            $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);

            if(sizeof($rentang_periode_id) != 0){
                $array_periode_id = [];
                foreach($rentang_periode_id as $periode){
                    array_push($array_periode_id, $periode->id);
                }

                $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_user($user_id, $array_periode_id, $isKetua, $selected_unit);
                $data["renstra_periode"] = $objCurrentRenstra;
                $data["data_kinerja_statistik"] = $statistic_kinerja;
            }
        }
        
        $hak_akses = $this->session->userdata("hak_akses");
        if($hak_akses == 1){
            $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
            $data["action_lihat_kinerja_user"] = site_url()."/dashboard";
            $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
            $this->load->view('dashboard/dashboard_user.php', $data);
        }else{
            $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
            $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
            $data["title"] = "Dashboard ".$this->session->userdata("nama_unit");
            $this->load->view('dashboard/dashboard_user.php', $data);
        }        
    }

    public function kinerja_user(){
        $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
        $selected_unit = $this->input->post("unit_id");
        $isKetua = $this->input->post("ketua_unit") == null ?  "0" : $this->input->post("ketua_unit");
        if($selected_unit == NULL){
            $selected_unit = $this->session->userdata("unit_id");
        }
        $objSelectedUnit = $this->unit_model->get_unit_by_id($selected_unit);
        //berisi tahun dan semester ex='2019-1';
        $selected_periode_id = $this->input->post("periode_id");
        $selected_renstra_periode = $this->input->post("renstra_periode");
        $objCurrentRenstra = null;
        $user_id = $this->input->post("user_id") != null ?  $this->input->post("user_id") : $this->session->userdata("id");
        $showBreadCrumb = $this->input->post("show_bread_crumb");
        if($showBreadCrumb == "1"){
            $data["breadcrumb"] = [
                [
                    "url" => site_url()."/dashboard/kinerja_unit",
                    "name" => $objSelectedUnit->tenaga_pengajar == "1" && $isKetua == "0" ? "Dosen ".$objSelectedUnit->nama_unit : $objSelectedUnit->nama_unit
                ]
            ];
        }

        $data["versi"] = "individu";
        $data["show_bread_crumb"] = $showBreadCrumb == "1" ? "1" : "0";
        $data["data_renstra_periode"] = $this->renstra_periode_model->get_all();
        $data["selected_unit"] = $selected_unit;
        $data["user_id"] = $user_id;
        $data["nama_user"] = "";
        $objUser = $this->user_model->get_user_by_id($user_id);
        if(isset($objUser)){
            $data["nama_user"] = $objUser->nama_user;
        }
        $data["ketua_unit"] = $isKetua;
        $data["data_kinerja_saat_ini"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "max_score" => 0,
            "minus_score" => 0
        ];
        $data["detil_kinerja_saat_ini"] = [];
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = null;


        if($selected_periode_id == null){
            if($selected_renstra_periode == null){
                $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
            }

            $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            //change rensta periode
            if($objCurrentRenstra != null){
                $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
                if(sizeof($array_periode_id) != 0){
                    $data["tahun"] = $array_periode_id[0]->tahun;
                    $data["semester"] = $array_periode_id[0]->semester;
                    $selected_periode_id = $array_periode_id[0]->id;
                }else{
                    //default value periode belum ada
                    $data["tahun"] = $objCurrentRenstra->tahun_awal;
                    $data["semester"] = "1";
                }
            }

        }else{
            $year = explode("-", $selected_periode_id)[0];
            $semester = explode("-", $selected_periode_id)[1];

            $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
            if(sizeof($cur_periode) !=0){
                $data["tahun"] = $cur_periode[0]->tahun;
                $data["semester"] = $cur_periode[0]->semester;
                $selected_periode_id = $cur_periode[0]->id;
            }else{
                //periode belum dibuat
                $data["tahun"] = $year;
                $data["semester"] = $semester;
            }
          
        }  
        
        if($selected_periode_id != null){
            $kinerja_formulir = $this->dashboard_model->get_kinerja_user_by_periode_id($user_id, $selected_periode_id, $isKetua, $selected_unit);
            if( isset($kinerja_formulir)){
                $data["data_kinerja_saat_ini"]->tercapai = ceil($kinerja_formulir->score);
                $data["data_kinerja_saat_ini"]->tidak_tercapai = $data["data_kinerja_saat_ini"]->tidak_tercapai - ceil($kinerja_formulir->score);
                $data["data_kinerja_saat_ini"]->actual_score = $kinerja_formulir->score;
                $data["data_kinerja_saat_ini"]->minus_score = $kinerja_formulir->MAX_SCORE - $kinerja_formulir->score;
                $data["data_kinerja_saat_ini"]->max_score = $kinerja_formulir->MAX_SCORE;

                $data_detil_ketercapaian = $this->dashboard_model->get_detil_kinerja_user_by_formulir_id($kinerja_formulir->id);
                if(sizeof($data_detil_ketercapaian) != 0){
                    //pelaporan belum di acc
                    foreach($data_detil_ketercapaian as $detil) {
                        if($detil->status != '1'){
                            $detil->persen_ketercapaian = 0;
                        }
                    }
                    $data["detil_kinerja_saat_ini"] = $data_detil_ketercapaian;
                }
            }
        }

           //line chart
        // rentang waktu 5 tahun        
        if($selected_renstra_periode != null){
            if($objCurrentRenstra == null){
                $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            }

            $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
            if(sizeof($rentang_periode_id) != 0){
                $array_periode_id = [];
                foreach($rentang_periode_id as $periode){
                    array_push($array_periode_id, $periode->id);
                }

                $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_user($user_id, $array_periode_id, $isKetua, $selected_unit);
                $data["renstra_periode"] = $objCurrentRenstra;
                $data["data_kinerja_statistik"] = $statistic_kinerja;
            }
        }

        
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
        $hak_akses = $this->session->userdata("hak_akses");
        if($hak_akses == 1 && $data['mode_individu'] == false){
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
            $data["data_unit"] = $this->unit_model->get();
            $this->load->view('dashboard/dashboard_bpm.php', $data);
        }else {
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

            $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
            $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
            $data["title"] = "Dashboard ".$this->session->userdata("nama_unit");
            
            $this->load->view('dashboard/dashboard_user.php', $data);
        }        
    }

    public function kinerja_unit(){
        $data["mode_individu"] = $this->input->post("mode_individu") == NULL ? false : true;
        $selected_unit = $this->input->post("unit_id");
        $isKetua = $this->input->post("ketua_unit") == null ?  "0" : $this->input->post("ketua_unit");
        //berisi tahun dan semester ex='2019-1';
        $selected_periode_id = $this->input->post("periode_id");

        $selected_renstra_periode = $this->input->post("renstra_periode");
        $objCurrentRenstra = null;

        $showBreadCrumb = $this->input->post("show_bread_crumb");
        if($showBreadCrumb == "1"){
            $institusi_id = $this->input->post("institusi_id");
            $selectedObjectInstitusi = $this->institusi_model->get_by_id($institusi_id);
            $data["selected_institusi_id"] = $institusi_id;
            $data["show_bread_crumb"] = "1";
            $data["breadcrumb"] = [
                [
                    "url" => site_url()."/dashboard/kinerja_institusi",
                    "name" => $selectedObjectInstitusi->nama_institusi
                ]
            ];
        }

        $data["versi"] = "unit";
        $data["ketua_unit"] = $isKetua;
        $data["selected_unit"] = $selected_unit;
        $data["nama_unit"] = "";
        $objUnit = $this->unit_model->get_unit_by_id($selected_unit);
        if(isset($objUnit)){
            if($objUnit->jumlah_anggota == 0) {
                $data["nama_unit"] = $objUnit->nama_unit;
            }else {
                if($isKetua == "1") {
                    $data["nama_unit"] = "Ketua ".$objUnit->nama_unit;
                }else{
                    $data["nama_unit"] = ($objUnit->tenaga_pengajar == "1") ? "Dosen ".$objUnit->nama_unit : "Anggota ".$objUnit->nama_unit;
                }
            }
        }
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["data_renstra_periode"] = $this->renstra_periode_model->get_all();
        $data["data_kinerja_anggota"] = [];
        $data["data_kinerja_saat_ini"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "max_score" => 0,
            "minus_score" => 0 
        ];
        $data["detil_kinerja_saat_ini"] = [];
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = $selected_renstra_periode;


        if(!isset($selected_unit)){
            $selected_unit = $this->session->userdata("unit_id");
        }

        if($selected_periode_id == null){
            if($selected_renstra_periode == null){
                $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
            }

            $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            //change rensta periode
            if($objCurrentRenstra != null){
                $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
                if(sizeof($array_periode_id) != 0){
                    $data["tahun"] = $array_periode_id[0]->tahun;
                    $data["semester"] = $array_periode_id[0]->semester;
                    $selected_periode_id = $array_periode_id[0]->id;
                }else{
                    //default value periode belum ada
                    $data["tahun"] = $objCurrentRenstra->tahun_awal;
                    $data["semester"] = "1";
                }
            }
        }else{
            $year = explode("-", $selected_periode_id)[0];
            $semester = explode("-", $selected_periode_id)[1];

            $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
            if(sizeof($cur_periode) !=0){
                $data["tahun"] = $cur_periode[0]->tahun;
                $data["semester"] = $cur_periode[0]->semester;
                $selected_periode_id = $cur_periode[0]->id;
            }else{
                //periode belum dibuat
                $data["tahun"] = $year;
                $data["semester"] = $semester;
            }
          
        }
       
        if($selected_periode_id != null ){
            //pie chart
            $kinerja_formulir = $this->dashboard_model->get_kinerja_unit_by_periode_id($selected_unit, $selected_periode_id, $isKetua);
            
            if( sizeof($kinerja_formulir) != 0){
                $data["data_kinerja_anggota"] = $kinerja_formulir;
                $jumFormulir = sizeof($kinerja_formulir);
                $maxScore = $jumFormulir * 100;
                
                $actualScore = 0;
                foreach($kinerja_formulir as $formulir){
                    $actualScore += ceil($formulir->score);
                }

                $data["data_kinerja_saat_ini"]->tercapai = ($actualScore / $maxScore) * 100;
                $data["data_kinerja_saat_ini"]->actual_score = $actualScore;
                $data["data_kinerja_saat_ini"]->minus_score = $maxScore - $actualScore;
                $data["data_kinerja_saat_ini"]->max_score = $maxScore;
                $data["data_kinerja_saat_ini"]->tidak_tercapai = $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;

                $data_id_formulir =[];
                foreach($kinerja_formulir as $formulir) {
                    array_push($data_id_formulir, $formulir->id);
                }

                //bar chart
                $data_detil_ketercapaian = $this->dashboard_model->get_detil_kinerja_unit_by_formulir_id($data_id_formulir);
                foreach($data_detil_ketercapaian as $detil ){
                    $detil->persen_ketercapaian = ($detil->nilai_pencapaian / $detil->MAX_SCORE) * 100;
                }

                if(sizeof($data_detil_ketercapaian) != 0){
                    $data["detil_kinerja_saat_ini"] = $data_detil_ketercapaian;
                }
            }
        }

        //line chart
      // rentang waktu 5 tahun
                
    if($selected_renstra_periode != null){
        if($objCurrentRenstra == null){
            $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
        }

        $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);

        if(sizeof($rentang_periode_id) != 0){
            $array_periode_id = [];
            foreach($rentang_periode_id as $periode){
                array_push($array_periode_id, $periode->id);
            }

            $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_unit($selected_unit, $array_periode_id, $isKetua);
            $data["renstra_periode"] = $objCurrentRenstra;
            $data["data_kinerja_statistik"] = $statistic_kinerja;
        }
    }
        
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
        $hak_akses = $this->session->userdata("hak_akses");
        if($hak_akses == 1 && $data['mode_individu'] == false){
            $data["title"] = "Dashboard Badan Penjaminan Mutu";
            $data["data_unit"] = $this->unit_model->get();
            $this->load->view('dashboard/dashboard_unit.php', $data);
        }else {
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

            $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
            $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
            $data["action_lihat_kinerja_institusi"] = "";
            $data["title"] = "Dashboard Kinerja Unit";
           $this->load->view('dashboard/dashboard_unit.php', $data);
        }
    }

    public function kinerja_institusi(){
        
        $selected_institusi_id = $this->input->post("institusi_id");
        $selected_periode_id = $this->input->post("periode_id");
        $selected_renstra_periode = $this->input->post("renstra_periode");
        $objCurrentRenstra = null;

        $data["title"] = "Dashboard Institusi";
        $data["selected_institusi_id"] = $selected_institusi_id;
        $data["action_lihat_kinerja_unit"] = site_url()."/dashboard/kinerja_unit";
        $data["action_lihat_kinerja_user"] = site_url()."/dashboard/kinerja_user";
        $data["action_lihat_kinerja_institusi"] = site_url()."/dashboard/kinerja_institusi";
        $data["data_institusi"] =  $this->institusi_model->get();
        $data["data_renstra_periode"] = $this->renstra_periode_model->get_all();
        //for table
        $data["data_kinerja_anggota"] = [];
        //for pie chart
        $data["data_kinerja_saat_ini"] = (object) [
            "tercapai" => 0,
            "tidak_tercapai" => 100,
            "actual_score" => 0,
            "minus_score" => 0,
            "max_score" => 0
        ];
        //for bar chart
        $data["data_detil_kinerja_saat_ini"] = [];
        //for line chart
        $data["data_kinerja_statistik"] = [];
        $data["renstra_periode"] = $selected_renstra_periode;

        //default value institusi
        if($selected_institusi_id == null){
            $selected_institusi_id = $data['data_institusi'][1]->id;
            $data["selected_institusi_id"] = $data['data_institusi'][1]->id;

        }

        if($selected_periode_id == null){
            //default value selected restra periode
            if($selected_renstra_periode == null){
                $selected_renstra_periode = $data['data_renstra_periode'][0]->id;
            }

            $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            //change rensta periode
            if($objCurrentRenstra != null){
                $array_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
                if(sizeof($array_periode_id) != 0){
                    $data["tahun"] = $array_periode_id[0]->tahun;
                    $data["semester"] = $array_periode_id[0]->semester;
                    //default value selected periode id
                    $selected_periode_id = $array_periode_id[0]->id;
                    $data["selected_renstra_periode"] = $objCurrentRenstra;
                }else{
                    //default value periode belum ada
                    $data["tahun"] = $objCurrentRenstra->tahun_awal;
                    $data["semester"] = "1";
                }
            }
        }else{
            $year = explode("-", $selected_periode_id)[0];
            $semester = explode("-", $selected_periode_id)[1];

            $cur_periode = $this->periode_model->get_by_year_and_semester($year, $semester);
            if(sizeof($cur_periode) !=0){
                $data["tahun"] = $cur_periode[0]->tahun;
                $data["semester"] = $cur_periode[0]->semester;
                $selected_periode_id = $cur_periode[0]->id;
            }else{
                //periode belum dibuat
                $data["tahun"] = $year;
                $data["semester"] = $semester;
            }
          
        }

        if($selected_periode_id != null){
            //pie chart
            $kinerja_formulir = $this->dashboard_model->get_kinerja_institusi($selected_institusi_id, $selected_periode_id);
            if( sizeof($kinerja_formulir) != 0){
                //sort descending by performance
                usort($kinerja_formulir, "sort_descending_by_score");
                $data["data_kinerja_anggota"] = $kinerja_formulir;
                $jumFormulir = sizeof($kinerja_formulir);
                $maxScore = $jumFormulir * 100;
                
                $actualScore = 0;
                foreach($kinerja_formulir as $formulir){
                    $actualScore += ($formulir->score/$formulir->MAX_SCORE) * 100;
                }

                $data["data_kinerja_saat_ini"]->tercapai = ($actualScore / $maxScore) * 100;
                $data["data_kinerja_saat_ini"]->tidak_tercapai = $data["data_kinerja_saat_ini"]->tidak_tercapai - $data["data_kinerja_saat_ini"]->tercapai;
                $data["data_kinerja_saat_ini"]->actual_score = $actualScore;
                $data['data_kinerja_saat_ini']->max_score = $maxScore;
                $data['data_kinerja_saat_ini']->minus_score = $maxScore - $actualScore;
                foreach($kinerja_formulir as $formulir){
                    $formulir->persen_ketercapaian = ($formulir->score / $formulir->MAX_SCORE) * 100;
                }
                
                $data["data_detil_kinerja_saat_ini"] = $kinerja_formulir;
                //print_r($kinerja_formulir);
            }
        }

        //line chart
        if($selected_renstra_periode != null){
            if($objCurrentRenstra == null){
                $objCurrentRenstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
            }
    
            $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($objCurrentRenstra->tahun_awal, $objCurrentRenstra->tahun_akhir);
            if(sizeof($rentang_periode_id) != 0){
                $array_periode_id = [];
                foreach($rentang_periode_id as $periode){
                    array_push($array_periode_id, $periode->id);
                }
    
                $statistic_kinerja = $this->dashboard_model->get_statistic_kinerja_institusi($selected_institusi_id, $array_periode_id);
                $data["selected_renstra_periode"] = $objCurrentRenstra;
                $data["data_kinerja_statistik"] = $statistic_kinerja;
            }
        }

        $this->load->view('dashboard/dashboard_institusi.php', $data);
    }

    
}



?>