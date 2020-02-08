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
  <link href=<?php echo base_url()."assets/css/page_detil_indikator.css";?> rel="stylesheet"/>
</head>

<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="page-detil-indikator">

    <div class="content-title">
      <div class="page-title">
        <h3><i class="fas fa-bullseye"> </i> Indikator / Sasaran Strategis</h3> 
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
        <h4><i class="fas fa-table"></i> Daftar Unit</h4>
      </div>
      <div class="card-body">
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
<!-- Own js file for current page setting -->
<script src=<?php echo base_url()."assets/js/page_detil_indikator.js";?>></script>
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
</html>

  
