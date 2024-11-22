<?php

class UserManager
{
    private $filePath;

    public function __construct($filePath = '../data/users.json')
    {
        $this->filePath = $filePath;

        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    public function getAllUsers()
    {
        $data = file_get_contents($this->filePath);
        return json_decode($data, true) ?? [];
    }

    public function registerUser($username, $password)
    {
        $users = $this->getAllUsers();

        foreach ($users as $user) {
            if ($user['username'] === $username) {
                throw new Exception("Le nom d'utilisateur existe déjà.");
            }
        }

        $id = count($users) > 0 ? max(array_column($users, 'id')) + 1 : 1;

        $newUser = [
            'id' => $id,
            'username' => htmlspecialchars($username),
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ];

        $users[] = $newUser;
        $this->saveUsers($users);
    }

    public function authenticate($username, $password)
    {
        $users = $this->getAllUsers();

        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    private function saveUsers($users)
    {
        file_put_contents($this->filePath, json_encode($users, JSON_PRETTY_PRINT));
    }
}
