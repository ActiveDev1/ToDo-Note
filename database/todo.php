<?php
class TODO_DB extends Database
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
            "SELECT * FROM `todo`
            WHERE `_user` = ?",
            [$userID]
        );

        $jdf = new jdf;
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['due_date'] = $jdf->timestamp__persian($res[$i]['due_date']);
            $res[$i]['created_at'] = $jdf->timestamp__persian($res[$i]['created_at']);
            $res[$i]['updated_at'] = $jdf->timestamp__persian($res[$i]['updated_at']);
            unset($res[$i]['_user']);
        }

        return $res;
    }

    public function create($text, $due_date, $userID)
    {
        $todoID = $this->insert(
            'todo',
            [
                'text' => $text,
                'due_date' => $due_date,
                '_user' => $userID,
            ]
        );

        return $todoID;
    }

    public function getTodoByID($ID)
    {
        return $this->fetch(
            "SELECT * FROM `TODO` WHERE `id`=?",
            [$ID]
        );
    }

    public function updateNote($todoIDForUpdate, $text, $due_date)
    {
        $this->query(
            "UPDATE `todo`
            SET
                `text`=?,
                `due_date`=?
            WHERE
                `id`=?",
            [
                $text,
                $due_date,
                $todoIDForUpdate,
            ]
        );
    }

    public function deleteNote($ID)
    {
        $deleteResult = $this->query(
            "DELETE FROM `todo` WHERE `id`=?",
            [$ID]
        );
    
        return $deleteResult;
    }
}
