<?php
    class Quote{
        public $conn;
        public $table = 'quotes';

        public $id;
        public $quote;
        public $author_id;
        public $category_id;
        public $author;
        public $category;

        public function __construct($db){
            $this->conn = $db;
        }

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

            foreach($id_array as $key => $value){
                $stmt->bindValue($key, $value);
            }

            if($stmt->execute();){
                return true;
            }

            printf("Error: %s.\n", stmt->error);
            return false;
        }

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

            $stmt->bindParam(1, $this->id);

            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if(isset($row['id'])&& isset($row['quote'])){
                $this->id = $row['id'];
                $this->quote = $row['quote'];
                $this->author = $row['author'];
                $this->category = $row['category'];
            }
        }

        public function create() {
            $query = 'INSERT INTO ' . $this->table. ' (quote, category_id, author_id)
            VALUES
                (:quote,
                :author_id,
                :category_id)';
            
             $stmt = $this->conn->prepare($query);

             $this->quote = htmlspecialchars(strip_tags($this->quote));
             $this->author_id = htmlspecialchars(strip_tags($this->author_id));
             $this->category_id = htmlspecialchars(strip_tags($this->category_id));

             $stmt->bindParam(':quote', $this->quote);
             $stmt->bindParam(':author_id', $this->author_id);
             $stmt->bindParam(':category_id', $this->category_id);

            if($stmt->execute()){
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function update() {
            $query = 'UPDATE ' . $this->table. ' SET 
                quote = :quote,
                author_id = :author_id,
                category_id = :category_id
                WHERE id = :id';
            
             $stmt = $this->conn->prepare($query);

             $this->quote = htmlspecialchars(strip_tags($this->quote));
             $this->author_id = htmlspecialchars(strip_tags($this->author_id));
             $this->category_id = htmlspecialchars(strip_tags($this->category_id));
             $this->id = htmlspecialchars(strip_tags($this->id));

             $stmt->bindParam(':quote', $this->quote);
             $stmt->bindParam(':author_id', $this->author_id);
             $stmt->bindParam(':category_id', $this->category_id);
             $stmt->bindParam(':id', $this->id);

            if($stmt->execute()){
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function delete() {
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()){
                return true;
            }

            printf("Error: %s.\n", $stmt->error);
            return false;

        }

    }
?>