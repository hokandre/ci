<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Form Tambah User"  
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
    <section class="page-content" id="page-indikator">
    
        <!-- CONTENT TITLE -->    
        <?php $this->load->view("user/template/content_title_form_tambah_user.php");?>

        <!-- bread crumb -->
        <?php $this->load->view("user/template/breadcrumb_form_tambah_user.php",[
            "breadcrumb" => $breadcrumb
        ]);?>

        <div class="card">
            <div class="card-header">
                <h4><i class="far fa-user"></i> Data Pengguna Baru</h4>
            </div>
            <div class="card-body">
                <form action="<?=$action_add_user?>" method="post" id="form-user">
                    <h4 style="margin-bottom:10px;">Id Pengguna :</h4>
                    <input value="<?=isset($id) ? $id : "";?>" style="margin-bottom:10px;" type="number" size="6" name="id" placeholder="terdiri dari 6 angka ..."/>
                
                    <h4 style="margin-bottom:10px;">Nama Pengguna :</h4>
                    <input value="<?=isset($nama_user) ? $nama_user : "";?>" class="input-block" style="margin-bottom:10px;" type="text" name="nama_user" placeholder="nama pengguna ..."/>

                    <h4 style="margin-bottom:10px;">Password :</h4>
                    <input value="<?=isset($password) ? $password : "";?>" class="input-block" style="margin-bottom:10px;" type="password" name="password" placeholder="password ..."/>

                    <h4 style="margin-bottom:10px;">Unit :</h4>
                    <select name="unit_id"  class="input-block" style="margin-bottom:10px;">
                        <?php $indexUnit=0; foreach($data_unit as $unit): $indexUnit++;?>
                            <option value="<?=$unit->id;?>" <?=isset($unit_id) ? $unit->id == $unit_id ?  "selected" : "" : "";?> > 
                                <?=$unit->nama_unit;?>
                            </option>
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
//modal response
$element_modal_response = "
<div class='error-response'>
    <div class='logo'>
        <i class='fas fa-exclamation-triangle fa-5x'></i>
    </div>
    <div class='message'>

    </div>
</div>
<div class='success-response'>
    <div class='logo'>
        <i class='far fa-check-circle fa-5x'></i></i>
    </div>
    <div class='message'>

    </div>
</div>
";

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
$(document).ready(function(){
    tablePagination('#table-user');
})

let error = '<?php echo  is_null($error) ? "" : json_encode($error);?>';
let success = '<?php echo  is_null($success) ? "" : json_encode($success);?>';
if(error){
    $("#modal-message .modal-body .error-response").show();
    $("#modal-message .modal-body .success-response").hide();
    $("#modal-message .modal-body .error-response .message").empty();
    let errorMessage = JSON.parse(error);
    let key = Object.keys(errorMessage);
    $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+key[0]+'!</b> '+errorMessage[key]+'</p>');
    $("#modal-message").css("display", "block"); 
}

if(success){
    $("#modal-message .modal-body .error-response").hide();
    $("#modal-message .modal-body .success-response").show();
    $("#modal-message .modal-body .sucess-response .message").empty();
    $("#modal-message .modal-body .success-response .message").append('<p ><b>Berhasil!</b> user berhasil didaftarkan!</p>');
    $("#modal-message").css("display", "block"); 
    $('#form-user').trigger("reset"); 
}
</script>


  
