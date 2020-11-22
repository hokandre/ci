<ul class="breadcrumb">
    <li>
        <?php if($show_bread_crumb_unit == "1") : ?>
            <form action="<?=$breadcrumb['unit']['url'];?>" method="post"  style="display: inline-block; margin-right:15px;">
                <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
                <input type="hidden" name="unit_id" value="<?=$selected_unit_crumb;?>"/>
                <input type="hidden" name="ketua_unit" value="<?=$selected_ketua_unit_crumb;?>"/>
                <input type="hidden" name="institusi_id" value="<?=$selected_institusi_crumb;?>"/>
                <input type="hidden" name="periode_id"value="<?=$selected_periode_tahun_semester_crumb?>"/>
                <input type="hidden" name="renstra_periode" value="<?=$selected_renstra_periode_crumb;?>"/>
                <input type="submit" value="<?=$breadcrumb['unit']['name']?>"/>
            </form>

        <?php endif;?>
    </li>
</ul>