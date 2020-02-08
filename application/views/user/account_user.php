<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?=$title;?></title>
  <!--Global CSS-->
  <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet"/>
</head>

<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="page-indikator">

    <div class="content-title">
      <div class="page-title">
        <h3><i class="fas fa-user-cog" aria-hidden="true"></i> Kelola Akun Anda</h3> 
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <h4> <i class="fas fa-user-cog" aria-hidden="true"></i>  Data Anda</h4>
      </div>
      <div class="card-body">
        <form action="<?=$action_update_password?>" method="post" id="form-account">
            <h4 style="margin-bottom:10px;">Username :</h4>
            <p style="padding:10px; border:1px solid black;"><?=$user_id;?></p>

            <h4 style="margin-bottom:10px;margin-top : 10px;">Nama User :</h4>
            <p style="padding:10px; border:1px solid black;"><?=$nama_user;?></p>         

            <h4 style="margin-bottom:10px;margin-top : 10px;">Unit :</h4>
            <p style="padding:10px; border:1px solid black;"><?=$nama_unit;?></p>

            <h4 style="margin-bottom:10px;margin-top : 10px;">Insitusi :</h4>
            <p style="padding:10px; border:1px solid black;"><?=$nama_institusi;?></p>

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
            <div class="success-response">
                <div class="logo">
                    <i class="far fa-check-circle fa-5x"></i></i>
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
</html>

  
