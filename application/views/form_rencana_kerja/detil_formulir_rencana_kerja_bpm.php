<!DOCTYPE html>
<html lang="en">

<!-- Header -->
<?php $this->load->view('partials/header.php',[
    "title" => "Detil Laporan Hasil Bidang Rencana Kerja",
    "css" => [
        base_url()."assets/css/page_detil_formulir_rencana_kerja.css"
    ]
]);?>

<main>
    <!-- SIDEBAR-->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
    <section class="page-content" id="page-detil-rencana-kerja">

        <!--CONTENT TITLE -->
        <?php $this->load->view("form_rencana_kerja/template/content_title_detil_formulir_bpm.php",[
            "action_get_ketidak_tercapaian" => $action_get_ketidak_tercapaian,
            "action_print" => $action_print,
            "formulir" => $formulir
        ]);?>

        <!-- breadcrumb -->
        <?php $this->load->view("form_rencana_kerja/template/breadcrumb_detil_formulir_bpm.php",[
            //breadcrumb list
            "show_breadcrumb_list" => $show_breadcrumb_list,
            "breadcrumb_list_url" => isset($breadcrumb_list_url) ? $breadcrumb_list_url : NULL,
            "breadcrumb_list_tahun" => isset($breadcrumb_list_tahun) ? $breadcrumb_list_tahun : NULL,
            "breadcrumb_list_institusi" => isset($breadcrumb_list_institusi) ? $breadcrumb_list_institusi : NULL,
            "breadcrumb_list_ganjil" => isset($breadcrumb_list_ganjil) ? $breadcrumb_list_ganjil : NULL,
            "breadcrumb_list_genap" => isset($breadcrumb_list_genap) ? $breadcrumb_list_genap : NULL,
            "breadcrumb_list_nama" => isset($breadcrumb_list_nama) ? $breadcrumb_list_nama : NULL, 

            //breadcrumb detil
            "show_breadcrumb_detil" => $show_breadcrumb_detil,
            "breadcrumb_detil_url" => isset($breadcrumb_detil_url) ? $breadcrumb_detil_url : NULL,
            "breadcrumb_detil_unit" => isset($breadcrumb_detil_unit) ? $breadcrumb_detil_unit : NULL,
            "breadcrumb_detil_nama" => isset($breadcrumb_detil_nama) ? $breadcrumb_detil_nama : NULL
        ]);?>

        <div class="card">
            <div class="card-body">
            <!-- List Poin -->                    
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
                                <td class="table-cell w-5 text-center">
                                    <?=$i;?>
                                </td>
                                <td class="table-cell w-25">
                                    <?=$poin->nama_indikator;?>
                                </td>
                                <?php 
                                    $bobot=$poin->bobot;;
                                    $total_bobot += $bobot;
                                ?>
                                <td class="table-cell w-5">
                                    <?=$bobot;?>
                                </td>
                                <td class="table-cell w-25">
                                    <?=$poin->nama_kpi;?>
                                </td>

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

                                <td class="table-cell w-5 text-center">
                                    <?=$poin->target_institusi." ".$simbolSatuan;?>
                                </td>
                                <td class="table-cell w-5 text-center">
                                    <?=$poin->target_individu." ".$simbolSatuan;?>
                                </td>
                                <td class="table-cell">
                                    <p class="printed" style="display:none;">
                                        <?=$poin->nilai_aktual;?> 
                                    </p>
                                    <form class="not-printed" 
                                        id="<?="'form$i'"?>" 
                                        action="<?=$action.$formulir->id.'/'.$poin->id;?>" method="post">
                                            <input 
                                                type="hidden" 
                                                value="<?=$poin->target_individu;?>" name="target_individu"/>
                                            <input 
                                                style="width:70px;" type="text" 
                                                value="<?=$poin->nilai_aktual;?>" 
                                                name="nilai_aktual"/>
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
                                        <p>
                                            <i 
                                            class="far fa-eye btn-toggle-modal btn-toogle-modal-bukti" modal-target="modal-bukti" data-file="<?php echo base_url()."dokumen/".$poin->bukti;?>"></i> <?=$poin->bukti;?>
                                        </p>
                                    <?php else : ?>
                                        <p>Belum ada file</p>
                                    <?php endif;?>
                                    <div class="flex-column">
                                        <input type="hidden" name="formulir" value="">

                                        <button 
                                            type="submit" 
                                            class="<?= $poin->status == '1' ? '' : 'btn-update';?>" 
                                            form="<?="'form$i'"?>" 
                                            <?= $poin->status == 1 ? "disabled" : "";?>>
                                                simpan
                                        </button>
                                        <button 
                                            data-formulir-id="<?=$formulir->id;?>"
                                            data-kpi-id="<?=$poin->kpi_id;?>"
                                            modal-target="modal-comment" 
                                            class="btn-toggle-modal btn-info btn-toogle-modal-comment">
                                            Catatan
                                        </button>
                                        
                                        <form 
                                            id="<?='terima'.$poin->id;?>" action="<?=$action_terima.$formulir->id."/".$poin->id;?>" method="post">
                                            <?php if($poin->status == 1) : ?>
                                                <input 
                                                    type="hidden" 
                                                    value="0" 
                                                    name="status"/>
                                                <input 
                                                    form="<?='terima'.$poin->id;?>" class="btn-delete" type="submit" value="Revisi"/>
                                            <?php else : ?>
                                                <input 
                                                    type="hidden" 
                                                    value="1" 
                                                    name="status"/>
                                                <input 
                                                    form="<?='terima'.$poin->id;?>" class="btn-delete" type="submit" value="Terima"/>
                                            <?php endif;?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        <tr>
                            <td colspan="2">Total</td>
                            <td>
                                <?=ceil($total_bobot) > 100 ? 100 : ceil($total_bobot);?>
                            </td>
                            <td id="colspan-print" colspan="4"></td>
                            <td>
                                <?=$total_score > 100 ? 100 : $total_score;?>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                    
            <table 
                style="width:100%;" 
                class="hide" 
                id="analisis-ketidak-tercapaian">
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
                            <div class="comment-poin">
                                [ <a href="#<?=$kpi_id;?>">Poin - <?=$i;?></a> ] 
                            </div>
                            <div class="comment-poin-name">:
                                <b> <?= $comment_array_by_kpi_id[$kpi_id][0]->nama_kpi;?></b>
                            </div>
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
                                            style="margin-right:10px;">
                                        </i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;?>
                    </div>
                <?php endforeach;?>
            </div>
        </div>
    </section>
</main>
<!-- modal dokumen -->
<div class="modal" id="modal-bukti">
    <div style="width:80%;margin:auto;height:100%;" >
        <embed id="file-pdf" src="" width="100%" height="100%"/>
    </div>
</div>


<?php 
//modal comment
$element_modal_comment = <<<EOD
<div id="error-response"></div>
<input type="hidden" name="comment_id">
<input type="hidden" name="formulir_id">
<input type="hidden" name="kpi_id">
<textarea name="comment" id="comment" cols="30" style="width:100%;" rows="10" placeholder="Berikan catatan pada poin ini..."></textarea>
EOD;
$this->load->view("template/modal_umum.php", [
    "id" => "modal-comment",
    "size" => "modal-content-sm",
    "title" => "Note",
    "icon" => "far fa-comment-dots",
    "element" => $element_modal_comment,
    "button_id" => "btn-save-modal-comment"
]);

//modal message
$this->load->view("template/modal_umum.php", [
    "id" => "modal-message",
    "size" => "modal-content-sm",
    "title" => "Message",
    "icon" => "far fa-comment-dots"
]);

?>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>

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