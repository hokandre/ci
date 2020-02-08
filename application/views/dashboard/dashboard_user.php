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
        <h3> <i class="fas fa-tachometer-alt"></i> Kinerja Individu Sebagai 
        <!-- lihat sebagai individu -->
        <form id="form-unit" action="<?=$action_lihat_kinerja_user;?>" method="post" style="display:inline;">
          <?php if(isset($mode_individu)) : ?>
          <?php if($mode_individu) : ?>
            <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
          <?php endif;?>
          <?php endif;?>

          <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
          <select name="unit_id" class="toolbar toolbar-white">
            <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
              <option 
                  view="<?=$unit->view;?>"
                  value="<?=$unit->unit_id;?>" 
                  ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                  <?= $ketua_unit == $unit->ketua && $unit->unit_id == $selected_unit ? "selected" : "";?>>
                <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
              </option>
            <?php endforeach;?>
          </select>
        </form>

        <?php if($ketua_unit != '1' && $this->session->userdata('ketua_unit') == $selected_unit && $this->session->userdata('unit_id') == $selected_unit) : ?>
        <!-- lihat sebagai unit atau user-->
        <form id="form-versi" method="post" style="display:inline;">
         <?php if(isset($mode_individu)) : ?>
          <?php if($mode_individu) : ?>
            <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
          <?php endif;?>
          <?php endif;?>

          <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
          <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
          <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
          <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
          <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
          <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
        </form>
      <?php endif;?>
      </h3> 
      </div>
      <?php if($this->session->userdata("hak_akses") == 1 ) :?>
        <div class="margin-left">
          <a class="toolbar" title="lihat pencapaian kinerja" href="<?=$action_lihat_kinerja_institusi;?>" ><i class="fas fa-plus-circle"></i> Pencapaian</a>
        </div>
      <?php endif;?>
    </div>


    <!-- breadcrumb -->
    <?php if(isset($show_bread_crumb)) : ?>
    <?php if($show_bread_crumb == 1 ) :?>
    <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <li>
              <form action="<?=$crum['url']?>" method="post">
                 <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                 <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                 <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                 <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                 <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                 <input type="submit" value="<?=$crum['name']?>"/>
              </form>
            </li>
        <?php endforeach;?>
    </ul>
    <?php endif;?>
    <?php endif;?>
    
    <!-- data kinerja individu -->
    <div class="flex-row">
      <div class="flex-col-6">
          <!-- card kinerja individu-->
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
              <h4><i class="fas fa-tachometer-alt"></i> Kinerja Saat ini (<?=$keteranganperiode;?>)</h4>
            </div>
            <div class="card-body">
              <div class="chart-container" style="position: relative;">
                  <h4 style="text-align:center; margin-bottom:10px;"><?=$nama_user;?></h4>
                  <canvas id="canvas-kinerja-saat-ini"></canvas>
              </div>
            </div>
          </div>
      </div>

      <div class="flex-col-6">
          <!-- card history kinerja-->
          <div class="card">
            <div class="card-header card-header-dashboard">
              <h4><i class="fas fa-history"></i> History Kinerja </h4>
              <!-- <form action="<?=$action_lihat_kinerja_user?>" method="post">
                 <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                 <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                 <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                  <select name="renstra_periode" class="toolbar" style="color:var(--dark-green);">
                    <?php $indexRenstraPeriode=0; foreach($data_renstra_periode as $renstra_periode) : $indexRenstraPeriode++; ?>
                        <option value="<?=$renstra_periode->id;?>"><?=$renstra_periode->tahun_awal." - ".$renstra_periode->tahun_akhir;?></option>
                    <?php endforeach;?>
                  </select>
              </form> -->
            </div>
            <div class="card-body">
              <form id="form-statistik-kinerja" action="<?=$action_lihat_kinerja_user?>" method="post">
                <?php if(isset($mode_individu)) : ?>
                <?php if($mode_individu) : ?>
                  <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <?php endif;?>
                <?php endif;?>

                <?php if(isset($show_bread_crumb)) : ?>
                <?php if($show_bread_crumb == 1 ) :?>
                    <input type="hidden" name="show_bread_crumb" value="<?=$show_bread_crumb;?>"/>
                    <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
                            <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                            <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                            <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                            <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                            <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                          </form>
                        </li>
                    <?php endforeach;?>
                <?php endif;?>
                <?php endif;?>

                 <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                 <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                 <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                 <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                 <input type="hidden" value="" name="periode_id"/>
                 <div class="chart-container" style="position: relative;">
                   <canvas id="canvas-statistik-kinerja"></canvas>
                  </div>
                </form>
            </div>
          </div>
      </div>

    </div>

    <div class="card" style="margin-top: 30px;">
      <div class="card-header">
        <h4><i class="fas fa-info"></i> Detil Ketercapain Kinerja</h4>
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
let urlUser = JSON.parse('<?php echo json_encode($action_lihat_kinerja_unit);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($renstra_periode) ? '' : json_encode($renstra_periode); ?>');
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

$(document).on('click', 'input[name="versi"]', function(){
  let versi = $(this).val();
  let unitId = $('#form-unit').find(':selected').val();
  $('form#form-versi').find('input[name="unit_id"]').val(unitId);

  if(versi == "unit"){
    $(this).parent("form#form-versi").attr("action", urlUnit);
  }else{
    $(this).parent("form#form-versi").attr("action", urlUnit);
  }

  $(this).parent("form#form-versi").submit();
})

$(document).on('change', 'select[name="unit_id"]', function(){
  let ketuaValue = $(this).find(":selected").attr("ketua-unit");
  let view = $(this).find(":selected").attr("view");
  if(view){
    if(view == "unit") $(this).parent('form').attr("action", urlUnit)
    else $(this).parent('form').attr("action", urlUser)
  }
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
        labels: [
          `Tercapai  : ${dataKinerjaSaatIni.actual_score}/${dataKinerjaSaatIni.max_score} (${dataKinerjaSaatIni.tercapai}%)`,
          `Belum Tercapai : ${dataKinerjaSaatIni.minus_score}/${dataKinerjaSaatIni.max_score} (${dataKinerjaSaatIni.tidak_tercapai}%)`

        ],
        datasets: [{
            label: 'Pencapaian Kinerja',
            data: [dataKinerjaSaatIni.tercapai, dataKinerjaSaatIni.tidak_tercapai],
            backgroundColor: [
                '#1DA462',
                '#DD5144',
            ],
            borderColor: [
                '#FFFFFF',
                '#FFFFFF'
            ],
            borderWidth: 1
        }]
    },
    options :{
      legend : {
          fullWidth : true,
          position : 'bottom',
          labels :{
              usePointStyle : true,
              fontStyle : "bold",
              fontColor : "black"
          }
      },
      tooltips : {
          callbacks : {
              label: function(tooltipItem, data) {
                  return data.labels[tooltipItem.index] || "";
              }
          }
      }
    }
});

let canvasDetilKinerjaSaatIni = $('#canvas-detil-kinerja-saat-ini');
let myBarChartDetilKinerjaSaatIni = new Chart(canvasDetilKinerjaSaatIni, {
    type: 'bar',
    data: {
        labels: dataDetilKinerjaSaatIni.map(el => el.nama_kpi),
        datasets: [{
            label: `Key Performance Indicator (${keteranganPeriode})`,
            data: dataDetilKinerjaSaatIni.map(el => el.persen_ketercapaian > 100 ? 100 : el.persen_ketercapaian),
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
          yAxes : [{
            ticks: {
              suggestedMin : 0,
              suggestedMax : 100,
              callback : function (value){
                          return value + " %";
              }
            }
          }]
      },
      tooltips : {
            callbacks : {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label) {
                        label += ': ';
                    }

                    label += `Nilai aktual : ${dataDetilKinerjaSaatIni[tooltipItem.index].nilai_aktual}/ Target Institusi : ${dataDetilKinerjaSaatIni[tooltipItem.index].target_institusi} (${dataDetilKinerjaSaatIni[tooltipItem.index].persen_ketercapaian}%)`
                    return label;
                }
            }
      }
    }
});

let canvasStatistikKinerja = $('#canvas-statistik-kinerja');
let dataMyLineChartStatistikKinerja = {};
let labelsMyLineChartStatistikKinerja = [];
let dataScoreMyLineChartStatistikKinerja = new Array(10).fill({ "actual_score" : 0, "MAX_SCORE" : 0});

//click event line chart
const getKinerjaUnit = (event, array) => {
   let indexChartClicked = array[0]._index;
   let incrementTahun = Math.floor( indexChartClicked / 2 );
   let semester = indexChartClicked % 2;

   let startYear = Number(renstraPeriode.tahun_awal);
   let akhirYear = Number(renstraPeriode.tahun_akhir);
   //0 berarti semester ganjil
   let periodeId = `${startYear+incrementTahun}-${semester == 0 ? "1" : "2"}`;
   $('#form-statistik-kinerja').find('input[name="periode_id"]').val(periodeId);
   $('#form-statistik-kinerja').submit();

}

if(renstraPeriode){
    let startYear = Number(renstraPeriode.tahun_awal);
    let akhirYear = Number(renstraPeriode.tahun_akhir);
    for(let tahun = startYear; tahun <= akhirYear;tahun++){
        labelsMyLineChartStatistikKinerja.push(`${tahun} ( Sem.1 )`);
        labelsMyLineChartStatistikKinerja.push(`${tahun} ( Sem.2 )`);
        
        dataMyLineChartStatistikKinerja[`${tahun}-${1}`] = 0;
        dataMyLineChartStatistikKinerja[`${tahun}-${2}`] = 0;
    }

    dataStatistikKinerja.forEach(statistik => {
        let index  = (Number(statistik.tahun)-startYear )* 2;
        if(statistik.semester == 2){
            index++;
        }

        dataScoreMyLineChartStatistikKinerja[index] = {
            "actual_score" : statistik.score,
            "MAX_SCORE" : statistik.MAX_SCORE
        }

        dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = statistik.score;
    })

    let MyLineChartStatistikKinerja = new Chart(canvasStatistikKinerja, {
        type: 'line',
        data: {
            labels: labelsMyLineChartStatistikKinerja ,
            datasets: [{
                label: `${startYear} - ${akhirYear}`,
                data: Object.values(dataMyLineChartStatistikKinerja),
                backgroundColor:colors.slice(0, dataDetilKinerjaSaatIni.length),
                borderColor: '#4F8CF5',
                borderWidth: 1,
                fill : false
            }]
        },
        options : {
          onClick : getKinerjaUnit,
          scales: {
              xAxes: [{
                  gridLines: {
                      offsetGridLines: true
                  }
              }],
              yAxes : [
                  {
                      ticks : {
                          suggestedMax : 100,
                          suggestedMin : 0,
                          callback : function (value){
                                return value + " %";
                          }
                      }
                  }
              ]
          },
          tooltips : {
              callbacks : {
                  label: function(tooltipItem, data) {
                      var label = data.datasets[tooltipItem.datasetIndex].label || '';

                      if (label) {
                          label += ': ';
                      }

                      label += `${dataScoreMyLineChartStatistikKinerja[tooltipItem.index].actual_score}/${dataScoreMyLineChartStatistikKinerja[tooltipItem.index].MAX_SCORE} (${(dataScoreMyLineChartStatistikKinerja[tooltipItem.index].actual_score / dataScoreMyLineChartStatistikKinerja[tooltipItem.index].MAX_SCORE) * 100}%)`
                      return label;
                  }
              }
          }
        }
    });
}

</script>
</html>

  