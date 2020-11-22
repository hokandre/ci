function drawPieChartKinerjaSaatIni(elChart, { nilai_tercapai = 0, nilai_maksimal = 0,nilai_tidak_tercapai = 0, persen_tercapai = 0, persen_tidak_tercapai = 0 }){
   return new Chart(elChart, {
        type: 'pie',
        data: {
            labels: [
                `Tercapai  : ${nilai_tercapai}/${nilai_maksimal} (${persen_tercapai}%)`,
                 `Belum Tercapai : ${nilai_tidak_tercapai}/${nilai_maksimal} (${persen_tidak_tercapai}%)`
            ],
            datasets: [{
                label: 'Pencapaian Kinerja',
                data: [persen_tercapai, persen_tidak_tercapai],
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
}

function drawBarChartDetilKinerjaInstitusi(elChart, dataDetil = [], { keteranganPeriode = "Data Periode Belum Ada" }){
    return new Chart(elChart, {
        type: 'bar',
        data: {
            labels: dataDetil.map(el => el.nama_unit),
            datasets: [{
                label: `Pencapaian Kinerja Unit (${keteranganPeriode})`,
                data: dataDetil.map(el => el.persen_tercapai),
                backgroundColor:colors.slice(0, dataDetil.length),
                borderColor: colors.slice(0, dataDetil.length),
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
                          callback :function(value){
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
                            `pencapaian : ${dataDetil[tooltipItem.index].nilai_tercapai}/${dataDetil[tooltipItem.index].nilai_maksimal}`,
                            `jumlah user : ${dataDetil[tooltipItem.index].jumlah_user}`,
                            `jumlah kpi : ${dataDetil[tooltipItem.index].jumlah_kpi}`,
                            `(${dataDetil[tooltipItem.index].persen_tercapai}%)`
                        ];
                    }
                }
          }
        }
    });
}

function drawBarChartDetilKinerjaUnit(elChart, dataDetil = [], { keteranganPeriode = "Data Periode Belum Ada"}){
    return new Chart(elChart, {
        type: 'bar',
        data: {
            labels: dataDetil.map(el => el.nama_kpi),
            datasets: [{
                label: `Pencapaian Kinerja Unit (${keteranganPeriode})`,
                data: dataDetil.map(el => el.persen_tercapai),
                backgroundColor:colors.slice(0, dataDetil.length),
                borderColor: colors.slice(0, dataDetil.length),
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
                              return value+" %";
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
                            `pencapaian : ${dataDetil[tooltipItem.index].nilai_tercapai}/${dataDetil[tooltipItem.index].jumlah_formulir * dataDetil[tooltipItem.index].nilai_maksimal }`,
                            `target unit : ${dataDetil[tooltipItem.index].nilai_maksimal}`,
                            `target user : ${dataDetil[tooltipItem.index].target_institusi}`,
                            `(${dataDetil[tooltipItem.index].persen_tercapai}%)`
                        ];
                    }
                }
          }
        }
    });
}

function drawLineChartStatistikKinerja(elChart, data_statistik = []){
    let dataMyLineChartStatistikKinerja = {};
    let labelsMyLineChartStatistikKinerja = [];
    let dataScoreMyLineChartStatistikKinerja = new Array(10).fill({ "actual_score" : 0, "MAX_SCORE" : 0});
    //click event line chart
    const getKinerjaUnit = (event, array) => {
        let indexChartClicked = array[0]._index;
        let incrementTahun = Math.floor( indexChartClicked / 2 );
        let semester = indexChartClicked % 2;
    
        let startYear = Number(renstraPeriode.tahun_awal);
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
    
        data_statistik.forEach(statistik => {
            let index  = (Number(statistik.tahun)-startYear )* 2;
            if(statistik.semester == 2){
                index++;
            }
    
            dataScoreMyLineChartStatistikKinerja[index] = {
                "actual_score" : statistik.nilai_ketercapaian_institusi,
                "MAX_SCORE" : statistik.MAX_SCORE
            }
    
            dataMyLineChartStatistikKinerja[`${statistik.tahun}-${statistik.semester}`] = (statistik.nilai_ketercapaian_institusi / statistik.MAX_SCORE ) * 100;
        })
    
        return new Chart(elChart, {
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
}