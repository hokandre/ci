<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Comment_model extends CI_Model
{
     private $table_name = 'comment';
     private $table_pk= 'id'; 


public function get($formulir_id)
{
    $sql = "SELECT user.nama_user, kpi.*, comment.* FROM comment
    JOIN user,kpi 
    WHERE user.id = comment.user_id
    AND kpi.id = comment.kpi_id
    AND comment.formulir_rencana_kerja_id = ".$formulir_id;
    $query = $this->db->query($sql);
    return $query->result();
}

public function add($data)
{
    $this->db->insert($this->table_name, $data);
    return $this->db->insert_id();
}

public function update($comment_id, $data)
{
    $this->db->set($data);
    $this->db->where('id', $comment_id);
    $this->db->update($this->table_name);
    return $this->db->affected_rows();
}

public function delete($comment_id)
{
    $this->db->where('id', $comment_id);
    $this->db->delete($this->table_name);
    return $this->db->affected_rows();
}

}

?>