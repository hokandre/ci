<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Kamus_indikator extends CI_Controller 
{

public function __construct()
{
    parent::__construct();
    $this->load->model('kamus_indikator_model');

}

public function index(){
    $result = $this->kamus_indikator_model->get();
    print_r($result);
}

}

?>