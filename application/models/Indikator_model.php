<?php

defined('BASEPATH') OR exit('No direct script access allowed ');
class Indikator_model extends CI_Model
{
     private $table_name = 'indikator';
     private $table_pk= 'id'; 

public function get ()
{ 
    $sql = "SELECT * FROM indikator ORDER BY id DESC";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_by_id($indikator_id)
{
    $sql = "SELECT * FROM indikator WHERE id = ".$indikator_id; 
    $query = $this->db->query($sql);
    return $query->row();
}

public function add($data)
{
    $this->db->insert($this->table_name, $data);
    return $this->db->insert_id();
}

public function update($id,$data)
{
    $this->db->where("id", $id);
    $this->db->update($this->table_name, $data);
    return $this->db->affected_rows();
}

public function get_pencapaian_indikator_by_unit($unit_id,$is_ketua, $periode_id){
    //user formulir data
    $sql = "
    SELECT 
        poin_formulir_grouped_by_indikator.formulir_id as `id`,
        poin_formulir_grouped_by_indikator.nama_user,
        poin_formulir_grouped_by_indikator.user_id,
        (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`
    FROM 
    (
        SELECT 
            formulir_hasil_bidang_kinerja_utama.id as `formulir_id`,
            user.nama_user,
            formulir_hasil_bidang_kinerja_utama.user_id,
            formulir_hasil_bidang_kinerja_utama.unit_id, 
            formulir_hasil_bidang_kinerja_utama.formulir_ketua, indikator.id as `indikator_id`, 
            indikator.nama_indikator, 
            COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
            SUM( 
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN  (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot 	
                    ELSE 0
                END
                
            ) as `nilai_pencapaian_kpi`
        FROM formulir_hasil_bidang_kinerja_utama
        JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user
        WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
        AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
        AND kpi.indikator_id = indikator.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND unit.institusi_id = institusi.id
        AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id, indikator_id
    ) as `poin_formulir_grouped_by_indikator`
    GROUP BY formulir_id";
    $query = $this->db->query($sql);
    return $query->result();

}

public function get_statistic_pencapaian_indikator_by_unit($unit_id,$is_ketua, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
    SELECT 
       formulir_user.periode_id,
       formulir_user.tahun,
       formulir_user.semester,
       SUM( (formulir_user.nilai_pencapaian_formulir) ) as `nilai_pencapaian`,
       ( count(formulir_user.formulir_id) * 100 )as `MAX_SCORE`
    FROM
    (
        SELECT 
            poin_formulir_grouped_by_indikator.formulir_id,
            poin_formulir_grouped_by_indikator.nama_user,
            poin_formulir_grouped_by_indikator.user_id,
            poin_formulir_grouped_by_indikator.tahun,
            poin_formulir_grouped_by_indikator.periode_id,
            poin_formulir_grouped_by_indikator.semester,
            (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`
        FROM 
        (
            SELECT 
                formulir_hasil_bidang_kinerja_utama.id as `formulir_id`,
                user.nama_user,
                formulir_hasil_bidang_kinerja_utama.user_id,
                formulir_hasil_bidang_kinerja_utama.unit_id, 
                formulir_hasil_bidang_kinerja_utama.formulir_ketua, indikator.id as `indikator_id`, 
                formulir_hasil_bidang_kinerja_utama.periode_id,
                periode.tahun,
                periode.semester,
                indikator.nama_indikator, 
                COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
                SUM( 
                    CASE
                        WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                        THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot 	
                        ELSE 0
                    END
                    
                ) as `nilai_pencapaian_kpi`
            FROM formulir_hasil_bidang_kinerja_utama
            JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user, periode
            WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
            AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
            AND kpi.indikator_id = indikator.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND unit.institusi_id = institusi.id
            AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $strPeriode )
            AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
            AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
            GROUP BY formulir_hasil_bidang_kinerja_utama.id, indikator_id
        ) as `poin_formulir_grouped_by_indikator`
        GROUP BY formulir_id
    ) as `formulir_user`
    GROUP BY formulir_user.periode_id
   ";

    $query = $this->db->query($sql);
    return $query->result();
}

public function get_detil_pencapaian_indikator_unit_by_formulir($array_formulir_id){
    $strFormulirId = join(",", $array_formulir_id);
    $sql = " 
            SELECT
                poin_akumulatif_seluruh_formulir.indikator_id,
                poin_akumulatif_seluruh_formulir.nama_indikator,
                ((poin_akumulatif_seluruh_formulir.nilai_pencapaian_formulir / poin_akumulatif_seluruh_formulir.jumlah_kpi_formulir) * 100) as `persen_ketercapaian`,
                poin_akumulatif_seluruh_formulir.jumlah_kpi_formulir as `MAX_SCORE`,
                poin_akumulatif_seluruh_formulir.nilai_pencapaian_formulir as `total_nilai_aktual`,
                poin_akumulatif_seluruh_formulir.jumlah_kpi_formulir as `jumlah_user`,
                poin_akumulatif_seluruh_formulir.jumlah_kpi,
                poin_akumulatif_seluruh_formulir.jumlah_user
            FROM 
            (   SELECT
                    poin_formulir_grouped_by_indikator.indikator_id,
                    poin_formulir_grouped_by_indikator.nama_indikator,
                    poin_formulir_grouped_by_indikator.jumlah_kpi,
                    ( SUM(poin_formulir_grouped_by_indikator.jumlah_kpi)) as `jumlah_kpi_formulir`,
                    (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`,
                    COUNT(poin_formulir_grouped_by_indikator.indikator_id) as `jumlah_user`
                FROM
                    (SELECT 
                        indikator.id as `indikator_id`,
                        indikator.nama_indikator, 
                        COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
                        SUM( 
                            CASE
                                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                                THEN  detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi 	
                                ELSE 0
                            END
                            
                        ) as `nilai_pencapaian_kpi`
                    FROM formulir_hasil_bidang_kinerja_utama
                    JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user, periode
                    WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
                    AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
                    AND kpi.indikator_id = indikator.id
                    AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
                    AND unit.institusi_id = institusi.id
                    AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
                    AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
                    AND formulir_hasil_bidang_kinerja_utama.id IN ($strFormulirId )
                    GROUP BY formulir_hasil_bidang_kinerja_utama.id, indikator_id 
                ) as `poin_formulir_grouped_by_indikator`
                GROUP BY poin_formulir_grouped_by_indikator.indikator_id 
            ) as `poin_akumulatif_seluruh_formulir`
            ";
    $query = $this->db->query($sql);
    return $query->result();      
}

public function get_pencapaian_indikator_by_unit_and_user($user_id,$unit_id,$is_ketua, $periode_id){
    //user formulir data
    $sql = "
    SELECT 
        poin_formulir_grouped_by_indikator.formulir_id,
        poin_formulir_grouped_by_indikator.nama_user,
        poin_formulir_grouped_by_indikator.user_id,
        (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`
    FROM 
    (
        SELECT 
            formulir_hasil_bidang_kinerja_utama.id as `formulir_id`,
            user.nama_user,
            formulir_hasil_bidang_kinerja_utama.user_id,
            formulir_hasil_bidang_kinerja_utama.unit_id, 
            formulir_hasil_bidang_kinerja_utama.formulir_ketua,
            indikator.id as `indikator_id`, 
            indikator.nama_indikator, 
            COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
            SUM( 
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN  (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) *  detil_formulir_hasil_bidang_kinerja_utama.bobot
                    ELSE 0
                END
                
            ) as `nilai_pencapaian_kpi`
        FROM formulir_hasil_bidang_kinerja_utama
        JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user
        WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
        AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
        AND kpi.indikator_id = indikator.id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
        AND unit.institusi_id = institusi.id
        AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        AND formulir_hasil_bidang_kinerja_utama.user_id = $user_id
        GROUP BY indikator_id
    ) as `poin_formulir_grouped_by_indikator`
    GROUP BY formulir_id";
    $query = $this->db->query($sql);
    return $query->row();

}

public function get_statistic_pencapaian_indikator_by_unit_and_user($user_id, $unit_id,$is_ketua, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
    SELECT 
       formulir_user.periode_id,
       formulir_user.tahun,
       formulir_user.semester,
       SUM( (formulir_user.nilai_pencapaian_formulir) ) as `nilai_pencapaian`,
       ( count(formulir_user.formulir_id) * 100 )as `MAX_SCORE`
    FROM
    (
        SELECT 
            poin_formulir_grouped_by_indikator.formulir_id,
            poin_formulir_grouped_by_indikator.nama_user,
            poin_formulir_grouped_by_indikator.user_id,
            poin_formulir_grouped_by_indikator.tahun,
            poin_formulir_grouped_by_indikator.periode_id,
            poin_formulir_grouped_by_indikator.semester,
            (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`
        FROM 
        (
            SELECT 
                formulir_hasil_bidang_kinerja_utama.id as `formulir_id`,
                user.nama_user,
                formulir_hasil_bidang_kinerja_utama.user_id,
                formulir_hasil_bidang_kinerja_utama.unit_id, 
                formulir_hasil_bidang_kinerja_utama.formulir_ketua, indikator.id as `indikator_id`, 
                formulir_hasil_bidang_kinerja_utama.periode_id,
                periode.tahun,
                periode.semester,
                indikator.nama_indikator, 
                COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
                SUM( 
                    CASE
                        WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                        THEN  (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot	
                        ELSE 0
                    END
                    
                ) as `nilai_pencapaian_kpi`
            FROM formulir_hasil_bidang_kinerja_utama
            JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user, periode
            WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
            AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
            AND kpi.indikator_id = indikator.id
            AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
            AND unit.institusi_id = institusi.id
            AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
            AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $strPeriode )
            AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
            AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
            AND formulir_hasil_bidang_kinerja_utama.user_id = '$user_id'
            GROUP BY formulir_hasil_bidang_kinerja_utama.id, indikator_id
        ) as `poin_formulir_grouped_by_indikator`
        GROUP BY formulir_id
    ) as `formulir_user`
    GROUP BY formulir_user.periode_id
   ";

    $query = $this->db->query($sql);
    return $query->result();
}

public function get_detil_pencapaian_indikator_by_unit_and_user($formulir_id){
    $sql = " 
            SELECT
                poin_akumulatif_seluruh_formulir.indikator_id,
                poin_akumulatif_seluruh_formulir.nama_indikator,
                poin_akumulatif_seluruh_formulir.nilai_pencapaian_formulir,
                ((poin_akumulatif_seluruh_formulir.nilai_pencapaian_formulir / poin_akumulatif_seluruh_formulir.jumlah_kpi_formulir) * 100) as `persen_ketercapaian`,
                poin_akumulatif_seluruh_formulir.jumlah_kpi_formulir as `jumlah_kpi`
            FROM 
            (   SELECT
                    poin_formulir_grouped_by_indikator.indikator_id,
                    poin_formulir_grouped_by_indikator.nama_indikator,
                    ( SUM(poin_formulir_grouped_by_indikator.jumlah_kpi)) as `jumlah_kpi_formulir`,
                    (SUM(poin_formulir_grouped_by_indikator.nilai_pencapaian_kpi) ) as `nilai_pencapaian_formulir`
                FROM
                    (SELECT 
                        indikator.id as `indikator_id`,
                        indikator.nama_indikator, 
                        COUNT(detil_formulir_hasil_bidang_kinerja_utama.id) as `jumlah_kpi`, 
                        SUM( 
                            CASE
                                WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                                THEN  detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi 	
                                ELSE 0
                            END
                            
                        ) as `nilai_pencapaian_kpi`
                    FROM formulir_hasil_bidang_kinerja_utama
                    JOIN detil_formulir_hasil_bidang_kinerja_utama, kpi, indikator, unit, institusi, user, periode
                    WHERE formulir_hasil_bidang_kinerja_utama.id = detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id
                    AND detil_formulir_hasil_bidang_kinerja_utama.kpi_id = kpi.id
                    AND kpi.indikator_id = indikator.id
                    AND formulir_hasil_bidang_kinerja_utama.unit_id = unit.id
                    AND unit.institusi_id = institusi.id
                    AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
                    AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
                    AND formulir_hasil_bidang_kinerja_utama.id = $formulir_id 
                    GROUP BY formulir_hasil_bidang_kinerja_utama.id, indikator_id 
                ) as `poin_formulir_grouped_by_indikator`
                GROUP BY poin_formulir_grouped_by_indikator.indikator_id 
            ) as `poin_akumulatif_seluruh_formulir`
            ";
    $query = $this->db->query($sql);
    return $query->result();      
}


}

?>