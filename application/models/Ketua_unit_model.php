<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Ketua_unit_model extends CI_Model
{
     private $table_name = 'ketua_unit';

     public function update_ketua_unit($unit_id, $ketua_unit){
        $this->db->set("ketua_unit", $ketua_unit);
        $this->db->where("unit_id", $unit_id);
        $this->db->update($this->table_name);
        return $this->db->affected_rows();
    }
    

}

?>