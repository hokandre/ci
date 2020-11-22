<?php if(isset($show_bread_crumb)) : ?>
    <?php if($show_bread_crumb == 1 ) :?>
    <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <li>
              <form action="<?=$crum['url']?>" method="post">
                 <input type="hidden" value="<?=$selected_institusi_id;?>" name="institusi_id"/>
                 <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                 <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                 <input type="submit" value="<?=$crum['name']?>"/>
              </form>
            </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>
<?php endif;?>