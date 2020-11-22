<ul class="breadcrumb">
    <li>
        <?php if($show_bread_crumb_institusi == "1") : ?>
            <form action="<?=$breadcrumb['institusi']['url'];?>" method="post" style="display: inline-block; margin-right:15px;">
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
                <input type="submit" value="<?=$breadcrumb['institusi']['name']?>"/>
            </form>
        <?php endif;?>
        <?php if($show_bread_crumb_unit == "1") : ?>
            <form action="<?=$breadcrumb['unit']['url'];?>" method="post"  style="display: inline-block; margin-right:15px;">
                <!-- for bread crumb institusi -->
                <?php if($show_bread_crumb_institusi == "1") : ?>
                <input type="hidden" name="show_bread_crumb_institusi" value="1"/>
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi;?>"/>
                <input type="hidden" name="periode_id_institusi" value="<?=$selected_periode_tahun_semester_institusi;?>"/>
                <input type="hidden" name="renstra_periode_institusi" value="<?=$selected_renstra_periode_institusi;?>"/>
                <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
                <?php endif; ?>
                <!-- for bread crum unit -->
                <input type="hidden" name="show_bread_crumb_unit" value="1"/>
                <input type="hidden" name="unit_id" value="<?=$selected_unit;?>">
                <input type="hidden" name="periode_id_unit" value="<?=$selected_periode_tahun_semetser;?>"/>
                <input type="hidden" name="renstra_periode_unit" value="<?=$selected_renstra_periode;?>"/>
                <input type="hidden" name="ketua_unit" value="<?=$ketua_unit;?>"/>

                <input type="hidden" name="unit_id" value="<?=$selected_unit;?>"/>
                <input type="hidden" name="ketua_unit" value="<?=$selected_ketua_unit;?>"/>
                <input type="hidden" name="sumber_id" value="<?=$selected_sumber;?>"/>
                <input type="hidden" name="periode_id"value="<?=$selected_periode_tahun_semester_unit?>"/>
                <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode_unit;?>"/>
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <input type="submit" value="<?=$breadcrumb['unit']['name']?>"/>
            </form>

        <?php endif;?>
    </li>
</ul>