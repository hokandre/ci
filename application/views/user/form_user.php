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
        <h3> <i class="fas fa-user-cog"></i> Pengguna</h3> 
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
</html>

  
