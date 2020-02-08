<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Rencana Kerja</title>
  <!--Global CSS-->
  <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet">
  <!-- Just for this page css -->
  <link href=<?php echo base_url()."assets/css/page_form_rencana_kerja.css";?> rel="stylesheet">
</head>

<body>
<?php $this->load->view('template/header.php');?>
<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="list-rencana-kerja">

  <!--Page Title -->
  <div class="content-title">
    <div class="page-title">    
      <h3><i class="far fa-file-word"></i> Formulir Bidang Hasil Kinerja Utama</h3> 
    </div>
    <div class="margin-left">
      <?php if($this->session->userdata('hak_akses') == 1 ) :?>
        <a class="toolbar" title="buat formulir baru" href="<?=$action_buat_format_formulir;?>"><i class="fas fa-plus-circle"></i > Formulir</a>
        <a class="toolbar" title="edit formulir" href="<?=$action_get_format_formulir;?>"><i class="fas fa-marker"></i> Formulir</a>
        <?php endif;?>
        <?php if( ($this->session->userdata('hak_akses') == 4) ||  ($this->session->userdata('hak_akses') == 1) ) :?>
          <a class="toolbar" title="cari laporan" href="<?=$action_cari_laporan;?>"><i class="fas fa-search-plus"></i> Laporan</a>
        <?php endif;?>
    </div>
  </div>

  <!-- breadcrumb -->
  <ul class="breadcrumb">
    <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
        <li><a href="<?=$crum['url'];?>"><?=$crum['name'];?></a></li>
    <?php endforeach;?>
  </ul>

  
  <!-- card laporan-->
  <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-table"></i>  Formulir Sendiri</h4>
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
        
            <table id="listRencanaKerja">
              <thead>
                <tr>
                    <th>No</th>
                    <th>Unit</th>
                    <th>User</th>
                    <th>Periode</th>
                    <th>Score</th>
                </tr>
              </thead>
              <tbody>
                
              <?php $i=0; foreach($data_formulir_rencana_kerja as $datum) : $i++; 
                      
                        $periode_str = "";
                        if( $datum->semester == "2"){
                            $periode_str = "Maret ".(string)((int) $datum->tahun+1)." - Agustus ".(string)((int)$datum->tahun+1);
                        }else{
                            $periode_str = "September ".$datum->tahun." - Februari ".(string)((int)$datum->tahun+1);
                        }
                        $score_2digit_precision = ceil(number_format((float)$datum->score,2,'.','')); 
                        ?>
                        <tr>
                        <td><?=$i?></td>
                        <td> <a style="color:blue;text-decoration:underline;" href="<?php echo $action.$datum->id;?>" title="Lihat detil"><?= $datum->formulir_ketua == 1 ? "Ketua ".$datum->nama_unit : $datum->nama_unit;?></a></td>
                        <td><?=$datum->nama_user?></td>
                        <td><?=$periode_str?></td>
                        <td><?=$score_2digit_precision?></td>
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
<script src=<?php echo base_url()."assets/js/page_form_rencana_kerja.js";?>></script>
</html>
