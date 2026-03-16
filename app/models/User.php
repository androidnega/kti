<?php

require_once __DIR__ . '/BaseModel.php';

class User extends BaseModel {
    protected $table = 'users';

    public function findByEmail($email) {
        return $this->db->fetchOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
    }

    public function createUser($name, $email, $password) {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password_hash' => Auth::hashPassword($password),
            'role' => 'admin'
        ]);
    }
}
