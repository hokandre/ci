<?php


defined('BASEPATH') OR exit('No direct script access allowed ');

class Dashboard_model extends CI_Model
{
    

public function get_kinerja_user_by_periode_id($user_id, $periode_id, $isKetua, $unit_id){
    $sql = "
    SELECT formulir_user.*, 
    (SUM(
        CASE 
            WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
            THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
            ELSE 0
        END )
        ) as `score`,
        100 as `MAX_SCORE`
    FROM (
        SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
        FROM formulir_hasil_bidang_kinerja_utama 
        JOIN user,unit,periode
        WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND periode.id =".$periode_id."
        AND formulir_hasil_bidang_kinerja_utama.user_id = ".$user_id."
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua='".$isKetua."'
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        ) as `formulir_user` 
    JOIN detil_formulir_hasil_bidang_kinerja_utama 
    ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id 
    GROUP BY formulir_user.id
    ";

    $query = $this->db->query($sql);
    return $query->row();
}
//KPI
public function get_detil_kinerja_user_by_formulir_id($formulir_id){
    $sql = "SELECT 
                (CASE
                     WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                     THEN CEIL( (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * 100 )
                     ELSE 0  
                 END ) as `persen_ketercapaian`,
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
            FROM detil_formulir_hasil_bidang_kinerja_utama
            JOIN kpi
            WHERE kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id
            AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = ".$formulir_id;
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_statistic_kinerja_user($user_id, $array_periode_id, $isKetua, $unit_id){
    $stringPeriodeId = join(",", $array_periode_id);
    $sql = "
        SELECT 
        formulir_user.*, 
        (SUM(
            CASE 
                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                ELSE 0
            END )
        ) as `score`,
        100 as `MAX_SCORE`
        FROM (
            SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND periode.id IN (".$stringPeriodeId.")
            AND formulir_hasil_bidang_kinerja_utama.user_id = ".$user_id."
            AND formulir_hasil_bidang_kinerja_utama.formulir_ketua='".$isKetua."'
            AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id) as `formulir_user` 
        LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama 
        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_user.id 
        AND detil_formulir_hasil_bidang_kinerja_utama.status = '1' GROUP BY formulir_user.id
    ";
    $query = $this->db->query($sql);
    return $query->result();
}



public function get_kinerja_unit_by_periode_id($unit_id, $periode_id, $isKetua){
    $sql = "
        SELECT formulir_unit.*, 
        (SUM(
            CASE 
                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                ELSE 0
            END )
        ) as `score`
        FROM (
            SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND periode.id =".$periode_id."
            AND formulir_hasil_bidang_kinerja_utama.unit_id = ".$unit_id."
            AND formulir_hasil_bidang_kinerja_utama.formulir_ketua='".$isKetua."') as `formulir_unit` 
        LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama 
        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_unit.id 
       GROUP BY formulir_unit.id" ;

    $query = $this->db->query($sql);
    return $query->result();
}
//kpi
public function get_detil_kinerja_unit_by_formulir_id($formulir_id){
    $array_id_str = join(",", $formulir_id);

    $sql = "SELECT
        detil_kpi.kpi_id, detil_kpi.nama_kpi, 
        COUNT(detil_kpi.id) * 100 as `MAX_SCORE`, 
        SUM(detil_kpi.persen_ketercapaian) as `nilai_pencapaian`,
        SUM(detil_kpi.nilai_aktual) as `total_nilai_aktual`,
        COUNT(detil_kpi.id) as `jumlah_user`,
        detil_kpi.target_institusi,
        detil_kpi.target_individu
        FROM 
        (
            SELECT 
            ( CEIL( (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * 100 ) ) as `persen_ketercapaian`,
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
            detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id,
            kpi.nama_kpi
        FROM detil_formulir_hasil_bidang_kinerja_utama
        JOIN kpi
        WHERE kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id IN (".$array_id_str.") 
        ) as `detil_kpi`
        GROUP BY detil_kpi.kpi_id";
    //ChromePhpModel::log($sql);
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_statistic_kinerja_unit($unit_id, $array_periode_id, $isKetua){
    $stringPeriodeId = join(",", $array_periode_id);

    $sql = "
    SELECT data_statistik_kinerja.*,(COUNT(data_statistik_kinerja.id) * 100) as `MAX_SCORE`, SUM(score_user) as `score` FROM
    (
        SELECT formulir_unit.*, 
        (SUM(
            CASE 
                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                ELSE 0
            END )
            ) as `score_user`
        FROM (
            SELECT formulir_hasil_bidang_kinerja_utama.*, user.nama_user, unit.nama_unit, unit.tenaga_pengajar, periode.tahun, periode.semester 
            FROM formulir_hasil_bidang_kinerja_utama 
            JOIN user,unit,periode
            WHERE formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND periode.id IN (".$stringPeriodeId." )
            AND formulir_hasil_bidang_kinerja_utama.unit_id = ".$unit_id."
            AND formulir_hasil_bidang_kinerja_utama.formulir_ketua='".$isKetua."') as `formulir_unit` 
        LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama 
        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_unit.id 
        AND detil_formulir_hasil_bidang_kinerja_utama.status = '1' GROUP BY formulir_unit.id
    ) as `data_statistik_kinerja` GROUP BY data_statistik_kinerja.periode_id" ;
    $query = $this->db->query($sql);
    return $query->result();
}


public function get_kinerja_institusi($institusi_id, $periode_id){
    $sql = "SELECT 
                data_nilai.*, 
                (COUNT(data_nilai.id) * 100) as `MAX_SCORE`,
                (SUM(data_nilai.nilai)) as `score`
            FROM
            (
                SELECT 
                    data_formulir.*,
                    (COALESCE(
                        SUM(
                            CASE 
                                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                                THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                                ELSE 0
                            END 
                        ), 0)
                    ) as `nilai`
                    FROM 
                    (
                        SELECT 
                            formulir_hasil_bidang_kinerja_utama.id, 
                            formulir_hasil_bidang_kinerja_utama.periode_id,
                            formulir_hasil_bidang_kinerja_utama.unit_id,
                            formulir_hasil_bidang_kinerja_utama.user_id,
                            formulir_hasil_bidang_kinerja_utama.versi,
                            formulir_hasil_bidang_kinerja_utama.revisi,
                            formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
                            formulir_hasil_bidang_kinerja_utama.created_at,
                            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
                            data_unit.nama_unit,
                            data_unit.tenaga_pengajar,
                            data_unit.institusi_id
                            
                        FROM
                            (
                                SELECT 
                                    (SELECT COUNT(unit.id) as `jumlah_unit` FROM unit WHERE unit.institusi_id = $institusi_id ) as `jumlah_unit`,
                                    unit.id, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id
                                FROM unit 
                                WHERE unit.institusi_id = $institusi_id
                            ) as `data_unit`
                        JOIN  formulir_hasil_bidang_kinerja_utama
                        ON formulir_hasil_bidang_kinerja_utama.unit_id = data_unit.id
                        AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id) as `data_formulir`
                        JOIN detil_formulir_hasil_bidang_kinerja_utama
                        ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = data_formulir.id
                        GROUP BY data_formulir.id
            ) as `data_nilai`
            GROUP BY data_nilai.unit_id,data_nilai.formulir_ketua";
        $query = $this->db->query($sql);
        return $query->result();
}

public function get_statistic_kinerja_institusi($institusi_id, $array_periode_id){
    $stringPeriode = join(",", $array_periode_id);
    $sql = "
            SELECT 
                all_formulir_data.periode_id,
                all_formulir_data.tahun,
                all_formulir_data.semester,
                (COUNT(all_formulir_data.id) * 100 ) as `MAX_SCORE`,
                SUM( (all_formulir_data.score / all_formulir_data.MAX_SCORE) * 100) as `nilai_ketercapaian_institusi`  
                FROM
                (SELECT 
                    data_nilai.*, 
                    (COUNT(data_nilai.id) * 100) as `MAX_SCORE`,
                    (SUM(data_nilai.nilai)) as `score`
                    FROM
                    (
                        SELECT 
                            data_formulir.*,
                            (COALESCE(
                                SUM(
                                    CASE 
                                        WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                                        THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot
                                        ELSE 0
                                    END 
                                ), 0)
                            ) as `nilai`
                            FROM 
                            (
                                SELECT 
                                    formulir_hasil_bidang_kinerja_utama.id, 
                                    formulir_hasil_bidang_kinerja_utama.periode_id,
                                    formulir_hasil_bidang_kinerja_utama.unit_id,
                                    formulir_hasil_bidang_kinerja_utama.user_id,
                                    formulir_hasil_bidang_kinerja_utama.versi,
                                    formulir_hasil_bidang_kinerja_utama.revisi,
                                    formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
                                    formulir_hasil_bidang_kinerja_utama.created_at,
                                    formulir_hasil_bidang_kinerja_utama.formulir_ketua,
                                    data_unit.nama_unit,
                                    data_unit.tenaga_pengajar,
                                    data_unit.institusi_id,
                                    periode.tahun,
                                    periode.semester
                                FROM
                                    (
                                        SELECT 
                                            (SELECT COUNT(unit.id) as `jumlah_unit` FROM unit WHERE unit.institusi_id = $institusi_id ) as `jumlah_unit`,
                                            unit.id, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id
                                        FROM unit 
                                        WHERE unit.institusi_id = $institusi_id
                                    ) as `data_unit`
                                JOIN  formulir_hasil_bidang_kinerja_utama, periode
                                WHERE formulir_hasil_bidang_kinerja_utama.unit_id = data_unit.id
                                AND periode.id = formulir_hasil_bidang_kinerja_utama.periode_id
                                AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $stringPeriode) ) as `data_formulir`
                                JOIN detil_formulir_hasil_bidang_kinerja_utama
                                ON detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = data_formulir.id
                                GROUP BY data_formulir.id
                    ) as `data_nilai`
                    GROUP BY data_nilai.unit_id,data_nilai.formulir_ketua,data_nilai.periode_id
                ) as `all_formulir_data`
                GROUP BY all_formulir_data.periode_id";
    $query = $this->db->query($sql);
    return $query->result();    
}


public function test1($institusi_id, $periode_id){
  
    $sql1 = " SELECT 
                  data_formulir.*
                FROM
                (
                    SELECT 
                        formulir_hasil_bidang_kinerja_utama.id as `formulir_id`, 
                        formulir_hasil_bidang_kinerja_utama.periode_id,
                        formulir_hasil_bidang_kinerja_utama.user_id,
                        formulir_hasil_bidang_kinerja_utama.versi,
                        formulir_hasil_bidang_kinerja_utama.revisi,
                        formulir_hasil_bidang_kinerja_utama.tanggal_berlaku,
                        formulir_hasil_bidang_kinerja_utama.created_at,
                        formulir_hasil_bidang_kinerja_utama.formulir_ketua,
                        data_unit.unit_id,
                        data_unit.nama_unit,
                        data_unit.tenaga_pengajar,
                        data_unit.institusi_id
                        
                    FROM
                        (
                            SELECT 
                                (SELECT COUNT(unit.id) as `jumlah_unit` FROM unit WHERE unit.institusi_id = $institusi_id ) as `jumlah_unit`,
                                (unit.id) as `unit_id`, unit.nama_unit, unit.tenaga_pengajar, unit.institusi_id
                            FROM unit 
                            WHERE unit.institusi_id = $institusi_id
                        ) as `data_unit`
                    LEFT JOIN  formulir_hasil_bidang_kinerja_utama
                    ON formulir_hasil_bidang_kinerja_utama.unit_id = data_unit.unit_id
                    AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
                ) as `data_formulir`
                LEFT JOIN detil_formulir_hasil_bidang_kinerja_utama
                ON data_formulir.formulir_id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
                GROUP BY data_formulir.unit_id,data_formulir.formulir_ketua";
        $query = $this->db->query($sql1);
        return $query->result();
}

}

?>