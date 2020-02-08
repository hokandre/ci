<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Migration_initial_schema extends CI_Migration {
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
         //hak akses schema
         $this->dbforge->add_field(array(
            'id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
            ),
            'deskripsi' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('hak_akses', TRUE);

        //unit schema
        $this->dbforge->add_field(array(
                'id' => array(
                        'type' => 'INT',
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                ),
                'nama_unit' => array(
                        'type' => 'VARCHAR',
                        'constraint' => '50'
                ),
                'tenaga_pengajar' => array(
                    'type' => "enum('0','1')",
                    'default' => '1'
                )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('unit', TRUE);

        //user schema
        $this->dbforge->add_field(array(
            'id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
            ),
            'nama_user' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50'
            ),
            'password_user' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255'
            ),
            'unit_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'hak_akses' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_field('CONSTRAINT user_unit FOREIGN KEY (unit_id) REFERENCES unit(id)');
        $this->dbforge->add_field('CONSTRAINT user_hak_akses FOREIGN KEY (hak_akses) REFERENCES hak_akses(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('user', TRUE);

         //ketua unit schema
         $this->dbforge->add_field(array(
            'unit_id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE
            ),
            'ketua_unit' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_key('unit_id', TRUE);
        $this->dbforge->add_key('ketua_unit', TRUE);
        $this->dbforge->add_field('CONSTRAINT ketua_unit_unit FOREIGN KEY (unit_id) REFERENCES unit(id)');
        $this->dbforge->add_field('CONSTRAINT ketua_unit_user FOREIGN KEY (ketua_unit) REFERENCES user(id)');
        $this->dbforge->create_table('ketua_unit', TRUE);

       

        //indikator schema
        $this->dbforge->add_field(array(
            'id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
            ),
            'nama_indikator' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '255'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('indikator', TRUE);

        //kamus indikator schema
        $this->dbforge->add_field(array(
            'unit_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'indikator_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_field('CONSTRAINT kamus_indikator_unit FOREIGN KEY (unit_id) REFERENCES unit(id)');
        $this->dbforge->add_field('CONSTRAINT kamus_indikator_indikator FOREIGN KEY (indikator_id) REFERENCES indikator(id)');
        $this->dbforge->add_key('unit_id', TRUE);
        $this->dbforge->add_key('indikator_id', TRUE);
        $this->dbforge->create_table('kamus_indikator', TRUE);

        //kpi schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT'
            ),
            'nama_kpi' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50'
            ),
            'indikator_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            )
        ));
        $this->dbforge->add_field('CONSTRAINT kpi_indikator FOREIGN KEY (indikator_id) REFERENCES indikator(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('kpi', TRUE);

        //periode schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'tahun' => array(
                    'type' => 'YEAR(4)',
                    'null' => FALSE
            ),
            'gazal' => array(
                    'type' => "ENUM('0', '1')",
                    'default'=> '0'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('periode', TRUE);

        //formulir rencana kerja schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'periode_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'unit_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'versi' => array(
                'type' => 'INT',
                'default' => 1
            ),
            'revisi' => array(
                'type' => 'INT',
                'default' => 1
            ),
            'tanggal_berlaku' => array(
                'type' => 'DATE'
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP'
            )
        ));
        $this->dbforge->add_field('CONSTRAINT formulir_rencana_kerja_periode FOREIGN KEY (periode_id) REFERENCES periode(id)');
        $this->dbforge->add_field('CONSTRAINT formulir_rencana_kerja_unit FOREIGN KEY (unit_id) REFERENCES unit(id)');
        $this->dbforge->add_field('CONSTRAINT formulir_rencana_kerja_user FOREIGN KEY (user_id) REFERENCES user(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('formulir_rencana_kerja', TRUE);

        //detil rencana kerja schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'kpi_id' => array(
                'type' => 'INT'
            ),
            'formulir_rencana_kerja_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'bukti' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'target_institusi' => array(
                'type' => 'INT'
            ),
            'target_individu' => array(
                'type' => 'INT'
            ),
            'sumber' => array(
                'type' => 'enum("renop","mutu","renstra")',
                'default' => 'renstra'
            ),
            'status' => array(
                'type' => 'enum("1","0")',
                'default' => '0'
            ),
            'nilai_aktual' => array(
                'type' => 'INT',
                'default' => 0
            ),
            'formulir_ketua' => array(
                'type' => 'enum("1","0")',
                'default' => '0'
            )
        ));
        $this->dbforge->add_field('CONSTRAINT detil_formulir_rencana_kerja_kpi FOREIGN KEY (kpi_id) REFERENCES kpi(id)');
        $this->dbforge->add_field('CONSTRAINT detil_formulir_rencana_kerja_formulir_rencana_kerja FOREIGN KEY (formulir_rencana_kerja_id) REFERENCES formulir_rencana_kerja(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('detil_formulir_rencana_kerja', TRUE);

        //analisis ketidak tercapaian schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'detil_formulir_rencana_kerja_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'analisis_penyebab' => array(
                'type' => 'TEXT'
            ),
            'tindakan_korektif' => array(
                'type' => 'TEXT'
            ),
            'tindakan_pencegahan' => array(
                'type' => 'TEXT'
            )
        ));
        $this->dbforge->add_field('CONSTRAINT analisis_ketidak_tercapaian_detil_formulir_rencana_kerja FOREIGN KEY (detil_formulir_rencana_kerja_id) REFERENCES detil_formulir_rencana_kerja(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('analisis_ketidak_tercapaian', TRUE);

        //comment schema
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'detil_formulir_rencana_kerja_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'isi' => array(
                'type' => 'TEXT'
            ),
            'user_id' => array(
                'type' => 'INT',
                'unsigned' => TRUE
            ),
            'created_at' => array(
                'type' => 'TIMESTAMP'
            )
        ));
        $this->dbforge->add_field('CONSTRAINT comment_detil_formulir_rencana_kerja FOREIGN KEY (detil_formulir_rencana_kerja_id) REFERENCES detil_formulir_rencana_kerja(id)');
        $this->dbforge->add_field('CONSTRAINT comment_user FOREIGN KEY (user_id) REFERENCES user(id)');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('comment', TRUE);
    }

    public function down()
    {
        $this->dbforge->drop_table('hak_akses');
        $this->dbforge->drop_table('unit');
        $this->dbforge->drop_table('user');
        $this->dbforge->drop_table('ketua_unit');
        $this->dbforge->drop_table('indikator');
        $this->dbforge->drop_table('kamus_indikator');
        $this->dbforge->drop_table('kpi');
        $this->dbforge->drop_table('periode');
        $this->dbforge->drop_table('formulir_rencana_kerja');
        $this->dbforge->drop_table('detil_formulir_rencana_kerja');
        $this->dbforge->drop_table('analisis_ketidak_tercapaian');
        $this->dbforge->drop_table('comment');
    }
}



?>