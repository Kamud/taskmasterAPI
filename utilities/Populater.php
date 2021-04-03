<?php
class Populater{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function fetchResource($resource_id,$resource_category)
    {
        $sql = "SELECT * FROM $resource_category WHERE _id = :id";
        $this->db->query($sql);
        $this->db->bind('id', $resource_id);

        return $this->db->single();

    }
}