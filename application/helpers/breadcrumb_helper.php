<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function set_bread_crumb_institusi(&$show_bread_crumb_institusi, &$data, $data_input){
    if($show_bread_crumb_institusi == null || $show_bread_crumb_institusi = "0"){
        $show_bread_crumb_institusi = "0";
    }

    if($show_bread_crumb_institusi == "1"){
        $data['selected_periode_tahun_semetser'] = $data_input["periode_id"];
        $data['selected_renstra_periode'] = $data_input["renstra_id"];
    }
}

function set_bread_crumb_unit(&$show_bread_crumb_institusi, &$data, $data_input){
    if( $show_bread_crumb_institusi == null) {
        $show_bread_crumb_institusi = "0";
    }

    if($show_bread_crumb_institusi == "1"){
        $data['selected_periode_tahun_semester_institusi'] = $data_input["periode_id"];
        $data['selected_renstra_periode_institusi'] = $data_input["renstra_id"];
        $data["breadcrumb"] = [
            [
                "url" => site_url()."/bidang/pencapaian_institusi",
                "name" => $data_input["obj_institusi"]->nama_institusi
            ]
        ];
    }
}

?>