<?php $hak_akses_bpm = 1;?>

<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain S. Strategis

        <?php 
        /**
         * Fitur : Memilih institusi untuk melihat pencapaian
         * Deskripsi : Dengan mengubah data institusi akan menampilkan  dashboard pencapaian indikator institusi.
         * User : BPM
         */
        ?>
        <?php if( $this->session->userdata("hak_akses") == $hak_akses_bpm ) : ?>
            <form id="ubah-institusi" method="post" style="display: inline-block;">
                <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                <input type="hidden" name="mode_individu" value="0"/>
                <select name="institusi_id" id="institusi_id" class="toolbar toolbar-white">
                    <?php $indexInstitusi=0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                        <option <?=($selected_institusi == $institusi->id) ? "selected" : "";?> value="<?=$institusi->id?>"> <?=$institusi->nama_institusi;?></option>
                    <?php endforeach;?>
                </select>
            </form>
        <?php endif;?>

        <?php 
        /**
         * Fitur : Untuk Memilih data pencapaian unit
         * Deskripsi : Dengan mengubah data unit akan menampilkan dasboard pencapaian indikator unit. User dengan hak akses BPM akan memiliki pilihan seluruh anggota unit, sedangkan user lain akan memiliki hanya data unit yang mereka ketuai atau terdaftar.
         * User : BPM, KETUA UNIT, ANGGOTA UNIT
         */
        ?>
        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm && $mode_individu == false) :?>
            <!-- USER : BPM-->
            <form id="ubah-unit" action="<?=$action_lihat_indikator_unit;?>" style="display: inline-block;" method="post">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/> 
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
                        <?=$unit->tenaga_pengajar == "$hak_akses_bpm" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                        </option>
                    <?php endif; ?>
                    <?php endforeach;?>
                </select>
            </form>
        <?php else : ?>
            <!-- USER : Ketua UNIT AND ANGGOTA UNIT-->
            <form id="ubah-unit" action="<?=$action_lihat_indikator_unit;?>" style="display: inline-block;" method="post">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/> 
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>
                <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
                <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
                <select name="unit_id" class="toolbar toolbar-white">
                    <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                        <option 
                            view="<?=$unit->view;?>"
                            value="<?=$unit->unit_id;?>" 
                            ketua-unit="<?=$unit->ketua == "$hak_akses_bpm" ? "$hak_akses_bpm" : "0";?>" 
                            <?= $ketua_unit == $unit->ketua && $selected_unit == $unit->unit_id ? "selected" : "";?>>
                            <?=$unit->tenaga_pengajar == "$hak_akses_bpm" && $unit->ketua != "$hak_akses_bpm" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                        </option>
                    <?php endforeach;?>
                </select>
            </form>
        <?php endif;?>
        
        <?php 
        /**
         * Fitur : Switch button menampilkan data pencapaian indikator versi unit atau versi user pribadi. 
         * Deskripsi : Fitur ini dikhusukan untuk ketua unit yang melihat data unitnya. Dengan mengubah switch button akan menampilkan dashboard pencapaian indikator unit apabila memilih unit dan dashboard pencapaian indikator user apabila memilih user.
         * User : KETUA UNIT
         */
        ?>
        <?php if($ketua_unit != '1' && $this->session->userdata("ketua_unit") == $selected_unit && $this->session->userdata("unit_id") == $selected_unit) : ?>
            <!-- lihat sebagai unit atau user-->
            <form id="form-versi" method="post" style="display:inline;">
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/> 
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/> 
                <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:$hak_akses_bpm0px;"> Unit </span> 
                <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:0px;"> User </span> 
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