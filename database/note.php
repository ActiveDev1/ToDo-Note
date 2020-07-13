<?php

class NoteDB extends Database
{
    public function __construct()
    {
        if (!$this->conn) {
            parent::__construct();
        }
    }

    public function getList($userID)
    {
        $res = $this->fetchAll(
            "SELECT * FROM `notes`
            WHERE
                `_user`=?",
            [$userID]
        );
        $jdf = new jdf;
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['tags'] = $this->getTags($res[$i]['id']);
            $res[$i]['created_at'] = $jdf->timestamp__persian($res[$i]['created_at']);
            $res[$i]['updated_at'] = $jdf->timestamp__persian($res[$i]['updated_at']);
            unset($res[$i]['_user']);
        }

        return $res;
    }

    public function create($title, $body, $tags, $userID)
    {
        $noteID = $this->insert(
            'notes',
            [
                'title' => $title,
                'body' => $body,
                '_user' => $userID,
            ]
        );

        $this->createTags($tags, $noteID);

        return $noteID;
    }

    private function createTags($tags, $noteID)
    {
        foreach ($tags as $each) {
            $this->insert(
                'notes__tags',
                [
                    '_note' => $noteID,
                    'text' => $each,
                ]
            );
        }
    }

    public function getNoteByID($ID)
    {
        return $this->fetch(
            "SELECT * FROM `notes` WHERE `id`=?",
            [$ID]
        );
    }

    public function deleteNote($ID)
    {
        $deleteResult = $this->query(
            "DELETE FROM `notes` WHERE `id`=?",
            [$ID]
        );
        $this->deleteNoteTags($ID);

        return $deleteResult;
    }

    private function deleteNoteTags($noteID)
    {
        $this->query(
            "DELETE FROM `notes__tags` WHERE `_note`=?",
            [$noteID]
        );
    }

    public function updateSecretKey($noteID, $secretKey)
    {
        return $this->query(
            "UPDATE `notes`
            SET
                `secret`=?
            WHERE
                `id`=?",
            [
                $secretKey,
                $noteID,
            ]
        );
    }

    public function getNoteBySecret($secret)
    {
        $res = $this->fetch(
            "SELECT * FROM `notes` WHERE `secret`=?",
            [$secret]
        );
        if (!$res) {
            return $res;
        }
        $res['tags'] = $this->getTags($res['id']);

        return $res;
    }

    private function getTags($noteID)
    {
        return $this->fetchColumn(
            "SELECT `text` FROM `notes__tags` WHERE `_note`=?",
            [$noteID]
        );
    }

    public function updateNote($noteIDForUpdate, $title, $noteBody, $tags)
    {
        $this->query(
            "UPDATE `notes`
            SET
                `title`=?,
                `body`=?
            WHERE
                `id`=?",
            [
                $title,
                $noteBody,
                $noteIDForUpdate,
            ]
        );

        $this->deleteNoteTags($noteIDForUpdate);
        $this->createTags($tags, $noteIDForUpdate);
    }
}
