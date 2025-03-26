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
        return $this->select('penilaian.*, stase.name as stase_name, stase.department as department, doctors.name as doctor_name')
            ->join('stase', 'stase.stase_id = penilaian.stase_id', 'left')
            ->join('doctors', 'doctors.doctor_id = penilaian.doctor_id', 'left')
            ->where('penilaian.penilaian_id', $id)
            ->first();
    }

    public function getPenilaian($staseID, $coassID, $doctorID = null)
    {
        $query = $this->where('stase_id', $staseID)
            ->where('coass_id', $coassID);

        if ($doctorID !== null) {
            $query->where('doctor_id', $doctorID);
        }

        $result = $query->first();

        // Jika tidak ditemukan dengan doctor_id, coba tanpa doctor_id
        if (!$result && $doctorID !== null) {
            $result = $this->where('stase_id', $staseID)
                ->where('coass_id', $coassID)
                ->first();
        }

        return $result;
    }


    public function insertPenilaian($data)
    {
        return $this->insert($data);
    }

    public function updatePenilaian($staseID, $coassID, $data, $doctorID = null)
    {
        $this->where('stase_id', $staseID)
            ->where('coass_id', $coassID);

        if ($doctorID !== null) {
            $this->where('doctor_id', $doctorID);
        }

        return $this->set($data)->update();
    }

    public function deletePenilaian($staseID, $coassID, $doctorID = null)
    {
        $this->where('stase_id', $staseID)
            ->where('coass_id', $coassID);

        if ($doctorID !== null) {
            $this->where('doctor_id', $doctorID);
        }

        return $this->delete();
    }
}
