<?php
    class Quote{
        //Quote params
        public $conn;
        public $table = 'quotes';

        public $id;
        public $quote;
        public $author_id;
        public $category_id;
        public $author;
        public $category;

        //constructor for creating a connection with DB instance
        public function __construct($db){
            $this->conn = $db;
        }

        //read all data with given id data
        public function read(){
            $query = 'SELECT 
                        q.id,
                        q.quote,
                        a.author,
                        c.category
                    FROM
                    ' . $this->table . ' q
                    LEFT JOIN
                        authors a ON q.author_id = a.id
                    LEFT JOIN
                        categories c ON q.category_id = c.id
                    WHERE 1 = 1';
            $id_array = [];

            // Check for author_id
            if(!empty($this->author_id)) {
                $query .= ' AND q.author_id = :author_id';
                $id_array[':author_id'] = $this->author_id;
            }

            // Check for category_id
            if (!empty($this->category_id)) {
                $query .= ' AND q.category_id = :category_id';
                $id_array[':category_id'] = $this->category_id;
            }

            $stmt = $this->conn->prepare($query);

            //bind all given variables to query
            foreach($id_array as $key => $value){
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt;
        }

        //read single data with given id
        public function read_single() {
            $query = 'SELECT 
                        q.id,
                        q.quote,
                        a.author,
                        c.category
                    FROM
                    ' . $this->table . ' q
                    LEFT JOIN
                        authors a ON q.author_id = a.id
                    LEFT JOIN
                        categories c ON q.category_id = c.id
                    WHERE q.id = ? LIMIT 1';

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(1, $this->id);     //bind id to variable id in query

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);      //create associative array with data recieved from stmt

            //check if data exist, set values if true.
            if(isset($row['id']) && isset($row['quote'])){
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            }
        }

        //post new data onto DB
        public function create() {
            $query = 'INSERT INTO ' . $this->table. ' (quote, category_id, author_id)
            VALUES
                (:quote,
                :author_id,
                :category_id)';
            
             $stmt = $this->conn->prepare($query);

             //sanatize statement and asign variables
             $this->quote = htmlspecialchars(strip_tags($this->quote));
             $this->author_id = htmlspecialchars(strip_tags($this->author_id));
             $this->category_id = htmlspecialchars(strip_tags($this->category_id));

             //bind values to query
             $stmt->bindParam(':quote', $this->quote);
             $stmt->bindParam(':author_id', $this->author_id);
             $stmt->bindParam(':category_id', $this->category_id);

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
            $query = 'UPDATE ' . $this->table. ' SET 
                id = :id,
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
                WHERE id = :id';
            
            $stmt = $this->conn->prepare($query);

            //sanatize and assign data.
            $this->quote = htmlspecialchars(strip_tags($this->quote));
            $this->author_id = htmlspecialchars(strip_tags($this->author_id));
            $this->category_id = htmlspecialchars(strip_tags($this->category_id));
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':quote', $this->quote);
            $stmt->bindParam(':author_id', $this->author_id);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);

            //attempt execute, return true if successful
            if($stmt->execute()){
                if ($stmt->rowCount()==0){
                    return false;
                }
                else{
                    return true;
                }
            } else {

            //print error message and return false if execute unsuccessful
            printf("Error: %s.\n", $stmt->error);
            return false;
            }
        }

        //delete data base on given id
        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));    //sanatize and asign data

            $stmt->bindParam(':id', $this->id);     //bind data to query

            //attempt execute, return true if successful
            if($stmt->execute()){
                if ($stmt->rowCount() == 0){
                    return false;
                } else {
                    return true;
                }
            } else {

            //print error message and return false if execute unsuccessful
            printf("Error: %s.\n", $stmt->error);
            return false;
            }
        }
    }
?>