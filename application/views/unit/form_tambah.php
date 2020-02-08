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
        <h3> <i class="fas fa-university"></i> Unit</h3> 
      </div>
    </div>

     <!-- breadcrumb -->
     <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <li><a href="<?=$crum['url'];?>"><?=$crum['name'];?></a></li>
        <?php endforeach;?>
    </ul>

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
        $('#form-unit').trigger("reset"); 
    }
</script>
</html>

  
