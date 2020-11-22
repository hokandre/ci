<!--  Header -->
<?php $this->load->view("template/header.php", ["title" => "Login"]); ?>
    <div class="login-page">
        <div class="form">
            <?php if($error != NULL) : ?>;
                <p class="alert error"><?=$error;?></p>
            <?php endif;?>    
            <form class="login-form" action="<?= $action ?>" method="post">
                <input type="text" placeholder="username" name="username" />
                <input type="password" placeholder="password" name="password" />
                <button >login</button>
            </form>
        </div>
    </div>

<!-- Footer -->
<?php $this->load->view("template/footer.php");?>
