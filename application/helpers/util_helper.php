<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_mode_individu(&$mode_individu){
    if($mode_individu == null || $mode_individu == "1") {
        $mode_individu = "1";
    }else {
        $mode_individu = "0";
    }
}

function set_institusi(&$selected_institusi, &$objInstitusi, $data_institusi, $model_institusi){
    
    if($selected_institusi == null){
        $selected_institusi = $data_institusi[0]->id;
        $objInstitusi = $data_institusi[0];
    }else {
        $objInstitusi = $model_institusi->get_by_id($selected_institusi);
    }
}

function set_bidang(&$selected_bidang, $data_bidang){
    if($selected_bidang == null) {
        $selected_bidang = $data_bidang[0]->id;
    }
}

function set_renstra_periode(&$selected_renstra_periode, &$obj_current_renstra ,$data_renstra_periode, $model_renstra_periode){
    if($selected_renstra_periode == null){
        $selected_renstra_periode = $data_renstra_periode[0]->id;
        $obj_current_renstra = $data_renstra_periode[0];
    }else{
        $obj_current_renstra = $model_renstra_periode->get_by_id($selected_renstra_periode);
    }
}

function set_periode(&$string_tahun_semester,&$periode_obj,$obj_current_renstra,  $model_periode){
    if($string_tahun_semester == null) {
        $array_periode = $model_periode->get_by_start_year_and_end_year($obj_current_renstra->tahun_awal, $obj_current_renstra->tahun_akhir);
        $periode_obj = $array_periode[0];
        $string_tahun_semester = $periode_obj->tahun."-".$periode_obj->semester;
    }else{
        $split_tahun_semester = explode("-", $string_tahun_semester);
        $tahun = $split_tahun_semester[0];
        $semester = $split_tahun_semester[1];
        $periode_obj = $model_periode->get_row_by_year_and_semester($tahun, $semester);
    }
}

?>