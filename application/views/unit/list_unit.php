<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "List Unit"
]);?> 

<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

  <section class="page-content" id="page-indikator">
      <!-- CONTENT TITLE -->    
      <?php $this->load->view("unit/template/content_title_list_unit.php", [
        "action_form_unit" => $action_form_unit
      ]);?>

      <!-- bread crumb -->
      <?php $this->load->view("unit/template/breadcrumb_list_unit.php",[
        "breadcrumb" => $breadcrumb
      ]);?>
     

      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Unit</h4>
        </div>
        <div class="card-body">
          <form class="form-filter" action="<?=$action_cari;?>" method="post">
              <div class="form-filter-option">
                  <h4>Institusi : </h4>
                      <select name="institusi_id" id="institusi_id">
                          <?php $indexInstitusi=0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                              <option value="<?=$institusi->id?>" <?= $institusi->id == $institusi_selected ? "selected" : "";?> >
                                  <?=$institusi->nama_institusi;?>
                              </option>
                          <?php endforeach;?>
                      </select>
              </div>

              <div class="form-filter-option">
                  <button class="toolbar fas fa-search"> Cari </button>
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
                <th>Nama Unit</th>
                <th>Jenis</th>
                <th>Institusi</th>
                <th style="width: 100px; text-align:center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; foreach($data_unit as $unit): $i++;?>
                <tr class="table-row">
                    <td class="table-cell"><?=$i;?></td>
                    <td class="table-cell">
                        <form id="<?='form'.$i;?>" action="<?=$action_update_unit.$unit->id;?>" method="post"></form>
                        <input form="<?='form'.$i;?>" class="input-block" type="text" value="<?=$unit->nama_unit?>" name="nama_unit">
                      </td>
                    <td class="table-cell">
                        <select class="input-block" form="<?='form'.$i;?>" name="tenaga_pengajar" id="tenaga_pengajar">
                              <option value="0" <?= $unit->tenaga_pengajar == "0" ? "selected" : "";?> >
                                Operasional
                              </option>
                              <option value="1" <?= $unit->tenaga_pengajar == "1" ? "selected" : "";?> >
                                Tenaga Pengajar
                              </option>
                        </select>
                      </td>
                      <td class="table-cell">
                        <select class="input-block" form="<?='form'.$i;?>" name="institusi_id" id="institusi_id">
                              <?php $indexInstitusi=0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                              <option value="<?=$institusi->id;?>" <?=$unit->institusi_id == $institusi->id ? "selected" : "";?> >
                                  <?= $institusi->nama_institusi;?>
                              </option>

                              <?php endforeach;?>
                        </select>
                      </td>
                      <td class="table-cell">
                          <button class="btn-update" form="<?='form'.$i;?>" >Update</button>
                          <a href="<?=$action_detil.$unit->id;?>" class="btn-info">Detil</a>
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
// modal response
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
</div>";
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
<?php $this->load->view("partials/footer.php") ;?>

<script>
$(document).ready(function(){
    tablePagination('#table-unit');
})

let error = '<?php echo  is_null($error) ? "" : json_encode($error);?>';
let success = '<?php echo  is_null($success) ? "" : $success;?>';
if(error){
    $("#modal-message .modal-body .error-response").show();
    $("#modal-message .modal-body .success-response").hide();
    $("#modal-message .modal-body .error-response .message").empty();
    let errorMessage = JSON.parse(error);
    let key = Object.keys(errorMessage);
    $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+key[0]+'</b> '+errorMessage[key]+'</p>');
    $("#modal-message").css("display", "block"); 
}

if(success){
    $("#modal-message .modal-body .error-response").hide();
    $("#modal-message .modal-body .success-response").show();
    $("#modal-message .modal-body .sucess-response .message").empty();
    $("#modal-message .modal-body .success-response .message").append('<p ><b>Berhasil!</b> Data unit berhasil di ubah!</p>');
    $("#modal-message").css("display", "block");  
}

$(document).on('hide', '#modal-message', function(){
  if(success){
    window.location.href = success;
  }
})
</script>
