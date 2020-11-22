<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain 
        <form id="ubah-kpi" action="<?=$action_lihat_kpi_unit;?>" style="display: inline-block;" method="post">
            <!-- for bread crumb -->
            <?php if(isset($show_bread_crumb_institusi)) : ?>
            <?php if($show_bread_crumb_institusi == "1" ) :?>
                        <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                        <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                        <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                        <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
            <?php endif;?>
            <?php endif;?>

            <?php if(isset($mode_individu)) : ?>
            <?php if($mode_individu) : ?>
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
            <?php endif;?>
            <?php endif;?>

            <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
            <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
            <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
            <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
            <select name="sumber_id" id="sumber_id" class="toolbar toolbar-white">
                <?php $indexsumber = 0; foreach($data_sumber as $sumber) : $indexsumber++;?>
                    <option <?=$selected_sumber == $sumber->id ? "selected" : "";?> value="<?=$sumber->id;?>">
                        <?=$sumber->nama_sumber;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        
        <form id="ubah-unit" action="<?=$action_lihat_kpi_unit;?>" style="display: inline-block;" method="post">
            <?php if(isset($mode_individu)) : ?>
            <?php if($mode_individu) : ?>
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
            <?php endif;?>
            <?php endif;?>

            <!-- for bread crumb -->
            <?php if(isset($show_bread_crumb_institusi)) : ?>
            <?php if($show_bread_crumb_institusi == "1" ) :?>
                        <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                        <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                        <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                        <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
            <?php endif;?>
            <?php endif;?>

            <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
            <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
            <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
            <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
            <select name="unit_id" class="toolbar toolbar-white">
            <?php if ($this->session->userdata("hak_akses") == 1 && $mode_individu == false) : ?>
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
                    <?php else : ?>
                        <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                        <option 
                            view="<?=$unit->view;?>"
                            value="<?=$unit->unit_id;?>" 
                            ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                            <?= ($ketua_unit == $unit->ketua  && $unit->unit_id == $selected_unit) ? "selected" : "";?>>
                            <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                        </option>
                        <?php endforeach;?>
                    <?php endif;?> 
            </select>
        </form>

        <?php if($ketua_unit != '1' && $this->session->userdata("ketua_unit") == $selected_unit && $this->session->userdata("unit_id") == $selected_unit ) : ?>
            <?php if(isset($mode_individu)) : ?>
            <?php if($mode_individu) : ?>
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
            <?php endif;?>
            <?php endif;?>

            <!-- lihat sebagai unit atau user-->
            <form id="form-versi" method="post" style="display:inline;">
                <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
            </form>
        <?php endif;?>
        
    </h3> 
    </div>
</div>