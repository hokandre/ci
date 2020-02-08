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
  <link href=<?php echo base_url()."assets/css/page_indikator.css";?> rel="stylesheet"/>
</head>

<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="page-indikator">

    <div class="content-title">
      <div class="page-title">
        <h3> <i class="fas fa-bullseye"></i> List Unit </h3> 
      </div>
    </div>

    <div class="card">
      <div class="card-header card-header-dashboard">
        <h4><i class="fas fa-table"></i> Daftar Unit</h4>
          <form id="form-versi" action="<?=$action_lihat;?>" method="get">
              <select name="versi" class="toolbar" style="color:var(--dark-green);">
                  <option value="indikator" <?= $versi == "indikator" ? "selected" : "";?>>Indikator</option>
                  <option value="unit"<?= $versi == "unit" ? "selected" : "";?> >Unit</option>
              </select>
          </form>
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
        
        <table class="" id="list-indikator">
          <colgroup>
              <col class="w-5">
              <col class="w-55">
              <col class="w-15">
              <col class="w-15">
              <col class="w-10">
          </colgroup>
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Unit</th>
              <th>Institusi</th>
              <th>Jenis</th>
              <th style="text-align: center;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $i=0; foreach ($data_unit as $unit) : $i++;?>
            <tr>
                <td style="padding: 15px;"><?=$i;?></td>
                <td style="padding: 15px;"><?=$unit->nama_unit;?></td>
                <td style="padding: 15px;"><?=$unit->nama_institusi;?></td>
                <td style="padding: 15px;"><?=($unit->tenaga_pengajar == 0) ? "Operasional" : "Tenaga Pengajar";?></td>
                <td style="text-align: center;"><a href="<?=$action_lihat_indikator_unit."?unit=$unit->id";?>" class="btn-info"> detil</a></td>
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
</body>

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<script src=<?php echo base_url()."assets/js/page_indikator.js";?>></script>
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
</html>

  
