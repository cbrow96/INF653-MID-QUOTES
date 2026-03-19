<?php
    class Author{
        ///author params
        public $conn;
        public $table = 'authors';
        public $id;
        Public $author;

        //constructor for creating a connection with DB instance
        public function __construct($db){
            $this->conn = $db;
        }

        //read all data with given id
        public function read() {
            $query = 'SELECT * FROM ' . $this->table . ' ORDER BY id DESC';

            $stmt = $this->conn->prepare($query);

            $stmt->execute();

            return $stmt;
        }

        //read single data with given id
        public function read_single() {
            $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);     //bind id to variable id in query

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);      //create associative array with data recieved from stmt

            //check if data exist, set author value if true.
            if(isset($row['id']) && isset($row['author'])){
                $this->author = $row['author'];
            }
        }

        //post new data onto DB
        public function create() {
            $query = 'INSERT INTO ' . $this->table . ' (author) VALUES (:author)';

            $stmt = $this->conn->prepare($query);

            $this->author = htmlspecialchars(strip_tags($this->author));        //sanatize statement

            $stmt->bindParam(':author', $this->author);     //bind given author to :author in query

            //if succefully exicutes, retur true. for error handling purposes
            if($stmt->execute()){
                return true;
            }

            //if execute unsuccessful, print error message and return false.
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
        
        //update value based on id given
        public function update() {
            $query = 'UPDATE ' . $this->table . ' SET author = :author WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            //sanatize and assign data.
            $this->author = htmlspecialchars(strip_tags($this->author));
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind params to query
            $stmt->bindParam(':author', $this->author);
            $stmt->bindParam(':id', $this->id);

            //attempt execute, return true if successful
            if($stmt->execute()){
                return true;
            }

            //print error and return false if execute unsuccessful
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        //delete data base on given id
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));    //sanatize andd asign data

            $stmt->bindParam(':id', $this->id);     //bind data to query

            //attempt exicute, return true if successful
            if($stmt->execute()){
                return true;
            }

            //print error message and return false if execute unsuccessful
            printf("Error: %s.\n", $stmt->error);
            return false;

        }
    }
?>