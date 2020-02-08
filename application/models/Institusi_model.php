<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Institusi_model extends CI_Model
{
     private $table_name = 'institusi';
     private $table_pk= 'id'; 

public function get ()
{ 
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