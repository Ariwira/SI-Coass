<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa_coass';
    protected $primaryKey       = 'coass_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id',
        'name',
        'nim',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'religion',
        'blood_group',
        'phone',
        'mobile_no',
        'address',
        'university',
        'year',
        'photo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get students with their corresponding user data
     */
    public function getStudentsWithUsers()
    {
        return $this->select('mahasiswa_coass.*, users.email')
            ->join('users', 'users.id = mahasiswa_coass.user_id')
            ->orderBy('mahasiswa_coass.name', 'ASC');
    }

    /**
     * Search students by name, nim, email, university
     */
    public function search($keyword)
    {
        return $this->select('mahasiswa_coass.*, users.email')
            ->join('users', 'users.id = mahasiswa_coass.user_id')
            ->like('mahasiswa_coass.name', $keyword)
            ->orLike('mahasiswa_coass.nim', $keyword)
            ->orLike('users.email', $keyword)
            ->orLike('mahasiswa_coass.university', $keyword)
            ->orderBy('mahasiswa_coass.name', 'ASC');
    }
}
