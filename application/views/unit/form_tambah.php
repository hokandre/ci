<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Form Tambah Unit"
]);?> 

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-indikator">
        <!-- CONTENT TITLE -->    
        <?php $this->load->view("unit/template/content_title_form_tambah_unit.php");?>
      
        <!-- bread crumb -->
        <?php $this->load->view("unit/template/breadcrumb_form_tambah_unit.php",[
            "breadcrumb" => $breadcrumb
        ]);?>


        <div class="card">
            <div class="card-header">
                <h4> <i class="fas fa-university"></i> Data Unit Baru</h4>
            </div>
            <div class="card-body">
                <form action="<?=$action_add_unit?>" method="post" id="form-unit">
                    <h4 style="margin-bottom:10px;">Nama Unit :</h4>
                    <input class="input-block" type="text" name="nama_unit" id="nama_unit" placeholder="masukan nama unit..." value="<?=$nama_unit;?>"/>    

                    <h4 style="margin-bottom:10px;">Jenis :</h4>
                    <select name="tenaga_pengajar" id="tenaga_pengajar" class="input-block">
                        <option value="1" <?=$tenaga_pengajar == "1" ? "selected" : "";?> >Tenaga Penagajar</option>
                        <option value="0" <?=$tenaga_pengajar == "0" ? "selected" : "";?> > Operasional </option>
                    </select>

                    <h4 style="margin-bottom:10px;">Institusi :</h4>
                    <select name="institusi_id" id="institusi_id" class="input-block">
                        <?php foreach($data_institusi as $institusi) :  ?>
                            <option value="<?=$institusi->id?>" <?=$institusi->id == $institusi_id ? "selected" : "";?> ><?=$institusi->nama_institusi;?></option>
                        <?php endforeach;?>
                    </select>

                    <h4 style="margin-bottom:10px;">Ketua Unit :</h4>
                    <select name="ketua_unit" id="ketua_unit" class="input-block">
                        <?php foreach($data_user as $user) :  ?>
                            <option value="<?=$user->id?>" <?=$user->id == $ketua_unit ? "selected" : "";?> ><?=$user->nama_user;?></option>
                        <?php endforeach;?>
                    </select>

                    <div class="flex-row" style="margin-top:30px;">
                        <button class="btn-info margin-left">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>

<?php 
//modal-response
$element_modal_response = <<<EOD
<div class="error-response">
    <div class="logo">
        <i class="fas fa-exclamation-triangle fa-5x"></i>
    </div>
    <div class="message">

    </div>
</div>
<div class="success-response">
    <div class="logo">
        <i class="far fa-check-circle fa-5x"></i></i>
    </div>
    <div class="message">

    </div>
</div>
EOD;

$this->load->view("template/modal_umum.php", [
    "id" => "modal-message",
    "size" => "modal-content-md",
    "icon" => "far fa-comment-dots",
    "title" => "Message",
    "element" => $element_modal_response,
    "button_type" => "btn-info close-modal",
    "button_desc" => "OK"
]);?>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>
<script>
let error = JSON.parse('<?php echo  json_encode($error);?>');
let success = JSON.parse('<?php echo json_encode($success);?>');

if(error.status){
    $("#modal-message .modal-body .error-response").show();
    $("#modal-message .modal-body .success-response").hide();
    $("#modal-message .modal-body .error-response .message").empty();
    $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+error.key+'!</b> '+error.message+'</p>');
    $("#modal-message").css("display", "block"); 
}

if(success.status){
    $("#modal-message .modal-body .error-response").hide();
    $("#modal-message .modal-body .success-response").show();
    $("#modal-message .modal-body .sucess-response .message").empty();
    $("#modal-message .modal-body .success-response .message").append(`<p ><b>${success.key}!</b> ${success.message}!</p>`);
    $("#modal-message").css("display", "block"); 
    $('#form-unit').trigger("reset"); 
}
</script>


  
