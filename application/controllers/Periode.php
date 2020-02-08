<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Periode extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("periode_model");
    }

public function add()
{
    $tahun = $this->input->post("tahun");
    $form = $this->input->post("form");
    $data = [
        ["tahun" => $tahun, "semester" => "1"],
        ["tahun" => $tahun, "semester" => "2"]
    ];

    $urlRedirect = site_url()."/formulir_rencana_kerja/form";

    if($form == 'edit'){
        $urlRedirect = site_url()."/formulir_rencana_kerja/get_format_formulir";
    }

    $this->periode_model->add($data);
    return $this->output
    ->set_content_type('application/json')
    ->set_status_header(200)
    ->set_output(json_encode( array("success" => true, "redirect" => $urlRedirect )));

    
}


}

?>