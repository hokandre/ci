<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "List KPI",
  "css" => [
    base_url()."assets/css/page_list_kpi.css"
  ]
]);?> 


<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
  <section class="page-content" id="page-kpi">

     <!-- CONTENT TITLE -->    
    <?php $this->load->view("kpi/template/content_title_list_kpi.php");?>

    <!-- BREADCRUMB -->
    <?php $this->load->view("kpi/template/breadcrumb_list_kpi.php");?>

    <div class="card">
        <form id="form-tambah-kpi" action="<?=$action_add;?>" method="post">
          <div>
            <p><b>Indikator : </b></p>
            <select name="indikator" id="indikator">
              <?php foreach($data_indikator as $indikator) :?>
                <option value="<?=$indikator->id;?>">
                  <?=$indikator->nama_indikator?>
                </option>
              <?php endforeach;?>
            </select>
          </div>
          <div>
            <p><b>Nama KPI : </b></p>
            <input type="text" name="kpi" id="kpi"/>
          </div>
          <div>
            <button type="submit" class="btn toolbar toolbar-white">
              Masukan
            </button>
          </div>
        </form>
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Key Performance Indicator( KPI )</h4>
        </div>
        <div class="card-body">

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

          <table id="table-kpi">
              <colgroup>
                  <col style="width:70px;">
                  <col style="width:40%;">
                  <col style="width:50%;">
                  <col style="width:100px;">
              </colgroup>
            <thead>
              <tr>
                <th>No</th>
                <th>Indikator / Sasaran Strategis</th>
                <th>Key Performance Indicator</th>
                <th style="width: 100px; text-align:center;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; foreach($data_kpi as $kpi): $i++;?>
                <tr class="table-row">
                    <td class="table-cell"><?=$i;?></td>
                        <td class="table-cell">
                            <select class="input-block" form="<?='form'.$i;?>" name="indikator_id" id="indikator_id">
                                  <?php $indexIndikator=0; foreach($data_indikator as $indikator): $indexIndikator++; ?>
                                      <option value="<?=$indikator->id?>" <?=$indikator->id == $kpi->indikator_id ? "selected" : "";?> >
                                          <?=$indikator->nama_indikator;?>
                                      </option>
                                  <?php endforeach; ?>
                            </select>
                          </td>
                      <td class="table-cell">
                        <form id="<?='form'.$i;?>" action="<?=$action_update_kpi.$kpi->id;?>" method="post"></form>
                        <input form="<?='form'.$i;?>" class="input-block" type="text" value="<?=$kpi->nama_kpi?>" name="nama_kpi">
                      </td>
                    
                      <td class="table-cell">
                          <button class="btn-update" form="<?='form'.$i;?>" >Update</button>
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
$element_modal_message = <<<EOD
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
    "element" => $element_modal_message,
    "button_type" => "btn-info close-modal",
    "button_desc" => "OK"
]); 
?>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php");?>

<script>
    $(document).ready(function(){
        tablePagination('#table-kpi');
    })

    let error = '<?php echo  is_null($error) ? "" : json_encode($error);?>';
    let success = '<?php echo  is_null($success) ? "" : $success;?>';
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
        $("#modal-message .modal-body .success-response .message").append('<p ><b>Berhasil!</b> kpi berhasil di update!</p>');
        $("#modal-message").css("display", "block");  
    }

    $(document).on('hide', '#modal-message', function(){
      if(success){
        window.location.href = success;
      }
    })
</script>