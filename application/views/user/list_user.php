<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "List User"
]);?> 

<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
  <section class="page-content" id="page-indikator">
      <!-- CONTENT TITLE -->    
      <?php $this->load->view("user/template/content_title_list_user.php", [
        "action_form_add" => $action_form_add
      ]);?>
     
      <!-- bread crumb -->
      <?php $this->load->view("user/template/breadcrumb_list_user.php",[
        "breadcrumb" => $breadcrumb
      ]);?>

      <div class="card">
        <div class="card-header">
          <h4><i class="fas fa-table"></i> Daftar Pengguna</h4>
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
          
          <table id="table-user">
            <colgroup>
                <col style="width: 20px;">
                <col style="width: 30%;">
                <col style="width: 30%;">
                <col style="width: 20%;">
                <col style="width: 10%;">
            </colgroup>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Pengguna</th>
                <th>Unit</th>
                <th>Institusi</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php $i=0; foreach($data_user as $user): $i++;?>
                  <tr>
                    <td><?=$i;?></td>
                    <td>
                      <form id="<?="'form$user->id'"?>" action="<?=$action_update_user.$user->id?>" method="post">
                        <input class="input-block" type="text" name="nama_user" value="<?=$user->nama_user;?>">
                      </form>
                    </td>
                    <td>
                      <select  form ="<?="'form$user->id'"?>" class="input-block" name="unit_id" id="unit_id">
                          <?php $indexUnit=0; foreach($data_unit as $unit): $indexUnit++;?>
                              <option value="<?=$unit->id?>" <?=$unit->id == $user->unit_id ? "selected" : "";?> >
                                  <?=$unit->nama_unit;?>
                              </option>
                          <?php endforeach;?>
                      </select>
                    </td>
                    <td>
                      <p><?=$user->nama_institusi;?></p>
                    </td>
                    <td>
                      <select form="<?="'form$user->id'"?>" name="status" id="status" class="input-block">
                        <option value="1" <?=$user->status == '1' ? 'selected' : '';?>>Aktif</option>
                        <option value="0" <?=$user->status == '0' ? 'selected' : '';?>>Tidak Aktif</option>
                      </select>
                    </td>
                    <td>
                      <button form="<?="'form$user->id'"?>" class="btn-update">update</button>
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
//modal response
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
</div>
";

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
<?php $this->load->view("partials/footer.php");?>

<script>
$(document).ready(function(){
    tablePagination('#table-user');
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
    $("#modal-message .modal-body .success-response .message").append('<p><b>Berhasil!</b> data user telah di update!</p>');
    $("#modal-message").css("display", "block");  
}

$(document).on('hide','#modal-message', function(){
  if(success){
    window.location.href = success;
  }
})
</script>


  
