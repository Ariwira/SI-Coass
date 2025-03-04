<?php

namespace App\Controllers;

helper('cookie');

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        // Check for remember me cookie
        $rememberedToken = get_cookie('remember_token');
        if ($rememberedToken) {
            $userModel = new UserModel();
            $user = $userModel->where('remember_token', $rememberedToken)->first();

            if ($user) {
                // Set session variables
                $this->setUserSession($user);
                return $this->redirectBasedOnRole();
            }
        }

        // Check if user already logged in
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }

        return view('auth/login');
    }

    public function processLogin()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $rememberMe = $this->request->getPost('remember_me') === 'on';

        $userModel = new UserModel();
        $user = $userModel->verifyPassword($email, $password);

        if ($user) {
            // Set session variables
            $this->setUserSession($user);

            // Handle remember me
            if ($rememberMe) {
                $this->setRememberMeToken($user['id']);
            }

            return $this->redirectBasedOnRole();
        } else {
            session()->setFlashdata('error', 'Email atau password tidak valid');
            return redirect()->to('/auth/login');
        }
    }

    private function setUserSession($user)
    {
        $db = \Config\Database::connect();
        $name = '';

        // Cek role dan ambil nama dari tabel yang sesuai
        if ($user['role'] == 'Admin') {
            $name = 'Administrator';
        } elseif ($user['role'] == 'Dokter') {
            $query = $db->table('dokter')->where('user_id', $user['id'])->get()->getRow();
            $name = $query ? $query->nama : 'Dokter';
        } elseif ($user['role'] == 'Mahasiswa Coass') {
            $query = $db->table('mahasiswa')->where('user_id', $user['id'])->get()->getRow();
            $name = $query ? $query->nama : 'Mahasiswa';
        }

        $session_data = [
            'id'       => $user['id'],
            'email'    => $user['email'],
            'name'     => $name, // Menyimpan nama sesuai role
            'role'     => $user['role'],
            'logged_in' => TRUE
        ];
        session()->set($session_data);
    }


    private function setRememberMeToken($userId)
    {
        $userModel = new UserModel();

        // Generate random token
        $token = bin2hex(random_bytes(32));

        // Save token to database
        $userModel->update($userId, ['remember_token' => $token]);

        // Set cookie that expires in 30 days
        set_cookie(
            'remember_token',
            $token,
            30 * 24 * 60 * 60  // 30 days in seconds
        );
    }

    private function redirectBasedOnRole()
    {
        switch (session()->get('role')) {
            case 'Admin':
                return redirect()->to('/admin/dashboard');
            case 'Dokter':
                return redirect()->to('/dokter/dashboard');
            case 'Mahasiswa Coass':
                return redirect()->to('/mahasiswa/dashboard');
            default:
                return redirect()->to('/auth/login');
        }
    }




    public function logout()
    {
        // Clear remember me token if exists
        if (session()->get('id')) {
            $userModel = new UserModel();
            $userModel->update(session()->get('id'), ['remember_token' => null]);
        }

        // Delete remember me cookie
        delete_cookie('remember_token');

        // Destroy session
        session()->destroy();

        return redirect()->to('/auth/login');
    }
}
