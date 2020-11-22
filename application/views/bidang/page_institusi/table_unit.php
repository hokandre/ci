<table id="table-list-unit-anggota-institusi">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Unit</th>
            <th>Pencapaian</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php $indexAnggota=0; foreach($data_kinerja_anggota as $data_anggota ): $indexAnggota++;?>
            <tr>
                <td><?=$indexAnggota;?></td>
                <td><?=$data_anggota->nama_unit;?></td>
                <td><?=$data_anggota->persen_tercapai;?></td>
                <td>
                    <form id="lihat-unit" action="<?=$data_aksi["action_lihat_bidang_unit"];?>" method="post">
                        <!-- Form Variable-->
                        <?php $this->load->view("bidang/page_institusi/form_variabel.php",[
                            "overide" => [
                                "mode_individu" => "0"
                            ]
                        ]);?>

                        <!-- for bread crumb -->
                        <input type="hidden" name="crumb_institusi" value="1"/>
                        <input type="hidden" name="periode_id_institusi" value="<?=$periode_id;?>"/>
                        <input type="hidden" name="renstra_id_institusi" value="<?=$renstra_id?>"/>
                        
                        <!-- unit data-->
                        <input type="hidden" name="unit_id" value="<?=$data_anggota->unit_id;?>"/>
                        <input type="hidden" name="formulir_ketua" value="<?=$data_anggota->formulir_ketua;?>"/>

                        <input type="submit" class="btn-info" value="Lihat"/>
                    </form>
                </td>
            </tr>
            <?php endforeach;?>
        </tr>
    </tbody>
</table>