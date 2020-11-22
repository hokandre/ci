<?php if($this->session->userdata("logged")) : ?>
    <header class="not-printed">
        <div class="header-logo" style="background-color:var(--dark-green);">
            <h3>Multi Data Palembang</h3>
        </div>
        <div class="header-menu shadow">
            <a id="btn-shrink-sidebar" href="#" class="header-menu-link" title="Perkecil sidebar"> &lt; </a>
            <a id="btn-expand-sidebar" href="#" class="header-menu-link hide" title="Perbesar sidebar">&gt;</a>
            <a href="<?php echo base_url().('index.php/auth/logout');?>" class="header-menu-link"><?=$this->session->userdata("user_logged")["nama_user"];?> , logout</a>
        </div>
    </header>
<?php endif;?>