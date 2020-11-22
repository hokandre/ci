<?php $hak_akses_bpm = 1; ?>

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