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

    // Optional: If you want to use timestamps
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function getTotals($coass_id)
    {
        return [
            'total_logbooks' => $this->where('coass_id', $coass_id)->countAllResults(),
            'total_verified' => $this->where(['coass_id' => $coass_id, 'status' => 'verified'])->countAllResults(),
            'total_not_verified' => $this->where(['coass_id' => $coass_id, 'status' => 'not_verified'])->countAllResults(),
        ];
    }

    // Validation rules
    protected $validationRules = [
        'coass_id' => 'required|integer',
        'stase_id' => 'required|integer',
        'date'     => 'required|valid_date',
        'activity' => 'required|string',
        'status'   => 'required|in_list[Not Verified,Verified,Pending]',
        'feedback' => 'permit_empty|string',
    ];
}
