<?php

class UserController {
    public function register() {
        try {
            global $body;
            // gather input data
            $name = $body['name'];
            $username = $body['username'];
            $password = $body['password'];
            $email = $body['email'];

            // validate inputs
            $validator = new Validator;
            if (!$validator->name($name)) {
                throw new Exception("invalid name length!");
            }
            if (!$validator->username($username)) {
                throw new Exception("invalid username length!");
            }

            $userDB = new UserDB;
            $registerInfo = $userDB->register($name, $username, $password, $email);

            return [
                'success' => true,
                'data' => $registerInfo,
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            if ($th->getCode() == 23000) {
                $error = 'Given username or email is already exist!';
            }

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }

    public function login() {
        try {
            global $body;
            // gather input data
            $usernameOrEmail = $body['usernameOrEmail'];
            $password = $body['password'];

            if (!$usernameOrEmail || !$password ) {
                throw new Exception("Please enter username and password", 1);
            }

            $userDB = new UserDB;
            $foundUser = $userDB->findUserByUsernameOrEmail($usernameOrEmail);
            if (!$foundUser) {
                throw new Exception("User not found", 1);
            }

            if ( sha1($password) != $foundUser['password'] ) {
                throw new Exception("Wrong Password", 1);
            }

            return [
                'success' => true,
                'data' => [
                    'token' => $foundUser['token'],
                    'username' => $foundUser['username'],
                ],
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }

    public function update() {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            $newUsername = $body['username'];
            $validator = new Validator;
            if (!$validator->name($newUsername)) {
                throw new Exception("invalid name length!");
            }
            if (!$newUsername) {
                throw new Exception("Username is invalid", 1);
            }
            $userDB = new UserDB;
            $updateResult = $userDB->updateUsername($newUsername, $foundUser['id']);

            if (!$updateResult) {
                throw new Exception("Error on Update", 1);
            }

            return [
                'success' => true,
                'data' => [
                    'username' => $newUsername,
                ],
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }
}
