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
                    </div>
                </div>
            <?php else : ?>
                <button data-unit-id="<?=$unit->id;?>" data-ketua-unit="<?=$unit->ketua_unit;?>" class="accordion"><?='Ketua '.$unit->nama_unit;?></button>
                <div class="panel">
                    <div class="table">
                        <?=$table_header;?>
                    </div>
                </div>
                <?php $namaUnit = $unit->tenaga_pengajar == '0' ? "Anggota " : "Dosen ";?>
                <button data-unit-id="<?=$unit->id;?>" data-ketua-unit="" class="accordion"><?=$namaUnit.$unit->nama_unit;?></button>
                <div class="panel">
                    <div class="table">
                        <?=$table_header;?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach;?>
    </div>
</div>