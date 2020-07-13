<?php

route('POST', '/user/register', function () {
    $controller = new UserController;

    return jsonResponse($controller->register());
});

route('POST', '/user/login', function () {
    $controller = new UserController;

    return jsonResponse($controller->login());
});

route('PATCH', '/user', function () {
    $controller = new UserController;

    return jsonResponse($controller->update());
});
