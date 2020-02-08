<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
class Unit_model extends CI_Model
{
     private $table_name = 'unit';
     private $table_pk= 'id';
     private $table_ketua_unit = "ketua_unit";

public function get ()
{ 
    $sql = "SELECT unit.id, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id, ketua_unit.ketua_unit, COUNT(user.id) AS `jumlah_anggota`
     FROM unit LEFT JOIN user ON unit.id = user.unit_id
     JOIN ketua_unit ON unit.id = ketua_unit.unit_id 
     GROUP BY unit.id";
    $query = $this->db->query($sql);
    return $query->result();
    
}

public function get_with_institusi()
{ 
    $sql = "SELECT unit.id, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id, COUNT(user.id) AS `jumlah_anggota`,
     institusi.nama_institusi
     FROM unit 
     LEFT JOIN user ON unit.id = user.unit_id
     JOIN institusi ON unit.institusi_id = institusi.id 
     GROUP BY unit.id";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_unit_by_ketua_id($idKetua)
{
    $sql = "SELECT unit.id, unit.nama_unit, ketua_unit.ketua_unit, unit.tenaga_pengajar FROM unit 
    JOIN ketua_unit ON  unit.id = ketua_unit.unit_id 
    WHERE ketua_unit.ketua_unit = ".$idKetua;
    $query = $this->db->query($sql);
    if($query->num_rows() > 0){
        return $query->row();
    }else{
        return false;
    }
}

public function get_unit_by_institusi_id($institusi_id){
   
    

    $sql = "
        SELECT data_unit.id,data_unit.nama_unit, data_unit.institusi_id,data_unit.tenaga_pengajar, institusi.nama_institusi,data_unit.jumlah_anggota,
            ketua_unit.ketua_unit,
            (user.nama_user) as `nama_ketua_unit`
        FROM 
        (
            SELECT 
                unit.id, unit.nama_unit, unit.institusi_id,unit.tenaga_pengajar,
                COUNT(user.id) as `jumlah_anggota`
            FROM unit
            LEFT JOIN user
            ON user.unit_id = unit.id
            WHERE unit.institusi_id = $institusi_id
            GROUP BY unit.id
        ) as `data_unit` 
        JOIN  institusi,ketua_unit, user
        WHERE ketua_unit.unit_id = data_unit.id
        AND ketua_unit.ketua_unit = user.id
        AND institusi.id = data_unit.institusi_id
        ORDER BY data_unit.id";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_unit_by_id($unit_id){
    $sql =  "
            SELECT  unit.id, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id, 
                    ketua_unit.ketua_unit,
                    user.nama_user,
                    institusi.nama_institusi,
                    COUNT(user.id) as `jumlah_anggota`
            FROM unit
            JOIN ketua_unit, user, institusi
            WHERE unit.institusi_id = institusi.id
            AND unit.id = ketua_unit.unit_id
            AND ketua_unit.ketua_unit = user.id
            AND unit.id = ".$unit_id;
    $query = $this->db->query($sql);
    return $query->row();
}


public function update_unit($unit_id,$data){
    $this->db->set($data);
    $this->db->where("id" , $unit_id);
    $this->db->update($this->table_name);
    return $this->db->affected_rows();
}

public function add_unit($data){
    $data_new_unit = [
        "nama_unit" => $data["nama_unit"],
        "institusi_id" => $data["institusi_id"],
        "tenaga_pengajar" => $data["tenaga_pengajar"]
    ];

    $this->db->insert($this->table_name, $data_new_unit);
    $new_unit_id =  $this->db->insert_id();

    $data_new_ketua_unit = [
        "unit_id" =>  $new_unit_id,
        "ketua_unit" => $data["ketua_unit"]
    ];
    $this->db->insert("ketua_unit", $data_new_ketua_unit);

    return $new_unit_id;
}


}

?>