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
</form>

<div class="container-flex" style="margin-bottom: 30px;">
    <button class="toolbar margin-left btn-toggle-modal" modal-target="modal-format-perseorangan">
        Buat Perseorangan
    </button>
    <button class="toolbar margin-left btn-toggle-modal" modal-target="modal-format">
        Gunakan Format Ini
    </button>
    <a id="navigasi-atas" href="#navigasi-bawah" class="toolbar margin-left">Ke Bawah</a>
</div>