<?php
class Database{
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        // Set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO instance
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch(PDOException $e){
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function id_is_valid($table,$id){
        $sql = "SELECT * FROM prospects WHERE _id = '89gedf4c-4'";
        $this->stmt = $this->dbh->query($sql);
//        $this->stmt->bindParam('id',$id);
        $this->stmt->execute();
        print_r($this->stmt->fetch(PDO::FETCH_ASSOC));
//        return $this->stmt->rowCount();
    }


    // Prepare statement with query
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null){
        if(is_null($type)){
            switch(true){
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
        return $this->stmt->execute();
    }
    // Get result set as array of objects
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Get single record as object
    public function single(){
        $this->execute();
        if($this->stmt->rowCount() === 0){
            return false;
        }
        else{
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }
    }
    //GET NUMBER OF MATCHED ROWS
    public function rowCount(){
        $this->execute();
        $x = $this->stmt->rowCount();
        return $x;
    }
}