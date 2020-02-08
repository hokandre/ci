<?php

defined('BASEPATH') OR exit('No direct script access allowed ');

class Migration_add_bidang extends CI_Migration {
    public function __construct()
    {
        parent::__construct();
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                    'type' => 'CHAR',
                    'constraint' => '1'
            ),
            'nama_bidang' => array(
                    'type' => 'VARCHAR',
                    'constraint' => '50'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('bidang', TRUE);

        //change column name periode to semester and data type from enum('0','1') to enum('1','2');
        $this->db->query("ALTER TABLE periode ADD COLUMN semester enum('1', '2') DEFAULT '1' ");
        $query = $this->db->query('SELECT id, gazal FROM periode');
        $data = $query->result();
        foreach($data as $row){
            $enumValue = (string) $row->gazal == '0' ? '2' : '1'; 
            $sql = "UPDATE periode SET semester = $enumValue WHERE id = $row->id";
            $this->db->query($sql);
        }
        $this->db->query("ALTER TABLE periode DROP COLUMN gazal");


    }

    public function down(){
        $this->dbforge->drop_table('bidang');
        
        $this->db->query("ALTER TABLE periode ADD COLUMN gazal enum('0', '1') DEFAULT '0' ");
        $query = $this->db->query('SELECT id, semester FROM periode');
        $data = $query->result();
        foreach($data as $row){
            $enumValue = (string) $row->semester == '2' ? '0' : '1'; 
            $sql = "UPDATE periode SET gazal = $enumValue WHERE id = $row->id";
            $this->db->query($sql);
        }
        $this->db->query("ALTER TABLE periode DROP COLUMN semester");
    }

}
?>