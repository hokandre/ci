<!-- header -->
<?php $this->load->view("partials/header.php", [
  "title" => "[Individu] | Formulir Hasil Bidang Kinerja",
  "css" =>[ base_url()."assets/css/page_form_rencana_kerja.css"]
]); ?>


<main>
  <!-- sidebar -->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>
  <section class="page-content" id="list-rencana-kerja">

    <!--Page Title -->
    <?php $this->load->view("form_rencana_kerja/template/content_title_form_individu.php", 
    [
      "action_buat_format_formulir" => $action_buat_format_formulir,
      "action_get_format_formulir" => $action_get_format_formulir,
      "action_cari_laporan" => $action_cari_laporan
    ]); ?>

    <!-- breadcrumb -->
    <?php $this->load->view("form_rencana_kerja/template/breadcrumb_form_individu.php", [
      "breadcrumb" => $breadcrumb
    ]);?>

    
    <!-- card laporan-->
    <div class="card">
          <div class="card-header">
              <h4><i class="fas fa-table"></i>  Formulir Sendiri</h4>
          </div>
          <div class="card-body">

              <!-- TABEL HEADER : SEARCH BAR -->
              <div class="table-topbar">
                <div class="table-topbar-filter">
                  <h4>show :</h4>
                    <select name="numberRow" id="numberRow">
                      <option value="10">10</option>
                      <option value="50">50</option>
                      <option value="100">100</option>
                    </select>
                </div>

                <div class="table-topbar-filter">
                  <h4>search : </h4> <input id="tableSearch" type="text" value="" placeholder="search..."/>
                </div> 
              </div>
          
              <table id="listRencanaKerja">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Unit</th>
                      <th>User</th>
                      <th>Periode</th>
                      <th>Score</th>
                  </tr>
                </thead>

                <tbody>
                  <?php $i=0; foreach($data_formulir_rencana_kerja as $datum) : $i++; ?> 

                    <?php      
                      $periode_str = "";
                      if( $datum->semester == "2"){
                          $periode_str = "Maret ".(string)((int) $datum->tahun+1)." - Agustus ".(string)((int)$datum->tahun+1);
                      }else{
                          $periode_str = "September ".$datum->tahun." - Februari ".(string)((int)$datum->tahun+1);
                      }
                      $score_2digit_precision = ceil(number_format((float)$datum->score,2,'.','')); 
                    ?>

                    <tr>
                      <td><?=$i?></td>
                      <td> 
                        <a style="color:blue;text-decoration:underline;" href="<?php echo $action.$datum->id;?>" title="Lihat detil">
                          <?php $nama_unit = $datum->nama_unit;?>
                          <?php 
                            if($datum->formulir_ketua == 1){
                              $nama_unit = "Ketua ".$nama_unit;
                            }else{
                              if($datum->tenaga_pengajar == 1) {
                                $nama_unit = "Dosen ".$nama_unit;
                              }else{
                                $nama_unit = "Anggota ".$nama_unit;
                              }
                            }
                          ?>
                          
                          <?=$nama_unit;?>
                        </a>
                    </td>
                      <td><?=$datum->nama_user?></td>
                      <td><?=$periode_str?></td>
                      <td><?=$score_2digit_precision?></td>
                    </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
              
              <!-- TABLE FOOTER : PAGINATION -->
              <div class="table-footer">
                <p><span id="numberOfDataFound"></span> data ditemukan.</p>
                <ul class="pagination">
                  <li class="pagination-item" data-page="prev" id="prev"> < </li>
                  <li class="pagination-item" data-page="next" id="next"> > </li>
                </ul>
              </div>
          </div>
    </div>
  </section>
</main>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php", [
  "js" => [ base_url()."assets/js/page_form_rencana_kerja.js" ]
]) ?>
