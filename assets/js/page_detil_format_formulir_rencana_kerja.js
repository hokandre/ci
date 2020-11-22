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

$(document).on('change', 'select[name="bidang"]', function(){
    let parentTr = $(this).closest('tr');
    $(parentTr)
        .find('[name="col-unit"]')
        .find('.tag-list-item')
        .each(function(){
            $(this).trigger('change');
        })
})

$(document).on('change', 'select[name="sumber"]', function(){
    let parentTr = $(this).closest('tr');
    $(parentTr)
        .find('[name="col-unit"]')
        .find('.tag-list-item')
        .each(function(){
            $(this).trigger('change');
        })
})

$(document).on('change', 'select[name="indikator"]', function(){
    let parentTr = $(this).closest('tr');
    $(parentTr)
        .find('[name="col-unit"]')
        .find('.tag-list-item')
        .each(function(){
            $(this).trigger('change');
        })
})


/* Funsi Untuk Komponen Di Tabel #table-form-rencana-kerja-baru */
//1. Button tambah baris
$('#tambahBaris').on('click', function(){
    let table = $('#table-form-rencana-kerja-baru');
    let lastRowId = $(table).find('tbody tr:last-child').attr("id");
    let newId = null;
    if(lastRowId){
        newId = Number(lastRowId.split("-")[1])+1;
    }else{
        newId = 1;
    }

    if(newId){
        let colSumber = $(`<td class="table-cell" name="col-sumber"></td>`)
                            .append(
                                $(`<select name="sumber"></select>`)
                                    .append(`<option value="mutu"> Sasaran Mutu </option>`)
                                    .append(`<option value="renop"> Renop </option>`)
                                    .append(`<option value="renstra">Renstra</option>`));
        let colBidang =  $(`<td class="table-cell" name="col-bidang"></td>`)
                            .append(
                                $(`<select name="bidang"></select>`)
                                    .append(bidang.map(value => `<option value="${value.id}"> ${value.nama_bidang} </option>` ).join("")));
        let colBobot = $(`<td class="table-cell" name="col-bobot"></td>`)
                            .append(`<input name="bobot" type="text"/>`);
        
        setInputFilter($(colBobot).find('input[name="bobot"]'), function(value) {
            return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
        });

        let colIndikator =  $(`<td class="table-cell" name="col-indikator"></td>`)
                            .append(
                                $(`<select name="indikator"></select>`)
                                    .append(indikator.map(value => `<option value="${value.id}"> ${value.nama_indikator} </option>` ).join("")));
        let colKpi =  $(`<td class="table-cell" name="col-kpi"></td>`)
                            .append(
                                $(`<div class="dropdown"></div>`)
                                    .append(
                                        $(`<input class="dropdown-input" name="kpi"/>`)
                                            .attr("data-kpi-id", "")
                                            .attr("data-nama-kpi", "")
                                            .attr("data-bidang-kpi", $(colBidang).val())
                                    )
                                    .append(
                                        $(`<div class="dropdown-content" style="display:none;"></div>`)
                                    )
                            );
        let colUnit =  $(`<td class="table-cell" name="col-unit"></td>`)
                            .append(
                                $(`<i  class="far fa-edit btn-toggle-modal btn-toggle-modal-unit"> Daftar Unit</i>`) 
                                    .attr("modal-target","modal-unit")  
                            )
                            .append(
                                $(`<ul class="tag-list"> </ul>`)
                                    .attr("data-removed", "[]")
                                    .attr("data-inserted", "[]")
                                    .attr("data-before", "[]")
                            );
        let colAction = $(`<td class="table-cell" name="col-button"></td>`)
                                .append(`<button class="btn-info btn-simpan" type="simpan">Simpan</button>`)
                                .append(`<button class="btn-delete">Hapus</button>`);

        let newRow = $(`<tr class='table-row' id='row-${newId}'></tr>`)
            .append(colSumber)
            .append(colBidang)
            .append(colBobot)
            .append(colIndikator)
            .append(colKpi)
            .append(colUnit)
            .append(colAction);
        
        $(table).find('tbody').append(newRow);
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
$(document).on('change', '#table-form-rencana-kerja-baru tr select[name="indikator"]',function(){
    let parentTr = $(this).closest("tr");
    $(this).parent().next().find("input[name='kpi']").val('');
    $(this).parent().next().find("input[name='kpi']").attr('data-kpi-id','');
    $(this).parent().next().find("input[name='kpi']").attr('data-nama-kpi','');
    $(this).parent().next().find("input[name='kpi']").attr('data-bidang-kpi','');

    $(this).parent().parent().find('td[name="col-unit"] ul.tag-list').empty();
    $(this).parent().parent().find('td[name="col-kpi"] .dropdown-content').empty();
    let dataBefore = $(this).parent().parent().find('td[name="col-unit"] ul.tag-list').attr("data-before");

    let dataBeforeInTable = JSON.parse($(this).parent().parent().find('td[name="col-unit"] ul.tag-list').attr("data-removed"));
    
    /**
     * Menentukan jenis operasi
     */
    let buttonType = $(parentTr).find("td[name='col-button'] button[type]").attr("type");

    if(dataBeforeInTable.length == 0 && buttonType == "update"){
        $(this).parent().parent().find('td[name="col-unit"] ul.tag-list').attr("data-removed",dataBefore);
    }

    $(this).parent().parent().find('td[name="col-unit"] ul.tag-list').attr("data-inserted","[]");
    $(this).parent().parent().find('td[name="col-unit"] ul.tag-list').attr("data-before","[]");
})

//4. Button Open modal
$(document).on("click",".btn-toggle-modal-unit",function(){
    let tagList = $(this).closest('tr').find('.tag-list');

    let rowId = $(this).closest('tr').attr("id");
    $('#modal-unit input[type="hidden"]').val(rowId);
    $("#modal-unit .tag-list").attr("data-inserted", $(tagList).attr('data-inserted'));
    $("#modal-unit .tag-list").attr("data-removed", $(tagList).attr('data-removed'));
    $("#modal-unit .tag-list").attr("data-before", $(tagList).attr('data-before'));


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
    $('#modal-unit input[name="target"]').val('');

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
           'form' : 'edit',
           'tahun' : $("#modal-tahun input[type='hidden'][name='tahun']").val()
        }
    })
        .done(data => {
            window.location.href = data.redirect;
        })
})


//8. ajax post data
$(document).on('click', '#btn-copy-format', function(){
    let tahun = $('select[name="tahun-target"]').val();
    let semester = $('select[name="semester-target"]').val();

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
            $("#modal-format").hide();
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
                            $(this).next(".dropdown-content").append(`<p class="dropdown-content-item" data-kpi-id="${kpi.id}" data-nama-kpi="${kpi.nama_kpi}" data-bidang-kpi="${kpi.bidang_id}">${kpi.nama_kpi}</p>`);
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

    let kpiVlue = $(this).val();
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
    }
})

//6. pilih hasil fetch pada list dropdown dan set value ke input text
$(document).on('click', '#table-form-rencana-kerja-baru .dropdown .dropdown-content .dropdown-content-item', function(event){
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
    }else{        
        $(this).parent().prev("input[name='kpi']").attr("data-kpi-id", $(this).attr("data-kpi-id"));
        $(this).parent().prev("input[name='kpi']").attr("data-nama-kpi", $(this).attr("data-nama-kpi"));
        $(this).parent().prev("input[name='kpi']").attr("data-bidang-kpi", $(this).attr("data-bidang-kpi"));
        //change select bidang
        let bidangValue = $(this).parent().prev("input[name='kpi']").attr("data-bidang-kpi");
        $(this).parent().parent().parent().prev().prev().find('select[name="bidang"]').val(bidangValue);

        let parentTr = $(this).closest('tr');
        $(parentTr)
            .find('[name="col-unit"]')
            .find('.tag-list-item')
            .each(function(){
                $(this).trigger('change');
            })

    }
})

//7. li item unit pada tabel customer listener for change and delete
$(document).on('add', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){
    let parentTr = $(this).closest("tr");
    let kpiId = $(parentTr).find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-kpi-id");
    let idInstitusi = 'institusi-' + $(this).attr("data-institusi-id");
    let unitId = $(this).attr("data-unit-id");
    let ketuaUnit = $(this).attr("data-ketua-unit");
    let target = $(this).attr("data-target");
    let satuan = $(this).attr("data-satuan");

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

    let namaKpi = $(parentTr).find('td[name="col-kpi"]').find('input[name="kpi"]').attr("data-nama-kpi");
    let indikatorId = $(parentTr).find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').val();
    let namaIndikator = $(parentTr).find('td[name="col-indikator"]').find('select[name="indikator"] option:selected').text();
    let bidangId = $(parentTr).find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').val();
    let namaBidang = $(parentTr).find('td[name="col-bidang"]').find('select[name="bidang"] option:selected').text();
    let sumberId = $(parentTr).find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').val();
    let namaSumber = $(parentTr).find('td[name="col-sumber"]').find('select[name="sumber"] option:selected').text();
    let bobot = $(parentTr).find('td[name="col-bobot"]').find('input[name="bobot"]').val();

    $(`#${idInstitusi} button.accordion[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`)
    .next('.panel')
    .children('.table')
    .append(`
        <div class="table-row" data-kpi-id="${kpiId}">
            <div class="table-cell" name="col-sumber"><p data-sumber-id="${sumberId}" data-nama-sumber="${namaSumber}">${namaSumber}</p></div>
            <div class="table-cell" name="col-bidang"><p data-bidang-id="${bidangId}" data-nama-bidang="${namaBidang}">${namaBidang}</p></div>
            <div class="table-cell" name="col-bobot">
                <p data-bobot="${bobot}">${bobot}</p>
            </div>
            <div class="table-cell" name="col-indikator"><p data-indikator-id="${indikatorId}" data-nama-indikator="${namaIndikator}">${namaIndikator}</p></div>
            <div class="table-cell" name="col-kpi"><p data-kpi-id="${kpiId}" data-nama-kpi="${namaKpi}">${namaKpi}</p></div>
            <div class="table-cell" name="col-target"><p data-target="${target}">${target} ${simbolSatuan}</p></div>
        </div>`);
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
        $("#modal-unit #btn-save").attr("disabled", false);
    }
    
})


//3. set to input text ketika dropdown dipilih
$(document).on("click", '#modal-unit .dropdown-content-item', function(){
    $("#modal-unit input[name='nama-unit']").attr("data-unit-id", $(this).attr("data-unit-id"));
    $("#modal-unit input[name='nama-unit']").attr("data-nama-unit", $(this).attr("data-nama-unit"));
    $("#modal-unit input[name='nama-unit']").attr("data-ketua-unit", $(this).attr("data-ketua-unit"));
    $("#modal-unit input[name='nama-unit']").attr("data-institusi-id", $(this).attr("data-institusi-id"));
})




/* updated */
//delete
$(document).on('click', '#modal-unit .tag-list .tag-list-item .tag-list-item-close', function(evt){
    evt.stopImmediatePropagation();
    let unitId = $(this).parent().attr("data-unit-id");
    let ketuaUnit = $(this).parent().attr("data-ketua-unit");
    let namaUnit = $(this).parent().attr("data-nama-unit");
    let target = $(this).parent().attr("data-target");
    let institusiId = $(this).parent().attr("data-institusi-id");


    let dataBefore = JSON.parse($('#modal-unit .tag-list').attr("data-before"));
    let dataRemoved = JSON.parse($('#modal-unit .tag-list').attr("data-removed"));
    let dataInserted = JSON.parse($('#modal-unit .tag-list').attr("data-inserted"));
    let indexInDataBefore = dataBefore.findIndex(unit => (unit.unit_id==unitId) && (unit.ketua_unit == ketuaUnit ));
    if (indexInDataBefore == -1){
        let indexInDataInserted = dataInserted.findIndex(unit => (unit.unit_id==unitId) && (unit.ketua_unit == ketuaUnit ));
        if(indexInDataInserted != -1){
            dataInserted.splice(indexInDataInserted, 1);
        }
    }else{
        dataRemoved.push({
            "unit_id" : unitId,
            "nama_unit" : namaUnit,
            "ketua_unit" : ketuaUnit,
            "institusi_id" : institusiId,
            "target" : target
        })
        dataBefore.splice(indexInDataBefore, 1);
    }
    //update data modal unit
    $('#modal-unit .tag-list').attr("data-inserted", JSON.stringify(dataInserted));
    $('#modal-unit .tag-list').attr("data-before", JSON.stringify(dataBefore));
    $('#modal-unit .tag-list').attr("data-removed", JSON.stringify(dataRemoved));

    //update data table
    $('#modal-unit .tag-list').trigger('change');

    //update ui
    let rowId = $('#modal-unit input[type="hidden"]').val();
    $(`#table-form-rencana-kerja-baru tr#${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("remove");
    $(`#table-form-rencana-kerja-baru tr#${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).remove();
    $(this).closest("li").remove();
})

//changed
//2. Button Save mengupdate dan menambah data pada modal dan tabel
$(document).on("click", '#modal-unit #btn-save', function(){
    let namaUnit = $('#modal-unit input[name="nama-unit"]').attr("data-nama-unit");
    let unitId = $('#modal-unit input[name="nama-unit"]').attr("data-unit-id");
    let ketuaUnit = $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit");
    let institusiId = $('#modal-unit input[name="nama-unit"]').attr("data-institusi-id");
    let target = $('#modal-unit input[name="target"]').val();
    let satuan = $('#modal-unit select[name="satuan"] option:selected').val();
    let rowId = $('#modal-unit input[type="hidden"]').val();
    let simbolSatuan = '%';
    switch(satuan) {
        case 'orang' :
            simbolSatuan = 'org';
            break;
        case 'satuan' :
            simbolSatuan = 'buah (Decimal)';
            break;
        case 'satuan bulat' :
            simbolSatuan = "buah (Bulat)";
            break; 
        default :
            simbolSatuan = simbolSatuan;
            break;
    }

    let dataBefore = JSON.parse($('#modal-unit .tag-list').attr("data-before"));
    let dataRemoved = JSON.parse($('#modal-unit .tag-list').attr("data-removed"));
    let dataInserted = JSON.parse($('#modal-unit .tag-list').attr("data-inserted"));

   

    let indexElementInDataRemoved = dataRemoved.findIndex(unit => (unit.unit_id==unitId) && (unit.ketua_unit == ketuaUnit ));
    if (indexElementInDataRemoved == -1){
        let indexElementInDataBefore = dataBefore.findIndex(unit => (unit.unit_id==unitId) && (unit.ketua_unit == ketuaUnit ));
        if(indexElementInDataBefore == -1){
            dataInserted.push({
                "unit_id" : unitId,
                "nama_unit" : namaUnit,
                "ketua_unit" : ketuaUnit,
                "institusi_id" : institusiId,
                "target" : target,
                "satuan" : satuan
            })
        }else{
            dataBefore[indexElementInDataBefore].target = target;
            dataBefore[indexElementInDataBefore].satuan = satuan;
            $('#modal-unit .tag-list').attr("data-before", JSON.stringify(dataBefore));
        }
    }else{
        dataRemoved.splice(indexElementInDataRemoved, 1);
        dataBefore.push(
            {
                "unit_id" : unitId,
                "nama_unit" : namaUnit,
                "ketua_unit" : ketuaUnit,
                "institusi_id" : institusiId,
                "target" : target,
                "satuan" : satuan
            }
        );
    }

    $('#modal-unit .tag-list').attr("data-before", JSON.stringify(dataBefore));
    $('#modal-unit .tag-list').attr("data-removed", JSON.stringify(dataRemoved));
    $('#modal-unit .tag-list').attr("data-inserted", JSON.stringify(dataInserted));

    $('#modal-unit .tag-list').trigger('change');



    if ($(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).length){
        //update data pada modal
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-target', target);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-nama-unit', namaUnit);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-unit-id', unitId);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-institusi-id', institusiId);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-satuan', satuan);
        $(`#modal-unit .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).html(`<span class="tag-list-item-nama-unit">${namaUnit}</span> <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span>
        <span class="tag-list-item-close">&#10005;</span>`);
        //update data pada tabel
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-target', target);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-nama-unit', namaUnit);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-unit-id', unitId);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-institusi-id', institusiId);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).attr('data-satuan', satuan);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).html(`
        <span class="tag-list-item-nama-unit">${namaUnit}</span> 
        <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span>
        <span class="tag-list-item-close">&#10005;</span>`);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("change");
    }else{
        //menambah data pada modal dan tabel
        $('#modal-unit .tag-list').append(`<li class="tag-list-item" data-unit-id="${unitId}" data-ketua-unit="${ketuaUnit}" data-nama-unit="${namaUnit}" data-target="${target}" data-institusi-id="${institusiId}" data-satuan="${satuan}">
        <span class="tag-list-item-nama-unit">${namaUnit}</span> 
        <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span> 
        <span class="tag-list-item-close">&#10005;</span></li>`);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list`).append(`<li class="tag-list-item btn-toggle-modal btn-toggle-modal-unit" modal-target="modal-unit" data-unit-id="${unitId}" data-ketua-unit="${ketuaUnit}" data-nama-unit="${namaUnit}" data-target="${target}" data-institusi-id="${institusiId}" data-satuan="${satuan}">
        <span class="tag-list-item-nama-unit">${namaUnit}</span> 
        <span class="tag-list-item-target">target:${target} ${simbolSatuan}</span> 
        <span class="tag-list-item-close">&#10005;</span></li>`);
        $(`#table-form-rencana-kerja-baru #${rowId} .tag-list .tag-list-item[data-unit-id="${unitId}"][data-ketua-unit="${ketuaUnit}"]`).trigger("add");
    }

    //clear input 
    $('#modal-unit input[name="nama-unit"]').val('');
    $('#modal-unit input[name="nama-unit"]').attr("data-unit-id", '');
    $('#modal-unit input[name="nama-unit"]').attr("data-ketua-unit", '');
    $('#modal-unit input[name="target"]').val('');
    $('#modal-unit select[name="satuan"] option:first').prop("selected", true);
    
})

$(document).on('change','#modal-unit .tag-list', function(){
    let rowId = $('#modal-unit input[type="hidden"]').val();
    let dataRemoved = $(this).attr("data-removed");
    let dataBefore = $(this).attr("data-before");
    let dataInserted = $(this).attr("data-inserted");
    $(`#table-form-rencana-kerja-baru #${rowId} .tag-list`).attr("data-removed", dataRemoved);
    $(`#table-form-rencana-kerja-baru #${rowId} .tag-list`).attr("data-inserted", dataInserted);
    $(`#table-form-rencana-kerja-baru #${rowId} .tag-list`).attr("data-before", dataBefore);
})

$(document).on('click', '.table-cell .btn-update', function(){
    let parentCell = $(this).parent();

    let periodeId = $(parentCell).find('input[name="periode_id"]').val();
    let kpiSebelum = $(parentCell).find('input[name="kpi"]').val();

    let parentTr = $(this).closest("tr");
    let sumber = $(parentTr).find('td[name="col-sumber"] select[name="sumber"]').val();
    let bidang = $(parentTr).find('td[name="col-bidang"] select[name="bidang"]').val();
    let indikator = $(parentTr).find('td[name="col-indikator"] select[name="indikator"]').val();
    let bobot = $(parentTr).find('td[name="col-bobot"] input[name="bobot"]').val();

    let unit = {
        "removed_unit" : JSON.parse($(parentTr).find('td[name="col-unit"] ul.tag-list').attr("data-removed")),
        "inserted_unit" :JSON.parse($(parentTr).find('td[name="col-unit"] ul.tag-list').attr("data-inserted")),
        "changed_unit" : JSON.parse($(parentTr).find('td[name="col-unit"] ul.tag-list').attr("data-before"))
    };
    
    let dataPost = {"kpi_sebelum" : kpiSebelum, "unit" : unit, "periode_id" : periodeId, "sumber" : sumber, "bidang" : bidang, "indikator" : indikator, "bobot" : bobot};
    let newKpiId = $(parentTr).find('td[name="col-kpi"] input[name="kpi"]').attr("data-kpi-id");
    if(kpiSebelum != newKpiId){
        dataPost["kpi_baru"] = {
            "nama_kpi" : $(parentTr).find('td[name="col-kpi"] input[name="kpi"]').attr("data-nama-kpi"),
        }

        
    }

    if(dataPost["kpi_baru"]){
       if(dataPost["kpi_baru"].nama_kpi == ""){
           alert("Kpi kosong!");
           return;
       }
    }

    $.ajax({
        method : 'POST',
        dataType : 'json',
        contentType: 'application/json',    
        url : url_update_format,
        data : JSON.stringify({ "data" : dataPost})
    })
        .done(success => {
            window.location.href = success.redirect;
        })
        .fail(error => {
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
                let errorMessage = JSON.parse(error.responseText);
                console.log('Internal server error!')
                console.log(errorMessage)
            }
        })
})

$(document).on('click', '.table-cell .btn-delete', function(){
    let parentRow = $(this).parent().parent();
    let dataUnitBefore = JSON.parse($(parentRow).find('td[name="col-unit"] ul.tag-list').attr("data-before"));
    let dataUnitRemoved = JSON.parse($(parentRow).find('td[name="col-unit"] ul.tag-list').attr("data-removed"));
    
    let dataPost = {
        periode_id : $(this).parent().find('input[name="periode_id"]').val(),
        unit : dataUnitBefore.length !== 0 ? dataUnitBefore : dataUnitRemoved,
        kpi_id : $(parentRow).find('td[name="col-kpi"] input[name="kpi"]').attr("data-kpi-id")
    }    
    if(dataUnitBefore.length !== 0 && dataUnitRemoved !== 0){
        $.ajax({
            method : 'POST',
            url : url_delete_format,
            data: JSON.stringify({"data" : dataPost})
        })
            .done(success => {
                window.location.href = success.redirect;
            })
    }else{
        parentRow.remove();
    }
})

$(document).on('click', '.table-cell .btn-simpan', function(){
    let parentTr = $(this).closest("tr");
    
    let periodeId = globalPeriodeId;
    let sumber = $(parentTr).find('td[name="col-sumber"] select[name="sumber"]').val();
    let bidang = $(parentTr).find('td[name="col-bidang"] select[name="bidang"]').val();
    let bobot = $(parentTr).find('td[name="col-bobot"] input[name="bobot"]').val();
    let kpi_id = $(parentTr).find('td[name="col-kpi"] input[name="kpi"]').attr("data-kpi-id");
    let indikator = $(parentTr).find('td[name="col-indikator"] select[name="indikator"]').val();

    let unit = {
        "removed_unit" : [],
        "changed_unit" : [],
        "inserted_unit" :JSON.parse($(parentTr).find('td[name="col-unit"] ul.tag-list').attr("data-inserted")),
    };

    let dataPost = {
        "unit" : unit, 
        "periode_id" : periodeId, 
        "sumber" : sumber, 
        "bidang" : bidang, 
        "indikator" : indikator,
        "bobot" : bobot
    };

    //kpi di ubah
    if(!kpi_id){
        dataPost["kpi_baru"] = {
            "nama_kpi" : $(parentTr).find('td[name="col-kpi"] input[name="kpi"]').attr("data-nama-kpi")
        }
    }else{
        dataPost["kpi_sebelum"] = kpi_id;
    }
    
    $.ajax({
        method : 'POST',
        dataType : 'json',
        contentType: 'application/json',    
        url : url_update_format,
        data : JSON.stringify({ "data" : dataPost})
    })
        .done(success => {
            console.log('ini adalah response' + success);
            window.location.href = success.redirect;
        })
        .fail(error => {
            console.log('Masuk error!', error);
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

