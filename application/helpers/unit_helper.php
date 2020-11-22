<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_unit_session(&$session){
    $data_unit = [
        //data unit diri sendiri
        (object) [
            "nama_unit" => $session->userdata("nama_unit"),
            "id" => $session->userdata("unit_id"),
            "tenaga_pengajar" => $session->userdata("tenaga_pengajar"),
            "formulir_ketua" => "0"
        ]  
    ];    
    //menjadi ketua unit dari unit diri nya sendiri
    //contoh : unit program studi sistem informasi dan merupakan kaprodi sistem informasi
    if($session->userdata("login_ketua")){
        array_push($data_unit, 
        (object)[
            "nama_unit" => $session->userdata("unit_diketuai")["nama_unit"],
            "id" => $session->userdata("unit_diketua")["id"],
            "tenaga_pengajar" => $session->userdata("unit_diketai")["tenaga_pengajar"],
            "formulir_ketua" => "1"
        ]);
    }

    return $data_unit;
}

function get_data_unit(&$data_unit, &$session, $selected_institusi, $mode_individu,$unit_model){
    $data_unit = [];
    $hak_akses_bpm = 1;
    if($session->userdata("hak_akses") != $hak_akses_bpm || $mode_individu == true){
       $data_unit = get_unit_associated_with_user($session);
    }else{
        $data_unit_anggota = $unit_model->get_unit_by_institusi_id($selected_institusi);
        $data_unit_ketua = $unit_model->get_unit_ketua_by_institusi_id($selected_institusi);
        
        array_push($data_unit, $data_unit_anggota);
        array_push($data_unit, $data_unit_ketua);
    }
}

function set_unit(&$selected_unit, &$obj_unit,$is_ketua, $data_unit, $model_unit){
    if($selected_unit == null) {
        $selected_unit = $data_unit[0]->unit_id;
        $obj_unit = $data_unit[0];
    }else{
        $obj_unit = $model_unit->get_unit_by_id($selected_unit);
    }

    if($obj_unit->jumlah_anggota != 0) {
        if($is_ketua == "1") {
            $obj_unit->nama_unit = "Ketua ".$obj_unit->nama_unit;
        }else{
            $obj_unit->nama_unit = ($obj_unit->tenaga_pengajar == "1") ? "Dosen ".$obj_unit->nama_unit : "Anggota ".$obj_unit->nama_unit;
        }
    }
}

?>