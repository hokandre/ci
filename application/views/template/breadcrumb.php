<ul class="breadcrumb">
    <?php if($crumb_institusi == "1" ) :?>
        <li>
            <form action="<?=$data_crumb_institusi['url']?>" method="post">
                <input type="hidden" name="mode_individu" value="0"/>
                <input type="hidden" name="institusi_id" value="<?=$institusi_id;?>"/>
                <input type="hidden" name="periode_id" value="<?=$data_crumb_institusi["periode_id_institusi"];?>"/>
                <input type="hidden" name="renstra_id" value="<?=$data_crumb_institusi["renstra_id_institusi"];?>"/>
                <input type="submit" value="<?=$obj_institusi->nama_institusi;?>"/>

                <!-- Type -->
                <?=$element;?>
            </form>
        </li>
    <?php endif;?> 
    
    <?php if($crumb_unit == "1" ) :?>
        <li>
            <form action="<?=$data_crumb_unit['url']?>" method="post">
                <input type="hidden" name="mode_individu" value="0"/>
                <input type="hidden" name="unit_id" value="<?=$unit_id;?>"/>
                <input type="hidden" name="formulir_ketua" value="<?=$formulir_ketua;?>"/>
                <input type="hidden" name="periode_id" value="<?=$data_crumb_unit["periode_id_institusi"];?>"/>
                <input type="hidden" name="renstra_id" value="<?=$data_crumb_unit["renstra_id_institusi"];?>"/>
                <input type="submit" value="<?=$obj_unit->nama_unit;?>"/>

                <!-- Type -->
                <?=$element;?>
            </form>
        </li>
    <?php endif;?> 
</ul>
