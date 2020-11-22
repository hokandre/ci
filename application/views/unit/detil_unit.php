<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Detil Unit"
]);?> 

<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

  <section class="page-content" id="page-indikator">
      <!-- CONTENT TITLE -->    
      <?php $this->load->view("unit/template/content_title_detil_unit.php", [
          "unit_selected" => $unit_selected
      ]);?>
     

      <!-- BREADCRUMB -->
      <?php $this->load->view("unit/template/breadcrumb_detil_unit.php",[
          "breadcrumb" => $breadcrumb
      ]);?>

      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Anggota Unit <?=$unit_selected->nama_unit;?></h4>
        </div>
        <div class="card-body">
          
          <form class="form-filter" action="<?=$action_update_ketua;?>" method="post">
              <div class="form-filter-option">
                  <h4>Ketua Unit : </h4>
                      <select name="ketua_unit" id="ketua_unit">
                          <?php $indexUser=0; foreach($data_user as $user) : $indexuser++;?>
                              <option value="<?=$user->id?>" <?= $user->id == $unit_selected->ketua_unit ? "selected" : "";?> >
                                  <?=$user->nama_user;?>
                              </option>
                          <?php endforeach;?>
                      </select>
              </div>

              <div class="form-filter-option">
                  <button class="toolbar"> Simpan </button>
              </div>
          </form>

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

          <table id="table-unit">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Jenis</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; foreach($data_anggota as $anggota): $i++;?>
                <tr class="table-row">
                    <td class="table-cell"><?=$i;?></td>
                    <td class="table-cell"><?=$anggota->nama_user;?></td>
                    <td class="table-cell"><?=$unit_selected->tenaga_pengajar == "1" ? "Tenaga Pengajar" : "Operasional";?></td>
                    <td class="table-cell">
                        <a class="btn-info">Detil</a>
                    </td>
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

<?php 
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
?>
<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>

<script>
let success = JSON.parse('<?php echo json_encode($success);?>');

if(success){
    $("#modal-message .modal-body .error-response").hide();
    $("#modal-message .modal-body .success-response").show();
    $("#modal-message .modal-body .sucess-response .message").empty();
    $("#modal-message .modal-body .success-response .message").append(`<p ><b>Berhasil!</b> Data Berhasil Diubah!</p>`);
    $("#modal-message").css("display", "block"); 
}

$(document).ready(function(){
    tablePagination('#table-unit');
})
</script>