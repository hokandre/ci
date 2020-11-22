<?php $hak_akses_bpm = 1;?>

<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain Bidang 
        <!-- switch dashbard unit - institusi -->
        <?php if($this->session->userdata("hak_akses") == 1) :?>
        <form id="ubah-bidang" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>" style="display: inline-block;" method="post">
            <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
            <input type="hidden" name="institusi_id" value="<?=$institusi_id;?>"/>
            <input type="hidden" name="periode_id" value="<?=$periode_id;?>"/>
            <input type="hidden" name="renstra_periode" value="<?=$renstra_id;?>"/>
            <select name="bidang_id" id="bidang_id" class="toolbar toolbar-white">
                <?php $indexBidang = 0; foreach($data_bidang as $bidang) : $indexBidang++;?>
                    <option <?=$bidang_id == $bidang->id ? "selected" : "";?> value="<?=$bidang->id;?>">
                        <?=$bidang->nama_bidang;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        
        <form id="ubah-institusi" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>" style="display: inline-block;" method="post">
            <input type="hidden" name="mode_individu" value="0"/>
            <select name="institusi_id" id="institusi_id" class="toolbar toolbar-white">
                <?php $indexInstitusi = 0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                    <option <?=$institusi_id == $institusi->id ? "selected" : "";?> value="<?=$institusi->id;?>">
                        <?=$institusi->nama_institusi;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        <?php endif;?> 
    </h3> 
    </div>
    <div class="margin-left">
        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm && $mode_individu == false)  :?>
            <form method="post" action="<?=$action_lihat_bidang_user;?>">
                <input type="hidden" name="mode_individu" value="1"/>
                <button class="toolbar" title="Lihat pencapaian bidang" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pribadi</button>
            </form>
        <?php endif;?>
        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm && $mode_individu == true)  :?>
            <form method="post" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>">
                <input type="hidden" name="mode_individu" value="0"/>
                <button class="toolbar" title="Lihat pencapaian bidang" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Institusi</button>
            </form>
        <?php endif;?>
    </div>
</div>