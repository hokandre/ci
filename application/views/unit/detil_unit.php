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
<section class="page-content" id="page-indikator">

    <div class="content-title">
      <div class="page-title">
        <h3> <i class="fas fa-university"></i> Unit <?=$unit_selected->nama_unit;?></h3> 
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

</body>

<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<script>
    $(document).ready(function(){
        tablePagination('#table-unit');
    })
</script>
</html>