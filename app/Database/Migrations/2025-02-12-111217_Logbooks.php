<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Logbooks extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'logbook_id' => [
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
            'date' => [
                'type' => 'DATE',
            ],
            'activity' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Not Verified', 'Verified', 'Pending'],
                'default'    => 'Not Verified',
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
            ],
        ]);

        $this->forge->addPrimaryKey('logbook_id');
        $this->forge->addForeignKey('coass_id', 'mahasiswa_coass', 'coass_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('stase_id', 'stase', 'stase_id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('logbooks');
    }

    public function down()
    {
        $this->forge->dropTable('logbooks');
    }
}
