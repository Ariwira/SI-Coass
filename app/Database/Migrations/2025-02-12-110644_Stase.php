<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stase extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'stase_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'duration_weeks' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'department' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'start_date' => [
                'type' => 'DATE',
            ],
            'end_date' => [
                'type' => 'DATE',
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

        $this->forge->addPrimaryKey('stase_id');
        $this->forge->addForeignKey('doctor_id', 'doctors', 'doctor_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('stase');
    }

    public function down()
    {
        $this->forge->dropTable('stase');
    }
}
