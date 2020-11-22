<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Dashboard User [versi : BPM]"
]);?> 
<main>
  <!-- SIDEBAR -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

  <section class="page-content" id="page-kinerja">
      <!-- CONTENT TITLE -->    
      <?php $this->load->view("dashboard/template/content_title_dashboard_bpm.php");?>
    
      <!-- bread crumb -->
      <?php $this->load->view("dashboard/template/breadcrumb_dashboard_user.php");?>

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
              <div class="card-header">
                <h4><i class="fas fa-history"></i> History Kinerja </h4>
              </div>
              <div class="card-body">
                  <form id="form-statistik-kinerja" action="<?=$action_lihat_kinerja_user?>" method="post">
                        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
                      <?php if($crum["type"] == "unit") :?>
                        <input type="hidden" name="show_bread_crumb_unit" value="1"/>
                      <?php else : ?>
                        <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi_id;?>">
                        <input type="hidden" name="periode_id_institusi" value="<?=$selected_institusi_periode_id;?>">
                        <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_institusi_renstra_periode;?>">
                      <?php endif;?>
                      <?php endforeach;?>
                      <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                      <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                      <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                      <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                      <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                      <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                    <div class="chart-container" style="position: relative;">
                      <canvas   id="canvas-statistik-kinerja"></canvas>
                    </div>
                </form>
              </div>
            </div>
        </div>

      </div>

      <!-- card detil kinerja -->
      <div class="card" style="margin-top: 30px;">
        <div class="card-header">
          <h4><i class="fas fa-info"></i> Pencapaian Key Performance Indicator</h4>
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
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_kinerja_unit); ?>');
let urlInstitusi = JSON.parse('<?php echo json_encode($action_lihat_kinerja_institusi);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($renstra_periode) ? '' : json_encode($renstra_periode); ?>');
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

//switch dash board unit - institusi
$(document).on('change', '#switch-dashboard', function(){
  let dashboardType = $(this).find(':selected').val();
  if(dashboardType == 'unit'){
    $(this).parent('form').attr("action", urlUnit);
  }else{
    $(this).parent('form').attr("action", urlInstitusi);
  }

  $(this).parent('form').submit();
})

//change unit
$(document).on('change', 'select[name="unit_id"]', function(){
  let ketuaValue = $(this).find(":selected").attr("ketua-unit");
  $('input[name="ketua_unit"]').val(ketuaValue);

  $(this).parent('form').submit();
})

let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja);?>');
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_detil_kinerja)?>');
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
                '#DD5144'
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
          yAxes : [{
            ticks:{
              suggestedMax : 100,
              suggestedMin : 0,
              callback : function (value){
                        return value + " %";
              }
            }
          }]
      },tooltips : {
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

  
