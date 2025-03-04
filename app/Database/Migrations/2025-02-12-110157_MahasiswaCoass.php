<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MahasiswaCoass extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'coass_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'date_of_birth' => [
                'type' => 'DATE',
            ],
            'place_of_birth' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nim' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'religion' => [
                'type'       => 'ENUM',
                'constraint' => ['Islam', 'Hindu', 'Protestan', 'Katolik', 'Buddha', 'Konghucu'],
            ],
            'blood_group' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'AB', 'O', 'Unknown'],
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'mobile_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'address' => [
                'type' => 'TEXT',
            ],
            'university' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'year' => [
                'type' => 'YEAR',
            ],
            'photo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
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

        $this->forge->addPrimaryKey('coass_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('mahasiswa_coass');
    }

    public function down()
    {
        $this->forge->dropTable('mahasiswa_coass');
    }
}
