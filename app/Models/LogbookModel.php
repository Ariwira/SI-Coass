<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbookModel extends Model
{
    protected $table      = 'logbooks';
    protected $primaryKey = 'logbook_id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'coass_id',
        'stase_id',
        'date',
        'activity',
        'status',
        'feedback',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTotals($coass_id)
    {
        return [
            'total_logbooks' => $this->where('coass_id', $coass_id)->countAllResults(),
            'total_verified' => $this->where(['coass_id' => $coass_id, 'status' => 'Verified'])->countAllResults(),
            'total_rejected' => $this->where(['coass_id' => $coass_id, 'status' => 'Rejected'])->countAllResults(),
        ];
    }


    public function getLogbooksWithStase()
    {
        return $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->findAll();
    }

    public function getLogbooks($perPage, $page)
    {
        return $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->paginate($perPage, 'logbooks', $page);
    }

    public function searchLogbooks($keyword, $perPage, $page)
    {
        return $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->like('activity', $keyword)
            ->orLike('date', $keyword)
            ->paginate($perPage, 'logbooks', $page);
    }

    public function getLogbooksByCoassId($coass_id, $perPage, $page)
    {
        return $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->where('coass_id', $coass_id)
            ->paginate($perPage, 'logbooks', $page);
    }

    public function getLogbookById($id)
    {
        return $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->where('logbook_id', $id)
            ->first();
    }

    public function getLogbooksByDoctor($coass_id, $doctor_id, $keyword, $perPage)
    {
        $query = $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->join('doctors', 'doctors.doctor_id = stase.doctor_id') // Pastikan tabel stase menyimpan doctor_id
            ->where('logbooks.coass_id', $coass_id)
            ->where('stase.doctor_id', $doctor_id) // Hanya ambil logbook yang sesuai dengan dokter login
            ->orderBy('logbooks.updated_at', 'DESC');

        if (!empty($keyword)) {
            $query->groupStart()
                ->like('logbooks.activity', $keyword)
                ->orLike('stase.name', $keyword)
                ->groupEnd();
        }

        return $query->paginate($perPage, 'logbooks');
    }

    public function getLogbooksByCoass($id, $perPage, $keyword = null)
    {
        $builder = $this->select('logbooks.*, stase.name as stase_name')
            ->join('stase', 'stase.stase_id = logbooks.stase_id')
            ->where('logbooks.coass_id', $id)
            ->orderBy('logbooks.updated_at', 'DESC');

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('logbooks.activity', $keyword)
                ->orLike('stase.name', $keyword)
                ->groupEnd();
        }

        return $builder->paginate($perPage, 'logbooks');
    }

    protected $validationRules = [
        'coass_id' => 'required|integer',
        'stase_id' => 'required|integer',
        'date'     => 'required|valid_date',
        'activity' => 'required|string',
        'status'   => 'permit_empty|in_list[Not Verified,Verified,Rejected]',
        'feedback' => 'permit_empty|string',
    ];
}
