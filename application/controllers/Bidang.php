<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Bidang extends CI_Controller 
{

public function __construct()
{
    parent::__construct();

    if (!$this->session->userdata("logged")){
        redirect("/auth/login");
    }

    $this->load->database();
    $this->load->model('bidang_model');
    $this->load->helper(array('form', 'url',"util_helper","unit_helper","breadcrumb_helper","kinerja_helper"));
    $this->load->model('institusi_model');
    $this->load->model('renstra_periode_model');
    $this->load->model('periode_model');
    $this->load->model('unit_model');
    $this->load->model('user_model');

    //db data
    $this->data_institusi = [];
    $this->data_bidang = [];
    $this->data_renstra = [];
    $this->data_periode = [];
    $this->data_unit = [];

    //authentication role
    $this->mode_individu = "1"; //true
    $this->institusi_id = null;
    $this->obj_institusi = null;
    $this->unit_id = null;
    $this->obj_unit = null;
    $this->formulir_ketua = "0"; //mencari data ketua unit
    $this->user_id = null;
    $this->obj_user = null;

    //periode data
    $this->obj_renstra = null;
    $this->renstra_id = null;
    $this->obj_periode = null;
    $this->periode_id = null;

    //data 
    $this->obj_bidang = null;
    $this->bidang_id = null;
    $this->crumb_institusi = "0";
    $this->data_crumb_institusi = [
        "periode_id_institusi" => "",
        "renstra_id_institusi" => "",
        "url" => site_url()."/bidang/pencapaian_institusi"
    ];
    $this->crumb_unit = "0";
    $this->data_crumb_unit = [
        "periode_id_unit" => "",
        "renstra_id_unit" => "",
        "url" => site_url()."/bidang/pencapaian_unit"
    ];

    //accumulation data
    $this->data_kinerja = (object) [
        "persen_tercapai" => 0,
        "persen_tidak_tercapai" => 100,
        "nilai_tercapai" => 0,
        "nilai_maksimal" => 100,
        "nilai_tidak_tercapai" => 100
    ];
    $this->data_kinerja_anggota = [];
    $this->data_detil_kinerja = [];
    $this->data_statistik_kinerja = [];
}

/* up */
private function set_mode_individu($mode_individu){
    if($mode_individu == null || $mode_individu == "1") {
        $this->mode_individu = "1";
    }else {
        $this->mode_individu = "0";
    }
}

private function set_institusi($institusi_id){
    if($institusi_id == null){
        $this->obj_institusi = $this->data_institusi[0];
        $this->institusi_id = $this->obj_institusi->id;
    }else {
        $this->institusi_id = $institusi_id;
        $this->obj_institusi = $this->institusi_model->get_by_id($institusi_id);
    }
}

private function set_unit($unit_id){
    if($unit_id == null){
        $this->obj_unit = $this->data_unit[0];
        $this->unit_id = $this->obj_unit->id;
    }else {
        $this->unit_id = $unit_id;
        $this->obj_unit = $this->unit_model->get_by_id($unit_id);
    }
}

private function set_user_id($user_id){
    if($user_id == null) {

    }else {
        $obj_user = $this->user_model->get_user_by_id($user_id);
        if($obj_user != null) {
            $this->user_id = $user_id;
            $this->obj_user = $obj_user;
        }
    }
}

private function set_data_unit($mode_individu){
    if($this->session->userdata("nama_hak_akses") != "admin"){
       $this->data_unit = $this->session->userdata("all_unit_user");
    }else if($mode_individu == "1") {
        $this->data_unit = $this->session->userdata("all_unit_user");
    }else{
        $data_unit_anggota = $this->unit_model->get_unit_by_institusi_id($this->institusi_id);
        $data_unit_ketua = $this->unit_model->get_unit_ketua_by_institusi_id($this->institusi_id);
        foreach($data_unit_ketua as $unit_ketua){
            array_push($data_unit_anggota, $unit_ketua);
        }
        $this->data_unit = $data_unit_anggota;
    }
}

private function set_formulir_ketua($formulir_ketua){
    if($formulir_ketua != null || $formulir_ketua == "1"){
        $this->formulir_ketua = $formulir_ketua;
    } 
}

private function set_bidang($selected_bidang){
    if($selected_bidang == null){
        $this->obj_bidang = $this->data_bidang[0];
        $this->bidang_id = $this->obj_bidang->id;
    }else {
        $this->bidang_id = $selected_bidang;
        $this->obj_bidang = $this->bidang_model->get_by_id($selected_bidang);
    }
}

private function set_renstra_periode($selected_renstra_periode){
    if($selected_renstra_periode == null){
        $this->renstra_id = $this->data_renstra[0]->id;
        $this->obj_renstra = $this->data_renstra[0];
    }else{
        $this->renstra_id = $selected_renstra_periode;
        $this->obj_renstra = $this->renstra_periode_model->get_by_id($selected_renstra_periode);
    }
}

private function set_periode($string_tahun_semester){
    if($string_tahun_semester == null) {
        $array_periode = $this->periode_model->get_by_start_year_and_end_year($this->obj_renstra->tahun_awal, $this->obj_renstra->tahun_akhir);
        $this->obj_periode = $array_periode[0];
        $this->periode_id = $this->obj_periode->id;
    }else{
        $split_tahun_semester = explode("-", $string_tahun_semester);
        $tahun = $split_tahun_semester[0];
        $semester = $split_tahun_semester[1];
        $obj_periode = $this->periode_model->get_row_by_year_and_semester($tahun, $semester);
        if($obj_periode != null ) {
            $this->obj_periode = $obj_periode;
            $this->periode_id = $this->obj_periode->id;
        }
    }
}

private function set_crumb_institusi($show_bread_crumb_institusi){
    if($show_bread_crumb_institusi == "1"){
        $this->crumb_institusi = "1";
        $this->data_crumb_institusi["periode_id_institusi"] = $this->input->post("periode_id_institusi");
        $this->data_crumb_institusi["renstra_id_institusi"] = $this->input->post("renstra_id_institusi");
    }
}

private function set_crumb_unit($show_bread_crumb_unit){
    if($show_bread_crumb_unit == "1"){
        $this->crumb_unit = "1";
        $this->data_crumb_unit["periode_id_unit"] = $this->input->post("periode_id_unit");
        $this->data_crumb_unit["renstra_id_unit"] = $this->input->post("renstra_id_unit");
    }
}

private function set_data_kinerja($data_kinerja){
    $this->data_kinerja = $data_kinerja;
}

private function set_data_kinerja_anggota($formulir_anggota){
   if(sizeof($formulir_anggota) != 0 && $formulir_anggota != null) {
        $this->data_kinerja_anggota = $formulir_anggota;
   }
}

private function set_data_detil_kinerja($array_formulir){
    if(sizeof($array_formulir) != 0 && $array_formulir != null) {
        $this->data_detil_kinerja = $array_formulir;
   }
}

private function set_data_statistik_kinerja($data_statisik){
    $this->data_statistik_kinerja = $data_statisik;
}

private function get_periode_id_by_year($tahun_awal, $tahun_akhir){
    $rentang_periode_id = $this->periode_model->get_by_start_year_and_end_year($tahun_awal, $tahun_akhir);
    if(sizeof($rentang_periode_id) != 0){
        $array_periode_id = [];
        foreach($rentang_periode_id as $periode){
            array_push($array_periode_id, $periode->id);
        }

       return $array_periode_id;
    }

    return null;
}


private function display($view_name){
    $data_view = [
        "data_aksi" => [
            'action_lihat_bidang_institusi' =>  site_url()."/bidang/pencapaian_institusi",
            'action_lihat_bidang_unit' => site_url()."/bidang/pencapaian_unit",
            'action_lihat_bidang_user' => site_url()."/bidang/pencapaian_user"
        ],
        "data_institusi" => $this->data_institusi,
        "data_bidang" => $this->data_bidang,
        "data_renstra" => $this->data_renstra,
        "data_periode" => $this->data_periode,
        "data_unit" => $this->data_unit,
        "mode_individu" => $this->mode_individu = "1",
        "institusi_id" => $this->institusi_id,
        "obj_institusi" => $this->obj_institusi,
        "unit_id" => $this->unit_id,
        "obj_unit" => $this->obj_unit,
        "user_id" => $this->user_id,
        "obj_user" => $this->obj_user,
        "formulir_ketua" => $this->formulir_ketua,
        "obj_renstra" => $this->obj_renstra,
        "renstra_id" => $this->renstra_id, 
        "obj_periode" => $this->obj_periode,
        "periode_id" => $this->obj_periode != null ? $this->obj_periode->tahun."-".$this->obj_periode->semester : null,
        "obj_bidang" => $this->obj_bidang,
        "bidang_id" => $this->bidang_id,
        "crumb_institusi" => $this->crumb_institusi,
        "data_crumb_institusi" => $this->data_crumb_institusi,
        "crumb_unit" => $this->crumb_unit,
        "data_crumb_unit" => $this->data_crumb_unit,
        "data_kinerja" => $this->data_kinerja,
        "data_kinerja_anggota" => $this->data_kinerja_anggota,
        "data_detil_kinerja" => $this->data_detil_kinerja,
        "data_statistik_kinerja" => $this->data_statistik_kinerja,
        "keterangan_periode" => $this->obj_periode !== null ? 
        ($this->obj_periode->semester == "1" ?
            "September ".($this->obj_periode->tahun)." - Februari ".($this->obj_periode->tahun+1) 
                :
            "Maret ".($this->obj_periode->tahun+1)." - Agustus ".($this->obj_periode->tahun+1)
        ) 
            :
        "Data Periode Belum Ada"
    ];
    $this->load->view($view_name, $data_view);
}

public function index($error=null)
{
    $nama_hak_akses = $this->session->userdata("nama_hak_akses");
   if($nama_hak_akses == "admin") {
       $this->pencapaian_institusi();
   }else if($nama_hak_akses == "manajemen"){
       $this->pencapaian_user();
   }else if($nama_hak_akses == "ketua"){
       $this->pencapaian_unit();
   }else {
       $this->pencapaian_user();
   }
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
    $this->data_institusi = $this->institusi_model->get();
    $this->data_bidang = $this->bidang_model->get();
    $this->data_renstra = $this->renstra_periode_model->get_all();
    $this->data_periode = $this->periode_model->get();

    $mode_individu = $this->input->post("mode_individu");
    $this->set_mode_individu($mode_individu);

    $intitusi_id = $this->input->post("institusi_id");
    $this->set_institusi($intitusi_id);

    $renstra_id = $this->input->post("renstra_periode");
    $this->set_renstra_periode($renstra_id);

    $string_tahun_semester = $this->input->post("periode_id");
    $this->set_periode($string_tahun_semester);

    $bidang_id = $this->input->post("bidang_id");
    $this->set_bidang($bidang_id);

    if( $this->obj_periode != null ){
        //pie chart
        $formulir_unit_anggota = $this->bidang_model->get_formulir_unit_anggota_institusi($this->bidang_id,$this->institusi_id, $this->periode_id);
        if(sizeof($formulir_unit_anggota) != 0 ) {
            $this->set_data_kinerja_anggota($formulir_unit_anggota);
            $this->set_data_detil_kinerja($formulir_unit_anggota);
            $kinerja_institusi = hitung_pencapaian_institusi($formulir_unit_anggota);
            if($kinerja_institusi !== null) $this->set_data_kinerja($kinerja_institusi);
        }
    }

    if($this->obj_renstra != null) {
        $id_periode_5_tahun = $this->get_periode_id_by_year($this->obj_renstra->tahun_awal, $this->obj_renstra->tahun_akhir); 
        $statistik_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_institusi_and_periode($this->bidang_id, $this->institusi_id, $id_periode_5_tahun);
        if(sizeof($statistik_kinerja) != 0 ) $this->set_data_statistik_kinerja($statistik_kinerja);
    }

    $view_name = 'bidang/page_institusi/index.php';
    $this->display($view_name);
}

public function pencapaian_unit(){
    //view sebagai data unit
    $mode_individu = "0";
    $this->set_mode_individu($mode_individu);
    $this->data_institusi = $this->institusi_model->get();
    $this->data_bidang = $this->bidang_model->get();
    $this->data_renstra = $this->renstra_periode_model->get_all();
    $this->data_periode = $this->periode_model->get();

    $institusi_id = $this->input->post("institusi_id");
    $this->set_institusi($institusi_id);
    $this->set_data_unit($mode_individu);

    $unit_id = $this->input->post("unit_id");
    $this->set_unit($unit_id);

    $formulir_ketua = $this->input->post("formulir_ketua");
    $this->set_formulir_ketua($formulir_ketua);

    $renstra_id = $this->input->post("renstra_periode");
    $this->set_renstra_periode($renstra_id);

    $string_tahun_semester = $this->input->post("periode_id");
    $this->set_periode($string_tahun_semester);

    $bidang_id = $this->input->post("bidang_id");
    $this->set_bidang($bidang_id);

    $crumb_institusi = $this->input->post("crumb_institusi");
    $this->set_crumb_institusi($crumb_institusi);
    
    if($this->obj_periode != null) {
        $array_formulir_anggota = $this->bidang_model->get_formulir_user_anggota_unit($this->bidang_id,$this->unit_id,$this->formulir_ketua, $this->periode_id);
        if(sizeof($array_formulir_anggota) != 0){
            $this->set_data_kinerja_anggota($array_formulir_anggota);
            $this->set_data_detil_kinerja($array_formulir_anggota);
            $kinerja_unit = hitung_pencapaian_unit( $array_formulir_anggota);
            if($kinerja_unit != null) $this->set_data_kinerja($kinerja_unit);

            $formulir_id = [];
            foreach($array_formulir_anggota as $formulir){
                array_push($formulir_id, $formulir->id);
            }

            $kpi_formulir = $this->bidang_model->get_detil_pencapaian_bidang_by_unit_and_formulir($this->bidang_id, $formulir_id);
            $this->set_data_detil_kinerja($kpi_formulir);
        }
    }

    if($this->obj_renstra != null) {
        $id_periode_5_tahun = $this->get_periode_id_by_year($this->obj_renstra->tahun_awal, $this->obj_renstra->tahun_akhir); 
        $statistik_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_unit_and_periode($this->bidang_id, $this->unit_id, $this->formulir_ketua, $id_periode_5_tahun);
        if(sizeof($statistik_kinerja) != 0 ) $this->set_data_statistik_kinerja($statistik_kinerja);
    }
    $view_name = 'bidang/page_unit/index.php';
    $this->display($view_name);
}

public function pencapaian_user(){
    $mode_individu = $this->input->post("mode_individu");
    $this->set_mode_individu($mode_individu);

    $this->data_institusi = $this->institusi_model->get();
    $this->data_bidang = $this->bidang_model->get();
    $this->data_renstra = $this->renstra_periode_model->get_all();
    $this->data_periode = $this->periode_model->get();

    $institusi_id = $this->input->post("institusi_id");
    $this->set_institusi($institusi_id);
    $this->set_data_unit($mode_individu);

    $unit_id = $this->input->post("unit_id");
    $this->set_unit($unit_id);

    $formulir_ketua = $this->input->post("formulir_ketua");
    $this->set_formulir_ketua($formulir_ketua);

    $user_id = $this->input->post("user_id");
    $this->set_user_id($user_id);

    $renstra_id = $this->input->post("renstra_periode");
    $this->set_renstra_periode($renstra_id);

    $string_tahun_semester = $this->input->post("periode_id");
    $this->set_periode($string_tahun_semester);

    $bidang_id = $this->input->post("bidang_id");
    $this->set_bidang($bidang_id);

    $crumb_institusi = $this->input->post("crumb_institusi");
    $this->set_crumb_institusi($crumb_institusi);

    $crumb_unit = $this->input->post("crumb_unit");
    $this->set_crumb_unit($crumb_unit);

    if($this->obj_periode != null) {
        $kpi_formulir_user = $this->bidang_model->get_detil_pencapaian_bidang_by_user_formulir($this->bidang_id, $this->user_id, $this->unit_id, $this->formulir_ketua, $this->periode_id);

        if($kpi_formulir_user != null && sizeof($kpi_formulir_user) != 0){
            $kinerja_user = hitung_pencapaian_user($kpi_formulir_user);
            $this->set_data_kinerja($kinerja_user);
            $this->set_data_detil_kinerja($kpi_formulir_user);
        }
    }

    if($this->obj_renstra != null) {
        $id_periode_5_tahun = $this->get_periode_id_by_year($this->obj_renstra->tahun_awal, $this->obj_renstra->tahun_akhir); 
        $statistik_kinerja = $this->bidang_model->get_statistic_pencapaian_bidang_by_user_and_periode($this->bidang_id, $this->user_id, $this->unit_id, $this->formulir_ketua, $id_periode_5_tahun);
        if(sizeof($statistik_kinerja) != 0 ) $this->set_data_statistik_kinerja($statistik_kinerja);
    }
    $nama_view = "bidang/dashboard_bidang_user.php";
    $this->display($nama_view);
}


}

?>