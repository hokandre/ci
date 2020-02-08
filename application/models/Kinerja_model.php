<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Kinerja_model extends CI_Model
{
    

public function index ()
{ 

     //code here
}

public function seed_data ($data)
{ 

     $this->db->insert_batch($this->table_name, $data);
     return $this->db->affected_rows();
}

}

?>