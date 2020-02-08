<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Kamus_indikator_model extends CI_Model
{
     private $table_name = 'kamus_indikator';
     private $table_pk= 'id'; 

public function get ()
{ 
    $query = $this->db->get($this->table_name);
    return $query->result();
}

public function get_by_indikator_id($indikator_id)
{
    $sql = "SELECT * FROM kamus_indikator 
    JOIN unit 
    WHERE kamus_indikator.unit_id = unit.id 
    AND kamus_indikator.indikator_id = ".$indikator_id;

    $query = $this->db->query($sql);
    return $query->result();
}

public function get_by_unit_and_indikator_id($unit_id, $indikator_id)
{
    $this->db->where("unit_id", $unit_id);
    $this->db->where("indikator_id", $indikator_id);
    $query = $query = $this->db->get($this->table_name);
    return $query->row();
}

public function add_detil ($data)
{
    $this->db->insert($this->table_name, $data);
    return $this->db->insert_id();
}

public function delete_detil($indikator_id, $unit_id)
{
    $this->db->delete($this->table_name, array("indikator_id" => $indikator_id, "unit_id" => $unit_id));
    return $this->db->affected_rows();
}

public function get_by_unit_id($unit_id){
    $sql = "
        SELECT indikator.id, indikator.nama_indikator 
        FROM indikator
        JOIN kamus_indikator
        WHERE indikator.id = kamus_indikator.indikator_id
        AND kamus_indikator.unit_id = $unit_id 
    ";
    $query = $this->db->query($sql);
    return $query->result();
}


}

?>