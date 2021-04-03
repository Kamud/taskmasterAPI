<?php


class Task
{
    public $id;
    public $error;
    public $db;
    private $table = 'tasks';
    private $fields = array(
        "_id",
        "description",
        "status",
        "status_description",
        "assigned_user_id",
        "closing_date",
        "created_at",
        "modified_at"
    );

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll()
    {
        $sql = "SELECT tasks._id, tasks.description, tasks.category,tasks.status_description, tasks.status, tasks.assigned_user_id ,tasks.closing_date,
                tasks.created_at,tasks.modified_at,
                users.user_name AS assigned_user_name, users.email AS assigned_user_email,users._id AS assigned_user_id FROM tasks
                INNER JOIN users ON tasks.assigned_user_id = users._id
                ORDER BY tasks.created_at DESC";

//        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    public function fetchOne()
    {
        $sql = "SELECT tasks._id, tasks.description,tasks.category,tasks.status_description, tasks.status, tasks.assigned_user_id, tasks.closing_date,
                tasks.created_at,tasks.modified_at,
                users.user_name AS assigned_user_name, users.email AS assigned_user_email,users._id AS assigned_user_id FROM tasks
                INNER JOIN users ON tasks.assigned_user_id = users._id
                WHERE tasks._id = :id";

//        $sql = "SELECT * FROM $this->table WHERE _id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $this->id);

        return $this->db->single();

    }
    public function createOne($data)
    {
        //check if fields exist and prepare to execute query for only available fields
        $i = 0;
        $length = count($this->fields);
        while ($i < $length) {
            if (!array_key_exists($this->fields[$i], $data)) {
                //REMOVE THE FIELD FROM THE FIELDS ARRAY
                unset($this->fields[$i]);
            }
            $i++;
        }

        $sql = "INSERT into $this->table (".(implode(',',$this->fields)).") VALUES (:".implode(',:',$this->fields).")";
        $this->db->query($sql);

        foreach ($this->fields as $field) {
            $this->db->bind($field, $data[$field]);
        }

        try {
            $this->db->execute();
            return true;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
    public function upateOne($data)
    {
        //CHECK IF ID IS VALID FIRST
        $id_is_valid = $this->db->check_id($this->table,$this->id);

        if(!$id_is_valid){
            $this->error = "The requested Id is not valid";
            return false;
        }
        else{
            //UPDATE THE DOCUMENT
            //check if fields exist and prepare to execute query for only available fields
            $i = 0;

            //PREVENT ID FROM UPDATING by REMOVING IT FROM FIELDS
            array_shift($this->fields);

            $length = count($this->fields);
            while ($i < $length) {
                if (!array_key_exists($this->fields[$i], $data)) {
                    //REMOVE THE FIELD FROM THE FIELDS ARRAY
                    unset($this->fields[$i]);
                }
                $i++;
            }



            //CREATE AN UPDATE STRING
            $update_string = "";

            foreach ($this->fields as $field){
                $update_string = $update_string."$field = :$field, ";
            }
            //REMOVE COMMA AT END OF STRING
            $update_string = substr_replace($update_string,"",-2);

            //RUN QUERY
            $sql = "UPDATE $this->table SET $update_string WHERE _id = '".$this->id."'";
            $this->db->query($sql);

            //BIND PARAMETER
            foreach ($this->fields as $field) {
                $this->db->bind($field, $data[$field]);
            }

            try {
                $this->db->execute();
                return true;
            } catch (PDOException $e) {
                $this->error = $e->getMessage();
                return false;
            }

        }
    }
    public function deleteOne()
    {
        $id_is_valid = $this->db->check_id($this->table,$this->id);

        if(!$id_is_valid){
            echo "Id is not valid";
            $this->error = "The requested Id ($this->id) is not valid and could not be deleted";
            return false;
        }

        else{
            echo "attempting to delete document";
            $sql = "DELETE FROM  $this->table  WHERE _id = :id";
            $this->db->query($sql);
            $this->db->bind('id', $this->id);
            $this->db->execute();
            return true;
        }

    }

}