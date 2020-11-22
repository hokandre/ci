<?php $hak_akses_bpm = 1; ?>

<!-- HEADER -->
<?php $this->load->view('partials/header.php',[
  "css" => [
    base_url()."assets/css/page_bidang.css"
  ]
]);?>

<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
  <section class="page-content" id="page-bidang">

      <!-- CONTENT TITLE -->
      <?php $this->load->view("form_bidang/template/content_title_bidang.php",[
        "action_lihat_pencapain" => $action_lihat_pencapain
      ]);?>

      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Bidang Indikator</h4>
        </div>
        <div class="card-body">
          <div class="table-topbar">
              <div class="col-6">
                <!-- Alert Succes Atau Error-->
                <?php if(isset($error)) :?>
                        <div class="alert error">
                          <span class="closebtn">&times;</span>
                          <b>Error!</b> <?= $error; ?>
                        </div>
                <?php endif;?>
                
                <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) : ?>
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
                      <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) :?>
                        <div class="table-cell w-10 text-center">Action</div>
                      <?php endif;?>
                </div>
                
                <?php $i=0; foreach($data_bidang as $bidang) :$i++;?>
                  <?php echo form_open_multipart($action_update.$bidang->id, array("class"=>"table-row"));?>
                        <div class="table-cell text-center"><?=$i;?></div>
                        <div class="table-cell text-center">
                          <input type="text" value="<?=$bidang->nama_bidang;?>" name="nama_bidang"/>
                        </div>
                        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) :?>
                          <div class="table-cell text-center">
                            <input type="submit" class="btn-update" value="update"/>
                          </div>
                        <?php endif;?>
                    </form>
                <?php endforeach;?>
              </div >
          </div>
        </div>
      </div>
  </section>
</main>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>
  
