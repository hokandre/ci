<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class User_model extends CI_Model
{
    private $table_name = 'user';
    private $table_pk= 'id'; 

public function index ()
{ 

     //code here
}

public function seed_data ($data)
{ 

     $this->db->insert_batch($this->table_name, $data);
     return $this->db->affected_rows();
}

public function get_user_by_unit_id($unit_id)
{
     $this->db->where("unit_id", $unit_id);
     $query = $this->db->get($this->table_name);
     return $query->result();
}

public function get_user_by_id($id){
     $sql = "
     SELECT 
          user.id, user.nama_user,user.unit_id,user.status,user.password_user,
          unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id,
          institusi.nama_institusi
     FROM user 
     JOIN unit , institusi
     WHERE 
          user.unit_id = unit.id 
          AND unit.institusi_id = institusi.id
          AND user.id =".$id;
     $query = $this->db->query($sql);
     
     if($query->num_rows() > 0 ){
          return $query->row();
     }else{
          return false;
     }
}

public function get_all(){
     $this->db->from($this->table_name);
     $this->db->select(
          "user.id, user.nama_user, user.unit_id, user.status,
          unit.nama_unit, unit.tenaga_pengajar,unit.institusi_id,
          institusi.nama_institusi"
     );
     $this->db->join("unit", "unit.id = user.unit_id");
     $this->db->join("institusi", "unit.institusi_id = institusi.id");

     $query = $this->db->get();
     return $query->result();
}

public function create($data){
     $this->db->insert($this->table_name,$data);
     return $this->db->insert_id();
     
}

public function update_user($user_id,$data){
     $this->db->set($data);
     $this->db->where("id", $user_id);
     $this->db->update($this->table_name);
     return $this->db->affected_rows();
}

public function update_password($user_id, $newPassword){
     $this->db->set("password_user", $newPassword);
     $this->db->where("id", $user_id);
     $this->db->update($this->table_name);
     return $this->db->affected_rows();
}

public function get_user_by_name_and_unit($name){
     $sql =  $sql = "
     SELECT 
          user.id, user.nama_user,user.unit_id,user.status,user.password_user,
          unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id,
          institusi.nama_institusi
     FROM user 
     JOIN unit , institusi
     WHERE 
         (user.unit_id = unit.id 
          AND unit.institusi_id = institusi.id)
          AND 
          (
               user.nama_user LIKE %'$name'%
          )";
     $query = $this->db->query($sql);
     return $query->result();
}

}

?>