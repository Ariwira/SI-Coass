<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MahasiswaStase extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'stase_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'coass_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('stase_id', 'stase', 'stase_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('coass_id', 'mahasiswa_coass', 'coass_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mahasiswa_stase');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa_stase');
    }
}
