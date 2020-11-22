<?php if(isset($show_bread_crumb_unit)) : ?>
    <?php if($show_bread_crumb_unit == 1 ) :?>
    <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <?php if($crum["type"] == "unit") : ?>
                <li>
                    <form action="<?=$crum['url']?>" method="post">
                        <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
                        <input type="hidden" value="<?=$user_id;?>" name="user_id"/>
                        <input type="hidden" value="<?=$ketua_unit?>" name="ketua_unit"/>
                        <input type="hidden" value="<?=$renstra_periode->id;?>" name="renstra_periode"/>
                        <input type="hidden" value="<?=$tahun."-".$semester;?>" name="periode_id"/>
                        <input type="submit" value="<?=$crum['name']?>"/>
                        <input type="hidden" name="versi"  value="unit"/>
                        <?php  if($show_bread_crumb_institusi) : ?>
                        <input type="hidden" name="show_bread_crumb" value="1"/>
                        <input type="hidden" name="institusi_id" value="<?=$selected_institusi_id;?>"/>
                        <?php endif;?>
                    </form>
                </li>
            <?php else : ?>
                <li>
                    <form action="<?=$crum['url']?>" method="post">
                        <input type="hidden" value="<?=$selected_institusi_id;?>" name="institusi_id"/>
                        <input type="hidden" value="<?=$selected_institusi_periode_id;?>" name="periode_id"/>
                        <input type="hidden" value="<?=$selected_institusi_renstra_periode?>" name="renstra_periode"/>
                        <input type="submit" value="<?=$crum['name']?>"/>
                    </form>
                </li>
            <?php endif;?>
        <?php endforeach;?>
    </ul>
<?php endif;?>
<?php endif;?>