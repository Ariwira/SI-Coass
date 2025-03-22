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

    public function getPenilaianByMahasiswa($coass_id, $keyword = null)
    {
        $builder = $this->select('penilaian.*, stase.name as stase_name, doctors.name as doctor_name')
                        ->join('stase', 'stase.stase_id = penilaian.stase_id')
                        ->join('doctors', 'doctors.doctor_id = penilaian.doctor_id')
                        ->where('penilaian.coass_id', $coass_id);

        if ($keyword) {
            $builder->like('stase.name', $keyword)
                    ->orLike('doctors.name', $keyword);
        }

        return $builder->paginate(10); // pagination
    }

    public function getTotalPenilaianByMahasiswa($coass_id, $keyword = null)
    {
        $builder = $this->select('penilaian.*')
                        ->where('penilaian.coass_id', $coass_id);

        if ($keyword) {
            $builder->like('stase.name', $keyword)
                    ->orLike('doctors.name', $keyword);
        }

        return $builder->countAllResults(); // menghitung total hasil
    }

    public function getDetailPenilaian($id)
    {
        return $this->select('penilaian.*, stase.name as stase_name, doctors.name as doctor_name')
                    ->join('stase', 'stase.stase_id = penilaian.stase_id')
                    ->join('doctors', 'doctors.doctor_id = penilaian.doctor_id')
                    ->where('penilaian.penilaian_id', $id)
                    ->first();
    }

    protected $useTimestamps = true; // Menggunakan created_at dan updated_at
}
