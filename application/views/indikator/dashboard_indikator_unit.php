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
        <section class="page-content" id="page-dashboard-bidang">
            <div class="content-title">
                <div class="page-title">
                    <h3> <i class="fas fa-tachometer-alt"></i> Pencapain S. Strategis
                    <form id="ubah-institusi" method="post" style="display: inline-block;">
                        <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                        <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                        <select name="institusi_id" id="institusi_id" class="toolbar toolbar-white" <?= $this->session->userdata("hak_akses") != 1 ? "disabled": "";?>>
                            <?php $indexInstitusi=0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                                <option <?=($selected_institusi == $institusi->id) ? "selected" : "";?> value="<?=$institusi->id?>"> <?=$institusi->nama_institusi;?></option>
                            <?php endforeach;?>
                        </select>
                    </form>

                    <?php if($this->session->userdata("hak_akses") == 1 && $mode_individu == false) :?>
                        <form id="ubah-unit" action="<?=$action_lihat_indikator_unit;?>" style="display: inline-block;" method="post">
                            <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                            <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                            <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                            <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                            <select name="unit_id" class="toolbar toolbar-white">
                                <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                                <?php if($unit->jumlah_anggota == 0) :?>
                                    <option ketua-unit="1" value="<?=$unit->id;?>" <?= $unit->id == $selected_unit && $ketua_unit == "1" ? "selected" : "";?> >
                                        <?=$unit->nama_unit;?>
                                    </option>

                                <?php else :?>
                                    <option ketua-unit="1" value="<?=$unit->id;?>" <?= $unit->id == $selected_unit && $ketua_unit == "1" ? "selected" : "";?> >
                                    <?="Ketua ".$unit->nama_unit;?>
                                    </option>
                                    <option ketua-unit="0" value="<?=$unit->id;?>" <?= $unit->id == $selected_unit && $ketua_unit == "0" ? "selected" : "";?> >
                                    <?=$unit->tenaga_pengajar == "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                                    </option>
                                <?php endif; ?>
                                <?php endforeach;?>
                            </select>
                        </form>
                    <?php else : ?>
                    <form id="ubah-unit" action="<?=$action_lihat_indikator_unit;?>" style="display: inline-block;" method="post">
                        <?php if(isset($mode_individu)) : ?>
                        <?php if($mode_individu) : ?>
                            <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                        <?php endif;?>
                        <?php endif;?>

                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                        <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                        <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                        <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                        <select name="unit_id" class="toolbar toolbar-white">
                            <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                                <option 
                                    view="<?=$unit->view;?>"
                                    value="<?=$unit->unit_id;?>" 
                                    ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                                    <?= $ketua_unit == $unit->ketua && $selected_unit == $unit->unit_id ? "selected" : "";?>>
                                    <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                                </option>
                            <?php endforeach;?>
                        </select>
                    </form>
                    <?php endif;?>

                    <?php if($ketua_unit != '1' && $this->session->userdata("ketua_unit") == $selected_unit && $this->session->userdata("unit_id") == $selected_unit) : ?>
                        <!-- lihat sebagai unit atau user-->
                        <form id="form-versi" method="post" style="display:inline;">
                            <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                            <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                            <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                            <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
                        </form>
                    <?php endif;?>
                </h3> 
                </div>
                <div class="margin-left">
                    <?php if($mode_individu == false && $this->session->userdata("hak_akses") == 1) : ?>
                    <form method="post" action="<?=$action_lihat_indikator_user;?>">
                        <input type="hidden" name="mode_individu" value="true"/>
                        <button class="toolbar" title="Lihat pencapaian indikator" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pribadi</button>
                    </form>
                    <?php endif;?>
                </div>
            </div>

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
                        Pencapaian Sasaran Strategis Unit Periode (<?=$keteranganperiode;?>)
                    </h4>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative;">
                            <h4 style="text-align:center; margin-bottom:10px;"><?=$nama_unit;?></h4>
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
                        <form id="ubah-periode" action="<?=$action_lihat_indikator_unit;?>" method="post">
                            <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                            <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                            <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                            <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
                            <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
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
                <h4> <i class="fas fa-users"></i> Pencapaian Anggota Unit</h4>
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
                                <td>
                                   <?=$data_anggota->nama_user;?>
                                </td>
                                <td><?=$data_anggota->score;?></td>
                                <td>
                                    <form id="lihat-user" action="<?=$action_lihat_indikator_user;?>" method="post">
                                        <input type="hidden" name="user_id" value="<?=$data_anggota->user_id;?>"/>
                                        <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
                                        <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                                        <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                                        <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                                        <!-- for bread crum unit -->
                                        <input type="hidden" name="show_bread_crumb_unit" value="1"/>
                                        <input type="hidden" name="unit_id_crumb" value="<?=$selected_unit;?>">
                                        <input type="hidden" name="periode_id_crumb" value="<?=$selected_periode_tahun_semetser;?>"/>
                                        <input type="hidden" name="renstra_periode_crumb" value="<?=$selected_renstra_periode;?>"/>
                                        <input type="hidden" name="ketua_unit_crumb" value="<?=$ketua_unit;?>"/>
                                        <input type="hidden" name="institusi_id_crumb" value="<?=$selected_institusi;?>"/>
                                        <input type="submit" class="btn-info" value="Lihat">
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
$(document).ready(function(){
        tablePagination('#table-list-unit-anggota-institusi');
})
let urlUnit = JSON.parse('<?php echo json_encode($action_lihat_indikator_unit);?>');
let urlUser = JSON.parse('<?php echo json_encode($action_lihat_indikator_user);?>');
//data untuk pie chart
let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja_saat_ini);?>');
//data untuk bar chart
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_detil_kinerja_saat_ini)?>');
//data untuk line chart
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_kinerja_statistik); ?>');

let keteranganPeriode = JSON.parse('<?php echo json_encode($keteranganperiode);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($selected_obj_renstra_periode) ? "" : json_encode($selected_obj_renstra_periode); ?>');

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

//form : ubah - unit 
$(document).on('change', 'select[name="unit_id"]', function(){
    let ketuaValue = $(this).find(":selected").attr("ketua-unit");
    let view = $(this).find(":selected").attr("view");
    if(view){
        if(view == "unit") $(this).parent('form').attr("action", urlUnit)
        else $(this).parent('form').attr("action", urlUser)
    }
    $('#ubah-unit').find('input[name="ketua_unit"]').val(ketuaValue);
    $('#ubah-unit').submit();
})

//form : ubah-institusi
$(document).on('change', 'select[name="institusi_id"]', function(){
    $('#ubah-institusi').submit();
})

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
        labels: dataDetilKinerjaSaatIni.map(el => el.nama_indikator),
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
                        `pencapaian : ${dataDetilKinerjaSaatIni[tooltipItem.index].total_nilai_aktual}/${dataDetilKinerjaSaatIni[tooltipItem.index].MAX_SCORE}`,
                        `jumlah user : ${dataDetilKinerjaSaatIni[tooltipItem.index].jumlah_user}`,
                        `jumlah kpi : ${dataDetilKinerjaSaatIni[tooltipItem.index].jumlah_kpi}`,
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
            "actual_score" : statistik.nilai_pencapaian,
            "MAX_SCORE" : statistik.MAX_SCORE
        }

        dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = (statistik.nilai_pencapaian / statistik.MAX_SCORE) * 100;
    })

    let MyLineChartStatistikKinerja = new Chart(canvasStatistikKinerja, {
        type: 'line',
        data: {
            labels: labelsMyLineChartStatistikKinerja ,
            datasets: [{
                label: `${startYear} - ${akhirYear}`,
                data: Object.values(dataMyLineChartStatistikKinerja),
                backgroundColor:colors.slice(0, colors.length),
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