<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
include 'ChromePhpModel.php';


class Formulir_rencana_kerja_model extends CI_Model
{
     private $table_name = 'formulir_hasil_bidang_kinerja_utama as formulir';
     private $tb_detil_formulir_hasil_bidang_kinerja_utama = 'detil_formulir_hasil_bidang_kinerja_utama as detil';
     private $tb_periode = 'periode as periode';


public function get_laporan ($tahun, $semester, $institusi)
{ 
    $conditionSemester = "";
    $conditionInstitusiType = "";
    if($semester != NULL){
        $conditionSemester = " AND periode.semester = '$semester' ";
    }
    if($institusi != NULL){
        $conditionInstitusiType = " AND unit.institusi_id=".$institusi;
    }

    $sql = "SELECT data_nilai.*,data_nilai.nilai * data_bobot.bobot as `score`  FROM 
	(
    SELECT formulir_user.*, (100/ COUNT(detil_formulir_hasil_bidang_kinerja_utama.id)) as `bobot` FROM (
        SELECT formulir_hasil_bidang_kinerja_utama.id 
    	FROM formulir_hasil_bidang_kinerja_utama
        JOIN user,unit,periode
        WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND periode.tahun ="
        .$tahun
        .$conditionSemester
        .$conditionInstitusiType."
        ) as `formulir_user`
     	JOIN detil_formulir_hasil_bidang_kinerja_utama 
    	ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id
     	GROUP BY formulir_user.id 
    ) as `data_bobot`
    JOIN
    (
        SELECT formulir_user.*, 
        (COALESCE(SUM(detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi), 0)) as `nilai`
        FROM (
            SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND periode.tahun ="
            .$tahun
            .$conditionSemester
            .$conditionInstitusiType."
        ) as `formulir_user` 
        LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama 
        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id 
        AND detil_formulir_hasil_bidang_kinerja_utama.status = '1' GROUP BY formulir_user.id
    ) as `data_nilai`
    ON data_nilai.id = data_bobot.id
    ORDER BY data_nilai.periode_id ASC" ;
   
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_laporan_ketua_unit($tahun, $semester, $unit_id){
    $conditionSemester = "";
    if($semester != NULL){
        $conditionSemester = " AND periode.semester = '$semester' ";
    }
    $sql = "SELECT data_nilai.*,data_nilai.nilai * data_bobot.bobot as `score`  FROM 
	(
    SELECT formulir_user.*, (100/ COUNT(detil_formulir_hasil_bidang_kinerja_utama.id)) as `bobot` FROM (
        SELECT formulir_hasil_bidang_kinerja_utama.id 
    	FROM formulir_hasil_bidang_kinerja_utama
        JOIN user,unit,periode
        WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND periode.tahun ="
        .$tahun
        .$conditionSemester."
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        ) as `formulir_user`
     	JOIN detil_formulir_hasil_bidang_kinerja_utama 
    	ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id
     	GROUP BY formulir_user.id 
    ) as `data_bobot`
    JOIN
    (
        SELECT formulir_user.*, 
        (COALESCE(SUM(detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi), 0)) as `nilai`
        FROM (
            SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND periode.tahun ="
            .$tahun
            .$conditionSemester."
            AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        ) as `formulir_user` 
        LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama 
        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id 
        AND detil_formulir_hasil_bidang_kinerja_utama.status = '1' GROUP BY formulir_user.id
    ) as `data_nilai`
    ON data_nilai.id = data_bobot.id" ;
   
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_laporan_by_user($user_id){
    $sql = "
            SELECT formulir_hasil_bidang_kinerja_utama.*,
             user.nama_user, unit.nama_unit, unit.tenaga_pengajar,
             periode.tahun, periode.semester,
             (
                 SUM(
                     CASE
                        WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                        THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                        ELSE 0 
                     END 
                 )
             ) as `score` 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode, detil_formulir_hasil_bidang_kinerja_utama 
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND formulir_hasil_bidang_kinerja_utama.user_id =".$user_id." 
            AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id 
            GROUP BY formulir_hasil_bidang_kinerja_utama.id
            ORDER BY periode.tahun, periode.semester ASC";
    $query = $this->db->query($sql);
    return $query->result();
}


public function get_laporan_by_id($formulir_id){
    $sql = "
        SELECT 
            formulir_hasil_bidang_kinerja_utama.*,
            user.nama_user,
            unit.nama_unit,
            unit.tenaga_pengajar,
            periode.tahun,
            periode.semester
        FROM formulir_hasil_bidang_kinerja_utama
        JOIN user, unit, periode
        WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND formulir_hasil_bidang_kinerja_utama.id = $formulir_id
    ";
    $query = $this->db->query($sql);
    return $query->row();
}

public function get_formulir_by_periode_id($periode_id)
{
   $sql = "SELECT *, detil_formulir_hasil_bidang_kinerja_utama.* FROM detil_formulir_hasil_bidang_kinerja_utama 
   JOIN (SELECT * FROM formulir_hasil_bidang_kinerja_utama WHERE periode_id =".$periode_id." GROUP BY formulir_ketua, unit_id) AS unit_of_formulir,
    kpi,indikator,bidang, unit, ketua_unit 
    WHERE unit_of_formulir.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id 
    AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id 
    AND kpi.indikator_id = indikator.id 
    AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = bidang.id 
    AND unit_of_formulir.unit_id = unit.id 
    AND unit.id = ketua_unit.unit_id ORDER BY unit_of_formulir.id ASC, created_at ASC";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_formulir_by_unit($periode_id, $data_unit)
{
    $sql = "SELECT * FROM formulir_hasil_bidang_kinerja_utama WHERE periode_id = ".$periode_id." AND unit_id = ".$data_unit["unit_id"]." AND formulir_ketua = "."'".$data_unit['formulir_ketua']."'";
    $query = $this->db->query($sql);
    return $query->result();
}

public function update_formulir($formulir_id,$data)
{   
    $sql = "UPDATE formulir_hasil_bidang_kinerja_utama SET ";
    $dataSql = [];
    foreach($data as $key => $value){
        $strData = "$key = '$value'";
        array_push($dataSql,$strData);
    }   
    $sql = $sql.join(",", $dataSql);
    $sql = $sql." WHERE id = $formulir_id";
    $this->db->query($sql);
    return $this->db->affected_rows();
    
}

public function create_many_formulir($data_unit, $periode_id)
{
    $data = [];
    if($data_unit["ketua_unit"] == ""){
        foreach($data_unit["anggota_unit"] as $anggota){
            $data[]= array(
                "periode_id" => (int) $periode_id, 
                "unit_id" =>(int) $anggota->unit_id,
                "user_id" => (int) $anggota->id,
                "versi" => 1,
                "revisi" => 1,
                "formulir_ketua" => "0", 
                );
        }
    }
    else{
        $data[]= array(
            "periode_id" => (int) $periode_id,
            "unit_id" =>(int) $data_unit["unit_id"],
            "user_id" => (int) $data_unit["ketua_unit"],
            "versi" => 1,
            "revisi" => 1,
            "formulir_ketua" => "1");
    }
    
    $count = count($data);
    $this->db->insert_batch("formulir_hasil_bidang_kinerja_utama", $data);
    $first_id = $this->db->insert_id();
    $last_id = $first_id + $count-1;
    $sql = "SELECT * FROM formulir_hasil_bidang_kinerja_utama WHERE id >=".$first_id." AND id <=".$last_id;
    $query = $this->db->query($sql);
    return $query->result();
    
}


}

?>