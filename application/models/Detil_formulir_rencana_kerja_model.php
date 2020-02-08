<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Detil_formulir_rencana_kerja_model extends CI_Model
{
     private $table_name = 'detil_formulir_hasil_bidang_kinerja_utama';
     private $table_pk= 'table_pk'; 

public function create ($data)
{ 
    $this->db->insert($this->table_name, $data);
    return $this->db->insert_id();
}

public function update_detil_kpi($detil_id, $data_detil)
{
    $this->db->set($data_detil);
    $this->db->where('id', $detil_id);
    $this->db->update($this->table_name);
    return $this->db->affected_rows();
}

public function update_detil_format_by_array_form_id($formulir_id, $data)
{
    $sql =" UPDATE detil_formulir_hasil_bidang_kinerja_utama 
    SET kpi_id =".$data["kpi_id"].
    " ,target_institusi =".$data["target_institusi"].
    " ,sumber ='".$data["sumber"]."'".
    " ,bidang_id =".$data["bidang_id"].
    " ,satuan = '".$data["satuan"]."'".
    " ,bobot = '".$data["bobot"]."'".
    " WHERE formulir_hasil_bidang_kinerja_utama_id IN (".join(",", $formulir_id).") AND kpi_id = ".$data["kpi_id_sebelum"];
    $query = $this->db->query($sql);
    return $this->db->affected_rows();
}

public function delete_detil_format_by_array_form_id($formulir_id)
{
    $sql = "DELETE FROM detil_formulir_hasil_bidang_kinerja_utama 
    WHERE formulir_hasil_bidang_kinerja_utama_id IN (".join(",", $formulir_id).")";
    $query = $this->db->query($sql);
    return $this->db->affected_rows();
}

public function delete_detil_format_by_array_form_id_and_kpi_id($kpi_id,$formulir_id)
{
    $sql = "DELETE FROM detil_formulir_hasil_bidang_kinerja_utama 
    WHERE formulir_hasil_bidang_kinerja_utama_id IN (".join(",", $formulir_id).") AND kpi_id = ".$kpi_id;
    $query = $this->db->query($sql);
    return $this->db->affected_rows();
}

public function terima_detil_kpi($detil_id, $status){
    $sql = "UPDATE detil_formulir_hasil_bidang_kinerja_utama SET status = '$status' WHERE id=".$detil_id;
     $query = $this->db->query($sql);
     return $this->db->insert_id();
}

public function get_detil($formulir_id)
{
    $sql = "SELECT  kpi.*, indikator.*, detil_formulir_hasil_bidang_kinerja_utama.* FROM detil_formulir_hasil_bidang_kinerja_utama JOIN indikator, kpi 
    WHERE detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
    AND kpi.indikator_id = indikator.id
    AND formulir_hasil_bidang_kinerja_utama_id = ".$formulir_id;
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_detil_tidak_tercapai_by_formulir_id($formulir_id)
{
    $this->db->select($this->table_name.".*, kpi.nama_kpi");
    $this->db->where("formulir_hasil_bidang_kinerja_utama_id", $formulir_id);
    $this->db->where("nilai_aktual < target_institusi");
    $this->db->from($this->table_name);
    $this->db->join("kpi","kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id");
    $query = $this->db->get();
    return $query->result();
}

public function update_detil_ketidak_tercapaian($id, $data)
{
    $this->db->where('id', $id);
    $this->db->set($data);
    $this->db->update($this->table_name);
    return $this->db->affected_rows();
}

}


?>