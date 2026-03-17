<?php
    class Database {
        //DB params
        private $conn;
        private $host ;
        private $db_name;
        private $username;
        private $password;
        private $port;

        public function __construct(){
            $this-> username = getenv('USERNAME');
            $this-> password = getenv('PASSWORD');
            $this-> port = getenv('PORT');
            $this-> db_name = getenv('DBNAME');
            $this-> host = getenv('HOST');
        }

        public function connect(){
            if($this-> conn){
                return $this-> conn;
            }else{
                $dsn = "pgsql:host={$this-> host};port={$this-> port}; dbname={$this-> db_name}";
                try{
                    $this-> conn = new PDO($dsn, $this-> username, $this-> password);
                    $this-> conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return $this-> conn;
                }catch(PDOException $e){
                    echo 'Connection Error: ' . $e->getMessage();
                }
            }
        }
    }
?>