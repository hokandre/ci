<?php if(isset($show_bread_crumb_institusi)) : ?>
<?php if($show_bread_crumb_institusi == "1" ) :?>
    <ul class="breadcrumb">
        <?php $indexBread=0; foreach($breadcrumb as $crum): $indexBread++;?>
            <li>
            <form action="<?=$crum['url']?>" method="post">
            <!-- for bread crumb -->
                <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
                <input type="submit" value="<?=$crum['name']?>"/>
            </form>
            </li>
        <?php endforeach;?>
    </ul>
<?php endif;?>
<?php endif;?>