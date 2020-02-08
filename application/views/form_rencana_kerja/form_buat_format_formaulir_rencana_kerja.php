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
  <link href=<?php echo base_url()."assets/css/page_form_buat_rencana_kerja.css";?> rel="stylesheet">

  <style>
    td[name="col-sumber"] {
        width: 100px;
    }
    td[name="col-bidang"] {
        width: 100px;
    }
    td[name="col-indikator"] {
        width: 300px;
    }   

    td[name="col-kpi"] input[name="kpi"]{
        display: block;
        width: 100%;
    }   


    td[name="col-indikator"] select {
       width: 100%;
       max-width: 300px;
    }

  </style>

  <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>
</head>

<body>
<!-- header -->
<?php $this->load->view('template/header.php');?>

<main>
<!-- sidebar-->
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

<section class="page-content" id="page-list-rencana-kerja-baru">

    <!-- page title-->
    <div class="content-title">
        <div class="page-title">
            <h3> <i class="far fa-file-word"></i> Buat Formulir Bidang Hasil Kinerja Utama</h3>
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
            <h4><i class="fas fa-table"></i>  Formulir</h4>
        </div>
        <div class="card-body">
            <div class="form-filter">

              <div class="form-filter-option">
                  <h4 for="tahun">Tahun : </h4>
                   <!-- BTN Modal Tahun -->
                  <i id="btn-toggle-modal-tahun" modal-target="modal-tahun" class="far fa-question-circle btn-toggle-modal" title="tambah data tahun"></i>
                  <select class="toolbar" name="tahun" id="tahun">
                      <?php
                      foreach($data_tahun as $tahun){
                          if((int) $selected_tahun->tahun == $tahun->tahun){
                              echo "<option value='$tahun->tahun' selected>$tahun->tahun</option>";
                          }else{
                              echo "<option value='$tahun->tahun'>$tahun->tahun</option>";
                          }
                      }
                      ?>
                  </select>
                </div> 
                <!--Semester-->
              <div class="form-filter-option">
                <h4>Semester : </h4>
                  <div class="toolbar">
                      <input class="toolbar" name="semester" type="radio" value="1" id="ganjil"/>
                      <label class="form-check-label" for="ganjil">
                              Ganjil(<?php echo " September ".$selected_tahun->tahun." - Februari ".(($selected_tahun->tahun)+1); ?> )
                      </label>
                  </div>
                  <divt class="toolbar">
                      <input class="toolbar" name="semester" type="radio" value="2" id="genap">
                      <label class="form-check-label" for="genap">
                              Genap(<?php echo " Maret ".$selected_tahun->tahun." - Agustus ".$selected_tahun->tahun; ?> )
                      </label>
                  </divt>
              </div>

            </div>

      
            <table id="table-form-rencana-kerja-baru">
                <thead>
                    <tr>
                        <th>Sumber</th>
                        <th>Bidang</th>
                        <th style="width:73px;">Bobot</th>
                        <th>Sasaran Strategis/Indikator</th>
                        <th>KPI</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="row-1">
                        <td name="col-sumber">
                          <select name="sumber">
                                  <option value="renstra" selected>Renstra</option>
                                  <option value="renop">Renop</option>
                                  <option value="mutu" selected>Sasaran Mutu</option>
                          </select>
                        </td>
                        <td name="col-bidang">
                          <select name="bidang" >
                                  <?php $i=0; foreach($data_bidang as $bidang): $i++; ?>
                                  <option value="<?=$bidang->id;?>"><?=$bidang->nama_bidang;?></option>
                                  <?php endforeach;?>
                          </select>
                        </td>
                        <td name="col-bobot">
                            <input type="text" name="bobot" id="bobot"/>
                        </td>
                        <td name="col-indikator">
                          <select name="indikator">
                              <?php
                                  foreach($data_indikator as $row){
                                      echo "<option class='text-truncate' value='$row->id'> $row->nama_indikator </option>";
                                  } 
                              ?>
                           </select>
                        </td>
                        <td name="col-kpi">
                            <div class="dropdown">
                                <input data-kpi-id="" data-nama-kpi="" data-bidang-kpi="" class="dropdown-input" type="text" name="kpi"/>
                                <div class="dropdown-content">
                                   
                                </div>
                            </div>
                        </td>
          
                        <td class="table-cell" name="col-unit">
                          <i modal-target="modal-unit" class="far fa-edit btn-toggle-modal btn-toggle-modal-unit toolbar toolbar-white"> Unit</i>
                          <span title="hapus baris" class="toolbar" name="hapus-baris">&times;</span>
                          <ul class="tag-list">
                          </ul>

                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex-row justify-space-between align-items-center">
                <button id="btn-post" class="btn-info">Post</button>
                <button class="toolbar">
                    <i  id="tambahBaris" class="far fa-plus-square" title="tambah baris"> ( baris ) </i>
                </button>
            </div>
            
        </div>      
    </div>
    
    
    <?php 
        $unit_amik = [];
        $unit_stmik = [];
        $unit_stie = [];
        $unit_umum = []; 
        foreach($data_unit as $unit){
            if((int) $unit->institusi_id == 1){
                array_push($unit_amik, $unit);
            }elseif((int) $unit->institusi_id == 2){
                array_push($unit_stmik, $unit);
            }elseif((int) $unit->institusi_id == 3){
                array_push($unit_stie, $unit);
            }else{
                array_push($unit_umum, $unit);
            }
        }
        
    ?>
    <!-- institusi box-->
    <?php 

        $data_amik = [
            "institusi_id" => 1,
            "nama_institusi" => "AMIK",
            "unit_insitusi" => $unit_amik
        ];
        $this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_amik);

        $data_stmik = [
            "institusi_id" => 2,
            "nama_institusi" => "STMIK",
            "unit_insitusi" => $unit_stmik
        ];
        $this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_stmik);
        
        $data_stie = [
            "institusi_id" => 3,
            "nama_institusi" => "STIE",
            "unit_insitusi" => $unit_stie
        ];
        $this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_stie);

        $data_umum = [
            "institusi_id" => 4,
            "nama_institusi" => "UMUM",
            "unit_insitusi" => $unit_umum
        ];
        $this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_umum);

    ?>
</section>
</main>

<!-- modal unit -->
<div id="modal-unit" class="modal">

    <div class="modal-content-md">

        <div class="modal-header">
            <h4> <i class="fas fa-school"></i> Daftar Unit</h4>
            <span class="close-modal">&times;</span>
        </div>

        <div class="modal-body">
            <input type="hidden" name="row-number"/>

            <ul class="tag-list"></ul>
            
            <div class="dropdown" id="dropdown-list-unit">
                <h4 style="margin-bottom:10px;">Nama Unit :</h4>
                <input id="nama-unit" type="text" class="dropdown-input input-block" name="nama-unit" placeholder="Masukan Nama Unit"/>

                <div class="dropdown-content">
                    <?php foreach($data_unit as $unit){
                        if((int) $unit->jumlah_anggota == 0) {?>
                            <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="<?php echo $unit->ketua_unit;?>" data-nama-unit="<?php echo $unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                <?php echo "Ketua ".$unit->nama_unit; ?>
                            </p>
                        <?php } else { ?>
                                <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="<?php echo $unit->ketua_unit;?>" data-nama-unit="<?php echo "Ketua ".$unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                    <?php echo "Ketua ".$unit->nama_unit; ?>
                                </p>
                                <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="" data-nama-unit="<?php echo $unit->tenaga_pengajar == "0" ? "Anggota " : "Dosen ".$unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                    <?php echo $unit->tenaga_pengajar == "0" ? "Anggota " : "Dosen ".$unit->nama_unit;?>
                                </p>
                        <?php } ?>
                    <?php }?>
                </div>

            </div>

            <h4 style="margin-bottom:10px;">Target Institusi: </h4>
            <input class="input-block" type="number" value="1" placeholder="1" name="target"  id="target"/>

            <h4 style="margin-bottom:10px;"> Satuan :</h4>
            <select name="satuan" id="satuan" class="input-block" value="persen">
                <option value="persen">Persen</option>
                <option value="satuan">Buah (Desimal)</option>
                <option value="satuan bulat">Buah (Bulat)</option>
                <option value="orang">Orang</option>
            </select>
        </div>

        <div class="modal-footer">
            <button id="btn-save" disabled class="btn-info">save</button>
        </div>
    </div>
</div>

<!-- modal tahun -->
<div class="modal" id="modal-tahun">
    <div class="modal-content-sm">
        <div class="modal-header">
            <h4> <i class="far fa-calendar-plus"></i> Tahun</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text center">
            <input type="hidden" name="tahun"/>
            <p>Tambahkan data tahun <span id="tahun-selanjutnya"></span> ?</p>
        </div>

        <div class="modal-footer">
            <button class="btn-update" id="btn-save">save</button>
        </div>
    </div>
</div>


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

<div id="error"></div>


</body>
<!-- Jquery Core-->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Jquery UI -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<script src=<?php echo base_url()."assets/js/page_form_buat_format_formaulir_rencana_kerja.js";?>></script>

<!-- data client -->
<script type="text/javascript">

let kamus_indikator = JSON.parse('<?php echo json_encode($data_kamus_indikator); ?>');
let unit = JSON.parse('<?php echo json_encode($data_unit);?>');
let indikator = JSON.parse('<?php echo json_encode($data_indikator);?>');
let baseUrl = '<?php echo base_url();?>';
let url_fetch_kpi = '<?php echo $action_get_kpi;?>';
let url_add_tahun = '<?php echo $action_add_tahun;?>';

setInputFilter($('input[name="bobot"]'), function(value) {
        return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});
</script>
</html>
