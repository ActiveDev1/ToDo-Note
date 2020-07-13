<?php

route('GET', '/note', function () {
    $controller = new NoteController;

    return jsonResponse($controller->list());
});

route('POST', '/note', function () {
    $controller = new NoteController;

    return jsonResponse($controller->create());
});

route('PATCH', '/note', function () {
    $controller = new NoteController;

    return jsonResponse($controller->updateNote());
});

route('DELETE', '/note', function () {
    $controller = new NoteController;

    return jsonResponse($controller->delete());
});

route('GET', '/note/share/:id', function ($args) {
    $controller = new NoteController;

    return jsonResponse($controller->generateShareURL($args['id']));
});

route('DELETE', '/note/share/:id', function ($args) {
    $controller = new NoteController;

    return jsonResponse($controller->deleteShareURL($args['id']));
});

route('GET', '/note/:secret', function ($args) {
    $controller = new NoteController;
    $noteData = $controller->getSharedNote($args['secret']);

    if (!$noteData['success']) {
        return response(phtml(__DIR__ . '/../static/not-found.php'));
    }

    return response(
        phtml(
            __DIR__ . '/../static/note.php',
            [
                'data' => $noteData['data'],
            ]
        )
    );
});

route('GET', '/test', function () {
    echo json_encode($_GET);
    return jsonResponse(true);
});
