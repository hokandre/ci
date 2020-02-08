<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Kpi_model extends CI_Model
{
     private $table_name = 'kpi';
     private $table_pk= 'id'; 

public function index ()
{ 

     //code here
}

public function get_all(){
     $this->db->from($this->table_name);
     $this->db->join("indikator", "indikator.id = kpi.indikator_id");
     $this->db->select(
          "kpi.id, kpi.nama_kpi, kpi.indikator_id,
          indikator.nama_indikator"
     );
     $query = $this->db->get();
     return $query->result();
}

public function get_by_name ($indikator, $name)
{ 
    $sql = "SELECT * FROM ".$this->table_name." WHERE indikator_id=".$indikator." AND nama_kpi LIKE "."'%".(string)$name."%'";
    $query = $this->db->query($sql);
    return $query->result();
}

public function create($data)
{
     $this->db->insert("kpi", $data);
     return $this->db->insert_id();
}

public function update($kpi_id, $data){
     $this->db->set($data);
     $this->db->where("id", $kpi_id);
     $this->db->update($this->table_name);
     return $this->db->affected_rows();
}

public function get_pencapaian_sumber_kpi_by_institusi_and_periode($sumber,$institusi_id, $periode_id){
     $sql = "
         SELECT 
             data_formulir.*,
             (COUNT(data_formulir.id) * 100) as `MAX_SCORE`,
             ( SUM(data_formulir.nilai_pencapaian) ) as `score`
         FROM
         (SELECT 
             unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
         AND unit.institusi_id = $institusi_id
         AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
         AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
         GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
         GROUP BY data_formulir.unit_id, data_formulir.formulir_ketua";
     
     $query = $this->db->query($sql);     
     return $query->result();
 
 }
 
public function get_statistic_pencapaian_sumber_kpi_by_institusi_and_periode($sumber, $institusi_id, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
        SELECT 
            all_formulir_data.periode_id,
            all_formulir_data.tahun,
            all_formulir_data.semester,
            (COUNT(all_formulir_data.id) * 100 ) as `MAX_SCORE`,
            SUM( (all_formulir_data.score / all_formulir_data.MAX_SCORE) * 100) as `nilai_ketercapaian_institusi`  
        FROM
        (SELECT 
            data_formulir.*,
            (COUNT(data_formulir.id) * 100) as `MAX_SCORE`,
            ( SUM(data_formulir.nilai_pencapaian) ) as `score`
        FROM
        (SELECT 
            unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
        AND unit.institusi_id = $institusi_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $strPeriode )
        AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.unit_id, data_formulir.formulir_ketua, data_formulir.periode_id) as `all_formulir_data`
        GROUP BY all_formulir_data.periode_id";

    $query = $this->db->query($sql);
    return $query->result();
}
 
public function get_pencapaian_sumber_kpi_by_unit_and_periode($sumber, $unit_id, $is_ketua, $periode_id){
    $sql = "
        SELECT 
            user.nama_user,
            unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
            SUM(
                CASE
                    WHEN detil_formulir_hasil_bidang_kinerja_utama.status = '1'
                    THEN (detil_formulir_hasil_bidang_kinerja_utama.nilai_aktual / detil_formulir_hasil_bidang_kinerja_utama.target_institusi) * detil_formulir_hasil_bidang_kinerja_utama.bobot 
                    ELSE 0  
                END 
            ) as `nilai_pencapaian`
        FROM unit 
        JOIN periode, formulir_hasil_bidang_kinerja_utama, detil_formulir_hasil_bidang_kinerja_utama, user
        WHERE unit.id = formulir_hasil_bidang_kinerja_utama.unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id = periode.id
        AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = formulir_hasil_bidang_kinerja_utama.id
        AND formulir_hasil_bidang_kinerja_utama.user_id = user.id
        AND unit.id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
        AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id ";
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_detil_pencapaian_sumber_kpi_by_unit_and_formulir($sumber, $array_formulir_id){
    $strFormulirId = join(",", $array_formulir_id);

    $sql = "
        SELECT detil_kpi.kpi_id, detil_kpi.nama_kpi,
         count(detil_kpi.id) * 100 as `MAX_SCORE`, 
         SUM(detil_kpi.persen_ketercapaian) as `nilai_pencapaian`,
         SUM(detil_kpi.nilai_aktual) as `total_nilai_aktual`,
         COUNT(detil_kpi.id) as `jumlah_user`,
         detil_kpi.target_institusi,
         detil_kpi.target_individu
        FROM (
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
    AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id IN (".$strFormulirId.") 
    AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
    ) as `detil_kpi`
    GROUP BY detil_kpi.kpi_id";
    //ChromePhpModel::log($sql);
    $query = $this->db->query($sql);
    return $query->result();;
}

public function get_statistic_pencapaian_sumber_kpi_by_unit_and_periode($sumber, $unit_id, $is_ketua, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql = "
        SELECT 
            all_formulir_data.periode_id,
            all_formulir_data.tahun,
            all_formulir_data.semester,
            (COUNT(all_formulir_data.id) * 100 ) as `MAX_SCORE`,
            SUM( (all_formulir_data.score / all_formulir_data.MAX_SCORE) * 100) as `nilai_ketercapaian_institusi`  
        FROM
        (SELECT 
            data_formulir.*,
            (COUNT(data_formulir.id) * 100) as `MAX_SCORE`,
            ( SUM(data_formulir.nilai_pencapaian) ) as `score`
        FROM
        (SELECT 
            unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
        AND unit.id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $strPeriode )
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.unit_id, data_formulir.formulir_ketua, data_formulir.periode_id) as `all_formulir_data`
        GROUP BY all_formulir_data.periode_id";

    $query = $this->db->query($sql);
    return $query->result();
}

public function get_pencapaian_sumber_kpi_by_user_and_periode($sumber, $user_id, $unit_id, $is_ketua, $periode_id){
        $sql = "
        SELECT 
            unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
        AND formulir_hasil_bidang_kinerja_utama.user_id = $user_id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        AND formulir_hasil_bidang_kinerja_utama.periode_id = $periode_id
        AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id";

    $query = $this->db->query($sql);
    return $query->row();
}

public function get_detil_pencapaian_sumber_kpi_by_user_formulir($sumber, $formulir_id){
    $sql = "
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
        kpi.nama_kpi
    FROM detil_formulir_hasil_bidang_kinerja_utama
    JOIN kpi
    WHERE kpi.id = detil_formulir_hasil_bidang_kinerja_utama.kpi_id
    AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
    AND detil_formulir_hasil_bidang_kinerja_utama.formulir_hasil_bidang_kinerja_utama_id = ".$formulir_id;
    $query = $this->db->query($sql);
    return $query->result();
}

public function get_statistic_pencapaian_sumber_kpi_by_user_and_periode($sumber, $user_id, $unit_id, $is_ketua, $array_periode){
    $strPeriode = join(",", $array_periode);
    $sql  = "
            SELECT 
                data_formulir.periode_id,
                data_formulir.semester,
                data_formulir.bobot * data_formulir.nilai_pencapaian as `score`
            (SELECT 
            unit.nama_unit, unit.institusi_id, unit.tenaga_pengajar,
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
        AND formulir_hasil_bidang_kinerja_utama.user_id = $user_id
        AND formulir_hasil_bidang_kinerja_utama.unit_id = $unit_id
        AND formulir_hasil_bidang_kinerja_utama.formulir_ketua = '$is_ketua'
        AND formulir_hasil_bidang_kinerja_utama.periode_id IN ( $strPeriode )
        AND detil_formulir_hasil_bidang_kinerja_utama.sumber = '$sumber'
        GROUP BY formulir_hasil_bidang_kinerja_utama.id) as `data_formulir`
        GROUP BY data_formulir.periode_id, data_formulir.semester
    ";

    $query = $this->db->query($sql);
    return $query->result();
}



}

?>