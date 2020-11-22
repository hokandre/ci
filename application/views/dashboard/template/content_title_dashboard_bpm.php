<?php $hak_akses_bpm = 1 ;?>

<div class="content-title">
    <div class="page-title">
    <h3> <i class="fas fa-tachometer-alt"></i> Kinerja 
    <!-- switch dashboard unit - institusi -->
    <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) :?>
        <form action="<?=$action_lihat_kinerja_unit;?>" style="display: inline-block;" method="post">
            <select name="switch-dashboard" id="switch-dashboard" class="toolbar toolbar-white">
                <option value="unit" selected>Unit</option>
                <option value="institusi">Institusi</option>
            </select>
        </form>
    <?php else:?>
        Unit
    <?php endif;?>

    <form id="form-cari" action="<?=$action_lihat_kinerja_unit;?>" method="post" style="display:inline;">
        <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>

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
    </h3> 
    </div>
</div>