<div class="card mb-3">
    <div class="card-header">
        <i class="fas fa-table"></i>
        Daftar Rencana Kerja
    </div>
    <div class="card-body">
        <div class="table-responsive">

        <!-- Toolbar -->
            <form action="<?php echo base_url()."index.php/formulir_rencana_kerja/get";?>" method="post">
                <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="input-group mb-3  mr-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="institusi">Institusi :</label>
                        </div>
                        <select class="custom-select" name="institusi" id="institusi">
                            <?php 
                                foreach($data["institusi"] as $institusi){
                                    if($data["selected_institusi_id"] == $institusi->id ){
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

                    <div class="input-group mb-3 mr-3">
                        <div class="input-group-prepend">
                            <label class="input-group-text" for="tahun">Tahun :</label>
                        </div>
                        <select class="custom-select" name="tahun" id="tahun">
                            <?php
                                foreach($data["tahun"] as $tahun){
                                    if((int) $data["selected_tahun"] == $tahun->tahun){
                                        echo "<option value='$tahun->tahun' selected>$tahun->tahun</option>";
                                    }else{
                                        echo "<option value='$tahun->tahun'>$tahun->tahun</option>";
                                    }
                                }
                            ?>
                        </select>
                    </div>

                    <div class="input-group mb-3 align-items-center mr-3">
                        <span class="mr-1"><b>Semester : </b></span> 
                        <div class="form-check mr-1">
                            <input 
                                name="ganjil" 
                                class="form-check-input" 
                                type="checkbox" 
                                value="1" 
                                id="ganjil" 
                                <?php echo $data["selected_semester"] == '1' || $data["selected_semester"] == NULL ? "checked"  : ""?>>
                            <label class="form-check-label" for="ganjil">
                                Ganjil
                            </label>
                        </div>
                        <div class="form-check">
                            <input 
                                name="genap" 
                                class="form-check-input" 
                                type="checkbox" 
                                value="1" 
                                id="genap"
                                <?php echo $data["selected_semester"] == '2' || $data["selected_semester"] == NULL ? "checked"  : ""?>>
                            <label class="form-check-label" for="genap">
                                Genap
                            </label>
                        </div>
                    </div>
                   
                    <div class="input-group mb-3  mr-3">
                        <input type="submit" class="btn btn-primary mr-1" value="Cari">
                        <a href="<?php echo base_url()."index.php/formulir_rencana_kerja/form"?>" class="btn btn-warning">Tambah Rencana Kerja</a>
                    </div> 
                </div>
            </form>

            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Unit</th>
                    <th>User</th>
                    <th>Periode</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($data["formulir_rencana_kerja"] as $datum){
                    $periode_str = "";
                    if( $datum->semester == "2"){
                        $periode_str = "Maret ".(string)((int) $datum->tahun-1)." - April ".(string)((int)$datum->tahun);
                    }else{
                        $periode_str = "September ".$datum->tahun." - April ".(string)((int)$datum->tahun+1);
                    }
                    echo (
                        "<tr>
                            <td>$datum->nama_unit</td>
                            <td>$datum->nama_user</td>
                            <td>$periode_str</td>
                            <td>$datum->score</td>
                        </tr>");
                }
                ?>
            </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
</div>