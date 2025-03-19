<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianModel extends Model
{
    protected $table = 'penilaian';
    protected $primaryKey = 'penilaian_id';
    protected $allowedFields = [
        'coass_id',
        'stase_id',
        'doctor_id',
        'date',
        'score',
        'feedback',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true; // Menggunakan created_at dan updated_at
}
