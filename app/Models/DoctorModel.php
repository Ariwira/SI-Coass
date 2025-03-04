<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    protected $table            = 'doctors';
    protected $primaryKey       = 'doctor_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id',
        'name',
        'id_card',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'mother_tongue',
        'marital_status',
        'religion',
        'blood_group',
        'city',
        'address',
        'state',
        'qualification',
        'nationality',
        'phone',
        'mobile_no',
        'photo'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get doctors with their corresponding user data
     */
    public function getDoctorsWithUsers()
    {
        return $this->select('doctors.*, users.email')
            ->join('users', 'users.id = doctors.user_id')
            ->orderBy('doctors.name', 'ASC');
    }

    /**
     * Search doctors by name, id_card, email, qualification
     */
    public function search($keyword)
    {
        return $this->select('doctors.*, users.email')
            ->join('users', 'users.id = doctors.user_id')
            ->like('doctors.name', $keyword)
            ->orLike('doctors.id_card', $keyword)
            ->orLike('users.email', $keyword)
            ->orLike('doctors.qualification', $keyword)
            ->orderBy('doctors.name', 'ASC');
    }
}
