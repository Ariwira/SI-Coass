<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['email', 'password', 'role', 'remember_token'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'email'    => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[8]',
        'role'     => 'required|in_list[Admin,Dokter,Mahasiswa Coass]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;

    public function verifyPassword($email, $password)
    {
        $user = $this->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    public function updateRememberToken($userId, $token)
    {
        return $this->update($userId, ['remember_token' => $token]);
    }

    public function getUserByRememberToken($token)
    {
        return $this->where('remember_token', $token)->first();
    }

    public function getTotalUsers()
    {
        return $this->countAll();
    }
}
