<div class="card">
    <div class="card-header">
    <h4><i class="fas fa-tachometer-alt"></i> 
        Pencapaian <?=$title;?> Periode (<?=$keterangan_periode;?>)
    </h4>
    </div>
    <div class="card-body">
        <div class="chart-container" style="position: relative;">
            <h4 style="text-align:center; margin-bottom:10px;"><?=$unit_name;?></h4>
            <canvas id="canvas-kinerja-saat-ini"></canvas>
        </div>
    </div>
</div>