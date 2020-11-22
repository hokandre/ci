<ul class="breadcrumb">
    <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
        <li><a href="<?=$crum['url'];?>"><?=$crum['name'];?></a></li>
    <?php endforeach;?>
</ul>