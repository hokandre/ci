<div class="container">
    <table class="table table-hover table-sm table-bordered">
      <thead>
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Bidang</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
            $counter = 1;
            foreach($data_bidang as $row){
                $base_url = base_url();
                $url_delete = $base_url."/bidang/delete/?id=".$row->id;
                $url_update = $base_url."/bidang/update/?id=".$row->id;
                echo "
                        <tr id='$row->id'>
                            <td>$counter</td>
                            <td>$row->nama_bidang</td>
                            <td>
                                <a href='$url_delete' class='btn btn-danger btn-sm'>hapus</a>
                                <a href='$url_update' class='btn btn-warning btn-sm'>update</a>
                            </td>
                        </tr>
                    ";
            
            }
        ?>
      </tbody>
    </table>
</div>