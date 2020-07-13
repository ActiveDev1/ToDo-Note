<?php

route('GET', '/todo', function () {
    $controller = new TODOController;

    return jsonResponse($controller->list());
});

route('POST', '/todo', function () {
    $controller = new TODOController;

    return jsonResponse($controller->create());
});

route('PATCH', '/todo', function () {
    $controller = new TODOController;

    return jsonResponse($controller->updateTodo());
});

route('DELETE', '/todo', function () {
    $controller = new TODOController;

    return jsonResponse($controller->delete());
});