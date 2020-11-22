<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
include 'ChromePhp.php';


class Formulir_rencana_kerja extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper("form");
        $this->load->model('formulir_rencana_kerja_model');
        $this->load->model('institusi_model');
        $this->load->model('periode_model');
        $this->load->model('user_model');
        $this->load->model('kpi_model');
        $this->load->model('detil_formulir_rencana_kerja_model');
        $this->load->model('bidang_model');
        $this->load->model('indikator_model');
        $this->load->model('kamus_indikator_model');
        $this->load->model('unit_model');
        $this->load->model('comment_model');

        if($this->session->userdata('logged') != 1){
            redirect(site_url()."/auth");
        }
    }

    public function index(){
        //menampilkan form indvidu yang login
        $user_id = $this->session->userdata("id");
        $formulir_rencana_kerja = $this->formulir_rencana_kerja_model->get_laporan_by_user($user_id);
        
        $data["data_formulir_rencana_kerja"] = $formulir_rencana_kerja;
        $data["action"] = site_url()."/formulir_rencana_kerja/detil/";
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/formulir_rencana_kerja",
                "name" => "Laporan"
            ]
        ];
        //bpm fitur
        $data["action_buat_format_formulir"] = site_url()."/formulir_rencana_kerja/form";
        $data["action_cari_laporan"] = site_url()."/formulir_rencana_kerja/get";
        $data["action_get_format_formulir"] = site_url()."/formulir_rencana_kerja/get_format_formulir";
        
        $this->load->view('form_rencana_kerja/form_rencana_kerja_individu.php', $data);
    }

    public function get(){
        $selected_tahun = (int) $this->input->post('tahun');
        $selected_institusi = (int) $this->input->post('institusi');
        $ganjil = $this->input->post('ganjil');
        $genap = $this->input->post('genap');
        $semester = NULL;
       

        //default value semester
        if($ganjil == null && $genap == null){
            $semester = "1";
        }

        if($ganjil == 1 && !isset($genap)){
            $semester = "1";
        }

        if(!isset($ganjil) && $genap == 2){
            $semester = "2";
        }



        $tahun = $this->periode_model->get_year();
        $institusi = $this->institusi_model->get();
        $data["action"] = site_url()."/formulir_rencana_kerja/detil/";
        $data["data_tahun"] = $tahun;
        if($selected_tahun == null){
            $selected_tahun = $data['data_tahun'][0]->tahun;
        }
        $data["data_institusi"] = $institusi;
        if($selected_institusi == null){
            $selected_institusi = end($data['data_institusi'])->id;
        }
        $data["selected_institusi_id"] = $selected_institusi;
        $data["selected_tahun"] = $selected_tahun;
        $data["selected_semester"] = $semester;
        $hak_akses_ketua_unit_biasa = 4;
        if($this->session->userdata("hak_akses") == $hak_akses_ketua_unit_biasa){
            //ketua unit biasa
            $formulir_rencana_kerja = $this->formulir_rencana_kerja_model->get_laporan_ketua_unit($selected_tahun, $semester, $this->session->userdata("ketua_unit"));
        }else{
            //BPM
            $formulir_rencana_kerja = $this->formulir_rencana_kerja_model->get_laporan($selected_tahun, $semester, $selected_institusi == 4 ? NULL : $selected_institusi);
        }
        $data["data_formulir_rencana_kerja"] = $formulir_rencana_kerja;
        $data["breadcrumb"]= [
                    "url" => site_url()."/formulir_rencana_kerja/get",
                    "name" => "List Laporan",
                    "institusi_crumb" => $selected_institusi,
                    "ganjil_crumb" => $ganjil,
                    "genap_crumb" => $genap,
                    "tahun_crumb" => $selected_tahun
        ];
        $this->load->view('form_rencana_kerja/formulir_search_laporan.php',$data);
    }

    public function form(){
        $tahun = $this->periode_model->get_year();
        $indikator = $this->indikator_model->get();
        $kamus_indikator = $this->kamus_indikator_model->get();
        $unit = $this->unit_model->get();
        $bidang = $this->bidang_model->get();
       
        $data["data_tahun"]= $tahun;
        $data["data_indikator"] = $indikator;
        $data["data_kamus_indikator"] = $kamus_indikator;
        $data["data_unit"] = $unit;
        $data["data_bidang"] = $bidang;
        $data["selected_tahun"] = $tahun[0];
        $data["action_add_tahun"] = site_url()."/periode/add";
        $data["action_get_kpi"] = site_url()."/kpi/get_by_name";
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/formulir_rencana_kerja",
                "name" => "Laporan"
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/form",
                "name" => "Buat Formulir"
            ]
        ];
        $this->load->view('form_rencana_kerja/form_buat_format_formaulir_rencana_kerja.php',$data);
    }

    public function create(){
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = null;
        if(isset($request->data) ){
            $data = $request->data;
        }
        
        if($data == null){
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(["data" => $request]));
        }
       
        $error = false;
        $error_response = array();
        
        //check if data semeter tidak ada 
        if(!property_exists($data, 'semester')){
            $error = true;
            $error_response["semester"] = "Semester harus dipilih.";       
        }else{
            //check jika data sudah pernah dibuat
            // 4 : mencari pada semua kategori institusi
            if( sizeof($this->formulir_rencana_kerja_model->get_laporan($data->tahun,$data->semester,null)) != 0){
                $error = true;
                $error_response["redudant"] = "formulir pada periode ini sudah pernah dibuat.";
            }else{
                 //check if data kpi ada yang redudant dan data kpi kosong
                foreach($data->dataTable as $keyOuter => $valueOuter){
                    //kpi kosong
                    if(!$valueOuter->kpi->kpiId && !$valueOuter->kpi->namaKpi){
                        $error = true;
                        $error_response["kpi kosong"] = "Data kpi harus diisi!";
                        break;
                    }
                    //unit tidak ada
                    if(sizeof($valueOuter->unit) == 0 ) {
                        $error = true;
                        $error_response["Unit kosong"] = "Minimal ada 1 unit pada setiap poin!";
                        break;
                    }


                    $redudant = false;
                    foreach($data->dataTable as $keyinner => $valueInner){
                        if($valueOuter->kpi->kpiId){
                            if($valueOuter->kpi->kpiId == $valueInner->kpi->kpiId && $keyOuter != $keyinner){
                                $redudant = true;
                                break;
                            }
                        }else{
                            if($valueOuter->kpi->namaKpi == $valueInner->kpi->namaKpi && $keyOuter != $keyinner){
                                $redudant = true;
                                break;
                            }
                        }
                    }

                    if($redudant){
                        $error = true;
                        $error_response["kpi redudant"] = "terdapat poin kpi yang berulang";
                        break;
                    }
                }
            }
        }

       $periode_id= null;

        if($error){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode($error_response));
        }else{
            $this->db->trans_begin();

            $data_unit = [];
            //loop row
            foreach($data->dataTable as $row){
                $new_kpi_id = null;
                //create new kpi
                if($row->kpi->kpiId == ""){
                    $kpi_obj = $this->kpi_model->get_by_name($row->indikator, $row->kpi->namaKpi);
                    if($kpi_obj){
                        $new_kpi_id = $kpi_obj->id;
                    }else{
                        $data_kpi_baru["indikator_id"] = $row->indikator;
                        $data_kpi_baru["nama_kpi"] = $row->kpi->namaKpi;
                        $data_kpi_baru["bidang_id"] = $row->bidang;
                        $new_kpi_id = $this->kpi_model->create($data_kpi_baru);
                    }
                }
                //loop unit
                foreach($row->unit as $unit){
                    if(!isset($data_unit[$unit->unitId."_".$unit->ketuaUnit])){
                        $anggota_unit = [];
                        //cek unit ketua atau bukan
                        if($unit->ketuaUnit == ""){
                            $all_user_of_unit = $this->user_model->get_user_by_unit_id($unit->unitId); 
                            foreach($all_user_of_unit as $user){
                                if($user->status == "1"){
                                    array_push($anggota_unit, $user);
                                }
                            }
                        }
    
                        $data_unit[$unit->unitId."_".$unit->ketuaUnit] = [
                            "unit_id" => $unit->unitId,
                            "ketua_unit" => $unit->ketuaUnit,
                            "anggota_unit" => $anggota_unit
                        ];
                        //membuat formulir
                        $periode_id = $this->periode_model->get_array_by_year_and_semester($data->tahun, $data->semester)[0]->id;
                        $array_rencana_kerja_id = $this->formulir_rencana_kerja_model->create_many_formulir($data_unit[$unit->unitId."_".$unit->ketuaUnit], $periode_id);
                        $data_unit[$unit->unitId."_".$unit->ketuaUnit]["formulir_rencana_kerja"] = $array_rencana_kerja_id;   

                    }
                    //loop anggota formulir
                    foreach($data_unit[$unit->unitId."_".$unit->ketuaUnit]["formulir_rencana_kerja"] as $formulir_rencana_kerja){
                        //cretate
                        if(!isset($formulir_rencana_kerja->detil) ){
                            $formulir_rencana_kerja->detil = array();
                        }

                        $data_detil_formulir["formulir_hasil_bidang_kinerja_utama_id"] = $formulir_rencana_kerja->id;
                        $data_detil_formulir["kpi_id"] = $row->kpi->kpiId;
                        if(isset($new_kpi_id)){
                            $data_detil_formulir["kpi_id"] = $new_kpi_id;
                        }
                        $data_detil_formulir["target_institusi"] = $unit->target;
                        $data_detil_formulir["satuan"] = $unit->satuan;
                        $data_detil_formulir["sumber"] = $row->sumber;
                        $data_detil_formulir["bobot"] = $row->bobot;
                        $data_detil_formulir["bidang_id"] = $row->bidang;
                        $last_detil_kpi_id = $this->detil_formulir_rencana_kerja_model->create($data_detil_formulir);
                        array_push($formulir_rencana_kerja->detil, $last_detil_kpi_id);
                    }

                }
            }

            if($this->db->trans_status() == FALSE){
                $this->db->trans_rollback();
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(array("error" => "Maaf Service Sedang Bermasalah!")));
            }else{
                $this->db->trans_commit();
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data_unit));
            }
        }
        
    }

    public function detil($formulir_id){
       $formulir = $this->formulir_rencana_kerja_model->get_laporan_by_id($formulir_id);
       $detil = $this->detil_formulir_rencana_kerja_model->get_detil($formulir_id);
       $formulir->detil = $detil;
       $show_breadcrumb_list = $this->input->post("show_bread_crumb_list");
       $show_breadcrumb_detil = $this->input->post("show_bread_crumb_detil");
        
       $data["title"] = "detil formulir rencana kerja";
       $data["formulir"] = $formulir;

       $data["action"] = site_url()."/formulir_rencana_kerja/update_detil_kpi/";
       $data["action_print"] = site_url()."/formulir_rencana_kerja/print/";
       $data["action_get_ketidak_tercapaian"] = site_url()."/formulir_rencana_kerja/get_ketidak_tercapaian/";
       $data["action_terima"] = site_url()."/formulir_rencana_kerja/terima_detil_kpi/";
       $data["action_add_comment"] = site_url()."/comment/create";
       $data["action_update_comment"] = site_url()."/comment/update/";
       $data["action_delete_comment"] = site_url()."/comment/delete/";

       $data['show_breadcrumb_list'] = $show_breadcrumb_list;
       $data['show_breadcrumb_detil'] = $show_breadcrumb_detil;
       $data["error"] = null;

       $comment = $this->comment_model->get($formulir_id);
       $formulir->comment = $comment;

       if($show_breadcrumb_list == 1){
           $data["show_breadcrumb_list"] = 1;
           $data["breadcrumb_list_url"] = site_url()."/formulir_rencana_kerja/get";
           $data["breadcrumb_list_nama"] = "List Laporan";
           $data["breadcrumb_list_institusi"]= $this->input->post("list_institusi_crumb");
           $data["breadcrumb_list_tahun"] = $this->input->post("list_tahun_crumb");
           $data["breadcrumb_list_ganjil"] = $this->input->post("list_ganjil_crumb");
           $data["breadcrumb_list_genap"] = $this->input->post("list_genap_crumb");
       }

       if($show_breadcrumb_detil == 1){
           $data["show_breadcrumb_detil"] = 1;
           $data['breadcrumb_detil_url'] = site_url()."/formulir_rencana_kerja/detil/";
           $data["breadcrumb_detil_nama"] = "Detil";
           $data["breadcrumb_detil_unit"] = $this->input->post("detil_unit_crumb");
       }


        //menentuakan view sebagai amin atau user biasa
        $kode_hak_akses_bpm = 1;
        if($this->session->userdata('hak_akses') == $kode_hak_akses_bpm){
            $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja_bpm.php", $data);
        }else{
            $unitYangDiketuai = $this->session->userdata("ketua_unit");
            $isKetua = $this->session->userdata("isKetua");
            $unitFormulir = $formulir->unit_id;
            $userId = $this->session->userdata("id");
            $userFormulir = $formulir->user_id;
            $id_program_studi_ti = 16;
            $id_program_studi_mi = 17;
            $id_program_studi_ka = 18;
            $id_program_studi_tk = 20;
            $id_program_studi_si = 24;
            $id_program_studi_akutansi = 32;
            $id_program_studi_manajemen = 33;
            
            //usecase : hanya unit berasal dari tenaga pengajar yang formulirnya harus divalidasi oleh ketua unitnya
            if($isKetua == false || ($unitYangDiketuai != $id_program_studi_ti && $unitYangDiketuai != $id_program_studi_mi && $unitYangDiketuai != $id_program_studi_ka && $unitYangDiketuai != $id_program_studi_tk && $unitYangDiketuai != $id_program_studi_si && $unitYangDiketuai != $id_program_studi_manajemen && $unitYangDiketuai != $id_program_studi_akutansi) ){
                //unit bukan prodi dan user biasa
                $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja.php", $data);
            }else{
                if( ($unitYangDiketuai == $unitFormulir) && ($userId != $userFormulir)){
                    // ketua unit memvalidasi form anggotanya
                    $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja_bpm.php", $data);            
                }else{
                    $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja.php", $data);
                }
            }
        }
    }

    public function update_detil_kpi($formulir_id,$detil_id)
    {
       
        $error = null;
        $taget_institusi = $this->input->post('target_institusi');
        $target_individu = $this->input->post('target_individu');
        $satuan = $this->input->post("satuan");
        $nilai_aktual = $this->input->post('nilai_aktual');

        if($satuan == 'orang'){
            $nilai = $nilai_aktual * 1;
            if(is_float($nilai)){
                $error = array("Nilai Aktual" => " harus bernilai bulat");
            }
        }

        if($satuan == 'satuan bulat'){
            $nilai = $nilai_aktual * 1;
            if(is_float($nilai)){
                $error = array("Nilai Aktual" => " harus bernilai bulat");
            }
        }

        //validation check
        if($target_individu < $taget_institusi){
            $error = array("Target Individu" => " minimal sama dengan target institusi");
        }else if(!isset($target_individu)){
            $error = array("Target Individu" => " harus di isi");  
        }else if($nilai_aktual < 0 ){
            $error = array("Nilai Aktual" => " minimum 0");    
        }else if(!isset($nilai_aktual)){
            $error = array("nilai aktual" => " harus di isi");  
        }else if(!empty($_FILES["file"]["name"])){
            $config['upload_path'] = './dokumen/';
            $config['allowed_types'] = 'pdf|word';
            $config['max_size'] = 5 * 1024;
            $new_name = time().$_FILES["file"]['name'];
            $config['file_name'] = $new_name;
            
            $this->load->library('upload', $config);
    
            if(! $this->upload->do_upload('file')){
                $error = array('file' => "Bermasalah dalam mengupload file.");
            }
        }

        $formulir = $this->formulir_rencana_kerja_model->get_laporan_by_id($formulir_id);
        $data["title"] = "detil formulir rencana kerja";
        $data["action"] = site_url()."/formulir_rencana_kerja/update_detil_kpi/";
        $data["action_print"] = site_url()."/formulir_rencana_kerja/print/";
        $data['show_breadcrumb_list'] = "0";
        $data['show_breadcrumb_detil'] = "0";
        $data["action_get_ketidak_tercapaian"] = site_url()."/formulir_rencana_kerja/get_ketidak_tercapaian/";
        $data["error"] = $error;
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/formulir_rencana_kerja",
                "name" => "Laporan"
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/search_laporan",
                "name" => "List Laporan"
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/detil/".$formulir_id,
                "name" => "Detil Laporan" 
            ]
        ];
        if(isset($error)){
            //remove file uploaded
            if(!empty($_FILES["file"]["name"])){       
                $pathFile = FCPATH."dokumen/".$_FILES["file"]["name"];
                if(file_exists($pathFile)){
                    unlink($pathFile);
                }
            }

            //display form again with old detil
            $detil = $this->detil_formulir_rencana_kerja_model->get_detil($formulir_id);
            $formulir->detil = $detil;
            $data["formulir"] = $formulir;  
            $data["action_terima"] = site_url()."/formulir_rencana_kerja/terima_detil_kpi/";
            $data["action_add_comment"] = site_url()."/comment/create";
            $data["action_update_comment"] = site_url()."/comment/update/";
            $data["action_delete_comment"] = site_url()."/comment/delete/"; 
            $comment = $this->comment_model->get($formulir_id);
            $formulir->comment = $comment;
            
            //display menurut role 
            $kode_hak_akses_bpm = 1;
            if($this->session->userdata('hak_akses') == $kode_hak_akses_bpm){
                //role : bpm
                $data["action_terima"] = site_url()."/formulir_rencana_kerja/terima_detil_kpi/";
                $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja_bpm.php", $data);
            }else{
                //role : selaian bpm
                $this->load->view("form_rencana_kerja/detil_formulir_rencana_kerja.php", $data);            
            }

        }else{
          $data_detil["target_individu"] = $target_individu;
          $data_detil["nilai_aktual"] = $nilai_aktual;
          if(isset($this->upload)){
            $data_detil["bukti"]= $this->upload->data()["file_name"];
          }

          $result = $this->detil_formulir_rencana_kerja_model->update_detil_kpi($detil_id, $data_detil);
          redirect(site_url('formulir_rencana_kerja/detil/'.$formulir_id));
        }
    }

    public function terima_detil_kpi($formulir_id, $detil_id)
    {
        $status = $this->input->post("status");
        $result = $this->detil_formulir_rencana_kerja_model->terima_detil_kpi($detil_id, $status);
        
        $formulir = $this->formulir_rencana_kerja_model->get_laporan_by_id($formulir_id); 
        $detil_formulir = $this->detil_formulir_rencana_kerja_model->get_detil($formulir_id);
        
        $formulir_siap_diberlakukan = true;
        foreach($detil_formulir as $detil){
            if($detil->status == '0'){
                $formulir_siap_diberlakukan = false;
                break;
            }
        }

        if($formulir_siap_diberlakukan){
            $data = [
                "tanggal_berlaku" => date('Y-m-d')
            ];
            $this->formulir_rencana_kerja_model->update_formulir($formulir->id, $data);
        }else{
            if( ($formulir->tanggal_berlaku != NULL) && $status == '0' ){
                $data = [
                    "tanggal_berlaku" => NULL,
                    "revisi" =>  $formulir->revisi+1,
                    "tanggal_revisi" => date('Y-m-d')
                ]; 
                $this->formulir_rencana_kerja_model->update_formulir($formulir->id, $data);
            }

        }
        redirect(site_url('formulir_rencana_kerja/detil/'.$formulir_id));
    }

    public function get_format_formulir($periode_id = null){
        $tahun = $this->periode_model->get_year();
        $indikator = $this->indikator_model->get();
        $kamus_indikator = $this->kamus_indikator_model->get();
        $unit = $this->unit_model->get();
        $bidang = $this->bidang_model->get();
       
        $data["data_tahun"]= $tahun;
        $data["data_indikator"] = $indikator;
        $data["data_kamus_indikator"] = $kamus_indikator;
        $data["data_unit"] = $unit;
        $data["data_bidang"] = $bidang;
        $data["selected_tahun"] = $tahun[0];
        $data["action_add_tahun"] = site_url()."/periode/add";
        $data["action_get_kpi"] = site_url()."/kpi/get_by_name";
        $data["action_search_format"] = site_url()."/formulir_rencana_kerja/get_format_formulir/";
        $data["action_update_format"] = site_url()."/formulir_rencana_kerja/update_format_formulir";
        $data["action_delete_format"] = site_url()."/formulir_rencana_kerja/delete_format_formulir";
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/formulir_rencana_kerja",
                "name" => "Laporan"
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/get_format_formulir/".$periode_id,
                "name" => "Format Laporan"
            ]
        ];
        //semester genap 
        if($periode_id == null){
            $data["selected_semester"] = $this->input->post('semester') ? $this->input->post('semester') : "2";
            $data["selected_tahun"] = $this->input->post('tahun') ? $this->input->post('tahun') : $data["data_tahun"][0]->tahun;
            $result_periode = $this->periode_model->get_array_by_year_and_semester($data["selected_tahun"], $data["selected_semester"]);
            $data["selected_periode"] = $result_periode[0]->id;
        }else{
            $data["selected_periode"] = $periode_id;
            $result_periode = $this->periode_model->get_by_id($periode_id);
            $data["selected_semester"] = $result_periode->semester;
            $data["selected_tahun"] = $result_periode->tahun;            
        }
        $formulir_data_result = $this->formulir_rencana_kerja_model->get_formulir_by_periode_id($data["selected_periode"]);
        $data_format_formulir = [];
        $data_unit_pada_formulir = [];
        foreach($formulir_data_result as $detil_row){
            if(!isset($data_unit_pada_formulir[$detil_row->unit_id."-".$detil_row->formulir_ketua == "0" ? "" : $detil_row->ketua_unit])){
                $unit_pada_formulir = [
                    "unit_id" => $detil_row->unit_id,
                    "nama_unit" => $detil_row->nama_unit,
                    "ketua_unit" => $detil_row->formulir_ketua == "0" ? "" : $detil_row->ketua_unit
                ];
                $data_unit_pada_formulir[$detil_row->unit_id."-".$detil_row->formulir_ketua == "0" ? "" : $detil_row->ketua_unit] = $unit_pada_formulir;
            }

            if(!isset($data_format_formulir[$detil_row->kpi_id])){
                $data_format_formulir[$detil_row->kpi_id] = [
                    "kpi_id" => $detil_row->kpi_id,
                    "nama_kpi" => $detil_row->nama_kpi,
                    "indikator_id" => $detil_row->indikator_id,
                    "nama_indikator" => $detil_row->nama_indikator,
                    "bidang_id" => $detil_row->bidang_id,
                    "nama_bidang" => $detil_row->nama_bidang,
                    "sumber" => $detil_row->sumber,
                    "bobot" => $detil_row->bobot,
                    "unit" => [
                        [
                            "unit_id" => $detil_row->unit_id, 
                            "nama_unit" => $detil_row->nama_unit , 
                            "ketua_unit" => $detil_row->formulir_ketua == "0" ? "" : $detil_row->ketua_unit,
                            "target" => $detil_row->target_institusi,
                            "institusi_id" => $detil_row->institusi_id,
                            "tenaga_pengajar" => $detil_row->tenaga_pengajar,
                            "satuan" => $detil_row->satuan,
                            "bobot" => $detil_row->bobot
                        ]
                    ]
                ];
            }else{
                array_push($data_format_formulir[$detil_row->kpi_id]["unit"],[
                    "unit_id" => $detil_row->unit_id, 
                    "nama_unit" => $detil_row->nama_unit , 
                    "ketua_unit" => $detil_row->formulir_ketua == "0" ? "" : $detil_row->ketua_unit,
                    "target" => $detil_row->target_institusi,
                    "institusi_id" => $detil_row->institusi_id,
                    "tenaga_pengajar" => $detil_row->tenaga_pengajar,
                    "satuan" => $detil_row->satuan,
                    "bobot" => $detil_row->bobot
                ]);
            }
        }
        $data["format_formulir"] = $data_format_formulir;
        $data["data_unit_pada_formulir"] = $data_unit_pada_formulir;
       $this->load->view("form_rencana_kerja/detil_format_formulir_rencana_kerja.php", $data);
    }

    public function update_format_formulir()
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = $request->data;

        if(sizeof($data->unit->inserted_unit) == 0 && sizeof($data->unit->changed_unit) == 0){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array("Unit" => "Minimal terdapat 1 unit pada setiap poin")));
        }



        if(property_exists($data, "kpi_baru")){
            if(empty($data->kpi_baru->nama_kpi)){
                return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array("Kpi" => "Nama kpi harus diisi")));
            }
        }

        /**
         * jika kpi_id_sebelum == null 
         * Maka artinya operasi berupa penambahan baris baru 
         * sedangkan, apabila kpi_sebelum != null berarti operasi update data yang sudah ada
        */
        $kpi_id_sebelum = property_exists($data,"kpi_sebelum") ? $data->kpi_sebelum : null;
        $bidang_id = $data->bidang;
        $sumber = $data->sumber;
        $bobot = $data->bobot;
        $unit_removed = $data->unit->removed_unit;
        $unit_inserted = $data->unit->inserted_unit;
        $unit_changed = $data->unit->changed_unit;
        $periode_id = $data->periode_id;
        $indikator_id = $data->indikator;

        $this->db->trans_begin();
        $new_kpi_id = null;
        if(property_exists($data,"kpi_baru")){
            $data_kpi_baru["nama_kpi"] = $data->kpi_baru->nama_kpi;
            $data_kpi_baru["bidang_id"] = $bidang_id;
            $data_kpi_baru["indikator_id"] = $indikator_id;
            
            $kpi_obj = $this->kpi_model->get_by_name($indikator_id, $data_kpi_baru["nama_kpi"]);
            if($kpi_obj){
                $new_kpi_id = $kpi_obj->id;
            }else {
                $new_kpi_id = $this->kpi_model->create($data_kpi_baru);
            }
        }

        foreach($unit_removed as $unit_being_removed){
            $condition["unit_id"]= $unit_being_removed->unit_id;
            $condition["formulir_ketua"] = $unit_being_removed->ketua_unit == "" ? "0" : "1";
            $formulir_user_for_removed = $this->formulir_rencana_kerja_model->get_formulir_by_unit($periode_id,$condition);
            $formulir_id = [];
            foreach($formulir_user_for_removed as $formulir ){
                array_push($formulir_id, $formulir->id);
            }
            if( sizeof($formulir_id) > 0 ){
                $affected_rows = $this->detil_formulir_rencana_kerja_model->delete_detil_format_by_array_form_id_and_kpi_id($kpi_id_sebelum,$formulir_id);
            }
        }

        foreach($unit_inserted as $unit_being_inserted){
            $condition["unit_id"]= $unit_being_inserted->unit_id;
            $condition["formulir_ketua"] = $unit_being_inserted->ketua_unit ==  "" ? "0" : "1";
            $formulir_user_for_inserted = $this->formulir_rencana_kerja_model->get_formulir_by_unit($periode_id,$condition);
            //formulir belum ada
            if(sizeof($formulir_user_for_inserted) == 0){
                $data_unit["unit_id"] = $unit_being_inserted->unit_id;
                $data_unit["ketua_unit"] = $unit_being_inserted->ketua_unit;
                $data_unit["anggota_unit"] = [];
                if($unit_being_inserted->ketua_unit == "" ){
                    $all_user_in_unit = $this->user_model->get_user_by_unit_id($unit_being_inserted->unit_id);
                    foreach($all_user_in_unit as $user){
                        if($user->status == "1"){
                            array_push($data_unit["anggota_unit"], $user);
                        }
                    }
                }
                //create formulir
                $formulir_user_for_inserted = $this->formulir_rencana_kerja_model->create_many_formulir($data_unit, $periode_id);
            }

            foreach($formulir_user_for_inserted as $formulir)
            {
                //create detil
                $data_detil_formulir["formulir_hasil_bidang_kinerja_utama_id"] = $formulir->id;
                $data_detil_formulir["kpi_id"] = $new_kpi_id == null ? $kpi_id_sebelum : $new_kpi_id;
                $data_detil_formulir["sumber"] = $sumber;
                $data_detil_formulir["bobot"] = $bobot;
                $data_detil_formulir["target_institusi"] = $unit_being_inserted->target;
                $data_detil_formulir["bidang_id"] = $bidang_id;
                $data_detil_formulir["satuan"] = $unit_being_inserted->satuan;
                $this->detil_formulir_rencana_kerja_model->create($data_detil_formulir);
            }

        }

        foreach($unit_changed as $unit_being_changed){
            $condition["unit_id"]= $unit_being_changed->unit_id;
            $condition["formulir_ketua"] = $unit_being_changed->ketua_unit == "" ? "0" : "1";
            $formulir_user_for_changed = $this->formulir_rencana_kerja_model->get_formulir_by_unit($periode_id,$condition);
            $formulir_id = [];
            foreach($formulir_user_for_changed as $formulir){
                array_push($formulir_id, $formulir->id);
            }
            $condition_update_detil["kpi_id"] = $new_kpi_id == null ? $kpi_id_sebelum : $new_kpi_id;
            $condition_update_detil["sumber"] = $sumber;
            $condition_update_detil["bobot"] = $bobot;
            $condition_update_detil["bidang_id"] = $bidang_id;
            $condition_update_detil["target_institusi"] = $unit_being_changed->target;
            $condition_update_detil["satuan"] = $unit_being_changed->satuan;
            $condition_update_detil["kpi_id_sebelum"] = $kpi_id_sebelum;
            $affected_rows = $this->detil_formulir_rencana_kerja_model->update_detil_format_by_array_form_id($formulir_id, $condition_update_detil);
        }

        if($this->db->trans_status() == FALSE){
            $this->db->trans_rollback();
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode(array("error" => "Maaf Service Sedang Bermasalah!")));
        }else{
            $this->db->trans_commit();
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("redirect" => site_url()."/formulir_rencana_kerja/get_format_formulir/".$periode_id)));
        }
        
    }
   
    public function delete_format_formulir()
    {   
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data = $request->data;

        $periode_id = $data->periode_id;
        $kpi_id = $data->kpi_id;
        $unit_need_removed = $data->unit;

        $this->db->trans_begin();
        $array_detail_row_deleted = [];
        //get formulir user
        foreach($unit_need_removed as $unit){
            $condition["unit_id"] = $unit->unit_id;
            $condition["formulir_ketua"] = $unit->ketua_unit == "" ? "0" : "1";
            $formulir_user = $this->formulir_rencana_kerja_model->get_formulir_by_unit($periode_id, $condition);
            $formulir_id = [];
            foreach($formulir_user as $formulir){
                array_push($formulir_id, $formulir->id);
            }
            //delete detail
            $array_detail_row_deleted = $this->detil_formulir_rencana_kerja_model->delete_detil_format_by_array_form_id_and_kpi_id($kpi_id,$formulir_id); 
        } 

        if($this->db->trans_status() == FALSE){
            $this->db->trans_rollback();
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(500)
            ->set_output(json_encode(array("error" => "Maaf Service Sedang Bermasalah!")));

        }else{
            $this->db->trans_commit();
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("redirect" => site_url()."/formulir_rencana_kerja/get_format_formulir/".$periode_id)));
        }

    }

    public function get_ketidak_tercapaian($formulir_id)
    {
        $data["data_tidak_tercapai"] = $this->detil_formulir_rencana_kerja_model->get_detil_tidak_tercapai_by_formulir_id($formulir_id);
        $data["formulir_id"] = $formulir_id;
        $data["action_update_ketidak_tercapaian"] = site_url()."/formulir_rencana_kerja/update_ketidak_tercapaian/";
        $data["breadcrumb"]= [
            [
                "url" => site_url()."/formulir_rencana_kerja",
                "name" => "Laporan"
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/detil/".$formulir_id,
                "name" => "Detil Laporan" 
            ],
            [
                "url" => site_url()."/formulir_rencana_kerja/get_ketidak_tercapaian/".$formulir_id,
                "name" => "Analisis Ketidak Tercapaian" 
            ]
        ];
        $data["error"] = null;
        $this->load->view('analisis_ketidak_tercapaian/list_analisis_ketidak_tercapaian.php', $data);
    }

    public function update_ketidak_tercapaian($detil_id)
    {
        $analisis_penyebab = $this->input->post('analisis_penyebab');
        $tindakan_korektif = $this->input->post('tindakan_korektif');
        $tindakan_pencegahan = $this->input->post('tindakan_pencegahan');
        $formulir_id = $this->input->post('formulir_id');

        $error = null;
        if(empty($analisis_penyebab)){
            $error = ["Analisis penyebab" => "tidak boleh kosong"];
        }

        if(empty($tindakan_korektif)){
            $error = ["Tindakan Korektif" => "tidak boleh kosong"];
        }

        if(empty($tindakan_pencegahan)){
            $error = ["Tindakan Pencegahan" => "tidak boleh kosong"];
        }

        if(isset($error)){
            $data["data_tidak_tercapai"] = $this->detil_formulir_rencana_kerja_model->get_detil_tidak_tercapai_by_formulir_id($formulir_id);
            $data["formulir_id"] = $formulir_id;
            $data["action_update_ketidak_tercapaian"] = site_url()."/formulir_rencana_kerja/update_ketidak_tercapaian/";
            $data["breadcrumb"]= [
                [
                    "url" => site_url()."/formulir_rencana_kerja",
                    "name" => "Laporan"
                ],
                [
                    "url" => site_url()."/formulir_rencana_kerja/detil/".$formulir_id,
                    "name" => "Detil Laporan" 
                ],
                [
                    "url" => site_url()."/formulir_rencana_kerja/get_ketidak_tercapaian/".$formulir_id,
                    "name" => "Analisis Ketidak Tercapaian" 
                ]
            ];
            $data["error"] = $error;
            $this->load->view('analisis_ketidak_tercapaian/list_analisis_ketidak_tercapaian.php', $data);

        }else{
            $condition["analisis_penyebab"]= $analisis_penyebab;
            $condition["tindakan_korektif"] = $tindakan_korektif;
            $condition["tindakan_pencegahan"] = $tindakan_pencegahan;
            $affected_rows = $this->detil_formulir_rencana_kerja_model->update_detil_ketidak_tercapaian($detil_id, $condition);
            
            redirect(site_url()."/formulir_rencana_kerja/get_ketidak_tercapaian/".$formulir_id);
        }


    }

    public function print($formulir_id){
        $formulir = $this->formulir_rencana_kerja_model->get_laporan_by_id($formulir_id);
        $detil = $this->detil_formulir_rencana_kerja_model->get_detil($formulir_id);
        $formulir->detil = $detil;
        $data["title"] = "detil formulir rencana kerja";
        $data["formulir"] = $formulir;
        //menentuakan view sebagai amin atau user biasa
        $this->load->view("form_rencana_kerja/print_format.php", $data);
    }

}

?>