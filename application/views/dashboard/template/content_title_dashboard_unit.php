<div class="content-title">
    <div class="page-title">
        <h3> 
            <i class="fas fa-tachometer-alt"></i> Kinerja 
            <!-- switch dashbard unit - institusi -->
            <!-- Hak Akses 1 = login sebagai bpm -->
            <?php if($this->session->userdata("hak_akses") == 1) :?>
            <form action="" style="display: inline-block;">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <select name="switch-dashboard" id="switch-dashboard" class="toolbar toolbar-white">
                    <option value="unit" selected>Unit</option>
                    <option value="institusi">Institusi</option>
                </select>
            </form>
            <?php else:?>
            Unit
            <?php endif;?> 

            <!--Bpm-->
            <?php if($this->session->userdata("hak_akses") == 1 && $mode_individu == false) :?>
                <form id="form-cari" action="<?=$action_lihat_kinerja_unit;?>" method="post" style="display:inline;">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
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
            <?php else : ?>
                <form id="form-unit" action="<?=$action_lihat_kinerja_user;?>" method="post" style="display:inline;">
                    <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                    <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                    <select name="unit_id" class="toolbar toolbar-white">
                        <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                            <option 
                                view="<?=$unit->view;?>"
                                value="<?=$unit->unit_id;?>" 
                                ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                                <?= ($ketua_unit == $unit->ketua && $unit->unit_id  == $selected_unit)? "selected" : "";?>>
                                <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                            </option>
                        <?php endforeach;?>
                    </select>
                </form>

                <?php if($ketua_unit != '1' && $this->session->userdata('ketua_unit') == $selected_unit && $this->session->userdata('unit_id') == $selected_unit) : ?>
                    <!-- lihat sebagai unit atau user-->
                    <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                    <form id="form-versi" method="post" style="display:inline;">
                    <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                    <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                    <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                    <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
                    </form>
                <?php endif;?>
            <?php endif;?>
        </h3> 
    </div>
</div>