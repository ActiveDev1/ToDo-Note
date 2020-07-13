<?php

foreach (glob("./class/*.php") as $filename) {
    include $filename;
}

include './database/db.php';
foreach (glob("./database/*.php") as $filename) {
    if ($filename != './database/db.php') {
        include $filename;
    }
}

$body = json_decode(file_get_contents('php://input'), true) ? : $_POST;
$db = new Database;
$auth = new AuthCheck;

foreach (glob("./controller/*.php") as $filename) {
    include $filename;
}

foreach (glob("./routes/*.php") as $filename) {
    include $filename;
}

dispatch();
