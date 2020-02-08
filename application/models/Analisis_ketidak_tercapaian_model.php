<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Analisis_ketidak_tercapaian_model extends CI_Model
{
    private $table_name = 'analisis_ketidak_tercapaian';


    public function create($data){
        $data_analisis["analisis_penyebab"] = $data["analisis_penyebab"];
        $data_analisis["tindakan_korektif"] = $data["tindakan_korektif"];
        $data_analisis["tindakan_pencegahan"] = $data["tindakan_pencegahan"];
        $data_analisis["formulir_rencana_kerja_id"] = $data["formulir_rencana_kerja_id"];
        $data_analisis["kpi_id"] = $data["kpi_id"];
        $this->db->insert($this->table_name, $data_analisis);
        return $this->db->insert_id();
    }

    public function get_by_formulir_id($formulir_id){
        $this->db->where('formulir_rencana_kerja_id',$formulir_id);
        $query = $this->db->get($this->table_name);
        return $query->result();
    }

    public function update($id, $data){
        $data_analisis["analisis_penyebab"] = $data["analisis_penyebab"];
        $data_analisis["tindakan_korektif"] = $data["tindakan_korektif"];
        $data_analisis["tindakan_pencegahan"] = $data["tindakan_pencegahan"];
        $data_analisis["formulir_rencana_kerja_id"] = $data["formulir_rencana_kerja_id"];
        $data_analisis["kpi_id"] = $data["kpi_id"];

        $this->db->set($data_analisis);
        $this->db->where('id', $id);
        $this->db->update($this->table_name);
        return $this->db->affected_rows();

    }

    public function delete($id){
        $this->db->where('id', $id);
        $this->db->delete($this->table_name);
        return $this->db->affected_rows();
    }



}

?>