<?php

class Validator {
    public function name($input) {
        return strlen(trim($input)) >= 3;
    }
    public function username($input) {
        return strlen(trim($input)) >= 5;
    }
}
