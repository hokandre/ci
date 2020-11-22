<?php $hak_akses_bpm = 1;?>

<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain User
                <!-- lihat sebagai individu -->
                <?php if($this->session->userdata("hak_akses") == 1 &&  $mode_individu == false) :?>
                <form id="ubah-unit" action="<?=$action_lihat_indikator_unit;?>" style="display: inline-block;" method="post">
                    <input type="hidden" name="mode_individu" value="<?=$mode_individu?>"/>
                    <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                    <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                    <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                    <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
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
            <?php else : ?>
            <form id="ubah-unit" action="<?=$action_lihat_indikator_user;?>" method="post" style="display:inline;">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>                
                <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                <input type="hidden" name="user_id" value="<?=$selected_user;?>"/>
                <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
                <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                <select name="unit_id" class="toolbar toolbar-white">
                    <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                    <option 
                        view="<?=$unit->view;?>"
                        value="<?=$unit->unit_id;?>" 
                        ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                        <?= $ketua_unit == $unit->ketua && $selected_unit == $unit->unit_id ? "selected" : "";?>>
                        <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                    </option>
                    <?php endforeach;?>
                </select>
            </form>
            <?php endif;?>

            <?php if( 
            ($ketua_unit != '1')  
            && ($this->session->userdata("ketua_unit") == $selected_unit) 
            && ($this->session->userdata("unit_id") == $selected_unit)
            && ($this->session->userdata("id") == $selected_user )
            ) : ?>
                <!-- lihat sebagai unit atau user-->
                <form id="form-versi" action="" method="post" style="display:inline;">
                    <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                    <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                    <input type="hidden" value="<?=$selected_periode_tahun_semetser;?>" name="periode_id"/>
                    <input type="hidden" value="<?=$selected_renstra_periode;?>" name="renstra_periode"/>
                    <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                    <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
                </form>
            <?php endif;?>
        </h3> 
    </div>
    <div class="margin-left">
    <?php 
        /**
         * Fitur : menampilkan data pencapaian indikator user pribadi / user yang login. 
         * Deskripsi : Fitur ini dikhususkan untuk BPM yang melihat data pencpaian dirinya. Dengan mengubah switch button akan menampilkan dashboard pencapaian indikator user.
         * User : BPM
         */
        ?>
        <?php if($mode_individu == false && $this->session->userdata("hak_akses") == $hak_akses_bpm) : ?>
            <form method="post" action="<?=$action_lihat_indikator_user;?>">
                <input type="hidden" name="mode_individu" value="1"/>
                <button class="toolbar" title="Lihat pencapaian indikator" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pribadi</button>
            </form>
        <?php endif;?>

        <?php if($mode_individu == true && $this->session->userdata("hak_akses") == $hak_akses_bpm) : ?>
            <form method="post" action="<?=$action_lihat_indikator_unit;?>">
                <input type="hidden" name="mode_individu" value="0"/>
                <button class="toolbar" title="Lihat pencapaian indikator" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Institusi</button>
            </form>
        <?php endif;?>
    </div>
</div>