<?php $hak_akses_bpm = 1 ;?>

<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain Bidang 
        <!-- switch dashbard unit - institusi -->
        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) :?>
        <form id="ubah-bidang" action="<?=$action_lihat_bidang_institusi;?>" style="display: inline-block;" method="post">
            <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
            <input type="hidden" name="periode_id" value="<?=$selected_periode_tahun_semetser;?>"/>
            <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode;?>"/>
            <select name="bidang_id" id="bidang_id" class="toolbar toolbar-white">
                <?php $indexBidang = 0; foreach($data_bidang as $bidang) : $indexBidang++;?>
                    <option <?=$selected_bidang == $bidang->id ? "selected" : "";?> value="<?=$bidang->id;?>">
                        <?=$bidang->nama_bidang;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        
        <form id="ubah-institusi" action="<?=$action_lihat_bidang_institusi;?>" style="display: inline-block;" method="post">
            <select name="institusi_id" id="institusi_id" class="toolbar toolbar-white">
                <?php $indexInstitusi = 0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                    <option <?=$selected_institusi == $institusi->id ? "selected" : "";?> value="<?=$institusi->id;?>">
                        <?=$institusi->nama_institusi;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        <?php endif;?> 
    </h3> 
    </div>
    <div class="margin-left">
        <a href="<?=$action_lihat_bidang_user;?>" class="toolbar toolbar-white"> pribadi</a>
    </div>
</div>