<?php $hak_akses_bpm = 1 ;?>
<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Pencapain Sumber Kpi 
        <!-- switch dashbard unit - institusi -->
        <?php if($this->session->userdata("hak_akses") == $hak_akses_bpm) :?>
            <form id="ubah-sumber" action="<?=$action_lihat_kpi_institusi;?>" style="display: inline-block;" method="post">
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
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
            
            <form id="ubah-institusi" action="<?=$action_lihat_kpi_institusi;?>" style="display: inline-block;" method="post">
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
        <form method="post" action="<?=$action_lihat_kpi_user;?>">
            <input type="hidden" name="mode_individu" value="true"/>
            <button class="toolbar" title="Lihat pencapaian kpi" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pribadi</button>
        </form>
    </div>
</div>