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
<section class="page-content" id="page-kinerja">
    <div class="content-title">
      <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Kinerja Unit 

        <!-- lihat sebagai individu -->
        <form id="form-unit" action="<?=$action_lihat_kinerja_user;?>" method="post" style="display:inline;">
          <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
          <select name="unit_id" class="toolbar toolbar-white">
            <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
              <option 
                  value="<?=$unit->unit_id;?>" 
                  ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                  <?= $ketua_unit == $unit->ketua ? "selected" : "";?>>
                <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
              </option>
            <?php endforeach;?>
          </select>
        </form>

        <?php if($this->session->userdata("ketua_unit") != null) : ?>
            <?php if($ketua_unit != '1' && $this->session->userdata("ketua_unit") == $selected_unit): ?>
                <!-- lihat sebagai unit atau user-->
                <form id="form-versi" method="post" style="display:inline;">
                <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                <input type="hidden" value="" name="unit_id"/>
                <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
                </form>
            <?php endif;?>
        <?php endif;?>

        
      </h3> 
      </div>
    </div>
    <!-- data kinerja unit -->
    <div class="flex-row">
      <div class="flex-col-6">
          <!-- card kinerja unit-->
          <div class="card">
            <div class="card-header">
              <?php 
                $keteranganperiode = "";
                if($semester == "1") {
                  $keteranganperiode = "September ".($tahun)." - Februari ".($tahun+1);
                }else{
                  $keteranganperiode = "Maret ".($tahun+1)." - Agustus ".($tahun+1);
                }
              ?>
              <h4><i class="fas fa-tachometer-alt"></i> Kinerja Unit Periode (<?=$keteranganperiode;?>)</h4>
            </div>
            <div class="card-body">
              <div class="chart-container" style="position: relative;">
                  <canvas id="canvas-kinerja-saat-ini"></canvas>
              </div>
            </div>
          </div>
      </div>

      <div class="flex-col-6">
          <!-- card history kinerja unit-->
          <div class="card">
            <div class="card-header">
              <h4><i class="fas fa-history"></i> History Kinerja </h4>
            </div>
            <div class="card-body">
              <h4>Test</h4>
            </div>
          </div>
      </div>
    </div>

    <div class="card" style="margin-top: 30px;">
       <div class="card-header">
           <h4> <i class="fas fa-users"></i> Pencapaian Anggota</h4>
       </div>
       <div class="card-body">
           <!-- table topbar -->
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

           <table id="table-list-anggota-unit">
               <thead>
                   <tr>
                       <th>No</th>
                       <th>Nama Anggota</th>
                       <th>Pencapaian</th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <?php $indexAnggota=0; foreach($data_kinerja_anggota as $data_anggota ): $indexAnggota++;?>
                       <tr>
                           <td><?=$indexAnggota;?></td>
                           <td><?=$data_anggota->nama_user;?></td>
                           <td><?=$data_anggota->score;?></td>
                       </tr>
                       <?php endforeach;?>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>


    <div class="card" style="margin-top: 30px;">
      <div class="card-header">
        <h4><i class="fas fa-info"></i> Detil Pencapaian Key Performance Indicator</h4>
      </div>

      <div class="card-body">
        <div class="chart-container" style="position: relative;">
            <canvas id="canvas-detil-kinerja-saat-ini"></canvas>
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
<!-- ChartJs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js" crossorigin="anonymous"></script>

<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<script>
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_kinerja_unit);?>');
let urlUser = JSON.parse('<?php echo json_encode($action_lihat_kinerja_user);?>');

$(document).on('change', 'select[name="unit_id"]', function(){
  let ketuaValue = $(this).find(":selected").attr("ketua-unit");
  $('input[name="ketua_unit"]').val(ketuaValue);

  $(this).parent('form').submit();
})

//fetch data by form versi when change radio button
$(document).on('click', 'input[name="versi"]', function(){
  let versi = $(this).val();
  let unitId = $('#form-unit').find(':selected').val();
  $('form#form-versi').find('input[name="unit_id"]').val(unitId);

  if(versi == "unit"){
    $(this).parent("form#form-versi").attr("action", urlUnit);
  }else{
    $(this).parent("form#form-versi").attr("action", urlUser);
  }

  $(this).parent("form#form-versi").submit();
})

$(document).on('change', 'select[name="unit_id"]', function(){
  let ketuaValue = $(this).find(":selected").attr("ketua-unit");
  $('input[name="ketua_unit"]').val(ketuaValue);

  $(this).parent('form').submit();
})

let colors = [ 
     '#4661EE',
     '#EC5657',
     '#1BCDD1',
     '#8FAABB',
     '#B08BEB',
     '#3EA0DD',
     '#F5A52A',
     '#23BFAA',
     '#FAA586',
     '#EB8CC6',
     "#2F4F4F",
    "#008080",
    "#2E8B57",
    "#3CB371",
    "#90EE90"
  ];

let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja_saat_ini);?>');
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($detil_kinerja_saat_ini)?>');
let keteranganPeriode = JSON.parse('<?php echo json_encode($keteranganperiode);?>');

var canvasKinerjaSaatIni = $('#canvas-kinerja-saat-ini');
var myPieChartKinerjaSaatIni = new Chart(canvasKinerjaSaatIni, {
    type: 'pie',
    data: {
        labels: ['Tercapai', 'Belum Tercapai'],
        datasets: [{
            label: 'Pencapaian Kinerja',
            data: [dataKinerjaSaatIni.tercapai, dataKinerjaSaatIni.tidak_tercapai],
            backgroundColor: [
                '#024314',
                '#F7A991',
            ],
            borderColor: [
                '#FFFFFF',
                '#FFFFFF'
            ],
            borderWidth: 1
        }]
    }
});

let canvasDetilKinerjaSaatIni = $('#canvas-detil-kinerja-saat-ini');
let myBarChartDetilKinerjaSaatIni = new Chart(canvasDetilKinerjaSaatIni, {
    type: 'bar',
    data: {
        labels: dataDetilKinerjaSaatIni.map(el => el.nama_kpi),
        datasets: [{
            label: `Key Performance Indicator (${keteranganPeriode})`,
            data: dataDetilKinerjaSaatIni.map(el => el.persen_ketercapaian),
            backgroundColor:colors.slice(0, dataDetilKinerjaSaatIni.length),
            borderColor: colors.slice(0, dataDetilKinerjaSaatIni.length),
            borderWidth: 1
        }]
    },
    options : {
      scales: {
          xAxes: [{
              gridLines: {
                  offsetGridLines: true
              }
          }],
          yAxes : [
              {
                  ticks : {
                      suggestedMax : 100
                  }
              }
          ]
      }
    }
});

</script>
</html>

  
