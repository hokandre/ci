<?php 

function hitung_pencapaian_institusi($array_formulir_unit){
    if(sizeof($array_formulir_unit) != 0){
       $nilai_maksimal = sizeof($array_formulir_unit) * 100;
       $nilai_tercapai = 0;
       foreach($array_formulir_unit as $formulir) {
           $nilai_tercapai += $formulir->persen_tercapai;
       }

        $persen_ketercapaian = ( $nilai_tercapai / $nilai_maksimal ) * 100;
        $persen_tidak_tercapai = 100 - $persen_ketercapaian;
        $nilai_tidak_tercapai = $nilai_maksimal - $nilai_tercapai;
        $kinerja_institusi  = (object) [
            "persen_tercapai" => $persen_ketercapaian,
            "persen_tidak_tercapai" => $persen_tidak_tercapai,
            "nilai_tercapai" => $nilai_tercapai,
            "nilai_maksimal" => $nilai_maksimal,
            "nilai_tidak_tercapai" => $nilai_tidak_tercapai
        ];

        return $kinerja_institusi;
    }
    return null;
}

function hitung_pencapaian_unit($array_formulir_anggota=[]){
    if(sizeof($array_formulir_anggota) != 0){
       $nilai_maksimal = sizeof($array_formulir_anggota) * 100;
       $nilai_tercapai = 0;
       foreach($array_formulir_anggota as $formulir) {
           $nilai_tercapai += $formulir->persen_tercapai;
       }

       $persen_ketercapaian = ( $nilai_tercapai / $nilai_maksimal ) * 100;$persen_tidak_tercapai = 100 - $persen_ketercapaian;
       $nilai_tidak_tercapai = $nilai_maksimal - $nilai_tercapai;

       $kinerja_unit  = (object) [
            "persen_tercapai" => $persen_ketercapaian,
            "persen_tidak_tercapai" => $persen_tidak_tercapai,
            "nilai_tercapai" => $nilai_tercapai,
            "nilai_maksimal" => $nilai_maksimal,
            "nilai_tidak_tercapai" => $nilai_tidak_tercapai,
        ];
       
        return $kinerja_unit;
    }

    return null;
}

function hitung_pencapaian_user($array_kpi=[]){
    if(sizeof($array_kpi) != 0){
       $nilai_maksimal = 100;
       $nilai_tercapai = 0;
       foreach($array_kpi as $formulir) {
           $nilai_tercapai += $formulir->nilai_tercapai;
       }

       $persen_ketercapaian = ( $nilai_tercapai / $nilai_maksimal ) * 100;
       $persen_tidak_tercapai = 100 - $persen_ketercapaian;
       $nilai_tidak_tercapai = $nilai_maksimal - $nilai_tercapai;

       $kinerja_user  = (object) [
            "persen_tercapai" => $persen_ketercapaian,
            "persen_tidak_tercapai" => $persen_tidak_tercapai,
            "nilai_tercapai" => $nilai_tercapai,
            "nilai_maksimal" => $nilai_maksimal,
            "nilai_tidak_tercapai" => $nilai_tidak_tercapai,
        ];
       
        return $kinerja_user;
    }

    return null;
}
?>