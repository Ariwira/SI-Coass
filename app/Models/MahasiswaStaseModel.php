<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaStaseModel extends Model
{
    protected $table            = 'mahasiswa_stase';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'stase_id',
        'coass_id',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get all mahasiswa for a specific stase
     *
     * @param int $staseId
     * @return array
     */

    public function getStasesByCoassId($coassId, $keyword = null, $perPage = 10, $currentPage = 1)
    {
        $builder = $this->select('mahasiswa_stase.stase_id, stase.name, stase.description, stase.department, stase.status, stase.duration_weeks, stase.start_date, stase.end_date, stase.doctor_id') // Include doctor_id
            ->join('stase', 'stase.stase_id = mahasiswa_stase.stase_id')
            ->where('mahasiswa_stase.coass_id', $coassId);

        if ($keyword) {
            $builder->groupStart()
                ->like('stase.name', $keyword)
                ->orLike('stase.description', $keyword)
                ->orLike('stase.department', $keyword)
                ->groupEnd();
        }

        return $builder->paginate($perPage, 'stases', $currentPage);
    }

    public function getMahasiswaByStaseId($staseId, $keyword = null)
    {
        $builder = $this->select('mahasiswa_coass.*, users.email') // Ambil kolom yang diperlukan
            ->join('mahasiswa_coass', 'mahasiswa_coass.coass_id = mahasiswa_stase.coass_id')
            ->join('users', 'users.id = mahasiswa_coass.user_id', 'left') // Gabungkan dengan tabel users
            ->where('mahasiswa_stase.stase_id', $staseId);

        if ($keyword) {
            $builder->groupStart()
                ->like('mahasiswa_coass.name', $keyword)
                ->orLike('mahasiswa_coass.nim', $keyword)
                ->orLike('mahasiswa_coass.university', $keyword)
                ->groupEnd();
        }

        return $builder; // Kembalikan query builder
    }

    /**
     * Add a new mahasiswa to a stase
     *
     * @param array $data
     * @return bool
     */
    public function addMahasiswaToStase(array $data)
    {
        return $this->insert($data);
    }

    /**
     * Update a mahasiswa-stase relationship
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateMahasiswaStase($id, array $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Delete a mahasiswa-stase relationship
     *
     * @param int $id
     * @return bool
     */
    public function deleteMahasiswaStase($id)
    {
        return $this->delete($id);
    }
}
