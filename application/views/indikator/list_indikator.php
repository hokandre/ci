<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "List Indikator",
  "css" => [
    base_url()."assets/css/page_indikator.css"
  ]
]);?>
<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
    <section class="page-content" id="page-indikator">

        <!-- CONTENT TITLE -->
        <?php $this->load->view("indikator/template/content_title_list_indikator.php");?>
        

        <!-- BREADCRUMB -->
        <?php $this->load->view("indikator/template/breadcrumb_list_indikator.php", [
          "breadcrumb" => $breadcrumb
        ]);?>

        <div class="card">
          <div class="card-header card-header-dashboard">
            <h4><i class="fas fa-table"></i> Daftar Indikator / Sasaran Strategis</h4>
              <form id="form-versi" action="<?=$action_lihat;?>" method="get">
                  <select name="versi" class="toolbar" style="color:var(--dark-green);">
                      <option value="indikator" <?= $versi == "indikator" ? "selected" : "";?> >Indikator</option>
                      <option value="unit" <?= $versi == "unit" ? "selected" : "";?> >Unit</option>
                  </select>
              </form>
          </div>
          <div class="card-body">
            <div class="table-topbar">
                <div class="col-6">
                  <!-- Alert Error-->
                  <?php if(isset($error)) : ?>
                    <div class="alert error">
                      <span class="closebtn">&times;</span>
                      <b>Error!</b> <?= $error; ?>
                    </div>
                  <?php endif;?>
                  
                  <?php if($this->session->userdata("hak_akses") == 1) :?>
                  <?php echo form_open($action_add);?>
                    <input type="text" name="nama_indikator" placeholder="masukan nama indikator ..."/>
                    <input type="submit" class="btn toolbar toolbar-white" value="Masukan" />
                  </form>
                  <?php endif;?>
                </div>
            </div>
            <div class="table-topbar">
                  <div class="table-topbar-filter">
                    <h4>show :</h4>
                      <select name="numberRow" id="numberRow">
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                      </select>
                  </div>
                  <div class="table-topbar-filter">
                    <h4>search : </h4> <input id="tableSearch" type="text" value="" placeholder="search..."/>
                  </div> 
            </div>
            
            <table class="" id="list-indikator">
              <colgroup>
                  <col class="w-5">
                  <col class="w-80">
                  <col class="w-15">
              </colgroup>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Indikator</th>
                  <?php if($this->session->userdata("hak_akses") == 1): ?> <th>Action</th> <?php endif;?>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; foreach($data_indikator as $indikator): $i++;?>
                    <tr>
                      <td><?=$i;?></td>
                      <td>
                        <form id="<?="'form$indikator->id'"?>" action="<?=$action_update.$indikator->id?>" method="post">
                          <input type="text" name="nama_indikator" value="<?=$indikator->nama_indikator;?>">
                        </form>
                      </td>
                      <?php if($this->session->userdata("hak_akses") == 1): ?>
                      <td>
                        <button form="<?="'form$indikator->id'"?>" class="btn-update">update</button>
                        <a href="<?=$action_detil.$indikator->id?>" class="btn-info"> detil</a>
                      </td>
                      <?php endif;?>
                    </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          
            <div class="table-footer">
              <p><span id="numberOfDataFound"></span> data ditemukan.</p>
              <ul class="pagination">
                <li class="pagination-item" data-page="prev" id="prev"> < </li>
                <li class="pagination-item" data-page="next" id="next"> > </li>
              </ul>
            </div>
          </div>
        </div>
    </section>
</main>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php", [
  "js" => [
    base_url()."assets/js/page_indikator.js"
  ]
]);?>
<script>
  let actionLihat = JSON.parse('<?php echo json_encode($action_lihat);?>');
  $("select[name='versi']").on('change', function(){
    let versi = $(this).find(":selected").val();
    if(versi == "unit"){
      $("#form-versi").attr("action", actionLihat+`?versi=unit`)
    }else{
      $("#form-versi").attr("action", actionLihat)
    }
    $("#form-versi").submit();
  })
</script>

  
