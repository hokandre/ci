
<?php  
$table_header = '
    <div class="table-header">
        <div class="table-cell">
            Sumber
        </div>
        <div class="table-cell">
            Bidang
        </div>
        <div class="table-cell">
            Bobot
        </div>
        <div class="table-cell">
            Sasaran Strategis/Indikator
        </div>
        <div class="table-cell">
            KPI
        </div>
        <div class="table-cell">
            Target
        </div>
    </div>';
?>

<div class="card" id="institusi-<?=$institusi_id;?>" style="margin-top:20px;">
    <div class="card-header">
        <h4><i class="fas fa-table"></i><?=$nama_institusi;?></h4>
    </div>
    <div class="card-body">
        <?php foreach($unit_insitusi as $unit) : ?>
            <?php if( (int)$unit->jumlah_anggota == 0 ) : ?>
                <button data-unit-id="<?=$unit->id;?>" data-ketua-unit="<?=$unit->ketua_unit;?>" class="accordion"><?= 'Ketua '.$unit->nama_unit;?></button>
                <div class="panel">
                    <div class="table">
                        <?=$table_header;?>
                        <?php if(isset($unit->kpi_ketua)) : ;?>
                        <?php $index_kpi=0; foreach($unit->kpi_ketua as $kpi_poin):$index_kpi++;?>
                            <?php 
                                $nama_sumber = "";
                                if($kpi_poin["sumber"] == 'mutu'){
                                    $nama_sumber = "Sasaran Mutu";
                                } else if($kpi_poin["sumber"] == "renop"){
                                    $nama_sumber ="Renop";
                                }else{
                                    $nama_sumber = "Renstra";
                                }
                            ?>
                            <div class="table-row" data-kpi-id="<?=$kpi_poin['kpi_id']?>" data-nama-sumber="<?=$nama_sumber;?>">
                                <div class="table-cell" name="col-sumber">
                                    <p data-sumber-id="<?=$kpi_poin['sumber']?>"><?=$nama_sumber;?></p>
                                </div>
                                <div class="table-cell" name="col-bidang">
                                    <p data-bidang-id="<?=$kpi_poin['bidang_id'];?>" data-nama-bidang="<?=$kpi_poin['nama_bidang'];?>"><?=$kpi_poin['nama_bidang'];?></p>
                                </div>
                                <div class="table-cell" name="col-bobot">
                                    <p data-bobot="<?=$kpi_poin['bobot'];?>"><?=$kpi_poin['bobot'];?></p>
                                </div>
                                <div class="table-cell" name="col-indikator">
                                    <p data-indikator-id="<?=$kpi_poin['indikator_id'];?>" data-nama-indikator="<?=$kpi_poin['nama_indikator'];?>"> <?=$kpi_poin['nama_indikator'];?> </p>
                                </div>
                                <div class="table-cell" name="col-kpi">
                                    <p data-kpi-id="<?=$kpi_poin['kpi_id'];?>" data-nama-kpi="<?=$kpi_poin['nama_kpi'];?>"><?=$kpi_poin['nama_kpi'];?></p>
                                </div>
                                <div class="table-cell" name="col-target">
                                    <?php
                                        $simbolSatuan = "%";
                                        switch($kpi_poin["satuan"]) {
                                            case 'orang' :
                                                $simbolSatuan = 'org';
                                                break;
                                            case 'satuan' :
                                                $simbolSatuan = 'Angka';
                                                break;
                                            default :
                                                $simbolSatuan = $simbolSatuan;
                                                break;
                                        }
                                    ?>
                                    <p data-target="<?=$kpi_poin['target'];?>"> <?=$kpi_poin['target']." ".$simbolSatuan;?> </p>
                                </div>
                            </div>
                        <?php endforeach;?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <button data-unit-id="<?=$unit->id;?>" data-ketua-unit="<?=$unit->ketua_unit;?>" class="accordion"><?='Ketua '.$unit->nama_unit;?></button>
                <div class="panel">
                    <div class="table">
                        <?=$table_header;?>
                        <?php if(isset($unit->kpi_ketua)) : ;?>
                        <?php $index_kpi=0; foreach($unit->kpi_ketua as $kpi_poin):$index_kpi++;?>
                            <?php 
                                $nama_sumber = "";
                                if($kpi_poin["sumber"] == 'mutu'){
                                    $nama_sumber = "Sasaran Mutu";
                                } else if($kpi_poin["sumber"] == "renop"){
                                    $nama_sumber ="Renop";
                                }else{
                                    $nama_sumber = "Renstra";
                                }
                            ?>
                            <div class="table-row" data-kpi-id="<?=$kpi_poin['kpi_id']?>" data-nama-sumber="<?=$nama_sumber;?>">
                                <div class="table-cell" name="col-sumber">
                                    <p data-sumber-id="<?=$kpi_poin['sumber']?>"><?=$nama_sumber;?></p>
                                </div>
                                <div class="table-cell" name="col-bidang">
                                    <p data-bidang-id="<?=$kpi_poin['bidang_id'];?>" data-nama-bidang="<?=$kpi_poin['nama_bidang'];?>"><?=$kpi_poin['nama_bidang'];?></p>
                                </div>
                                <div class="table-cell" name="col-bobot">
                                    <p data-bobot="<?=$kpi_poin['bobot'];?>"><?=$kpi_poin['bobot'];?></p>
                                </div>
                                <div class="table-cell" name="col-indikator">
                                    <p data-indikator-id="<?=$kpi_poin['indikator_id'];?>" data-nama-indikator="<?=$kpi_poin['nama_indikator'];?>"> <?=$kpi_poin['nama_indikator'];?> </p>
                                </div>
                                <div class="table-cell" name="col-kpi">
                                    <p data-kpi-id="<?=$kpi_poin['kpi_id'];?>" data-nama-kpi="<?=$kpi_poin['nama_kpi'];?>"><?=$kpi_poin['nama_kpi'];?></p>
                                </div>
                                <div class="table-cell" name="col-target">
                                    <?php
                                        $simbolSatuan = "%";
                                        switch($kpi_poin["satuan"]) {
                                            case 'orang' :
                                                $simbolSatuan = 'org';
                                                break;
                                            case 'satuan' :
                                                $simbolSatuan = 'Angka';
                                                break;
                                            default :
                                                $simbolSatuan = $simbolSatuan;
                                                break;
                                        }
                                    ?>
                                    <p data-target="<?=$kpi_poin['target'];?>"> <?=$kpi_poin['target']." ".$simbolSatuan;?> </p>
                                </div>
                            </div>
                        <?php endforeach;?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $namaUnit = $unit->tenaga_pengajar == '0' ? "Anggota " : "Dosen ";?>
                <button data-unit-id="<?=$unit->id;?>" data-ketua-unit="" class="accordion"><?=$namaUnit.$unit->nama_unit;?></button>
                <div class="panel">
                    <div class="table">
                        <?=$table_header;?>
                        <?php if(isset($unit->kpi_anggota)) : ;?>
                        <?php $index_kpi=0; foreach($unit->kpi_anggota as $kpi_poin):$index_kpi++;?>
                            <?php 
                                $nama_sumber = "";
                                if($kpi_poin["sumber"] == 'mutu'){
                                    $nama_sumber = "Sasaran Mutu";
                                } else if($kpi_poin["sumber"] == "renop"){
                                    $nama_sumber ="Renop";
                                }else{
                                    $nama_sumber = "Renstra";
                                }
                            ?>
                            <div class="table-row" data-kpi-id="<?=$kpi_poin['kpi_id']?>" data-nama-sumber="<?=$nama_sumber;?>">
                                <div class="table-cell" name="col-sumber">
                                    <p data-sumber-id="<?=$kpi_poin['sumber']?>"><?=$nama_sumber;?></p>
                                </div>
                                <div class="table-cell" name="col-bidang">
                                    <p data-bidang-id="<?=$kpi_poin['bidang_id'];?>" data-nama-bidang="<?=$kpi_poin['nama_bidang'];?>"><?=$kpi_poin['nama_bidang'];?></p>
                                </div>
                                <div class="table-cell" name="col-bobot">
                                    <p data-bobot="<?=$kpi_poin['bobot'];?>"><?=$kpi_poin['bobot'];?></p>
                                </div>
                                <div class="table-cell" name="col-indikator">
                                    <p data-indikator-id="<?=$kpi_poin['indikator_id'];?>" data-nama-indikator="<?=$kpi_poin['nama_indikator'];?>"> <?=$kpi_poin['nama_indikator'];?> </p>
                                </div>
                                <div class="table-cell" name="col-kpi">
                                    <p data-kpi-id="<?=$kpi_poin['kpi_id'];?>" data-nama-kpi="<?=$kpi_poin['nama_kpi'];?>"><?=$kpi_poin['nama_kpi'];?></p>
                                </div>
                                <div class="table-cell" name="col-target">
                                    <?php
                                        $simbolSatuan = "%";
                                        switch($kpi_poin["satuan"]) {
                                            case 'orang' :
                                                $simbolSatuan = 'org';
                                                break;
                                            case 'satuan' :
                                                $simbolSatuan = 'Angka';
                                                break;
                                            default :
                                                $simbolSatuan = $simbolSatuan;
                                                break;
                                        }
                                    ?>
                                    <p data-target="<?=$kpi_poin['target'];?>"> <?=$kpi_poin['target']." ".$simbolSatuan;?> </p>
                                </div>
                            </div>
                        <?php endforeach;?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;?>
    </div>
</div>