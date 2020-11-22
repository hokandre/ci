<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Comment extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata("logged")){
            redirect("/auth/login");
        }
        
        $this->load->model('comment_model');
    }

    public function create()
    {

        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data_input = $request->data;
        $data["kpi_id"] = $data_input->kpi_id;
        $data["formulir_rencana_kerja_id"] = $data_input->formulir_id;
        $data["isi"] = $data_input->isi;
        $data["user_id"] = $this->session->userdata("id");
    
        if(empty($data["isi"])){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array("Catatan" => "isi tidak boleh kosong!")));
        }else{
            $this->comment_model->add($data);
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array("redirect" => site_url()."/formulir_rencana_kerja/detil/".$data["formulir_rencana_kerja_id"])));
        }
    }


    public function update($comment_id)
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data_input = $request->data;

        $data["kpi_id"] = $data_input->kpi_id;
        $data["formulir_rencana_kerja_id"] = $data_input->formulir_id;
        $data["isi"] = $data_input->isi;
        $data["user_id"] = $this->session->userdata("id");

        if(empty($data["isi"])){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(400)
            ->set_output(json_encode(array("Isi Catatan" => "tidak boleh kosong!")));
        }else{
            $this->comment_model->update($comment_id,$data);
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["redirect" => site_url()."/formulir_rencana_kerja/detil/".$data["formulir_rencana_kerja_id"]]));
        }
    }

    public function delete($comment_id)
    {
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean);
        $data_input = $request->data;
        $data["formulir_rencana_kerja_id"] = $data_input->formulir_id;
        $this->comment_model->delete($comment_id);
        return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(["redirect" => site_url()."/formulir_rencana_kerja/detil/".$data["formulir_rencana_kerja_id"]]));
    }
}

?>