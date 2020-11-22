<!-- HEADER DOCUMENT -->
<?php $this->load->view("partials/header.php",[
  "title" => "Laporan Formulir Hasil Bidang Kinerja Utama"
]); ?>


<main>

  <!-- SIDEBAR-->
  <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

  <section class="page-content" id="list-rencana-kerja">
     
      <!-- CONTENT TITLE -->
      <?php $this->load->view("form_rencana_kerja/template/content_title_form_search.php") ;?>

      <!-- BREADCRUMB -->
      <?php $this->load->view("form_rencana_kerja/template/breadcrumb_form_search.php"); ?>

      <div class="card">
          <div class="card-header">
              <h4><i class="fas fa-table"></i>  Formulir Unit</h4>
          </div>
          <div class="card-body">

            <!-- FORM SEARCH -->
            <?php 
              $this->load->view("form_rencana_kerja/template/form_search_formulir_search.php",[
                "data_tahun" => $data_tahun,
                "selected_tahun" => $selected_tahun,
                "selected_semester" => $selected_semester,
                "data_institusi" => $data_institusi,
                "selected_institusi_id" => $selected_institusi_id
              ]);
            ?>

              <!--table topbar -->
              <div class="table-topbar">
                <div class="table-topbar-filter">
                  <h4>show : </h4>
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
              
              <!-- table -content -->
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
                  <?php 
                    $i=0; foreach($data_formulir_rencana_kerja as $datum): $i++; 
                  ?>
                    <?php 
                        $periode_str = "";
                        if( $datum->semester == "2"){
                            $periode_str = "Maret ".(string)((int) $datum->tahun+1)." - Agustus ".(string)((int)$datum->tahun+1);
                        }else{
                            $periode_str = "September ".($datum->tahun)." - Februari ".(string)((int)$datum->tahun+1);
                        }
                        $score_2digit_precision = $datum->score; 
                    ?>
                    
                    <tr>
                      <td><?=$i;?></td>
                      <?php 
                        $namaUnit = $datum->tenaga_pengajar == "0" ?      
                        (
                          $datum->formulir_ketua == "1" ? "Ketua ".$datum->nama_unit : "Anggota ".$datum->nama_unit
                        ) : 
                        (
                          $datum->formulir_ketua == "1" ? "Ketua ".$datum->nama_unit : "Dosen ".$datum->nama_unit
                        );
                      ?>
                      <td>
                        <!-- FORM ROW -->
                        <form 
                          class="form-list-laporan" 
                          action="<?=$action.$datum->id;?>" 
                          method="post">
                            <!-- for breadcrum list laporan-->
                            <input type="hidden" value="1" name="show_bread_crumb_list"/>
                            <input type="hidden" value="<?=$breadcrumb['institusi_crumb'];?>" name="list_institusi_crumb"/>
                            <input type="hidden" value="<?=$breadcrumb['tahun_crumb'];?>" name="list_tahun_crumb"/>
                            <input type="hidden" value="<?=$breadcrumb['ganjil_crumb']?>" name="list_ganjil_crumb"/>
                            <input type="hidden" value="<?=$breadcrumb['genap_crumb']?>" name="list_genap_crumb"/>

                            <!-- for breadcrumb detil-->
                            <input type="hidden" value="1" name="show_bread_crumb_detil"/>
                            <input type="hidden" value="<?=$datum->id?>" name="detil_unit_crumb"/>

                            <input type="submit" value="<?=$namaUnit;?>" style="border:0;background:transparent;display:block;"/>
                        </form>
                      </td>
                      <td><?=$datum->nama_user;?></td>
                      <td><?=$periode_str;?></td>
                      <td><?=$score_2digit_precision;?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
              
              <!-- table-footer -->
              <div class="table-footer">
                <p><span id="numberOfDataFound"></span> data ditemukan.</p>
                <ul class="pagination">
                  <li class="pagination-item" data-page="prev" id="prev"> < </li>
                  <li class="pagination-item" data-page="next" id="next"> > </li>
                </ul>
              </div>
          </div>

      </div>
      <!-- end of card -->
      
  </section>

</main>

<!-- FOOTER -->
<?php $this->load->view("partials/footer.php",[
  "js" => [ base_url()."assets/js/page_form_rencana_kerja.js" ]
]); ?>