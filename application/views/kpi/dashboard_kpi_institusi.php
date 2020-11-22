<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "Dashboard KPI Insitusi",
]);?> 

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-dashboard-bidang">

        <!-- CONTENT TITLE -->    
        <?php $this->load->view("kpi/template/content_title_dashboard_kpi_institusi.php", [
            "action_lihat_kpi_institusi" => $action_lihat_kpi_institusi,
            "selected_institusi" => $selected_institusi,
            "selected_periode_tahun_semetser" => $selected_periode_tahun_semetser,
            "selected_renstra_periode" => $selected_renstra_periode,
            "data_sumber" => $data_sumber,
            "selected_sumber" => $selected_sumber,
            "data_institusi" => $data_institusi,
            "selected_institusi" => $selected_institusi,
            "action_lihat_kpi_user" => $action_lihat_kpi_user
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
                    Pencapaian Bidang Periode (<?=$keteranganperiode;?>)
                </h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative;">
                        <h4 style="text-align:center; margin-bottom:10px;"><?=$nama_institusi;?></h4>
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
                    <form id="ubah-periode" action="<?=$action_lihat_kpi_institusi;?>" method="post">
                    <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                    <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                    <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                    <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
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
            <h4> <i class="fas fa-users"></i> Pencapaian Unit Anggota</h4>
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

            <table id="table-list-unit-anggota-institusi">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Unit</th>
                        <th>Pencapaian</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php $indexAnggota=0; foreach($data_kinerja_anggota as $data_anggota ): $indexAnggota++;?>
                        <tr>
                            <td><?=$indexAnggota;?></td>
                            
                            <td>
                                <?php 
                                        switch($data_anggota->tenaga_pengajar){
                                            case "1" : {
                                            echo $data_anggota->formulir_ketua == "0" ? "Dosen ".$data_anggota->nama_unit  : "Ketua ".$data_anggota->nama_unit; 
                                                break;
                                            }
                                            case "0" : {
                                                echo $data_anggota->formulir_ketua == "0" ? "Anggota ".$data_anggota->nama_unit :  "Ketua ".$data_anggota->nama_unit;
                                                break;
                                            }
                                        }
                                ?>
                            </td>
                            <td><?=($data_anggota->score / $data_anggota->MAX_SCORE) *100;?></td>
                            <td>
                                <form id="lihat-unit" action="<?=$action_lihat_kpi_unit;?>" method="post">
                                        <input type="hidden" name="unit_id" value="<?=$data_anggota->unit_id;?>"/>
                                        <input type="hidden" name="ketua_unit" value="<?=$data_anggota->formulir_ketua;?>"/>
                                        <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                                        <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                                        <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
                                    <!-- for bread crumb -->
                                        <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                                        <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semetser;?>"/>
                                        <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode?>"/>
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

    <div class="card" style="margin-top: 30px;">
        <div class="card-header">
            <h4><i class="fas fa-info"></i> Detil Pencapaian Masing - Masing Unit</h4>
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
<?php $this->load->view("partials/footer.php", [
    "js" => [
        "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js"
    ]
]) ;?>


<script>
$(document).ready(function(){
    tablePagination('#table-list-unit-anggota-institusi');
})
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_kpi_unit);?>');
let urlInstitusi = JSON.parse('<?php echo json_encode($action_lihat_kpi_institusi);?>');
//data untuk pie chart
let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja);?>');
//data untuk bar chart
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_detil_kinerja)?>');
//data untuk line chart
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

let keteranganPeriode = JSON.parse('<?php echo json_encode($keteranganperiode);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($selected_obj_renstra_periode) ? "" : json_encode($selected_obj_renstra_periode); ?>');

//form : ubah - bidang 
$(document).on('change', 'select[name="sumber_id"]', function(){
    $('#ubah-sumber').submit();
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
        labels: dataDetilKinerjaSaatIni.map(el => {
            return el.tenaga_pengajar == "1" ? 
                el.formulir_ketua == "1" ? "Ketua " + el.nama_unit : "Dosen "+el.nama_unit
            :
                el.formulir_ketua == "1" ? "Ketua " + el.nama_unit : "Anggota "+el.nama_unit;      
        }),
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
                        `pencapaian : ${dataDetilKinerjaSaatIni[tooltipItem.index].score}/${dataDetilKinerjaSaatIni[tooltipItem.index].MAX_SCORE}`,
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
            "actual_score" : statistik.nilai_ketercapaian_institusi,
            "MAX_SCORE" : statistik.MAX_SCORE
        }

        dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = (statistik.nilai_ketercapaian_institusi / statistik.MAX_SCORE) * 100;
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
