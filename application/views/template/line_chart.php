<div class="card">
    <div class="card-header card-header-dashboard">
    <h4><i class="fas fa-history"></i> History Kinerja </h4>
    </div>
    <div class="card-body">
        <form id="ubah-periode" action="<?=$action;?>" method="post">
            <!-- custom variabel-->
            <?=$custom_variabel;?>
            <div class="chart-container" style="position: relative;">
                <canvas id="canvas-statistik-kinerja"></canvas>
            </div>
        </form>
    </div>
</div>