<!--Personaliation role-->
<input type="hidden" name="mode_individu" value="<?=isset($overide)&&$overide["mode_individu"] != null ? $overide["mode_individu"] : $mode_individu;?>"/>
<input type="hidden" name="institusi_id" value="<?=$institusi_id;?>"/>
<input type="hidden" name="unit_id" value="<?=$unit_id;?>"/>
<input type="hidden" name="formulir_ketua" value="<?=$formulir_ketua;?>"/>

<!--periodic role -->
<input type="hidden" name="periode_id" value="<?=$periode_id;?>"/>
<input type="hidden" name="renstra_id" value="<?=$renstra_id;?>"/>

<!-- data -->
<input type="hidden" name="bidang_id" value="<?=$bidang_id;?>"/>

<!-- crumb -->
<input type="hidden" name="crumb_institusi" value="<?=$crumb_institusi;?>"/>
<?php if($crumb_institusi == "1") :?>
    <input type="hidden" name="periode_id_institusi" value="<?=$data_crumb_institusi["periode_id_institusi"]?>"/>
    <input type="hidden" name="renstra_id_institusi" value="<?=$data_crumb_institusi["renstra_id_institusi"]?>"/>
<?php endif;?>
