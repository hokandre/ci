<!--Personaliation role-->
 <input type="hidden" name="mode_individu" value="<?=isset($overide)&&$overide["mode_individu"] != null ? $overide["mode_individu"] : $mode_individu;?>"/>
<input type="hidden" name="institusi_id" value="<?=$institusi_id;?>"/>

<!--periodic role -->
<input type="hidden" name="periode_id" value="<?=$periode_id;?>"/>
<input type="hidden" name="renstra_id" value="<?=$renstra_id;?>"/>

<!-- data -->
<input type="hidden" name="bidang_id" value="<?=$bidang_id;?>"/>
