
<!-- 

1. fetch semua indikator
2. ajax kpi
3. fetch semua unit beserta kamus indikator


-->

<!-- Form Section-->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Formulir Rencana Kerja
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <!-- Top bar table -->
            <form action="<?php echo base_url()."index.php/formulir_rencana_kerja/create";?>" method="post">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <!-- SELECTION TAHUN-->
                    <div class="input-group mb-3 mr-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="tahun">Tahun :</label>
                        </div>
                        <select onchange="changeYear(event)" class="custom-select" name="tahun" id="tahun">
                            <?php
                                foreach($data["tahun"] as $tahun){
                                    if((int) $data["selected_tahun"] == $tahun->tahun){
                                        echo "<option value='$tahun->tahun' selected>$tahun->tahun</option>";
                                    }else{
                                        echo "<option value='$tahun->tahun'>$tahun->tahun</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>
                    
                    <!--RADIO BUTTON SEMESTER-->
                    <div class="input-group mb-3 align-items-center mr-3">
                        <span class="mr-1"><b>Semester : </b></span>
                        <div class="form-check mr-1">
                            <input  
                                name="gazal" 
                                class="form-check-input" 
                                type="radio" 
                                value="0" 
                                id="genap">
                            <label class="form-check-label" for="genap">
                                Genap(<?php echo " Maret ".$data['selected_tahun']->tahun." - Agustus ".$data['selected_tahun']->tahun; ?> )
                            </label>
                        </div> 
                        <div class="form-check mr-1">
                            <input 
                                name="gazal" 
                                class="form-check-input" 
                                type="radio" 
                                value="1" 
                                id="ganjil">
                            <label class="form-check-label" for="ganjil">
                                Ganjil(<?php echo " September ".$data['selected_tahun']->tahun." - Februari ".($data['selected_tahun']->tahun+1); ?> )
                            </label>
                        </div>
                    </div>
                </div>
                <a href="#">tahun belum ada?</a>
            </form>

            <table class="table table-bordered" id="table-rencana-kerja" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width : 10%">Sumber</th>
                        <th style="width : 30%">Sasaran Strategis / Indikator </th>
                        <th style="width: 40%">KPI</th>
                        <th style="width : 20%">Unit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="1">
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
                            <div class="dropdown">
                                <div class="input-group">
                                    <input onkeyup="fetchKpi(url_fetch, this.value, this)" class="form-control text-truncate" type="text" name="kpi" value="" placeholder="Masukan nama kpi"/>
                                    <div class="invalid-feedback">
                                        Kpi ini sudah digunakan.
                                    </div>
                                </div>
                                <ul class="dropdown-menu"></ul>
                            </div>
                        </td>
                        <td>
                            <p class="btn-open-model-unit"><i class="far fa-edit"> Daftar Unit</i></p>
                            <ul class="tag-list"></ul>
                                    
                        </td>
                    </tr>
                </tbody>
            </table>

            <!--Footbar table -->
            <div id="button-add-row" class="container d-flex justify-content-between">
                <button class="btn btn-primary btn-sm">Simpan</button>
                <button class="btn btn-link btn-sm">
                    <span onclick="addRow()">&#10010; baris</span>
                </button>
            </div>
        </div>

    </div>
</div>

<!-- Dikelompokan Berdasarkan Unit --> 
<?php
    $unit_stmik = [];
    $unit_amik = [];
    $unit_stie = [];
    $unit_umum = [];
    $table_tamplate = ' <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width : 20%">Sumber</th>
                                    <th style="width : 30%">Sasaran Strategis / Indikator </th>
                                    <th style="width: 40%">KPI</th>
                                    <th style="width : 10%">Target</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>';
    foreach($data["unit"] as $unit){
        if((int) $unit->institusi_id == 1){
            array_push($unit_amik, $unit);
        }elseif((int) $unit->institusi_id == 2){
            array_push($unit_stmik, $unit);
        }elseif((int) $unit->institusi_id == 3){
            array_push($unit_stie, $unit);
        }else{
            array_push($unit_umum, $unit);
        }
    }
    
?>

<!-- STMIK -->
<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
                STMIK
        </div>
    <div class="card-body">
        <div class="accordion" id="accordion-kategori-stmik">
            <?php 
               foreach($unit_stmik as $unit){
                    if( (int)$unit->jumlah_anggota == 0 ){
                        //unit yang tidak memiliki anggota contoh PK1, PK2
                        echo '<div class="card mb-3">';
                        echo '<div class="card-header" id="'."h".$unit->id.'">';
                        echo '<h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#'."c".$unit->id.'" aria-expanded="true" aria-controls="collapseOne">
                                  '."Ketua ".$unit->nama_unit.'
                                </button>
                            </h2>';
                        echo '</div>';
                        
                        echo '<div id="'."c".$unit->id.'" ketua-unit="'.$unit->ketua_unit.'" class="collapse" aria-labelledby="'."h".$unit->id.'">
                            <div class="card-body border">
                              '.$table_tamplate.'
                            </div>
                            </div>';
                        echo '</div>';
                    }else{
                        //ketua unit kode id ck+unit_id, attribute ketua-unit = ketua_unit_id  
                        echo '<div class="card mb-3">';
                        echo '<div class="card-header" id="'."h".$unit->id.'">';
                        echo '<h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#'."ck".$unit->id.'" aria-expanded="true" aria-controls="collapseOne">
                                  '."Ketua ".$unit->nama_unit.'
                                </button>
                            </h2>';
                        echo '</div>';
                        
                        echo '<div id="'."ck".$unit->id.'" ketua-unit="'.$unit->ketua_unit.'" class="collapse" aria-labelledby="'."h".$unit->id.'">
                            <div class="card-body border">
                              '.$table_tamplate.'
                            </div>
                            </div>';
                        echo '</div>';

                        //anggota unit kode c, attribute ketua-unit = null
                        echo '<div class="card mb-3">';
                        echo '<div class="card-header" id="'."h".$unit->id.'">';
                        echo '<h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#'."c".$unit->id.'" aria-expanded="true" aria-controls="collapseOne">
                                  '."Anggota ".$unit->nama_unit.'
                                </button>
                            </h2>';
                        echo '</div>';
                        
                        echo '<div id="'."c".$unit->id.'" ketua-unit="null" class="collapse" aria-labelledby="'."h".$unit->id.'">
                            <div class="card-body border">
                              '.$table_tamplate.'
                            </div>
                            </div>';
                        echo '</div>';
                    }
               }
            ?>
        </div>
</div>


<!-- Modal Untuk Tambah Daftar Unit-->
<div class="modal fade" id="modal-form-unit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
            <h5 class="modal-title" id="modal-form-unit">Masukan Unit Ke Daftar</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            </div>
            <div class="modal-body">
                <!-- List Unit Yang dipilih -->
                <p>List unit : </p>
                <ul id="list-unit-modal" class="tag-list">
                </ul>

                <div class="container">
                    <form id="form-unit">
                        <input type="hidden" value=""  name="row-number"/>
                        <div class="form-group">
                            <label for="unit">Nama Unit</label>
                            <div class="dropdown">
                                <input onkeyup="serachUnit(event)" type="text" class="form-control" id="input-unit" aria-describedby="unitHelp" placeholder="Masukan Nama unit..">
                                <ul id="dropdown-selection-unit" class="dropdown-menu overflow-auto">
                                    <li id="li-unit-tidak-ditemukan" class="dropdown-item">Unit tidak ditemukan!</li>
                                        <?php foreach($data["unit"] as $unit){
                                            if((int) $unit->jumlah_anggota == 0) {?>
                                                <li class='dropdown-item' onclick='chooseUnit("<?php echo $unit->id;?>","<?php echo $unit->ketua_unit;?>","<?php echo $unit->nama_unit;?>")'>
                                                    <?php echo "Ketua ".$unit->nama_unit; ?>
                                                </li>
                                            <?php } else { ?>
                                                    <li class='dropdown-item' onclick='chooseUnit("<?php echo $unit->id;?>","<?php echo $unit->ketua_unit;?>","<?php echo "Ketua ".$unit->nama_unit;?>")'>
                                                    <?php echo "Ketua ".$unit->nama_unit; ?>
                                                    </li>
                                                    <li class='dropdown-item' onclick='chooseUnit("<?php echo $unit->id;?>",null,"<?php echo "Anggota ".$unit->nama_unit;?>")'>
                                                    <?php echo "Anggota ".$unit->nama_unit; ?>
                                                    </li>
                                            <?php } ?>
                                        <?php }?>
                                </ul>
                            </div>
                            <div class="invalid-feedback">Unit yang dimasukan tidak terdaftar.</div>
                        </div>
                        <div class="form-group">
                            <label for="target">Target</label>
                            <input name="target" value="1" type="text" class="form-control" id="input-target" aria-describedby="target"/>
                            <small id="target" class="form-text text-muted">Masukan angka tidak negatif.</small>
                            <div class="invalid-feedback">Target tidak valid.</div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary mr-2" type="button" data-dismiss="modal">Tutup</button>
                <button onclick="tambahUnit()" id="btn-simpan" class="btn btn-primary" disabled type="button">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

setInputFilter($('input[name="target"]'), function(value) {
    return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});

let row_selected = 0;
let kamus_indikator = JSON.parse('<?php echo json_encode($data["kamus_indikator"]); ?>');
let unit = JSON.parse('<?php echo json_encode($data["unit"]);?>');
let indikator = JSON.parse('<?php echo json_encode($data["indikator"]);?>');
let url_fetch = '<?php echo base_url()."index.php/kpi/get_by_name";?>';

let form_data = [{
    "row_number" : 1,
    "indikator" : indikator[0],
    "kpi" : null,
    "kpi_baru" : null,
    "unit" :[]
}];
let new_row = {
    "row_number" : form_data.length+1,
    "indikator" : indikator[0],
    "kpi" : null,
    "kpi_baru" : null,
    "unit" :[]
}
</script>