<?php 

defined('BASEPATH') OR exit('No direct script access allowed ');

class Migration_add_institusi extends CI_Migration {
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }
    
    public function up()
    {
        $fields = [
            'id' => array(
                'type' => 'INT',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
                ),
            'nama_institusi' => array(
                'type' => 'VARCHAR',
                'constraint' => 10
            )
        ];
        
        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('institusi', TRUE);

        //drop constraint hak akses pada table user
        $this->db->query("ALTER TABLE user DROP FOREIGN KEY user_hak_akses");
        //drop column hak akses pada table user
        $this->db->query("ALTER TABLE user DROP COLUMN hak_akses");
        //add column institusi_id pada table unit
        $this->db->query("ALTER TABLE unit ADD COLUMN institusi_id INT UNSIGNED");
        //add constraint institusi pada table unit
        $this->db->query("ALTER TABLE unit ADD CONSTRAINT unit_institusi FOREIGN KEY (institusi_id) REFERENCES institusi(id)");

    }

    public function down()
    {
        $this->dbforge->drop_table('institusi');
        $this->db->query("ALTER TABLE user ADD COLUMN hak_akses INT UNSIGNED");
        $this->db->query("ALTER TABLE user ADD CONSTRAINT user_hak_akses FOREIGN KEY (hak_akses) REFERENCES hak_akses(id)");
        $this->db->query("ALTER TABLE unit DROP FOREIGN KEY unit_institusi"); 
        $this->db->query("ALTER TABLE unit DROP COLUMN intitusi_id");       

    }
}

?>