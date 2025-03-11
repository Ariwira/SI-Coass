<?php

namespace App\Models;

use CodeIgniter\Model;

class StaseModel extends Model
{
    protected $table = 'stase';
    protected $primaryKey = 'stase_id';
    protected $allowedFields = ['doctor_id', 'name', 'description', 'duration_weeks', 'department', 'start_date', 'end_date',  'status'];

    public function getTotalStase()
    {
        return $this->countAll(); // This will return the total number of records in the stase table
    }


    // Method to search stase by name, description, or department
    public function search($keyword)
    {
        return $this->like('name', $keyword)
            ->orLike('description', $keyword)
            ->orLike('department', $keyword);
    }

    public function getLatestStases($limit = 5)
    {
        return $this->select('stase.*, doctors.name as doctor_name')
            ->join('doctors', 'doctors.doctor_id = stase.doctor_id')
            ->orderBy('stase.created_at', 'DESC')
            ->findAll($limit);
    }

    // Method to get all stase with associated doctor information
    public function getStasesWithDoctors()
    {
        return $this->select('stase.*, doctors.name as doctor_name')
            ->join('doctors', 'doctors.doctor_id = stase.doctor_id')
            ->orderBy('stase.start_date', 'ASC');
    }
}
