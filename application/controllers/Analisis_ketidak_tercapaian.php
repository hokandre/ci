<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Analisis_ketidak_tercapaian extends CI_Controller 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('analisis_ketidak_tercapaian_model');
        $this->load->model('detil_formulir_rencana_kerja_model');
    }

    //list all formlir data
    public function index($formulir_id, $error=null, $success = null){
        $data_analisis = $this->analisis_ketidak_tercapaian_model->get_by_formulir_id($formulir_id);
        if(sizeof($data_analisis) == 0){
            $data["error"] = $error;
            $data["success"]= $success;
            $data["poin_kpi"]= $this->detil_formulir_rencana_kerja_model->get_detil_tidak_tercapai_by_formulir_id($formulir_id);
            
        }else{
            $data["data_analisis"] = $this->analisis_ketidak_tercapaian_model->get_by_formulir_id($formulir_id);
            $data["error"] = $error;
            $data["success"] = $success;
            $data["action_add_analisis"] = site_url()."/analisis_ketidak_tercapaian/".$formulir_id;
        }

    }



}

?>