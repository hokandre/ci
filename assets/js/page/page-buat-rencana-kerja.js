//close dropdown jika mengklik disembarang area
$(document).click(function (e) {
    e.stopPropagation();
    var container = $(".dropdown");
    //check if the clicked area is dropDown or not
    if (container.has(e.target).length === 0) {
        $('.dropdown-menu').hide();
    }
})

//[TABLE] button open model
$(document).on("click",".btn-open-model-unit",function(){
    let trParentId = $(this).closest('tr').attr("id");
    $('#form-unit input[type="hidden"]').val(trParentId);
    let ulElementListUnitRowSelected = $(this).next();
    $("#list-unit-modal").empty();
    ulElementListUnitRowSelected.children().clone().appendTo('#list-unit-modal');
    $('#li-unit-tidak-ditemukan').hide();
    $('#modal-form-unit').modal('toggle');
})




//[MODAL] bersihkan modal ketika di tutup
$("#modal-form-unit").on('hidden.bs.modal', function (e) {
    //clear form
    $('#form-unit').trigger("reset");
})

// [MODAL] tampilkan dropdown pilihan unit ketika input text di ketik
function serachUnit(event){
    let indikator_id = $(`tr#${row_selected} td:nth-child(2)`).find('.form-control').val();
    let value = event.target.value;
    
    if(!value){
        $('#dropdown-selection-unit').hide();
    }else{
        let count = 0;
        $('#dropdown-selection-unit > li').filter(function(){
            if($(this).text().toLowerCase().indexOf(value) > -1){
                count++;
            }

            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        })

        if (count == 0){
            $('#li-unit-tidak-ditemukan').show();
            $('#btn-simpan').attr('disabled', true);

        }else{
            $('#li-unit-tidak-ditemukan').hide();
        }
        
        $('#dropdown-selection-unit').show();
    }

}

// [MODAL] menset nilai pada input text ketika dropdown dipilih
function chooseUnit(unitId,ketuaUnit, namaUnit){
    
    $('#input-unit').val(namaUnit);
    $('#input-unit').attr("unit-id", unitId);
    $('#input-unit').attr("ketua-unit", ketuaUnit);
    $('#dropdown-selection-unit').hide();
    $('#btn-simpan').removeAttr('disabled'); 
}

// [Modal] button simpan
function tambahUnit(){
    let namaUnit = $('#form-unit #input-unit').val();
    let unitId = $('#form-unit #input-unit').attr("unit-id");
    let ketuaUnit = $('#form-unit #input-unit').attr("ketua-unit");
    let target = $('#form-unit #input-target').val();
    let rowId = $('#form-unit input[type="hidden"]').val();
    if(!ketuaUnit) {
        ketuaUnit = null
    }
    $('#form-unit').trigger('reset');
    if ($(`#list-unit-modal li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).length){
        $(`#list-unit-modal li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).attr('target', target);
        $(`#list-unit-modal li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).attr('nama-unit', namaUnit);
        $(`#list-unit-modal li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).html(`<span class="unit">${namaUnit}</span> <span>target:${target} <span class="close">&#10005;</span></span>`);
        //change list in table
        $(`table#table-rencana-kerja tbody tr#${rowId} ul.tag-list li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).attr('target', target);
        $(`table#table-rencana-kerja tbody tr#${rowId} ul.tag-list li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).attr('nama-unit', namaUnit);
        $(`table#table-rencana-kerja tbody tr#${rowId} ul.tag-list li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).html(`<span class="unit">${namaUnit}</span> <span>target:${target} <span class="close">&#10005;</span></span>`);
    }else{
        $('#list-unit-modal').append(`<li unit-id="${unitId}" ketua-unit="${ketuaUnit}" nama-unit="${namaUnit}" target="${target}"><span class="unit">${namaUnit}</span> <span>target:${target} <span class="close">&#10005;</span></span></li>`);
        $(`table#table-rencana-kerja tbody tr#${rowId} ul.tag-list`).append(`<li unit-id="${unitId}" ketua-unit="${ketuaUnit}" nama-unit="${namaUnit}" target="${target}"><span class="unit">${namaUnit}</span> <span>target:${target} <span class="close">&#10005;</span></span></li>`);
    }
}

//[MODAL] click pada list unit akan menset input text

$(document).on("click", "#list-unit-modal li", function(){
    let namaUnit = $(this).attr("nama-unit");
    let unitId = $(this).attr("unit-id");
    let target = $(this).attr("target");
    let ketuaUnit = $(this).attr("ketua-unit");
    $('#form-unit #input-unit').val(namaUnit);
    $('#form-unit #input-unit').attr("unit-id", unitId);
    $('#form-unit #input-unit').attr("target", target);
    $('#form-unit #input-unit').attr("ketua-unit", ketuaUnit);
    $('#form-unit #input-target').val(target);
})

//[MODAL] click pada list unit pada bagian simbol X akan menghapus element
$(document).on("click", "#list-unit-modal li span.close", function(){
    let rowId = $('#form-unit input[type="hidden"]').val();
    let liParent = $(this).closest('li');
    let namaUnit = $(liParent).attr("nama-unit");
    let unitId = $(liParent).attr("unit-id");
    let target = $(liParent).attr("target");
    let ketuaUnit = $(liParent).attr("ketua-unit");
    $(liParent).remove();
    $(`table#table-rencana-kerja tbody tr#${rowId} ul.tag-list li[unit-id="${unitId}"][ketua-unit="${ketuaUnit}"]`).remove();
})




function validateMinus(value){
    if(Number(value) <= 0 ){
        $('#btn-simpan').attr('disabled', true);
    }else{
        $('#btn-simpan').removeAttr('disabled'); 
    }
}



function changeYear(event){
    let tahun = Number(event.target.value);
    let stringGenap = `Genap( Maret ${tahun} - Agustus ${tahun} )`;
    let stringGanjil = `Ganjil( September ${tahun} - Februari ${tahun+1} )`;
    $("label[for='ganjil']").text(stringGanjil);
    $("label[for='genap']").text(stringGenap);

}

function changeIndikator(event, element){
    // membersihkan input type key performance indikator dan daftar tag list
    let tdElementParent = $(element).parent();
    let inputTypeKpi = tdElementParent.next().find('.form-control').val("");
    let ulListElementDaftarUnit = tdElementParent.next().next().find('.tag-list').empty();
}

function delay(fn, ms) {
    let timer = 0
    return function(...args) {
        clearTimeout(timer)
        timer = setTimeout(fn.bind(this, ...args), ms || 0)
    }
}

//fetch kpi to server
var delayTimer;
function fetchKpi(url,value, element){
   
    
}

// set dropdown value



function addRow(){
    //menambahkan baris pada tabel
    $('#table-rencana-kerja').append(`
        <tr>
            <td>
                <select class="form-control text-truncate" name="sumber">
                    <option value="renstra" selected>Renstra</option>
                    <option value="renop">Renop</option>
                    <option value="mutu" selected>Sasaran Mutu</option>
                </select>
            </td>
            <td>
                <select class="form-control" onchange="changeIndikator(event, this)" name="indikator">
                    <?php
                        foreach($data["indikator"] as $row){
                            echo "<option class='text-truncate' value='$row->id'> $row->nama_indikator </option>";
                        } 
                    ?>
                </select>
            </td>
            <td>
                <input  class="form-control text-truncate" type="text" name="kpi" value="" placeholder="Masukan nama kpi"/>
            </td>
            <td>
                <p>Daftar Unit :</p>
            <ul class="tag-list"><li>a</li></ul>
            <input class="form-control" placeholder="masukan unit.."/>

            </td>
        </tr>
    `);
    
}