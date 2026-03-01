<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'type', 'name', 'email', 'password',
        'cpf', 'birth_date', 'cnpj', 'company_name', 'trade_name',
        'phone', 'active'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'name'     => 'required|min_length[3]',
        'type'     => 'required|in_list[pf,pj]',
    ];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->where('active', 1)->first();
    }
}
