<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
    "title" => "Detil Format Laporan Formulir Hasil Bidang Kinerja Utama",
    "css" => [
        base_url()."assets/css/page_detil_format_rencana_kerja.css",
        "http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"
    ]
]);?>


<main>
    <!-- sidebar-->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-detil-format-rencana-kerja">

        <!-- CONTENT TITLE -->
        <?php $this->load->view("form_rencana_kerja/template/content_title_detil_format_formulir.php");?>

        <!-- breadcrumb -->
        <?php $this->load->view("form_rencana_kerja/template/breadcrumb_detil_format_formulir.php");?>

        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-table"></i> Format Formulir Rencana Kerja</h4>
            </div>
            <div class="card-body">
                <!-- FORM SEARCH -->
                <?php $this->load->view("form_rencana_kerja/template/form_search_detil_format_formulir.php");?>

                <table id="table-form-rencana-kerja-baru">
                    <thead>
                        <tr>
                            <th>Sumber</th>
                            <th>Bidang</th>
                            <th>Bobot</th>
                            <th>Sasaran Strategis/Indikator</th>
                            <th>KPI</th>
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $outer=0; foreach($format_formulir as $row_formulir): $outer++;?>
                            <tr class="table-row" id="row-<?=$outer;?>" data-kpi-id="<?=$row_formulir["kpi_id"];?>">
                                <td class="table-cell" name="col-sumber">
                                    <select name="sumber">
                                            <option value="renstra" <?=$row_formulir["sumber"] == 'renstra' ? "selected" : ""?>>Renstra</option>
                                            <option value="renop" <?=$row_formulir["sumber"] == 'renop' ? "selected" : ""?>>Renop</option>
                                            <option value="mutu" <?=$row_formulir["sumber"] == 'mutu' ? "selected" : ""?>>Sasaran Mutu</option>
                                    </select>
                                </td>
                                <td class="table-cell"  name="col-bidang">
                                    <select name="bidang">
                                            <?php $i=0; foreach($data_bidang as $bidang): $i++; ?>
                                                <option 
                                                    value="<?=$bidang->id;?>" 
                                                    <?=$row_formulir["bidang_id"]==$bidang->id ? "selected" : "";?>>
                                                        <?=$bidang->nama_bidang;?>
                                                </option>
                                            <?php endforeach;?>
                                    </select>
                                </td>
                                <td class="table-cell"  name="col-bobot">
                                <input type="text" name="bobot" id="bobot" value="<?=$row_formulir["bobot"];?>"/>
                                </td>
                                <td class="table-cell"  name="col-indikator">
                                    <select name="indikator">
                                        <?php $i=0; foreach($data_indikator as $row) : $i++; ?>
                                            <option 
                                                class='text-truncate' 
                                                value='<?=$row->id?>' 
                                                <?=$row_formulir["indikator_id"] == $row->id ? "selected" : ""?>>
                                                    <?=$row->nama_indikator?>
                                            </option>";
                                        <?php endforeach;?>
                                    </select>
                                </td>
                                <td class="table-cell"  name="col-kpi">
                                    <div class="dropdown">
                                        <input 
                                            data-kpi-id="<?=$row_formulir["kpi_id"];?>" data-nama-kpi="<?=$row_formulir["nama_kpi"];?>" data-bidang-kpi="<?=$row_formulir["bidang_id"];?>" class="dropdown-input" 
                                            type="text" 
                                            name="kpi" 
                                            value="<?=$row_formulir["nama_kpi"];?>"/>
                                        <div class="dropdown-content"></div>
                                    </div>
                                </td>
                            <td class="table-cell"  name="col-unit">
                                    <i 
                                        modal-target="modal-unit" 
                                        class="far fa-edit btn-toggle-modal btn-toggle-modal-unit"> 
                                            Daftar Unit
                                    </i>
                                    <ul 
                                        class="tag-list"  
                                        data-removed="[]" 
                                        data-inserted="[]" 
                                        data-before='<?= json_encode($row_formulir["unit"]);?>'>
                                        <?php $index_unit = 0; foreach($row_formulir["unit"] as $unit): $index_unit++;?>
                                            <?php 
                                                $namaUnit = $unit["ketua_unit"] == "" ? $unit["tenaga_pengajar"] == "0" ? "Anggota ".$unit["nama_unit"] : "Dosen ".$unit["nama_unit"] : "Ketua ".$unit["nama_unit"]; 
                                                $simbolSatuan = "%";
                                                switch($unit["satuan"]) {
                                                    case 'orang' :
                                                        $simbolSatuan = 'org';
                                                        break;
                                                    case 'satuan' :
                                                        $simbolSatuan = 'Buah (Decimal) ';
                                                        break;
                                                    case 'satuan bulat' :
                                                        $simbolSatuan = 'Buah (Bulat)';
                                                        break;
                                                    default :
                                                        $simbolSatuan = $simbolSatuan;
                                                        break;
                                                }
                                            ?>
                                            <li 
                                                class="tag-list-item btn-toggle-modal btn-toggle-modal-unit" 
                                                modal-target="modal-unit"
                                                data-unit-id="<?= $unit["unit_id"];?>" 
                                                data-ketua-unit="<?= $unit["ketua_unit"];?>" 
                                                data-nama-unit="<?=$namaUnit;?>"
                                                data-target="<?=$unit["target"];?>"
                                                data-institusi-id="<?=$unit["institusi_id"];?>"
                                                data-satuan="<?=$unit["satuan"];?>">
                                                <span class="tag-list-item-nama-unit">
                                                    <?= $namaUnit;?>
                                                </span>
                                                <span class="tag-list-item-target">
                                                    target : <?=$unit["target"]." ".$simbolSatuan;?>
                                                </span>
                                                <span class="tag-list-item-close">&times;</span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td class="table-cell"  name="col-button">
                                    <input type="hidden" name="periode_id" value="<?=$selected_periode;?>"/>
                                    <input type="hidden" name="kpi" value="<?=$row_formulir['kpi_id'];?>"/>
                                    <button class="btn-update" type="update">Update</button>
                                    <button class="btn-delete">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- NAVIGATION -->
                <div class="container-flex">
                    <div id="navigasi-bawah"  class="toolbar toolbar-white">
                        <a href="#navigasi-atas">
                            <i class="fa fa-plus"; title="tambah baris"> Kembali Keatas</i>
                        </a>
                    </div>
                    <div class="toolbar toolbar-white">
                        <i id="tambahBaris" class="fa fa-plus"; title="tambah baris"> baris </i>
                    </div>
                </div>

        </div>

        <!-- institusi box-->
        <?php 
            $table_header = '
                            <div class="table-header">
                                <div class="table-cell">
                                    Sumber
                                </div>
                                <div class="table-cell">
                                    Bidang
                                </div>
                                <div class="table-cell">
                                    Sasaran Strategis/Indikator
                                </div>
                                <div class="table-cell">
                                    KPI
                                </div>
                                <div class="table-cell">
                                    Target
                                </div>
                            </div>';
            $unit_amik = [];
            $unit_stmik = [];
            $unit_stie = [];
            $unit_umum = []; 
            
            //assing kpi to unit object
            foreach($format_formulir as $row_formulir){
                foreach($row_formulir["unit"] as $unit_from_row){
                    foreach($data_unit as $unit){
                    //untuk ketua unit
                        $found = false;
                        if( ($unit_from_row["unit_id"] == $unit->id ) && ($unit_from_row["ketua_unit"] == $unit->ketua_unit) ){
                            if(!isset($unit->kpi_ketua)){
                                $unit->kpi_ketua = [];
                                array_push($unit->kpi_ketua,   [
                                    "sumber" => $row_formulir["sumber"],
                                    "bidang_id" => $row_formulir["bidang_id"],
                                    "nama_bidang" => $row_formulir["nama_bidang"],
                                    "indikator_id" => $row_formulir["indikator_id"],
                                    "nama_indikator" => $row_formulir["nama_indikator"],
                                    "kpi_id" => $row_formulir["kpi_id"],
                                    "nama_kpi" => $row_formulir["nama_kpi"],
                                    "target" => $unit_from_row["target"],
                                    "satuan" => $unit_from_row["satuan"],
                                    "bobot" => $unit_from_row["bobot"]
                                ]);
                            }else{
                                array_push($unit->kpi_ketua,[
                                    "sumber" => $row_formulir["sumber"],
                                    "bidang_id" => $row_formulir["bidang_id"],
                                    "nama_bidang" => $row_formulir["nama_bidang"],
                                    "indikator_id" => $row_formulir["indikator_id"],
                                    "nama_indikator" => $row_formulir["nama_indikator"],
                                    "kpi_id" => $row_formulir["kpi_id"],
                                    "nama_kpi" => $row_formulir["nama_kpi"],
                                    "target" => $unit_from_row["target"],
                                    "satuan" => $unit_from_row["satuan"],
                                    "bobot" => $unit_from_row["bobot"]
                                ]);
                            }
                            $found = true;
                        }
                        //untuk unit anggota unit
                        else if (($unit_from_row["unit_id"] == $unit->id) && ($unit_from_row["ketua_unit"] == null)){
                            if(!isset($unit->kpi_anggota)){
                                $unit->kpi_anggota = [];
                                array_push($unit->kpi_anggota,   [
                                    "sumber" => $row_formulir["sumber"],
                                    "bidang_id" => $row_formulir["bidang_id"],
                                    "nama_bidang" => $row_formulir["nama_bidang"],
                                    "indikator_id" => $row_formulir["indikator_id"],
                                    "nama_indikator" => $row_formulir["nama_indikator"],
                                    "kpi_id" => $row_formulir["kpi_id"],
                                    "nama_kpi" => $row_formulir["nama_kpi"],
                                    "target" => $unit_from_row["target"],
                                    "satuan" => $unit_from_row["satuan"],
                                    "bobot" => $unit_from_row["bobot"]
                                ]);
                            
                            }else{
                                array_push($unit->kpi_anggota,[
                                    "sumber" => $row_formulir["sumber"],
                                    "bidang_id" => $row_formulir["bidang_id"],
                                    "nama_bidang" => $row_formulir["nama_bidang"],
                                    "indikator_id" => $row_formulir["indikator_id"],
                                    "nama_indikator" => $row_formulir["nama_indikator"],
                                    "kpi_id" => $row_formulir["kpi_id"],
                                    "nama_kpi" => $row_formulir["nama_kpi"],
                                    "target" => $unit_from_row["target"],
                                    "satuan" => $unit_from_row["satuan"],
                                    "bobot" => $unit_from_row["bobot"]
                                ]);
                            }
                            $found = true;
                        }

                        if($found){
                            break;
                        }
                    }
                }

            }

            //filter unit by institusi id
            foreach($data_unit as $unit){
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
        
        <?php 
            $data_amik = [
                "institusi_id" => 1,
                "nama_institusi" => "AMIK",
                "unit_insitusi" => $unit_amik
            ];
            $this->load->view('form_rencana_kerja/partial_box_institusi_form_edit.php', $data_amik);

            $data_stmik = [
                "institusi_id" => 2,
                "nama_institusi" => "STMIK",
                "unit_insitusi" => $unit_stmik
            ];

            $this->load->view('form_rencana_kerja/partial_box_institusi_form_edit.php', $data_stmik);
            
            $data_stie = [
                "institusi_id" => 3,
                "nama_institusi" => "STIE",
                "unit_insitusi" => $unit_stie
            ];
            $this->load->view('form_rencana_kerja/partial_box_institusi_form_edit.php', $data_stie);

            $data_umum = [
                "institusi_id" => 4,
                "nama_institusi" => "UMUM",
                "unit_insitusi" => $unit_umum
            ];
            $this->load->view('form_rencana_kerja/partial_box_institusi_form_edit.php', $data_umum);
        ?>
    </section>
</main>

<?php 
//modal unit
$dropdown_item = "<div class='dropdown-content'>";
foreach($data_unit as $unit){
    if((int) $unit->jumlah_anggota == 0) {
        $dropdown_item = $dropdown_item."<p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='$unit->ketua_unit' data-nama-unit='$unit->nama_unit' data-institusi-id='$unit->institusi_id'>
            Ketua $unit->nama_unit
        </p>";
    }else { 
        $nama_unit = $unit->tenaga_pengajar == '1' ? 'Dosen '.$unit->nama_unit : 'Anggota '.$unit->nama_unit;
        $dropdown_item = $dropdown_item."<p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='$unit->ketua_unit' data-nama-unit='Ketua $unit->nama_unit' data-institusi-id='$unit->institusi_id'>
            Ketua $unit->nama_unit
        </p>
        <p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='' data-nama-unit='Anggota $unit->nama_unit' data-institusi-id='$unit->institusi_id'>
            $nama_unit
        </p>";
    }
}
$dropdown_item .= "</div>";
$dropdown = " <input type='hidden' name='row-number'/>
<ul class='tag-list' data-before='[]' data-inserted='[]' data-removed='[]'></ul>
<div class='dropdown' id='dropdown-list-unit'>
    <h4 style='margin-bottom:10px;'>Nama Unit :</h4>
    <input id='nama-unit' type='text' class='dropdown-input input-block' name='nama-unit' placeholder='Masukan Nama Unit'/>
    $dropdown_item
</div>
<h4 style='margin-bottom:10px;'>Target Institusi: </h4>
<input class='input-block' type='number' name='target' placeholder='0' id='target'/>

<h4 style='margin-bottom:10px;'> Satuan :</h4>
<select name='satuan' id='satuan' class='input-block'>
    <option value='persen'>Persen</option>
    <option value='satuan'>Buah (Desimal)</option>
    <option value='satuan bulat'>Buah (Bulat)</option>
    <option value='orang'>Orang</option>
</select>";

$this->load->view("template/modal_umum.php",[
    "id" => "modal-unit",
    "size" => "modal-content-md",
    "icon" => "fas fa-school",
    "title" => "Daftar Unit",
    "element" => $dropdown,
    "button_type" => "btn-info",
    "button_desc" => "SAVE"
]);

//modal response 
$element_modal_response = <<<EOD
<div class="success-response">
    <div class="logo">
        <i class="far fa-check-circle fa-5x"></i></i>
    </div>
    <div class="message">

    </div>
</div>
<div class="error-response">
    <div class="logo">
        <i class="fas fa-exclamation-triangle fa-5x"></i>
    </div>
    <div class="message">

    </div>
</div>
EOD;

$this->load->view("template/modal_umum.php",[
    "id" => "modal-message",
    "size" => "modal-content-md",
    "icon" => "far fa-comment-dots",
    "title" => "Message",
    "element" => $element_modal_response,
    "button_type" => "btn-info close-modal",
    "button_desc" => "OK",
    "style" => "z-index:200"
]);

//modal tahun
$element_modal_tahun = <<<EOD
<input type="hidden" name="tahun"/>
<p>Tambahkan data tahun <span id="tahun-selanjutnya"></span> ?</p>
EOD;
$this->load->view("template/modal_umum.php",[
    "id" => "modal-tahun",
    "size" => "modal-content-sm",
    "icon" => "far fa-calendar-plus",
    "title" => "Tahun",
    "element" => $element_modal_tahun,
    "button_type" => "btn-update",
    "button_desc" => "SAVE",
    "style" => "z-index:100"
]);

//modal format
$option_tahun = "";
foreach($data_tahun as $tahun){
    $isSelected = (int)$selected_tahun == $tahun->tahun ? 'selected' : '';
    $option_tahun .= "<option value='$tahun->tahun' $isSelected> $tahun->tahun </option>";
}
$element_modal_format = <<<EOD
<div class="flex-row">
    <h4 style="margin-bottom:10px;"> Tahun :</h4>
    <select class="toolbar" name="tahun-target" id="tahun-target">
       $option_tahun
    </select>

    <h4 style="margin-bottom:10px;"> Semester :</h4>
    <select class="toolbar" name="semester-target" id="semester-target">
        <option value="1">Ganjil</option>
        <option value="2">Genap</option>
    </select>

    <i id="btn-toggle-modal-tahun" modal-target="modal-tahun" class="far fa-question-circle btn-toggle-modal" title="tambah data tahun"></i>
</div>
EOD;

$this->load->view("template/modal_umum.php",[
    "id" => "modal-format",
    "size" => "modal-content-sm",
    "icon" => "far fa-calendar-plus",
    "title" => "Pilih Tahun dan Semester Untuk Dibuat:",
    "element" => $element_modal_format,
    "button_type" => "btn-update",
    "button_id" => "btn-copy-format",
    "button_desc" => "SAVE"
]);

//modal format perseorangan
$option_selection_unit = "";
foreach($data_unit_pada_formulir as $unit_pada_formulir){
    $namaUnit = $unit_pada_formulir["ketua_unit"] == "" ? 
    $unit_pada_formulir["tenaga_pengajar"] == "0" ? "Anggota ".$unit_pada_formulir["nama_unit"] : "Dosen ".$unit_pada_formulir["nama_unit"] : "Ketua ".$unit_pada_formulir["nama_unit"];

    $unit_id = $unit_pada_formulir['unit_id'];
    $ketua_unit = $unit_pada_formulir["ketua_unit"];

    $option_selection_unit .= "<option value='$unit_id' data-ketua-unit='$ketua_unit'>
    $namaUnit</option>";
}  
           
$element_modal_format_perseorangan = " 
<h4 style='margin-bottom:10px;'> Unit :</h4>
<select class='toolbar' name='tahun-target' id='tahun-target'>
    $option_selection_unit
</select>
<h4 style='margin-bottom:10px;'> User :</h4>
<select class='toolbar' name='semester-target' id='semester-target'>
    <option value='1'>Ganjil</option>
    <option value='2'>Genap</option>
</select>
";

$this->load->view("template/modal_umum.php",[
    "id" => "modal-format-perseorangan",
    "size" => "modal-content-md",
    "icon" => "far fa-calendar-plus",
    "title" => "Pilih User : ",
    "element" => $element_modal_format_perseorangan,
    "button_type" => "btn-update",
    "button_id" => "btn-copy-format",
    "button_desc" => "SAVE"
]);

?>


<!-- FOOTER -->
<?php $this->load->view("partials/footer.php", [
    "js" => [
        "https://code.jquery.com/ui/1.12.0/jquery-ui.js", //jquery ui
        "https://code.jquery.com/ui/1.12.1/jquery-ui.js",
        base_url()."assets/js/page_detil_format_formulir_rencana_kerja.js"
    ]
]) ;?>

<!-- data client -->
<script type="text/javascript">

let kamus_indikator = JSON.parse('<?php echo json_encode($data_kamus_indikator); ?>');
let unit = JSON.parse('<?php echo json_encode($data_unit);?>');
let indikator = JSON.parse('<?php echo json_encode($data_indikator);?>');
let bidang = JSON.parse('<?php echo json_encode($data_bidang);?>');
let globalPeriodeId = '<?php echo $selected_periode;?>';

let baseUrl = '<?php echo base_url();?>';
let url_fetch_kpi = '<?php echo $action_get_kpi;?>';
let url_add_tahun = '<?php echo $action_add_tahun;?>';
let url_update_format = '<?php echo $action_update_format; ?>';
let url_delete_format = '<?php echo $action_delete_format; ?>';

setInputFilter($('input[name="bobot"]'), function(value) {
        return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});

let globalNewIdKpi = 0;
$(document).on('change', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){
    let parentTr = $(this).closest('tr');
    let kpiId = $(parentTr).attr("data-kpi-id");
    let institusiId = $(this).attr("data-institusi-id");
    let kpiIdNew = $(this).parent().parent().prev('[name="col-kpi"]').find('input[name="kpi"]').attr("data-kpi-id");
    let namaKpi = $(this).parent().parent().prev('[name="col-kpi"]').find('input[name="kpi"]').attr("data-nama-kpi");
    let target = $(this).attr("data-target");  
    let satuan = $(this).attr("data-satuan");
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

    let cardInstitusi = $(`#institusi-${institusiId}`);
    $(cardInstitusi).find(`[data-kpi-id="${kpiId}"] [name="col-target"] [data-target]`).text(target+' '+simbolSatuan);
    $(cardInstitusi).find(`[data-kpi-id="${kpiId}"] [name="col-kpi"] [data-kpi-id]`).text(namaKpi);
    //kpi berubah
    if(kpiId != kpiIdNew){
        //memberi kpi baru dengan id sementara
        
       if(!kpiIdNew){
           if(!kpiId){
                globalNewIdKpi++;
                kpiIdNew = "kpi-" + globalNewIdKpi;
                $(parentTr).attr("data-kpi-id", kpiIdNew);
                $(cardInstitusi).find(`[data-kpi-id="${kpiId}"]`).attr("data-kpi-id", kpiIdNew);
           }else{
                if(!kpiId.includes("kpi")){
                    globalNewIdKpi++;
                    kpiIdNew = "kpi-" + globalNewIdKpi;
                    $(parentTr).attr("data-kpi-id", kpiIdNew);
                    $(cardInstitusi).find(`[data-kpi-id="${kpiId}"]`).attr("data-kpi-id", kpiIdNew);
                }
           }
         
       } 
    }

})
$(document).on('remove', '#table-form-rencana-kerja-baru .tag-list .tag-list-item', function(){
    let institusiId = $(this).attr("data-institusi-id");
    let kpiId = $(this).closest('tr').attr("data-kpi-id");
    let cardInstitusi = $(`#institusi-${institusiId}`);
    $(cardInstitusi).find(`[data-kpi-id="${kpiId}"]`).remove();
})
</script>

