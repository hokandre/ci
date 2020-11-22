<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
    "title" => "Analisis Ketidak Tercapaian"
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content">
        <!--Page Title -->
        <?php $this->load->view("analisis_ketidak_tercapaian/template/content_title_list_analisis_ketidak_tercapaian.php");?>
    
        <!-- breadcrumb -->
        <?php $this->load->view("analisis_ketidak_tercapaian/template/breadcrumb_list_analisis_ketidak_tercapaian.php",[
            "breadcrumb" => $breadcrumb
        ]);?> 
    
        <div class="card">
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Indikator</th>
                            <th colspan="3">Analisis Ketidaktercapaian</th>
                            <!-- HAK AKSES BPM = 1 -->
                            <?php if($this->session->userdata("hak_akses") != 1) :?>
                                <th rowspan="2">Action</th>
                            <?php endif;?>
                        </tr>
                        <tr>
                            <th>Analisis Penyebab <br> (Cause Analysis)</th>
                            <th>Tindakan Korektif <br> (Corrective Action)</th>
                            <th>Tindakan Pencegahan <br> (Preventive Action)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index=0; foreach($data_tidak_tercapai as $poin_tidak_tercapai): $index++;?>
                        <tr>
                            <td><?=$index;?></td>
                            <td><?=$poin_tidak_tercapai->nama_kpi;?></td>
                            <td>
                                <?php 
                                    $hak_akses_bpm = 1;
                                    if($this->session->userdata("hak_akses") == $hak_akses_bpm) :
                                ?>
                                    <p>
                                        <?=$poin_tidak_tercapai->analisis_penyebab;?>
                                    </p>
                                <?php else : ?>
                                    <form 
                                        method="post" 
                                        action="<?=$action_update_ketidak_tercapaian.$poin_tidak_tercapai->id;?>" id="form-<?=$index;?>">
                                        <textarea 
                                            form="form-<?=$index;?>" name="analisis_penyebab">
                                            <?=$poin_tidak_tercapai->analisis_penyebab;?>
                                        </textarea>
                                    </form>
                                <?php endif;?>
                            </td>
                            <td>
                                <!-- Bpm -->
                                <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm): ?>
                                    <p>
                                        <?=$poin_tidak_tercapai->tindakan_korektif;?>
                                    </p>
                                <?php else :?>
                                    <textarea 
                                        form="form-<?=$index;?>" name="tindakan_korektif">
                                        <?=$poin_tidak_tercapai->tindakan_korektif;?>
                                    </textarea>
                                <?php endif;?>
                            </td>
                            <td>
                                 <!-- Bpm -->
                                 <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm): ?>
                                    <p>
                                        <?=$poin_tidak_tercapai->tindakan_pencegahan;?>
                                    </p>
                                <?php else :?>
                                    <textarea 
                                        form="form-<?=$index;?>" name="tindakan_pencegahan">
                                        <?=$poin_tidak_tercapai->tindakan_pencegahan;?>
                                    </textarea>
                                <?php endif;?>
                            </td>
                            <?php 
                                if($this->session->userdata("hak_akses") != $hak_akses_bpm) : ?>
                                <td>
                                    <input name="formulir_id" type="hidden" form="form-<?=$index;?>" value="<?=$formulir_id;?>">
                                    <input type="submit" class="btn-info" form="form-<?=$index;?>" value="simpan">
                                </td>
                            <?php endif;?>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <!-- END OF CARD BODY -->
        </div>
        <!-- END OF CARD -->
    </section>
</main>

<?php 
//modal response
$element_modal_response = "
<div class='error-response'>
    <div class='logo'>
        <i class='fas fa-exclamation-triangle fa-5x'></i>
    </div>
    <div class='message'>

    </div>
</div>
";
$this->load->view("template/modal_umum.php",[
    "id" => "modal-message",
    "size" => "modal-content-md",
    "icon" => "far fa-comment-dots",
    "title" => "Message",
    "element" => $element_modal_response,
    "button_type" => "btn-info close-modal",
    "button_desc" => "OK"
]);
?>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>

<script>
    let error = '<?php echo is_null($error) ? "" : json_encode($error);?>';
    if(error){
            $("#modal-message .modal-body .error-response").show();
            $("#modal-message .modal-body .error-response .message").empty();
            let errorMessage = JSON.parse(error);
            let key = Object.keys(errorMessage);
            $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+key[0]+'!</b> '+errorMessage[key]+'</p>');
            $("#modal-message").css("display", "block"); 
    }
</script>


 
