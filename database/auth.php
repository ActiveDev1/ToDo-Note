<?php

class AuthCheck extends Database {
    public function __construct() {
        if (!$this->conn) {
            parent::__construct();
        }

        return $this;
    }

    public function authenticate() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            exit;
        }
        $token = $headers['Authorization'];

        $foundUser = $this->fetch(
            "SELECT * FROM `users`
            WHERE
                `token`=?
            LIMIT 1",
            [$token]
        );

        if (!$foundUser) {
            http_response_code(401);
            exit;
        }

        return $foundUser;
    }
}
