<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Rencana Kerja</title>
  <!--Global CSS-->
  <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet">

    <!-- Just for this page css -->
    <link href=<?php echo base_url()."assets/css/page_detil_format_rencana_kerja.css";?> rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css"/>

  <style>
    td[name="col-sumber"] {
        width: 100px;
    }
    td[name="col-bidang"] {
        width: 100px;
    }
    td[name="col-bobot"] {
        width: 73px;
    }
    td[name="col-indikator"] {
        width: 200px;
    }   
    td[name="col-unit"] {
        width: 200px;
    }
    td[name="col-button"] {
        width: 100px;
    }

    td[name="col-kpi"] input[name="kpi"]{
        display: block;
        width: 100%;
    }   


    td[name="col-indikator"] select {
       width: 100%;
       max-width: 300px;
    }

    

  </style>

</head>

<body>
<!-- header -->
<?php $this->load->view('template/header.php');?>


<main>
<!-- sidebar-->
<?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

<section class="page-content" id="page-detil-format-rencana-kerja">
    <div class="content-title" >
        <div class="page-title">
            <h3><i class="far fa-file-word"></i> Format Formulir Hasil Bidang Kinerja Utama</h3> 
        </div>
    </div>

    <!-- breadcrumb -->
    <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <li><a href="<?=$crum['url'];?>"><?=$crum['name'];?></a></li>
        <?php endforeach;?>
    </ul>

    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-table"></i> Format Formulir Rencana Kerja</h4>
        </div>
        <div class="card-body">
            <form class="form-filter" action="<?=$action_search_format?>" method="post" id="form_search">
                <!-- Tahun -->
                <div class="form-filter-option">
                    <h4 for="tahun">Tahun</h4>
                    <select class="toolbar" name="tahun" id="tahun">
                        <?php $indexTahun=0; foreach($data_tahun as $tahun): $indexTahun++; ?>
                        <option value="<?=$tahun->tahun;?>" <?=(int)$selected_tahun == $tahun->tahun ? "selected" : "";?>><?=$tahun->tahun;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!--Semester-->
                <div class="form-filter-option">
                    <h4  for="semester">Semester</h4>
                    <div class="filter-option">
                        <div class="toolbar">
                            <input  name="semester" type="radio" value="1" id="ganjil" <?= $selected_semester == "1" ? "checked" : "";?>/>
                            <label style="background-color:transparent;" class="form-check-label" for="ganjil">
                                        Ganjil (<?php echo " September ".$selected_tahun." - Februari ".(($selected_tahun)+1); ?> )
                            </label>   
                        </div>
                        <div class="toolbar">
                            <input name="semester" type="radio" value="2" id="genap" <?= $selected_semester == "2" ? "checked" : "";?>>
                            <label class="form-check-label" for="genap">
                                    Genap (<?php echo " Maret ".$selected_tahun." - Agustus ".$selected_tahun; ?> )
                            </label>
                        </div>
                    </div>
                </div>
                <button class="toolbar" form="form_search">cari</button>

                <!-- membuat rencana kerja selanjutnya -->
            </form>

            <div class="container-flex" style="margin-bottom: 30px;">
                <button class="toolbar margin-left btn-toggle-modal" modal-target="modal-format-perseorangan">Buat Perseorangan</button>
                <button class="toolbar margin-left btn-toggle-modal" modal-target="modal-format">Gunakan Format Ini</button>
                <a id="navigasi-atas" href="#navigasi-bawah" class="toolbar margin-left">Ke Bawah</a>
            </div>

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
                                            <option value="<?=$bidang->id;?>" <?=$row_formulir["bidang_id"]==$bidang->id ? "selected" : "";?>><?=$bidang->nama_bidang;?></option>
                                        <?php endforeach;?>
                                </select>
                            </td>
                            <td class="table-cell"  name="col-bobot">
                               <input type="text" name="bobot" id="bobot" value="<?=$row_formulir["bobot"];?>"/>
                            </td>
                            <td class="table-cell"  name="col-indikator">
                                <select name="indikator">
                                    <?php $i=0; foreach($data_indikator as $row) : $i++; ?>
                                        <option class='text-truncate' value='<?=$row->id?>' <?=$row_formulir["indikator_id"] == $row->id ? "selected" : ""?>><?=$row->nama_indikator?></option>";
                                    <?php endforeach;?>
                                </select>
                            </td>
                            <td class="table-cell"  name="col-kpi">
                                <div class="dropdown">
                                    <input data-kpi-id="<?=$row_formulir["kpi_id"];?>" data-nama-kpi="<?=$row_formulir["nama_kpi"];?>" data-bidang-kpi="<?=$row_formulir["bidang_id"];?>" class="dropdown-input" type="text" name="kpi" value="<?=$row_formulir["nama_kpi"];?>"/>
                                    <div class="dropdown-content">
                                        
                                    </div>
                                </div>
                            </td>
    
                            <td class="table-cell"  name="col-unit">
                                <i modal-target="modal-unit" class="far fa-edit btn-toggle-modal btn-toggle-modal-unit"> Daftar Unit</i>
                                <ul class="tag-list"  data-removed="[]" data-inserted="[]" data-before='<?= json_encode($row_formulir["unit"]);?>'>
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
                                        <li class="tag-list-item btn-toggle-modal btn-toggle-modal-unit" 
                                            modal-target="modal-unit"
                                            data-unit-id="<?= $unit["unit_id"];?>" 
                                            data-ketua-unit="<?= $unit["ketua_unit"];?>" 
                                            data-nama-unit="<?=$namaUnit;?>"
                                            data-target="<?=$unit["target"];?>"
                                            data-institusi-id="<?=$unit["institusi_id"];?>"
                                            data-satuan="<?=$unit["satuan"];?>">

                                            <span class="tag-list-item-nama-unit"><?= $namaUnit;?></span>
                                            <span class="tag-list-item-target">target : <?=$unit["target"]." ".$simbolSatuan;?></span>
                                            <span class="tag-list-item-close">&times;</span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td class="table-cell"  name="col-button">
                                <input type="hidden" name="periode_id" value="<?=$selected_periode;?>">
                                <input type="hidden" name="kpi" value="<?=$row_formulir['kpi_id'];?>">
                                <button class="btn-update">Update</button>
                                <button class="btn-delete">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
        foreach($data_unit as $unit){
           //assing kpi
            foreach($format_formulir as $row_formulir){
                foreach($row_formulir["unit"] as $unit_from_row){
                    //untuk ketua unit
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
                        break;
                    }
                    //untuk unit anggota unit
                    else if(($unit_from_row["unit_id"]) == $unit->id && $unit_from_row["ketua_unit"] == ""){
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
                        break;
                    }
                }
            }

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


<!-- modal unit -->
<div id="modal-unit" class="modal">

    <div class="modal-content-md">

        <div class="modal-header">
            <h4> <i class="fas fa-school"></i> Daftar Unit</h4>
            <span class="close-modal">&times;</span>
        </div>

        <div class="modal-body">
            <input type="hidden" name="row-number"/>

            <ul class="tag-list" data-before="[]" data-inserted="[]" data-removed="[]"></ul>
            
            <div class="dropdown" id="dropdown-list-unit">
                <h4 style="margin-bottom:10px;">Nama Unit :</h4>
                <input id="nama-unit" type="text" class="dropdown-input input-block" name="nama-unit" placeholder="Masukan Nama Unit"/>

                <div class="dropdown-content">
                    <?php foreach($data_unit as $unit){
                        if((int) $unit->jumlah_anggota == 0) {?>
                            <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="<?php echo $unit->ketua_unit;?>" data-nama-unit="<?php echo $unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                <?php echo "Ketua ".$unit->nama_unit; ?>
                            </p>
                        <?php } else { ?>
                                <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="<?php echo $unit->ketua_unit;?>" data-nama-unit="<?php echo "Ketua ".$unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                    <?php echo "Ketua ".$unit->nama_unit; ?>
                                </p>
                                <p class='dropdown-content-item' data-unit-id="<?php echo $unit->id;?>" data-ketua-unit="" data-nama-unit="<?php echo "Anggota ".$unit->nama_unit;?>" data-institusi-id="<?php echo $unit->institusi_id;?>">
                                    <?php echo $unit->tenaga_pengajar == "1" ? "Dosen ".$unit->nama_unit : "Anggota ".$unit->nama_unit; ?>
                                </p>
                        <?php } ?>
                    <?php }?>
                </div>

            </div>

            <h4 style="margin-bottom:10px;">Target Institusi: </h4>
            <input class="input-block" type="number" name="target" placeholder="0" id="target"/>

            <h4 style="margin-bottom:10px;"> Satuan :</h4>
            <select name="satuan" id="satuan" class="input-block">
                <option value="persen">Persen</option>
                <option value="satuan">Buah (Desimal)</option>
                <option value="satuan bulat">Buah (Bulat)</option>
                <option value="orang">Orang</option>
            </select>
        </div>

        <div class="modal-footer">
            <button id="btn-save" class="btn-info">save</button>
        </div>
    </div>
</div>


<!-- modal response -->
<div class="modal" id="modal-message" style="z-index:200;">
    <div class="modal-content-md">
        <div class="modal-header">
            <h4> <i class="far fa-comment-dots"> </i>Message</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body">
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
        </div>

        <div class="modal-footer">
            <button class="btn-info close-modal">OK</button>
        </div>
    </div>
</div>

<div id="error"></div>

<!-- modal tahun -->
<div class="modal" id="modal-tahun" style="z-index:100;">
    <div class="modal-content-sm">
        <div class="modal-header">
            <h4> <i class="far fa-calendar-plus"></i> Tahun</h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text center">
            <input type="hidden" name="tahun"/>
            <p>Tambahkan data tahun <span id="tahun-selanjutnya"></span> ?</p>
        </div>

        <div class="modal-footer">
            <button class="btn-update" id="btn-save">save</button>
        </div>
    </div>
</div>


<!-- modal format -->
<div class="modal" id="modal-format">
    <div class="modal-content-sm">
        <div class="modal-header">
            <h4> <i class="far fa-calendar-plus"></i> Pilih Tahun dan Semester Untuk Dibuat: </h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text center">
            <div class="flex-row">
                <h4 style="margin-bottom:10px;"> Tahun :</h4>
                <select class="toolbar" name="tahun-target" id="tahun-target">
                    <?php $indexTahun=0; foreach($data_tahun as $tahun): $indexTahun++; ?>
                        <option value="<?=$tahun->tahun;?>" <?=(int)$selected_tahun == $tahun->tahun ? "selected" : "";?>><?=$tahun->tahun;?></option>
                    <?php endforeach; ?>
                </select>

                <h4 style="margin-bottom:10px;"> Semester :</h4>
                <select class="toolbar" name="semester-target" id="semester-target">
                    <option value="1">Ganjil</option>
                    <option value="2">Genap</option>
                </select>

                <i id="btn-toggle-modal-tahun" modal-target="modal-tahun" class="far fa-question-circle btn-toggle-modal" title="tambah data tahun"></i>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn-update" id="btn-copy-format">save</button>
        </div>
    </div>
</div>

<!-- modal format -->
<div class="modal" id="modal-format-perseorangan">
    <div class="modal-content-md">
        <div class="modal-header">
            <h4> <i class="far fa-calendar-plus"></i> Pilih User: </h4>
            <span class="close-modal">&times;</span>
        </div>
        <div class="modal-body text center">
                <h4 style="margin-bottom:10px;"> Unit :</h4>
                <select class="toolbar" name="tahun-target" id="tahun-target">
                    <?php foreach($data_unit_pada_formulir as $unit_pada_formulir):  ?>
                        <?php                                                
                          $namaUnit = $unit_pada_formulir["ketua_unit"] == "" ? $unit_pada_formulir["tenaga_pengajar"] == "0" ? "Anggota ".$unit_pada_formulir["nama_unit"] : "Dosen ".$unit_pada_formulir["nama_unit"] : "Ketua ".$unit_pada_formulir["nama_unit"]; 
                        ?>
                        <option 
                            value="<?=$unit_pada_formulir["unit_id"];?>"
                            data-ketua-unit="<?=$unit_pada_formulir["ketua_unit"];?>"
                        ><?=$namaUnit;?></option>
                    <?php endforeach; ?>
                </select>

                <h4 style="margin-bottom:10px;"> User :</h4>
                <select class="toolbar" name="semester-target" id="semester-target">
                    <option value="1">Ganjil</option>
                    <option value="2">Genap</option>
                </select>
        </div>

        <div class="modal-footer">
            <button class="btn-update" id="btn-copy-format">save</button>
        </div>
    </div>
</div>

</body>
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

</script>


<!-- Jquery Core-->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<!-- Jquery UI -->
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js" integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
<!-- Own js file for global setting -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>
<!-- Own js file for current page setting -->
<script src=<?php echo base_url()."assets/js/page_detil_format_formulir_rencana_kerja.js";?>></script>
<script>
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
</html>
