<?php


class Prospect
{
    public $id;
    public $error;
    private $db;
    private $table = 'prospects';
    private $fields = array(
        "_id",
        "organisation",
        "description",
        "client_ref",
        "type",
        "document_fees",
        "publish_date",
        "closing_date",
        "created_at"
    );

    private function checkIdValidity(){
        $sql = "SELECT _id FROM $this->table WHERE _id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $this->id);

        print_r($this->db->single());
    }


    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll()
    {

        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $this->db->query($sql);

        return $this->db->resultSet();
    }

    public function fetchOne()
    {
        echo $this->checkIdValidity();
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE _id = :id';
        $this->db->query($sql);
        $this->db->bind('id', $this->id);

        return $this->db->single();

    }
    public function deleteOne()
    {

        $sql = 'DELETE FROM ' . $this->table . ' WHERE _id = :id';
        $this->db->query($sql);
        $this->db->bind('id', $this->id);

        try {
             echo $this->db->execute();
        }
        catch (PDOException $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function addOne($data)
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
        printf($sql);

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

        //CREATE AN UPDATE STRING
        $update_string = "";

        foreach ($this->fields as $field){
            $update_string = $update_string."$field = :$field, ";
        }

        $update_string = substr_replace($update_string,"",-2);

        $sql = "UPDATE $this->table SET $update_string WHERE _id = '".$this->id."'";

        echo $sql;
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