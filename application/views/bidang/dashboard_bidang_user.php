<!-- HEADER -->
<?php $this->load->view('partials/header.php',[
    "title" => "Dashboard Bidang User"
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-dashboard-bidang">

    <!-- CONTENT TITLE -->
    <?php $this->load->view("form_bidang/template/content_title_dashboard_bidang_user.php",[
        "action_lihat_bidang_user" => $action_lihat_bidang_user,
        //breadcrumb unit
        "show_bread_crumb_unit" => $show_bread_crumb_unit,
        "selected_unit" => isset($selected_unit) ? $selected_unit : NULL,
        "selected_periode_tahun_semetser" => isset($selected_periode_tahun_semetser) ? $selected_periode_tahun_semetser : NULL,
        "selected_renstra_periode" => isset($selected_renstra_periode) ? $selected_renstra_periode : NULL,
        "ketua_unit" => isset($ketua_unit) ? $ketua_unit : NULL,

        //breadcrumb institusi
        "show_bread_crumb_institusi" => $show_bread_crumb_institusi,
        "selected_institusi" => isset($selected_institusi) ? $selected_institusi : NULL,
        "selected_periode_tahun_semester_institusi" => isset($selected_periode_tahun_semester_institusi) ? $selected_periode_tahun_semester_institusi : NULL,
        "selected_renstra_periode_institusi" => isset($selected_renstra_periode_institusi) ? $selected_renstra_periode_institusi : NULL,
        "selected_bidang" => isset($selected_bidang) ? $selected_bidang : NULL,

        "selected_user" => $selected_user,
        "selected_periode_tahun_semetser" => $selected_periode_tahun_semetser,
        "selected_renstra_periode" => $selected_renstra_periode,
        "data_bidang" => $data_bidang,

        "action_lihat_bidang_user" => $action_lihat_bidang_user,
        "mode_individu" => $mode_individu,
        "data_unit" => isset($data_unit) ? $data_unit : NULL,
    ]);?>

    <!-- BREADCRUMB -->
    <?php $this->load->view("form_bidang/template/breadcrumb_dashboard_bidang_user.php",[
        //breadcrumb institusi
        "show_bread_crumb_institusi" => $show_bread_crumb_institusi,
        "selected_institusi" => isset($selected_institusi) ? $selected_institusi : NULL,
        "selected_periode_tahun_semester_institusi" => isset($selected_periode_tahun_semester_institusi) ?$selected_periode_tahun_semester_institusi : NULL,
        "selected_renstra_periode_institusi" => isset($selected_renstra_periode_institusi) ? $selected_renstra_periode_institusi : NULL,
        "selected_bidang" => isset($selected_bidang) ? $selected_bidang : NULL,

        //breadcrumb unit
        "show_bread_crumb_unit" => isset($show_bread_crumb_unit) ? $show_bread_crumb_unit : NULL,
        "selected_unit" => isset($selected_unit) ? $selected_unit : NULL,
        "selected_periode_tahun_semetser" => isset($selected_periode_tahun_semetser) ? $selected_periode_tahun_semetser : NULL,
        "selected_renstra_periode" => isset($selected_renstra_periode) ? $selected_renstra_periode : NULL,
        "ketua_unit" => isset($ketua_unit) ? $ketua_unit : NULL,

        //breadcrumb user
        "selected_ketua_unit" => isset($selected_ketua_unit) ? $selected_ketua_unit : NULL,
        "selected_periode_tahun_semester_unit" => isset($selected_periode_tahun_semester_unit) ? $selected_periode_tahun_semester_unit : NULL,
        "selected_renstra_periode_unit" => isset($selected_renstra_periode_unit) ? $selected_renstra_periode_unit : NULL
    ]);?>

    <div class="flex-row">
        <!-- pie chart -->
        <div class="flex-col-6">
            <div class="card">
                <div class="card-header">
                <?php 
                    $tahun = explode("-", $selected_periode_tahun_semetser)[0];
                    $semester = explode("-", $selected_periode_tahun_semetser)[1];
                    $keteranganperiode = "";
                    if($semester == "1") {
                    $keteranganperiode = "September ".($tahun)." - Februari ".($tahun+1);
                    }else{
                    $keteranganperiode = "Maret ".($tahun+1)." - Agustus ".($tahun+1);
                    }
                ?>
                <h4><i class="fas fa-tachometer-alt"></i> 
                    Pencapaian Bidang  User Periode (<?=$keteranganperiode;?>)
                </h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative;">
                        <h4 style="text-align:center; margin-bottom:10px;"><?=$nama_user;?></h4>
                        <canvas id="canvas-kinerja-saat-ini"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- line chart -->
        <div class="flex-col-6">
            <div class="card">
                <div class="card-header card-header-dashboard">
                <h4><i class="fas fa-history"></i> History Kinerja </h4>
                </div>
                <div class="card-body">
                    <form id="ubah-periode" action="<?=$action_lihat_bidang_user;?>" method="post">
                        <!-- for bread crum unit -->
                        <?php if(isset($show_bread_crumb_unit)) : ?>
                        <?php if($show_bread_crumb_unit == "1" ) :?>
                            <input type="hidden" name="show_bread_crumb_unit" value="1"/>
                            <input type="hidden" name="unit_id" value="<?=$selected_unit;?>">
                            <input type="hidden" name="periode_id_unit" value="<?=$selected_periode_tahun_semetser;?>"/>
                            <input type="hidden" name="renstra_periode_unit" value="<?=$selected_renstra_periode;?>"/>
                            <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                        <?php endif;?>
                        <?php endif;?>
                        <!-- for bread crumb institusi -->
                        <?php if(isset($show_bread_crumb_institusi)) : ?>
                        <?php if($show_bread_crumb_institusi == "1" ) :?>
                            <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                            <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                            <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                            <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                            <input type="hidden" name="bidang_id" value="<?=$selected_bidang;?>"/>                  
                        <?php endif;?>
                        <?php endif;?>

                        <input type="hidden" name="user_id" value="<?=$selected_user;?>"/>
                        <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
                        <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                        <input type="hidden" name="bidang_id" value="<?=$selected_bidang;?>"/>
                        <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                        <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
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
            <h4><i class="fas fa-info"></i> Detil KPI Unit</h4>
        </div>

        <div class="card-body">
            <div class="chart-container" style="position: relative;">
                <canvas id="canvas-detil-kinerja-saat-ini"></canvas>
            </div>
        </div>
    </div>

    </section>
</main>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php",[
    "js" => [
        "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js",
        
    ]
]);?>

<script>
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_bidang_unit);?>');
let urlInstitusi = JSON.parse('<?php echo json_encode($action_lihat_bidang_institusi);?>');
let urlUser =  JSON.parse('<?php echo json_encode($action_lihat_bidang_user);?>');
//data untuk pie chart
let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja);?>');
//data untuk bar chart
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_detil_kinerja)?>');
//data untuk line chart
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

let keteranganPeriode = JSON.parse('<?php echo json_encode($keteranganperiode);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($selected_obj_renstra_periode) ? "" : json_encode($selected_obj_renstra_periode); ?>');


$(document).on('click', 'input[name="versi"]', function(){
  let versi = $(this).val();
  if(versi == "unit"){
    $(this).parent("form#form-versi").attr("action", urlUnit);
  }else{
    $(this).parent("form#form-versi").attr("action", urlUser);
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
  $('#ubah-unit input[name="ketua_unit"]').val(ketuaValue);
  $('#ubah-unit').submit();
})

//form : ubah - bidang 
$(document).on('change', 'select[name="bidang_id"]', function(){
    $('#ubah-bidang').submit();
})

//form : ubah-institusi
$(document).on('change', 'select[name="institusi_id"]', function(){
    $('#ubah-institusi').submit();
})

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
    options : {
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
            label: `Pencapaian Kinerja Unit (${keteranganPeriode})`,
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
                    suggestedMax : 100,
                    suggestedMin : 0,
                    callback : function(value){
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
   $('#ubah-periode').find('input[name="periode_id"]').val(periodeId);
   $('#ubah-periode').submit();

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
            "MAX_SCORE" : 100
        }
        dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = (statistik.score);
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