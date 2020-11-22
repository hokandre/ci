<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Detil Indikator",
  "css" => [
    base_url()."assets/css/page_detil_indikator.css"
  ]
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
  <section class="page-content" id="page-detil-indikator">

      <!-- CONTENT TITLE -->
      <?php $this->load->view("indikator/template/content_title_detil_indikator.php");?>
      

      <!-- BREADCRUMB -->
      <?php $this->load->view("indikator/template/breadcrumb_detil_indikator.php", [
        "breadcrumb" => $breadcrumb
      ]);?>

      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Unit</h4>
        </div>
        <div class="card-body">
          <!-- MENU CHANGE UNIT -->
          <div class="table-topbar">
              <div class="col-6">
                <?php echo form_open($action_add_detil);?>
                    <input type="hidden" name="indikator" value="<?=$indikator->id;?>"/>
                  <select name="unit">
                      <?php $i=0; foreach($data_unit as $unit): $i++; ?>;
                        <option value="<?=$unit->id?>"><?=$unit->nama_unit;?></option>
                      <?php endforeach;?>
                  </select>
                  <input type="submit" class="btn toolbar toolbar-white" value="Masukan" />
                </form>
              </div>
          </div>

          <!-- MENU SHOW NUMBER OF ROW -->
          <div class="table-topbar">
            <div class="table-topbar-filter">
              <h4>show : </h4>
                <select name="numberRow" id="numberRow">
                  <option value="10">10</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
            </div> 
            <div class="table-topbar-filter">
              <h4>search :</h4> <input id="tableSearch" type="text" value="" placeholder="search..."/>
            </div>
          </div>
    
          <table id="list-detil-indikator">
            <colgroup>
                <col class="w-5">
                <col class="w-85">
                <col>
            </colgroup>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Unit</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; foreach($indikator->detil as $unit): $i++;?>
                  <tr>
                    <td><?=$i;?></td>
                    <td><p><?=$unit->nama_unit;?></p></td>
                    <td style="padding:15px 0px; text-align:center;">
                      <a href="<?=$action_delete_detil.$indikator->id."/".$unit->id;?>" class="btn-delete"> Hapus </a>
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
        <!-- END OF CARD BODY-->
      </div>
      <!-- END OF CARD -->
  </section>
</main>

<?php
$element_modal_message = <<<EOD
<div class="logo">
    <i class="fas fa-exclamation-triangle fa-5x"></i>
    <div class="error-response">
        <div class="message"></div>
    </div>
</div>
EOD;
$this->load->view("template/modal_umum.php",[
  "id" => "modal-message",
  "size" => "modal-content-md",
  "icon" => "far fa-comment-dots",
  "title" => "Message",
  "element" => $element_modal_message,
  "button_type" => "btn-info close-modal",
  "button_desc" => "OK"
]);
;?>

<!-- FOOTER -->
<?php $this->load->view('partials/footer.php',[
 "js" => [
   base_url()."assets/js/page_detil_indikator.js"
 ]]); ?>

<script>
  let error = '<?php echo is_null($error) ? "" : json_encode($error);?>';
 
  if(error){
    $("#modal-message .modal-body .error-response").show();
     $("#modal-message .modal-body .error-response .message").empty();
     let errorMessage = JSON.parse(error);
     let key = Object.keys(errorMessage);
     $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+key[0]+'!</b> '+errorMessage[key]+'</p>');
     $("#modal-message").css("display", "block"); 
  }
</script>


  
