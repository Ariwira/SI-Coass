<?php

namespace App\Models;

use CodeIgniter\Model;

class PenilaianHistoryModel extends Model
{
    protected $table = 'penilaian_history';
    protected $primaryKey = 'history_id';
    protected $allowedFields = [
        'penilaian_id',
        'user_id',
        'action',
        'old_score',
        'new_score',
        'old_feedback',
        'new_feedback',
        'created_at',
        'updated_at'

    ];
    protected $useTimestamps = true;

    public function getHistoryByPenilaian($penilaianID)
    {
        return $this->select('penilaian_history.*, users.role, users.id')
            ->join('users', 'users.id = penilaian_history.user_id', 'left')
            ->where('penilaian_history.penilaian_id', $penilaianID)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    public function getHistoryWithUserNames($penilaianID)
    {
        $builder = $this->db->table('penilaian_history ph')
            ->select('ph.*, u.role, d.name AS doctor_name')
            ->join('users u', 'u.id = ph.user_id', 'left')
            ->join('doctors d', 'd.user_id = ph.user_id', 'left')
            ->where('ph.penilaian_id', $penilaianID)
            ->orderBy('ph.created_at', 'DESC');

        $history = $builder->get()->getResultArray();

        // Mapping user_name berdasarkan role
        foreach ($history as &$item) {
            if ($item['role'] === 'Admin') {
                $item['user_name'] = 'Administrator';
            } elseif ($item['role'] === 'Dokter') {
                $item['user_name'] = $item['doctor_name'] ?? 'Dokter tidak ditemukan';
            } else {
                $item['user_name'] = 'Pengguna tidak dikenal';
            }
        }

        return $history;
    }
}
