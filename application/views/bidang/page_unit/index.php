<!DOCTYPE html>
<html lang="en">
    <?php $this->load->view('template/header.php',[
        "page_title" => "Dashboard Bidang Unit"
    ]);?>
<body>
    <?php $this->load->view('template/top_bar.php');?>

    <main>
        <?php $this->load->view('template/sidebar.php');?>
        <section class="page-content" id="page-dashboard-bidang">
            <?php 
                $this->load->view('template/content_title.php',[
                    "element" => $this->load->view('bidang/page_unit/ct_element.php','',TRUE),
                    "toolbar" => $this->load->view('bidang/page_unit/ct_toolbar.php','', TRUE)
                ]);
            ?>
            <?php $this->load->view('template/breadcrumb.php',[
                "element" => "<input type='hidden' name='bidang_id' value='$bidang_id'>"
            ]);?>
            <div class="flex-row">
                <!-- pie chart -->
                <div class="flex-col-6">
                    <?php 
                        $this->load->view('template/pie_chart.php',[
                            "title" => "Bidang Indikator",
                            "unit_name" => $obj_institusi->nama_institusi
                        ]);
                    ?>
                </div>
                <!-- line chart -->
                <div class="flex-col-6">
                    <?php 
                        $this->load->view('template/line_chart.php',[
                            "action" => $data_aksi["action_lihat_bidang_unit"],
                            "custom_variabel" => $this->load->view("bidang/page_unit/form_variabel.php", '', TRUE)
                        ]);
                    ?>
                </div>
            </div>
            <!-- table unit anggota -->
            <?php
                $this->load->view('template/table_filter.php',[
                    "table" => $this->load->view("bidang/page_unit/table_user.php",'', TRUE)
                ]);
            ?>
            <!--bar chart -->
            <?php 
                $this->load->view('template/bar_chart.php',[
                    "title" => "Daftar User Anggota"
                ]);
            ?>
        </section>
    </main>
</body>
<!-- FOOTER -->
<?php $this->load->view("template/footer.php",[
    "js" => [
        "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js",
        base_url()."assets/js/draw_chart.js"
    ]
]);?>
</html>
<script>
let keteranganPeriode = JSON.parse('<?php echo json_encode($keterangan_periode);?>');
let renstraPeriode = JSON.parse('<?php echo  is_null($obj_renstra) ? "" : json_encode($obj_renstra); ?>');
let urlUnit = JSON.parse('<?php echo json_encode($data_aksi["action_lihat_bidang_unit"]);?>');
let urlInstitusi = JSON.parse('<?php echo json_encode($data_aksi["action_lihat_bidang_institusi"]);?>');
let urlUser = JSON.parse('<?php echo json_encode($data_aksi["action_lihat_bidang_user"]);?>');

//data untuk pie chart
let dataKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_kinerja);?>');
let canvasKinerjaSaatIni = $('#canvas-kinerja-saat-ini');
let chartKinerjaSaatIni = drawPieChartKinerjaSaatIni(canvasKinerjaSaatIni, dataKinerjaSaatIni);

//data untuk bar chart
let dataDetilKinerjaSaatIni = JSON.parse('<?php echo json_encode($data_detil_kinerja)?>');
let canvasDetilKinerjaSaatIni = $('#canvas-detil-kinerja-saat-ini');
let chartDetilKinerja = drawBarChartDetilKinerjaUnit(canvasDetilKinerjaSaatIni, dataDetilKinerjaSaatIni, { keteranganPeriode : keteranganPeriode });

//data untuk line chart
let dataStatistikKinerja = JSON.parse('<?php echo json_encode($data_statistik_kinerja); ?>');
let canvasStatistikKinerja = $('#canvas-statistik-kinerja');
let chartStatistik = drawLineChartStatistikKinerja(canvasStatistikKinerja, dataStatistikKinerja);
</script>