<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory as Faker;

class DokterSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Mengatur user_id dokter mulai dari 22 hingga 42
        for ($userId = 22; $userId <= 41; $userId++) {
            $data = [
                'user_id' => $userId, // Mengaitkan dengan user yang ada
                'name' => $faker->name,
                'date_of_birth' => $faker->date(),
                'place_of_birth' => $faker->city,
                'id_card' => $faker->unique()->numerify('################'),
                'gender' => $faker->randomElement(['Male', 'Female']),
                'mother_tongue' => $faker->word,
                'marital_status' => $faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']),
                'religion' => $faker->randomElement(['Islam', 'Hindu', 'Protestan', 'Katolik', 'Buddha', 'Konghucu']),
                'blood_group' => $faker->randomElement(['A', 'B', 'AB', 'O', 'Unknown']),
                'city' => $faker->city,
                'address' => $faker->address,
                'state' => $faker->state,
                'qualification' => $faker->word,
                'nationality' => $faker->country,
                'phone' => $faker->phoneNumber,
                'mobile_no' => $faker->phoneNumber,
                'photo' => 'default-avatar.jpg', // Atau bisa diisi dengan URL gambar palsu
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            $this->db->table('doctors')->insert($data);
        }
    }
}
