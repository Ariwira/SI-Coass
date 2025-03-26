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

    public function getTotalMahasiswa()
    {
        return $this->countAll(); // This will return the total number of records in the mahasiswa table
    }

    /**
     * Get students with their corresponding user data
     */
    public function getStudentsWithUsers()
    {
        return $this->select('mahasiswa_coass.*, users.email')
            ->join('users', 'users.id = mahasiswa_coass.user_id')
            ->orderBy('mahasiswa_coass.updated_at', 'DESC');
    }

    public function getLatestStudents($limit = 5)
    {
        return $this->select('mahasiswa_coass.*, users.email')
            ->join('users', 'users.id = mahasiswa_coass.user_id')
            ->orderBy('mahasiswa_coass.created_at', 'DESC')
            ->findAll($limit);
    }

    public function getStudentsByDoctor($doctor_id, $keyword = null, $perPage = 10)
    {
        $query = $this->select('mahasiswa_coass.*, users.email')
            ->join('logbooks', 'logbooks.coass_id = mahasiswa_coass.coass_id')
            ->join('users', 'users.id = mahasiswa_coass.user_id')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->where('stase.doctor_id', $doctor_id)
            ->groupBy('mahasiswa_coass.coass_id');

        if (!empty($keyword)) {
            $query->like('mahasiswa_coass.name', $keyword);
        }

        return $query->paginate($perPage, 'students');
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

    public function getMahasiswaByStase($staseID, $keyword = null)
    {
        $this->select('mahasiswa_coass.coass_id, mahasiswa_coass.name, mahasiswa_coass.nim, mahasiswa_coass.university, users.email')
            ->join('mahasiswa_stase', 'mahasiswa_stase.coass_id = mahasiswa_coass.coass_id')
            ->join('users', 'users.id = mahasiswa_coass.user_id', 'left')
            ->where('mahasiswa_stase.stase_id', $staseID);

        if ($keyword) {
            $this->groupStart()
                ->like('mahasiswa_coass.name', $keyword)
                ->orLike('mahasiswa_coass.nim', $keyword)
                ->orLike('mahasiswa_coass.university', $keyword)
                ->orLike('users.email', $keyword)
                ->groupEnd();
        }

        return $this->paginate(5, 'mahasiswa');
    }

    public function getStudentsByStaseWithUsers($staseID, $keyword = null)
    {
        $this->select('mahasiswa_coass.coass_id, mahasiswa_coass.name, mahasiswa_coass.nim, mahasiswa_coass.university, users.email')
            ->join('mahasiswa_stase', 'mahasiswa_stase.coass_id = mahasiswa_coass.coass_id')
            ->join('users', 'users.id = mahasiswa_coass.user_id', 'left')
            ->where('mahasiswa_stase.stase_id', $staseID);

        if ($keyword) {
            $this->groupStart()
                ->like('mahasiswa_coass.name', $keyword)
                ->orLike('mahasiswa_coass.nim', $keyword)
                ->orLike('mahasiswa_coass.university', $keyword)
                ->groupEnd();
        }

        return $this->paginate(5, 'mahasiswa');
    }

    public function getAllMahasiswa()
    {
        return $this->findAll();
    }
}
