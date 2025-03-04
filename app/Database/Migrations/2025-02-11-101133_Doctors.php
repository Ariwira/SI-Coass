<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Doctors extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'doctor_id' => [
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
            'id_card' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'gender' => [
                'type'       => 'ENUM',
                'constraint' => ['Male', 'Female'],
            ],
            'mother_tongue' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'marital_status' => [
                'type'       => 'ENUM',
                'constraint' => ['Single', 'Married', 'Divorced', 'Widowed'],
            ],
            'religion' => [
                'type'       => 'ENUM',
                'constraint' => ['Islam', 'Hindu', 'Protestan', 'Katolik', 'Buddha', 'Konghucu'],
            ],
            'blood_group' => [
                'type'       => 'ENUM',
                'constraint' => ['A', 'B', 'AB', 'O', 'Unknown'],
            ],
            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'address' => [
                'type' => 'TEXT',
            ],
            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'qualification' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'nationality' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'mobile_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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

        $this->forge->addPrimaryKey('doctor_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('doctors');
    }

    public function down()
    {
        $this->forge->dropTable('doctors');
    }
}
