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
  <!-- Just for this page css -->
  <link href=<?php echo base_url()."assets/css/page_bidang.css";?> rel="stylesheet"/>
</head>

<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="page-bidang">

    <div class="content-title">
      <div class="page-title">
        <h3><i class="fas fa-globe"></i> Bidang Indikator</h3> 
      </div>
      <div class="margin-left">
          <a class="toolbar" title="buat formulir baru" href="<?=$action_lihat_pencapain;?>"><i class="fas fa-plus-circle"></i> Pencapaian</a>
      </div>
    </div>


    <div class="card">
      <div class="card-header">
        <h4><i class="fas fa-table"></i> Daftar Bidang Indikator</h4>
      </div>
      <div class="card-body">
        <div class="table-topbar">
            <div class="col-6">
              <!-- Alert Succes Atau Error-->
              <?php
                    if(isset($error)){
                      echo '<div class="alert error">';
                      echo '<span class="closebtn">&times;</span>';
                      echo '<b>Error!</b> '.$error;
                      echo '</div>';
                    }
              ?>
              
              <?php if($this->session->userdata("hak_akses") == 1) :?>
              <?php echo form_open($action_add);?>
                <input type="text" name="nama_bidang" placeholder="masukan nama bidang ..."/>
                <input type="submit" class="btn btn-primary" value="Masukan" />
              </form>
              <?php endif;?>
            </div>
  
  
        </div>
        <div class="table-container">
            <div class="table">
              <div class="table-header">
                    <div class="table-cell w-5 text-center">No</div>
                    <div class="table-cell w-85 text-center">Nama Bidang</div>
                    <?php if($this->session->userdata("hak_akses") == 1) :?>
                      <div class="table-cell w-10 text-center">Action</div>
                    <?php endif;?>
              </div>
              
               <?php $i=0; foreach($data_bidang as $bidang) :$i++;?>
                <?php echo form_open_multipart($action_update.$bidang->id, array("class"=>"table-row"));?>
                      <div class="table-cell text-center"><?=$i;?></div>
                      <div class="table-cell text-center"><input type="text" value="<?=$bidang->nama_bidang;?>" name="nama_bidang"/></div>
                      <?php if($this->session->userdata("hak_akses") == 1) :?>
                        <div class="table-cell text-center"><input type="submit" class="btn-update" value="update"/></div>
                      <?php endif;?>
                  </form>
               <?php endforeach;?>
            </div >
        </div>
      </div>
    </div>
</section>
</main>
</body>

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<!-- <script src=<?php echo base_url()."assets/js/page_form_rencana_kerja.js";?>></script> -->
</html>

  
