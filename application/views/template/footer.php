<!-- Jquery -->
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

<!-- Font Awsome -->
<script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>

<!-- Global setting js -->
<script src=<?php echo base_url()."assets/js/global.js";?>></script>

<!-- custom js -->
<?php if ( isset($js) ) :?>
    <?php foreach($js as $file) : ?>
        <script src="<?=$file;?>" crossorigin="anonymous"></script>
    <?php endforeach; ?>
<?php endif;?>