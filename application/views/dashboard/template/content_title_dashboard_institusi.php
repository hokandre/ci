<div class="content-title">
    <div class="page-title">
        <h3> <i class="fas fa-tachometer-alt"></i> Kinerja Institusi
        <?php if($this->session->userdata("hak_akses") == 1) :?>
        
        <form id="ubah-institusi" action="<?=$action_lihat_kinerja_institusi;?>" style="display: inline-block;" method="post">
            <select name="institusi_id" id="institusi_id" class="toolbar toolbar-white">
                <?php $indexInstitusi = 0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                    <option <?=$selected_institusi_id == $institusi->id ? "selected" : "";?> value="<?=$institusi->id;?>">
                        <?=$institusi->nama_institusi;?>
                    </option>
                <?php endforeach;?>
            </select>
        </form>
        <?php endif;?> 
    </h3> 
    </div>

    <!-- Hak Akses bpm = 1 -->
    <?php if($this->session->userdata("hak_akses") == 1 ) :?>
        <div class="margin-left">
            <a class="toolbar" title="lihat pencapaian kinerja" href="<?=$action_lihat_kinerja_pribadi;?>" > <i class="fas fa-search"></i> Pencapaian Pribadi</a>
        </div>
    <?php endif;?>
</div>