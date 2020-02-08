/*
NAMA PAGE : 
page_form_buat_rencana_kerja.php

DESKRIPSI PAGE :
page ini digunakan untuk menambahkan daftar rencana kerja untuk semua unit yang ada 
pengiriman data menggunakan AJAX.

BAGIAN PAGE :
1. TABEL #table-form-rencana-kerja-baru
    Tabel ini akan berfurngsi sebagai formulir rencana kerja.
2. Modal #modal-unit
    Modal ini berfungsi untuk melakukan perubahan dan penambah data unit pada level baris di tabel dan berefek pada
    beserta CARD No.3 | 4 | 5
3. Card STMIK #{idInstitusi dari db}
4. Card STIE #{idInstitusi dari db}
5. Card AMIK #{idInstitusi dari db}
*/

$(document).ready(function(){
    $(".table-cell").resizable();
})

let tableRowTemplate = $('#table-form-rencana-kerja-baru tbody tr:last-child').clone();
/* Funsi Untuk Komponen Di Tabel #table-form-rencana-kerja-baru */
//1. Button tambah baris
$('#tambahBaris').on('click', function(){
    let lastRowId = $('#table-form-rencana-kerja-baru tbody tr:last-child').attr("id").split("-");
    lastRowId[1] = Number(lastRowId[1])+1;
    let newRowId = lastRowId.join("-");
    let newRow = $(tableRowTemplate).clone().attr("id", newRowId);
    $("#table-form-rencana-kerja-baru tbody").append(newRow);
})

$('span[name="hapus-baris"]').on('click', function(){
    let sizeTableRow = $('#table-form-rencana-kerja-baru tbody').children().length;
    if(sizeTableRow > 1){
        $(this).closest("tr").remove();
    }
})

//2. Selection tahun
$(document).on('change', '#tahun', function(event){
    let tahun = Number(event.target.value);
    let stringGenap = `Genap( Maret ${tahun} - Agustus ${tahun} )`;
    let stringGanjil = `Ganjil( September ${tahun} - Februari ${tahun+1} )`;
    $("label[for='ganjil']").text(stringGanjil);
    $("label[for='genap']").text(stringGenap);
});

//3. selection indicator
$(document).on('change', '#table-form-rencana-kerja-baru select[name="indikator"]',function(){
    $(this).parent().next().find("input[name='kpi']").val('');
    $(this).parent().next().find("input[name='kpi']").attr('data-kpi-id','');
    $(this).parent().next().find("input[name='kpi']").attr('data-nama-kpi','');
    $(this).parent().next().find("input[name='kpi']").attr('data-bidang-kpi','');

})

//4. Button Open modal
$(document).on("click",".btn-toggle-modal-unit",function(){
    let rowId = $(this).closest('tr').attr("id");
    $('#modal-unit input[type="hidden"]').val(rowId);
    $("#modal-unit .tag-list").empty();
    let listUnit = $(this).closest('tr').find('.tag-list').children().clone().each(function(){
        $(this).removeClass("btn-toggle-modal");
        $(this).removeClass("btn-toggle-modal-unit");
    })
    listUnit.appendTo('#modal-unit .tag-list');
    //clear input di modal
    $('#modal-unit input[name="nama-unit"]').val('');
    $('#modal-unit input[name="nama-unit"]').attr("data-unit-id", '');
    $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit", '');
    $('#modal-unit input[name="target"]').val(1);

})

//5. button open modal tahun
$(document).on('click', '#btn-toggle-modal-tahun', function(){
    let maxTahun = 0;
    $("select[name='tahun']").children().each(function(){
        if(maxTahun == 0){
            maxTahun = Number($(this).val());
        }else{
            if(maxTahun < Number($(this).val())){
                maxTahun = Number($(this).val());
            }
        }
    })

    //data tahun selanjutnya
    maxTahun++;

    $("#modal-tahun input[type='hidden'][name='tahun']").val(maxTahun);
    $("#modal-tahun span#tahun-selanjutnya").text(maxTahun);
})

//modal tahun
$(document).on('click', '#modal-tahun #btn-save', function(){
    $.ajax({
        method : 'post',
        url : url_add_tahun,
        data : {
           'tahun' : $("#modal-tahun input[type='hidden'][name='tahun']").val()
        }
    })
        .done(data => {
            window.location.href = data.redirect;
        })
})


$(document).on('change', 'select[name="sumber"]', function(){
    let tr = $(this).closest('tr');
    let rowId = $(tr).attr("id");
    $(`#table-form-rencana-kerja-baru tr#${rowId} .tag-list .tag-list-item`).trigger("change");
})
$(document).on('change', 'input[name="bobot"]', function(){
    let tr = $(this).closest('tr');
    let rowId = $(tr).attr("id");
    $(`#table-form-rencana-kerja-baru tr#${rowId} .tag-list .tag-list-item`).trigger("change");
})
$(document).on('change', 'select[name="bidang"]', function(){
    let tr = $(this).closest('tr');
    let rowId = $(tr).attr("id");
    $(`#table-form-rencana-kerja-baru tr#${rowId} .tag-list .tag-list-item`).trigger("change");
})

//5. fetch kpi ke server ketika input text kpi diketik
function delay(fn, ms){
    let timer = 0
    return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}

$(document).on("keyup", '#table-form-rencana-kerja-baru tr input[name="kpi"]', function(){
    $(this).attr("data-kpi-id","");
    $(this).attr("data-nama-kpi","");
    if($(this).val()){
        $(this).attr("data-kpi-id","");
        $(this).attr("data-nama-kpi",$(this).val());
        delay(()=>{
            let indikator = $(this).parent().parent().prev().find('select[name="indikator"]').val();
            let url_fetch = url_fetch_kpi+`?indikator=${indikator}&name=${$(this).val()}`;
            $.ajax({
                url : url_fetch
            })
            .done(response => {
                let array_kpi = JSON.parse(response);
                if(array_kpi.length == 0){
                    $(this).next('.dropdown-content').css("display", "none");
                }else{
                    array_kpi.forEach(kpi => {
                        let kpiSudahAda = false;
                        $(this).next(".dropdown-content").children().each(function(){
                            if(Number($(this).attr("data-kpi-id") == Number(kpi.id))){
                                kpiSudahAda = true
                            }
                        })
                        if(!kpiSudahAda){
                            $(this).next(".dropdown-content").append(`<p class="dropdown-content-item" data-kpi-id="${kpi.id}" data-nama-kpi="${kpi.nama_kpi}" data-bidang-kpi="${kpi.bidang_id}">${kpi.nama_kpi}</p>`)
                        }
                    })
                }
            })
        }, 2000)();
    }

    let parentTr = $(this).closest('tr');
    $(parentTr)
        .find('[name="col-unit"]')
        .find('.tag-list-item')
        .each(function(){
            $(this).trigger('change');
        })

    //cek kpi sudah ada
    let indikatorValue = $(this).closest('tr').find('td[name="col-indikator"]').find("select[name='indikator']").find(":selected").val();
    let kpiVlue = $(this).val();
    let tableTbody = $('#table-form-rencana-kerja-baru tbody');
    let tr = $(this).closest("tr");
    let indexTr = tableTbody.find(tr).index();
    let kpiSudahAda = false;
    $('input[name="kpi"]').each(function(){
        
        let currentIndex = tableTbody.find($(this).closest("tr")).index();
        // cek kpi sama
        if( ($(this).val() == kpiVlue) && (indexTr !== currentIndex) ){
            let indikatorValueCurrent = $(this).closest("tr").find('td[name="col-indikator"]').find("select[name='indikator']").find(":selected").val();
            if(indikatorValue == indikatorValueCurrent){
                kpiSudahAda = true;
                return;
            }
        }
    })

    if(kpiSudahAda){
        alert("Kpi ini sudah ada!");
    }
})

//6. pilih hasil fetch pada list dropdown dan set value ke input text
$(document).on('click', '#table-form-rencana-kerja-baru .dropdown .dropdown-content .dropdown-content-item', function(){
    let kpiVlue = $(this).attr("data-nama-kpi");
    let tableTbody = $('#table-form-rencana-kerja-baru tbody');
    let tr = $(this).closest("tr");
    let indexTr = tableTbody.find(tr).index();
    let kpiSudahAda = false;
    $('input[name="kpi"]').each(function(){
        
        let currentIndex = tableTbody.find($(this).closest("tr")).index();
        if( ($(this).val() == kpiVlue) && (indexTr !== currentIndex) ){
            kpiSudahAda = true;
            return;
        }
    })

    if(kpiSudahAda){
        alert("Kpi ini sudah ada!");
        let value = "";
        if(tr.find('td[name="col-kpi"').find('input[name="kpi"]').attr("old-value")){
            value = tr.find('td[name="col-kpi"').find('input[name="kpi"]').attr("old-value")
        }
        tr.find('td[name="col-kpi"').find('input[name="kpi"]').val(value);
        event.preventDefault();
        event.stopPropagation();
        return false;
    }else {
        //triger change
        $(this).closest('tr')
            .find('[name="col-unit"]')
            .find('.tag-list-item')
            .each(function(){
                $(this).trigger('change');
            })
    
        $(this).parent().prev("input[name='kpi']").attr("data-kpi-id", $(this).attr("data-kpi-id"));
        $(this).parent().prev("input[name='kpi']").attr("data-nama-kpi", $(this).attr("data-nama-kpi"));
        $(this).parent().prev("input[name='kpi']").attr("data-bidang-kpi", $(this).attr("data-bidang-kpi"));
        //change select bidang
        let bidangValue = $(this).parent().prev("input[name='kpi']").attr("data-bidang-kpi");
        $(this).parent().parent().parent().prev().prev().find('select[name="bidang"]').val(bidangValue);
    }
    

})


//7. li item unit pada tabel customer listener for change and delete
$(document).on('add', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){
    let rowId = $(this).parent().parent().parent().attr("id");
    let idInstitusi = 'institusi-' + $(this).attr("data-institusi-id");
    let unitId = $(this).attr("data-unit-id");
    let ketuaUnit = $(this).attr("data-ketua-unit");
    let target = $(this).attr("data-target");
    let satuan = $(this).attr("data-satuan");
    let namaKpi = $(this).closest('tr').find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-nama-kpi");
    let idKpi = $(this).closest('tr').find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-kpi-id");
    let indikatorId = $(this).closest('tr').find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').val();
    let namaIndikator = $(this).closest('tr').find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').text();
    let bidangId = $(this).closest('tr').find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').val();
    let namaBidang = $(this).closest('tr').find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').text();
    let sumberId = $(this).closest('tr').find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').val();
    let namaSumber = $(this).closest('tr').find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').text();
    let bobot = $(this).closest('tr').find('td[name="col-bobot"]').find("input[name='bobot']").val();

    let simbolSatuan = '%';
    switch(satuan) {
        case 'orang' :
            simbolSatuan = 'org';
            break;
        case 'satuan' :
            simbolSatuan = 'Buah (Desimal)';
            break;
        case 'satuan bulat' :
            simbolSatuan = 'Buah (Bulat)';
            break;    
        default :
            simbolSatuan = simbolSatuan;
            break;
    }

    $(`#${idInstitusi} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`)
    .next('.panel')
    .children('.table')
    .append(`
        <div class="table-row" data-row-id="${rowId}">
            <div class="table-cell" name="col-sumber"><p data-sumber-id="${sumberId}" data-nama-sumber="${namaSumber}">${namaSumber}</p></div>
            <div class="table-cell" name="col-bidang"><p data-bidang-id="${bidangId}" data-nama-bidang="${namaBidang}">${namaBidang}</p></div>
            <div class="table-cell" name="col-bobot"><p data-bobot="${bobot}">${bobot}</p></div>
            <div class="table-cell" name="col-indikator"><p data-indikator-id="${indikatorId}" data-nama-indikator="${namaIndikator}">${namaIndikator}</p></div>
            <div class="table-cell" name="col-kpi"><p data-kpi-id="${idKpi}" data-nama-kpi="${namaKpi}">${namaKpi}</p></div>
            <div class="table-cell" name="col-target"><p data-target="${target}">${target} ${simbolSatuan}</p></div>
        </div>`);
})
$(document).on('change', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){  
    let institusiId = 'institusi-' + $(this).attr("data-institusi-id");
    let rowId = $(this).closest('tr').attr('id');
    let unitId = $(this).attr("data-unit-id");
    let ketuaUnit = $(this).attr("data-ketua-unit");
    let target = $(this).attr("data-target");  
    let satuan = $(this).attr("data-satuan");
    let namaKpi = $(this).closest('tr').find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-nama-kpi");
    let idKpi = $(this).closest('tr').find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-kpi-id");
    let indikatorId = $(this).closest('tr').find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').val();
    let namaIndikator = $(this).closest('tr').find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').text();
    let bidangId = $(this).closest('tr').find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').val();
    let namaBidang = $(this).closest('tr').find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').text();
    let sumberId = $(this).closest('tr').find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').val();
    let namaSumber = $(this).closest('tr').find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').text();
    let bobot = $(this).closest('tr').find('td[name="col-bobot"]').find("input[name='bobot']").val();
                    

    let simbolSatuan = '%';
    switch(satuan) {
        case 'orang' :
            simbolSatuan = 'org';
            break;
        case 'satuan' :
            simbolSatuan = 'Angka';
            break;
        default :
            simbolSatuan = simbolSatuan;
            break;
    }


    console.log('unit id ', unitId)
    console.log('ketua unit', ketuaUnit)
    console.log('row id ', rowId)

    console.log('find institusi'+ $(`#${institusiId}`).length)
    console.log('find accordion' + $(`#${institusiId} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).length)
    console.log('find panel' + $(`#${institusiId} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).next('.panel').length)
    console.log('find tabel' + $(`#${institusiId} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).next('.panel').find('.table').length)
    console.log('find row' + $(`#${institusiId} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).next('.panel').find('.table').find(`div[data-row-id="${rowId}"]`).length)
    let rowTable =  $(`#${institusiId} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`)
                        .next('.panel')
                        .find('.table')
                        .find(`div[data-row-id="${rowId}"]`);
    console.log(rowTable)
   
    $(rowTable).find("div[name='col-sumber']").find("p")
        .attr("data-sumber-id",sumberId)
        .attr("data-nama-sumber", namaSumber)
        .text(namaSumber)
    $(rowTable).find("div[name='col-bidang']").find("p")
        .attr("data-bidang-id",bidangId)
        .attr("data-nama-bidang", namaBidang)
        .text(namaBidang)
    $(rowTable).find("div[name='col-bobot']").find("p")
        .attr("data-bobot",bobot)
        .text(bobot)
    $(rowTable).find("div[name='col-indikator']").find("p")
        .attr("data-indikator-id",indikatorId)
        .attr("data-nama-indikator", namaIndikator)
        .text(namaIndikator)
    $(rowTable).find("div[name='col-kpi']").find("p")
        .attr("data-kpi-id",idKpi)
        .attr("data-nama-sumber", namaKpi)
        .text(namaKpi)
    $(rowTable).find("div[name='col-target']").find("p")
        .attr("data-target",target)
        .text(`${target} ${simbolSatuan}`)
})

$(document).on('remove', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){
   let institusiId = $(this).attr("data-institusi-id");
   let rowId = $(this).parent().parent().parent().attr('id');
   let unitId = $(this).attr("data-unit-id");
   let ketuaUnit = $(this).attr("data-ketua-unit");
   let cardInstitusi = $(`#institusi-${institusiId}`);
   $(cardInstitusi).find(`button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`)
    .next('.panel')
    .find('.table')
    .find(`div[data-row-id="${rowId}"]`).remove();
})


//8. ajax post data
$(document).on('click', '#btn-post', function(){
    let tahun = $('select[name="tahun"]').val();
    let semester = $('input[name="semester"]:checked').val();

    let dataTable = [];
    $('#table-form-rencana-kerja-baru tbody tr').each(function(){
        let rowData = {};
        $(this).children().each(function(index){
            if(index == 0 ){
              let sumber = $(this).find('select[name="sumber"]').val();
              rowData["sumber"]= sumber;
            }else if (index == 1) {
                let bidang = $(this).find('select[name="bidang"]').val();
                rowData["bidang"] = bidang;
            }else if (index == 2) {
                let bobot = $(this).find('input[name="bobot"]').val();
                rowData["bobot"] = bobot;
            }else if (index == 3) {
                let indikator = $(this).find('select[name="indikator"]').val();
                rowData["indikator"] = indikator;
            }else if (index == 4) {
                let kpiId = $(this).find('input[name="kpi"]').attr("data-kpi-id");
                let namaKpi = $(this).find('input[name="kpi"]').attr("data-nama-kpi");
                rowData["kpi"] = {kpiId, namaKpi};
            }else if (index == 5) {
                let unit = []
               $(this).find(".tag-list").children().each(function(){
                    let unitId = $(this).attr("data-unit-id");
                    let ketuaUnit = $(this).attr("data-ketua-unit");
                    let target = $(this).attr("data-target");
                    let satuan = $(this).attr("data-satuan");
                    unit.push({ unitId, ketuaUnit, target, satuan});
               })
               rowData["unit"] = unit;
            }

        })
        dataTable.push(rowData);
    })


    $.ajax({
        type : 'POST',
        url : baseUrl+'index.php/formulir_rencana_kerja/create',
        dataType : 'json',
        contentType: 'application/json',
        data : JSON.stringify( { "data" : {tahun, semester, dataTable} })
    })
        .done( succes => {
            $("#modal-message .modal-body .error-response").hide();
            $("#modal-message .modal-body .success-response").show();
            $("#modal-message .modal-body .sucess-response .message").empty();
            $("#modal-message .modal-body .success-response .message").append('<p ><b>Berhasil!</b> Formulir berhasil dibuat!</p>');
            $("#modal-message").css("display", "block");  
        })
        .fail( error => {
            if(error.status == 400){
                $("#modal-message .modal-body .error-response").show();
                $("#modal-message .modal-body .success-response").hide();
                $("#modal-message .modal-body .error-response .message").empty();
                let errorMessage = JSON.parse(error.responseText);
                let key = Object.keys(errorMessage);
                $("#modal-message .modal-body .error-response .message").append('<p ><b>Data '+key[0]+'!</b> '+errorMessage[key]+'</p>');
                $("#modal-message").css("display", "block"); 
            }

            if(error.status == 500){
                console.log('Internal server error!');
                console.log(error);
            }
        })    
})



/* Funsi Untuk Komponen Di Modal  #modal-unit */
//1. List item unit set data to input
$(document).on("click", ".tag-list .tag-list-item", function(){
    let unitId = $(this).attr("data-unit-id");
    let namaUnit = $(this).attr("data-nama-unit");
    let targetUnit = $(this).attr("data-target");
    let ketuaUnit = $(this).attr("data-ketua-unit");
    let institusiId = $(this).attr("data-institusi-id");
    let satuan = $(this).attr("data-satuan");

    //mengeset data pada input berdasarkan attr list item yang dipilih
    $('#modal-unit input[name="nama-unit"]').val(namaUnit);
    $('#modal-unit input[name="nama-unit"]').attr("data-nama-unit", namaUnit);    
    $('#modal-unit input[name="nama-unit"]').attr("data-unit-id", unitId);
    $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit", ketuaUnit);
    $('#modal-unit input[name="nama-unit"]').attr("data-institusi-id", institusiId);    
    $('#modal-unit input[name="target"]').val(targetUnit);
    $('#modal-unit select[name="satuan"]').val(satuan);
})

//2. input text nama unit show list unit 
$(document).on("keyup", '#modal-unit input[name="nama-unit"]', function(){
    let value = $(this).val();
    let rowId = $('#modal-unit input[type="hidden"]').val();
    let indikatorId = $(`#table-form-rencana-kerja-baru tr#${rowId} select[name="indikator"]`).val();
    let unitfound = 0;
    $('#modal-unit #dropdown-list-unit .dropdown-content-item').filter(function(){
        if( $(this).text().toLowerCase().includes(value.toLowerCase())){
            let unitId = Number($(this).attr("data-unit-id"));
            let ketuaUnit = $(this).attr("data-ketua-unit");

            let found = false;
            //check unit dengan variabel kamus indikator [{unit_id : ... , indikator_id : ... }]
            kamus_indikator.forEach(indikator => {
                if( ( unitId== indikator.unit_id) && (indikatorId == indikator.indikator_id) ){
                    let unitRegistered = false;
                    //check apakah unit sudah ada di tag list
                    $("#modal-unit .tag-list-item").each(function(){
                        if(Number($(this).attr("data-unit-id")) == unitId && ketuaUnit == $(this).attr("data-ketua-unit")){
                            unitRegistered = true;
                        } 
                    })
                    if(!unitRegistered){
                        $(this).css("display", "block");
                        found = true;
                        unitfound++;
                        return;
                    }else{
                        $(this).css("display", "none");
                    }
                }
            })

            if(!found){
                $(this).css("display", "none"); 
            }
        }else{
            $(this).css("display", "none");
        }
    })

    if(unitfound == 0 ){
        $('#modal-unit .dropdown-content').css("display", "none");
        $("#modal-unit #btn-save").attr("disabled", true);
    }else{
        let targetValue = $('#modal-unit input[name="target"]').val();
         if( (Number(targetValue) <= 0) || isNaN(targetValue) ){
            $("#modal-unit #btn-save").attr("disabled", true);
        }else{
            $("#modal-unit #btn-save").attr("disabled", false);
        }
    }
    
})

//3. input text target institusi 
$(document).on("keyup", '#modal-unit input[name="target"]', function(){
    let value  = $(this).val();
    if( (Number(value) <= 0) || isNaN(value) ){
        $("#modal-unit #btn-save").attr("disabled", true);
    }else{
        $("#modal-unit #btn-save").attr("disabled", false);
        let namaUnitValue = $('#modal-unit input[name="nama-unit"]').val();
        if(!namaUnitValue){
            $("#modal-unit #btn-save").attr("disabled", true);
        }else{
            $("#modal-unit #btn-save").attr("disabled", false);
        }
    }
})


//3. set to input text ketika dropdown dipilih
$(document).on("click", '#modal-unit .dropdown-content-item', function(){
    $("#modal-unit input[name='nama-unit']").attr("data-unit-id", $(this).attr("data-unit-id"));
    $("#modal-unit input[name='nama-unit']").attr("data-nama-unit", $(this).attr("data-nama-unit"));
    $("#modal-unit input[name='nama-unit']").attr("data-ketua-unit", $(this).attr("data-ketua-unit"));
    $("#modal-unit input[name='nama-unit']").attr("data-institusi-id", $(this).attr("data-institusi-id"));
})

//2. Button Save mengupdate dan menambah data pada modal dan tabel
$(document).on("click", '#modal-unit #btn-save', function(){
    let namaUnit = $('#modal-unit input[name="nama-unit"]').attr("data-nama-unit");
    let unitId = $('#modal-unit input[name="nama-unit"]').attr("data-unit-id");
    let ketuaUnit = $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit");
    let institusiId = $('#modal-unit input[name="nama-unit"]').attr("data-institusi-id");
    let target = $('#modal-unit input[name="target"]').val();
    let rowId = $('#modal-unit input[type="hidden"]').val();
    let satuan = $('#modal-unit select[name="satuan"]').find(':selected').val();
    let simbolSatuan = '%';
    switch(satuan) {
        case 'orang' :
            simbolSatuan = 'org';
            break;
        case 'satuan' :
            simbolSatuan = 'Buah (Desimal)';
            break;
        case 'satuan bulat' :
            simbolSatuan = 'Buah (Bulat)';
            break;
        default :
            simbolSatuan = simbolSatuan;
            break;
    }


    if ($(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).length){
        //update data pada modal
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-target', target);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-nama-unit', namaUnit);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-unit-id', unitId);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-institusi-id', institusiId);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-satuan', satuan);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).html(`<span class="tag-list-item-nama-unit">${namaUnit}</span> <span class="tag-list-item-target">target:${target} ${simbolSatuan} <span class="close">&#10005;</span></span>`);
        //update data pada tabel
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-target', target);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-nama-unit', namaUnit);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-unit-id', unitId);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-institusi-id', institusiId);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-satuan', satuan);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).html(`<span class="tag-list-item-nama-unit">${namaUnit}</span> <span class="tag-list-item-target">target:${target} ${simbolSatuan} <span class="close">&#10005;</span></span>`);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("change");
    }else{
        //menambah data pada modal dan tabel
        $('#modal-unit .tag-list').append(`<li class="tag-list-item" data-unit-id="${unitId}" data-ketua-unit="${ketuaUnit}" data-nama-unit="${namaUnit}" data-target="${target}" data-institusi-id="${institusiId}" data-satuan="${satuan}"><span class="tag-list-item-nama-unit">${namaUnit}</span> <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span> <span class="tag-list-item-close">&#10005;</span></li>`);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list`).append(`<li class="tag-list-item btn-toggle-modal btn-toggle-modal-unit" modal-target="modal-unit" data-unit-id="${unitId}" data-ketua-unit="${ketuaUnit}" data-nama-unit="${namaUnit}" data-target="${target}" data-institusi-id="${institusiId}" data-satuan=${satuan}><span class="tag-list-item-nama-unit">${namaUnit}</span> <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span> <span class="tag-list-item-close">&#10005;</span></li>`);
        $(`#table-form-rencana-kerja-baru tr#${rowId} td[name="col-unit"] .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("add");
    }

    //clear input 
    $('#modal-unit input[name="nama-unit"]').val('');
    $('#modal-unit input[name="nama-unit"]').attr("data-unit-id", '');
    $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit", '');
    $('#modal-unit input[name="target"]').val('');
    $('#modal-unit select[name="satuan"] option:first').prop('selected', true);
})

$(document).on('click', '#modal-unit .tag-list-item-close', function(evt){
    evt.stopImmediatePropagation();
    let unitId = $(this).parent().attr("data-unit-id");
    let ketuaUnit = $(this).parent().attr("data-ketua-unit");
    let rowId = $('#modal-unit input[type="hidden"]').val();

    $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("remove");
    $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).remove();
    $(this).parent().remove()
})


