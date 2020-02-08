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
  <!-- <link href=<?php echo base_url()."assets/css/page_form_rencana_kerja.css";?> rel="stylesheet"> -->
</head>

<body>
<?php $this->load->view('template/header.php');?>

<main>
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
<section class="page-content" id="list-rencana-kerja">
    <div class="content-title shadow">
      <div class="page-title">
        <h3><i class="far fa-file-word"></i> Laporan Formulir Hasil Bidang Kinerja Utama</h3></i> 
      </div>
      <div class="margin-left">
      </div>
    </div>

    <ul class="breadcrumb">
        <li>
          <form action="<?=$breadcrumb['url']?>" method="post">
            <input type="hidden" value="<?=$breadcrumb['tahun_crumb'];?>" name="tahun"/>
            <input type="hidden" value="<?=$breadcrumb['institusi_crumb'];?>" name="institusi"/>
            <input type="hidden" value="<?=$breadcrumb['ganjil_crumb']?>" name="ganjil"/>
            <input type="hidden" value="<?=$breadcrumb['genap_crumb']?>" name="genap"/>
            <input type="submit" value="<?=$breadcrumb['name']?>"/>
          </form>
        </li>
    </ul>


    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-table"></i>  Formulir Unit</h4>
        </div>
        <div class="card-body">
          <form class="form-filter" method="post" action="<?php echo base_url()."index.php/formulir_rencana_kerja/get";?>">
            <div class="form-filter-option">
              <h4 for="tahun">Tahun :</h4>
              <select class="toolbar" name="tahun" id="tahun">
                <?php
                  foreach($data_tahun as $tahun){
                      if((int) $selected_tahun == $tahun->tahun){
                          echo "<option value='$tahun->tahun' selected>$tahun->tahun</option>";
                      }else{
                          echo "<option value='$tahun->tahun'>$tahun->tahun</option>";
                      }
                  }
                ?>
              </select>
            </div>
            <div class="form-filter-option">
              <h4>Semester : </h4>
              <div class="toolbar">
                <input  type="checkbox" name="ganjil" id="ganjil" value="1" <?php echo $selected_semester == '1' || $selected_semester == NULL ? "checked"  : "";?>/> Ganjil <br>
              </div>
              <div class="toolbar">
                <input type="checkbox" name="genap" id="genap" value="2" <?php echo $selected_semester == '2' || $selected_semester == NULL ? "checked"  : "";?>/> Genap
              </div>
            </div>
            <?php if($this->session->userdata("hak_akses") == 1) :?>
            <div class="form-filter-option">
              <h4 for="institusi">Institusi : </h4>
              <select class="toolbar" name="institusi" id="institusi">
                <?php 
                    foreach($data_institusi as $institusi){
                        if($selected_institusi_id == $institusi->id ){
                            echo "
                                <option value='$institusi->id' selected>$institusi->nama_institusi</option>
                            ";
                        }else{
                            echo "
                                <option value='$institusi->id'>$institusi->nama_institusi</option>
                            ";
                        }
                    }
                ?>
              </select>
            </div>
            <?php endif;?>
            <div class="content-filter-option">
              <button class="toolbar"><i class="fas fa-search" title="cari"></i></button>
            </div>
          </form action="<?php echo base_url()."index.php/formulir_rencana_kerja/get";?>">

            <!--table topbar -->
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
                <h4>search : </h4> <input id="tableSearch" type="text" value="" placeholder="search..."/>
              </div>
            </div>
            
            <!-- table -content -->
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
                <?php $i=0; foreach($data_formulir_rencana_kerja as $datum): $i++; ?>
                      <?php 
                          $periode_str = "";
                          if( $datum->semester == "2"){
                              $periode_str = "Maret ".(string)((int) $datum->tahun+1)." - Agustus ".(string)((int)$datum->tahun+1);
                          }else{
                              $periode_str = "September ".($datum->tahun)." - Februari ".(string)((int)$datum->tahun+1);
                          }
                          $score_2digit_precision = ceil(number_format((float)$datum->score,2,'.','')); 
                        ?>
                        <tr>
                          <td><?=$i;?></td>
                          <?php $namaUnit = $datum->tenaga_pengajar == "0" ? $datum->formulir_ketua == "1" ? "Ketua ".$datum->nama_unit : "Anggota ".$datum->nama_unit : $datum->formulir_ketua == "1" ? "Ketua ".$datum->nama_unit : "Dosen ".$datum->nama_unit?>
                          <td>
                            <form class="form-list-laporan" action="<?=$action.$datum->id;?>" method="post">
                                <!-- for breadcrum list laporan-->
                                <input type="hidden" value="1" name="show_bread_crumb_list"/>
                                <input type="hidden" value="<?=$breadcrumb['institusi_crumb'];?>" name="list_institusi_crumb"/>
                                <input type="hidden" value="<?=$breadcrumb['tahun_crumb'];?>" name="list_tahun_crumb"/>
                                <input type="hidden" value="<?=$breadcrumb['ganjil_crumb']?>" name="list_ganjil_crumb"/>
                                <input type="hidden" value="<?=$breadcrumb['genap_crumb']?>" name="list_genap_crumb"/>

                                <!-- for breadcrumb detil-->
                                <input type="hidden" value="1" name="show_bread_crumb_detil"/>
                                <input type="hidden" value="<?=$datum->id?>" name="detil_unit_crumb"/>

                                <input type="submit" value="<?=$namaUnit;?>" style="border:0;background:transparent;display:block;"/>
                            </form>
                          </td>
                          <td><?=$datum->nama_user;?></td>
                          <td><?=$periode_str;?></td>
                          <td><?=$score_2digit_precision;?></td>
                        </tr>
                  <?php endforeach; ?>
              </tbody>
            </table>
            
            <!-- table-footer -->
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
