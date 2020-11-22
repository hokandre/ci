<?php if($this->session->userdata("nama_hak_akses") == "bpm" && $mode_individu == "0")  :?>
    <form method="post" action="<?=$data_aksi["action_lihat_bidang_user"];?>">
        <input type="hidden" name="mode_individu" value="1"/>
        <button class="toolbar" title="Lihat pencapaian bidang" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Pribadi</button>
    </form>
<?php else :?>
    <form method="post" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>">
        <input type="hidden" name="mode_individu" value="0"/>
        <button class="toolbar" title="Lihat pencapaian bidang" ><i class="fas fa-plus-circle" aria-hidden="true"></i> Institusi</button>
    </form>
<?php endif;?>