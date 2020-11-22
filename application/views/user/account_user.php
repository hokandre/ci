<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Account User",
]);?> 

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-indikator">

        <!-- CONTENT TITLE -->    
        <?php $this->load->view("user/template/content_title_account_user.php");?>

        <div class="card">
            <div class="card-header">
                <h4> <i class="fas fa-user-cog" aria-hidden="true"></i>  Data Anda</h4>
            </div>
            <div class="card-body">
                <form action="<?=$action_update_password?>" method="post" id="form-account">
                    <h4 style="margin-bottom:10px;">Username :</h4>
                    <p style="padding:10px; border:1px solid black;">
                        <?=$user_id;?>
                    </p>

                    <h4 style="margin-bottom:10px;margin-top : 10px;">Nama User :</h4>
                    <p style="padding:10px; border:1px solid black;">
                        <?=$nama_user;?>
                    </p>         

                    <h4 style="margin-bottom:10px;margin-top : 10px;">Unit :</h4>
                    <p style="padding:10px; border:1px solid black;">
                        <?=$nama_unit;?>
                    </p>

                    <h4 style="margin-bottom:10px;margin-top : 10px;">Insitusi :</h4>
                    <p style="padding:10px; border:1px solid black;">
                        <?=$nama_institusi;?>
                    </p>

                    <h4 style="margin-bottom:10px;margin-top : 10px;">Password Baru :</h4>
                    <input class="input-block" style="padding:10px;" type="text" name="password" id="password" value="<?=$password;?>"/>

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
    $('#form-account').trigger("reset"); 
}
</script>


  
