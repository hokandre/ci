<ul class="breadcrumb">
    <?php if($show_breadcrumb_list == 1):?>
        <li>
            <form action="<?=$breadcrumb_list_url?>" method="post" style="margin-right:30px;">
                <input type="hidden" value="<?=$breadcrumb_list_tahun;?>" name="tahun"/>
                <input type="hidden" value="<?=$breadcrumb_list_institusi;?>" name="institusi"/>
                <input type="hidden" value="<?=$breadcrumb_list_ganjil;?>" name="ganjil"/>
                <input type="hidden" value="<?=$breadcrumb_list_genap?>" name="genap"/>
                <input type="submit" value="<?=$breadcrumb_list_nama;?>"/>
            </form>
        </li>
    <?php endif;?>

    <?php if($show_breadcrumb_detil == 1):?>
        <li>
            <form action="<?=$breadcrumb_detil_url.$breadcrumb_detil_unit;?>" method="post">
                <!-- for bread crumb list -->
                <?php if(isset($show_breadcrumb_list)) : ?>
                    <?php if($show_breadcrumb_list == 1) : ?>                
                        <input type="hidden" name="show_bread_crumb_list" value="1"/>
                        <input type="hidden" name="list_institusi_crumb" value="<?=$breadcrumb_list_institusi;?>">
                        <input type="hidden" name="list_tahun_crumb" value="<?=$breadcrumb_list_tahun;?>"/>
                        <input type="hidden" name="list_ganjil_crumb" value="<?=$breadcrumb_list_ganjil;?>"/>
                        <input type="hidden" name="list_genap_crumb" value="<?=$breadcrumb_list_genap;?>"/>
                    <?php endif;?>
                <?php endif;?>
                <!-- for bread crumb detil -->
                <input type="hidden" name="show_bread_crumb_detil" value="1"/>
                <input type="hidden" value="<?=$breadcrumb_detil_unit;?>" name="detil_unit_crumb">
                <input type="submit" value="<?=$breadcrumb_detil_nama;?>"/>
            </form>
        </li>
    <?php endif;?>
</ul>