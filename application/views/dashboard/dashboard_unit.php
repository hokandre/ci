<!-- header file html-->
<?php $this->load->view("partials/header.php", ["title" => $title]);?>

<!-- header document -->
<?php $this->load->view('template/header.php');?>

<main>
<!-- sidebar -->
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

<section class="page-content" id="page-kinerja">

    <!-- content title -->
    <?php $this->load->view("dashboard/template/content_title_dashboard_unit.php"); ?>

    <!-- breadcrumb -->
    <?php $this->load->view("dashboard/template/breadcrumb_dashboard_unit.php");?>


    <!-- pie chart and line chart -->
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
                  <h4 style="text-align:center; margin-bottom:10px;"><?=$nama_unit;?></h4>
                  <canvas id="canvas-kinerja-saat-ini"></canvas>
                  <div id="ket-kinerja"></div>
              </div>
            </div>
          </div>
      </div>

      <div class="flex-col-6">
          <!-- card history kinerja unit-->
          <div class="card">
            <div class="card-header card-header-dashboard">
              <h4><i class="fas fa-history"></i> History Kinerja </h4>
              <!-- <form action="<?=$action_lihat_kinerja_unit?>" method="post">
                 <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                 <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                  <select name="renstra_periode" class="toolbar" style="color:var(--dark-green);">
                    <?php $indexRenstraPeriode=0; foreach($data_renstra_periode as $renstra_periode) : $indexRenstraPeriode++; ?>
                        <option value="<?=$renstra_periode->id;?>"><?=$renstra_periode->tahun_awal." - ".$renstra_periode->tahun_akhir;?></option>
                    <?php endforeach;?>
                  </select>
              </form> -->
            </div>
            <div class="card-body">
                <form id="form-statistik-kinerja" action="<?=$action_lihat_kinerja_unit?>" method="post">
                <?php if(isset($mode_individu)) : ?>
                    <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <?php endif;?>

                <?php if(isset($show_bread_crumb)) : ?>
                <?php if($show_bread_crumb == 1 ) :?>
                    <input type="hidden" name="show_bread_crumb" value="<?=$show_bread_crumb;?>"/>
                    <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
                            <input type="hidden" value="<?=$selected_institusi_id;?>" name="institusi_id"/>
                            <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                            <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                    <?php endforeach;?>
                <?php endif;?>
                <?php endif;?>

                 <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
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
    
    <!-- tabel anggota -->
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
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody>
                   <tr>
                       <?php $indexAnggota=0; foreach($data_kinerja_anggota as $data_anggota ): $indexAnggota++;?>
                       <tr>
                           <td><?=$indexAnggota;?></td>
                           <td><?=$data_anggota->nama_user;?></td>
                           <td><?=$data_anggota->score;?></td>
                           <td>
                               <form action="<?=$action_lihat_kinerja_user;?>" method="post">
                               <!-- breadcrumb institusi -->
                                <?php if(isset($show_bread_crumb)) : ?>
                                <?php if($show_bread_crumb == 1 ) :?>
                                    <input type="hidden" name="show_bread_crumb_institusi" value="<?=$show_bread_crumb;?>"/>
                                    <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
                                            <input type="hidden" value="<?=$selected_institusi_id;?>" name="institusi_id"/>
                                            <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id_institusi"/>
                                            <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode_institusi"/>
                                    <?php endforeach;?>
                                <?php endif;?>
                                <?php endif;?>
                                
                                    <input type="hidden" value="1" name="show_bread_crumb_unit"/>
                                    <input type="hidden" value="<?=$selected_unit;?>" name="unit_id">
                                    <input type="hidden" value="<?=$ketua_unit;?>"name="ketua_unit">
                                    <input type="hidden" value="<?=$tahun."-".$semester;?>"name="periode_id">
                                    <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode">
                                    <input type="hidden" value="<?=$data_anggota->user_id;?>" name="user_id">
                                    <input type="hidden" value="<?=$mode_individu;?>" name="mode_individu">
                                    
                                    <input type="submit" class="btn-info" value="Lihat"/>
                               </form>
                           </td>
                       </tr>
                       <?php endforeach;?>
                   </tr>
               </tbody>
           </table>
       </div>
   </div>

    <!-- chart detil -->
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
<?php $this->load->view("template/modal_response.php", ["title" => "Message"]); ?>

<!-- footer -->
<!-- include : ChartJs -->
<?php $this->load->view("partials/footer.php", 
["js" => [
  "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"
  ]
]);?>



<script>
$(document).ready(function(){
        tablePagination('#table-list-anggota-unit');
})
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_kinerja_unit);?>');
let urlUser = JSON.parse('<?php echo json_encode($action_lihat_kinerja_user);?>');
let urlInstitusi = JSON.parse('<?php echo is_null($action_lihat_kinerja_institusi) ? "" : json_encode($action_lihat_kinerja_institusi);?>');

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
let renstraPeriode = JSON.parse('<?php echo  is_null($renstra_periode) ? '' : json_encode($renstra_periode); ?>');
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

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
          yAxes : [
              {
                  ticks : {
                      suggestedMin : 0,
                      suggestedMax : 100,
                      callback : function (value){
                        return value + " %";
                      }
                  }
              }
          ]
      },
      tooltips : {
            displayColors : false,
            callbacks : {
                label: function(tooltipItem, data) {
                    var label = data.datasets[tooltipItem.datasetIndex].label || '';

                    if (label) {
                        label += ': ';
                    }

                    return [
                        label,
                        `pencapaian : ${dataDetilKinerjaSaatIni[tooltipItem.index].total_nilai_aktual}/${dataDetilKinerjaSaatIni[tooltipItem.index].jumlah_user * dataDetilKinerjaSaatIni[tooltipItem.index].target_institusi}`,
                        `jumlah user : ${dataDetilKinerjaSaatIni[tooltipItem.index].jumlah_user}`,
                        `target per user : ${dataDetilKinerjaSaatIni[tooltipItem.index].target_institusi}`,
                        `(${dataDetilKinerjaSaatIni[tooltipItem.index].persen_ketercapaian}%)`
                    ];
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
        
        dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = (statistik.score / statistik.MAX_SCORE) * 100;

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

  
