<!-- HEADER -->
<?php $this->load->view('partials/header.php', [
  "title" => "List Indikator Pada Unit",
  "css" => [
    base_url()."assets/css/page_indikator.css"
  ]
]);?>

<main>
    <!-- SIDEBAR -->
    <?php $this->load->view('template/sidebar/sidebar_bpm.php');?>

    <section class="page-content" id="page-indikator">
        <!-- CONTENT TITLE -->
        <?php $this->load->view("indikator/template/content_title_list_indikator_pada_unit.php",[
          "selected_unit" => $selected_unit
        ]);?>

        <!-- BREADCRUMB -->
        <?php $this->load->view("indikator/template/breadcrumb_list_indikator_pada_unit.php",[
          "action_back" => $action_back
        ]);?>

        <div class="card">
          <div class="card-header card-header-dashboard">
            <h4><i class="fas fa-table"></i> Daftar Indikator</h4>
          </div>

          <div class="card-body">
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

            <div class="form-filter">
                <div class="form-filter-option">
                    <b> Unit </b> : <?=$selected_unit->nama_unit;?>
                </div>
            </div>
            
            <table class="" id="list-indikator">
              <colgroup>
                  <col class="w-5">
                  <col class="w-80">
                  <col class="w-15">
              </colgroup>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nama Nama Indikator</th>
                </tr>
              </thead>
              <tbody>
                <?php $i=0; foreach ($data_indikator as $indikator) : $i++;?>
                <tr>
                    <td style="padding: 15px;"><?=$i;?></td>
                    <td style="padding: 15px;"><?=$indikator->nama_indikator;?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          
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

<!-- FOOTER  -->
<?php $this->load->view("partials/footer.php", [
  "js" => [
    base_url()."assets/js/page_indikator.js"
  ]
]) ;?>

<script>
  let actionLihat = JSON.parse('<?php echo json_encode($action_lihat);?>');
  $("select[name='versi']").on('change', function(){
    let versi = $(this).find(":selected").val();
    if(versi == "unit"){
      $("#form-versi").attr("action", actionLihat+`?versi=unit`)
    }else{
      $("#form-versi").attr("action", actionLihat)
    }
    $("#form-versi").submit();
  })
</script>


  
