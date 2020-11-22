<table id="table-list-unit-anggota-institusi">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Anggota Unit</th>
            <th>Pencapaian</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php $indexAnggota=0; foreach($data_kinerja_anggota as $data_anggota ): $indexAnggota++;?>
            <tr>
                <td><?=$indexAnggota;?></td>
                <td>
                    <?=$data_anggota->nama_user;?>
                </td>
                <td><?=$data_anggota->persen_tercapai;?></td>
                <td>
                    <form id="lihat-user" action="<?=$data_aksi["action_lihat_bidang_user"];?>" method="post">
                        <!-- Form Variable-->
                        <?php $this->load->view("bidang/page_institusi/form_variabel.php",[
                            "overide" => [
                                "mode_individu" => "0"
                            ]
                        ]);?>

                        <!-- user data -->
                        <input type="hidden" name="user_id" value="<?=$data_anggota->user_id;?>"/>
                        
                        <input type="submit" class="btn-info" value="Lihat">
                    </form>
                </td>
            </tr>
            <?php endforeach;?>
        </tr>
    </tbody>
</table>