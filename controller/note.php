<?php

class NoteController {

    public function list() {
        try {
            global $auth;
            $foundUser = $auth->authenticate();
            $noteDB = new NoteDB;
            $notesList = $noteDB->getList($foundUser['id']);

            return [
                'success' => true,
                'data' => $notesList,
            ];
        } catch (\Throwable $th) {
            $error = $th->getMessage();

            return [
                'success' => false,
                'error' => $error,
            ];
        }
    }

    public function create() {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();
            // gather notes data
            $title = $body['title'];
            $noteBody = $body['body'];
            $tags = $body['tags'] ? : [];
            if (!is_array($tags)) {
                throw new Exception("Tags should be array", 1);
            }

            if (!$title) {
                throw new Exception("Title can't be empty", 1);
            }

            $noteDB = new NoteDB;

            $createResult = $noteDB->create($title, $noteBody, $tags, $foundUser['id']);
            if (!$createResult) {
                throw new Exception("Error on saving note", 1);
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

    public function updateNote() {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            $noteIDForUpdate = $body['id'];
            $title = $body['title'];
            $noteBody = $body['body'];
            $tags = $body['tags'] ? : [];
            if (!is_array($tags)) {
                throw new Exception("Tags should be array", 1);
            }

            if (!$title) {
                throw new Exception("Title can't be empty", 1);
            }

            if (!$noteIDForUpdate) {
                throw new Exception("Invalid ID", 1);
            }

            $noteDB = new NoteDB;
            $foundNote = $noteDB->getNoteByID($noteIDForUpdate);

            if (!$foundNote) {
                throw new Exception("Note Not Found", 1);
            }

            if ( $foundNote['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $noteDB->updateNote($noteIDForUpdate, $title, $noteBody, $tags);

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

            // gather notes data
            $noteIDForDelete = $body['id'];
            if (!$noteIDForDelete) {
                throw new Exception("Invalid ID", 1);
            }

            $noteDB = new NoteDB;
            $foundNote = $noteDB->getNoteByID($noteIDForDelete);

            if (!$foundNote) {
                throw new Exception("Note Not Found", 1);
            }

            if ( $foundNote['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $deleteResult = $noteDB->deleteNote($noteIDForDelete);
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

    public function generateShareURL($noteID) {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            // gather notes data
            $noteIDForShare = $noteID;
            if (!$noteIDForShare) {
                throw new Exception("Invalid ID", 1);
            }

            $noteDB = new NoteDB;
            $foundNote = $noteDB->getNoteByID($noteIDForShare);

            if (!$foundNote) {
                throw new Exception("Note Not Found", 1);
            }

            if ( $foundNote['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $util = new Utility;

            $secretKey = $util->randomStringGenerator_alphabet_num(256);
            $updateResult = $noteDB->updateSecretKey($noteIDForShare, $secretKey);
            if (!$updateResult) {
                throw new Exception("Error On Share note", 1);
            }

            return [
                'success' => true,
                'data' => [
                    'secret' => $secretKey,
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

    public function deleteShareURL($noteID) {
        try {
            global $auth, $body;
            $foundUser = $auth->authenticate();

            // gather notes data
            $noteIDForShare = $noteID;
            if (!$noteIDForShare) {
                throw new Exception("Invalid ID", 1);
            }

            $noteDB = new NoteDB;
            $foundNote = $noteDB->getNoteByID($noteIDForShare);

            if (!$foundNote) {
                throw new Exception("Note Not Found", 1);
            }

            if ( $foundNote['_user'] != $foundUser['id'] ) {
                throw new Exception("Access Denied", 1);
            }

            $util = new Utility;

            $updateResult = $noteDB->updateSecretKey($noteIDForShare, null);
            if (!$updateResult) {
                throw new Exception("Error On Share note", 1);
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

    public function getSharedNote($secret) {
        try {
            if (strlen($secret) < 256) {
                throw new Exception("Invalid Secret", 1);
            }
            $noteDB = new NoteDB;
            $noteData = $noteDB->getNoteBySecret($secret);

            if (!$noteData) {
                throw new Exception("Note not found", 1);
            }

            return [
                'success' => true,
                'data' => $noteData,
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
