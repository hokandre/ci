<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Periode_model extends CI_Model
{
     private $table_name = 'periode';
     private $table_pk= 'id'; 

public function get ()
{ 
    $query = $this->db->get($this->table_name);
    return $query->result();
    
}

public function get_year()
{
    $sql = "SELECT DISTINCT tahun FROM ".$this->table_name." ORDER BY tahun DESC";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_by_year_and_semester($tahun, $semester){
    $sql = "SELECT * FROM ".$this->table_name." WHERE tahun = ".$tahun." AND semester = ".$semester;
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_by_id($id)
{
    $sql = " SELECT * FROM periode WHERE id = ".$id;
    $query = $this->db->query($sql);
    return $query->row();
}

public function add($data)
{
    $this->db->insert_batch($this->table_name, $data);
    return $this->db->insert_id();
}

public function get_by_start_year_and_end_year($start_year, $end_year){
    $sql = "SELECT * FROM periode WHERE tahun BETWEEN '$start_year' AND '$end_year'";
    $query = $this->db->query($sql);
    return $query->result();
}


}

?>