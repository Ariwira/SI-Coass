<?php

namespace App\Controllers;

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
            $this->setUserSession($user);

            if ($rememberMe) {
                $this->setRememberMeToken($user['id']);
            }

            return $this->redirectBasedOnRole();
        }

        session()->setFlashdata('error', 'Email atau password tidak valid');
        return redirect()->to('/auth/login');
    }

    private function setUserSession($user)
    {
        $db = \Config\Database::connect();
        $name = 'User';

        if ($user['role'] === 'Admin') {
            $name = 'Administrator';
        } elseif ($user['role'] === 'Dokter') {
            $query = $db->table('doctors')->where('user_id', $user['id'])->get()->getRow();
            $name = $query->name ?? 'Dokter';
        } elseif ($user['role'] === 'Mahasiswa Coass') {
            $query = $db->table('mahasiswa_coass')->where('user_id', $user['id'])->get()->getRow();
            $name = $query->name ?? 'Mahasiswa';
        }

        session()->set([
            'id'        => $user['id'],
            'email'     => $user['email'],
            'name'      => $name,
            'role'      => $user['role'],
            'logged_in' => true
        ]);
    }

    private function setRememberMeToken($userId)
    {
        helper('cookie');
        $userModel = new UserModel();

        $token = bin2hex(random_bytes(32));
        $hashedToken = password_hash($token, PASSWORD_DEFAULT);
        $userModel->update($userId, ['remember_token' => $hashedToken]);

        set_cookie('remember_token', $token, 30 * 24 * 60 * 60, '/', '', false, true);
        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), "/"); // Alternatif manual


        log_message('info', 'Remember Me Token Set: ' . $token);
    }

    public function verifyRememberMe()
    {
        helper('cookie');
        $rememberedToken = get_cookie('remember_token');

        if ($rememberedToken) {
            $userModel = new UserModel();
            $user = $userModel->where('remember_token IS NOT NULL')->findAll();

            foreach ($user as $u) {
                if (password_verify($rememberedToken, $u['remember_token'])) {
                    log_message('info', 'User Found: ' . json_encode($u));
                    $this->setUserSession($u);
                    return $this->redirectBasedOnRole();
                }
            }
        }

        log_message('info', 'No user found for the provided remember token.');
    }

    private function redirectBasedOnRole()
    {
        $role = session()->get('role');

        return match ($role) {
            'Admin' => redirect()->to('/admin/dashboard'),
            'Dokter' => redirect()->to('/dokter/dashboard'),
            'Mahasiswa Coass' => redirect()->to('/mahasiswa/dashboard'),
            default => redirect()->to('/auth/login'),
        };
    }

    public function logout()
    {
        helper('cookie'); // Tambahkan ini sebelum delete_cookie()

        $userModel = new UserModel();

        if (session()->has('id')) {
            $userModel->update(session()->get('id'), ['remember_token' => null]);
        }

        // Hapus cookie dengan metode yang lebih aman
        set_cookie('remember_token', '', -3600, '/');
        setcookie('remember_token', '', time() - 3600, "/");

        session()->destroy();

        return redirect()->to('/auth/login');
    }
}
