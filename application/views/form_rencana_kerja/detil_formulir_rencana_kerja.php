<!-- HEADER -->
<?php $this->load->view('partials/header.php',[
    "title" => "Detil Formulir Hasil Bidang Kinerja Utama",
    "css" => [ base_url()."assets/css/page_detil_formulir_rencana_kerja.css" ]
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-detil-rencana-kerja">
        <!-- CONTENT TITLE -->
        <?php $this->load->view("form_rencana_kerja/template/content_title_detil_formulir_user.php");?>

        <!-- breadcrumb -->
        <?php $this->load->view("form_rencana_kerja/template/breadcrumb_detil_formulir_user.php");?>

        <!-- CONTENT -->
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
                                                <p style="flex:1; text-align:start;">: <?=$formulir->tanggal_revisi == null ? "-" : $formulir->tanggal_revisi;?></p>
                                            </div>
                                            <div style="display:flex;">
                                                <p style="flex:1; text-align:start;">Tanggal Berlaku</p>
                                                <p style="flex:1; text-align:start;">: <?=$formulir->tanggal_berlaku == null ? "-" : $formulir->tanggal_berlaku;?></p>
                                            </div>
                                        </div>
                                        <div id="document-version" style="flex:1;">
                                            <div style="display:flex; border-bottom:1px solid black;">
                                                <p style="flex:1; text-align:start;">Versi / Revisi</p>
                                                <p style="flex:1; text-align:start;">: <?=$formulir->versi;?> / <?=$formulir->revisi;?></p>
                                            </div>
                                            <div style="display:flex;">
                                                <p style="flex:1; text-align:start;">Halaman</p>
                                                <p style="flex:1; text-align:start;">: - </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="document-user" style="display:flex; padding : 10px 0px;">
                                        <div id="document-user-identity" style="flex:1; text-align:start;">
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
                                            <p id="user-name"> <span id="label-name" style="display: inline-block; width:50px;">NAMA </span> : <?=$formulir->nama_user;?></p>
                                            <p id="user-nik"> <span id="label-nil" style="display: inline-block; width:50px;">NIK </span> : <?=$formulir->user_id;?></p>
                                        </div>
                                        <div id="document-user-job" style="flex:1; text-align:start;">
                                            <p id="user-job"> <span id="label-job" style="display: inline-block; width:90px;">JABATAN </span> :  <?=$namaUnit;?></p>
                                            <?php
                                            $periodeName = "";
                                            if($formulir->semester == "1"){
                                                $periodeName = "September ".$formulir->tahun." - Februari ".($formulir->tahun+1);
                                            }else {
                                                $periodeName = "Maret ".($formulir->tahun+1)." - Agustus ".($formulir->tahun+1);
                                            }
                                            ?>
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
                                    <div>
                                        <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="w-5">No</th>
                                                <th class="w-25">Indikator</th>
                                                <th class="w-5">Bobot</th>
                                                <th class="w-25">KPI</th>
                                                <th class="w-5">Target</th>
                                                <th class="w-5">Target Individu</th>
                                                <th class="w-5">Aktual</th>
                                                <th class="w-5">Skor</th>
                                                <th class="w-10 not-printed">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  
                                                $total_bobot = 0;
                                                $total_score = 0;
                                            ?>
                                            <?php $i=0; foreach($formulir->detil as $poin) :$i++;?>
                                                <?php $total_bobot = $total_bobot + $poin->bobot;?> 
                                            <tr>
                                                    <td><?=$i;?></td>
                                                    <td><?=$poin->nama_indikator;?></td>
                                                    <td><?=$poin->bobot;?></td>
                                                    <td><?=$poin->nama_kpi;?></td>
                                                    
                                                    <?php 
                                                        $simbolSatuan = "%";
                                                        switch($poin->satuan){
                                                            case 'satuan':
                                                                $simbolSatuan = "Buah (Desimal)";
                                                                break;
                                                            case 'orang':
                                                                $simbolSatuan = "org";
                                                                break;
                                                            case 'satuan bulat' :
                                                                $simbolSatuan = "Buah (Bulat)";
                                                                break;
                                                            default :  
                                                                $simbolSatuan = $simbolSatuan;
                                                                break;
                                                        }
                                                    ?>
                                                    <td><?=$poin->target_institusi." ".$simbolSatuan;?></td>
                                                    <td>
                                                        <?php echo form_open_multipart($action.$formulir->id."/".$poin->id, array("id"=>"'form$i'"));?>
                                                            <input style="width:70px;" type="text" value="<?=$poin->target_individu;?>" name="target_individu"/>
                                                            <input type="hidden" name="satuan" value="<?=$poin->satuan;?>"/>
                                                            <br>
                                                            <?=$simbolSatuan;?>
                                                            <input type="hidden" name="target_institusi" value="<?=$poin->target_institusi;?>">
                                                        </form>
                                                    </td>
                                                    <td class="table-cell">
                                                        <input  form="'form<?=$i;?>'" style="width:70px;" type="text" value="<?=$poin->nilai_aktual;?>" name="nilai_aktual"/>
                                                        <?=$simbolSatuan;?>
                                                    </td>
                                                    <td class="table-cell w-5">
                                                        <?php 
                                                            $score= 0;
                                                            if($poin->nilai_aktual < $poin->target_institusi){
                                                                $score = number_format((float)($poin->nilai_aktual / $poin->target_institusi) * $poin->bobot);
                                                            }else{
                                                                $score = $poin->bobot;
                                                            }
                                                            echo $score;
                                                            $total_score += $score;
                                                        ?>
                                                    </td>
                                                        <td class="table-cell w-10 not-printed">
                                                            <?php if($poin->bukti) : ?>                         
                                                                <p><i class="far fa-eye btn-toggle-modal btn-toogle-modal-bukti" modal-target="modal-bukti" data-file="<?php echo base_url()."dokumen/".$poin->bukti;?>"></i> <?=$poin->bukti;?></p>
                                                            <?php else : ?>
                                                                <p>Belum ada file</p>
                                                            <?php endif;?>
                                                            <?php if($this->session->userdata("id") == $formulir->user_id) :?>
                                                                <input form="'form<?=$i;?>'" type="file" name="file" id="dokument_pendukung" placeholder="Dokumen"/>
                                                            <?php endif;?>
                                                            <?php if($this->session->userdata("id") == $formulir->user_id) :?>
                                                                <input type="submit" form="'form<?=$i;?>'" class="<?=$poin->status == "1" ? "" : "btn-info";?>" value="Simpan" <?=$poin->status == "1" ? "disabled" : "";?>/>
                                                            <?php endif;?>
                                                        </td>
                                                
                                            </tr>
                                            <?php endforeach;?>
                                            <tr>
                                                <td colspan="2">Total</td>
                                                <td><?=ceil($total_bobot) > 100 ? 100 : ceil($total_bobot);?></td>
                                                <td colspan="5"></td>
                                                <td><?=$total_score > 100 ? 100 : $total_score;?></td>
                                            </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                </table>               
                <!-- for print -->
                <div class="pagebreak"></div>
                <table id="format-document">
                    <thead id="header-document" style="visibility: hidden">
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
                
                <!-- COMMNET BOX -->
                <?php $this->load->view("form_rencana_kerja/template/comment_box_detil_formulir_user.php",[
                    "formulir" => $formulir
                ]);?>

            </div>
        </div>

    </section>
</main>

<!-- MODAL BUKTI : DISPLAY PDF -->
<div class="modal" id="modal-bukti">
    <div style="width:80%;margin:auto;height:100%;" >
        <embed id="file-pdf" src="" width="100%" height="100%"/>
    </div>
</div>

<!-- MODAL ERROR -->
<?php $this->load->view("template/modal_umum.php",[
    "id" => "modal-error",
    "size" => "modal-content-sm",
    "icon" => "",
    "title" => "Message",
    "button_type" => "btn-default close-modal",
    "button_desc" => "OK"
]) ;?>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php"); ?>

<script type="text/javascript">
setInputFilter($('input[name="nilai_aktual"]'), function(value) {
    return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});

let error = '<?php echo is_null($error) ? "" : json_encode($error);?>';
let key = '';

if(error){
    error = JSON.parse(error);
    key =  Object.keys(error)[0];
    $('#modal-error .modal-body').append(`
    <p class="alert error"><b>${key}</b> ${error[key]}</p>
    `)
    $('#modal-error').css("display","block");
}

$(document).on('click', '.btn-toogle-modal-bukti', function(){
    let urlFile = $(this).attr("data-file");
    $('#modal-bukti embed#file-pdf').replaceWith(
        $(`<embed width="100%" height="100%"/>`)
        .attr("src", urlFile)
    );
})
</script>

