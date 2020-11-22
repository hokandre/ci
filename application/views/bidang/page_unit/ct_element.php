<i class="fas fa-tachometer-alt"></i> Pencapain 
<form id="ubah-bidang" action="<?=$data_aksi["action_lihat_bidang_unit"];?>" style="display: inline-block;" method="post">
    <!--Form Variable-->
    <?php $this->load->view("bidang/page_unit/form_variabel.php");?>
    <select name="option-bidang" id="option-bidang" class="toolbar toolbar-white">
        <?php foreach($data_bidang as $bidang) : ?>
            <option 
                <?=$bidang_id == $bidang->id ? "selected" : "";?> 
                value="<?=$bidang->id;?>"
            >
                <?=$bidang->nama_bidang;?>
            </option>
        <?php endforeach;?>
    </select>
</form>

<form id="ubah-unit" action="<?=$data_aksi["action_lihat_bidang_unit"];?>" style="display: inline-block;" method="post">
    <!--Form Variable-->
    <?php $this->load->view("bidang/page_unit/form_variabel.php");?>
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
    <form id="form-versi" action="<?=$data_aksi["action_lihat_bidang_user"];?>" method="post" style="display:inline;">
       <!--Form Variable-->
        <?php $this->load->view("bidang/page_unit/form_variabel.php");?>
        <input type="radio" value="unit" name="versi" checked/> 
        <span style="padding:10px;"> Unit </span> 
        <input type="radio" value="individu" name="versi"/> 
        <span style="padding:10px;"> User </span> 
    </form>
    <script>
        $(document).on('click', 'input[name="versi"]', function(){
            $("#form-versi").submit();
        })
    </script>
<?php endif;?>