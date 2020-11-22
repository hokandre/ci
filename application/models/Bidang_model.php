<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Bidang_model extends CI_Model
{
    private $table_name = 'bidang';


public function get_by_id($id){
    $this->db->where("id", $id);
    $query = $this->db->get($this->table_name);
    return $query->row();
}

public function get()
{ 
   $sql = "SELECT * FROM bidang";
   $result = $this->db->query($sql);
   return $result->result();
}

public function get_last_alphabet_id()
{
    $sql = "SELECT MAX(id) FROM bidang LIMIT 1";
    $query = $this->db->query($sql);
    $result = $query->row();
    
    if(isset($result)){
        return $result->id;
    }else{
        return NULL;
    }
}

public function add($nama_bidang)
{
    $sql = "INSERT INTO bidang (nama_bidang) VALUES (?)";
    $this->db->query($sql,[$nama_bidang]);
    return $this->db->insert_id();
}

public function update($id, $nama_bidang)
{
    $sql = "UPDATE bidang SET nama_bidang = ? WHERE id = ?";
    $this->db->query($sql, [$nama_bidang, $id]);
    return $this->db->affected_rows();
}

public function test($bidang_id,$institusi_id, $periode_id){
    $jumlah_kpi = "COUNT(detil_formulir_hasil_bidang_kinerja_utama.kpi_id)";
    $pencapaian_user = "SUM(
        CASE
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
            ELSE 0  
        END 
    )"; 
    $sql = " 
        SELECT 
            CASE
                WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                THEN CONCAT('Dosen ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Ketua ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Ketua ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Anggota ', unit.nama_unit)
            END as nama_unit,
            unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            $jumlah_kpi as `jumlah_kpi`,
            $pencapaian_user as `nilai_pencapaian`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND unit.institusi_id = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id = ?
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id";
    $query = $this->db->query($sql,[$institusi_id, $periode_id, $bidang_id]);
    return $query->result();
}

public function get_formulir_unit_anggota_institusi($bidang_id,$institusi_id, $periode_id){
    $jumlah_kpi = "COUNT(detil_formulir_hasil_bidang_kinerja_utama.kpi_id)";
    $pencapaian_user = "SUM(
        CASE
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
            ELSE 0  
        END 
    )";

    $jum_form_angg_unit = "(COUNT(formulir_user.id))";
    $jum_kpi = "COUNT(formulir_user.kpi_id)";
    $nilai_maksimal = "$jum_form_angg_unit * 100";
    $nilai_tercapai = "SUM(formulir_user.nilai_pencapaian)";
    $nilai_tidak_tercapai = "($nilai_maksimal)-($nilai_tercapai)";
    $persen_tercapai = "($nilai_tercapai) / ($nilai_maksimal) *100";
    $persen_tidak_tercapai = "100 - ($persen_tercapai)"; 
    $sql = " 
    SELECT 
        formulir_user.nama_unit,
        formulir_user.unit_id,
        formulir_user.formulir_ketua,
        $jum_form_angg_unit as `jumlah_user`,
        formulir_user.jumlah_kpi as `jumlah_kpi`,
        $nilai_maksimal as `nilai_maksimal`,
        $nilai_tercapai as `nilai_tercapai`,
        $nilai_tidak_tercapai as `nilai_tidak_tercapai`,
        $persen_tercapai as `persen_tercapai`,
        $persen_tidak_tercapai as `persen_tidak_tercapai`
    FROM
    (   SELECT 
            CASE
                WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                THEN CONCAT('Dosen ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Ketua ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Ketua ', unit.nama_unit)
                WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                THEN CONCAT('Anggota ', unit.nama_unit)
            END as nama_unit,
            unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            $jumlah_kpi as `jumlah_kpi`,
            $pencapaian_user as `nilai_pencapaian`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND unit.institusi_id = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id = ?
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id
    ) as `formulir_user`
    GROUP BY formulir_user.unit_id, formulir_user.formulir_ketua ";
    $query = $this->db->query($sql,[$institusi_id, $periode_id, $bidang_id]);
    return $query->result();
}

public function get_statistic_pencapaian_bidang_by_institusi_and_periode($bidang_id, $institusi_id, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
        SELECT 
            all_formulir_data.periode_id,
            all_formulir_data.tahun,
            all_formulir_data.semester,
            COUNT(all_formulir_data.id) * 100 as `MAX_SCORE`,
            SUM( (all_formulir_data.score / all_formulir_data.MAX_SCORE) * 100) as `nilai_ketercapaian_institusi`,
            SUM(all_formulir_data.score) as `score`  
        FROM
        (SELECT 
            data_formulir.*,
            (COUNT(data_formulir.id) * 100) as `MAX_SCORE`,
            ( SUM(data_formulir.nilai_pencapaian) ) as `score`
        FROM
        (SELECT 
            CASE
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                    THEN CONCAT('Dosen ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Anggota ', unit.nama_unit)
                END as nama_unit, unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            SUM(
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                    ELSE 0  
                END 
            ) as `nilai_pencapaian`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND unit.institusi_id = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN (?)
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.unit_id, data_formulir.formulir_ketua, data_formulir.periode_id) as `all_formulir_data`
        GROUP BY all_formulir_data.periode_id";

    $query = $this->db->query($sql, [$institusi_id, $strPeriode, $bidang_id]);
    return $query->result();
}

public function get_formulir_user_anggota_unit($bidang_id, $unit_id, $is_ketua, $periode_id){
    $nilai_tercapai = "SUM(
        CASE
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
            ELSE 0  
        END 
    )";

    $sql = "
        SELECT 
            user.nama_user,
            CASE
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                    THEN CONCAT('Dosen ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Anggota ', unit.nama_unit)
                END as nama_unit, unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.user_id,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            $nilai_tercapai as `nilai_pencapaian`,
            100 as `nilai_maksimal`,
            100 - $nilai_tercapai as `nilai_tidak_tercapai`,
            $nilai_tercapai as `persen_tercapai`,
            100 - $nilai_tercapai as `persen_tidak_tercapai`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama, user
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND unit.id = ?
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id = ?
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id ";
    $query = $this->db->query($sql, [$unit_id, $is_ketua, $periode_id, $bidang_id]);
    return $query->result();
}

public function get_detil_pencapaian_bidang_by_unit_and_formulir($bidang_id, $array_formulir_id){
   $table_detil =  
   "detil_formulir_hasil_bidang_kinerja_utama";
   $jumlah_formulir = "COUNT($table_detil.id)";
   $nilai_maksimal = "($jumlah_formulir) * $table_detil.target_institusi";
   $nilai_tercapai = "SUM($table_detil.nilai_aktual)";
   $nilai_tidak_tercapai = "$nilai_maksimal - $nilai_tercapai";
   $persen_tercapai = "(($nilai_tercapai) / ($nilai_maksimal)) * 100";
   $persen_tidak_tercapai = "(($nilai_tidak_tercapai) / ($nilai_maksimal)) * 100";

    $sql = "
            SELECT 
                $jumlah_formulir as  `jumlah_formulir`,
                $nilai_maksimal as `nilai_maksimal`,
                $nilai_tercapai as `nilai_tercapai`,
                $nilai_tidak_tercapai as `nilai_tidak_tercapai`,
                $persen_tercapai as `persen_tercapai`,
                $persen_tidak_tercapai as `persen_tidak_tercapai`,
                kpi.nama_kpi as `nama_kpi`,
                kpi.id as `kpi_id`
            FROM detil_formulir_hasil_bidang_kinerja_utama
            JOIN kpi
            WHERE kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id
            AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id IN ? 
            AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
            GROUP BY detil_formulir_hasil_bidang_kinerja_utama.kpi_id";
    $query = $this->db->query($sql,[$array_formulir_id, $bidang_id]);
    return $query->result();
}

public function get_statistic_pencapaian_bidang_by_unit_and_periode($bidang_id, $unit_id, $is_ketua, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
        SELECT 
            all_formulir_data.periode_id,
            all_formulir_data.tahun,
            all_formulir_data.semester,
            all_formulir_data.MAX_SCORE,
            SUM( (all_formulir_data.score / all_formulir_data.MAX_SCORE) * 100) as `nilai_ketercapaian_institusi`,
            SUM( all_formulir_data.score) as `score`  
        FROM
        (SELECT 
            data_formulir.*,
            (COUNT(data_formulir.id) * 100) as `MAX_SCORE`,
            ( SUM(data_formulir.nilai_pencapaian) ) as `score`
        FROM
        (SELECT 
            CASE
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                    THEN CONCAT('Dosen ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Anggota ', unit.nama_unit)
            END as nama_unit, unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            SUM(
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                    ELSE 0  
                END 
            ) as `nilai_pencapaian`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND unit.id = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN (?)
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = ?
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.unit_id, data_formulir.formulir_ketua, data_formulir.periode_id) as `all_formulir_data`
        GROUP BY all_formulir_data.periode_id";

    $query = $this->db->query($sql,[$unit_id, $strPeriode, $is_ketua, $bidang_id]);
    return $query->result();
}

// public function get_pencapaian_bidang_by_user_and_periode($bidang_id, $user_id, $unit_id, $is_ketua, $periode_id){
//         $sql = "
//          SELECT 
//             CASE
//                     WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
//                     THEN CONCAT('Dosen ', unit.nama_unit)
//                     WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
//                     THEN CONCAT('Ketua ', unit.nama_unit)
//                     WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
//                     THEN CONCAT('Ketua ', unit.nama_unit)
//                     WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
//                     THEN CONCAT('Anggota ', unit.nama_unit)
//                 END as nama_unit, unit.institusi_id, 
//             unit.tenaga_pengajar,
//             periode.tahun, periode.semester,
//             formulir_hasil_bidang_kinerja_utama.id,
//             formulir_hasil_bidang_kinerja_utama.unit_id,
//             formulir_hasil_bidang_kinerja_utama.periode_id,
//             formulir_hasil_bidang_kinerja_utama.versi,
//             formulir_hasil_bidang_kinerja_utama.revisi,
//             formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
//             formulir_hasil_bidang_kinerja_utama.created_at,
//             formulir_hasil_bidang_kinerja_utama.formulir_ketua,
//             SUM(
//                 CASE
//                     WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
//                     THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
//                     ELSE 0  
//                 END 
//             ) as `nilai_pencapaian`
//             FROM unit 
//         JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
//         WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
//         AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
//         AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
//         AND formulir_hasil_bidang_kinerja_utama.user_id = ?
//         AND formulir_hasil_bidang_kinerja_utama.unit_id = ?
//         AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = ?
//         AND formulir_hasil_bidang_kinerja_utama.periode_id = ?
//         AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
//         GROUP BY formulir_hasil_bidang_kinerja_utama.id";

//     $query = $this->db->query($sql, [$user_id, $unit_id, $is_ketua, $periode_id, $bidang_id]);
//     return $query->row();
// }

public function get_detil_pencapaian_bidang_by_user_formulir($user_id, $unit_id, $formulir_ketua, $periode_id){
    $persen_tercapai = "CEIL( (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * 100 )";

    $sql = "
    SELECT 
        CASE
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
            ELSE 0  
        END as `nilai_tercapai`,
        CASE
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN 
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.target_institusi - detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual > 0
                    THEN 
                    ( (detil_formulir_hasil_bidang_kinerja_utama.target_institusi / detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual) / detil_formulir_hasil_bidang_kinerja_utama.target_institusi ) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                    ELSE 0
                END
            ELSE 0  
        END as `nilai_tidak_tercapai`,
        detil_formulir_hasil_bidang_kinerja_utama.target_institusi * detil_formulir_hasil_bidang_kinerja_utama.bobot as `nilai_maksimal`,
        ( $persen_tercapai ) as `persen_ketercapaian`,
        (100- ($persen_tercapai) )as `persen_tidak_tercapai`,
        detil_formulir_hasil_bidang_kinerja_utama.id,
        detil_formulir_hasil_bidang_kinerja_utama.kpi_id,
        detil_formulir_hasil_bidang_kinerja_utama.bukti,
        detil_formulir_hasil_bidang_kinerja_utama.target_institusi,
        detil_formulir_hasil_bidang_kinerja_utama.target_individu,
        detil_formulir_hasil_bidang_kinerja_utama.sumber,
        detil_formulir_hasil_bidang_kinerja_utama.status,
        detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual,
        detil_formulir_hasil_bidang_kinerja_utama.bidang_id,
        detil_formulir_hasil_bidang_kinerja_utama.satuan,
        kpi.nama_kpi
    FROM formulir_hasil_bidang_kinerja_utama
    JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi
    WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
    AND kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id
    AND formulir_hasil_bidang_kinerja_utama.user_id = ?
    AND formulir_hasil_bidang_kinerja_utama.unit_id = ?
    AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = ?
    AND formulir_hasil_bidang_kinerja_utama.periode_id = ?
    AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?";
    $query = $this->db->query($sql,[$user_id, $unit_id, $formulir_ketua, $periode_id, $bidang_id]);
    return $query->result();
}

public function get_statistic_pencapaian_bidang_by_user_and_periode($bidang_id, $user_id, $unit_id, $is_ketua, $array_periode){
    $sql  = "
            SELECT 
                data_formulir.periode_id,
                data_formulir.tahun,
                data_formulir.semester,
                (data_formulir.nilai_pencapaian) as `score`
            FROM 
            (SELECT 
            CASE
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '0'
                    THEN CONCAT('Dosen ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '1' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Ketua ', unit.nama_unit)
                    WHEN unit.tenaga_pengajar = '0' AND  formulir_hasil_bidang_kinerja_utama.formulir_ketua = '1'
                    THEN CONCAT('Anggota ', unit.nama_unit)
                END as nama_unit, unit.institusi_id, unit.tenaga_pengajar,
            periode.tahun, periode.semester,
            formulir_hasil_bidang_kinerja_utama.id,
            formulir_hasil_bidang_kinerja_utama.unit_id,
            formulir_hasil_bidang_kinerja_utama.periode_id,
            formulir_hasil_bidang_kinerja_utama.versi,
            formulir_hasil_bidang_kinerja_utama.revisi,
            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
            formulir_hasil_bidang_kinerja_utama.created_at,
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            SUM(
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                    ELSE 0  
                END 
            ) as `nilai_pencapaian`
            FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND formulir_hasil_bidang_kinerja_utama.user_id = ?
        AND formulir_hasil_bidang_kinerja_utama.unit_id = ?
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = ?
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN ?
        AND detil_formulir_hasil_bidang_kinerja_utama.bidang_id = ?
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.periode_id, data_formulir.semester
    ";

    $query = $this->db->query($sql,[$user_id, $unit_id, $is_ketua, $array_periode, $bidang_id]);
    return $query->result();
}

}

?>