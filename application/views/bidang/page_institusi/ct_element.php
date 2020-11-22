<i class="fas fa-tachometer-alt">Pencapain Bidang</i> 
<?php if($this->session->userdata("hak_akses") == 1) :?>
    <form id="ubah-bidang" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>" style="display: inline-block;" method="post">
        <!-- Form Variabel-->
        <?php $this->load->view("bidang/page_institusi/form_variabel.php");?>

        <select name="option-bidang" id="option-bidang" class="toolbar toolbar-white">
            <?php $indexBidang = 0; foreach($data_bidang as $bidang) : $indexBidang++;?>
                <option <?=$bidang_id == $bidang->id ? "selected" : "";?> value="<?=$bidang->id;?>">
                    <?=$bidang->nama_bidang;?>
                </option>
            <?php endforeach;?>
        </select>
    </form>
    
    <form id="ubah-institusi" action="<?=$data_aksi["action_lihat_bidang_institusi"];?>" style="display: inline-block;" method="post">

        <!-- Form Variabel-->
        <?php $this->load->view("bidang/page_institusi/form_variabel.php");?>
        
        <select name="option-institusi" id="option-institusi" class="toolbar toolbar-white">
            <?php $indexInstitusi = 0; foreach($data_institusi as $institusi) : $indexInstitusi++;?>
                <option <?=$institusi_id == $institusi->id ? "selected" : "";?> value="<?=$institusi->id;?>">
                    <?=$institusi->nama_institusi;?>
                </option>
            <?php endforeach;?>
        </select>
    </form>

    <script>
        //form : ubah - bidang 
        $(document).on('change', 'select[name="option-bidang"]', function(){
            let bidangId = $(this).find(":selected").val();
            $('#ubah-bidang input[name="bidang_id"]').val(bidangId);
            $('#ubah-bidang').submit();
        })

        //form : ubah-institusi
        $(document).on('change', 'select[name="option-institusi"]', function(){
            let institusiId = $(this).find(":selected").val();
            $('#ubah-institusi input[name="institusi_id"]').val(institusiId);
            $('#ubah-institusi').submit();
        })
    </script>
<?php endif;?> 