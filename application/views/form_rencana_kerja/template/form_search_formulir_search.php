<?php 
    $hak_akses_bpm = 1;
    $session_hak_akses = $this->session->userdata("hak_akses");
?>

<form 
    class="form-filter" 
    method="post" 
    action="<?php echo base_url()."index.php/formulir_rencana_kerja/get";?>">

    <div class="form-filter-option">
        <h4 for="tahun">Tahun :</h4>
        <select class="toolbar" name="tahun" id="tahun">
            <?php
                foreach($data_tahun as $tahun){
                    if((int) $selected_tahun == $tahun->tahun){
                        echo "<option value='$tahun->tahun' selected>$tahun->tahun</option>";
                    }else{
                        echo "<option value='$tahun->tahun'>$tahun->tahun</option>";
                    }
                }
            ?>
        </select>
    </div>
    <div class="form-filter-option">
        <h4>Semester : </h4>
        <div class="toolbar">
            <input  
                type="checkbox" 
                name="ganjil" 
                id="ganjil" 
                value="1" 
                <?php echo $selected_semester == '1' || $selected_semester == NULL ? "checked"  : "";?>/> Ganjil <br>
        </div>
        <div class="toolbar">
            <input 
                type="checkbox" 
                name="genap" 
                id="genap" 
                value="2" 
                <?php echo $selected_semester == '2' || $selected_semester == NULL ? "checked"  : "";?>/> Genap
        </div>
    </div>
    <?php if( $session_hak_akses == $hak_akses_bpm) :?>
        <div class="form-filter-option">
            <h4 for="institusi">Institusi : </h4>
            <select class="toolbar" name="institusi" id="institusi">
                <?php 
                    foreach($data_institusi as $institusi){
                        if($selected_institusi_id == $institusi->id ){
                            echo "
                                <option value='$institusi->id' selected>$institusi->nama_institusi</option>
                            ";
                        }else{
                            echo "
                                <option value='$institusi->id'>$institusi->nama_institusi</option>
                            ";
                        }
                    }
                ?>
            </select>
        </div>
    <?php endif;?>

    <div class="content-filter-option">
        <button class="toolbar">
            <i class="fas fa-search" title="cari"></i>
        </button>
    </div>
</form>