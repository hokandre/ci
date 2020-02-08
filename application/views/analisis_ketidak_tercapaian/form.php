
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Analisis Ketidak Tercapaian</title>
    <!--Global CSS-->
    <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet">
    
</head>
<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
    <section class="page-content">
        <!--Page Title -->
        <div class="content-title">
            <div class="page-title">
            <h3><i class="fas fa-scroll"></i> Analisis Ketidak Tercapaian</h3> 
            </div>
            <div class="margin-left">
            </div>
        </div>
    
        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
                <li><a href="<?=$crum['url'];?>"><?=$crum['name'];?></a></li>
            <?php endforeach;?>
        </ul>
    
        <div class="card">
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Indikator</th>
                            <th colspan="3">Analisis Ketidaktercapaian</th>
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
                                <?php if($this->session->userdata("hak_akses") == 1) :?>
                                    <p><?=$poin_tidak_tercapai->analisis_penyebab;?></p>
                                <?php else : ?>
                                    <form method="post" action="<?=$action_update_ketidak_tercapaian.$poin_tidak_tercapai->id;?>" id="form-<?=$index;?>">
                                        <textarea form="form-<?=$index;?>" name="analisis_penyebab"><?=$poin_tidak_tercapai->analisis_penyebab;?></textarea>
                                    </form>
                                <?php endif;?>
                            </td>
                            <td>
                                <!-- Bpm -->
                                <?php if($this->session->userdata("hak_akses") == 1): ?>
                                    <p><?=$poin_tidak_tercapai->tindakan_korektif;?></p>
                                <?php else :?>
                                    <textarea form="form-<?=$index;?>" name="tindakan_korektif"><?=$poin_tidak_tercapai->tindakan_korektif;?></textarea>
                                <?php endif;?>
                            </td>
                            <td>
                                 <!-- Bpm -->
                                 <?php if($this->session->userdata("hak_akses") == 1): ?>
                                    <p><?=$poin_tidak_tercapai->tindakan_pencegahan;?></p>
                                <?php else :?>
                                    <textarea form="form-<?=$index;?>" name="tindakan_pencegahan"><?=$poin_tidak_tercapai->tindakan_pencegahan;?></textarea>
                                <?php endif;?>
                            </td>
                            <?php if($this->session->userdata("hak_akses") != 1) : ?>
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
        </div>
    </section>
</main>

<!-- modal response -->
<div class="modal" id="modal-message">
    <div class="modal-content-md">
        <div class="modal-header">
            <h4> <i class="far fa-comment-dots"> </i>Message</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="error-response">
                <div class="logo">
                    <i class="fas fa-exclamation-triangle fa-5x"></i>
                </div>
                <div class="message">

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-info close-modal">OK</button>
        </div>
    </div>
</div>
</body>

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
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
</html>


 
