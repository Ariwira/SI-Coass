<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Penilaian extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'penilaian_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'coass_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'stase_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'date' => [
                'type' => 'DATE',
            ],
            'score' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ]
        ]);

        $this->forge->addPrimaryKey('penilaian_id');
        $this->forge->addForeignKey('coass_id', 'mahasiswa_coass', 'coass_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stase_id', 'stase', 'stase_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'doctor_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penilaian');
    }

    public function down()
    {
        $this->forge->dropTable('penilaian');
    }
}
