<?php 
    $kode_akses_bpm = 1;
    $kode_akses_user_biasa = 4;
    $session_hak_akses = $this->session->userdata('hak_akses');
?>
<div class="content-title">
    <div class="page-title">    
        <h3>
            <i class="far fa-file-word"></i> Formulir Bidang Hasil Kinerja Utama
        </h3> 
    </div>
    <div class="margin-left">
        <?php if( $session_hak_akses == $kode_akses_bpm ) :?>
            <a class="toolbar" title="buat formulir baru" 
                href="<?=$action_buat_format_formulir;?>">
                <i class="fas fa-plus-circle"></i > Formulir
            </a>
            <a class="toolbar" title="edit formulir" 
                href="<?=$action_get_format_formulir;?>">
                <i class="fas fa-marker"></i> Formulir
            </a>
        <?php endif;?>
        <?php if( ($session_hak_akses == $kode_akses_user_biasa) ||  ($session_hak_akses == $kode_akses_bpm) ) :?>
            <a class="toolbar" title="cari laporan" 
                href="<?=$action_cari_laporan;?>">
                <i class="fas fa-search-plus"></i> Laporan
            </a>
        <?php endif;?>
    </div>
</div>