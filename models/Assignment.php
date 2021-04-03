<?php


class Assignment
{
    public $id;
    public $error;
    public $db;
    private $table = 'assignments';
    private $fields = array(
        "_id",
        "category",
        "organisation",
        "description",
        "client_ref",
        "type",
        "slug",
        "publish_date",
        "closing_date",
        "status",
        "status_description",
        "submission_date",
        "document_src_type",
        "document_src_email",
        "document_fees",
        "document_fees_currency",
        "bid_bond",
        "bid_bond_currency",
        "awarded_on",
        "awarded_to",
        "awarded_price",
        "created_at",
        "modified_at"
    );

    private $fieldsToCheck = array(
        "document_src_type",
        "document_src_email",
        "document_fees",
        "document_fees_currency",
        "bid_bond",
        "bid_bond_currency",
    );

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
        $sql = "SELECT * FROM $this->table WHERE _id = :id";
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
            //PREVENT ID FROM UPDATING by REMOVING IT FROM FIELDS
            array_shift($this->fields);

            //EVALUATE IF THE DOCUMENT SOURCE FIELDS ARE AVAILABLE OR SEND THEM AS NULL
            foreach ($this->fieldsToCheck as $field){
                if (!array_key_exists($field, $data)) {
                    //ADD THEM AS NULL
                    $data[$field] = null;
                }
            }

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

            //EVALUATE IF THE DOCUMENT SOURCE FIELDS ARE AVAILABLE OR SEND THEM AS NULL
            while ($i < count($this->fieldsToCheck)) {
                if (!array_key_exists($this->fieldsToCheck[$i], $data)) {
                    //ADD THEM AS NULL
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