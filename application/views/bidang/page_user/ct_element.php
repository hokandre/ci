<i class="fas fa-tachometer-alt"></i> Pencapain User
<form id="ubah-bidang" action="<?=$action_lihat_bidang_user;?>" style="display: inline-block;" method="post">
   <!--Form Variable-->
   <?php $this->load->view("bidang/page_user/form_variabel.php");?>
    <select name="option-bidang" id="option-bidang" class="toolbar toolbar-white">
        <?php $indexBidang = 0; foreach($data_bidang as $bidang) : $indexBidang++;?>
            <option <?=$bidang_id == $bidang->id ? "selected" : "";?> value="<?=$bidang->id;?>">
                <?=$bidang->nama_bidang;?>
            </option>
        <?php endforeach;?>
    </select>
</form>

    <!-- lihat sebagai individu -->
<form id="ubah-unit" action="<?=$action_lihat_bidang_unit;?>" method="post" style="display:inline;">
   <!--Form Variable-->
   <?php $this->load->view("bidang/page_user/form_variabel.php");?>
    <select name="option-unit" class="toolbar toolbar-white">
        <?php foreach($data_unit as $unit ) : ?>
            <option 
                <?=($unit_id == $unit->id && $unit->formulir_ketua == $formulir_ketua ) ? "selected" : "";?> 
                formulir-ketua="<?=$unit->formulir_ketua;?>" value="<?=$unit->id;?>"
            >
                <?=$unit->nama_unit;?>
            </option>
        <?php endforeach;?>
    </select>
</form>

<?php if( ($ketua_unit != '1')
    && ($this->session->userdata("ketua_unit") == $selected_unit) 
    && ($this->session->userdata("unit_id") == $selected_unit)
    && ($this->session->userdata("id") == $selected_user)) : ?>
    <!-- lihat sebagai unit atau user-->
    <form id="form-versi" action="" method="post" style="display:inline;">
        <input type="hidden" name="mode_individu" value="<?=$mode_individu;?>"/>
        <input type="hidden" value="<?=$ketua_unit;?>" name="ketua_unit"/>
        <input type="hidden" value="<?=$selected_unit;?>" name="unit_id"/>
        <input type="hidden" value="<?=$selected_periode_tahun_semetser;?>" name="periode_id"/>
        <input type="hidden" value="<?=$selected_renstra_periode;?>" name="renstra_periode"/>
        <input type="radio" value="unit" name="versi" <?=$versi=="unit" ? "checked" :"";?>/> <span style="padding:10px;"> Unit </span> 
        <input type="radio" value="individu" name="versi" <?=$versi=="individu" ? "checked" :"";?>/> <span style="padding:10px;"> User </span> 
    </form>
<?php endif;?>
<script>
    //form : ubah - unit
    $(document).on('change', 'select[name="option-unit"]', function(){
        let formulirKetua = $(this).find(":selected").attr("formulir-ketua");
        $('#ubah-unit').find('input[name="formulir_ketua"]').val(formulirKetua);
        let unitId = $(this).find(":selected").val();
        $('#ubah-unit').find('input[name="unit_id"]').val(unitId);
        $('#ubah-unit').submit();
    })

    //form : ubah - bidang 
    $(document).on('change', 'select[name="option-bidang"]', function(){
            let bidangId = $(this).find(":selected").val();
            $('#ubah-bidang input[name="bidang_id"]').val(bidangId);
            $('#ubah-bidang').submit();
    })
</script>

<!-- switch button untuk ketua unit -->
<?php 
$is_user_ketua_unit = false;
$unit_id_user = $this->session->userdata("unit_user")->id;
$data_unit_diketuai_user = $this->session->userdata("unit_diketuai");
foreach($data_unit_diketuai_user as $unit) {
    if($unit->id == $unit_id) {
        $is_user_ketua_unit = true;
        break;
    }
}

if( ($formulir_ketua == '0') && $is_user_ketua_unit && ($unit_id_user == $unit_id) )  : ?>
    <!-- lihat sebagai user-->
    <form id="form-versi" action="<?=$data_aksi["action_lihat_bidang_unit"];?>" method="post" style="display:inline;">
       <!--Form Variable-->
        <?php $this->load->view("bidang/page_user/form_variabel.php");?>
        <input type="radio" value="unit" name="versi"/> 
        <span style="padding:10px;"> Unit </span> 
        <input type="radio" value="individu" name="versi" checked/> 
        <span style="padding:10px;"> User </span> 
    </form>
    <script>
        $(document).on('click', 'input[name="versi"]', function(){
            $("#form-versi").submit();
        })
    </script>
<?php endif;?>