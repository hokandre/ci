<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--Global CSS-->
    <link href=<?php echo base_url()."assets/css/global.css";?> rel="stylesheet"/>
    <title> <?=$page_title;?> </title>

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>

    <!-- Font Awsome -->
    <script src="https://kit.fontawesome.com/60acd380e3.js" crossorigin="anonymous"></script>
    
    <!-- custom css-->
    <?php if ( isset($css) ) : ?>
        <?php $index = 0; foreach($css as $file) : $index++; ?>
            <link href='<?= $file;?>' rel="stylesheet"/>
        <?php endforeach;?>
    <?php endif;?>
</head>
