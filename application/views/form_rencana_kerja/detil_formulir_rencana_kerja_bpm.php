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
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="page-detil-rencana-kerja">

    <div class="content-title shadow not-printed">
        <div class="page-title">
            <h3> <i class="far fa-file-alt"></i> Detil Laporan Formulir Bidang Hasil Kinerja Utama</h3> 
        </div>
        <div class="margin-left">
            <a class="toolbar" href="<?=$action_get_ketidak_tercapaian.$formulir->id;?>"><i title="analisis ketidak tercapaian" class="fas fa-info"></i> Analisis</a>
            <a class="toolbar" href="<?=$action_print.$formulir->id;?>"><i onclick="edit_colspan()" title="print" class="fas fa-print"></i> Print</a> 
        </div>
    </div>

     <!-- breadcrumb -->
     <ul class="breadcrumb">
       <?php if($show_breadcrumb_list == 1):?>
        <li>
            <form action="<?=$breadcrumb_list_url?>" method="post" style="margin-right:30px;">
                <input type="hidden" value="<?=$breadcrumb_list_tahun;?>" name="tahun"/>
                <input type="hidden" value="<?=$breadcrumb_list_institusi;?>" name="institusi"/>
                <input type="hidden" value="<?=$breadcrumb_list_ganjil;?>" name="ganjil"/>
                <input type="hidden" value="<?=$breadcrumb_list_genap?>" name="genap"/>
                <input type="submit" value="<?=$breadcrumb_list_nama;?>"/>
            </form>
        </li>
       <?php endif;?>

       <?php if($show_breadcrumb_detil == 1):?>
        <li>
            <form action="<?=$breadcrumb_detil_url.$breadcrumb_detil_unit;?>" method="post">
                <!-- for bread crumb list -->
                <?php if(isset($show_breadcrumb_list)) : ?>
                    <?php if($show_breadcrumb_list == 1) : ?>                
                        <input type="hidden" name="show_bread_crumb_list" value="1"/>
                        <input type="hidden" name="list_institusi_crumb" value="<?=$breadcrumb_list_institusi;?>">
                        <input type="hidden" name="list_tahun_crumb" value="<?=$breadcrumb_list_tahun;?>"/>
                        <input type="hidden" name="list_ganjil_crumb" value="<?=$breadcrumb_list_ganjil;?>"/>
                        <input type="hidden" name="list_genap_crumb" value="<?=$breadcrumb_list_genap;?>"/>
                    <?php endif;?>
                <?php endif;?>
                <!-- for bread crumb detil -->
                <input type="hidden" name="show_bread_crumb_detil" value="1"/>
                <input type="hidden" value="<?=$breadcrumb_detil_unit;?>" name="detil_unit_crumb">
                <input type="submit" value="<?=$breadcrumb_detil_nama;?>"/>
            </form>
        </li>
       <?php endif;?>
    </ul>

    <div class="card">
        <div class="card-body">
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
                                            <th class="table-cell w-10 not-printed">Action</th>
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
                                                    <p class="printed" style="display:none;"><?=$poin->nilai_aktual;?> </p>
                                                    <form class="not-printed" id="<?="'form$i'"?>" action="<?=$action.$formulir->id.'/'.$poin->id;?>" method="post">
                                                        <input type="hidden" value="<?=$poin->target_individu;?>" name="target_individu"/>
                                                        <input style="width:70px;" type="text" value="<?=$poin->nilai_aktual;?>" name="nilai_aktual"/>
                                                        <br>
                                                        <?=$simbolSatuan;?>
                                                    </form>
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
                                                <td class="table-cell w-10 not-printed">
                                                    <?php if($poin->bukti) : ?>                         
                                                        <p><i class="far fa-eye btn-toggle-modal btn-toogle-modal-bukti" modal-target="modal-bukti" data-file="<?php echo base_url()."dokumen/".$poin->bukti;?>"></i> <?=$poin->bukti;?></p>
                                                    <?php else : ?>
                                                        <p>Belum ada file</p>
                                                    <?php endif;?>
                                                    <div class="flex-column">
                                                        <input type="hidden" name="formulir" value="">

                                                        <button type="submit" class="<?= $poin->status == '1' ? '' : 'btn-update';?>" form="<?="'form$i'"?>" <?= $poin->status == 1 ? "disabled" : "";?>>
                                                                simpan
                                                        </button>
                                                        <button 
                                                            data-formulir-id="<?=$formulir->id;?>"
                                                            data-kpi-id="<?=$poin->kpi_id;?>"
                                                            modal-target="modal-comment" 
                                                            class="btn-toggle-modal btn-info btn-toogle-modal-comment">
                                                            Catatan
                                                        </button>
                                                        
                                                        <form id="<?='terima'.$poin->id;?>" action="<?=$action_terima.$formulir->id."/".$poin->id;?>" method="post">
                                                            <?php if($poin->status == 1) : ?>
                                                                <input type="hidden" value="0" name="status"/>
                                                                <input form="<?='terima'.$poin->id;?>" class="btn-delete" type="submit" value="Revisi"/>
                                                            <?php else : ?>
                                                                <input type="hidden" value="1" name="status"/>
                                                                <input form="<?='terima'.$poin->id;?>" class="btn-delete" type="submit" value="Terima"/>
                                                            <?php endif;?>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach;?>
                                        <tr>
                                            <td colspan="2">Total</td>
                                            <td><?=ceil($total_bobot) > 100 ? 100 : ceil($total_bobot);?></td>
                                            <td id="colspan-print" colspan="4"></td>
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
                <thead id="header-document" class="hide">
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

           
            
            <div class="comment-box not-printed">
                <hr size="3">
                <h4> <i class="far fa-comment-dots"></i> Catatan :</h4> 
                <?php 
                  $comment_array_by_kpi_id = [];
                  foreach($formulir->comment as $com ){
                      if(isset($comment_array_by_kpi_id[$com->kpi_id])){
                          array_push($comment_array_by_kpi_id[$com->kpi_id], $com);
                      }else{
                          $comment_array_by_kpi_id[$com->kpi_id] = [$com];
                      }
                  }
                ?>
                <?php $i=0; foreach($comment_array_by_kpi_id as $kpi_id => $row_comment): $i++; ?>
                  <div class="comment-row">
                      <div class="comment-title">
                          <div class="comment-poin">[ <a href="#<?=$kpi_id;?>">Poin - <?=$i;?></a> ] </div>
                          <div class="comment-poin-name">:<b> <?= $comment_array_by_kpi_id[$kpi_id][0]->nama_kpi;?> </b></div>
                      </div>
                      <?php $j=0; foreach($row_comment as $comment):$j++;?>
                          <div class="comment-list">
                              <div class="comment-list-left">
                                  <p><?= $comment->nama_user;?></p>
                                  <p><?= $comment->created_at;?></p>
                              </div>
                              <div class="comment-list-right">
                                  <p><?= $comment->isi;?></p>
                                  <div class="comment-action" >
                                      <i 
                                          data-comment-id="<?=$comment->id;?>"
                                          data-formulir-id="<?=$comment->formulir_rencana_kerja_id;?>"
                                          data-kpi-id="<?=$comment->kpi_id;?>"
                                          data-isi="<?=$comment->isi;?>"
                                          modal-target="modal-comment" 
                                          class="fas fa-eye-dropper btn-toggle-modal btn-update-modal-comment" 
                                          style="margin-right:10px;"></i>

                                      <!-- <i 
                                          modal-target="modal-konfirmasi-delete-comment"
                                          data-comment-id="<?=$comment->id;?>"
                                          data-formulir-id="<?=$comment->formulir_rencana_kerja_id;?>"
                                          class="far fa-trash-alt btn-togglsae-modal btn-delete-modal-comment" 
                                          style="margin-right:10px;"></i> -->
                                  </div>
                              </div>
                          </div>
                      <?php endforeach;?>
                  </div>
              <?php endforeach;?>
            </div>
        </div>
    </div>
</section>
</main>

</body>
<!-- modal dokumen -->
<div class="modal" id="modal-bukti">
    <div style="width:80%;margin:auto;height:100%;" >
        <embed id="file-pdf" src="" width="100%" height="100%"/>
    </div>
</div>

<!-- modal comment -->
<div class="modal" id="modal-comment">
    <div class="modal-content-sm">
        <div class="modal-header">
            <h4> <i class="far fa-comment-dots"></i>  Note</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <!-- display error in here --->
            <div id="error-response"></div>
            <input type="hidden" name="comment_id">
            <input type="hidden" name="formulir_id">
            <input type="hidden" name="kpi_id">
            <textarea name="comment" id="comment" cols="30" style="width:100%;" rows="10" placeholder="Berikan catatan pada poin ini..."></textarea>
        </div>
        <div class="modal-footer">
            <button id="btn-save-modal-comment" class="btn-info">simpan</button>
        </div>
    </div>
</div>

<!--modal konfirmasi delete comment-->
<div class="modal" id="modal-konfirmasi-delete-comment">
    <div class="modal-content-sm">
        <div class="modal-body">
            <input type="hidden" name="comment_id">
            <input type="hidden" name="formulir_id">
            Apakah anda yakin akan menghapus catatan ini ?
        </div>
        <div class="modal-footer">
            <button class="btn-info close-modal">Tidak</button>
            <button id="btn-save" class="btn-update">Ya</button>
        </div>
    </div>
</div>

<!-- modal message -->
<div class="modal" id="modal-message">
    <div class="modal-content-sm">
        <div class="modal-header">
            <h4>Message</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">                                
        </div>
    </div>
</div>



<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<script type="text/javascript">
//input just for number

setInputFilter($('input[name="nilai_aktual"]'), function(value) {
    return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});




let error =JSON.parse('<?php echo  json_encode($error);?>');
if(error){
    let key = Object.keys(error);
    $('#modal-message .modal-body').append(`<p class="alert error"><b>${key[0]}</b> ${error[key[0]]}</p>`);
    $('#modal-message').css("display","block");
}

$(document).on('click', '.btn-toogle-modal-bukti', function(){
    let urlFile = $(this).attr("data-file");
    $('#modal-bukti embed#file-pdf').replaceWith(
        $(`<embed width="100%" height="100%"/>`)
        .attr("src", urlFile)
    );
})

$(document).on("click", '.btn-toogle-modal-comment', function(){
    let formulir_id = $(this).attr("data-formulir-id");
    let kpi_id = $(this).attr("data-kpi-id");
    $("#modal-comment input[name='formulir_id']").val(formulir_id);
    $("#modal-comment input[name='kpi_id']").val(kpi_id); 
})

$(document).on("click", '.btn-update-modal-comment', function(){
    let formulir_id = $(this).attr("data-formulir-id");
    let kpi_id = $(this).attr("data-kpi-id");
    let comment_id = $(this).attr("data-comment-id");
    let isi = $(this).attr("data-isi");
    $("#modal-comment input[name='comment_id']").val(comment_id);
    $("#modal-comment input[name='formulir_id']").val(formulir_id);
    $("#modal-comment input[name='kpi_id']").val(kpi_id); 
    $("#modal-comment textarea[name='comment']").val(isi); 
})

//toogle model konfirmasi delete
$(document).on('click', '.btn-delete-modal-comment', function(){
    let comment_id = $(this).attr("data-comment-id");
    let formulir_id = $(this).attr("data-formulir-id");
    $('#modal-konfirmasi-delete-comment input[name="comment_id"]').val(comment_id);
    $('#modal-konfirmasi-delete-comment input[name="formulir_id"]').val(formulir_id);
})

//btn yes pada modal delete
$(document).on('click', '#modal-konfirmasi-delete-comment #btn-save', function(){
    let comment_id = $('#modal-konfirmasi-delete-comment input[name="comment_id"]').val();
    let data = {
        'formulir_id' : $('#modal-konfirmasi-delete-comment input[name="formulir_id"]').val()
    } 
    $.ajax({
        method: 'post',
        url : '<?php echo $action_delete_comment?>'+comment_id,
        data : JSON.stringify({'data'  : data})
    })
        .done(success => {
            window.location.href = success.redirect;
        })

})    


$(document).on("click", "#btn-save-modal-comment", function(){
    $("#error-response").empty();

    if($("#modal-comment input[name='comment_id']").val()){
        //do update
        let data = {
            'formulir_id' :  $("#modal-comment input[name='formulir_id']").val(),
            'kpi_id' : $("#modal-comment input[name='kpi_id']").val(),
            'isi' : $("#modal-comment textarea[name='comment']").val()

        }

        let comment_id = $("#modal-comment input[name='comment_id']").val();
        $.ajax({
            method : "post",
            url : '<?php echo $action_update_comment;?>'+comment_id,
            data : JSON.stringify({"data" : data})
        })
            .done(success => {
                $("#modal-comment input[name='comment_id']").val("");
                $("#modal-comment input[name='formulir_id']").val("");
                $("#modal-comment input[name='kpi_id']").val(""); 
                window.location.href = success.redirect;
            })
            .fail(error => {
                    let key = Object.keys(error.responseJSON)[0];
                $("#error-response").append(`<p class="alert error"><b>${key}</b> ${error.responseJSON[key]} <span class="closebtn">&times;</span></p>`)
            })

    }else{
        //do add
        let data = {
            'formulir_id' :  $("#modal-comment input[name='formulir_id']").val(),
            'kpi_id' : $("#modal-comment input[name='kpi_id']").val(),
            'isi' : $("#modal-comment textarea[name='comment']").val()
        }
        $.ajax({
            method : "post",
            url : '<?php echo $action_add_comment;?>',
            data : JSON.stringify({ "data" : data})
        })
            .done(success => {
                $("#modal-comment input[name='formulir_id']").val("");
                $("#modal-comment input[name='kpi_id']").val(""); 
                window.location.href = success.redirect;
            })
            .fail(error => {
                let key = Object.keys(error.responseJSON)[0];
                $("#error-response").append(`<p class="alert error"><b>${key}</b> ${error.responseJSON[key]} <span class="closebtn">&times;</span></p>`)
            })
    }
})
</script>

</html>
