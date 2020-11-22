<aside class="not-printed">
  <ul class="sidebar">
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/dashboard";?>  title="Dashoard">
        <i class="fas fa-tachometer-alt" ></i>
        <span class="sidebar-link-description">Kinerja</span>
      </a>
    </li>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/formulir_rencana_kerja";?> title="Formulir rencana kerja">
        <i class="far fa-file-word" ></i>
        <span class="sidebar-link-description">Formulir Hasil Bidang Kinerja Utama</span>
      </a>
    </li>
    <?php if($this->session->userdata("nama_hak_akses") == "admin") : ?>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/kpi";?> title="Key performance indicator">
        <i class="far fa-lightbulb" ></i> 
        <span class="sidebar-link-description">Key Performance Indicator</span>
      </a>
    </li>
    <li>
        <a class="sidebar-link" href=<?php echo base_url()."index.php/unit"; ?> title="Unit">
          <i class="fas fa-university" ></i>
          <span class="sidebar-link-description">Unit</span>
        </a>
    </li>
      <li>
        <a class="sidebar-link" href=<?php echo base_url()."index.php/indikator";?> title="indikator">
          <i class="fas fa-bullseye"></i>
            <span class="sidebar-link-description">Sasaran Strategis</span>
        </a>
      </li>
      <li>
        <a class="sidebar-link" href=<?php echo base_url()."index.php/user";?> title="User">
          <i class="fa fa-user" aria-hidden="true"></i>
          <span class="sidebar-link-description">Kelola Pengguna</span>
        </a>
      </li>
    <?php else:?>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/kpi/pencapaian_user";?> title="Key performance indicator">
        <i class="far fa-lightbulb" ></i> 
        <span class="sidebar-link-description">Key Performance Indicator</span>
      </a>
    </li>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/indikator/pencapaian_user";?> title="indikator">
        <i class="fas fa-bullseye"></i>
          <span class="sidebar-link-description">Sasaran Strategis</span>
      </a>
    </li>
    <?php endif;?>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/bidang";?> title="Bidang indikator">
          <i class="fas fa-globe"></i> 
          <span class="sidebar-link-description">Bidang Indikator</span>
      </a>
    </li>
    <li>
      <a class="sidebar-link" href=<?php echo base_url()."index.php/user/account";?> title="Account Anda">
          <i class="fas fa-user-cog"></i>
          <span class="sidebar-link-description">Kelola Akun Anda</span>
      </a>
    </li>

  </ul>
</aside>