<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Renstra_periode_model extends CI_Model
{
     private $table_name = 'renstra_periode';
     private $table_pk= 'id'; 


public function get_by_year($year){
    $sql = "SELECT * FROM renstra_periode WHERE tahun_awal <= '$year' AND tahun_akhir >= '$year'";
    $query = $this->db->query($sql);
    return $query->row();
}

public function get_all(){
    $query = $this->db->get($this->table_name);
    return $query->result();
}

public function get_by_id($id){
    $this->db->where("id", $id);
    $query = $this->db->get($this->table_name);
    return $query->row();
}

}

?>