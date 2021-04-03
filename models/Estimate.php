<?php


class Estimate
{
    public $id;
    public $error;
    public $db;
    private $table = 'estimates';
    private $fields = array(
        "_id",
        "assignment_id",
        "category",
        "quote_price",
        "quote_currency",
        "quote_ref",
        "status",
        "status_description",
        "created_at",
        "modified_at"
    );

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll()
    {

        $sql = "SELECT estimates._id, estimates.assignment_id,estimates.category, estimates.quote_price, estimates.quote_currency, estimates.quote_ref,
                estimates.status,estimates.status_description,estimates.created_at,estimates.modified_at,
                assignments.description, assignments.client_ref, assignments._id AS assignment_id, assignments.organisation FROM estimates 
                INNER JOIN assignments ON estimates.assignment_id = assignments._id
                ORDER BY estimates.created_at DESC";
//        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
    public function fetchOne()
    {
        $sql = "SELECT estimates._id, estimates.assignment_id,estimates.category, estimates.quote_price, estimates.quote_currency, estimates.quote_ref,
                estimates.status,estimates.status_description,estimates.created_at,estimates.modified_at,
                assignments.description,assignments.client_ref, assignments._id AS assignment_id, assignments.status, assignments.organisation FROM estimates 
                INNER JOIN assignments ON estimates.assignment_id = assignments._id
                ";
        $this->db->query($sql);
//        $this->db->bind('id', $this->id);

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
            $this->error = "The requested Id is not valid";
            return false;
        }

        else{
            $sql = "DELETE FROM  $this->table  WHERE _id = :id";
            $this->db->query($sql);
            $this->db->bind('id', $this->id);
            $this->db->execute();
            return true;
        }

    }

}