<?php

class UserDB extends Database {
    public function __construct() {
        if (!$this->conn) {
            parent::__construct();
        }
    }

    public function register($name, $username, $password, $email) {
        $util = new Utility;
        $token = $util->randomStringGenerator(50);
        $ins = [
            'name' => $name,
            'username' => $username,
            'password' => sha1($password),
            'email' => $email,
            'token' => $token,
        ];

        $this->insert('users', $ins);

        return [
            'token' => $token,
            'username' => $ins['username'],
        ];
    }

    public function findUserByUsernameOrEmail($usernameOrEmail) {
        return $this->fetch(
            /** @lang text */ "SELECT * FROM `users` WHERE `username`=? OR `email`=? LIMIT 1",
            [$usernameOrEmail, $usernameOrEmail]
        );
    }

    public function updateUsername($username, $userID) {
        return $this->query(
            "UPDATE `users`
            SET
                `username`=?
            WHERE
                `id`=?",
            [
                $username,
                $userID,
            ]
        );
    }
}
