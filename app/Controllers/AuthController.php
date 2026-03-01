<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function index()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }
        return view('auth/login');
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $model = new UserModel();
        $user  = $model->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'E-mail ou senha inválidos.');
        }

        session()->set([
            'user_id'      => $user['id'],
            'user_name'    => $user['name'],
            'user_email'   => $user['email'],
            'user_type'    => $user['type'],
            'company_name' => $user['company_name'] ?? null,
            'trade_name'   => $user['trade_name'] ?? null,
            'logged_in'    => true,
        ]);

        return redirect()->to('/home')->with('success', 'Bem-vindo(a), ' . $user['name'] . '!');
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/home');
        }
        return view('auth/register');
    }

    public function store()
    {
        $type = $this->request->getPost('type');

        $rules = [
            'type'     => 'required|in_list[pf,pj]',
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'phone'    => 'permit_empty|max_length[20]',
        ];

        if ($type === 'pf') {
            $rules['cpf']        = 'required|min_length[11]|max_length[14]';
            $rules['birth_date'] = 'required|valid_date';
        } else {
            $rules['cnpj']         = 'required|min_length[14]|max_length[18]';
            $rules['company_name'] = 'required|min_length[3]';
            $rules['trade_name']   = 'required|min_length[2]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'type'     => $type,
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone'    => $this->request->getPost('phone'),
        ];

        if ($type === 'pf') {
            $data['cpf']        = $this->request->getPost('cpf');
            $data['birth_date'] = $this->request->getPost('birth_date');
        } else {
            $data['cnpj']         = $this->request->getPost('cnpj');
            $data['company_name'] = $this->request->getPost('company_name');
            $data['trade_name']   = $this->request->getPost('trade_name');
        }

        $model = new UserModel();
        $model->insert($data);

        return redirect()->to('/login')->with('success', 'Cadastro realizado com sucesso! Faça login.');
    }

    public function changePasswordForm()
    {
        return view('auth/change_password', ['title' => 'Trocar Senha']);
    }

    public function changePassword()
    {
        $current  = $this->request->getPost('current_password');
        $new      = $this->request->getPost('new_password');
        $confirm  = $this->request->getPost('confirm_password');

        if ($new !== $confirm) {
            return redirect()->back()->with('error', 'A nova senha e a confirmação não coincidem.');
        }

        if (strlen($new) < 6) {
            return redirect()->back()->with('error', 'A nova senha deve ter pelo menos 6 caracteres.');
        }

        $model = new UserModel();
        $user  = $model->find(session()->get('user_id'));

        if (!password_verify($current, $user['password'])) {
            return redirect()->back()->with('error', 'Senha atual incorreta.');
        }

        $model->update($user['id'], ['password' => password_hash($new, PASSWORD_DEFAULT)]);

        return redirect()->to('/profile/password')->with('success', 'Senha alterada com sucesso!');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Você saiu com sucesso.');
    }
}
