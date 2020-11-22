<!-- header -->
<?php $this->load->view('partials/header.php',[
    "title" => "Buat Format Formulir Hasil Bidang Kinerja Utama",
    "css" => [
        base_url()."assets/css/page_form_buat_rencana_kerja.css",
        "http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"
    ]
]);?>

<main>
    <!-- sidebar-->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-list-rencana-kerja-baru">

        <!-- page title-->
        <?php $this->load->view("form_rencana_kerja/template/content_title_buat_format.php");?>

        <!-- breadcrumb -->
        <?php $this->load->view("form_rencana_kerja/template/breadcrumb_form_buat.php",[
            "breadcrumb" => $breadcrumb
        ]);?>
        
        <!--card -->
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-table"></i>  Formulir</h4>
            </div>
            <div class="card-body">
                <div class="form-filter">
                    <div class="form-filter-option">
                        <h4 for="tahun">Tahun : </h4>
                        <!-- BTN Modal Tahun -->
                        <i id="btn-toggle-modal-tahun" 
                            modal-target="modal-tahun" 
                            class="far fa-question-circle btn-toggle-modal" title="tambah data tahun">
                        </i>
                        <select class="toolbar" name="tahun" id="tahun">
                            <?php foreach($data_tahun as $tahun) : ?>
                                <?php if((int) $selected_tahun->tahun == $tahun->tahun) : ?>
                                <option value='<?=$tahun->tahun;?>' selected>
                                        <?=$tahun->tahun?>
                                    </option>
                                <?php else : ?>
                                    <option value='<?=$tahun->tahun?>'>
                                        <?=$tahun->tahun;?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach;?>
                        </select>
                    </div> 
                    <!--Semester-->
                    <div class="form-filter-option">
                        <h4>Semester : </h4>
                        <div class="toolbar">
                            <input class="toolbar" name="semester" type="radio" value="1" id="ganjil"/>
                            <label class="form-check-label" for="ganjil">
                                    Ganjil(<?php echo " September ".$selected_tahun->tahun." - Februari ".(($selected_tahun->tahun)+1); ?> )
                            </label>
                        </div>
                        <divt class="toolbar">
                            <input class="toolbar" name="semester" type="radio" value="2" id="genap">
                            <label class="form-check-label" for="genap">
                                    Genap(<?php echo " Maret ".$selected_tahun->tahun." - Agustus ".$selected_tahun->tahun; ?> )
                            </label>
                        </divt>
                    </div>
                </div>

                <table id="table-form-rencana-kerja-baru">
                    <thead>
                        <tr>
                            <th>Sumber</th>
                            <th>Bidang</th>
                            <th style="width:73px;">Bobot</th>
                            <th>Sasaran Strategis/Indikator</th>
                            <th>KPI</th>
                            <th>Unit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr id="row-1">
                            <td name="col-sumber">
                                <select name="sumber">
                                        <option value="renstra" selected>
                                        Renstra
                                        </option>
                                        <option value="renop">Renop</option>
                                        <option value="mutu" selected>
                                        Sasaran Mutu
                                        </option>
                                </select>
                            </td>
                            <td name="col-bidang">
                                <select name="bidang" >
                                        <?php $i=0; foreach($data_bidang as $bidang): $i++; ?>
                                        <option value="<?=$bidang->id;?>"><?=$bidang->nama_bidang;?></option>
                                        <?php endforeach;?>
                                </select>
                            </td>
                            <td name="col-bobot">
                                <input type="text" name="bobot" id="bobot"/>
                            </td>
                            <td name="col-indikator">
                                <select name="indikator">
                                    <?php foreach($data_indikator as $row):?>
                                        <option class='text-truncate' value='<?=$row->id;?>'>
                                            <?=$row->nama_indikator;?> 
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td name="col-kpi">
                                <div class="dropdown">
                                    <input data-kpi-id="" data-nama-kpi="" data-bidang-kpi="" class="dropdown-input" type="text" name="kpi"/>
                                    <div class="dropdown-content"></div>
                                </div>
                            </td>
                            <td class="table-cell" name="col-unit">
                                <i modal-target="modal-unit" 
                                class="far fa-edit btn-toggle-modal btn-toggle-modal-unit toolbar toolbar-white">   
                                    Unit
                                </i>
                                <span title="hapus baris" class="toolbar" name="hapus-baris">
                                    &times;
                                </span>
                                <ul class="tag-list"></ul>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="flex-row justify-space-between align-items-center">
                    <button id="btn-post" class="btn-info">Post</button>
                    <button class="toolbar">
                        <i  id="tambahBaris" class="far fa-plus-square" title="tambah baris"> ( baris ) </i>
                    </button>
                </div>
                
            </div>      
        </div>
    
<!-- institusi box-->
<?php 
$unit_amik = [];
$unit_stmik = [];
$unit_stie = [];
$unit_umum = []; 
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

$data_amik = [
    "institusi_id" => 1,
    "nama_institusi" => "AMIK",
    "unit_insitusi" => $unit_amik
];
$this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_amik);

$data_stmik = [
    "institusi_id" => 2,
    "nama_institusi" => "STMIK",
    "unit_insitusi" => $unit_stmik
];
$this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_stmik);

$data_stie = [
    "institusi_id" => 3,
    "nama_institusi" => "STIE",
    "unit_insitusi" => $unit_stie
];
$this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_stie);

$data_umum = [
    "institusi_id" => 4,
    "nama_institusi" => "UMUM",
    "unit_insitusi" => $unit_umum
];
$this->load->view('form_rencana_kerja/partial_box_institusi_form_buat.php', $data_umum);

?>
    </section>
</main>



<?php 
//MODAL TAHUN
$element_modal_tahun = <<<EOD
    <input type='hidden' name='tahun'/>              
    <p>Tambahkan data tahun <span id='tahun-selanjutnya'></span> ?</p>
EOD;
$this->load->view("template/modal_umum.php", [
    "id" => "modal-tahun",
    "size" => "modal-content-sm",
    "icon" => "far fa-calender-plus",
    "title" => "Tahun",
    "element" => $element_modal_tahun,
    "button_type" => "btn-update",
    "button_desc" => "SAVE"
]);

//modal response
$element_modal_response =<<<EOD
    <div class='error-response'>
        <div class='logo'>
            <i class='fas fa-exclamation-triangle fa-5x'></i>
        </div>
        <div class='message'>
        </div>
    </div>
    <div class='success-response'>
        <div class='logo'>
            <i class='far fa-check-circle fa-5x'></i></i>
        </div>
        <div class='message'>

        </div>
    </div>
EOD;

$this->load->view("template/modal_umum.php",[
    "id" => "modal-message",
    "size" => "modal-content-md",
    "icon" => "far fa-comment-dots",
    "title" => "message",
    "element" => $element_modal_response,
    "button_type" => "btn-info close-modal",
    "button_desc" => "OK"
]);

//modal unit
$dropdown_list = "";
foreach($data_unit as $unit) {
    if ((int) $unit->jumlah_anggota == 0) {
        $element_list = "<p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='$unit->ketua_unit' data-nama-unit='$unit->nama_unit' data-institusi-id='$unit->institusi_id'>
            Ketua $unit->nama_unit
        </p>";

        $dropdown_list .= $element_list;
    } else {
        $nama_unit = $unit->tenaga_pengajar == "0" ? "Anggota " : "Dosen ".$unit->nama_unit;
        $element_list = "<p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='$unit->ketua_unit' data-nama-unit='Ketua $unit->nama_unit' data-institusi-id='$unit->institusi_id'>
            Ketua $unit->nama_unit
        </p>
        <p class='dropdown-content-item' data-unit-id='$unit->id' data-ketua-unit='' data-nama-unit='$nama_unit' data-institusi-id='$unit->institusi_id'>
           $nama_unit
        </p>";
        $dropdown_list .= $element_list;
    } 
}

$element_modal_unit =<<<EOD
    <input type='hidden' name='row-number'/>
    <ul class='tag-list'></ul>
    <div class='dropdown' id='dropdown-list-unit'>
        <h4 style='margin-bottom:10px;'>Nama Unit :</h4>
        <input id='nama-unit' 
            type='text' class='dropdown-input input-block' name='nama-unit' placeholder='Masukan Nama Unit'/>
                <div class='dropdown-content'>
                {$dropdown_list}
                </div>
    </div>
    <h4 style='margin-bottom:10px;'>Target Institusi: </h4>
    <input class='input-block' type='number' value='1' placeholder='1' name='target'  id='target'/>

    <h4 style='margin-bottom:10px;'> Satuan :</h4>
    <select name='satuan' id='satuan' class='input-block' value='persen'>
        <option value='persen'>Persen</option>
        <option value='satuan'>Buah (Desimal)</option>
        <option value='satuan bulat'>Buah (Bulat)</option>
        <option value='orang'>Orang</option>
    </select>
EOD;

$this->load->view("template/modal_umum.php", [
    "id" => "modal-unit",
    "size" => "modal-content-md",
    "icon" => "fas fa-school",
    "title" => "Daftar Unit",
    "element" => $element_modal_unit,
    "button_type" => "btn-info",
    "button_desc" => "SAVE"
]);

?>



<!-- FOOTER-->
<?php  $this->load->view("partials/footer.php",[
    "js" => [
        base_url()."assets/js/page_form_buat_format_formaulir_rencana_kerja.js"
    ]
]);?>

<!-- Jquery UI -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<!-- data client -->
<script type="text/javascript">

let kamus_indikator = JSON.parse('<?php echo json_encode($data_kamus_indikator); ?>');
let unit = JSON.parse('<?php echo json_encode($data_unit);?>');
let indikator = JSON.parse('<?php echo json_encode($data_indikator);?>');
let baseUrl = '<?php echo base_url();?>';
let url_fetch_kpi = '<?php echo $action_get_kpi;?>';
let url_add_tahun = '<?php echo $action_add_tahun;?>';

setInputFilter($('input[name="bobot"]'), function(value) {
        return /^\d*\.?\d*$/.test(value); // Allow digits and '.' only, using a RegExp
});
</script>