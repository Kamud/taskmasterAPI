<?php

include_once '../utilities/Populater.php';

class Pinned
{
    public $id;
    public $error;
    public $db;
    private $table = 'pinned';
    private $fields = array(
        "_id",
        "resource_id",
        "resource_category",
        "pinned_at",
    );

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchAll($filter = false)
    {
        //CHECK IF FILTER IS SET
        if($filter){
            $key = $filter->key;
            $value = $filter->value;
            $sql= "SELECT _id AS pinned_item_id, resource_id, resource_category,pinned_at FROM $this->table WHERE $key = :value ORDER BY pinned_at DESC ";
        }
        else{
            $sql= "SELECT _id AS pinned_item_id, resource_id, resource_category,pinned_at FROM $this->table ORDER BY pinned_at DESC ";
        }


        $this->db->query($sql);
        $filter && $this->db->bind('value',$value);
        //GET THE INITIAL RESULT OF PINNED ITEMS ONLY
        $firstBatch = $this->db->resultSet();

        //PRE CREATE A RESULT ARRAY BATCH
        $populater = new Populater();
        $result = array();

        foreach ($firstBatch as $item){
            $populatedItem = $populater->fetchResource($item->resource_id,$item->resource_category);
            $mergedObject = (object)array_merge((array)$item,(array)$populatedItem);

            //DELETE THE ID FIELD FROM THE MERGED OBJECT
            unset($mergedObject->_id);
            array_push($result,$mergedObject);
        }

        return $result;


    }
    public function fetchOne()
    {
        $sql = "SELECT * FROM $this->table WHERE _id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $this->id);

        return $this->db->single();

    }    public function fetchByResourceId()
    {
        $sql = "SELECT * FROM $this->table WHERE resource_id = :id";
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
            $this->error = "The requested Id ($this->id) is not valid";
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
    public function deleteByResourceId($resource_id)
    {
        $sql = "DELETE FROM  $this->table  WHERE resource_id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $resource_id);
        return $this->db->execute();

    }
}