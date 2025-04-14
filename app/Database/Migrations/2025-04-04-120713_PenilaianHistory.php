<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PenilaianHistory extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'history_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'penilaian_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'action' => [
                'type'       => 'ENUM',
                'constraint' => ['create', 'update', 'delete'],
                'default'    => 'update',
            ],
            'old_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'new_score' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'old_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'new_feedback' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'null'    => true,
            ]

        ]);

        $this->forge->addPrimaryKey('history_id');
        $this->forge->addForeignKey('penilaian_id', 'penilaian', 'penilaian_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('penilaian_history');
    }

    public function down()
    {
        $this->forge->dropTable('penilaian_history');
    }
}
