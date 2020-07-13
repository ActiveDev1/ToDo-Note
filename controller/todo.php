<?php
class TODOController
{
    public function list()
    {
        try {
            global $auth;
            $foundUser = $auth->authenticate();
            $todoDB = new TODO_DB;
            $todoList = $todoDB->getList($foundUser['id']);

            return [
                'success' => true,
                'data' => $todoList
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();
            return [
                'success' => false,
                'error' => $error
            ];
        }
    }

    public function create()
    {
        try {
            global $auth, $body;

            $foundUser = $auth->authenticate();
            // gather todo data
            $text = $body['text'];
            $due_date = $body['due_date'];

            if (!$text)
                throw new Exception("Text cant't be empty", 1);

            $todoDB = new TODO_DB;

            $createResult = $todoDB->create($text, $due_date, $foundUser['id']);
            if (!$createResult) {
                throw new Exception("Error on saving todo", 1);
            }

            return [
                'success' => true,
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }

    public function updateTodo()
    {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            $todoIDForUpdate = $body['id'];
            $text = $body['text'];
            $due_date = $body['due_date'];

            if (!$text) {
                throw new Exception("text can't be empty", 1);
            }

            if (!$due_date) {
                throw new Exception("due date can't be empty", 1);
            }

            if (!$todoIDForUpdate) {
                throw new Exception("Invalid ID", 1);
            }

            $todoDB = new TODO_DB;
            $foundTodo = $todoDB->getTodoByID($todoIDForUpdate);
      
            if (!$foundTodo) {
                throw new Exception("Todo Not Found", 1);
            }

            if ( $foundTodo['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $todoDB->updateNote($todoIDForUpdate, $text, $due_date);

            return [
                'success' => true,
            ];

        } catch (\Throwable $th) {
            $error = $th->getMessage();

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }

    public function delete() {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            $todoIDForUpdate = $body['id'];
            if (!$todoIDForUpdate) {
                throw new Exception("Invalid ID", 1);
            }

            $todoDB = new TODO_DB;
            $foundNote = $todoDB->getTodoByID($todoIDForUpdate);

            if (!$foundNote) {
                throw new Exception("Todo Not Found", 1);
            }

            if ( $foundNote['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $deleteResult = $todoDB->deleteNote($todoIDForUpdate);
            if (!$deleteResult) {
                throw new Exception("Error On Delete note", 1);
            }

            return [
                'success' => true,
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
