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
<section class="page-content" id="page-kpi">

    <div class="content-title">
      <div class="page-title">
        <h3> <i class="far fa-lightbulb"></i> Key Performance Indicator (KPI)</h3> 
      </div>
      <div class="margin-left">
        <form method="post" action="<?=$action_lihat_pencapaian_kpi;?>">
          <button class="toolbar" title="Lihat pencapaian kpi" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pencapaian</button>
        </form>
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
</html>