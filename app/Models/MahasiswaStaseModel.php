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

    public function getStasesByCoassId($coassId)
    {
        return $this->select('mahasiswa_stase.stase_id, stase.name')
                    ->join('stase', 'stase.stase_id = mahasiswa_stase.stase_id') // Use stase_id for the join
                    ->where('mahasiswa_stase.coass_id', $coassId)
                    ->findAll();
    }

    public function getMahasiswaByStaseId($staseId)
    {
        return $this->where('stase_id', $staseId)->findAll();
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
