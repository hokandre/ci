<div class="content-title">
    <div class="page-title">
        <h3> 
            <i class="fas fa-tachometer-alt"></i> Kinerja Individu Sebagai 

            <!-- lihat sebagai individu -->
            <form id="form-unit" action="<?=$action_lihat_kinerja_user;?>" method="post" style="display:inline;">

                <?php if(isset($mode_individu)) : ?>
                <?php if($mode_individu) : ?>
                    <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <?php endif;?>
                <?php endif;?>

                <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>

                <select name="unit_id" class="toolbar toolbar-white">
                <?php $indexUnit = 0; foreach($data_unit as $unit): $indexUnit++;?>
                <option 
                    view="<?=$unit->view;?>"
                    value="<?=$unit->unit_id;?>" 
                    ketua-unit="<?=$unit->ketua == "1" ? "1" : "0";?>" 
                    <?= $ketua_unit == $unit->ketua && $unit->unit_id == $selected_unit ? "selected" : "";?>>
                    <?=$unit->tenaga_pengajar == "1" && $unit->ketua != "1" ? "Dosen ".$unit->nama_unit : $unit->nama_unit;?>
                </option>
                <?php endforeach;?>
                </select>

            </form>

            <!-- lihat sebagai unit atau user-->
            <?php if($ketua_unit != '1' && $this->session->userdata('ketua_unit') == $selected_unit && $this->session->userdata('unit_id') == $selected_unit && $user_id == $this->session->userdata("id")) : ?>
            
                <form id="form-versi" method="post" style="display:inline;">
                    <?php if(isset($mode_individu)) : ?>
                    <?php if($mode_individu) : ?>
                        <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                    <?php endif;?>
                    <?php endif;?>

                    <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                    <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
                    <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                    <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                    <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
                    <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
                </form>
            <?php endif;?>
      </h3> 
    </div>
    
    <!-- Hak Akses bpm = 1 -->
    <?php if($this->session->userdata("hak_akses") == 1 ) :?>
        <div class="margin-left">
            <a class="toolbar" title="lihat pencapaian kinerja" href="<?=$action_lihat_kinerja_institusi;?>" > <i class="fas fa-search"></i> Pencapaian Institusi</a>
        </div>
    <?php endif;?>
</div>