<!-- header file html-->
<?php $this->load->view("partials/header.php", ["title" => $title]);?>

<!-- header document -->
<?php $this->load->view('template/header.php');?>
<main>
<!-- sidebar -->
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

<section class="page-content" id="page-kinerja">

    <!-- content title -->
    <?php $this->load->view("dashboard/template/content_title_dashboard_user.php", [
      "action_lihat_kinerja_user" => $action_lihat_kinerja_user,
      "mode_individu" => isset($mode_individu) ? $mode_individu : NULL,
      "ketua_unit" => $ketua_unit,
      "data_unit" => $data_unit,
      "selected_unit" => $selected_unit,
      "user_id" => $user_id,
      "renstra_periode" => $renstra_periode,
      "versi" => $versi,
      "action_lihat_kinerja_institusi" => $action_lihat_kinerja_institusi
    ]);?>

    <!-- breadcrumb -->
    <?php $this->load->view("dashboard/template/breadcrumb_dashboard_user.php"); ?>
    
    <!-- content -->
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
<?php $this->load->view("template/modal_response.php", ["title" => "Message"]); ?>

<!-- footer -->
<!-- include : ChartJs -->
<?php $this->load->view("partials/footer.php", 
["js" => [
  "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"
  ]
]);?>

<script>
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_kinerja_unit);?>');
let urlUser = JSON.parse('<?php echo json_encode($action_lihat_kinerja_user);?>');
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


  
