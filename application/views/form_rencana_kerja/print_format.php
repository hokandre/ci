<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Rencana Kerja</title>
  <!--Global CSS-->
  <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet"/>
  <!-- Just for this page css -->
  <link href=<?php echo base_url()."assets/css/page_detil_formulir_rencana_kerja.css";?> rel="stylesheet"/>
</head>

<body>
    
    <table id="format-document">
            <thead id="header-document">
                <tr>
                    <!-- Image mdp -->
                    <th>   
                        <div>
                        <div class="container-logo" style="display: flex;" >
                            <div class="logo-box" style="flex:1; height:50px;">
                                <img style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/stmik_mdp_logo.png";?>" />
                            </div>
                            <div class="logo-box" style="flex:1; height:50px;" >
                                <img style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/amik_logo.jpeg";?>"/>
                            </div>
                            <div class="logo-box" style="flex:1; height:50px;">
                                <img  style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/stie_mdp_logo.png";?>"/>
                            </div>
                        </div>
                        <h2 id="document-name">FORMULIR BIDANG HASIL KINERJA UTAMA</h2>
                        <div id="document-description" style="display: flex;">
                            <div id="document-code" style="flex:1;text-align:center;border-right:1px solid black;">
                                <p>KODE DOKUMEN :</p>
                                <p><b>FM-SKM-01-04/R2</b></p>
                            </div>
                            <div id="document-date" style="flex:1;border-right:1px solid black;">
                                <div style="display:flex; border-bottom:1px solid black;">
                                    <p style="flex:1; text-align:start;">Tanggal Revisi</p>
                                    <p style="flex:1; text-align:start;">:<?=$formulir->tanggal_revisi == null ? "-" : $formulir->tanggal_revisi;?></p>
                                </div>
                                <div style="display:flex;">
                                    <p style="flex:1; text-align:start;">Tanggal Berlaku</p>
                                    <p style="flex:1; text-align:start;">: <?=$formulir->tanggal_berlaku == null ? "-" : $formulir->tanggal_berlaku;?></p>
                                </div>
                            </div>
                            <div id="document-version" style="flex:1;">
                                <div style="display:flex; border-bottom:1px solid black;">
                                    <p style="flex:1; text-align:start;">Versi / Revisi</p>
                                    <p style="flex:1; text-align:start;">:<?=$formulir->versi;?> / <?=$formulir->revisi;?></p>
                                </div>
                                <div style="display:flex;">
                                    <p style="flex:1; text-align:start;">Halaman</p>
                                    <p style="flex:1; text-align:start;">: - </p>
                                </div>
                            </div>
                        </div>

                        <div id="document-user" style="display:flex; padding : 10px 0px;">
                            <div id="document-user-identity" style="flex:1; text-align:start;">
                                <p id="user-name"> <span id="label-name" style="display: inline-block; width:50px;">NAMA </span> : <?=$formulir->nama_user;?></p>
                                <p id="user-nik"> <span id="label-nil" style="display: inline-block; width:50px;">NIK </span> : <?=$formulir->user_id;?></p>
                            </div>
                            <div id="document-user-job" style="flex:1; text-align:start;">
                                <?php 
                                    $namaUnit = $formulir->nama_unit;
                                    switch ($formulir->tenaga_pengajar) {
                                        case '0':
                                            if($formulir->formulir_ketua == '1'){
                                                $namaUnit = "Ketua ".$namaUnit;
                                            }else{
                                                $namaUnit = "Anggota ".$namaUnit;
                                            }
                                            break;
                                        case '1' : 
                                            if($formulir->formulir_ketua == '1'){
                                                $namaUnit = "Ketua ".$namaUnit;
                                            }else{
                                                $namaUnit = "Dosen ".$namaUnit;
                                            }
                                            break;
                                        default:
                                            $namaUnit = $namaUnit;
                                            break;
                                    }
                                ?>
                                <p id="user-job"> <span id="label-job" style="display: inline-block; width:90px;">JABATAN </span> : <?=$namaUnit;?></p>
                                <?php
                                    $periodeName = "";
                                    if($formulir->semester == "1"){
                                    $periodeName = "September ".$formulir->tahun." - Februari ".($formulir->tahun+1);
                                    }else {
                                    $periodeName = "Maret ".($formulir->tahun+1)." - Agustus ".($formulir->tahun+1);
                                    }
                                ?>
                                <p id="periode"> <span id="label-periode" style="display: inline-block; width:90px;">PERIODE </span> : <?=$periodeName;?> </p>
                            </div>
                        </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <td id="document-body">
                    <table id="table-detil-formulir-hasil-bidang-kinerja-utama">
                                <thead>
                                    <tr>
                                        <th class="table-cell w-5">No</th>
                                        <th class="table-cell w-25">Indikator</th>
                                        <th class="table-cell w-5">Bobot</th>
                                        <th class="table-cell w-25">KPI</th>
                                        <th class="table-cell w-5">Target</th>
                                        <th class="table-cell w-5">Target Inthidu</th>
                                        <th class="table-cell w-5">Aktual</th>
                                        <th class="table-cell w-5">Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                        $total_bobot=0; 
                                        $total_score=0;
                                    ?>
                                    <?php $i=0; foreach($formulir->detil as $poin) :$i++;?>
                                        <tr class="table-row" id="<?=$poin->id;?>">
                                            <td class="table-cell w-5 text-center"><?=$i;?></td>
                                            <td class="table-cell w-25"><?=$poin->nama_indikator;?></td>
                                            <?php 
                                                $bobot=$poin->bobot;;
                                                $total_bobot += $bobot;
                                            ?>
                                            <td class="table-cell w-5"><?=$bobot;?></td>
                                            <td class="table-cell w-25"><?=$poin->nama_kpi;?></td>
                                            <?php 
                                                $simbolSatuan = "%";
                                                switch($poin->satuan){
                                                    case 'satuan':
                                                        $simbolSatuan = "buah (Desimal)";
                                                        break;
                                                    case 'satuan bulat':
                                                            $simbolSatuan = "buah (Bulat)";
                                                            break;
                                                    case 'orang':
                                                        $simbolSatuan = "org";
                                                        break;
                                                    default :  
                                                        $simbolSatuan = $simbolSatuan;
                                                        break;
                                                }
                                            ?>
                                            <td class="table-cell w-5 text-center"><?=$poin->target_institusi." ".$simbolSatuan;?></td>
                                            <td class="table-cell w-5 text-center"><?=$poin->target_individu." ".$simbolSatuan;?></td>
                                            <td class="table-cell">
                                                <p class="printed"><?=$poin->nilai_aktual;?> </p>
                                            </td>
                                            <td class="table-cell" style="width:70px;">
                                                <?php 
                                                    $score=0;
                                                    if($poin->nilai_aktual < $poin->target_institusi){
                                                        $score = number_format((float)($poin->nilai_aktual / $poin->target_institusi) * $poin->bobot, 2,'.','');
                                                    }else{
                                                        $score =  number_format((float)$poin->bobot,2,'.','');
                                                    }
                                                    $total_score += $score;
                                                    echo $score;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach;?>
                                    <tr>
                                        <td colspan="2">Total</td>
                                        <td><?=ceil($total_bobot) > 100 ? 100 : ceil($total_bobot);?></td>
                                        <td id="colspan-print" colspan="3"></td>
                                        <td><?=$total_score > 100 ? 100 : $total_score;?></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            </tbody>
    </table>
    <div class="pagebreak"></div>
        <table id="format-document">
            <thead id="header-document">
                    <tr>
                        <!-- Image mdp -->
                        <th>   
                            <div>
                            <div class="container-logo" style="display: flex;" >
                                <div class="logo-box" style="flex:1; height:50px;">
                                    <img style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/stmik_mdp_logo.png";?>" />
                                </div>
                                <div class="logo-box" style="flex:1; height:50px;" >
                                    <img style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/amik_logo.jpeg";?>"/>
                                </div>
                                <div class="logo-box" style="flex:1; height:50px;">
                                    <img  style="width:100%; height:100%; object-fit:contain;" src="<?php echo base_url()."assets/image/stie_mdp_logo.png";?>"/>
                                </div>
                            </div>
                            <h2 id="document-name">FORMULIR BIDANG HASIL KINERJA UTAMA</h2>
                            <div id="document-description" style="display: flex;">
                                <div id="document-code" style="flex:1;text-align:center;border-right:1px solid black;">
                                    <p>KODE DOKUMEN :</p>
                                    <p><b>FM-SKM-01-04/R2</b></p>
                                </div>
                                <div id="document-date" style="flex:1;border-right:1px solid black;">
                                    <div style="display:flex; border-bottom:1px solid black;">
                                        <p style="flex:1; text-align:start;">Tanggal Revisi</p>
                                        <p style="flex:1; text-align:start;">: 28 February 2017</p>
                                    </div>
                                    <div style="display:flex;">
                                        <p style="flex:1; text-align:start;">Tanggal Berlaku</p>
                                        <p style="flex:1; text-align:start;">: 1 Maret 2017</p>
                                    </div>
                                </div>
                                <div id="document-version" style="flex:1;">
                                    <div style="display:flex; border-bottom:1px solid black;">
                                        <p style="flex:1; text-align:start;">Versi / Revisi</p>
                                        <p style="flex:1; text-align:start;">: 28 February 2017</p>
                                    </div>
                                    <div style="display:flex;">
                                        <p style="flex:1; text-align:start;">Halaman</p>
                                        <p style="flex:1; text-align:start;">: 1 / 2</p>
                                    </div>
                                </div>
                            </div>

                            <div id="document-user" style="display:flex; padding : 10px 0px;">
                                <div id="document-user-identity" style="flex:1; text-align:start;">
                                    <p id="user-name"> <span id="label-name" style="display: inline-block; width:50px;">NAMA </span> : <?=$formulir->nama_user;?></p>
                                    <p id="user-nik"> <span id="label-nil" style="display: inline-block; width:50px;">NIK </span> : <?=$formulir->user_id;?></p>
                                </div>
                                <div id="document-user-job" style="flex:1; text-align:start;">
                                    <p id="user-job"> <span id="label-job" style="display: inline-block; width:90px;">JABATAN </span> : <?=$namaUnit;?></p>
                                    <p id="periode"> <span id="label-periode" style="display: inline-block; width:90px;">PERIODE </span> : <?=$periodeName;?></p>
                                </div>
                            </div>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td id="document-body">
                            <table style="width:100%;" class="hide" id="analisis-ketidak-tercapaian">
                                <thead>
                                    <tr>
                                        <th width="60px" rowspan="2">No</th>
                                        <th width="20%" rowspan="2">Indikator</th>
                                        <th width="80%" colspan="3">Analisis Ketidaktercapaian</th>
                                    </tr>
                                    <tr>
                                        <th>Analisis Penyebab <br> (Cause Analysis)</th>
                                        <th>Tindakan Korektif <br> (Corrective Action)</th>
                                        <th>Tindakan Pencegahan <br> (Preventive Action)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $indexTidakTercapai=0; foreach($formulir->detil as $poin_tidak_tercapai): $indexTidakTercapai++;?>
                                        <?php if($poin_tidak_tercapai->nilai_aktual < $poin_tidak_tercapai->target_institusi):?>
                                            <tr>
                                                <td><?=$indexTidakTercapai;?></td>
                                                <td><?=$poin_tidak_tercapai->nama_kpi;?></td>
                                                <td><?=$poin_tidak_tercapai->analisis_penyebab;?></td>
                                                <td><?=$poin_tidak_tercapai->tindakan_korektif;?></td>
                                                <td><?=$poin_tidak_tercapai->tindakan_pencegahan;?></td>
                                            </tr>
                                        <?php endif;?>
                                    <?php endforeach;?>
                                </tbody>
                            </table> 
                        </td>
                    </tr>
                </tbody>
        </table>
    </div>

</body>
<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<script type="text/javascript">
//input just for number
$(document).on("ready", function(){
    window.print();
})
</script>

</html>
